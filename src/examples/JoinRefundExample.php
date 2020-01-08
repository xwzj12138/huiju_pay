<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */
require_once '../../vendor/autoload.php';

$config = ['partnerkey'=>'6221db6e83634f779b632dd77d188f64','merchantNo'=>'888108900001969'];

$post = [
    'p2_OrderNo'=>'frtesgr42t54664536543654',
    'p3_RefundOrderNo'=>'cdsgerwt54brt5t34354',
    'p4_RefundAmount'=>'0.01',
    'p6_NotifyUrl'=>'http://test.huang-dou.com/other/v1/paynotify'
];

$result = (new \JoinPay\pay\trade\JoinRefund($config))->refund($post);

var_dump(json_decode($result,true));