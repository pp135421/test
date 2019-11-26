<?php

class Test
{
    //静态属性
    //对接网关地址
    protected static $gateway = 'http://52.175.29.3/api/index/index';
    //商户号
    protected static $member_id = 'K088888888888';
    //商户秘钥
    protected static $apikey = 'bb4ccdbbd39006179b9e8b0cc258becc';
    //通道类型
    protected static $type_name = 'alipay'; //通道类型 支付宝填写alipay， 微信填写wechat

    /**
     * 请求方法
     */
    protected static function  curl_post($param, $url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, is_array($param) ? http_build_query($param) : $param);
        $contents = curl_exec($ch);
        curl_close($ch);
//    $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        return $contents;
    }

    /*
        支付方法
    */
    public static function pay()
    {
        $submit_order_id = rand(10000,99999).time();
        $param = array(
            "member_id" => self::$member_id,
            "submit_order_id" => $submit_order_id,
            "amount" => 50,
            "type_name" => self::$type_name,
            "notify_url" => 'http://www.1111.com/notify_yueyi',
            "callback_url" => 'http://www.111.com/result_yueyi',
        );
        $param['sign'] = self::makeSign($param, self::$apikey);
        $content = self::curl_post($param, self::$gateway);
        echo '<pre>';
        var_dump($content);
        $arr_content = json_decode($content, true);
        var_dump($arr_content);
        if($arr_content && $arr_content['code'] == 200 && $arr_content['data']){
            //为了数据安全，返回数据必须验签！
            $data = $arr_content['data'];
            $sign_old = $data['sign'];
            unset($data['sign']);
            $sign = self::makeSign($data, self::$apikey);
            if($sign == $sign_old){
                //此处业务逻辑流程
                var_dump('返回数据：验签通过，进行业务处理');
            }else{
                var_dump('返回数据：验签失败');
            }
        }
    }

    /*
        签名方法
    */
    protected static function makeSign($param, $apikey)
    {
        ksort($param);
        $md5_str = '';
        foreach ($param AS $key => $value){
            $md5_str .= $key.'='.$value."&";
        }
        $md5_str.= 'key='.$apikey;
        return strtoupper(md5($md5_str));
    }
}

Test::pay();




