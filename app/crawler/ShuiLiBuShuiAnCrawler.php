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

class ShuiLiBuShuiAnCrawler extends BaseCrawler
{
    public $bodyQuery;
    public $page = 1;

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
        $this->doRequest("http://www.cwun.org/cyry.php");
    }

    function getContentAndSaveToBody($url)
    {
        $this->body = mb_convert_encoding($this->doRequest($url), 'utf-8', 'gbk');
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

    function saveCompany($compInfo)
    {
//        $this->getEngineer($id);//for test
        $collection = $this->mongoConnection->build_info1->shui_an;
        try {
            $has = $collection->findOne(['certNumber' => $compInfo['certNumber']]);
            if (!$has) {
                $result = $collection->insertOne($compInfo);
            } else {
                $result = $collection->updateOne(
                    ['certNumber' => $compInfo['certNumber']],
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

    function savePage()
    {
//        $this->saveCompany('7c6c6710-4ece-43c5-8013-5dc88dd4d273');// for test
        $this->getContentAndSaveToBody($this->url);
        $this->bodyQuery = \QueryPath::withHTML5($this->body);
        $this->content = $this->bodyQuery->find('#table10');
        $trs = $this->content->find('tr');
        $lineNum = 0;
        foreach ($trs as $tr) {
            if (in_array(++$lineNum, [1, 2])) {
                continue;
            }
            if ($lineNum == $trs->length) {
                continue;
            }
            $company = [];
            $company['personName'] = trim($tr->find('td')->eq(1)->find('a')->text());
            $company['certNumber'] = trim($tr->find('td')->eq(2)->text());
            $company['endTime'] = strtotime(trim($tr->find('td')->eq(3)->text()));
            $company['compName'] = trim($tr->find('td')->eq(4)->text());
            $compDetail = $this->saveCompany($company);
            $lines[] = $compDetail;
        }
        if (!empty($lines)) {
            return true;
        }
        return false;
    }

    function moveToNext()
    {
        $nextPageContent = $this->bodyQuery->find('.altt')->find('span');
        if ($nextPageContent->length > 0 && strcmp(trim($nextPageContent->eq(0)->text()), '下一页') === 0) {
            return false;
        }
        $this->page = $this->redis->incr(__CLASS__);
        $this->url = str_replace('{page}', $this->page, $this->urlRaw);
        return true;
    }
}