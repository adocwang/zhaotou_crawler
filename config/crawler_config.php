<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 5/20/17
 * Time: 23:46
 */

$crawlers = array(
    1 => array(
        'id' => '1',
        'class' => 'ScjstCompanyCrawler',
        'url' => 'http://www.scjst.gov.cn:8081/QueryInfo/Ente/EnteList.aspx?type=101&cpageid={page}',
        'name' => '四川省住建厅-施工企业'
    ),
    2 => array(
        'id' => '2',
        'class' => 'ScjstCompanyCrawler',
        'url' => 'http://www.scjst.gov.cn:8081/QueryInfo/Ente/EnteList.aspx?type=201&cpageid={page}',
        'name' => '四川省住建厅-入川企业'
    ),
    3 => array(
        'id' => '3',
        'class' => 'ScjstCompanyCrawler',
        'url' => 'http://www.scjst.gov.cn:8081/QueryInfo/Ente/EnteList.aspx?type=108&cpageid={page}',
        'name' => '四川省住建厅-设计施工一体化'
    ),
    4 => array(
        'id' => '4',
        'class' => 'ScjstCompanyPersonSearchListCrawler',
        'url' => 'http://www.scjst.gov.cn:8081/QueryInfo/Person/PersonList.aspx?type=407',
        'name' => '四川省住建厅-各种人员'
    ),
    5 => array(
        'id' => '5',
        'class' => 'MohurdGovCnCompanyIdCrawler',
        'url' => '',
        'name' => '建设部 抓取建设部公司ID'
    ),
    6 => array(
        'id' => '6',
        'class' => 'ZhuJianBuCompanyPersonSearchListCrawler',
        'url' => 'http://jzsc.mohurd.gov.cn/dataservice/query/staff/list',
        'name' => '住建部-根据公司爬建造师和造价工程师'
    ),
    7 => array(
        'id' => '7',
        'class' => 'ShuiLiBuWuDaYuanCrawler',
        'url' => 'http://www.cwun.org/cyry_info.php?type=8&id={page}',
        'name' => '水利部-五大员'
    ),
    8 => array(
        'id' => '8',
        'class' => 'ShuiLiBuZaoJiaCrawler',
        'url' => 'http://www.cwun.org/cyry.php?page={page}&type=4&kw=&name=&idcard=&certid=&unitname=',
        'name' => '水利部-造价工程师'
    ),
    9 => array(
        'id' => '9',
        'class' => 'ShuiLiBuShuiAnCrawler',
        'url' => 'http://www.cwun.org/cyry.php?page={page}&type=9&kw=&name=&idcard=&certid=&unitname=',
        'name' => '水利部-三类人员'
    ),
    10 => array(
        'id' => '10',
        'class' => 'JiaoTongBuJiaoAnCrawler',
        'url' => 'http://219.143.235.78:8080/khglui/PeopleSeach.aspx?PageId={page}',
        'name' => '交通部交安'
    ),
    11 => array(
        'id' => '11',
        'class' => 'ZhuCeAnQuanGongChengShiCrawler',
        'url' => 'http://rmocse.chinasafety.ac.cn/UnitSearch.aspx?UnitsName=',
        'name' => '注册安全工程师'
    ),
    12 => array(
        'id' => '12',
        'class' => 'AnQuanPingJiaShiCrawler',
        'url' => 'http://cydj.5anquan.com/UILogin/BeforeUserInfoShow/?id={page}',
        'name' => '安全评价师'
    ),
    13 => array(
        'id' => '13',
        'class' => 'SichuanJiaotongCreditCrawler',
        'url' => 'http://182.150.21.174:8000/credit/enterprise_query.php?startLocation={page}&ENTERPRISE_NAME=&CERTIFICATE_NUMBER=&CREDIT_RECORD=-1',
        'name' => '四川省交通信用'
    ),
    14 => array(
        'id' => '14',
        'class' => 'GongLuXinYongCrawler',
        'url' => 'http://glxy.mot.gov.cn/BM/CreditAction_jPublishList.do?loc=CreditList&corpcode=&corpname=&periodcode=&corptype=21020_01&grade=&pageNo={page}',
        'name' => '公路信用市场评价'
    ),
);

//$crawlers = array(
//    15 => array(//这个已经没用了，网址都打不开
//        'id' => '15',
//        'class' => 'SiChuanShuiAnCrawler',
//        'url' => 'http://zscx.scslinfo.cn/default.aspx',
//        'name' => '四川水利厅-水安'
//    ),
//    17 => array(
//        'id' => '17',
//        'class' => 'JzbstCompanyCrawler',
//        'url' => '',
//        'name' => '建设百事通-公司'
//    ),
//    18 => array(
//        'id' => '18',
//        'class' => 'JzbstPersonCrawler',
//        'url' => '',
//        'name' => '建设百事通-人员'
//    ),
//    19 => array(
//        'id' => '19',
//        'class' => 'JzbstPerformaceCrawler',
//        'url' => '', 'name' => '建设百事通-业绩'
//    ),
//    22 => array(
//        'id' => '22',
//        'class' => 'GongLuXinYongCrawler',
//        'url' => 'http://glxy.mot.gov.cn/BM/CreditAction_jPublishList.do?loc=CreditList&corpcode=&corpname=&periodcode=&corptype=21020_01&grade=&pageNo={page}',
//        'name' => '公路信用市场评价'
//    )
//);

return $crawlers;