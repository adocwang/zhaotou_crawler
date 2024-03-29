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

class ShuiLiBuWuDaYuanCrawler extends BaseCrawler
{
    public $bodyQuery;
    public $page = 1;
    public $useproxy = true;
    private static $mongoInstance;
    private $shuiLiBuWuDaYuanCollection;


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
        $this->shuiLiBuWuDaYuanCollection = $this->mongoConnection->build_info1->shui_li_wu_da_yuan;
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

    function savePage()
    {
//        $this->saveCompany('7c6c6710-4ece-43c5-8013-5dc88dd4d273');// for test
        $this->url = 'http://www.cwun.org/cyry_info.php?type=8&id=' . $this->page;
        $this->getContentAndSaveToBody($this->url);
        $this->bodyQuery = \QueryPath::withHTML5($this->body);
        $this->content = $this->bodyQuery->find('#table10');
        $trs = $this->content->find('tr');
        $person = [];
        $person['certNumber'] = trim($trs->eq(5)->find('td')->eq(1)->text());
        if (empty($person['certNumber'])) {
            return true;
        }
        $person['siteId'] = $this->page;
        $person['name'] = trim($trs->eq(1)->find('td')->eq(1)->text());
        $person['endTime'] = strtotime(trim($trs->eq(8)->find('td')->eq(1)->text()));
        $person['compName'] = trim($trs->eq(9)->find('td')->eq(1)->text());
        $certMajorStr = trim($trs->eq(6)->find('td')->eq(1)->text());
        $certMajors = explode(',', $certMajorStr);
        foreach ($certMajors as $certMajor) {
            $person['certMajor'] = $certMajor;
            $this->savePerson($person);
        }
        return true;
    }

    function savePerson($person)
    {
        if (!empty($this->shuiLiBuWuDaYuanCollection->findOne([
            "certNumber" => $person['certNumber'],
            "certMajor" => $person['certMajor']
        ]))) {
            echo "exist \n";
            print_r($person);
            return true;
        }
        $this->shuiLiBuWuDaYuanCollection->insertOne($person);
        return true;
    }

    function moveToNext()
    {
        if ($this->page > 148880) {
            return false;
        }
        $this->page = $this->redis->incr(__CLASS__);
        $this->url = str_replace('{page}', $this->page, $this->urlRaw);
        return true;
    }
}