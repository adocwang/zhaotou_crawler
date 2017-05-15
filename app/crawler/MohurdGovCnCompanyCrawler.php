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

class MohurdGovCnCompanyCrawler extends BaseCrawler
{
    function __construct($urlRaw)
    {

        $this->redis = ClientFactory::create([
            'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
            'timeout' => 2
        ]);
        $this->page = $this->redis->get('MohurdGovCnCompanyCrawlerPage');
        if (empty($this->page) || $this->page == 0) {
            $this->redis->set('MohurdGovCnCompanyCrawlerPage', 874);
            $this->page = $this->redis->get('MohurdGovCnCompanyCrawlerPage');
        }
        parent::__construct($urlRaw);
    }

    function getContent()
    {
        $this->body = $this->doRequest($this->url);
        try {
            $jsonObj = \GuzzleHttp\json_decode($this->body);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            throw new \Exception('json error1!');
        }
        $this->content = $jsonObj->tb;
        return '<table>' . $this->content . '</table>';
    }

    function hasNew()
    {
        // TODO:是否有新的内容
    }

    function saveCompany($corpid)
    {
        $url = 'http://210.12.219.18/jianguanfabuweb/company_details.aspx?corpid=' . $corpid;
        $html = $this->doRequest($url);
        $dom = \QueryPath::withHTML5($html);
        $contentDom = $dom->find('.content');
        $compInfo = $this->getBasicInfo($contentDom);
        $compInfo['sourceData'] = $dom->find('#Hidden101')->attr('value');
        $compInfo['corpid'] = $corpid;
        $compInfo['updateTime'] = time();
        $compInfo['zizhi'] = $this->getZizhi($contentDom);
        $compInfo['zizhiUpdateTime'] = time();
        $compInfo['engineerInfo'] = $this->getEngineer($compInfo['sourceData']);//engineer的corpid是sourceData
        $compInfo['engineerUpdateTime'] = time();
//        print_r($compInfo);exit;
        $collection = (new \MongoDB\Client('mongodb://localhost:27017'))->build_info->companies;
        try {
            $has = $collection->findOne(['CorpName' => $compInfo['CorpName']]);
            if (!$has) {
                $result = $collection->insertOne($compInfo);
            } else {
                $result = $collection->updateOne(
                    ['CorpName' => $compInfo['CorpName']],
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

    function getBasicInfo($contentDom)
    {
        $basicInfo = [];
        $basicInfo['CorpName'] = trim($contentDom->find("#CorpName")->text());
        $basicInfo['LicenseNum'] = trim($contentDom->find("#LicenseNum")->text());
        $basicInfo['CorpCode'] = trim($contentDom->find("#CorpCode")->text());
        $basicInfo['LegalMan'] = trim($contentDom->find("#LegalMan")->text());
        $basicInfo['EconType'] = trim($contentDom->find("#EconType")->text());
        $basicInfo['Province'] = trim($contentDom->find("#Province")->text());
        $basicInfo['Address'] = trim($contentDom->find("#Address")->text());
        $basicInfo['Description'] = trim($contentDom->find("#Description")->text());
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
        $zizhiDoms = $contentDom->find('.zizhi');
        foreach ($zizhiDoms as $zizhiDom) {
            $zizhi = [];
            $imgSrc = $zizhiDom->find('.certificate_img_show')->find('img')->attr('src');
//            print_r($imgSrc);
            $fieldsText = $zizhiDom->text();
            if (preg_match_all('/\d{4}-\d{2}-\d{2}/', $fieldsText, $match)) {
                $dates = [];
                foreach ($match[0] as $date) {
                    $dates[] = strtotime($date);
                }
                if ($dates[1] < $dates[0]) {
                    $dateTmp = $dates[1];
                    $dates[1] = $dates[0];
                    $dates[0] = $dateTmp;
                }
            }
            $zizhi['fromDate'] = date('Y-m-d', $dates[0]);
            $zizhi['toDate'] = date('Y-m-d', $dates[1]);
            if (empty($imgSrc)) {
                continue;
            }
            $url = new \Purl\Url($imgSrc);
            $arr = $url->query->getData();
            $zizhi['certId'] = trim($arr['certid']);
            foreach (explode(',', $arr['certrange']) as $certName) {
                $name = trim($certName);
                $cert = $this->explainCert($name);
                $zizhi['certName'] = $cert['name'];
                $zizhi['certLevel'] = $cert['level'];
                $zizhis[] = $zizhi;
            }
        }
        return $zizhis;
//        print_r($zizhis);
    }

    function getEngineer($corpid)
    {
        $url = 'http://210.12.219.18/jianguanfabuweb/handler/Company_Details_CertifiedEngineers.ashx?method=getStaff&corpid=' . $corpid;
        $json = $this->doRequest($url);
        $pos = mb_strpos($json, '{"tb":"');
        $json = mb_substr($json, $pos);
        try {
            $jsonObj = json_decode($json);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            throw new \Exception('json error2!');
        }
        $html = '<div>' . $jsonObj->tb . '</div>';
        $dom = \QueryPath::withHTML5($html);
        $trs = $dom->find('.company_details_engineers_list')->find('tr');
        $key = 0;
        $engineers = [];
        foreach ($trs as $tr) {
            $engineer = [];
            $tds = $tr->find('td');
            if ($tds->length == 0) {
                continue;
            }
            $name = $tds->eq(1);
            $engineer['name'] = $name->find('a')->text();
            $url = new \Purl\Url($name->find('a')->attr('href'));
            $arr = $url->query->getData();
            $engineer['personId'] = urlencode($arr['personid']);
            $engineer['idCardId'] = $tds->eq(2)->text();
            $cert = $this->explainCert($tds->eq(3)->text());
            $engineer['certName'] = $cert['name'];
            $engineer['certLevel'] = $cert['level'];
            $engineer['licenceNumber'] = $tds->eq(4)->text();
            $engineer['stampNumber'] = $tds->eq(5)->text();
            $engineer['fromDate'] = strtotime(trim($tds->eq(6)->text()));
            $engineer['toDate'] = strtotime(trim($tds->eq(7)->text()));
            $engineers[] = $engineer;
        }
        return $engineers;
    }

    function savePage()
    {
        $this->getContent();
        $lines = [];
        $trs = \QueryPath::withHTML5($this->content, 'tr');
        $lineNum = 0;
        foreach ($trs as $tr) {
            $url = new \Purl\Url($tr->find('.company_list_company_name')->find('a')->attr('href'));
            $arr = $url->query->getData();
            $compDetail = $this->saveCompany(urlencode($arr['corpid']));
            echo ++$lineNum . '|';
            $lines[] = $compDetail;
        }
        if (!empty($lines)) {
            return $lines;
        }
        return false;
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

    function moveToNext()
    {
        try {
            $jsonObj = json_decode($this->body);
        } catch (\Exception $e) {
            echo $e->getMessage() . "\n";
            throw new \Exception('json error3!');
        }
        if ($this->page < $jsonObj->PageCount) {
            $this->page = $this->redis->incr('MohurdGovCnCompanyCrawlerPage');
            $this->url = str_replace('{page}', $this->page, $this->urlRaw);
            return true;
        }
        return false;
    }
}