<?php

/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 5/29/17
 * Time: 14:01
 */
namespace BuildInfo\proxy;

class MayiProxy extends BaseProxy
{
    private $proxyConfig;

    public function __construct($proxyConfig)
    {
        $this->proxyConfig = $proxyConfig;
    }

    public function getHeaders()
    {
        //设置时区（使用中国时间，以免时区不同导致认证错误）
        date_default_timezone_set("Asia/Shanghai");
//AppKey 信息，请替换
        $appKey = $this->proxyConfig['appKey'];
//AppSecret 信息，请替换
        $secret = $this->proxyConfig['secret'];

//示例请求参数
        $paramMap = array(
            'app_key' => $appKey,
            'timestamp' => date('Y-m-d H:i:s')
        );

//按照参数名排序
        ksort($paramMap);
//连接待加密的字符串
        $codes = $secret;

//请求的URL参数
        $auth = 'MYH-AUTH-MD5 ';
        foreach ($paramMap as $key => $val) {
            $codes .= $key . $val;
            $auth .= $key . '=' . $val . '&';
        }

        $codes .= $secret;

//签名计算
        $auth .= 'sign=' . strtoupper(md5($codes));
        return ['Proxy-Authorization' => $auth];
    }

    public function getAddress()
    {
        return $this->proxyConfig['ip'] . ':' . $this->proxyConfig['port'];
    }
}