<?php
//����ʱ����ʹ���й�ʱ�䣬����ʱ����ͬ������֤����
date_default_timezone_set("Asia/Shanghai");
//AppKey ��Ϣ�����滻
$appKey = '162257779';
//AppSecret ��Ϣ�����滻
$secret = 'ab3c266be450c935830da7e3d7d368ef';

//ʾ���������
$paramMap = array(
    'app_key' => $appKey,
    'timestamp' => date('Y-m-d H:i:s')
);

//���ղ���������
ksort($paramMap);
//���Ӵ����ܵ��ַ���
$codes = $secret;

//�����URL����
$auth = 'MYH-AUTH-MD5 ';
foreach ($paramMap as $key => $val) {
    $codes .= $key . $val;
    $auth .= $key . '=' . $val . '&';
}

$codes .= $secret;

//ǩ������
$auth .= 'sign=' . strtoupper(md5($codes));

//������ʹ�����϶�̬������з��ʣ�Ҳ����ʹ��curl��ʽ)
$opts = array(
    'http' => array(
        'proxy' => '123.56.160.119:8123',
        'request_fulluri' => true,
        'header' => "Proxy-Authorization: {$auth}",
    ),
);
$context = stream_context_create($opts);
exit;
//$ip = file_get_contents("http://jzsc.mohurd.gov.cn/dataservice/query/comp/list", false, $context);
$ip = file_get_contents("http://www.ip138.com/ips1388.asp", false, $context);

echo $ip;
?>