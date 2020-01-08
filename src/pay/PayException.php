<?php
/**
 * Notes:支付异常类
 * Date: 2020/1/7
 * @author: 陈星星
 */

namespace JoinPay\pay;


class PayException extends \Exception
{
    protected $code = 704;
}