<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/12/12
 * Time: 11:29
 * 公共基础类
 */
namespace pengjun132\cwlibrary;
class Common
{
    /**
    * 返回json数据,格式要统一
    * $code int  返回的错误码
    * $msg  string 返回的错误信息
    * $data array 返回的数组
    * //isForce 是否强制转化 如果data为空 ，那么我强制转化为 {}
    */
    public static function EchoResult($code = 0 , $msg = '' , $data = array()  , $isForce = 1 )
    {
        if( $isForce == 1 ){
            $data = (empty($data)) ?   "" : $data ;
        }
        $result = array(
            'code' => intval($code),
            'msg' => $msg ,
            'data' => $data
        );
        echo json_encode($result,JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES  );
        exit;
    }

    /**
     * 导入文件并且进行实例化 ,默认导入的是business这个文件夹下面的文件
     * $filename	   string $layer 模型层名称
     * $folder 文件夹
     * return object
     */
    public static function ImportBusiness($name = null  , $folder = ''  ){
        $layer = "business";
        $class=$name.ucfirst($layer);
        static $_obj=array();//定义一个静态的变量 避免重复的new对象
        $obj_key = '';
        if($folder != '' ){
            $obj_key = md5($folder . $class);
        }else{
            $obj_key = md5($class);
        }
        if(isset($_obj[$obj_key])){
            return $_obj[$obj_key];
        }
        $filename = '' ;
        if($folder != '' ){
            $filename = APP_PATH."/application/{$layer}/{$folder}/{$class}.php";
        }else{
            $filename = APP_PATH."/application/{$layer}/{$class}.php";
        }
        if(!file_exists($filename)){
            exit("file {$class}.php is not exists ");
        }
        \Yaf_loader::import($filename);//导入类库（业务逻辑类库之类的）
        $_obj[$obj_key] = new $class();
        return $_obj[$obj_key];
    }

    //获取上个页面的url地址
    public static function getPreUrl() {
        if (!isset($_SERVER["HTTP_REFERER"]) OR empty($_SERVER["HTTP_REFERER"])) {
            return "";
        }
        $data = parse_url($_SERVER["HTTP_REFERER"]);
        if ($data['host'] != $_SERVER['HTTP_HOST']) {
            return "";
        }
        return $_SERVER["HTTP_REFERER"];
    }
    //获取当前页面地址
    public static function getCurrentUrl(){
        $pageURL = 'http';
        if ( isset($_SERVER["HTTPS"]) AND $_SERVER["HTTPS"] == "on") {
            $pageURL .= "s";
        }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else{
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}