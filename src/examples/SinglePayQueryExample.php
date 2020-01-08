<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */

require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'6221db6e83634f779b632dd77d188f64',
    'merchantNo'=>'888108900001969'
];

$result = (new \JoinPay\pay\payment\SinglePayQuery($config))->query('151651544213541414');
echo $result;
//var_dump(json_decode($result,true));