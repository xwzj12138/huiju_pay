<?php
/**
 * Notes:单笔代付接口
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\payment;


use JoinPay\pay\PayException;

class SinglePay extends PaymentBase
{
    //请求url
    private $url = 'https://www.joinpay.com/payment/pay/singlePay';
    //请求数据
    protected $post = [
        'userNo'=>'',
        'productCode'=>'',
        'requestTime'=>'',
        'merchantOrderNo'=>'',
        'receiverAccountNoEnc'=>'',
        'receiverNameEnc'=>'',
        'receiverAccountType'=>'',
        'receiverBankChannelNo'=>'',
        'paidAmount'=>'',
        'currency'=>'201',
        'isChecked'=>'202',
        'paidDesc'=>'',
        'paidUse'=>'',
        'callbackUrl'=>'',
        'firstProductCode'=>''
    ];

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
     * 代付付款
     * @param $post
     * @return mixed
     * @throws PayException
     */
    public function payment($post)
    {
        foreach ($post as $k=>$v) {
            $this->setAttr($k,$v);
        }
        if($this->post['productCode']=='') throw new PayException('产品类型:productCode不能为空');
        if($this->post['merchantOrderNo']=='') throw new PayException('商户订单号:merchantOrderNo不能为空');
        if($this->post['receiverAccountNoEnc']=='') throw new PayException('收款账户号:receiverAccountNoEnc不能为空');
        if($this->post['receiverNameEnc']=='') throw new PayException('收款人:receiverNameEnc不能为空');
        if($this->post['receiverAccountType']=='') throw new PayException('账户类型:receiverAccountType不能为空');
        if($this->post['receiverAccountType']==204 && $this->post['receiverBankChannelNo']==null) throw new PayException('收款账户联行号:receiverBankChannelNo不能为空');
        if($this->post['paidAmount']=='') throw new PayException('交易金额:paidAmount不能为空');
        if($this->post['paidDesc']=='') throw new PayException('代付说明:paidDesc不能为空');
        if($this->post['paidUse']=='') throw new PayException('代付用途:paidUse不能为空');
        if($this->post['productCode']=='BANK_PAY_COMPOSE_ORDER' && $this->post['firstProductCode']==null) {
            throw new PayException('优先使用产品:firstProductCode不能为空');
        }
        $this->post['requestTime'] = date('Y-m-d H:i:s');
        $this->post['paidAmount'] = (string)$this->post['paidAmount'];
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        return $this->request($this->url,$this->post);
    }
}