<?php
/**
 * Created by PhpStorm.
 * User: wangyibo
 * Date: 2017-01-05
 * Time: 16:55
 */

namespace BuildInfo\tool;


class GetFormClasses
{
    private static $mongoInstance;
    private $companyFinalCollection;
    private $companyFormatedCollection;
    private $personJsonData = '{"\u5b89\u8003\u8bc1\u5efa\u5b89A":42407,"\u5b89\u8003\u8bc1:\u5efa\u5b89A":41946,"\u5b89\u8003\u8bc1\u5efa\u5b89C":116085,"\u5b89\u8003\u8bc1:\u5efa\u5b89C":115412,"\u5b89\u8003\u8bc1\u5efa\u5b89B":123709,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u5efa\u7b51\u5de5\u7a0b":147775,"\u5b89\u8003\u8bc1:\u5efa\u5b89B":123108,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u5e02\u653f\u516c\u7528\u5de5\u7a0b":71251,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u516c\u8def\u5de5\u7a0b":23221,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u6c34\u5229\u6c34\u7535\u5de5\u7a0b":26959,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u673a\u7535\u5de5\u7a0b":32248,"\u5b89\u8003\u8bc1:\u5ddd\u6c34\u5b89A":1106,"\u5b89\u8003\u8bc1:\u5ddd\u6c34\u5b89B":5706,"\u5b89\u8003\u8bc1:\u5ddd\u6c34\u5b89C":3303,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u6c34\u5229)":399,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u5e02\u653f\u516c\u7528\u5de5\u7a0b":3830,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u5efa\u7b51\u5de5\u7a0b":17835,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u901a\u4fe1\u4e0e\u5e7f\u7535\u5de5\u7a0b":1490,"\u9020\u4ef7\u5de5\u7a0b\u5e08\u6c34\u5229":5252,"\u5b89\u8003\u8bc1:\u4ea4\u5b89B":34368,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u516c\u8def\u5de5\u7a0b":20250,"\u5b89\u8003\u8bc1:\u4ea4\u5b89C":35436,"\u6ce8\u518c\u9020\u4ef7\u5de5\u7a0b\u5e08\u571f\u5efa":16542,"\u5b89\u8003\u8bc1:\u4ea4\u5b89A":2992,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u5e02\u653f\u516c\u7528\u5de5\u7a0b":31839,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u516c\u8def\u5de5\u7a0b":1291,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u5efa\u7b51\u5de5\u7a0b":88961,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u673a\u7535\u5de5\u7a0b":27283,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5efa\u7b51\u65bd\u5de5\u5b89\u5168":8089,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u5efa\u7b51\u5de5\u7a0b":8815,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u673a\u7535\u5de5\u7a0b":2913,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u6c34\u5229\u6c34\u7535\u5de5\u7a0b":1317,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u94c1\u8def\u5de5\u7a0b":4602,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u94c1\u8def\u5de5\u7a0b":221,"\u6c34\u5229\u4e94\u5927\u5458:\u5b89\u5168\u5458":8304,"\u6c34\u5229\u4e94\u5927\u5458:\u8d28\u68c0\u5458":8512,"\u6ce8\u518c\u9020\u4ef7\u5de5\u7a0b\u5e08\u5b89\u88c5":2724,"\u6c34\u5229\u4e94\u5927\u5458:\u6750\u6599\u5458":4882,"\u6c34\u5229\u4e94\u5927\u5458:\u65bd\u5de5\u5458":7344,"\u5b89\u5168\u8bc4\u4ef7\u5e08:\u4e09\u7ea7":132,"\u6ce8\u518c\u571f\u6728\u5de5\u7a0b\u5e08\uff08\u5ca9\u571f\uff09":3261,"\u4e00\u7ea7\u6ce8\u518c\u7ed3\u6784\u5de5\u7a0b\u5e08":8965,"\u6c34\u5229\u4e94\u5927\u5458:\u8d44\u6599\u5458":5017,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u77ff\u4e1a\u5de5\u7a0b":1136,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u4ea4\u901a)":325,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u673a\u7535\u5b89\u88c5\u5de5\u7a0b":3183,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u519c\u6797\u5de5\u7a0b":460,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b":17719,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u5e02\u653f\u516c\u7528\u5de5\u7a0b":13659,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u7535\u529b\u5de5\u7a0b":2031,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u519c\u4e1a)":119,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u7279\u79cd\u8bbe\u5907)":35,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u6d88\u9632)":31,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u6797\u4e1a)":19,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u6c34\u5229\u6c34\u7535\u5de5\u7a0b":9566,"\u6ce8\u518c\u7535\u6c14\u5de5\u7a0b\u5e08\uff08\u4f9b\u914d\u7535\uff09":3575,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u6c34\u5229\u6c34\u7535\u5de5\u7a0b":1602,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u516c\u8def\u5de5\u7a0b":1952,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u5316\u5de5\u77f3\u6cb9\u5de5\u7a0b":1744,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u7b51\u5e08":7709,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u77ff\u4e1a\u5de5\u7a0b":1121,"\u4e8c\u7ea7\u6ce8\u518c\u7ed3\u6784\u5de5\u7a0b\u5e08":642,"\u5b89\u5168\u8bc4\u4ef7\u5e08:\u4e8c\u7ea7":139,"\u6ce8\u518c\u516c\u7528\u8bbe\u5907\u5de5\u7a0b\u5e08\uff08\u7ed9\u6c34\u6392\u6c34\uff09":3265,"\u6ce8\u518c\u516c\u7528\u8bbe\u5907\u5de5\u7a0b\u5e08\uff08\u6696\u901a\u7a7a\u8c03\uff09":2594,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u7b51\u5e08":1626,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u516c\u8def\u5de5\u7a0b":1366,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u673a\u7535\u5de5\u7a0b":2119,"\u6ce8\u518c\u516c\u7528\u8bbe\u5907\u5de5\u7a0b\u5e08\uff08\u52a8\u529b\uff09":1151,"\u9879\u76ee\u8d1f\u8d23\u4eba":80,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5371\u9669\u7269\u54c1\u5b89\u5168(\u5371\u9669\u5316\u5b66\u54c1)":68,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u7164\u77ff\u5b89\u5168":64,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u94c1\u8def\u5de5\u7a0b":1513,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u77ff\u4e1a\u5de5\u7a0b":135,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u5e02\u653f\u516c\u7528\u5de5\u7a0b":943,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u6c34\u5229\u6c34\u7535\u5de5\u7a0b":406,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b":2057,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u901a\u4fe1\u5de5\u7a0b":474,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u5176\u4ed6)":210,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u7535\u529b)":191,"\u4e00\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08\u6c11\u822a\u673a\u573a\u5de5\u7a0b":314,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u51b6\u70bc\u5de5\u7a0b":485,"\u5b89\u8003\u8bc1:\u56fd\u6c34\u5b89B":6974,"\u5b89\u8003\u8bc1:\u56fd\u6c34\u5b89A":714,"\u5b89\u8003\u8bc1:\u56fd\u6c34\u5b89C":6208,"\u6ce8\u518c\u7535\u6c14\u5de5\u7a0b\u5e08\uff08\u53d1\u8f93\u53d8\u7535\uff09":763,"\u4e13\u804c\u5b89\u5168\u8d1f\u8d23\u4eba":65,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u975e\u7164\u77ff\u77ff\u5c71\u5b89\u5168":216,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u77ff\u4e1a\u5de5\u7a0b":111,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u901a\u4fe1\u4e0e\u5e7f\u7535\u5de5\u7a0b":46,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u77ff\u5c71\u5de5\u7a0b":443,"\u6ce8\u518c\u5316\u5de5\u5de5\u7a0b\u5e08":1051,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b":123,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5371\u9669\u7269\u54c1\u5b89\u5168(\u6c11\u7528\u7206\u7834\u5668\u6750)":11,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u94c1\u8def)":125,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b":329,"\u6ce8\u518c\u76d1\u7406\u5de5\u7a0b\u5e08\u822a\u5929\u822a\u7a7a\u5de5\u7a0b":177,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u6c11\u822a)":6,"\u5b89\u5168\u8bc4\u4ef7\u5e08:\u4e00\u7ea7":79,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u519b\u5de5)":21,"\u6ce8\u518c\u5b89\u5168\u5de5\u7a0b\u5e08:\u5176\u4ed6\u5b89\u5168(\u7535\u4fe1)":37,"\u4e00\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08\u6c11\u822a\u673a\u573a\u5de5\u7a0b":13,"\u6ce8\u518c\u5efa\u9020\u5e08":3171,"\u5b89\u8003\u8bc1:\u6c34\u5b89B":312,"\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08":72,"\u5b89\u8003\u8bc1:\u6c34\u5b89C":228,"\u4e8c\u7ea7\u4e34\u65f6\u6ce8\u518c\u5efa\u9020\u5e08":9,"\u5b89\u8003\u8bc1:\u6c34\u5b89A":2,"\u4e8c\u7ea7\u6ce8\u518c\u5efa\u9020\u5e08":7,"\u6c34\u5229\u4e94\u5927\u5458:\u8d44\u6599\u5458\uff0c\u5b89\u5168\u5458":2,"\u6c34\u5229\u4e94\u5927\u5458:\u65bd\u5de5\u5458\uff0c\u8d28\u68c0\u5458":1,"\u6c34\u5229\u4e94\u5927\u5458:\u6750\u6599\u5458\uff0c\u8d44\u6599\u5458":1}
';
    public $zizhiJsonData = '{"\u65bd\u5de5\u52b3\u52a1\u65bd\u5de5\u52b3\u52a1\u4e0d\u5206\u7b49\u7ea7":3468,"\u57ce\u5e02\u53ca\u9053\u8def\u7167\u660e\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1586,"\u5730\u57fa\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1399,"\u94a2\u7ed3\u6784\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":3142,"\u516c\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":1672,"\u516c\u8def\u8def\u57fa\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":604,"\u516c\u8def\u8def\u9762\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":592,"\u6cb3\u6e56\u6574\u6cbb\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":836,"\u73af\u4fdd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1983,"\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":3426,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":4131,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":2100,"\u5efa\u7b51\u88c5\u4fee\u88c5\u9970\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":5261,"\u5b89\u8bb8\u8bc1":17827,"\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":2642,"\u94a2\u7b4b\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":2368,"\u6df7\u51dd\u571f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":2619,"\u6728\u5de5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":1506,"\u780c\u7b51\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":518,"\u6c34\u6696\u7535\u5b89\u88c5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1746,"\u6a21\u677f\u811a\u624b\u67b6\u4e13\u4e1a\u627f\u5305\u4e0d\u5206\u7b49\u7ea7":3568,"\u96a7\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":215,"\u710a\u63a5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":945,"\u780c\u7b51\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":2111,"\u673a\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":277,"\u6865\u6881\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":260,"\u8f93\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":720,"\u94a2\u7b4b\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":539,"\u77f3\u5236\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":249,"\u7535\u5b50\u4e0e\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1257,"\u9632\u6c34\u9632\u8150\u4fdd\u6e29\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1605,"\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":627,"\u5efa\u7b51\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1474,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1287,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1137,"\u8d77\u91cd\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":637,"\u94a2\u7ed3\u6784\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1089,"\u62b9\u7070\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1833,"\u94c1\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":114,"\u5730\u57fa\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":640,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":1680,"\u811a\u624b\u67b6\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":401,"\u9884\u62cc\u6df7\u51dd\u571f\u4e13\u4e1a\u627f\u5305\u4e0d\u5206\u7b49\u7ea7":592,"\u901a\u4fe1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":255,"\u6cb9\u6f06\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":861,"\u7535\u529b\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":552,"\u516c\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":228,"\u57ce\u5e02\u53ca\u9053\u8def\u7167\u660e\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":258,"\u5730\u57fa\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":495,"\u5efa\u7b51\u88c5\u4fee\u88c5\u9970\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":971,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":550,"\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":920,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":861,"\u516c\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":497,"\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":160,"\u6cb3\u6e56\u6574\u6cbb\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":310,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":639,"\u6a21\u677f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":130,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u516c\u8def\u5b89\u5168\u8bbe\u65bd\u8d30\u7ea7":449,"\u73af\u4fdd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":288,"\u7279\u79cd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u4e0d\u5206\u7b49\u7ea7":370,"\u6865\u6881\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":239,"\u96a7\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":221,"\u6728\u5de5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":314,"\u673a\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":206,"\u6c34\u5de5\u91d1\u5c5e\u7ed3\u6784\u5236\u4f5c\u4e0e\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":58,"\u6c34\u5229\u6c34\u7535\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":98,"\u77ff\u5c71\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":137,"\u8d77\u91cd\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":132,"\u57ce\u5e02\u53ca\u9053\u8def\u7167\u660e\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":114,"\u9632\u6c34\u9632\u8150\u4fdd\u6e29\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":379,"\u516c\u8def\u8def\u57fa\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":212,"\u516c\u8def\u8def\u9762\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":194,"\u5efa\u7b51\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":337,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":296,"\u77f3\u6cb9\u5316\u5de5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":173,"\u7535\u5b50\u4e0e\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":227,"\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":52,"\u73af\u4fdd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":85,"\u673a\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":407,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u516c\u8def\u673a\u7535\u5de5\u7a0b\u8d30\u7ea7":190,"\u77f3\u6cb9\u5316\u5de5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":193,"\u7535\u529b\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":98,"\u5efa\u7b51\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":414,"\u51b6\u91d1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":48,"\u901a\u4fe1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":53,"\u8f93\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":226,"\u6865\u6881\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":205,"\u96a7\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":142,"\u516c\u8def\u8def\u57fa\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":204,"\u94a2\u7ed3\u6784\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":432,"\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":133,"\u51b6\u91d1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":13,"\u6c34\u5229\u6c34\u7535\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":70,"\u67b6\u7ebf\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":130,"\u94a3\u91d1\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":53,"\u6a21\u677f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":419,"\u7279\u7ea7":2633,"\u710a\u63a5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":206,"\u51b6\u91d1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":26,"\u77ff\u5c71\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":32,"\u94c1\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":17,"\u77ff\u5c71\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":45,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u8d30\u7ea7":38,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u8d30\u7ea7":29,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u8d30\u7ea7":24,"\u6c34\u5de5\u91d1\u5c5e\u7ed3\u6784\u5236\u4f5c\u4e0e\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":55,"\u7535\u529b\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":28,"\u8d77\u91cd\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":74,"\u8f93\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":27,"\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":66,"\u71c3\u6c14\u71c3\u70e7\u5668\u5177\u5b89\u88c5\u3001\u7ef4\u4fee\u4e13\u9879\u8d44\u8d28\u4e0d\u5206\u7b49\u7ea7":15,"\u77f3\u6cb9\u5316\u5de5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":47,"\u94c1\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":19,"\u516c\u8def\u8def\u9762\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":176,"\u673a\u573a\u573a\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":24,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u516c\u8def\u673a\u7535\u5de5\u7a0b\u58f9\u7ea7":42,"\u94c1\u8def\u7535\u6c14\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":15,"\u94c1\u8def\u7535\u52a1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":21,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":15,"\u6c34\u5229\u6c34\u7535\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":3,"\u5efa\u7b51\u88c5\u9970\u88c5\u4fee\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u8d30\u7ea7":57,"\u901a\u822a\u5efa\u7b51\u7269\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1,"\u6e2f\u822a\u8bbe\u5907\u5b89\u88c5\u53ca\u6c34\u4e0a\u4ea4\u7ba1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":87,"\u94c1\u8def\u7535\u6c14\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":9,"\u94c1\u8def\u7535\u52a1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":13,"\u811a\u624b\u67b6\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u8d30\u7ea7":145,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u58f9\u7ea7":16,"\u6838\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":9,"\u6c34\u5de5\u91d1\u5c5e\u7ed3\u6784\u5236\u4f5c\u4e0e\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":13,"\u5efa\u7b51\u88c5\u9970\u88c5\u4fee\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u58f9\u7ea7":14,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u58f9\u7ea7":10,"\u673a\u573a\u76ee\u89c6\u52a9\u822a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":4,"\u51b6\u91d1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":23,"\u94c1\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":29,"\u516c\u8def\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":29,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u516c\u8def\u5b89\u5168\u8bbe\u65bd\u58f9\u7ea7":54,"\u6df7\u51dd\u571f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":8,"\u67b6\u7ebf\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":2,"\u62b9\u7070\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":11,"\u6c34\u6696\u7535\u5b89\u88c5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":6,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u58f9\u7ea7":10,"\u6838\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":7,"\u5730\u57fa\u57fa\u7840\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1,"\u673a\u573a\u76ee\u89c6\u52a9\u822a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":4,"\u6c11\u822a\u7a7a\u7ba1\u5de5\u7a0b\u53ca\u673a\u573a\u5f31\u7535\u7cfb\u7edf\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":3,"\u94c1\u8def\u94fa\u8f68\u67b6\u6881\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":19,"\u77f3\u5236\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":2,"\u6cb9\u6f06\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":1,"\u94a3\u91d1\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u58f9\u7ea7":1,"\u94c1\u8def\u7535\u6c14\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u673a\u573a\u573a\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":7,"\u710a\u63a5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1,"\u94c1\u8def\u7535\u52a1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u811a\u624b\u67b6\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1,"\u6a21\u677f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1,"\u5efa\u7b51\u88c5\u9970\u88c5\u4fee\u5de5\u7a0b\u8bbe\u8ba1\u4e0e\u65bd\u5de5\u4e00\u4f53\u5316\u53c1\u7ea7":2,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":18,"\u56ed\u6797\u7eff\u5316\u6240\u6709\u4e13\u4e1a\u56ed\u6797\u7eff\u5316\u6240\u6709\u5e8f\u5217\u58f9\u7ea7":89,"\u77f3\u6cb9\u5316\u5de5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":3,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":45,"\u8f7b\u578b\u94a2\u7ed3\u6784\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":4,"\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":6,"\u5efa\u7b51\u88c5\u9970\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":53,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":1,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":60,"\u516c\u8def\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":28,"\u5efa\u7b51\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":126,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":2,"\u519c\u6797\u884c\u4e1a(\u519c\u4e1a\u5de5\u7a0b)\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u5e02\u653f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":24,"\u6c34\u5229\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":24,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u58f9\u7ea7":9,"\u5de5\u7a0b\u52d8\u5bdf\u7efc\u5408\u7c7b\u5de5\u7a0b\u52d8\u5bdf\u7efc\u5408\u8d44\u8d28\u7532\u7ea7":94,"\u5efa\u7b51\u884c\u4e1a\uff08\u5efa\u7b51\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":210,"\u56ed\u6797\u7eff\u5316\u6240\u6709\u4e13\u4e1a\u56ed\u6797\u7eff\u5316\u6240\u6709\u5e8f\u5217\u8d30\u7ea7":13,"\u6cb3\u6e56\u6574\u6cbb\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":3,"\u5ca9\u571f\u5de5\u7a0b\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":63,"\u7167\u660e\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":5,"\u5efa\u7b51\u88c5\u9970\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":10,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":11,"\u822a\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":6,"\u6d77\u6d0b\u77f3\u6cb9\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u6c11\u822a\u7a7a\u7ba1\u5de5\u7a0b\u53ca\u673a\u573a\u5f31\u7535\u7cfb\u7edf\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":3,"\u822a\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":3,"\u7279\u79cd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u7ed3\u6784\u8865\u5f3a":3,"\u901a\u4fe1\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":6,"\u5efa\u7b51\u88c5\u9970\u88c5\u4fee\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u58f9\u7ea7":5,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u58f9\u7ea7":2,"\u6d77\u6d0b\u77f3\u6cb9\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u6e2f\u53e3\u4e0e\u6d77\u5cb8\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":5,"\u7167\u660e\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879":1,"\u5de5\u7a0b\u8bbe\u8ba1\u7efc\u5408\u8d44\u8d28\u5de5\u7a0b\u8bbe\u8ba1\u7efc\u5408\u8d44\u8d28\u8bf7\u9009\u62e9":1,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8bf7\u9009\u62e9":2,"\u8f7b\u578b\u94a2\u7ed3\u6784\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8bf7\u9009\u62e9":1,"\u5e02\u653f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":18,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u8d30\u7ea7":1,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u8d30\u7ea7":1,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u58f9\u7ea7":1,"\u5de5\u7a0b\u6d4b\u91cf\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":22,"\u6c34\u6587\u5730\u8d28\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":8,"\u6e2f\u53e3\u4e0e\u6d77\u5cb8\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":7,"\u6c34\u6587\u5730\u8d28\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":10,"\u6e2f\u53e3\u4e0e\u6d77\u5cb8\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":44,"\u5efa\u7b51\u884c\u4e1a\uff08\u5efa\u7b51\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":12,"\u8f7b\u578b\u94a2\u7ed3\u6784\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":1,"\u5e02\u653f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":92,"\u6e2f\u822a\u8bbe\u5907\u5b89\u88c5\u53ca\u6c34\u4e0a\u4ea4\u7ba1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":27,"\u5730\u57fa\u4e0e\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":64,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":43,"\u94a2\u7b4b\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u58f9\u7ea7":3,"\u6df7\u51dd\u571f\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u4e0d\u5206\u7b49\u7ea7":3,"\u62b9\u7070\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u4e0d\u5206\u7b49\u7ea7":3,"\u780c\u7b51\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u58f9\u7ea7":3,"\u901a\u822a\u5efa\u7b51\u7269\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u73af\u5883\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":13,"\u5de5\u7a0b\u8bbe\u8ba1\u7efc\u5408\u8d44\u8d28\u5de5\u7a0b\u8bbe\u8ba1\u7efc\u5408\u8d44\u8d28\u7532\u7ea7":4,"\u77ff\u5c71\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":2,"\u5efa\u7b51\u667a\u80fd\u5316\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":22,"\u5efa\u7b51\u88c5\u4fee\u88c5\u9970\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":43,"\u5730\u57fa\u4e0e\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":21,"\u5730\u57fa\u4e0e\u57fa\u7840\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":25,"\u56ed\u6797\u7eff\u5316\u6240\u6709\u4e13\u4e1a\u56ed\u6797\u7eff\u5316\u6240\u6709\u5e8f\u5217\u53c1\u7ea7":2,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u4ea4\u901a\u5b89\u5168\u8bbe\u65bd":9,"\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":31,"\u9001\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":8,"\u6d88\u9632\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":22,"\u51b6\u70bc\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":6,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":104,"\u5316\u5de5\u77f3\u6cb9\u8bbe\u5907\u7ba1\u9053\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":6,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u58f9\u7ea7":2,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":7,"\u6c34\u5229\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":12,"\u5ca9\u571f\u5de5\u7a0b\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":23,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":1,"\u6c34\u6696\u7535\u5b89\u88c5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u4e0d\u5206\u7b49\u7ea7":2,"\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":52,"\u5efa\u7b51\u9632\u6c34\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":8,"\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":27,"\u5efa\u7b51\u9632\u6c34\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":8,"\u710a\u63a5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u58f9\u7ea7":1,"\u6728\u5de5\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u58f9\u7ea7":1,"\u77f3\u5236\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u4e0d\u5206\u7b49\u7ea7":1,"\u6cb9\u6f06\u4f5c\u4e1a\u52b3\u52a1\u5206\u5305(\u65e7)\u4e0d\u5206\u7b49\u7ea7":1,"\u7279\u79cd\u4e13\u4e1a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u4e0d\u5206\u7b49\u7ea7":7,"\u5efa\u7b51\u88c5\u9970\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8bf7\u9009\u62e9":2,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8d44\u8d28\u7532\u7ea7":1,"\u5efa\u7b51\u88c5\u9970\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8d44\u8d28\u7532\u7ea7":1,"\u9632\u8150\u4fdd\u6e29\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":6,"\u7279\u79cd\u4e13\u4e1a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u5efa\u7b51\u7269\u7ea0\u504f\u548c\u5e73\u79fb":3,"\u7279\u79cd\u4e13\u4e1a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u7ed3\u6784\u8865\u5f3a":4,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":27,"\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":39,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u53c1\u7ea7":35,"\u4f53\u80b2\u573a\u5730\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":12,"\u571f\u77f3\u65b9\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":31,"\u7ba1\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":6,"\u57ce\u5e02\u8f68\u9053\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u4e0d\u5206\u7b49\u7ea7":4,"\u5316\u5de5\u77f3\u6cb9\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":16,"\u571f\u77f3\u65b9\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":32,"\u56ed\u6797\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":13,"\u7206\u7834\u4e0e\u62c6\u9664\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u5efa\u7b51\u5e55\u5899\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":13,"\u571f\u77f3\u65b9\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":31,"\u7535\u5b50\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":4,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u8d30\u7ea7":54,"\u91d1\u5c5e\u95e8\u7a97\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":13,"\u56ed\u6797\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":12,"\u56ed\u6797\u53e4\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":16,"\u6c34\u5de5\u5efa\u7b51\u7269\u57fa\u7840\u5904\u7406\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1,"\u4f53\u80b2\u573a\u5730\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":7,"\u9632\u8150\u4fdd\u6e29\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":7,"\u91d1\u5c5e\u95e8\u7a97\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":6,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u901a\u4fe1\u3001\u76d1\u63a7\u3001\u6536\u8d39\u7efc\u5408\u7cfb\u7edf\u5de5\u7a0b":6,"\u6df7\u51dd\u571f\u9884\u5236\u6784\u4ef6\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":5,"\u7535\u5b50\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":4,"\u7206\u7834\u4e0e\u62c6\u9664\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":4,"\u5824\u9632\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u9632\u8150\u4fdd\u6e29\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":14,"\u9644\u7740\u5347\u964d\u811a\u624b\u67b6\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u5316\u5de5\u77f3\u6cb9\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":10,"\u901a\u822a\u5efa\u7b51\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u7206\u7834\u4e0e\u62c6\u9664\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":3,"\u4f53\u80b2\u573a\u5730\u8bbe\u65bd\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u6c34\u5229\u6c34\u7535\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":1,"\u5824\u9632\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":4,"\u73af\u5883\u5de5\u7a0b\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":2,"\u6df7\u51dd\u571f\u9884\u5236\u6784\u4ef6\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":7,"\u8f7b\u578b\u94a2\u7ed3\u6784\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":1,"\u77f3\u5236\u4f5c\u52b3\u52a1\u5206\u5305\u4e0d\u5206\u7b49\u7ea7":1,"\u5efa\u7b51\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":2,"\u5316\u5de5\u77f3\u6cb9\u8bbe\u5907\u7ba1\u9053\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u7ba1\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":5,"\u706b\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u9001\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u9884\u5e94\u529b\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u9001\u53d8\u7535\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":8,"\u91d1\u5c5e\u95e8\u7a97\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":9,"\u5824\u9632\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u5316\u5de5\u77f3\u6cb9\u8bbe\u5907\u7ba1\u9053\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":3,"\u51b6\u70bc\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u7279\u7ea7":3,"\u7ba1\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":4,"\u77f3\u6cb9\u5929\u7136\u6c14\uff08\u6d77\u6d0b\u77f3\u6cb9\uff09\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u7535\u4fe1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":2,"\u6c34\u5de5\u96a7\u6d1e\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u9884\u62cc\u5546\u54c1\u6df7\u51dd\u571f\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u6c34\u5de5\u5efa\u7b51\u7269\u57fa\u7840\u5904\u7406\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":2,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":166,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b \u76d1\u7406\u7532\u7ea7":19,"\u822a\u9053\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u9884\u62cc\u5546\u54c1\u6df7\u51dd\u571f\u4e13\u4e1a\u627f\u5305\u53c1\u7ea7":3,"\u673a\u68b0\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":10,"\u516c\u8def\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":16,"\u5e02\u653f\uff08\u71c3\u6c14\u5de5\u7a0b\u3001\u8f68\u9053\u4ea4\u901a\u5de5\u7a0b\u9664\u5916\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":18,"\u5de5\u7a0b\u6d4b\u91cf\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":28,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u5efa\u7b51\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":12,"\u9020\u4ef7\u54a8\u8be2\u6240\u6709\u4e13\u4e1a\u9020\u4ef7\u54a8\u8be2\u6240\u6709\u5e8f\u5217\u7532\u7ea7":102,"\u51b6\u70bc\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u7089\u7a91\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u706b\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u51b6\u70bc\u673a\u7535\u8bbe\u5907\u5b89\u88c5\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u9ad8\u8038\u6784\u7b51\u7269\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u7535\u5b50\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":2,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u6536\u8d39\u7cfb\u7edf\u5de5\u7a0b":1,"\u516c\u8def\u4ea4\u901a\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u76d1\u63a7\u7cfb\u7edf\u5de5\u7a0b":1,"\u51b6\u70bc\u5de5\u7a0b\u65bd\u5de5\u603b\u627f\u5305\u58f9\u7ea7":4,"\u7535\u4fe1\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":1,"\u7089\u7a91\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u58f9\u7ea7":5,"\u6c34\u5de5\u5927\u575d\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u9ad8\u8038\u6784\u7b51\u7269\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305\u8d30\u7ea7":1,"\u5efa\u7b51\u88c5\u9970\u88c5\u4fee\u5de5\u7a0b\u4e13\u4e1a\u627f\u5305(\u65e7)\u8d30\u7ea7":2,"\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":27,"\u7efc\u5408\u8d44\u8d28":47,"\u5ca9\u571f\u5de5\u7a0b(\u5206\u9879)\u5de5\u7a0b\u52d8\u5bdf\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u516c\u8def\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":33,"\u6c34\u8fd0\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":2,"\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u7532\u7ea7":102,"\u623f\u5730\u4ea7\u4f30\u4ef7\u673a\u6784\u6240\u6709\u4e13\u4e1a\u623f\u5730\u4ea7\u4f30\u4ef7\u673a\u6784\u8d44\u8d28\u58f9\u7ea7":19,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":24,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":115,"\u51b6\u70bc\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":6,"\u6c34\u5229\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":10,"\u901a\u4fe1\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":4,"\u516c\u8def\u5de5\u7a0b\u76d1\u7406\u4e19\u7ea7":3,"\u5316\u5de5\u77f3\u6cb9\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":14,"\u7535\u529b\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":23,"\u5316\u5de5\u77f3\u6cb9\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":17,"\u5e02\u653f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":6,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b \u76d1\u7406\u4e59\u7ea7":5,"\u516c\u8def\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":8,"\u673a\u7535\u5b89\u88c5\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":22,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u76d1\u7406\u4e19\u7ea7":3,"\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u4e13\u4e1a\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u5e8f\u5217\u58f9\u7ea7":88,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":15,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":7,"\u5efa\u7b51\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":3,"\u94c1\u8def\u5de5\u7a0b \u76d1\u7406\u7532\u7ea7":1,"\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u4e13\u4e1a\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u5e8f\u5217\u53c1\u7ea7":7,"\u6c34\u5229\u6c34\u7535\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":8,"\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u4e59\u7ea7":3,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\uff08\u901a\u4fe1\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u4e13\u4e1a\u7269\u4e1a\u7ba1\u7406\u6240\u6709\u5e8f\u5217\u8d30\u7ea7":24,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":13,"\u5efa\u7b51\u884c\u4e1a\uff08\u5efa\u7b51\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":5,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28":1,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":4,"\u5efa\u7b51\u667a\u80fd\u5316\u7cfb\u7edf\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":1,"\u5efa\u7b51\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":6,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\uff08\u5e7f\u7535\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u51b6\u91d1\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":5,"\u5546\u7269\u7cae\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":2,"\u519c\u6797\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":3,"\u9020\u4ef7\u54a8\u8be2\u6240\u6709\u4e13\u4e1a\u9020\u4ef7\u54a8\u8be2\u6240\u6709\u5e8f\u5217\u8bf7\u9009\u62e9":1,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":26,"\u51b6\u91d1\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":2,"\u77f3\u6cb9\u5929\u7136\u6c14\uff08\u6d77\u6d0b\u77f3\u6cb9\uff09\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":5,"\u519c\u6797\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":3,"\u5316\u5de5\u77f3\u5316\u533b\u836f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":14,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8d44\u8d28\u7532\u7ea7":1,"\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u5de5\u7a0b\u62db\u6807\u4ee3\u7406\u673a\u6784\u6682\u5b9a\u7ea7":3,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":2,"\u6c34\u5229\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":5,"\u73af\u5883\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":4,"\u94c1\u9053\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":2,"\u6c34\u8fd0\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u6c34\u8fd0\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u6c34\u8fd0\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":3,"\u5e02\u653f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":2,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8bf7\u9009\u62e9":3,"\u516c\u8def\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":11,"\u519b\u5de5\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":3,"\u5316\u5de5\u77f3\u5316\u533b\u836f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":7,"\u5efa\u6750\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":2,"\u8f7b\u7eba\u884c\u4e1a\uff08\u7eba\u7ec7\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u5316\u5de5\u77f3\u5316\u533b\u836f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":1,"\u8f7b\u7eba\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":5,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":5,"\u77f3\u6cb9\u5929\u7136\u6c14\uff08\u6d77\u6d0b\u77f3\u6cb9\uff09\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":2,"\u7164\u70ad\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u77ff\u5c71\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":6,"\u94c1\u9053\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u94c1\u9053\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\uff08II\uff09\u7ea7":2,"\u5546\u7269\u7cae\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":1,"\u7535\u529b\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":9,"\u51b6\u91d1\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":3,"\u5546\u7269\u7cae\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u623f\u5c4b\u5efa\u7b51\u5de5\u7a0b\u76d1\u7406\u4e19\u7ea7":2,"\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":3,"\u822a\u5929\u822a\u7a7a\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":2,"\u94c1\u8def\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":12,"\u901a\u4fe1\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":2,"\u8f7b\u7eba\u884c\u4e1a\uff08\u8f7b\u5de5\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":2,"\u77f3\u6cb9\u5929\u7136\u6c14\uff08\u6d77\u6d0b\u77f3\u6cb9\uff09\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":1,"\u77ff\u5c71\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":3,"\u94c1\u8def\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":2,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u7532\u7ea7":7,"\u901a\u4fe1\u5de5\u7a0b\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u5efa\u7b51\u88c5\u9970\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":1,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e19\u7ea7":1,"\u5efa\u6750\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u5e02\u653f\u516c\u7528\u5de5\u7a0b\u76d1\u7406\u4e19\u7ea7":1,"\u51b6\u70bc\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":1,"\u5efa\u7b51\u8bbe\u8ba1\u4e8b\u52a1\u6240\u5efa\u7b51\u5de5\u7a0b\u8bbe\u8ba1\u4e8b\u52a1\u6240\u8d44\u8d28\u7532\u7ea7":2,"\u5316\u5de5\u77f3\u5316\u533b\u836f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u7535\u5b50\u901a\u4fe1\u5e7f\u7535\u884c\u4e1a\uff08\u901a\u4fe1\u5de5\u7a0b\uff09\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u7532\uff08I\uff09\u7ea7":1,"\u98ce\u666f\u56ed\u6797\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u4e59\u7ea7":3,"\u6e2f\u53e3\u4e0e\u822a\u9053\u5de5\u7a0b\u76d1\u7406\u7532\u7ea7":1,"\u7535\u529b\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u8bf7\u9009\u62e9":1,"\u5efa\u6750\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u4e1a\u8d44\u8d28\u7532\u7ea7":1,"\u5316\u5de5\u77f3\u5316\u533b\u836f\u884c\u4e1a\u5de5\u7a0b\u8bbe\u8ba1\u884c\u4e1a\u8d44\u8d28\u4e59\u7ea7":1,"\u73af\u5883\u5de5\u7a0b\u5de5\u7a0b\u8bbe\u8ba1\u4e13\u9879\u8bf7\u9009\u62e9":1,"\u822a\u5929\u822a\u7a7a\u5de5\u7a0b\u76d1\u7406\u4e59\u7ea7":1}
';

