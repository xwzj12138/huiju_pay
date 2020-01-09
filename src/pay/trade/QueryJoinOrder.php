<?php
/**
 * Notes:聚合支付订单查询接口
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\trade;


use JoinPay\pay\PayException;

class QueryJoinOrder extends JoinBase
{
    //请求url
    private $url = 'https://www.joinpay.com/trade/queryOrder.action';
    //请求数据
    protected $post = [
        'p1_MerchantNo'=>null,
        'p2_OrderNo'=>null
    ];

    /**
     * 获取订单信息
     * @param $orderNo 商户订单号
     * @return mixed
     * @throws PayException
     */
    public function getInfo($orderNo)
    {
        if(empty($orderNo)) throw new PayException('商户订单号不能为空');
        $this->post['p2_OrderNo'] = $orderNo;
        //获取签名
        $this->post['hmac'] = $this->sign($this->post);
        //拼接参数
        $post = $this->ToUrlParams();
        return $this->request($this->url,$post);
    }
}