<?php
/**
 * Notes:账户查询接口
 * Date: 2020/1/9
 * @author: 陈星星
 */

namespace JoinPay\pay\payment;


class AccountBalanceQuery extends PaymentBase
{
    //请求url
    private $url = 'https://www.joinpay.com/payment/pay/accountBalanceQuery';
    //请求数据
    protected $post = [
        'userNo'=>null
    ];

    /**
     * 代付付款
     */
    public function query()
    {
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        return $this->request($this->url,$this->post);
    }
}