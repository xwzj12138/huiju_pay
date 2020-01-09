<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\payment;


use JoinPay\pay\PayException;

class SinglePayQuery extends PaymentBase
{
    //请求url
    private $url = 'https://www.joinpay.com/payment/pay/singlePayQuery';
    //请求数据
    protected $post = [
        'userNo'=>null,
        'merchantOrderNo'=>null
    ];

    /**
     * 代付付款
     * @param $orderNo 代付的商户订单号
     * @return mixed
     * @throws PayException
     */
    public function query($orderNo)
    {
        if(empty($orderNo)) throw new PayException('商户订单号不能为空');
        $this->post['merchantOrderNo'] = $orderNo;
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        return $this->request($this->url,$this->post);
    }
}