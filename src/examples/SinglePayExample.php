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

$post = [
    'productCode'=>'BANK_PAY_ORDINARY_ORDER',
    'merchantOrderNo'=>'156545311354418714',
    'receiverAccountNoEnc'=>'6212263602112155759',
    'receiverNameEnc'=>'陈星星',
    'receiverAccountType'=>'201',
    'receiverBankChannelNo'=>'103589038022',
    'paidAmount'=>'0.01',
    'paidDesc'=>'测试代付',
    'paidUse'=>'205'
];
$result = (new \JoinPay\pay\payment\SinglePay($config))->payment($post);
echo $result;
//var_dump(json_decode($result,true));