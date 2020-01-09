<?php
/**
 * Notes:批量代付查询接口
 * Date: 2020/1/9
 * @author: 陈星星
 */

namespace JoinPay\pay\payment;


use JoinPay\pay\PayException;

class BatchPayQuery extends PaymentBase
{
    //请求url
    private $url = 'https://www.joinpay.com/payment/pay/batchPayQuery';
    //请求数据
    protected $post = [
        'userNo'=>null,
        'merchantBatchNo'=>null
    ];

    /**
     * 代付付款
     * @param $merchantBatchNo 批量代付的商户批次号
     * @return mixed
     * @throws PayException
     */
    public function query($merchantBatchNo)
    {
        if(empty($orderNo)) throw new PayException('商户批次号不能为空');
        $this->post['merchantBatchNo'] = $merchantBatchNo;
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        return $this->request($this->url,$this->post);
    }
}