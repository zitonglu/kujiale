<?php
/**
 * 使用POST方式提供API，默认header提交模式
 *
 * @since //www.runoob.com/php/php-ref-curl.html
 * @version 1.0105
 * @author zitonglu/910109610@qq.com
 * @param $url:提交的网址；$data:提交数组；$headerArray:头部数组
 * @return json_decode
 */
function posturl($url,$data,$headerArray=''){
    $data  = json_encode($data);
    if (empty($headerArray)) {
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($curl);
    curl_close($curl);
    return json_decode($output，true);
}
/**
 * 使用GET方式提供API，默认header提交模式
 *
 * @since //www.runoob.com/php/php-ref-curl.html
 * @version 1.0105
 * @author zitonglu/910109610@qq.com
 * @param $url:提交的网址；$headerArray:头部数组
 * @return json_decode
 */
function geturl($url,$headerArray=''){
    if (empty($headerArray)) {
        $headerArray =array("Content-type:application/json;","Accept:application/json");
    }
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    return $output;
}

// 创建API链接密匙
$timestamp = date_timestamp_get(date_create())*1000;
$sign = array(
	'appkey'=>'nnukVb2cN0',
	'appSecret'=>'PFND9hffNZHmtgZvUCEq2Bn4TDHgazjI',
	'date'=>$timestamp
);
$md5 = md5($sign['appSecret'].$sign['appkey'].$sign['date']);

// 创建CURL变量并输出相关 2020-1-5
$url = 'https://sandbox-openapi.kujiale.com/v2/root/node?';
$url .= 'timestamp='.$sign['date'].'&';
$url .= 'appkey='.$sign['appkey'].'&';
$url .= 'sign='.md5($sign['appSecret'].$sign['appkey'].$sign['date']);
echo '<b>URL:</b> '.$url.'<br>';
echo '<b>timestamp:</b> '.$timestamp.'<br>';
echo '<b>sign:</b> '.md5($sign['appSecret'].$sign['appkey'].$sign['date']).'<br>';
$echo = geturl($url);
echo '<b>return:</b><br><pre> ';
var_dump($echo);
echo '</pre><br>';
?>