<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-12-08
 * Time: 1:22
 */

namespace BuildInfo\tool;


use Masterminds\HTML5\Exception;

class Client
{
    public $headers = [];
    private $postData = [];
    private $response;
    private $statusCode;
    private $curl;

    function __construct()
    {

    }

    function makeHeader()
    {
        $headerStrs = [];
        foreach ($this->headers as $key => $value) {
            $headerStrs[] = $key . ": " . $value;
        }
//        $headerStrs[]='Expect:';
        return $headerStrs;
    }

    function makePostFields()
    {
        $postData = [];
        if (!is_array($this->postData)) {
            return $this->postData;
        }
        foreach ($this->postData as $key => $value) {
            $postData[] = urlencode($key) . "=" . urlencode($value);
        }
        $postStr = implode('&', $postData);
//        print_r($postStr);exit;
//        echo $this->postData['__VIEWSTATE'];
        return $postStr;
    }

    function getBody()
    {
        return $this->response;
    }

    function getStatusCode()
    {
        return $this->statusCode;
    }

    function request($method, $url, $options=[])
    {
        $this->curl = curl_init();
        if (!empty($options['headers'])) {
            $this->headers = $options['headers'];
        }
        $version = CURL_HTTP_VERSION_1_1;
        if (!empty($options['version'])) {
            if ($options['version'] == 1) {
                $version = CURL_HTTP_VERSION_1_0;
            }
        }
        $timeout = 15;
        if (!empty($options['timeout'])) {
            $timeout = $options['timeout'];
        }
        curl_setopt_array($this->curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => $timeout,
            CURLOPT_CONNECTTIMEOUT => $timeout,
            CURLOPT_HTTP_VERSION => $version,
            CURLOPT_HTTPHEADER => $this->makeHeader(),
            CURLOPT_ENCODING => 'gzip,deflate',
            CURLOPT_HEADER => false,
            CURLINFO_HEADER_OUT => false,
        ));


        if (!empty($options['proxy'])) {
            curl_setopt($this->curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
            curl_setopt($this->curl, CURLOPT_PROXY, $options['proxy']);
        }


        if($options['use_cookie']){
            $cookie_file = dirname(__FILE__).'/cookie.txt';
            curl_setopt($this->curl, CURLOPT_COOKIEFILE, $cookie_file); //使用上面获取的cookies
            curl_setopt($this->curl, CURLOPT_COOKIEJAR,  $cookie_file); //存储cookies
        }

        if (strcmp(strtolower($method), 'get') === 0) {
//            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'GET');
        } elseif (strcmp(strtolower($method), 'post') === 0) {
            if (!empty($options['post_data'])) {
                $this->postData = $options['post_data'];
            }
//            curl_setopt($this->curl, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($this->curl, CURLOPT_POST, true);
            if (!empty($options['post_type']) && $options['post_type'] == 'form-data') {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->postData);
            } else {
                curl_setopt($this->curl, CURLOPT_POSTFIELDS, $this->makePostFields());
            }
        }
        $this->response = curl_exec($this->curl);
        $this->statusCode = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);
//        $information = curl_getinfo($this->curl);
//        print_r($information);exit;
        $err = curl_error($this->curl);
        curl_close($this->curl);
        if ($err) {
            throw new Exception("cURL Error #:" . $err);
        }
    }

}