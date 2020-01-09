<?php
/**
 * Notes:聚合支付示例代码
 * Date: 2020/1/8
 * @author: 陈星星
 */

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