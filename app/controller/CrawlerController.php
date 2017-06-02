<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2016-11-19
 * Time: 17:40
 */

namespace BuildInfo\controller;


use BuildInfo\crawler\CrawlerBuilder;
use BuildInfo\model\Watchers;
use Illuminate\Database\Capsule\Manager;
use Symfony\Component\Console\Output\OutputInterface;
use BuildInfo\model\Crawlers;

class CrawlerController extends BaseController
{
    private $crawlerConfig = [];

    function init()
    {
        // Eloquent ORM
        $dbManager = new Manager;
        $dbManager->addConnection(require ROOT_DIR . '/config/database.php');
        $dbManager->bootEloquent();

    }

    function __construct(OutputInterface $output)
    {
        parent::__construct($output);
        $this->init();
        $this->crawlerConfig = require_once ROOT_DIR . '/config/crawler_config.php';
    }

    function start()
    {
        $enableWatches = Watchers::where([['enable', 1], ['crawler_id', '!=', 0]])->get();
        $output = $this->output;
        $enableWatches->each(function ($enableWatche) use ($output) {
            $crawlerInfo = Crawlers::where('id', $enableWatche->crawler_id)->first();
            $crawler = CrawlerBuilder::getCrawler($crawlerInfo);
            if (empty($crawler)) {
                $output->writeln('class of crawler is undefined', OutputInterface::OUTPUT_RAW);
            }
//            $crawler->page = 756;
            $moveSuccess = true;
            do {
                try {
                    $res = $crawler->savePage();
                    if ($res) {
                        $output->writeln('saved page success:' . $crawler->page);
                    } else {
                        $output->writeln('saved page failed:' . $crawler->page);
                        continue;
                    }
                } catch (\Exception $e) {
                    echo $e->getMessage() . "\n";
                    sleep(10);
                    continue;
                }
                $moveSuccess = $crawler->moveToNext();
            } while ($moveSuccess);
        });
    }

    function test()
    {
        $output = $this->output;
        $crawlerInfo = Crawlers::where('id', 14)->first();
        $crawler = CrawlerBuilder::getCrawler($crawlerInfo);
        if (empty($crawler)) {
            $output->writeln('class of crawler is undefined', OutputInterface::OUTPUT_RAW);
        }
//            $crawler->page = 756;
        $moveSuccess = true;
        do {
            try {
                $res = $crawler->savePage();
                if ($res) {
                    $output->writeln('saved page success:' . $crawler->page);
                } else {
                    $output->writeln('saved page failed:' . $crawler->page);
                    continue;
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                sleep(10);
                continue;
            }
            $moveSuccess = $crawler->moveToNext();
        } while ($moveSuccess);


//        $crawlerInfo = Crawlers::where('id', 14)->first();
//        $crawler = CrawlerBuilder::getCrawler($crawlerInfo);
//        $crawler->savePage();


//        $collection = (new \MongoDB\Client('mongodb://localhost:27017'))->build_info1->company;
//        $companys = $collection->find([]);
//        foreach ($companys as $company) {
//            $siteId = $company['siteId'];
//            $siteIdNew = strtolower($siteId);
//            if (strcmp($siteId, $siteIdNew) !== 0) {
//                echo $siteId . "|" . $siteIdNew;
//                $collection->updateOne(
//                    ['siteId' => $siteId],
//                    ['$set' => ['siteId' => $siteIdNew]]
//                );
//            }
//        }
    }

    function runSigle($crawlerId)
    {
        if (empty($crawlerId)) {
            echo 'no $crawlerId';
            return false;
        }
        $output = $this->output;
        $crawlerInfo = $this->crawlerConfig[$crawlerId];//Crawlers::where('id', $crawlerId)->first();
        $crawler = CrawlerBuilder::getCrawler($crawlerInfo);
        if (empty($crawler)) {
            $output->writeln('class of crawler is undefined', OutputInterface::OUTPUT_RAW);
        }
//            $crawler->page = 756;
        $moveSuccess = true;
        $failedTimes = 0;
        do {
            try {
                $res = $crawler->savePage();
                if ($res) {
                    $failedTimes = 0;
                    $output->writeln('saved page success:' . $crawler->page);
                } else {
                    $failedTimes++;
                    if($failedTimes>10){
                        $output->writeln('stop! too many saved page failed!');
                        break;
                    }
                    $output->writeln('saved page failed:' . $crawler->page);
                    continue;
                }
            } catch (\Exception $e) {
                echo $e->getMessage() . "\n";
                sleep(10);
                continue;
            }
            $moveSuccess = $crawler->moveToNext();
        } while ($moveSuccess);
        if(!$moveSuccess){
            $output->writeln('no next stop!');
        }
    }
}