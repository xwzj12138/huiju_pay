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
        'p1_MerchantNo'=>null,
        'p2_RefundOrderNo'=>null,
        'p3_Version'=>null
    ];

    /**
     * 查询退款信息
     * @param $post post[p2_RefundOrderNo] 商户退款订单号
     * @return mixed
     * @throws PayException
     */
    public function query($post)
    {
        foreach ($post as $k=>$v) {
            $this->setAttr($k,$v);
        }
        if(empty($this->post['p2_RefundOrderNo'])) throw new PayException('商户退款订单号:p2_RefundOrderNo不能为空');
        //获取签名
        $this->post['hmac'] = $this->sign($this->post);
        //拼接参数
        $post = $this->ToUrlParams();
        return $this->request($this->url,$post);
    }
}