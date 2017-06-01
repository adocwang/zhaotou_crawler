<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 9/10/16
 * Time: 15:11
 */

//error_reporting(0);

use Symfony\Component\Console\Output\OutputInterface;
use DI\ContainerBuilder;
use BuildInfo\controller\CrawlerController;
use BuildInfo\tool\ProcessJsbCompIdIntoScjst;
use BuildInfo\tool\MergeCompanyInfos;

//根目录常量定义
const ROOT_DIR = __DIR__;

//引入composer的依赖自动加载
require ROOT_DIR . '/vendor/autoload.php';

//定义container
$containerBuilder = new ContainerBuilder;
$containerBuilder->addDefinitions(ROOT_DIR . '/config/config.php');
$container = $containerBuilder->build();

//开始服务器的定义,使用Silly命令行开发框架
$app = new Silly\Application();
$app->useContainer($container, true);
/**
 * 定义Start命令,开启服务器
 */
$app->command('start', function (OutputInterface $output) {
    $crawler = new CrawlerController($output);
    $crawler->start();
})->descriptions('Start crawler');

$app->command('test', function (OutputInterface $output) {
    $class = new \BuildInfo\tool\GetFormClasses();
    $class->getAllClasses();

});
$app->command('run [crawlerId]', function ($crawlerId, OutputInterface $output) {
    $crawler = new CrawlerController($output);
    $crawler->runSigle($crawlerId);

});

$app->command('runBatch [crawlerIds]', function ($crawlerIds, OutputInterface $output) {
    $crawlerIdArr = explode(',', $crawlerIds);
    foreach($crawlerIdArr as $crawlerId) {
        $crawler = new CrawlerController($output);
        $crawler->runSigle($crawlerId);
    }

});

$app->command('jsbIdProcess', function () {
    $processJsbCompIdIntoScjst = new ProcessJsbCompIdIntoScjst();
    $processJsbCompIdIntoScjst->process();

});

$app->command('merge', function () {
    $mergeCompanyInfos = new MergeCompanyInfos();
    $mergeCompanyInfos->processAll();

});

//默认命令为start
$app->setDefaultCommand('start');


$app->run();