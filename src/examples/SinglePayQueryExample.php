<?php
/**
 * Notes:单笔代付查询代码
 * Date: 2020/1/8
 * @author: 陈星星
 */

require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$result = (new \JoinPay\pay\payment\SinglePayQuery($config))->query('151651544213541414');
echo $result;
//var_dump(json_decode($result,true));