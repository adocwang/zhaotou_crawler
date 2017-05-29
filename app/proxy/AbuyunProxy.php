<?php

/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 5/29/17
 * Time: 14:01
 */
namespace BuildInfo\proxy;

class AbuyunProxy extends BaseProxy
{
    private $proxyConfig;

    public function __construct($proxyConfig)
    {
        $this->proxyConfig = $proxyConfig;
    }

    public function getHeaders()
    {
        $proxyAuth = base64_encode("{$this->proxyConfig['user']}:{$this->proxyConfig['password']}");
        return ["Proxy-Authorization" => "Basic {$proxyAuth}"];
    }

    public function getAddress()
    {
        return $this->proxyConfig['ip'] . ':' . $this->proxyConfig['port'];
    }
}