<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-11-19
 * Time: 20:48
 */

namespace BuildInfo\crawler;

use GuzzleHttp;
use GuzzleHttp\Exception\RequestException;
use BuildInfo\tool\Client;
use BuildInfo\proxy\ProxyBuilder;

abstract class BaseCrawler
{
    public $url;
    public $urlRaw;
    public $page = 0;
    protected $body;
    protected $content;
    protected $lines;
    protected $baseUri;
    public static $proxies = [];
    public static $proxyId = 0;
    public $useproxy = true;
    public $lastRequestInfo;
    /**
     * @var $proxy \BuildInfo\proxy\BaseProxy
     */
    private $proxy;
    public $mongoConnection;

    function __construct($urlRaw)
    {
        $this->urlRaw = $urlRaw;
        $this->url = str_replace('{page}', $this->page, $this->urlRaw);
        $this->baseUri = new \Purl\Url($this->url);
        $proxyConfig = require ROOT_DIR . '/config/proxy.php';
        $this->proxy = ProxyBuilder::getProxy($proxyConfig);
        $mongoConfig = require ROOT_DIR . '/config/mongo_config.php';
        if (isset($mongoConfig['username']) && $mongoConfig['username']) {
            $uri = 'mongodb://' . $mongoConfig['username'] . ":" . $mongoConfig['password'] . '@' . $mongoConfig['host'] . ':' . $mongoConfig['port'];
        } else {
            $uri = 'mongodb://' . $mongoConfig['host'] . ':' . $mongoConfig['port'];
        }
        $this->mongoConnection = new \MongoDB\Client($uri);
    }

    function doRequest($url = '', $postData = [])
    {
        $this->lastRequestInfo = [];
        if (empty($url)) {
            $url = $this->url;
        }
        $this->lastRequestInfo['url'] = $url;
        $this->lastRequestInfo['postData'] = $postData;
        $client = new Client();
        $seconds = 2;
        $sleepTimes = 0;
        $timeout = 10;
        if (!empty($this->timeout)) {
            $timeout = $this->timeout;
        }
        do {
            try {
                $fakeIp = rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255) . '.' . rand(0, 255);
                $options = [
                    'version' => 1,
                    'headers' => [
                        'User-Agent' => 'Mozilla/' . rand(4, 6) . '.0 (Windows NT ' . rand(5, 7) . '.1; WOW64) AppleWebKit/' . rand(400, 600) . '.36 (KHTML, like Gecko) Chrome/' . rand(24, 56) . '.0.2893.1 Safari/' . rand(140, 360) . '.36',
//                        'Accept-Encoding' => 'gzip',
                        'Referer' => $this->url,
                        'HTTP_CLIENT_IP' => $fakeIp,
                        'X-Forwarded-For' => $fakeIp,
                    ],
                    'use_proxy' => true,
                    'timeout' => $timeout,
//                    'decode_content' => 'gzip'
//                    'debug' => true,
//                    'track_redirects' => false
                ];
                if ($this->useproxy && $seconds < 2048) {
                    $options['proxy'] = $this->proxy->getAddress();
                    $headers = $this->proxy->getHeaders();
                    foreach ($headers as $key => $value) {
                        $options['headers'][$key] = $value;
                    }
                }
//                print_r($options);exit;
//                echo self::$proxies[$proxyId];exit;
//                $url = 'http://115.29.113.202/addr.php';
//                $url='http://httpbin.org/post';
                echo 'sending request:' . $url . "\r\n";
                if (empty($postData)) {
                    $client->request('GET', $url, $options);
                } else {
                    $options['post_data'] = $postData;
                    $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
                    $client->request('POST', $url, $options);
                }
//                print_r($res->getHeaders());
                echo 'getStatusCode:' . $client->getStatusCode() . "\n";
//                print_r($res->getBody() . "");
//                exit;
                usleep(100000);
                if ($client->getStatusCode() != 200) {
                    $sleepTimes++;
                    if ($sleepTimes > 10) {
                        if (!$this->requestNot200()) {
                            return false;
                        }
                    }
                    echo 'code not 200' . "\n";
//                    print_r($client->getBody() . "");
//                    exit;
                    echo 'sleep:' . 1 . "\n";
                    sleep(2);
                    continue;
                }
                return $client->getBody() . "";
            } catch (\Exception $e) {
                $sleepTimes++;
                if ($sleepTimes > 10) {
                    if (!$this->requestNot200()) {
                        return false;
                    }
                }
                echo $this->page . 'proxy broken: ' . self::$proxies[self::$proxyId] . "\n";
                self::$proxyId++;
                echo 'failed:' . $e->getMessage() . ' sleep:' . $seconds . "\n";
                sleep(2);
            }
        } while (1);
    }

    function getFullUrl($relativeUrl)
    {
        return "";
    }

    function requestNot200()
    {
        echo "too many not 200 but not cached!\n";
        return true;
    }

    abstract function savePage();

    abstract function moveToNext();

    abstract function hasNew();
}