<?php
/**
 * Notes:聚合支付
 * Date: 2020/1/7
 * @author: 陈星星
 */

namespace JoinPay\pay\trade;


use JoinPay\pay\PayException;

class JoinPay extends JoinBase
{
    //请求url
    private $url = 'https://www.joinpay.com/trade/uniPayApi.action';
    //请求数据
    protected $post = [
        'p0_Version'=>'1.0',
        'p1_MerchantNo'=>'',
        'p2_OrderNo'=>'',
        'p3_Amount'=>0,
        'p4_Cur'=>1,
        'p5_ProductName'=>'',
        'p6_ProductDesc'=>'',
        'p7_Mp'=>'',
        'p8_ReturnUrl'=>'',
        'p9_NotifyUrl'=>'',
        'q1_FrpCode'=>'',
        'q2_MerchantBankCode'=>'',
        'q3_SubMerchantNo'=>'',
        'q4_IsShowPic'=>'',
        'q5_OpenId'=>'',
        'q6_AuthCode'=>'',
        'q7_AppId'=>'',
        'q8_TerminalNo'=>'',
        'q9_TransactionModel'=>'',
        'qa_TradeMerchantNo'=>'',
        'qb_buyerId'=>''
    ];

    /**
     * 设置签名类型
     */
    public function setEncryptType($value)
    {
        $this->pm_encryptType = $value;
    }

    /**
     * 设置版本号
     */
    public function setVersion($value)
    {
        $this->post['p0_Version'] = $value;
    }

    /**
     * 设置交易币种
     */
    public function setCur($value)
    {
        $this->post['p4_Cur'] = $value;
    }

    /**
     * 设置商品名称
     */
    public function setProductName($value)
    {
        $this->post['p5_ProductName'] = $value;
    }

    /**
     * 设置异步通知地址
     */
    public function setNotifyUrl($value)
    {
        $this->post['p9_NotifyUrl'] = 'AB|'.$value;
    }

    /**
     * 请求支付数据
     */
    public function getPayInfo($post=[])
    {
        foreach ($post as $k=>$value) {
            $this->setAttr($k,$value);
        }
        if($this->post['p2_OrderNo']==null)  throw new PayException('商户订单号:p2_OrderNo不能为空');
        if($this->post['p3_Amount']==null)  throw new PayException('订单金额:p3_Amount不能为空');
        if($this->post['p5_ProductName']==null)  throw new PayException('商品名称:p5_ProductName不能为空');
        if($this->post['p9_NotifyUrl']==null)  throw new PayException('服务器异步通知地址:p9_NotifyUrl不能为空');
        if($this->post['q1_FrpCode']==null)  throw new PayException('交易类型:q1_FrpCode不能为空');

        if($post['q1_FrpCode']=='WEIXIN_GZH' || $post['q1_FrpCode']=='WEIXIN_XCX' ||
            $post['q1_FrpCode']=='WEIXIN_APP' || $post['q1_FrpCode']=='WEIXIN_APP3'){
            //微信公众号小程序支付必填q5_OpenId即openid
            if(($post['q1_FrpCode']=='WEIXIN_GZH' || $post['q1_FrpCode']=='WEIXIN_XCX')){
                if($this->post['q5_OpenId']==null)  throw new PayException('微信Openid:q5_OpenId不能为空');
            }
            //q7_AppId即appid
            if($this->post['q7_AppId']==null)  throw new PayException('q7_AppId不能为空');
        }elseif ($post['q1_FrpCode']=='ALIPAY_CARD' || $post['q1_FrpCode']=='WEIXIN_CARD' ||
            $post['q1_FrpCode']=='JD_CARD' || $post['q1_FrpCode']=='QQ_CARD' || $post['q1_FrpCode']=='UNIONPAY_CARD'){
            //扫码支付时必须要有付款码数字（二维码标识）
            if($this->post['q6_AuthCode']==null)  throw new PayException('付款码数字:q6_AuthCode不能为空');
        }elseif ($post['q1_FrpCode']=='ALIPAY_CARD' || $post['q1_FrpCode']=='WEIXIN_CARD' ||
            $post['q1_FrpCode']=='JD_CARD' || $post['q1_FrpCode']=='QQ_CARD' || $post['q1_FrpCode']=='UNIONPAY_CARD'){
            //用户的openid
            if($this->post['q5_OpenId']==null)  throw new PayException('微信Openid:q5_OpenId不能为空');
        }
        //签名
        $this->post['hmac'] = $this->sign($this->post);
        //拼接参数
        $post = $this->ToUrlParams();
        return $this->request($this->url,$post);
    }
}