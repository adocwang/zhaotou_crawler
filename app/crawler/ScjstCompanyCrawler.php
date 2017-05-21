<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-11-19
 * Time: 20:56
 */

namespace BuildInfo\crawler;

use RedisClient\RedisClient;
use RedisClient\ClientFactory;

class ScjstCompanyCrawler extends BaseCrawler
{
    public $bodyQuery;
    protected $companiesInPage = 0;
    public $useproxy = true;
    protected $maybeEnd=false;

    function __construct($urlRaw)
    {
        $this->redis = ClientFactory::create([
            'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
            'timeout' => 2
        ]);
        $this->page = $this->redis->get(__CLASS__);
        if (empty($this->page) || $this->page == 0) {
            $this->redis->set(__CLASS__, 1);
            $this->page = $this->redis->get(__CLASS__);
        }
        parent::__construct($urlRaw);
    }

    function getContentAndSaveToBody($url)
    {
        $this->body = $this->doRequest($url);
        return $this->body;
    }

    function doRequest($url = '', $postData = [])
    {
        return parent::doRequest($url, $postData); // TODO: Change the autogenerated stub
    }

    function hasNew()
    {
        // TODO:是否有新的内容
    }

    function saveCompany($id)
    {
//        $this->getEngineer($id);//for test
        $collection = $this->mongoConnection->build_info1->company;
        //判断这个人的ID是否最近才抓过
        $companyInDb = $collection->findOne(['siteId' => $id]);
        $companyNeedUpdate = true;
        if (!empty($companyInDb)) {
            if ($companyInDb->updateTime > (time() - 3600 * 24 * 60)) {
                $compInfo = $companyInDb;
                $companyNeedUpdate = false;
            }
        }
//        $companyNeedUpdate = true;//for debug

        $personNeedUpdate = true;
        if (!empty($companyInDb) && !empty($companyInDb->personUpdateTime)) {
            if ($companyInDb->personUpdateTime > (time() - 3600 * 24 * 7)) {
                $personNeedUpdate = false;
            }
        }

        $personNeedUpdate = false;//不爬人员

        if ($companyNeedUpdate) {
            $url = 'http://www.scjst.gov.cn:8081/QueryInfo/Ente/EnteZsxx.aspx?id=' . $id;
            $html = $this->doRequest($url);
            $dom = \QueryPath::withHTML5($html);
            if ($dom->find('input#txtCode')->length > 0) {//遇到验证码了
                echo "gotCheckCode!\n";
                return $this->saveCompany($id);
            }
            $contentDom = $dom->find('table.list');
            $compInfo = $this->getBasicInfo($contentDom->eq(0));
//            if (empty($compInfo['compName'])) {
//                echo $html;
//                exit;
//            }
            $id = strtolower($id);
            $compInfo['siteId'] = $id;
            $compInfo['updateTime'] = time();
            $compInfo['zizhi'] = $this->getZizhi($dom->find('#mainContent_jzsgzz'));
            $compInfo['zizhiUpdateTime'] = time();
        }

        if ($personNeedUpdate) {
            $compInfo['person'] = [];
        }
        try {
            $has = $collection->findOne(['siteId' => $id]);
            if (!$has) {
                $result = $collection->insertOne($compInfo);
            } else {
//                print_r($compInfo);exit;
                $result = $collection->updateOne(
                    ['siteId' => $compInfo['siteId']],
                    ['$set' => $compInfo]
                );
            }
            if ($personNeedUpdate) {
                $this->getEngineer($id);
                $compInfo['personUpdateTime'] = time();
                $result = $collection->updateOne(
                    ['siteId' => $compInfo['siteId']],
                    ['$set' => $compInfo]
                );
            }
            if ($result) {
                return true;
            }
        } catch (\MongoDB\Driver\Exception\BulkWriteException $e) {
            return false;
        }
    }

