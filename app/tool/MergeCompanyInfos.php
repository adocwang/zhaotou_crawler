<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2017-01-03
 * Time: 12:17
 */

namespace BuildInfo\tool;


class MergeCompanyInfos
{
    private static $mongoInstance;
    private $companyFinalCollection;
    private $companyIndexCollection;
    private $companyCollection;
    private $jiananCollection;
    private $mohurdPerson;
    private $jiaotongCreditCollection;
    private $shuiliZaojiashiCollection;
    private $shuiliWudayuanCollection;
    private $shuianCollection;
    private $jiaoanCollection;
    private $anQuanGongChengShiCollection;
    private $anQuanPingJiaShiCollection;
    private $gongLuXinYongCollection;

    function __construct()
    {
        $this->companyFinalCollection = $this->getDb()->build_info1->company_final;//最终结果
        $this->companyIndexCollection = $this->getDb()->build_info1->company_index;//最终结果的索引
        $this->companyCollection = $this->getDb()->build_info1->company;//公司信息
        $this->jiananCollection = $this->getDb()->build_info1->scjst_person_san_lei_copy;//建安
        $this->mohurdPerson = $this->getDb()->build_info1->zhu_jian_bu_person;//住建部建造师和土建造价师
        //水利部信用,通过json作为数据源
        $this->jiaotongCreditCollection = $this->getDb()->build_info1->jiaotong_credit;//交通信用
        $this->shuiliZaojiashiCollection = $this->getDb()->build_info1->shui_li_zao_jia;//水利造价师
        $this->shuiliWudayuanCollection = $this->getDb()->build_info1->shui_li_wu_da_yuan;//水利五大员
        $this->shuianCollection = $this->getDb()->build_info1->shui_an;//水安
        $this->siChuanShuianCollection = $this->getDb()->build_info1->si_chuan_shui_an;//川水安
        $this->jiaoanCollection = $this->getDb()->build_info1->jiao_an;//交安
        $this->anQuanGongChengShiCollection = $this->getDb()->build_info1->an_quan_gong_cheng_shi;//注册安全工程师
        $this->anQuanPingJiaShiCollection = $this->getDb()->build_info1->an_quan_ping_jia_shi;//安全评价师
        $this->gongLuXinYongCollection = $this->getDb()->build_info1->gong_lu_xin_yong;//公路信用

    }

    function getDb($new = false)
    {
        if ($new) {
            return new \MongoDB\Client('mongodb://localhost:27017', [], [
                    'typeMap' => [
                        'array' => 'array',
                        'document' => 'array',
                        'root' => 'array',
                    ],
                ]
            );
        }
        if (empty(self::$mongoInstance)) {
            self::$mongoInstance = new \MongoDB\Client('mongodb://localhost:27017', [], [
                    'typeMap' => [
                        'array' => 'array',
                        'document' => 'array',
                        'root' => 'array',
                    ],
                ]
            );
        }
        return self::$mongoInstance;
    }

    function processAll()
    {
//        $this->processCompany();
//        echo "processCompany finish!\n";
//        $this->processJianan();
//        echo "processJianan finish!\n";
        $this->processJianzaoshi();
        echo "processJianzaoshi finish!\n";
        $this->processShuiLiBuCredit();
        echo "processShuiLiBuCredit finish!\n";
        $this->processJiaotongCredit();
        echo "processJiaotongCredit finish!\n";
        $this->processGongluCredit();
        echo "processGongluCredit finish!\n";
        $this->processShuiliZaojia();
        echo "processShuiliZaojia finish!\n";
        $this->processShuiliWudayuan();
        echo "processShuiliWudayuan finish!\n";
        $this->processShuiAn();
        echo "processShuiAn finish!\n";
        $this->processChuanShuiAn();
        echo "processChuanShuiAn finish!\n";
        $this->processJiaoAn();
        echo "processJiaoAn finish!\n";
        $this->processAnquanGongchengshi();
        echo "processAnquanGongchengshi finish!\n";
        $this->processAnquanPingjiashi();
        echo "processAnquanPingjiashi finish!\n";
    }

