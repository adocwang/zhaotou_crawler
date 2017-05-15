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

class ScjstCompanyPersonSearchListCrawler2 extends BaseCrawler
{
    public $postData = [];
    public $bodyQuery;
    protected $limit = 1;
    private static $mongoInstance;

    private $companyCollection;
    private $scjstPersonCollection;
    public $useproxy = true;
    private $tooManyNot200 = false;

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
        $this->companyCollection = $this->getDb()->build_info1->company;
        $this->scjstPersonCollection = $this->getDb()->build_info1->scjst_person;
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

    function saveCompany($company)
    {
        $compName = $company['compName'];
        $compId = $company['siteId'];
//        $compName = '四川祥昇建设工程有限公司';
        $this->postData = [];

        $this->tooManyNot200 = false;
        $page = 1;
        do {
            $url = 'http://xmgk.scjst.gov.cn/QueryInfo/Ente/EnteCyry.aspx?id=' . $compId;
            $this->getContentAndSaveToBody($url);
            if ($this->tooManyNot200) {
                return -1;
            }
            $bq = \QueryPath::withHTML5($this->body);
            if ($bq->find('input#txtCode')->length > 0) {//遇到验证码了
                echo "gotCheckCode!\n";
                $page--;
                continue;
            }
            $this->bodyQuery = $bq;
            $res = $this->saveItem($compName);
            if ($res === -1) {
                break;
            }
            echo "finish child page:$page\n";
        } while ($this->nextChildPage($compId, ++$page));
//        exit;
    }

    function nextChildPage($siteId, $page)
    {
        if ($page < 2) {
            return true;
        }
        $paginator = $this->bodyQuery->find('td.paginator')->eq(0);
        $as = $paginator->find('a');
        foreach ($as as $a) {
            $text = trim($a->text());
            if (strcmp($text, '下页') === 0) {
                $href = trim($a->attr('href'));
                if (empty($href) || $href == "") {
                    return false;
                } else {
                    break;
                }
            }
        }

        $hidden = [];
        $hidden['__VIEWSTATE'] = $this->bodyQuery->find('#__VIEWSTATE')->attr('value');
        $hidden['__EVENTTARGET'] = 'ctl00$mainContent$gvBiddingResultPager';
        $hidden['__EVENTARGUMENT'] = $page;
        $hidden['__EVENTVALIDATION'] = $this->bodyQuery->find('#__EVENTVALIDATION')->attr('value');
        $hidden['ctl00$mainContent$cxtj'] = '  where  b.qybm = (select top 1  FCompanyId from qy_jbxx where qybm =\'' . $siteId . '\')';
//        print_r($hidden);
//        exit;
        $this->postData = $hidden;
        return true;

    }

    public function saveItem($compName)
    {
        $this->content = $this->bodyQuery->find('table.list')->eq(1);
        $trs = $this->content->find('tr');
        $results = [];
        $lines = 0;
        if ($trs->length < 2) {
            echo "no person {$compName}\n";
            return -1;
        }
        foreach ($trs as $tr) {
            $lines++;
            if ($lines == 1) {
                continue;
            }
            $person = [];
            $person['compName'] = $compName;
            $url = $tr->find('td')->eq(1)->find('a')->attr('href');
//            echo $url;exit;
            $URi = new \Purl\Url($url);
            $arr = $URi->query->getData();
            $person['siteId'] = strtolower(trim($arr['id']));
            $person['name'] = trim($tr->find('td')->eq(1)->text());
            $person['certName'] = trim($tr->find('td')->eq(2)->text());
            $person['certNumber'] = trim($tr->find('td')->eq(3)->text());
            if ($this->scjstPersonCollection->findOne(['certName' => $person['certName'], 'certNumber' => $person['certNumber']])) {
                echo "exist " . $person['certNumber'] . " {$compName}\n";
                continue;
            }
            $this->scjstPersonCollection->insertOne($person);
        }
        echo "meet lines:" . $lines . "\n";
//        exit;
        if (!empty($results)) {
            return true;
        }
        return false;
    }

    function explodeMajor($majorStr)
    {
        $majors = explode("、", $majorStr);
        foreach ($majors as &$major) {
            $major = trim($major);
        }
        return $majors;
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

    function hasNew()
    {
        // TODO:是否有新的内容
    }

    function requestNot200()
    {
        $this->tooManyNot200 = true;
        return false;
    }

    function savePage()
    {
        $companies = $this->companyCollection->find([], [
            'limit' => $this->limit,
            'sort' => ['_id' => 1],
            'skip' => $this->page * $this->limit
        ]);
        foreach ($companies as $company) {
            do {
                $res = $this->saveCompany($company);
                if ($res === -1) {
                    echo 'clean postdata and restart this company';
                }
            } while ($res === -1);
        }
        return true;
    }

    function moveToNext()
    {
        if ($this->companyCollection->count() <= $this->limit * $this->page) {
            return false;
        }
        $this->page = $this->redis->incr(__CLASS__);
        return true;
    }

}