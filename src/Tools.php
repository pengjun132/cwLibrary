<?php
class Tools {
    /**
     * 对手机号中间几位用 * 隐藏
     * */
    public  static function  hidenmobile($mobile) {
         if(empty($mobile)){
            return "";
        }
        $pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
        $replacement = "\$1****\$3";
        return preg_replace($pattern, $replacement, $mobile);
    }

    /**
     * 对邮箱中间几位用 * 隐藏
     * */
    public  static function  hidenemail($email) {
        if(empty($email)){
            return "";
        }
        $emails = explode("@", $email);
        $e = substr($emails[0], 0, 3) . "****" . substr($emails[0], 8);
        $email = $e . "@" . $emails[1];
        return $email;
    }
    //隐藏身份证
    public static function hideidcard($idcard) {
        return strlen($idcard) == 15 ? substr_replace($idcard, "****", 8, 4) : (strlen($idcard) == 18 ? substr_replace($idcard, "****", 12, 4) : "异常");
    }
    /*
     * 判定用是否是手机
     * @return boolean 
    */
   public static function isMobile($mobile){
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
   public static function isEmail($email){
        $email = strtolower($email);
        if (preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', $email)) {
            return true;
        }
        return false;
   }

   //截取邮箱的前几位数后面加****
   public static function subStrEmail($email , $num = 4 ){
        if(!self::isEmail($email)){
            return '' ;
        }
        $emails = explode("@", $email);
        return  substr($emails[0] , 0 , $num ) . "****" ;
   }
   //截取手机的前几位数 后面加*
   public static function subStrMobile($mobile , $num = 4 ){
        return substr($mobile , 0 , $num  ) . "****" ;
   }
}