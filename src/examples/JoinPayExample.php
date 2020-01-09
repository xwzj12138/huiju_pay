<?php
/**
 * Notes:聚合支付示例代码
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
    'p2_OrderNo'=>'DD2020010916091397981706',
    'p3_Amount'=>'15.8',
    'p5_ProductName'=>'佳品接龙',
    'p9_NotifyUrl'=>'http://test.huang-dou.com/api/other/v2/paynotify/notify',
    'q1_FrpCode'=>'WEIXIN_XCX',
    'q5_OpenId'=>'oJfTy5DvH7EXWKqVQCK5kFTrh5yo'
];
$result = (new \JoinPay\pay\trade\JoinPay($config))->getPayInfo($post);
echo $result;
//var_dump(json_decode($result,true));