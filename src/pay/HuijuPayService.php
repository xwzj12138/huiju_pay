<?php
/**
 * Notes:
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay;

use JoinPay\curl\Request;

trait HuijuPayService
{
    //1：md5签名  2：RSA签名
    protected $pm_encryptType = 1;
    //商户密钥
    protected $partnerkey;
    //请求数据
    protected $post = [];

    /**
     * 设置参数
     */
    public function setAttr($field,$value)
    {
        $this->post[$field] = $value;
    }

    /**
     * 格式化参数格式化成url参数
     */
    protected function ToUrlParams()
    {
        $buff = [];
        foreach ($this->post as $k => $v)
        {
            if($v != "" && !is_array($v)){
                $buff[] = $k . "=" . urlencode($v);
            }
        }
        return implode('&',$buff);
    }

    /**
     * 生成签名
     */
    protected function sign()
    {
        if($this->pm_encryptType==1){
            return urlencode(md5(implode('',$this->post).$this->partnerkey));
        }
        $private_key = openssl_pkey_get_private($this->partnerkey);
        $params = implode("", $this->post);
        openssl_sign($params, $sign, $private_key, OPENSSL_ALGO_MD5);
        openssl_free_key($private_key);
        return urlencode(base64_encode($sign));
    }

    /**
     * 请求汇聚支付获取支付信息
     */
    protected function request($url,$post=null)
    {
        return Request::curl_request($url,$post);
    }
}