    function trs2pair($trs)
    {
        $pairs = [];
        foreach ($trs as $tr) {
            $tds = $tr->find('td');
            if ($tds->length == 2 || $tds->length == 3) {
                $pairs[] = $this->chineseField2en($tds->eq(0)->text(), $tds->eq(1)->text());
            } elseif ($tds->length == 4 || $tds->length == 5) {
                $pairs[] = $this->chineseField2en($tds->eq(0)->text(), $tds->eq(1)->text());
                $pairs[] = $this->chineseField2en($tds->eq(2)->text(), $tds->eq(3)->text());
            } elseif ($tds->length == 6 || $tds->length == 7) {
                $pairs[] = $this->chineseField2en($tds->eq(0)->text(), $tds->eq(1)->text());
                $pairs[] = $this->chineseField2en($tds->eq(2)->text(), $tds->eq(3)->text());
            }
        }
        return $pairs;
    }

    function getBasicInfo($contentDom)
    {
        $trs = $contentDom->find("tr");
        $pairs = $this->trs2pair($trs);
        $basicInfo = [];
        foreach ($pairs as $pair) {
            if (!empty($pair['name']) || !empty($pair['value'])) {
                $basicInfo[($pair['name'])] = $pair['value'];
            }
        }
        return $basicInfo;
    }

    /**
     * 解析资质信息
     * @param $contentDom
     * @return array
     */
    function getZizhi($contentDom)
    {
        $zizhis = [];
        $trs = $contentDom->find('tr');
        $zizhi = [];
        $pairs = $this->trs2pair($trs);
//        print_r($pairs);exit;
        foreach ($pairs as $pair) {
            //key和值都是空的，直接continue
            if (empty($pair['name']) || empty($pair['value'])) {
                continue;
            }


            //遇到证书编号，说明一个新的证书table要开始了，需要把当前暂存certs的先保存到zizhis里面
            if (strcmp('certNumber', $pair['name']) === 0) {
                if (!empty($zizhi)) {//保存上一个证书，并清空当前证书数据
                    if (!empty($certs)) {//一行多个资质的情况
                        foreach ($certs as $cert) {
                            $zizhi['certName'] = $cert['name'];
                            $zizhi['certLevel'] = $cert['level'];
                            $zizhis[] = $zizhi;
                        }
                        $certs = [];
                    } else {
                        $zizhis[] = $zizhi;
                    }
                    $zizhi = [];
                }
                //安许证的证书编号里面提取证书名字
                if (preg_match('/安许证/', $pair['value'])) {
                    $certs[] = ['name' => '安许证', 'level' => -2];
                }
            }

            //资质证书名字，分级
            if (strcmp('certName', $pair['name']) === 0) {
                $certs = [];
                //普通证书
                foreach ($pair['value'] as $value) {
                    $certs[] = $this->explainCert($value);
                }
                continue;
            }

            //所属省
            if (strcmp('province', $pair['name']) === 0) {
                $pair['value'] = mb_substr($pair['value'], 0, 3);
            }
            $zizhi[($pair['name'])] = $pair['value'];
        }
        if (!empty($zizhi)) {
            if (!empty($certs)) {//一行多个资质的情况
                foreach ($certs as $cert) {
                    $zizhi['certName'] = $cert['name'];
                    $zizhi['certLevel'] = $cert['level'];
                    $zizhis[] = $zizhi;
                }
                $certs = [];
            } else {
                $zizhis[] = $zizhi;
            }
            $zizhi = [];
        }
//        print_r($zizhis);
//        exit;
        return $zizhis;
    }

