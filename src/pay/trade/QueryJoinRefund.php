<?php
/**
 * Notes:查询聚合支付退款订单信息
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\trade;


use JoinPay\pay\PayException;

class QueryJoinRefund extends JoinBase
{
    //请求url
    private $url = 'https://www.joinpay.com/trade/queryRefund.action';
    //请求数据
    protected $post = [
        'p1_MerchantNo'=>'',
        'p2_RefundOrderNo'=>'',
        'p3_Version'=>'2.0'
    ];

    /**
     * 查询退款信息
     * @param $RefundOrderNo 商户退款订单号
     * @return mixed
     * @throws PayException
     */
    public function query($RefundOrderNo)
    {
        if(empty($RefundOrderNo)) throw new PayException('商户订单号不能为空');
        $this->post['p2_RefundOrderNo'] = $RefundOrderNo;
        //获取签名
        $this->post['hmac'] = $this->sign($this->post);
        //拼接参数
        $post = $this->ToUrlParams();
        return $this->request($this->url,$post);
    }
}