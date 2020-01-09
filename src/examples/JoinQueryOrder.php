<?php
/**
 * Notes:聚合支付订单查询示例
 * Date: 2020/1/8
 * @author: 陈星星
 */
require_once '../../vendor/autoload.php';

$config = ['partnerkey'=>'商户秘钥','merchantNo'=>'商户编号'];

$result = (new \JoinPay\pay\trade\QueryJoinOrder($config))->getInfo('frtesgr42t54664536543654');

var_dump(json_decode($result,true));