    function chineseField2en($chineseName, $value)
    {
        $map = [
            '企业名称' => 'compName',
            '营业执照号／统一社会信用代码' => 'licienceNumber',
            '所属地区' => 'province',
            '注 册 地 址' => 'address',
            '法 定 代 表 人' => 'legalMan',
            '企业类型' => 'compType',
            '注册资本' => 'capital',
            '成立日期' => 'startTime',
            '证书编号' => 'certNumber',
            '发证日期' => 'startTime',
            '有效期' => 'endTime',
            '资质项' => 'certName',
            '企业经理' => 'compMamager',
            '技术负责人' => 'techMamager',
            '组织机构代码或统一社会信用代码' => 'organizationCode',
        ];
        $enName = $map[trim($chineseName)];
        $value = trim($value);
        switch ($enName) {
            case 'startTime':
                $value = $this->strGetTime($value, true);
                break;
            case 'endTime':
                $value = $this->strGetTime($value, false);
                break;
            case 'certName':
                $value = explode(',', $value);
                break;
        }
        return ['name' => $enName, 'value' => $value];
    }

    function strGetTime($timeStr, $first = true)
    {
        if (preg_match_all('/\d{4}-\d{2}-\d{2}/', $timeStr, $match)) {
            if ($first) {
                return strtotime($match[0][0]);
            }
            return strtotime(end($match[0]));
        }
        return 0;
    }

    function getEngineer($compId)
    {
        $crawler1 = new ScjstPeopleCrawler($compId);
        do {
            $crawler1->savePage();
            echo "one person page finish!\n";
        } while ($crawler1->moveToNext());
    }

    function explainCert($name)
    {
        $cert = [];
        $name = trim($name);
        if (preg_match('/特级/', $name, $match)) {
            $level = 0;
            $name = str_replace($match[0], '', $name);
        } elseif (preg_match('/(一|壹)级/', $name, $match)) {
            $level = 1;
            $name = str_replace($match[0], '', $name);
        } elseif (preg_match('/(二|贰)级/', $name, $match)) {
            $level = 2;
            $name = str_replace($match[0], '', $name);
        } elseif (preg_match('/(三|叁)级/', $name, $match)) {
            $level = 3;
            $name = str_replace($match[0], '', $name);
        } elseif (preg_match('/不分等级/', $name, $match)) {
            $level = -1;
            $name = str_replace($match[0], '', $name);
        } else {
            $level = -2;
        }
        $cert['name'] = $name;
        $cert['level'] = $level;
        return $cert;
    }

    function savePage()
    {
//        $this->saveCompany('9DE6ADAF-FC9F-4AF6-A210-A6C34311DF55');// for test
        $this->getContentAndSaveToBody($this->url);
        $this->bodyQuery = \QueryPath::withHTML5($this->body);
        $this->content = $this->bodyQuery->find('.page-content')->find('.list');
        $trs = $this->content->find('tr');
        $lineNum = 0;
        foreach ($trs as $tr) {
            if (++$lineNum == 1) {
                continue;
            }
            $url = $tr->find('td')->eq(2)->find('a')->attr('href');
            $URi = new \Purl\Url($url);
            $arr = $URi->query->getData();
            $arr['id'] = strtolower($arr['id']);
            $compDetail = $this->saveCompany($arr['id']);
            echo "one company finish!\n";
            $lines[] = $compDetail;
        }
        $thisPageLines=count($lines);
        if($this->companiesInPage<1 && $thisPageLines===0){
            $this->maybeEnd=true;
        }
        $this->companiesInPage = $thisPageLines;
        if (!empty($lines)) {
            return true;
        }
        return false;
    }

    function doSomeFix()
    {
        $collection = (new \MongoDB\Client('mongodb://localhost:27017'))->build_info1->company;
        $companys = $collection->find(['compName' => ['$exists' => false]]);
        foreach ($companys as $company) {
            $siteId = $company['siteId'];
            $this->saveCompany($siteId);
        }
    }

    function moveToNext()
    {
        if ($this->maybeEnd) {
//            $this->page = $this->redis->set(__CLASS__, 0);
            return false;
        }
        $this->page = $this->redis->incr(__CLASS__);
        $this->url = str_replace('{page}', $this->page, $this->urlRaw);
        return true;
    }
}