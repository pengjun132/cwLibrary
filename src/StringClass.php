<?php
/***字符串处理类****/
class StringClass{
    /**
     *  处理禁用HTML但允许换行的内容
     * @access    public
     * @param     string  $msg  需要过滤的内容
     * @return    string
     */
    public  static function TrimMsg($msg)
    {
        $msg = trim(stripslashes($msg));
        $msg = nl2br(htmlspecialchars($msg));
        //$msg = str_replace("  ","&nbsp;&nbsp;",$msg);
        return addslashes($msg);
    }
    //PHP去除Html所有标签、空格以及空白
    public static function cutstr_html($str){
        $str = trim($str); //清除字符串两边的空格
        $str = strip_tags($str,""); //利用php自带的函数清除html格式
        $str = preg_replace("/\t/","",$str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。
        $str = preg_replace("/\r\n/","",$str);
        $str = preg_replace("/\r/","",$str);
        $str = preg_replace("/\n/","",$str);
        $str = preg_replace("/ /","",$str);
        $str = preg_replace("/  /","",$str);  //匹配html中的空格
        return trim($str); //返回字符串
    }
    /**
     * PHP判断字符串纯汉字 OR 纯英文 OR 汉英混合
     * return 1: 英文
     * return 2：纯汉字
     * return 3：汉字和英文
     */
    public static function  utf8_str($str){
        $mb = mb_strlen($str,'utf-8');
        $st = strlen($str);
        if($st==$mb)
            return 1;
        if($st%$mb==0 && $st%3==0)
            return 2;
        return 3;
    }
    /**
     +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * @param string $strength 字符串的长度
     +----------------------------------------------------------
     * @return string
     +----------------------------------------------------------
     */
    public static function msubstr($str, $start=0, $length,$charset="utf-8", $suffix=true)
    {
        $strength = self::abslength($str , $charset) ; 
        if(function_exists("mb_substr")){
            if($suffix){
                if($length <$strength ){
                    return mb_substr($str, $start, $length, $charset)."....";
                }else{
                    return mb_substr($str, $start, $length, $charset);
                }
            }else{
                return mb_substr($str, $start, $length, $charset);
            }
    
             
        }elseif(function_exists('iconv_substr')) {
            if($suffix){//是否加上......符号
                if($length < $strength){
                    return iconv_substr($str,$start,$length,$charset)."....";
                }else{
                    return iconv_substr($str,$start,$length,$charset) ;
                }
            }else{
                return iconv_substr($str,$start,$length,$charset) ;
            }
        }
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
        if($suffix){
            return $slice."…";
        } else{
            return $slice;
        }
         
    }
    /**
     +----------------------------------------------------------
     * 字符串截取，支持中文和其他编码
     +----------------------------------------------------------
     * @static
     * @access public
     +----------------------------------------------------------
     * @param string $str 需要计算的字符串
     * @param string $charset 字符编码
     +----------------------------------------------------------
     * @return length int
     +----------------------------------------------------------
     */
    public static function abslength($str,$charset= 'utf-8'){
        if(empty($str)){
            return 0;
        }
        if(function_exists('mb_strlen')){
            return mb_strlen($str,'utf-8');
        }
        else {
            @preg_match_all("/./u", $str, $ar);
            return count($ar[0]);
        }
    }
    //获取随机数
    public static function getRandom($length = 4, $type = 1) {
        switch ($type) {
            case 1:
                $string = '1234567890';
                break;
    
            case 2:
                $string = 'abcdefghijklmnopqrstuvwxyz';
                break;
    
            case 3:
                $string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
    
            case 4:
                $string = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
    
            case 5:
                $string = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                break;
        }
        $output = '';
        for ($i = 0; $i < $length; $i++) {
            $pos = mt_rand(0, strlen($string) - 1);
            $output .= $string[$pos];
        }
        return $output;
    }
    //手机号码中间隐藏
    public  static function  hidenmobile($mobile) {
        if(empty($mobile)){
            return "";
        }
        $pattern = "/(1\d{1,2})\d\d(\d{0,3})/";
        $replacement = "\$1****\$3";
        return preg_replace($pattern, $replacement, $mobile);
    }
    //对邮箱中间几位用 * 隐藏
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
        return strlen($idcard) == 15 ? substr_replace($idcard, "****", 8, 4) : (strlen($idcard) == 18 ? substr_replace($idcard, "****", 10, 4) : "异常");
    }
    public static function hideidCertificates($card , $num = 3 ) {
        return substr($card, 0, $num) .str_repeat("*", (strlen($card) - ($num * 2))) .substr($card, -$num);
    }

    //替换字符串中的br标签
    public static function replaceBr($str){
        $str =  str_replace("<br />" , ""  , $str);
        $str =  str_replace("<br>" , ""  , $str);
        return $str ;
    }
   
}