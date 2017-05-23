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

class MohurdGovCnCompanyIdCrawler extends BaseCrawler
{
    public $postData = [];
    public $bodyQuery;
    public $useproxy = false;
    public $limit=1;
    private static $mongoInstance;

    function __construct($urlRaw)
    {
        $this->redis = ClientFactory::create([
            'server' => 'tcp://127.0.0.1:6379', // or 'unix:///tmp/redis.sock'
            'timeout' => 2
        ]);
//        $this->redis->set(__CLASS__, 12774);
        $this->page = $this->redis->get(__CLASS__);
        if (!$this->page) {
            $this->redis->set(__CLASS__, 0);
            $this->page = 0;
        }
        $this->companyCollection = $this->mongoConnection->build_info1->company;
        parent::__construct($urlRaw);
    }

    function getContentAndSaveToBody($url)
    {
        $this->body = $this->doRequest($url, $this->postData);
//        print_r($this->postData);
        return $this->body;
    }

    function doRequest($url = '', $postData = [])
    {
        return parent::doRequest($url, $postData); // TODO: Change the autogenerated stub
    }

    function saveCompany($compName)
    {
//        $compName = '四川尧顺建设集团有限公司';
        $this->postData = $this->getPostData($compName);
        $url = 'http://jzsc.mohurd.gov.cn/dataservice/query/comp/list';
        $this->tooManyNot200 = false;
        $this->getContentAndSaveToBody($url);
        if (empty($this->body) || $this->tooManyNot200) {
            return -1;
        }
        $this->bodyQuery = \QueryPath::withHTML5($this->body);
        $trs = $this->bodyQuery->find('.cursorDefault')->find('tr');
        if ($trs->length < 0) {
            echo 'no company:', $compName . "\n";
            return true;
        }
        $url = $trs->eq(0)->find('td')->eq(2)->find('a')->attr('href');
        $pos = strrpos($url, '/');
        $jsbSiteId = trim(substr($url, ($pos + 1)));
        $this->companyCollection->updateOne(['compName' => $compName], ['$set' => ['jsbSiteId' => $jsbSiteId]]);
        echo $compName . "\n";
    }

    function hasNew()
    {
        // TODO:是否有新的内容
    }

    function savePage()
    {
        $companies = $this->companyCollection->find(['compType' => ['$regex' => "入川.*"]], [
            'limit' => $this->limit,
            'sort' => ['_id' => 1],
            'skip' => $this->page * $this->limit
        ]);
        foreach ($companies as $company) {
            $res = $this->saveCompany($company['compName']);
        }
        return true;
    }

    function moveToNext()
    {
        if ($this->page >= 5427) {
            return false;
        }
        $this->page = $this->redis->incr(__CLASS__);
        return true;
    }

    function getPostData($compName)
    {
        $postData = 'qy_name=' . urlencode($compName);
        return $postData;
    }

}