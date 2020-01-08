<?php
/**
 * Notes:curl请求类
 * Date: 2020/1/7
 * @author: 陈星星
 */

namespace JoinPay\curl;


use JoinPay\pay\PayException;

class Request
{
    /**
     * 请求封装类
     * @param $url 请求链接
     * @param null $post 发送的数据
     * @param int $second 请求超时时间，超过这个时间自动断开请求
     * @return mixed
     * @throws PayException
     */
    public static function curl_request($url,$post=null,$second = 10)
    {
        $ch = curl_init();
        //设置超时
        curl_setopt($ch, CURLOPT_TIMEOUT, $second);

        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        //设置header
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        //设置数据类型
        curl_setopt($ch,CURLOPT_HTTPHEADER,['Content-Type:application/json;charset=utf-8','Accept:application/json']);
        if($post){
            //post提交方式
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
        }
        //运行curl
        $data = curl_exec($ch);
        //返回结果
        if($data){
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);
            curl_close($ch);
            throw new PayException("curl出错，错误码:$error");
        }
    }
}