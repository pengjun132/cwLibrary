<?php
/***加密类****/
namespace pengjun132\cwlibrary;
class Encrypt{
	/*摘自 discuz
	 * $string 明文或密文
	 * $operation 加密ENCODE或解密DECODE
	 * $key 密钥
	 * $expiry 密钥有效期 ， 默认是一直有效
	 */
	public  static function  AuthCode($string, $operation = 'DECODE', $key = '', $expiry = 0) {
		/*
		 动态密匙长度，相同的明文会生成不同密文就是依靠动态密匙
		加入随机密钥，可以令密文无任何规律，即便是原文和密钥完全相同，加密结果也会每次不同，增大破解难度。
		取值越大，密文变动规律越大，密文变化 = 16 的 $ckey_length 次方
		当此值为 0 时，则不产生随机密钥
		*/
		$ckey_length = 4;
		$key = md5($key != '' ? $key : "#@www.shixiba.com$#"); // 此处的key可以自己进行定义，写到配置文件也可以
		$keya = md5(substr($key, 0, 16));
		$keyb = md5(substr($key, 16, 16));
		$keyc = $ckey_length ? ($operation == 'DECODE' ? substr($string, 0, $ckey_length): substr(md5(microtime()), -$ckey_length)) : '';
	
		$cryptkey = $keya.md5($keya.$keyc);
		$key_length = strlen($cryptkey);
		// 明文，前10位用来保存时间戳，解密时验证数据有效性，10到26位用来保存$keyb(密匙b)，解密时会通过这个密匙验证数据完整性
		// 如果是解码的话，会从第$ckey_length位开始，因为密文前$ckey_length位保存 动态密匙，以保证解密正确
		$string = $operation == 'DECODE' ? base64_decode(substr(str_replace(array('-','_'),array('+','/'),$string), $ckey_length)) : sprintf('%010d', $expiry ? $expiry + time() : 0).substr(md5($string.$keyb), 0, 16).$string;
		$string_length = strlen($string);
		$result = '';
		$box = range(0, 255);
		$rndkey = array();
		for($i = 0; $i <= 255; $i++) {
			$rndkey[$i] = ord($cryptkey[$i % $key_length]);
		}
	
		for($j = $i = 0; $i < 256; $i++) {
			$j = ($j + $box[$i] + $rndkey[$i]) % 256;
			$tmp = $box[$i];
			$box[$i] = $box[$j];
			$box[$j] = $tmp;
		}
	
		for($a = $j = $i = 0; $i < $string_length; $i++) {
			$a = ($a + 1) % 256;
			$j = ($j + $box[$a]) % 256;
			$tmp = $box[$a];
			$box[$a] = $box[$j];
			$box[$j] = $tmp;
			$result .= chr(ord($string[$i]) ^ ($box[($box[$a] + $box[$j]) % 256]));
		}
	
		if($operation == 'DECODE') {
			if((substr($result, 0, 10) == 0 || substr($result, 0, 10) - time() > 0) && substr($result, 10, 16) == substr(md5(substr($result, 26).$keyb), 0, 16)) {
				return substr($result, 26);
			} else {
				return '';
			}
		} else {
			// 把动态密匙保存在密文里，这也是为什么同样的明文，生产不同密文后能解密的原因
			// 因为加密后的密文可能是一些特殊字符，复制过程可能会丢失，所以用base64编码
			return $keyc.str_replace(array('+','/','='), array('-','_',''), base64_encode($result));
		}
	}
	public static function resumCrypt($action_id , $user_id ){
		$key = "wXbf8SBAmSXVtwGVJuVRILXmd89WUCvI";
		$datetime = strtotime(date('Y-m-d')); //当天日期
	    $kyg_date = $key . $user_id . $action_id . $datetime;
	    return md5("sxb@1998" . md5($kyg_date));
	}
}