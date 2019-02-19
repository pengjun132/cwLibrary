<?php
/**
 * @Author: speakphp
 * @Date:   2016-06-20 20:57:56
 * @Last Modified by:   Awe
 * @Last Modified time: 2018-02-26 16:40:00
 * @des 请求内部的接口
 */
namespace pengjun132\cwlibrary;
use pengjun132\cwlibrary\Log; 
use pengjun132\cwlibrary\Network; 
class RequestApi{
	public static $appid = "QWEwKkcLAn3yF26J" ;
	public static $secret = "J5Tdgpi4kqjT8Dms5A4XkZvG3zg4qrsi" ;
    public static function getRequestData($requestUrl = '' , $request_data , $time = 3  , $curl_info = null ){
        $currentTime = time();
        $sign = md5( self::$secret . $currentTime );
        $header = array(
            'appid:'.self::$appid ,
            'time:'.$currentTime ,
            'sign:' . $sign ,
        ) ;
        $request_data['source'] = "" ;
        $request_data['ip'] = Network::GetClientIp() ;
        $startTime = microtime(true);

        $curlOptions = array(
            CURLOPT_URL => $requestUrl, //访问URL
            CURLOPT_RETURNTRANSFER => true, //获取结果作为字符串返回
            CURLOPT_FOLLOWLOCATION => FALSE,
            CURLOPT_HEADER => false, //获取返回头信息
            CURLOPT_POST => true, //发送时带有POST参数
            CURLOPT_POSTFIELDS => http_build_query($request_data), //请求的POST参数字符串
            CURLOPT_CONNECTTIMEOUT => $time ,//超时时间设置
            CURLOPT_TIMEOUT => $time ,
            CURLOPT_SSL_VERIFYPEER => false ,
            CURLOPT_SSL_VERIFYHOST => false ,
            CURLOPT_CUSTOMREQUEST => "POST" ,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0 ,
            CURLOPT_HTTPHEADER => $header,
        );
        $data =  Network::HttpRequest($curlOptions ,  $curl_info ,3 , 1 );
        //@file_put_contents("/tmp/slow.log", "url:".$requestUrl ."    start:".$startTime ."end:". microtime(true)."=>".(1000*(microtime(true)-$startTime))."\n" ,FILE_APPEND);
        $val =  json_decode($data , true ) ;
        if (is_array($val)){
            return $val ;
        }else{
            Log::waringLog($data);
        }
    }

    public  static function  RPC($requestUrl = '' , $request_data , $time = 3  , $header, $curl_info = null)
	{
		$currentTime = time();
		$sign = md5( self::$secret . $currentTime );
		$auth = array(
			'appid:'.self::$appid ,
			'time:'.$currentTime ,
			'sign:' . $sign ,
		) ;

		if (is_array($header) & count($header)>0)
		{

			$header = array_merge($header,$auth);
		}else
		{
			$header = $auth;
		}
        $request_data['ip'] = Network::GetClientIp();
        $request_data['source'] = isset($_SERVER['HTTP_SOURCE']) ? $_SERVER['HTTP_SOURCE'] : '' ;
        if( empty($request_data['source']) ){
            $request_data['source'] ="jlwxapp" ;
        }
		$curlOptions = array(
			CURLOPT_URL => $requestUrl, //访问URL
			CURLOPT_RETURNTRANSFER => true, //获取结果作为字符串返回
			CURLOPT_FOLLOWLOCATION => FALSE,
			CURLOPT_HEADER => false, //获取返回头信息
			CURLOPT_POST => true, //发送时带有POST参数
			CURLOPT_POSTFIELDS => http_build_query($request_data), //请求的POST参数字符串
			CURLOPT_CONNECTTIMEOUT => $time ,//超时时间设置
			CURLOPT_TIMEOUT => $time ,
			CURLOPT_SSL_VERIFYPEER => false ,
			CURLOPT_SSL_VERIFYHOST => false ,
			CURLOPT_CUSTOMREQUEST => "POST" ,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0 ,
			CURLOPT_HTTPHEADER => $header,
        );
        //Log::WriteLog("/tmp/net-api.log" ,$requestUrl);
        $log = Log::getInstance(array('filename' => "net-api" )) ; 
        //$log->Write("info" , $requestUrl);
		$data =  Network::HttpRequest($curlOptions ,  $curl_info ,3 , 1 );
        $val =  json_decode($data , true ) ;
        if (is_array($val))
        {
            return $val ;
        }else
        {
            $log->Write("error" , $data);
        }
	}

	/*
	 * 元数据提交  类似 提交 xml
	 */
	public static  function RawRPC($requestUrl = '' , $request_data , $time = 3  , $header = '' , $curl_info = null)
    {
        $currentTime = time();
        $sign = md5( self::$secret . $currentTime );
        $auth = array(
            'appid:'.self::$appid ,
            'time:'.$currentTime ,
            'sign:' . $sign ,
        ) ;

        if (is_array($header) & count($header)>0)
        {

            $header = array_merge($header,$auth);
        }else
        {
            $header = $auth;
        }
        $request_data['ip'] = Network::GetClientIp();
        $request_data['source'] = isset($_SERVER['HTTP_SOURCE']) ? $_SERVER['HTTP_SOURCE'] : '' ;
        if( empty($request_data['source']) ){
            $request_data['source'] ="mianjing" ;
        }
        $data_string = json_encode($request_data , JSON_UNESCAPED_UNICODE);
        $curlOptions = array(
            CURLOPT_URL => $requestUrl, //访问URL
            CURLOPT_RETURNTRANSFER => true, //获取结果作为字符串返回
            CURLOPT_FOLLOWLOCATION => FALSE,
            CURLOPT_HEADER => false, //获取返回头信息
            CURLOPT_POST => true, //发送时带有POST参数
            CURLOPT_POSTFIELDS => $data_string, //请求的POST参数字符串
            CURLOPT_CONNECTTIMEOUT => $time ,//超时时间设置
            CURLOPT_TIMEOUT => $time ,
            CURLOPT_SSL_VERIFYPEER => false ,
            CURLOPT_SSL_VERIFYHOST => false ,
            CURLOPT_CUSTOMREQUEST => "POST" ,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0 ,
            CURLOPT_HTTPHEADER => $header,
        );
        $data =  Network::HttpRequest($curlOptions ,  $curl_info ,3 , 1 );
        $ret = json_decode($data , true ) ;
        $log = Log::getInstance(array('filename' => "net-api" )) ;
        if (is_array($ret))
        {
            return $ret ;
        }else
        {
            $log->Write("error" ,$data);
        }
    }

}
