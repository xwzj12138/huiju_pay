<?php
/**
 * Notes:批量代付接口
 * Date: 2020/1/9
 * @author: 陈星星
 */

namespace JoinPay\pay\payment;


use JoinPay\pay\PayException;

class BatchPay extends PaymentBase
{
    //请求数据
    protected $post = [
        'userNo'=>null,
        'productCode'=>null,
        'merchantBatchNo'=>null,
        'requestCount'=>null,
        'requestAmount'=>null,
        'requestTime'=>null,
        'callbackUrl'=>null,
        'firstProductCode'=>null,
        'details'=>[]
    ];
    //代付明细数据域
    protected $detail = [
        'userNo'=>null,
        'merchantOrderNo'=>null,
        'receiverAccountNoEnc'=>null,
        'receiverNameEnc'=>null,
        'receiverAccountType'=>null,
        'receiverBankChannelNo'=>null,
        'paidAmount'=>null,
        'currency'=>'201',
        'isChecked'=>'202',
        'paidDesc'=>null,
        'paidUse'=>null
    ];
    //请求url
    private $url = 'https://www.joinpay.com/payment/pay/batchPay';

    /**
     * 设置币种
     * @param $value 类型 201人民币
     * @throws PayException
     */
    public function setCurrency($value)
    {
        if($value!=201 && $value!=204) throw new PayException('币种类型错误');
        $this->post['currency'] = $value;
    }

    /**
     * 设置是否复核
     * @param $value 类型 201复核 202 不复核
     * @throws PayException
     */
    public function setIsChecked($value)
    {
        if($value!=201 && $value!=202) throw new PayException('是否复核类型错误');
        $this->post['isChecked'] = $value;
    }

    /**
     * 设置单个代付明细
     * 没有判断参数类型是否为一位数组，不开放
     * 用属性detail遍历排除多余字段及为空的字段
     * 由于接口问题，多传其他不需要的参数时，报错：验签失败-HMAC内容不匹配
     * @param $value
     */
    protected function setDetail($value)
    {
        $post = [];
        foreach ($this->detail as $key=>$item) {
            if(!empty($value[$key])){
                $post[$key] = $value[$key];
            }
        }
        $this->post['details'][] = $post;
    }

    /**
     * 设置单个或多个代付明细
     * @param $value
     */
    public function setDetails($value)
    {
        if(count($value)==count($value,1)){
            $this->setDetail($value);
        }else{
            foreach ($value as $item) {
                $this->setDetail($item);
            }
        }
    }

    /**
     * 代付付款
     * @param $post
     * @return mixed
     * @throws PayException
     */
    public function payment($post)
    {
        foreach ($post as $k=>$v) {
            if($k=='details'){
                $this->setDetails($v); continue;
            }
            $this->setAttr($k,$v);
        }
        if($this->post['productCode']==null) throw new PayException('产品类型:productCode不能为空');
        if($this->post['merchantBatchNo']==null)  throw new PayException('商户批次号:merchantBatchNo不能为空');
        if($this->post['requestAmount']==null)  throw new PayException('请求总金额:requestAmount不能为空');
        if($this->post['productCode']=='BANK_PAY_COMPOSE_ORDER' && $this->post['firstProductCode']==null) {
            throw new PayException('优先使用产品:firstProductCode不能为空');
        }
        if(empty($this->post['callbackUrl'])) unset($this->post['callbackUrl']);
        if(empty($this->post['firstProductCode'])) unset($this->post['firstProductCode']);
        $this->post['requestCount'] = count($this->post['details']);
        $this->post['requestTime'] = date('Y-m-d H:i:s');
        //签名
        $this->post['hmac'] = $this->sign($this->post['details']);
        return $this->request($this->url,$this->post);
    }
}