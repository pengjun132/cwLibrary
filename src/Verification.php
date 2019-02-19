<?php

/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/12
 * Time: 11:39
 * 验证的组件
 */
namespace pengjun132\cwlibrary;
use pengjun132\cwlibrary\StringClass; 
class Verification
{

    public  static  function  is_mobile($mobile)
    {
        $mobile = strtolower($mobile);
        if (preg_match('/^[1-9]\d{10}$/', $mobile)) {
            return true;
        }
        return false ;
    }
    /*
     * 判定用是否是邮箱
     * @return array 
    */
   public static function is_email($email){
        $email = strtolower($email);
        if (preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)) {
            return true;
        }
        return false;
   }

    public static  function  safeSmsCode($mobile, $ip, $did)
    {



    }
    //判断用户名的有效性
    public static function checkUsername($username = ''){
        if(!self::is_mobile($username) AND !self::is_email($username)){
            return false; 
        }
        return true ;
    }

     //判断密码的准确性 目前是6-16位数字 字母 或者下划线
    public static function checkPwd($passwd = '' ){
        if((StringClass::abslength($passwd)) < 6 or (StringClass::abslength($passwd)) > 16 ){
            return false;
        }
        if(StringClass::utf8_str($passwd) != 1){
            return false;
        }
        return true ;
    }
    //校验日期是不是合法的 例如 2015-11-12
    public static function isDate($dateString = '' , $type = 1  ){
        if(empty($dateString) or !in_array($type, array(1, 2 ) ) ){
            return false ;
        }
        if($type == 1 ){
            return strtotime( date('Y-m-d', strtotime($dateString)) ) === strtotime( $dateString );
        }else if($type == 2 ){
            return strtotime( date('Y-m', strtotime($dateString)) ) === strtotime( $dateString );
        }
    }

}