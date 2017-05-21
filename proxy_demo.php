<?php
//设置时区（使用中国时间，以免时区不同导致认证错误）
date_default_timezone_set("Asia/Shanghai");
//AppKey 信息，请替换
$appKey = '162257779';
//AppSecret 信息，请替换
$secret = 'ab3c266be450c935830da7e3d7d368ef';

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

//接下来使用蚂蚁动态代理进行访问（也可以使用curl方式)
$opts = array(
    'http' => array(
        'proxy' => 's2.proxy.mayidaili.com:8123',
        'request_fulluri' => true,
        'header' => "Proxy-Authorization: {$auth}",
    ),
);
$context = stream_context_create($opts);
//exit;
//$ip = file_get_contents("http://jzsc.mohurd.gov.cn/dataservice/query/comp/list", false, $context);
$ip = file_get_contents("http://1212.ip138.com/ic.asp", false, $context);

echo $ip;
