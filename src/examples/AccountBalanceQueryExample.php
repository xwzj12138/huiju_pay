<?php
/**
 * Notes:账户查询示例代码
 * Date: 2020/1/9
 * @author: 陈星星
 */

require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$result = (new \JoinPay\pay\payment\AccountBalanceQuery($config))->query();

echo $result;

//var_dump(json_decode($result,true));