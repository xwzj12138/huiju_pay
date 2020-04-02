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
$post = [
    'productCode'=>'产品类型',
    'merchantOrderNo'=>'支付订单号',
    'receiverAccountNoEnc'=>'收款账户号',
    'receiverNameEnc'=>'收款人或公司名',
    'receiverAccountType'=>'账户类型',
    'receiverBankChannelNo'=>'收款账户联行号，对公必填',
    'paidAmount'=>'支付金额',
    'paidDesc'=>'代付说明',
    'paidUse'=>'代付用途',
    'firstProductCode'=>'优先使用产品,选填'
];
$result = (new JoinPay\pay\payment\SinglePay($config))->payment($post);
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
    'productCode'=>'产品类型',
    'merchantBatchNo'=>'商户批量代付订单号',
    'callbackUrl'=>'回调url,选填',
    'firstProductCode'=>'优先使用产品，选填',
    'details'=>[
        //对公代付
        ['userNo'=>'商户编号','merchantOrderNo'=>'商户订单号(不能与批量代付及其他代付订单号相同)','receiverAccountNoEnc'=>'收款账户号',
            'receiverNameEnc'=>'对公收款账户的公司名','receiverAccountType'=>'204','receiverBankChannelNo'=>'收款账户联行号',
            'paidAmount'=>'交易金额','paidDesc'=>'代付说明','paidUse'=>'代付用途','isChecked'=>'是否复核,默认202不复核',
            'currency'=>'币种,默认人民币'
        ],
        //对私代付
        ['userNo'=>'商户编号','merchantOrderNo'=>'商户订单号(不能与批量代付及其他代付订单号相同)','receiverAccountNoEnc'=>'收款账户号',
            'receiverNameEnc'=>'收款人','receiverAccountType'=>'201', 'paidAmount'=>'交易金额','paidDesc'=>'代付说明','paidUse'=>'代付用途',
            'isChecked'=>'是否复核,默认202不复核','currency'=>'币种,默认人民币'
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