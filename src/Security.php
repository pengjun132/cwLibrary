<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/12
 * Time: 11:42
 */
namespace pengjun132\cwlibrary;
class Security
{

    /*
     * 验证码安全
     * $mobile  手机号码
     * $ip       ip地址
     * $did    deviceId 用户设备id
     *
     * store to redis  {
     *
     * key : sms_log_mobile          #手机号码作为键值对的键
     *
     * 存储发送的时间列表
     * value :{
     *      time :148754578
     * }
     * ttl 3600
     * key :  sms_log_ip
     * value :{
     *      time :148754578
     * }
     * ttl 3600
     * key : sms_log_$did
     * value :{
     *      time :148754578
     * }
     * ttl 3600
     */
    public  static  function  safeSmsCode($mobile,$ip ,$did)
    {

        #判断每一分钟和60分钟的数字

        return true;

    }


    public static function urlsafe_b64encode($string){
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
    public static function urlsafe_b64decode($string) {
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}