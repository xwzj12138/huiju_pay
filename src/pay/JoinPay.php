<?php
/**
 * Notes:聚合支付
 * Date: 2020/1/7
 * @author: 陈星星
 */

namespace JoinPay\pay;


use JoinPay\curl\Request;

class JoinPay
{
    //1：md5签名  2：RSA签名
    private $pm_encryptType = 1;
    //商户密钥
    private $partnerkey;
    //请求url
    private $url = 'https://www.joinpay.com/trade/uniPayApi.action';
    //请求数据
    private $post = [
        'p0_Version'=>'1.0',
        'p1_MerchantNo'=>null,
        'p2_OrderNo'=>null,
        'p3_Amount'=>null,
        'p4_Cur'=>1,
        'p5_ProductName'=>null,
        'p6_ProductDesc'=>null,
        'p7_Mp'=>null,
        'p8_ReturnUrl'=>null,
        'p9_NotifyUrl'=>null,
        'q1_FrpCode'=>null,
        'q2_MerchantBankCode'=>null,
        'q3_SubMerchantNo'=>null,
        'q4_IsShowPic'=>null,
        'q5_OpenId'=>null,
        'q6_AuthCode'=>null,
        'q7_AppId'=>null,
        'q8_TerminalNo'=>null,
        'q9_TransactionModel'=>null,
        'qa_TradeMerchantNo'=>null,
        'qb_buyerId'=>null
    ];

    /**
     * JoinPay constructor.
     * @param $config $pm_encryptType 支付类型 $partnerkey 商户秘钥 $q7_AppId 应用的appid
     * @throws PayException
     */
    public function __construct($config)
    {
        if(!empty($config['pm_encryptType'])) $this->pm_encryptType = $config['pm_encryptType'];
        if(empty($config['partnerkey'])) throw new PayException('商户秘钥字段partnerkey不能为空');
        if(empty($config['p1_MerchantNo'])) throw new PayException('商户编号字段p1_MerchantNo不能为空');
        $this->partnerkey = $config['partnerkey'];
        $this->post['p1_MerchantNo'] = $config['p1_MerchantNo'];
        if(!empty($config['q7_AppId'])) $this->post['q7_AppId'] = $config['q7_AppId'];
    }

    /**
     * 设置签名类型
     */
    public function setEncryptType($value)
    {
        $this->pm_encryptType = $value;
    }
    /**
     * 设置参数
     */
    public function setAttr($field,$value)
    {
        $this->post[$field] = $value;
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
        if($this->post['p1_MerchantNo']==null)  throw new PayException('商户编号字段p1_MerchantNo不能为空');
        if($this->post['p2_OrderNo']==null)  throw new PayException('商户订单号字段p2_OrderNo不能为空');
        if($this->post['p3_Amount']==null)  throw new PayException('订单金额字段p3_Amount不能为空');
        if($this->post['p5_ProductName']==null)  throw new PayException('商品名称字段p5_ProductName不能为空');
        if($this->post['p9_NotifyUrl']==null)  throw new PayException('服务器异步通知地址p9_NotifyUrl不能为空');
        if($this->post['q1_FrpCode']==null)  throw new PayException('交易类型q1_FrpCode不能为空');

        if($post['q1_FrpCode']=='WEIXIN_GZH' || $post['q1_FrpCode']=='WEIXIN_XCX' ||
            $post['q1_FrpCode']=='WEIXIN_APP' || $post['q1_FrpCode']=='WEIXIN_APP3'){
            //微信公众号小程序支付必填q5_OpenId即openid
            if(($post['q1_FrpCode']=='WEIXIN_GZH' || $post['q1_FrpCode']=='WEIXIN_XCX')){
                if($this->post['q5_OpenId']==null)  throw new PayException('微信Openid字段q5_OpenId不能为空');
            }
            //q7_AppId即appid
            if($this->post['q7_AppId']==null)  throw new PayException('q7_AppId不能为空');
        }elseif ($post['q1_FrpCode']=='ALIPAY_CARD' || $post['q1_FrpCode']=='WEIXIN_CARD' ||
            $post['q1_FrpCode']=='JD_CARD' || $post['q1_FrpCode']=='QQ_CARD' || $post['q1_FrpCode']=='UNIONPAY_CARD'){
            //扫码支付时必须要有付款码数字（二维码标识）
            if($this->post['q6_AuthCode']==null)  throw new PayException('付款码数字字段q6_AuthCode不能为空');
        }elseif ($post['q1_FrpCode']=='ALIPAY_CARD' || $post['q1_FrpCode']=='WEIXIN_CARD' ||
            $post['q1_FrpCode']=='JD_CARD' || $post['q1_FrpCode']=='QQ_CARD' || $post['q1_FrpCode']=='UNIONPAY_CARD'){
            //用户的openid
            if($this->post['q5_OpenId']==null)  throw new PayException('微信Openid字段q5_OpenId不能为空');
        }
        //签名
        $this->post['hmac'] = $this->sign();
        //拼接参数
        $url = $this->url.'?'.$this->ToUrlParams();
        return $this->request($url);
    }

    /**
     * 格式化参数格式化成url参数
     */
    public function ToUrlParams()
    {
        $buff = "";
        foreach ($this->post as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff .= $k . "=" . urlencode($v) . "&";
            }
        }
        $buff = trim($buff, "&");
        return $buff;
    }

    /**
     * 生成签名
     */
    private function sign()
    {
        if($this->pm_encryptType==1){
            return md5(implode('',$this->post).$this->partnerkey);
        }
        $private_key = openssl_pkey_get_private($this->partnerkey);
        $params = implode("", $this->post);
        openssl_sign($params, $sign, $private_key, OPENSSL_ALGO_MD5);
        openssl_free_key($private_key);
        return base64_encode($sign);
    }

    /**
     * 请求汇聚支付获取支付信息
     */
    private function request($url)
    {
        return Request::curl_request($url);
    }
}