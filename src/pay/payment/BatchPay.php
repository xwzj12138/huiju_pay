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
        'userNo'=>'',
        'productCode'=>'',
        'merchantBatchNo'=>'',
        'requestCount'=>0,
        'requestAmount'=>0,
        'requestTime'=>'',
        'callbackUrl'=>'',
        'firstProductCode'=>'',
        'details'=>[]
    ];
    //代付明细数据域
    protected $detail = [
        'userNo'=>'',
        'merchantOrderNo'=>'',
        'receiverAccountNoEnc'=>'',
        'receiverNameEnc'=>'',
        'receiverAccountType'=>'',
        'receiverBankChannelNo'=>'',
        'paidAmount'=>'',
        'currency'=>'201',
        'isChecked'=>'202',
        'paidDesc'=>'',
        'paidUse'=>''
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
            }else{
                $post[$key] = $item;
            }
        }
        $this->post['requestAmount'] += $post['paidAmount'];
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
     * 批量代付生成签名，由于批量代付包含details的字段，需要特殊处理
     * @param $params
     * @return string
     */
    protected function sign($params)
    {
        $details=$params['details'];
        unset($params['details']);
        $detailsStr='';
        foreach ($details as $k=>$value){
            $detailsStr.= implode("", $value);
        }
        if ("1" == $this->pm_encryptType) {
            return md5(implode("", $params) . $detailsStr . $this->partnerkey);
        } else {
            $private_key = openssl_pkey_get_private($this->partnerkey);
            $params = implode("", $params).$detailsStr;
            openssl_sign($params, $sign, $private_key, OPENSSL_ALGO_MD5);
            openssl_free_key($private_key);
            $sign = base64_encode($sign);
            return $sign;
        }
    }

    /**
     * 代付付款
     * @param $post 参数格式
     * {productCode:'产品类型',
     * merchantBatchNo:'商户批次号即支付订单号',
     * requestAmount:'支付总金额',
     * firstProductCode:'优先使用产品',
     * callbackUrl:'回调url',
     * details:[
     *      [
     *          userNo:'商户编号',merchantOrderNo:'商户订单号',receiverAccountNoEnc:'收款账户号',receiverNameEnc:'收款人',
     *          receiverAccountType:'账户类型',receiverBankChannelNo:'收款账户联行号',paidAmount:'交易金额',currency:'币种,默认人民币',
     *          isChecked:'是否复核,默认202不复核',paidDesc:'代付说明',paidUse:'代付用途'
     *      ]
     *  ]
     * }
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
        if($this->post['productCode']=='BANK_PAY_COMPOSE_ORDER' && $this->post['firstProductCode']==null) {
            throw new PayException('优先使用产品:firstProductCode不能为空');
        }
        $this->post['requestCount'] = count($this->post['details']);
        $this->post['requestTime'] = date('Y-m-d H:i:s');
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        return $this->request($this->url,$this->post);
    }
}