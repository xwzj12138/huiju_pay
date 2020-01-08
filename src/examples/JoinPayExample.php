<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */

require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'6221db6e83634f779b632dd77d188f64',
    'AppId'=>'wxe8f0cf6923789bc5',
    'merchantNo'=>'888108900001969'
];

$post = [
    'p2_OrderNo'=>'frtesgr42t54664536543654',
    'p3_Amount'=>'0.01',
    'p5_ProductName'=>'测试支付',
    'p9_NotifyUrl'=>'http://test.huang-dou.com/other/v1/paynotify',
    'q1_FrpCode'=>'WEIXIN_XCX',
    'q5_OpenId'=>'oJfTy5EDSL9gWCYyEfxhIniHa7x8'
];
$result = (new \JoinPay\pay\trade\JoinPay($config))->getPayInfo($post);
echo $result;
//var_dump(json_decode($result,true));