<?php
//网管接口测试
set_time_limit(0);
$ch = curl_init();


$post_data = array(
	'action' => 'getPermission',
	'timestamp' => time(),
	'appKey' => '12345692',
	'tenantid' => 'yctv',
	'uid' => 22,
	'token' => '574CF5ED-6F68-4140-941E-0F18C0A8630A',
	//'type' => 1,
	);
//$appSecret = 'be196ff7e42f35b1abf3efadcb8931ac72b60b03';
$appSecret = '7dbeafb769c0135e911d7bbb3441f01798f0119f';
$publicParams = array( 'appKey', 'action', 'timestamp', 'sign' ,'tenantid');
$pArr = array_diff_key( $post_data, array_flip($publicParams) );
ksort( $pArr );  //按参数名对参数进行升序排序

$pStr2 = '';
foreach ( $pArr as $k => $v ) {      
	$pStr2 .= $k . $v ;  
}

$sign = md5( $pStr2 . $appSecret );

$post_data['sign'] = $sign;

$post_data = json_encode($post_data);


$post_data = array('parameter'=>$post_data);
echo "<pre>";
print_r($post_data);die;
$post_data = http_build_query($post_data);



$url = 'http://yctv.ida.sobeycloud.com/interface';


curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 0);
curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);


curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
$outopt = curl_exec($ch);
if($outopt === FALSE)
{
    echo "<br/>"."curl errno:".curl_errno($ch).'<br>'."cUrl Error:".curl_error($ch);
}
curl_close($ch);