    public $specialJsonData = '{"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2015":255,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5:B":1484,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u76d1\u7406 \u62db\u6807\u4ee3\u7406:B":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u65bd\u5de5:AA":39,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5:B":55,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2010":193,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2013":263,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2014":268,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2014":22,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2015":28,"\u6c34\u5229\u90e8\u4fe1\u7528:\u65bd\u5de5:AAA":64,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u52d8\u5bdf\u8bbe\u8ba1:B":8,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u8bbe\u5907\u4f9b\u5e94\u5546:B":6,"\u6c34\u5229\u90e8\u4fe1\u7528:\u65bd\u5de5:BBB":11,"\u6c34\u5229\u90e8\u4fe1\u7528:\u65bd\u5de5:A":22,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2011":211,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2012":39,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2010":31,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u54a8\u8be2 \u8bbe\u5907\u4f9b\u5e94\u5546:B":1,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2011":53,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:A:2012":245,"\u6c34\u5229\u90e8\u4fe1\u7528:\u8d28\u91cf\u68c0\u6d4b:AAA":8,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2014":54,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2015":66,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5:A":11,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5 \u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u8bbe\u5907\u4f9b\u5e94\u5546:B":6,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5:AA":12,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2013":33,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5:AA":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u673a\u68b0\u5236\u9020:A":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1:B":2,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2010":42,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2013":40,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2 \u8bbe\u5907\u4f9b\u5e94\u5546:B":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u673a\u68b0\u5236\u9020:AAA":6,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u52d8\u5bdf\u8bbe\u8ba1:B":3,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u52d8\u5bdf\u8bbe\u8ba1:B":23,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5 \u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u52d8\u5bdf\u8bbe\u8ba1 \u8bbe\u5907\u4f9b\u5e94\u5546:B":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u65bd\u5de5:CCC":3,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:B:2012":43,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:C:2011":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u6c34\u8def:\u65bd\u5de5:B":12,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u54a8\u8be2:AAA":20,"\u6c34\u5229\u90e8\u4fe1\u7528:\u8bbe\u8ba1:AAA":24,"\u6c34\u5229\u90e8\u4fe1\u7528:\u52d8\u5bdf:AAA":22,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u673a\u68b0\u5236\u9020:AA":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u54a8\u8be2 \u62db\u6807\u4ee3\u7406:B":1,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:AA:2011":30,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5:C":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u68c0\u6d4b:B":4,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:C:2012":3,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u76d1\u7406:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u68c0\u6d4b:AA":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u76d1\u7406:AA":5,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:C:2010":6,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5 \u76d1\u7406 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u76d1\u7406:AAA":16,"\u6c34\u5229\u90e8\u4fe1\u7528:\u8bbe\u8ba1:A":2,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:C:2015":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5:D":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1:B":1,"\u516c\u8def\u4fe1\u7528\u7b49\u7ea7:D:2010":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u8bbe\u5907\u4f9b\u5e94\u5546:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u65bd\u5de5 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406:B":9,"\u6c34\u5229\u90e8\u4fe1\u7528:\u8bbe\u8ba1:AA":6,"\u6c34\u5229\u90e8\u4fe1\u7528:\u62db\u6807\u4ee3\u7406:AA":8,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2 \u62db\u6807\u4ee3\u7406:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":16,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:A":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u62db\u6807\u4ee3\u7406:A":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u52d8\u5bdf:AA":5,"\u6c34\u5229\u90e8\u4fe1\u7528:\u62db\u6807\u4ee3\u7406:AAA":5,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u68c0\u6d4b:B":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u54a8\u8be2:AA":4,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u76d1\u7406 \u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":3,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u76d1\u7406 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":2,"\u6c34\u5229\u90e8\u4fe1\u7528:\u8d28\u91cf\u68c0\u6d4b:AA":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u65bd\u5de5 \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2 \u62db\u6807\u4ee3\u7406:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u76d1\u7406:B":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u68c0\u6d4b \u54a8\u8be2:B":1,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def:\u76d1\u7406 \u54a8\u8be2 \u62db\u6807\u4ee3\u7406:B":1,"\u6c34\u5229\u90e8\u4fe1\u7528:\u76d1\u7406:A":2,"\u56db\u5ddd\u4ea4\u901a\u4fe1\u7528:\u516c\u8def \u6c34\u8def:\u68c0\u6d4b \u52d8\u5bdf\u8bbe\u8ba1 \u54a8\u8be2 \u62db\u6807\u4ee3\u7406:B":1}';

