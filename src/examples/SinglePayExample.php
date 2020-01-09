<?php
/**
 * Notes:单笔代付示例代码
 * Date: 2020/1/8
 * @author: 陈星星
 */
require_once '../../vendor/autoload.php';

$config = [
    'partnerkey'=>'商户秘钥',
    'merchantNo'=>'商户编号'
];

$post = [
    'productCode'=>'产品类型',
    'merchantOrderNo'=>'商户订单号',
    'receiverAccountNoEnc'=>'收款账户号',
    'receiverNameEnc'=>'收款人',
    'receiverAccountType'=>'账户类型',
    'receiverBankChannelNo'=>'收款账户联行号',
    'paidAmount'=>'交易金额',
    'paidDesc'=>'代付说明',
    'paidUse'=>'代付用途'
];
$result = (new \JoinPay\pay\payment\SinglePay($config))->payment($post);
echo $result;
//var_dump(json_decode($result,true));