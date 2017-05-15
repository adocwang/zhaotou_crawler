<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-11-19
 * Time: 17:39
 */


namespace BuildInfo\controller;

use Symfony\Component\Console\Output\OutputInterface;

class BaseController
{
    protected $output;

    function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }
}