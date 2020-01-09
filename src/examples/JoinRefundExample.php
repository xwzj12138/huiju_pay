<?php
/**
 * Notes:聚合支付退款示例代码
 * Date: 2020/1/8
 * @author: 陈星星
 */
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