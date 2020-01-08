<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */
require_once '../../vendor/autoload.php';

$config = ['partnerkey'=>'6221db6e83634f779b632dd77d188f64','merchantNo'=>'888108900001969'];

$result = (new \JoinPay\pay\trade\QueryJoinOrder($config))->getInfo('frtesgr42t54664536543654');

var_dump(json_decode($result,true));