    function __construct()
    {
        $this->companyFinalCollection = $this->getDb()->build_info1->company_final;//最终结果
        $this->companyFormatedCollection = $this->getDb()->build_info1->company_formated;//最终结果格式化
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

    function getPersonClass()
    {
        $page = 0;
        $limit = 100;
        $count = $this->companyFinalCollection->count();
        $certs = [];
        while ($count > $page * $limit) {
            $items = $this->companyFinalCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['person'])) {
                    continue;
                }
                foreach ($item['person'] as $person) {
                    if (empty($person['cert'])) {
                        continue;
                    }
                    foreach ($person['cert'] as $cert) {
                        if ($cert == '') {
//                            print_r($person)."\n";
//                            echo $item['compName']."\n";
//                            echo $page . "\n";
                            continue;
                        }
//                        if(strcmp('',$cert)===0){
//                            print_r($item);exit;
//                        }
                        if (!empty($certs[$cert])) {
                            $certs[$cert] = $certs[$cert] + 1;
                        } else {
                            $certs[$cert] = 1;
                        }
                    }
                }
            }
            $page++;
            echo $page . "\n";
        }
        $this->personJsonData = json_encode($certs) . "\n";
        echo $this->personJsonData;
        return $certs;
    }

    function formatPersonCert($certs)
    {
        $personCerts = [];
//        print_r($certs);
        foreach ($certs as $cert) {
            if (strpos($cert, '二级注册建造师') !== false || strpos($cert, '二级临时注册建造师') !== false) {
                $major = str_replace('二级临时注册建造师', '', $cert);
                $major = str_replace('二级注册建造师', '', $major);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "二级注册建造师:" . $major;

            } elseif (strpos($cert, '一级注册建造师') !== false || strpos($cert, '一级临时注册建造师') !== false) {
                $major = str_replace('一级临时注册建造师', '', $cert);
                $major = str_replace('一级注册建造师', '', $major);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "一级注册建造师:" . $major;

            } elseif (strpos($cert, '注册造价工程师') !== false || strpos($cert, '造价工程师') !== false) {
                $major = str_replace('注册造价工程师', '', $cert);
                $major = str_replace('造价工程师', '', $major);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "注册造价工程师:" . $major;

            } elseif (strpos($cert, '注册结构工程师') !== false) {
                $major = str_replace('注册结构工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "注册结构工程师:" . $major;

            } elseif (strpos($cert, '水利五大员') !== false) {
                $major = str_replace('水利五大员:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "水利五大员:" . $major;

            } elseif (strpos($cert, '注册安全工程师') !== false) {
                $major = str_replace('注册安全工程师:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "注册安全工程师:" . $major;

            } elseif (strpos($cert, '注册电气工程师') !== false) {
                $major = str_replace('注册电气工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $personCerts[] = "注册电气工程师:" . $major;

            } elseif (strpos($cert, '注册土木工程师') !== false) {
                $major = str_replace('注册土木工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $personCerts[] = "注册土木工程师:" . $major;

            } elseif (strpos($cert, '注册化工工程师') !== false) {
                $certMerged['subs']['注册化工工程师']['text'] = '注册土木工程师';
                $personCerts[] = "注册化工工程师";

            } elseif (strpos($cert, '注册公用设备工程师') !== false) {
                $major = str_replace('注册公用设备工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $personCerts[] = "注册公用设备工程师:" . $major;

            } elseif (strpos($cert, '安全评价师') !== false) {
                $major = str_replace('安全评价师:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $personCerts[] = "安全评价师:" . $major;

            } elseif (strpos($cert, '安考证') !== false) {
                if (strpos($cert, '建') !== false) {
                    $major = '建安';
                } elseif (strpos($cert, '交') !== false) {
                    $major = '交安';
                } elseif (strpos($cert, '水') !== false) {
                    $major = '水安';
                }
                if ($major == "") {
                    continue;
                }
                if (strpos($cert, 'A') !== false) {
                    $level = 'A';
                } elseif (strpos($cert, 'B') !== false) {
                    $level = 'B';
                } elseif (strpos($cert, 'C') !== false) {
                    $level = 'C';
                }
                if ($level == "") {
                    continue;
                }
                $personCerts[] = "安考证:" . $major . ":" . $level;
//                $personCerts[] = "安考证:" . $level;

            }
            if (strpos($cert, '注册监理工程师') !== false || strpos($cert, '注册建筑师') !== false) {
                continue;
            }
        }
        return $personCerts;
    }

    function mergePersonClasses()
    {
//        $this->getPersonClass();
        $certs = json_decode($this->personJsonData, true);
        arsort($certs);
        $certMerged = ['subPlaceHolder' => '请选择证书类型'];
        foreach ($certs as $cert => $count) {
            if (strpos($cert, '二级注册建造师') !== false || strpos($cert, '二级临时注册建造师') !== false) {
                $major = str_replace('二级临时注册建造师', '', $cert);
                $major = str_replace('二级注册建造师', '', $major);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['二级注册建造师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['二级注册建造师']['text'] = '二级注册建造师';
                $certMerged['subs']['二级注册建造师']['subs'][$major]['subPlaceHolder'] = '带安考证';
                $certMerged['subs']['二级注册建造师']['subs'][$major]['text'] = $major;
                $certMerged['subs']['二级注册建造师']['subs'][$major]['subs'] = [['text' => '不要求'], ['text' => '任意安B'], ['text' => '建安B'], ['text' => '交安B'], ['text' => '水安B']];
                unset($certs[$cert]);
            } elseif (strpos($cert, '一级注册建造师') !== false || strpos($cert, '一级临时注册建造师') !== false) {
                $major = str_replace('一级临时注册建造师', '', $cert);
                $major = str_replace('一级注册建造师', '', $major);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['一级注册建造师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['一级注册建造师']['text'] = '一级注册建造师';
                $certMerged['subs']['一级注册建造师']['subs'][$major]['subPlaceHolder'] = '带安考证';
                $certMerged['subs']['一级注册建造师']['subs'][$major]['text'] = $major;
                $certMerged['subs']['一级注册建造师']['subs'][$major]['subs'] = [['text' => '不要求'], ['text' => '任意安B'], ['text' => '建安B'], ['text' => '交安B'], ['text' => '水安B']];
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册造价工程师') !== false || strpos($cert, '造价工程师') !== false) {
                $major = str_replace('注册造价工程师', '', $cert);
                $major = str_replace('造价工程师', '', $major);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['注册造价工程师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['注册造价工程师']['text'] = '注册造价工程师';
                $certMerged['subs']['注册造价工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册结构工程师') !== false) {
                $major = str_replace('注册结构工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['注册结构工程师']['subPlaceHolder'] = '请选择等级';
                $certMerged['subs']['注册结构工程师']['text'] = '注册结构工程师';
                $certMerged['subs']['注册结构工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '水利五大员') !== false) {
                $major = str_replace('水利五大员:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['水利五大员']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['水利五大员']['text'] = '水利五大员';
                $certMerged['subs']['水利五大员']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册安全工程师') !== false) {
                $major = str_replace('注册安全工程师:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['注册安全工程师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['注册安全工程师']['text'] = '注册安全工程师';
                $certMerged['subs']['注册安全工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册电气工程师') !== false) {
                $major = str_replace('注册电气工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $certMerged['subs']['注册电气工程师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['注册电气工程师']['text'] = '注册电气工程师';
                $certMerged['subs']['注册电气工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册土木工程师') !== false) {
                $major = str_replace('注册土木工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $certMerged['subs']['注册土木工程师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['注册土木工程师']['text'] = '注册土木工程师';
                $certMerged['subs']['注册土木工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册化工工程师') !== false) {
                $certMerged['subs']['注册化工工程师']['text'] = '注册土木工程师';
                unset($certs[$cert]);
            } elseif (strpos($cert, '注册公用设备工程师') !== false) {
                $major = str_replace('注册公用设备工程师', '', $cert);
                if ($major == "") {
                    continue;
                }
                $major = str_replace('（', '', $major);
                $major = str_replace('）', '', $major);
                $certMerged['subs']['注册公用设备工程师']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['注册公用设备工程师']['text'] = '注册公用设备工程师';
                $certMerged['subs']['注册公用设备工程师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '安全评价师') !== false) {
                $major = str_replace('安全评价师:', '', $cert);
                if ($major == "") {
                    continue;
                }
                $certMerged['subs']['安全评价师']['text'] = '安全评价师';
                $certMerged['subs']['安全评价师']['subPlaceHolder'] = '请选择等级';
                $certMerged['subs']['安全评价师']['subs'][$major]['text'] = $major;
                unset($certs[$cert]);
            } elseif (strpos($cert, '安考证') !== false) {
                if (strpos($cert, '建') !== false) {
                    $major = '建安';
                } elseif (strpos($cert, '交') !== false) {
                    $major = '交安';
                } elseif (strpos($cert, '水') !== false) {
                    $major = '水安';
                }
                if ($major == "") {
                    continue;
                }
                if (strpos($cert, 'A') !== false) {
                    $level = 'A';
                } elseif (strpos($cert, 'B') !== false) {
                    $level = 'B';
                } elseif (strpos($cert, 'C') !== false) {
                    $level = 'C';
                }
                if ($level == "") {
                    continue;
                }
                $certMerged['subs']['安考证']['text'] = '安考证';
                $certMerged['subs']['安考证']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['安考证']['subs'][$major]['text'] = $major;
                $certMerged['subs']['安考证']['subs'][$major]['subPlaceHolder'] = '请选择级别';
                $certMerged['subs']['安考证']['subs'][$major]['subs'][$level] = ['text' => $level];
                unset($certs[$cert]);
            }
            if (strpos($cert, '注册监理工程师') !== false || strpos($cert, '注册建筑师') !== false) {
                unset($certs[$cert]);
            }

        }
        $certMerged = $this->subsFormator($certMerged);
        return $certMerged;
        exit;
    }


    function getZizhiClass()
    {
        $page = 0;
        $limit = 100;
        $count = $this->companyFinalCollection->count();
        $certs = [];
        while ($count > $page * $limit) {
            $items = $this->companyFinalCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['zizhi'])) {
                    continue;
                }
                foreach ($item['zizhi'] as $cert) {
                    if (empty($cert)) {
                        continue;
                    }
//                    if ($cert == '特级') {
//                            print_r($cert)."\n";
//                            echo $item['compName']."\n";
//                            echo $page . "\n";
//                        continue;
//                    }
//                        if(strcmp('',$cert)===0){
//                            print_r($item);exit;
//                        }
                    if (!empty($certs[$cert])) {
                        $certs[$cert] = $certs[$cert] + 1;
                    } else {
                        $certs[$cert] = 1;
                    }
                }
            }
            $page++;
            echo $page . "\n";
        }
        $this->zizhiJsonData = json_encode($certs) . "\n";
        echo $this->zizhiJsonData;
        exit;
        return $certs;
    }

    function formatZizhiClasses($certs)
    {
        $zizhis = [];
        foreach ($certs as $cert) {
            if (strpos($cert, '专业承包') !== false) {
                if (preg_match('/(.+)专业承包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $zizhis[] = '专业承包:' . $major . ":" . $level;
            } elseif (strpos($cert, '施工总承包') !== false) {
                if (preg_match('/(.+)施工总承包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $zizhis[] = '施工总承包:' . $major . ":" . $level;
            } elseif (strpos($cert, '劳务分包') !== false) {
                if (preg_match('/(.+)劳务分包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $zizhis[] = '劳务分包:' . $major . ":" . $level;
            } elseif (strpos($cert, '施工劳务') !== false) {
                $zizhis[] = '施工劳务:不分等级';
            } elseif (strpos($cert, '安许证') !== false || strpos($cert, '工程设计') !== false || strpos($cert, '监理') !== false || strpos($cert, '招标') !== false || strpos($cert, '咨询') !== false || strpos($cert, '勘察') !== false) {
                continue;
            }

        }
        return $zizhis;
    }

    function mergeZizhiClasses()
    {
//        $this->getZizhiClass();exit;
        $certs = json_decode($this->zizhiJsonData, true);
        arsort($certs);
        $certMerged = ['subPlaceHolder' => '请选择资质类型'];
        foreach ($certs as $cert => $count) {
            if (strpos($cert, '专业承包') !== false) {
                if (preg_match('/(.+)专业承包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $certMerged['subs']['专业承包']['text'] = '专业承包';
                $certMerged['subs']['专业承包']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['专业承包']['subs'][$major]['text'] = $major;
                $certMerged['subs']['专业承包']['subs'][$major]['subPlaceHolder'] = '等级';
                $certMerged['subs']['专业承包']['subs'][$major]['subs'][$level] = ['text' => $level];
                unset($certs[$cert]);
            } elseif (strpos($cert, '施工总承包') !== false) {
                if (preg_match('/(.+)施工总承包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $certMerged['subs']['施工总承包']['text'] = '施工总承包';
                $certMerged['subs']['施工总承包']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['施工总承包']['subs'][$major]['text'] = $major;
                $certMerged['subs']['施工总承包']['subs'][$major]['subPlaceHolder'] = '等级';
                $certMerged['subs']['施工总承包']['subs'][$major]['subs'][$level] = ['text' => $level];
                unset($certs[$cert]);
            } elseif (strpos($cert, '劳务分包') !== false) {
                if (preg_match('/(.+)劳务分包(.+)/', $cert, $matches)) {
                    if (count($matches) == 3) {
                        $major = $matches[1];
                        $level = $matches[2];
                    } else {
                        continue;
                    }
                } else {
                    continue;
                }
                $certMerged['subs']['劳务分包']['text'] = '劳务分包';
                $certMerged['subs']['劳务分包']['subPlaceHolder'] = '请选择专业';
                $certMerged['subs']['劳务分包']['subs'][$major]['text'] = $major;
                $certMerged['subs']['劳务分包']['subs'][$major]['subPlaceHolder'] = '等级';
                $certMerged['subs']['劳务分包']['subs'][$major]['subs'][$level] = ['text' => $level];
                unset($certs[$cert]);
            } elseif (strpos($cert, '安许证') !== false || strpos($cert, '施工劳务') !== false || strpos($cert, '工程设计') !== false || strpos($cert, '监理') !== false || strpos($cert, '招标') !== false || strpos($cert, '咨询') !== false || strpos($cert, '勘察') !== false) {
                unset($certs[$cert]);
                continue;
            }

        }
        $certMerged = $this->subsFormator($certMerged);
        return $certMerged;
        print_r($certMerged);
    }


    function getSpecitalClass()
    {
        $page = 0;
        $limit = 100;
        $count = $this->companyFinalCollection->count();
        $certs = [];
        while ($count > $page * $limit) {
            $items = $this->companyFinalCollection->find(['special' => ['$exists' => true]], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                if (empty($item['special'])) {
                    continue;
                }
                foreach ($item['special'] as $cert) {
                    if (empty($cert)) {
                        continue;
                    }
//                    if ($cert == '特级') {
//                            print_r($cert)."\n";
//                            echo $item['compName']."\n";
//                            echo $page . "\n";
//                        continue;
//                    }
//                        if(strcmp('',$cert)===0){
//                            print_r($item);exit;
//                        }
                    if (!empty($certs[$cert])) {
                        $certs[$cert] = $certs[$cert] + 1;
                    } else {
                        $certs[$cert] = 1;
                    }
                }
            }
            $page++;
            echo $page . "\n";
        }
        $this->specialJsonData = json_encode($certs) . "\n";
//        echo $this->specialJsonData;
//        exit;
        return $certs;
    }

    function mergeSpecialClasses()
    {
        $dataByHand = [
            'subPlaceHolder' => '请选择特殊条件',
            'subs' => [
                ['text' => '四川交通信用', 'subPlaceHolder' => '请选择专业', 'subs' => [
                    ['text' => '公路', 'subPlaceHolder' => '请选择等级', 'subs' => [
                        ['text' => 'AA'], ['text' => 'A'], ['text' => 'B'], ['text' => 'C'], ['text' => 'D'],
                    ]],
                    ['text' => '水路', 'subPlaceHolder' => '请选择等级', 'subs' => [
                        ['text' => 'AA'], ['text' => 'A'], ['text' => 'B'], ['text' => 'C'], ['text' => 'D'],
                    ]],
                ]],
                ['text' => '全国公路信用等级', 'subPlaceHolder' => '请选择等级', 'subs' => [
                    ['text' => 'AA'], ['text' => 'A'], ['text' => 'B'], ['text' => 'C'], ['text' => 'D'],
                ]],
                ['text' => '水利部信用等级', 'subPlaceHolder' => '请选择等级', 'subs' => [
                    ['text' => 'AAA'], ['text' => 'AA'], ['text' => 'A'], ['text' => 'BBB'], ['text' => 'CCC'],
                ]],
            ]
        ];
        $certMerged = $this->subsFormator($dataByHand);
        return $certMerged;
        print_r($certMerged);
        exit;
//        $this->getZizhiClass();exit;
        $certs = json_decode($this->specialJsonData, true);
        arsort($certs);
        $certMerged = ['subPlaceHolder' => '请选择特殊条件'];
        foreach ($certs as $cert => $count) {
            $fields = explode(":", $cert);
            if (strcmp($fields[0], '公路信用等级') === 0) {
                $level = $fields[1];
                $certMerged['subs']['全国公路信用等级']['text'] = '全国公路信用等级';
                $certMerged['subs']['全国公路信用等级']['subPlaceHolder'] = '请选择等级';
                $certMerged['subs']['全国公路信用等级']['subs'][$level]['text'] = $level;
                unset($certs[$cert]);
            } elseif (strcmp($fields[0], '四川交通信用') === 0) {
                $majorStr = $fields[1];
                $majors = explode(' ', $majorStr);
                if (strpos($fields[2], '施工') === false) {
                    continue;
                }
                foreach ($majors as $major) {
                    $level = $fields[3];
                    $certMerged['subs']['四川交通信用']['text'] = '四川交通信用';
                    $certMerged['subs']['四川交通信用']['subPlaceHolder'] = '请选择专业';
                    $certMerged['subs']['四川交通信用']['subs'][$major]['text'] = $major;
                    $certMerged['subs']['四川交通信用']['subs'][$major]['subPlaceHolder'] = '等级';
                    $certMerged['subs']['四川交通信用']['subs'][$major]['subs'][$level] = ['text' => $level];
                }
                unset($certs[$cert]);
            } elseif (strcmp($fields[0], '水利部信用') === 0) {
                if (strpos($fields[1], '施工') === false) {
                    continue;
                }
                $level = $fields[2];
                $certMerged['subs']['水利部信用']['text'] = '水利部信用';
                $certMerged['subs']['水利部信用']['subPlaceHolder'] = '请选择等级';
                $certMerged['subs']['水利部信用']['subs'][$level]['text'] = $level;
                unset($certs[$cert]);
            }
        }
        $certMerged = $this->subsFormator($certMerged);
        return $certMerged;
        print_r($certMerged);
    }

    function formatSpecialClasses($certs)
    {
        $specials = [];
        foreach ($certs as $cert) {
            $fields = explode(":", $cert);
            if (strcmp($fields[0], '公路信用等级') === 0) {
                $level = $fields[1];
                $specials[] = "全国公路信用等级:" . $level;
            } elseif (strcmp($fields[0], '四川交通信用') === 0) {
                $majorStr = $fields[1];
                $majors = explode(' ', $majorStr);
                if (strpos($fields[2], '施工') === false) {
                    continue;
                }
                foreach ($majors as $major) {
                    $level = $fields[3];
                    $specials[] = "四川交通信用:" . $major . ":" . $level;
                }
            } elseif (strcmp($fields[0], '水利部信用') === 0) {
                if (strpos($fields[1], '施工') === false) {
                    continue;
                }
                $level = $fields[2];
                $specials[] = "水利部信用:" . $level;
            }
        }
        return $specials;
    }

    function getAllClasses()
    {
        $this->getFormated();
//        $allArr = [];
//        $allArr['zizhi'] = $this->mergeZizhiClasses();
//        $allArr['person'] = $this->mergePersonClasses();
//        $allArr['special'] = $this->mergeSpecialClasses();
//        echo json_encode($allArr);
    }

    function sortByText($a, $b)
    {
        $v1 = iconv('utf-8', 'gbk', $a['text']);
        $v2 = iconv('utf-8', 'gbk', $b['text']);
        return (strcmp($v1, $v2) > 0);
    }

    function subsFormator($certMerged, $level = 0)
    {
        $level++;
        if (!empty($certMerged['subs'])) {
            if ($level == 2) {
                usort($certMerged['subs'], array('\BuildInfo\tool\GetFormClasses', 'sortByText'));
            }
            if ($level == 3) {
                $subs = [];
                foreach ($certMerged['subs'] as $sub3) {
                    if (strpos($sub3['text'], '特级') !== false) {
                        $subs[0] = $sub3;
                    } elseif (strpos($sub3['text'], '壹级') !== false) {
                        $subs[1] = $sub3;
                    } elseif (strpos($sub3['text'], '贰级') !== false) {
                        $subs[2] = $sub3;
                    } elseif (strpos($sub3['text'], '叁级') !== false) {
                        $subs[3] = $sub3;
                    } else {
                        $subs[] = $sub3;
                    }
                }
                ksort($subs);
                $certMerged['subs'] = $subs;
            }
            $certMerged['subs'] = array_values($certMerged['subs']);
            foreach ($certMerged['subs'] as &$sub) {
                $sub = $this->subsFormator($sub, $level);
            }
        }
        return $certMerged;
    }

    function getFormated()
    {
        echo "getFormated\n";
        $page = 0;
        $limit = 100;
        $count = $this->companyFinalCollection->count();
        while ($count > $page * $limit) {
            $items = $this->companyFinalCollection->find([], [
                'limit' => $limit,
                'sort' => ['_id' => 1],
                'skip' => $page * $limit
            ])->toArray();
            foreach ($items as $item) {
                $company = $item;
                $company['zizhi'] = $this->formatZizhiClasses($item['zizhi']);
                $company['special'] = $this->formatSpecialClasses($item['special']);
//                print_r($company);exit;
                $persons = [];
                if (!empty($item['person'])) {
                    foreach ($item['person'] as $person) {
                        $person['cert'] = $this->formatPersonCert($person['cert']);
                        $persons[] = $person;
                    }
                }
                $company['person'] = $persons;
                if ($this->companyFormatedCollection->findOne(['compName' => ($company['compName'])])) {
                    $this->companyFormatedCollection->updateOne(['compName' => ($company['compName'])], ['$set' => $company]);
                } else {
                    $this->companyFormatedCollection->insertOne($company);
                }
            }
            $page++;
            echo $page . "\n";
        }
    }
}