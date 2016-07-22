<?php

//define your token and domain
define("TOKEN", "demo");

$domain = "demo";
$token = TOKEN;

$demo = new WukongAuthDemo();
$auth = $demo->getSha1Sign($domain,$token);
echo $auth;
$demo->checkSha1Sign($auth);

class WukongAuthDemo
{
	/**
	 * description: 验证http Authorization 签名是否正确
	 * @param httpAuth  http Authorization头
	 * @param appToken  应用的token
	 * @param appDomain 应用的domain
	 * @return false 验签失败 ; true 验签成功
	 */
    public function checkSha1Sign($authorization)
    {   
	    echo "enter into checkSha1Sign\n";
		//e.g. authorization的值类似如下:
	    //Wukong nonce="73165416", domain="demo", timestamp="1425611635", signature_method="sha1", version="1.0", signature="bc9ddc1b264a7709042f3329fca99b8fbb393863"
        list($protocol, $args) = explode(" ", $authorization, /*limit*/2);
        $params = array();
        foreach (explode(",", trim($args)) as $item) {
            list($key, $value) = explode("=", $item, /*limit*/2);
            $params[trim($key)] = trim($value, " \t\n\r\"");
        }

        $signature = $params["signature"];
        $timestamp = $params["timestamp"];
        $nonce = $params["nonce"];
        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode($tmpArr);
        $expect = sha1($tmpStr);

        if ($expect == $signature) {
		    echo "checkSha1Sign true\n";
            return true;
        } else {
		    echo "checkSha1Sign false\n";
            return false;
        }
    }

	/**
	 * description: 根据domain 和token 生成 http Authorization 签名
	 * @param domain   应用申请的domain
	 * @param token    应用申请的token
	 * @return String  http Authorization
	 */
    public function getSha1Sign($domain,$token)
	{
        $nonce = mt_rand(100000,200000);
        $timestamp = time();
        $signature_array=array(
            $token,
            (string)$nonce,
            (string)$timestamp,
        );
        sort($signature_array, SORT_STRING);
        $signature= sha1(implode($signature_array));
        return "Wukong nonce=\"{$nonce}\", domain=\"{$domain}\", timestamp=\"{$timestamp}\", signature_method=\"sha1\", version=\"1.0\", signature=\"{$signature}\"";
    }
}

?>
