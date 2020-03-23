# huiju_pay
汇聚支付相关SDK

## 官方文档
使用前请先观看[官方文档](https://www.joinpay.com/open-platform/pages/platform.html)，了解每一个字段的作用
## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
composer require xwzj/wechat
```

## 聚合支付

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'AppId'=>'微信公众号，小程序等微信平台的appid',
    'merchantNo'=>'商户编号'
];

$post = [
    'p2_OrderNo'=>'frtesgr42t54664536543654',
    'p3_Amount'=>'0.01',
    'p5_ProductName'=>'测试支付',
    'p9_NotifyUrl'=>'异步通知url',
    'q1_FrpCode'=>'WEIXIN_XCX',
    'q5_OpenId'=>'微信平台的openid'
];
$result = (new \JoinPay\pay\trade\JoinPay($config))->getPayInfo($post);
echo $result;
//var_dump(json_decode($result,true));

```

## 聚合支付订单查询示例

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = ['partnerkey'=>'商户秘钥','merchantNo'=>'商户编号'];

$result = (new \JoinPay\pay\trade\QueryJoinOrder($config))->getInfo('frtesgr42t54664536543654');

var_dump(json_decode($result,true));

```

## 聚合支付退款示例代码

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = ['partnerkey'=>'商户秘钥','merchantNo'=>'商户编号'];

$post = [
    'p2_OrderNo'=>'frtesgr42t54664536543654',
    'p3_RefundOrderNo'=>'cdsgerwt54brt5t34354',
    'p4_RefundAmount'=>'0.01',
    'p6_NotifyUrl'=>'异步通知url'
];

$result = (new \JoinPay\pay\trade\JoinRefund($config))->refund($post);

var_dump(json_decode($result,true));

```

## 单笔代付示例代码

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$result = (new \JoinPay\pay\payment\SinglePayQuery($config))->query('151651544213541414');
echo $result;
//var_dump(json_decode($result,true));

```

## 单笔代付查询代码

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$result = (new \JoinPay\pay\payment\SinglePayQuery($config))->query('151651544213541414');
echo $result;
//var_dump(json_decode($result,true));

```

## 批量代付

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$post = [
    'productCode'=>'BANK_PAY_DAILY_ORDER',
    'merchantBatchNo'=>'订单号',
    'details'=>[
        ['userNo'=>$config['merchantNo'],'merchantOrderNo'=>'订单号','receiverAccountNoEnc'=>'104100000004',
            'receiverNameEnc'=>'广州佳品接龙网络科技有限公司','receiverAccountType'=>'204','receiverBankChannelNo'=>'104100000004',
            'paidAmount'=>1,'paidDesc'=>'测试代付','paidUse'=>'202'
        ],
        ['userNo'=>$config['merchantNo'],'merchantOrderNo'=>'订单号','receiverAccountNoEnc'=>'6212263602112155759',
            'receiverNameEnc'=>'陈星星','receiverAccountType'=>'201', 'paidAmount'=>1,'paidDesc'=>'测试代付','paidUse'=>'202'
        ]
    ]
];
$result = (new JoinPay\pay\payment\BatchPay($config))->payment($post);
//var_dump(json_decode($result,true));

```

## 批量代付查询

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];


$result = (new JoinPay\pay\payment\BatchPayQuery($config))->query('商户批次号');
//var_dump(json_decode($result,true));

```

## 账户查询示例代码

```php
<?php
//框架使用时可以忽略这一行
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$result = (new \JoinPay\pay\payment\AccountBalanceQuery($config))->query();

echo $result;

//var_dump(json_decode($result,true));

```