    function compNameFormator($compName)
    {
        $compName = str_replace(',', '', $compName);
        $compName = str_replace('.', '', $compName);
        $compName = str_replace(']', '', $compName);
        $compName = str_replace('(', '（', $compName);
        $compName = str_replace(')', '）', $compName);
        if (strpos($compName, '）') !== false && strpos($compName, '（') === false) {
            $compName = str_replace('）', '', $compName);
        }
        $compName = trim($compName);
        return $compName;
    }

    function processCompany()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->companyCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $companies = $this->companyCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ]);
            foreach ($companies as $company) {
                $copied = [];
                foreach ($company as $key => $value) {
                    if (!empty($key) && trim($key != "")) {
                        $copied[$key] = $value;
                    }
                }
                $company = $copied;
                $zizhis = [];
                foreach ($company['zizhi'] as $zizhi) {
                    if ($zizhi['endTime'] < $time) {
                        continue;
                    }
                    if (empty($zizhi['certName'])) {
                        continue;
                    }
                    if ($zizhi['certLevel'] == 0) {
                        $zizhis[] = $zizhi['certName'] . "特级";
                    } elseif ($zizhi['certLevel'] == 1) {
                        $zizhis[] = $zizhi['certName'] . "壹级";
                    } elseif ($zizhi['certLevel'] == 2) {
                        $zizhis[] = $zizhi['certName'] . "贰级";
                    } elseif ($zizhi['certLevel'] == 3) {
                        $zizhis[] = $zizhi['certName'] . "叁级";
                    } elseif ($zizhi['certLevel'] == -1) {
                        $zizhis[] = $zizhi['certName'] . "不分等级";
                    } elseif ($zizhi['certLevel'] == -2) {
                        $zizhis[] = $zizhi['certName'];
                    }
                }
                $company['zizhi'] = array_unique($zizhis);
                $company['compType'] = [($company['compType'])];
                $company['compName'] = $this->compNameFormator($company['compName']);
                $company['aliasName'] = $company['compName'];
                unset($company['_id']);
                $compOld = $this->companyFinalCollection->findOne(['compName' => $company['compName']]);
                if (!$compOld) {
                    $company['person'] = [];
                    $company['special'] = [];
                    $this->companyFinalCollection->insertOne($company);
                } else {
//                    print_r($compOld);
//                    exit;
                    echo $company['compName'] . " exist!\n";
                    $company['compType'] = array_unique(array_merge($company['compType'], $compOld['compType']));
                    $company['zizhi'] = array_unique(array_merge($company['zizhi'], $compOld['zizhi']));
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $company['compName']],
                        ['$set' => $company]
                    );
                    if (!$result) {
                        echo $company['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processJianan()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->jiananCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->jiananCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['name']) || empty($item['compName']) || empty($item['certNumber']) || empty($item['endTime'])) {
                    continue;
                }
                if ($time > $item['endTime']) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

                if (strpos($item['compName'], ' ') > 0) {
                    $compNames = explode(' ', $item['compName']);
                    foreach ($compNames as $compName) {
                        $item['compName'] = $this->compNameFormator($compName);
                        if (mb_strlen($item['compName'] < 8)) {
                            continue;
                        }
                        $items[] = $item;
                    }
                    continue;
                }

                if (preg_match_all('/(.+公司)/U', $item['compName'], $matches)) {
                    if (count($matches[1]) > 1) {
                        foreach ($matches[1] as $compName) {
                            $item['compName'] = $this->compNameFormator($compName);
                            if (mb_strlen($item['compName'] < 8)) {
                                continue;
                            }
                            $items[] = $item;
                        }
                        continue;
                    } else {
                        $item['compName'] = $this->compNameFormator($item['compName']);
                    }
                } else {
//                    echo $item['compName'] . " no 公司\n";
//                    continue;
                }
                $gotChinese = false;
                if (preg_match("(Ａ|Ｂ|Ｃ|c)", $item['certNumber'])) {
                    $gotChinese = true;
                    $item['certNumber'] = str_replace("Ａ", "A", $item['certNumber']);
                    $item['certNumber'] = str_replace("Ｂ", "B", $item['certNumber']);
                    $item['certNumber'] = str_replace("Ｃ", "C", $item['certNumber']);
                    $item['certNumber'] = str_replace("c", "C", $item['certNumber']);
                }
                if (strpos($item['certNumber'], '建') !== false) {
                    if (preg_match_all('/(A|B|C)/', $item['certNumber'], $match)) {
                        $item['certName'] = '安考证:建安' . $match[1][0];
                    }
                } elseif (strpos($item['certNumber'], '交') !== false) {
                    if (preg_match_all('/(A|B|C)/', $item['certNumber'], $match)) {
                        $item['certName'] = '安考证:交安' . $match[1][0];
                    }
                } elseif (strpos($item['certNumber'], '水') !== false) {
                    if (preg_match_all('/(A|B|C)/', $item['certNumber'], $match)) {
                        $item['certName'] = '安考证:水安' . $match[1][0];
                    }
                } elseif (preg_match_all('/(A|B|C)/', $item['certNumber'], $match)) {
                    $item['certName'] = '安考证:建安' . $match[1][0];
                } else {
                    echo $item['certNumber'] . "\n";
                    continue;
                }
//                if (empty($item['certName'])) {
//                    print_r($item);
//                    exit;
//                }
                $person = [];
                $person['name'] = $item['name'];
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
                    echo $item['siteId'] . " company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
//                            if ($gotChinese) { //用于修正记录中的中文B
//                                foreach ($oldPerson['cert'] as $key => $oldCert) {
//                                    if (preg_match("(Ａ|Ｂ|Ｃ)", $oldCert)) {
//                                        unset($oldPerson['cert'][$key]);
//                                    }
//                                }
//                            }
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processJianzaoshi()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->mohurdPerson->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->mohurdPerson->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['name']) || empty($item['compName']) || empty($item['certName'])) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['name'];
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo $item['person_id'] . " company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processShuiLiBuCredit()
    {
        $jsonStr = file_get_contents(ROOT_DIR . '/data/slxypj.json');
        $items = json_decode($jsonStr, true);
        foreach ($items as $item) {
            if (empty($item['level'])) {
                continue;
            }
            $item['company'] = $this->compNameFormator($item['company']);
            $company = ['special' => [("水利部信用:" . $item['comp_type'] . ":" . $item['level'])]];
            $compOld = $this->companyFinalCollection->findOne(['compName' => $item['company']]);
            if (!$compOld) {
//                echo  " company " . $item['company'] . " not exists\n";
            } else {
                if (!empty($compOld['special'])) {
                    $company['special'] = array_unique(array_merge($company['special'], $compOld['special']));
                }
                $result = $this->companyFinalCollection->updateOne(
                    ['compName' => $item['company']],
                    ['$set' => $company]
                );
                if (!$result) {
                    echo $item['company'] . " error!\n";
                } else {
//                    echo $item['company'] . " " . $company['shuili_credit'] . "!\n";
                }
            }
        }
    }

    function processJiaotongCredit()
    {
        $page = 0;
        $limit = 100;
        $total = $this->jiaotongCreditCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->jiaotongCreditCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['industryType']) || empty($item['compName']) || empty($item['businessType']) || empty($item['level'])) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);
                $company = ['special' => [("四川交通信用:" . $item['industryType'] . ":" . $item['businessType'] . ":" . $item['level'])]];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo  " company " . $item['compName'] . " not exists\n";
                } else {
                    if (!empty($compOld['special'])) {
                        $company['special'] = array_unique(array_merge($company['special'], $compOld['special']));
                    }
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $item['compName']],
                        ['$set' => $company]
                    );
                    if (!$result) {
                        echo $item['compName'] . " error!\n";
                    } else {
                        echo $item['compName'] . " " . $company['special'] . "!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processShuiliZaojia()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->shuiliZaojiashiCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->shuiliZaojiashiCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['personName']) || empty($item['compName']) || empty($item['endTime'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['personName'];
                $person['cert'] = [('造价工程师水利')];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processShuiliWudayuan()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->shuiliWudayuanCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->shuiliWudayuanCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['name']) || empty($item['compName']) || empty($item['endTime']) || empty($item['certMajor'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['name'];
                $person['cert'] = [];
                if (strpos($item['certMajor'], '，')) {
                    $majors = explode('，', $item['certMajor']);
                    foreach ($majors as $major) {
                        $person['cert'][] = '水利五大员:' . $major;
                    }
                } else {
                    $person['cert'][] = '水利五大员:' . $item['certMajor'];
                }
                $person['cert'] = [('水利五大员:' . $item['certMajor'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processShuiAn()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->shuianCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->shuianCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['personName']) || empty($item['compName']) || empty($item['endTime']) || empty($item['certNumber'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['personName'];

                if (preg_match_all('/水安(A|B|C)/', $item['certNumber'], $match)) {
                    $item['certName'] = '安考证:国水安' . $match[1][0];
                }
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processChuanShuiAn()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->siChuanShuianCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->siChuanShuianCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['name']) || empty($item['compName']) || empty($item['endTime']) || empty($item['certNumber'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['name'];

                if (preg_match_all('/安(A|B|C)/', $item['certNumber'], $match)) {
                    $item['certName'] = '安考证:川水安' . $match[1][0];
                }
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processJiaoAn()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->jiaoanCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->jiaoanCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['personName']) || empty($item['compName']) || empty($item['endTime']) || empty($item['certNumber'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['personName'];

                if (preg_match_all('/安(A|B|C)/', $item['certNumber'], $match)) {
                    $item['certName'] = '安考证:交安' . $match[1][0];
                }
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processAnquanGongchengshi()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->anQuanGongChengShiCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->anQuanGongChengShiCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['personName']) || empty($item['compName']) || empty($item['endTime']) || empty($item['regType'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['personName'];
                $item['certName'] = '注册安全工程师:' . $item['regType'];
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processAnquanPingjiashi()
    {
        $page = 0;
        $limit = 1000;
        $total = $this->anQuanPingJiaShiCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->anQuanPingJiaShiCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['personName']) || empty($item['compName']) || empty($item['endTime']) || empty($item['certLevel'])) {
                    continue;
                }
                if ($item['endTime'] < $time) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);

//                if (strpos($item['compName'], ' ') > 0) {
//                    $compNames = explode(' ', $item['compName']);
//                    foreach ($compNames as $compName) {
//                        $item['compName'] = $this->compNameFormator($compName);
//                        if (mb_strlen($item['compName'] < 8)) {
//                            continue;
//                        }
//                        $items[] = $item;
//                    }
//                    continue;
//                }
                $person = [];
                $person['name'] = $item['personName'];
                $item['certName'] = '安全评价师:' . $item['certLevel'];
                $person['cert'] = [($item['certName'])];
//                $person['certNumber'] = $item['certNumber'];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo "company " . $item['compName'] . " not exists\n";
                } else {
                    $gotPerson = false;
                    foreach ($compOld['person'] as &$oldPerson) {
                        if (strcmp($oldPerson['name'], $person['name']) === 0) {
                            $oldPerson['cert'] = array_unique(array_merge($oldPerson['cert'], $person['cert']));
                            $gotPerson = true;
                            break;
                        }
                    }
                    unset($oldPerson);
                    if (!$gotPerson) {
                        $compOld['person'][] = $person;
                    }
//                    print_r($compOld);
//                    exit;
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $compOld['compName']],
                        ['$set' => ['person' => $compOld['person']]]
                    );
                    if (!$result) {
                        echo $compOld['compName'] . " error!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }

    function processGongluCredit()
    {
        $page = 0;
        $limit = 100;
        $total = $this->gongLuXinYongCollection->count();
        while ($total > ($page * $limit)) {
            $time = time();
            $items = $this->gongLuXinYongCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['year']) || empty($item['compName']) || empty($item['level']) || empty($item['score'])) {
                    continue;
                }
                $item['compName'] = $this->compNameFormator($item['compName']);
                $company = ['special' => [("公路信用等级:" . $item['level'] . ':' . $item['year'])]];
                $compOld = $this->companyFinalCollection->findOne(['compName' => $item['compName']]);
                if (!$compOld) {
//                    echo  " company " . $item['compName'] . " not exists\n";
                } else {
                    if (!empty($compOld['special'])) {
                        $company['special'] = array_unique(array_merge($company['special'], $compOld['special']));
                    }
                    $result = $this->companyFinalCollection->updateOne(
                        ['compName' => $item['compName']],
                        ['$set' => $company]
                    );
                    if (!$result) {
                        echo $item['compName'] . " error!\n";
                    } else {
                        echo $item['compName'] . " " . $company['special'] . "!\n";
                    }
                }
            }
            echo $page . " success!\n";
            $page++;
        }
    }
}