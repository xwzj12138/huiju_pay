<?php
/**
 * Notes:汇聚支付基类
 * Date: 2020/1/8
 * @author: 陈星星
 */

namespace JoinPay\pay\trade;

use JoinPay\pay\HuijuPayService;
use JoinPay\pay\PayException;

class JoinBase
{
    use HuijuPayService;
    /**
     * JoinPay constructor.
     * @param $config $pm_encryptType 支付类型 $partnerkey 商户秘钥 $q7_AppId 应用的appid
     * @throws PayException
     */
    public function __construct($config)
    {
        if(!empty($config['encryptType'])) $this->pm_encryptType = $config['encryptType'];
        if(empty($config['partnerkey'])) throw new PayException('商户秘钥:partnerkey不能为空');
        if(empty($config['merchantNo'])) throw new PayException('商户编号:merchantNo不能为空');
        if(!empty($config['AppId'])) $this->post['q7_AppId'] = $config['AppId'];
        $this->partnerkey = $config['partnerkey'];
        $this->post['p1_MerchantNo'] = $config['merchantNo'];
    }
}