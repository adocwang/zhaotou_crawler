<?php

/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 5/29/17
 * Time: 14:00
 */
namespace BuildInfo\proxy;

abstract class BaseProxy
{
    public abstract function getHeaders();

    public abstract function getAddress();
}