<?php
/**
 * Notes:聚合支付退款接口
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\trade;


use JoinPay\pay\PayException;

class JoinRefund extends JoinBase
{
    //请求url
    private $url = 'https://www.joinpay.com/trade/refund.action';
    //请求数据
    protected $post = [
        'p1_MerchantNo'=>null,
        'p2_OrderNo'=>null,
        'p3_RefundOrderNo'=>null,
        'p4_RefundAmount'=>null,
        'p5_RefundReason'=>null,
        'p6_NotifyUrl'=>null,
        'q1_version'=>null
    ];

    /**
     * 发起退款
     * @param $post post[p2_OrderNo] 商户原支付订单号
     * post[p3_RefundOrderNo] 退款金额
     * post[p4_RefundAmount] 商户退款订单号
     * post[p6_NotifyUrl] 服务器异步通知地址
     * @return mixed
     * @throws PayException
     */
    public function refund($post)
    {
        foreach ($post as $k=>$v) {
            $this->setAttr($k,$v);
        }
        if(empty($this->post['p2_OrderNo'])) throw new PayException('商户原支付订单号:p2_OrderNo不能为空');
        if(empty($this->post['p3_RefundOrderNo'])) throw new PayException('商户退款订单号:p3_RefundOrderNo不能为空');
        if(empty($this->post['p4_RefundAmount'])) throw new PayException('退款金额:p4_RefundAmount不能为空');
        if(empty($this->post['p6_NotifyUrl'])) throw new PayException('服务器异步通知地址:p6_NotifyUrl不能为空');
        //签名
        $this->post['hmac'] = $this->sign();
        //拼接参数
        $url = $this->url.'?'.$this->ToUrlParams();
        return $this->request($url);
    }
}