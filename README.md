# huiju_pay
汇聚支付相关SDK

## Installation

Use [Composer](https://getcomposer.org/) to install the library.

``` bash
composer require xwzj/wechat
```

## 聚合支付

```php
<?php

require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'6221db6e83634f779b632dd77d188f64', //商户秘钥
    'q7_AppId'=>'wxe8f0cf6923789bc5', //应用的appid
    'p1_MerchantNo'=>'888108900001969' //商户编号
    ];

$post = [
    'p2_OrderNo'=>'frtesgr42t54664536543654', //订单号
    'p3_Amount'=>'0.01', //支付金额，单位分
    'p5_ProductName'=>'测试支付', //商品名
    'p9_NotifyUrl'=>'http://test.huang-dou.com/other/v1/paynotify', //服务器异步通知url
    'q1_FrpCode'=>'WEIXIN_XCX', //交易类型
    'q5_OpenId'=>'oJfTy5EDSL9gWCYyEfxhIniHa7x8'  //用户的openid
];

$result = (new \JoinPay\pay\JoinPay($config))->getPayInfo($post);

var_dump(json_decode($result,true));

```