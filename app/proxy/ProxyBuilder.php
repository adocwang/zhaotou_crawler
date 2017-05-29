<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 5/29/17
 * Time: 15:05
 */

namespace BuildInfo\proxy;


class ProxyBuilder
{
    public static function getProxy($proxyConfig)
    {
        $proxyClass = new \ReflectionClass('BuildInfo\\proxy\\' . $proxyConfig['class']);
        return $proxyClass->newInstanceArgs([$proxyConfig]);
    }
}