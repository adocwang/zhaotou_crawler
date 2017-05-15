<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-12-21
 * Time: 19:23
 */

namespace BuildInfo\tool;

use BuildInfo\tool\Client;

class ProcessJsbCompIdIntoScjst
{
    function process()
    {
        $mongo = new \MongoDB\Client('mongodb://localhost:27017');
        $companyDb = $mongo->build_info1->company;
        $client = new Client();
        $client->request('GET', 'http://127.0.0.1/jsbcompid.html');
        $body = $client->getBody();
        $preg = '/query\\/comp\\/compDetail\\/\d{10,}\\"\\>([\s\S]*?)\\<\\/a\\>/';
        preg_match_all($preg, $body, $matches);
        $line = 0;
        foreach ($matches[0] as $matche) {
            $preg1 = '/query\\/comp\\/compDetail\\/\d{10,}/';
            preg_match_all($preg1, $matche, $preg1Match);
            $pos = strrpos($preg1Match[0][0], '/');
            $jsbSiteId = trim(substr($preg1Match[0][0], ($pos + 1)));
            $preg2 = '/\d{10,}\"\>([\s\S]*?)\<\/a\>/';
            preg_match_all($preg2, $matche, $preg2Match);
            $jsbName = trim($preg2Match[1][0]);
            $companyDb->updateOne(['compName' => $jsbName], ['$set' => ['jsbSiteId' => $jsbSiteId]]);
            if (empty($companyDb->findOne(['compName' => $jsbName]))) {
                echo 'not exists:'.$jsbName."\n";
            }
//            echo $line++ . "\n";
        }
    }
}