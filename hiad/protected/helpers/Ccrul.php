<?php

class Ccrul{
	public static function post($url, $fields, $timeout = NULL){
        // 开启curl_init()
		$ch = curl_init() ;
		//set the url, number of POST vars, POST data
		curl_setopt($ch, CURLOPT_URL, $url) ;
		curl_setopt($ch, CURLOPT_POST, count($fields)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
        if ($timeout !== NULL) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        }
		ob_start();
		curl_exec($ch);
		$result = ob_get_contents() ;
		ob_end_clean();  //注释，可看到发送的参数

		//关闭链接
		curl_close($ch) ;

		return $result;
	}

    public static function postLottery($url, $fields, $token, $host){
        $header = array(
            'Content-Type:application/xml; charset=utf-8',
            'Authorization: Basic '.$token
        );
        $ch = curl_init() ;
        curl_setopt($ch, CURLOPT_URL, $url) ;
        curl_setopt($ch, CURLOPT_POST, count($fields)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURL_HTTP_VERSION_1_1, 'HTTP/1.1');    //手动指定HTTP版本
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    //使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);       //自动设置Referer
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);    //从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);          //设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //获取的信息以文件流的形式返回
        $result = curl_exec($ch);   //执行操作
        curl_close($ch) ;
        return $result;
    }

	public static function getLottery($url, $token, $host){
        $header = array(
            'Content-Type:application/xml; charset=utf-8',
            'Authorization: Basic '.$token
        );
        $ch = curl_init();  //启动一个CURL会话
        curl_setopt($ch, CURLOPT_URL, $url);    //要访问的地址
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET'); //指定提交方式
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);  //添加Header
        curl_setopt($ch, CURL_HTTP_VERSION_1_1, 'HTTP/1.1');    //手动指定HTTP版本
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);   //模拟用户使用的浏览器
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);    //使用自动跳转
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);       //自动设置Referer
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);    //对认证证书来源的检查
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 1);    //从证书中检查SSL加密算法是否存在
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);          //设置超时限制防止死循环
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    //获取的信息以文件流的形式返回
        $result = curl_exec($ch);   //执行操作
        //var_dump(curl_error($ch));
        curl_close($ch);
        return $result;
	}

    public static function get($url){
        //初始化
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        //执行并获取HTML文档内容
        $result = curl_exec($ch);
        curl_close($ch) ;
        return $result;
    }
    
    /**
     * 数组参数转url字符串
     */
    public static function array2Url($array = null) {
    	$url = "";
    	if (!empty($array)) {
    		foreach ($array as $k => $v) {
    			$url .= $k . '=' . $v . '&';
    		}
    		$url = substr($url, 0, -1);
    	}
    	return $url;
    }
    
	public static function upload($url, $file,$filesize,$fileName,$host){
		$ch = curl_init();
		
		$data = file_get_contents($file);
		$headerArr = array(
				"PUT /".$fileName." HTTP/1.1",
				"Host: ".$host,
				"Content-Length: $filesize"
		);
		curl_setopt($ch, CURLOPT_URL, $url); //设置请求的URL
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); //设为TRUE把curl_exec()结果转化为字串，而不是直接输出
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT'); //设置请求方式
		 
		curl_setopt($ch, CURLOPT_HTTPHEADER,$headerArr);//设置HTTP头信息
		curl_setopt($ch, CURLOPT_HEADER,false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);//设置提交的字符串
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  FALSE);
		
		$result = curl_exec($ch);
		$httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
		curl_close($ch);
		return $httpCode;
	}
}