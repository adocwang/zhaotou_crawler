<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-11-19
 * Time: 21:58
 */

namespace BuildInfo\crawler;
use BuildInfo\model\Crawlers;

class CrawlerBuilder
{
    public static function getCrawler($crawlerInfo)
    {
        $crawlerClass= new \ReflectionClass('BuildInfo\\crawler\\'.$crawlerInfo['class']);
        $crawler=$crawlerClass->newInstanceArgs([$crawlerInfo['url']]);
        return $crawler;
    }
}