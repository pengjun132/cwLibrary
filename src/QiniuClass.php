<?php
/**
 * @Author: Awe
 * @Date:   2018-01-27 14:13:37
 * @Last Modified by:   Awe
 * @Last Modified time: 2018-01-27 17:38:09
 */
use Qiniu\Auth;
require_once APP_PATH . "/application/library/qiniu/autoload.php" ;
class QiniuClass{
    //获取 上传的token
    // type 1:public 存储 2：private 存储
    public static  function getToken($type = 1 ,$dateline = 3600 ){
        if( !in_array($type , array(1,2) ) ){
            return ;
        }
        global $_G ;
        $accessKey = '';
        $bucket = '';
        $secretKey = '';
        if( $type == 2 ){
            $accessKey = $_G['config']['qiniu_private']['qiniu_private']['ACCESS_KEY'];
            $secretKey = $_G['config']['qiniu_private']['qiniu_private']['SECRET_KEY'];
            $bucket = $_G['config']['qiniu_private']['qiniu_private']['bucket'];
        }else if($type == 1 ){
            $accessKey = $_G['config']['qiniu_public']['qiniu_public']['ACCESS_KEY'];
            $secretKey = $_G['config']['qiniu_public']['qiniu_public']['SECRET_KEY'];
            $bucket = $_G['config']['qiniu_public']['qiniu_public']['bucket'];
        }
        return self::makeToken($accessKey ,$secretKey ,  $bucket , $dateline);
    }
    /**
     * 获取下载文件的地址 私有 bueket
     * @param string $path 比如 3.jpg path
     * @param string $attname 下载显示的问件名字（记着带后缀啊） 如果传递这个 那么会直接下载
     * @param string $durationInSeconds 链接的有效期（以秒为单位）
     * @return string 
    */
    public static function getDownLoadUrl($path , $attname = '' , $durationInSeconds = 3600  ){
        global $_G ;
        $accessKey = $_G['config']['qiniu_private']['qiniu_private']['ACCESS_KEY'];
        $secretKey = $_G['config']['qiniu_private']['qiniu_private']['SECRET_KEY'];
        // 构建Auth对象
        $auth = new Auth($accessKey, $secretKey);
        // 私有空间中的外链 http://<domain>/<file_key>
        $baseUrl = $_G['config']['domain']['domain']['QINIU_UPLOAD_PRIVATE_HOST'] .$path ;
        if( !empty($attname) ){
            if( stripos($baseUrl, "&") !== false ){
                $baseUrl .= "?attname=" .urlencode($attname);
            }else{
                $baseUrl .= "?attname=" .urlencode($attname);
            }
        }
        // 对链接进行签名
        $signedUrl = $auth->privateDownloadUrl($baseUrl ,$durationInSeconds );
        return $signedUrl ;
    }
    //返回上传的token
    private static function makeToken($accessKey ,$secretKey ,  $bucket , $dateline ){
        $auth = new Auth($accessKey, $secretKey);
        $upToken = $auth->uploadToken($bucket, null, $dateline, null , true);
        return $upToken;
    }
    private static function urlsafe_base64_encode($data){
        $find = array('+', '/');
        $replace = array('-', '_');
        return str_replace($find, $replace, base64_encode($data));
    }
   
}
