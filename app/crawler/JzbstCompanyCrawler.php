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

class JzbstCompanyCrawler extends BaseCrawler
{
    public $postData = [];
    public $bodyQuery;
    protected $limit = 2000;
    private static $mongoInstance;

    private $companyCollection;
    private $scjstPersonCollection;
    public $useproxy = false;
    private $tooManyNot200 = false;
    private $hasNext = true;
    public $timeout=1000;

    public static $scrollId;

    function __construct($urlRaw)
    {
        $this->redis = ClientFactory::create([
            'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
            'timeout' => 2
        ]);
        $this->page = $this->redis->get(__CLASS__);
        if (empty($this->page)) {
            $this->redis->set(__CLASS__, 0);
            $this->page = $this->redis->get(__CLASS__);
        }
        $this->companyCollection = $this->getDb()->build_info1->jsbst_company;
        parent::__construct($urlRaw);
    }

    function getContentAndSaveToBody($url)
    {
        $this->body = $this->doRequest($url, $this->postData);
//        print_r($this->postData);
        return $this->body;
    }

    function getDb($new = false)
    {
        if ($new) {
            return new \MongoDB\Client('mongodb://localhost:27017');
        }
        if (empty(self::$mongoInstance)) {
            self::$mongoInstance = new \MongoDB\Client('mongodb://localhost:27017');
        }
        return self::$mongoInstance;
    }

    function doRequest($url = '', $postData = [])
    {
        return parent::doRequest($url, $postData); // TODO: Change the autogenerated stub
    }

    function hasNew()
    {
        // TODO:是否有新的内容
    }

    function requestNot200()
    {
        $this->tooManyNot200 = true;
        return true;
    }

    function getScrollId()
    {
        if (!empty(self::$scrollId)) {
            return self::$scrollId;
        }
        $url = 'http://118.123.173.75:9200/construction_company_index_v1/_search?search_type=scan&scroll=5m&size=' . $this->limit;
        $json = $this->doRequest($url);
        $jsonArr = json_decode($json, true);
        self::$scrollId = $jsonArr['_scroll_id'];
        return self::$scrollId;
    }

    function savePage()
    {
        $scrollId = $this->getScrollId();
//        print_r($scrollId);exit;
//        $scrollId = 'c2Nhbjs1OzQxNjkzOTpGQzBMejkyaFJ2LTZaM19UZ2N2VHlnOzQxNjk0MDpGQzBMejkyaFJ2LTZaM19UZ2N2VHlnOzQxNjk0MjpGQzBMejkyaFJ2LTZaM19UZ2N2VHlnOzQxNjk0MTpGQzBMejkyaFJ2LTZaM19UZ2N2VHlnOzQxNjk0MzpGQzBMejkyaFJ2LTZaM19UZ2N2VHlnOzE7dG90YWxfaGl0czo0MjUwMzs=';
        $this->postData = $scrollId;
        $url = 'http://118.123.173.75:9200/_search/scroll?scroll=5m';
        $json = $this->doRequest($url, $this->postData);
        if ($this->tooManyNot200) {
            $this->hasNext = false;
            return true;
        }
        $jsonArr = json_decode($json, true);
        $persons = $jsonArr['hits']['hits'];
        if (count($persons) > 0) {
            $this->hasNext = true;
            foreach ($persons as $person) {
                if ($this->companyCollection->findOne(['company_id' => ($person['_source']['company_id'])])) {
                    echo "exist " . $person['_source']['company_id'] . "\n";
                    continue;
                }
                $this->companyCollection->insertOne($person['_source']);
            }
        } else {
            $this->hasNext = false;
        }
        return true;
    }

    function moveToNext()
    {
        return $this->hasNext;
    }

}