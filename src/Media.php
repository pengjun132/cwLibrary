<?php
/**
 * 
 */
 namespace pengjun132\cwlibrary;
 use pengjun132\cwlibrary\RedisClass; 
 use pengjun132\cwlibrary\Network; 
//广告的类 调用星哥写的api
class Media{
	public $redis_expire = 300 ; //过期时间
	public $url = '' ;
    private $config = null ;
	public function __CONSTRUCT(){
        global $_G ;
        $this->config  = $_G['config']['domain']['domain']['GOAPI_HOST'];
		$this->url = $this->config; 
	}

    //根据广告的位置调用广告------
    //远程拉取接口数据
    //key 广告位置的唯一标识
    public function getAdByPosition($key =  '' , $time = 5  ){
        if($key  == ''  ){
            return ;
        }
        $requestUrl = $this->url . "adkey/{$key}" ;
        $header = array() ;
        $curlOptions = array(
            CURLOPT_URL => $requestUrl, //访问URL
            CURLOPT_RETURNTRANSFER => true, //获取结果作为字符串返回
            CURLOPT_FOLLOWLOCATION => FALSE,
            CURLOPT_HEADER => false, //获取返回头信息
            CURLOPT_POST => false, //发送时带有POST参数
            CURLOPT_CONNECTTIMEOUT => $time ,//超时时间设置
            CURLOPT_TIMEOUT => $time ,
            CURLOPT_SSL_VERIFYPEER => false ,
            CURLOPT_SSL_VERIFYHOST => false ,
            CURLOPT_CUSTOMREQUEST => "GET" ,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0 ,
            CURLOPT_HTTPHEADER => $header,
        );
        $data = Network::HttpRequest($curlOptions ,  1  );
        $curlInfo = $data['curl_info'];
        return json_decode($data['responseText'] , true )  ;
    }
	
	//格式化所需数据格式
    public function formatData($data = array() ,$num ){
        if(empty($data)){
            return array();
        }
        $result = array();
        $i  = 0 ;
        foreach ($data as $key => $value) {
            if($num > 0 ){
                if($i == $num ){
                    break ; 
                }
            }
            $result[] = array(
                'id' => $value['id'],
                'picurl' => $value['picurl'],
                'title' => $value['title'],
                'desc' => $value['desc'],
                'target' => $value['target'],
                'sort' => $value['sort'],
                'type' => isset($value['type']) ? $value['type'] : '' ,
            );
            $i++ ;
        }
        return $result ;
    }
	//读取广告数据 ， 如果缓存没有那么从远程拉取数据
    public function getMediaByPositionKey($key = ''  ,$num = 0  ){
        if($key == ''){
            return ;
        }
        
        $redis_key = "ad_".$key ;
        $redis = RedisClass::getInstance(0) ;
        $cache = $redis->GET($redis_key);
        if($cache AND !empty($cache)){
            return unserialize($cache);
        }
        $data = $this->getAdByPosition($key);
        $result = array();
        if(isset($data['code']) AND is_numeric($data['code']) AND $data['code'] == 0 ){
            $result = $this->formatData($data['data'] , $num );
        }
        if(!empty($result)){
            $redis->set($redis_key , serialize($result) , $this->redis_expire);
        }
        return $result ;
    }
}