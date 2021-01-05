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

$addArray = array(
    'appkey' => $sign['appkey'],
    'timestamp' => $timestamp,
    'sign' => $md5
);

// 获取账号体系根节点信息 
//open.kujiale.com/open/apps/1/docs?doc_id=434&tab_id=api&path=0_58_433_434&tag_id=3
// $url = 'https://sandbox-openapi.kujiale.com/v2/root/node';
// $url .= '?timestamp='.$sign['date'].'&';
// $url .= 'appkey='.$sign['appkey'].'&';
// $url .= 'sign='.md5($sign['appSecret'].$sign['appkey'].$sign['date']);
// echo '<b>URL:</b> '.$url.'<br>';
// echo '<b>timestamp:</b> '.$timestamp.'<br>';
// echo '<b>sign:</b> '.md5($sign['appSecret'].$sign['appkey'].$sign['date']).'<br>';
// $echo = geturl($url);
// echo '<b>获取账号体系根节点信息:</b><br><pre> ';
// var_dump($echo);
// echo '</pre><br>';

//账号注册绑定接口
//open.kujiale.com/open/apps/1/docs?doc_id=524&tab_id=api&path=0_58_433_524&tag_id=3
$url = 'https://sandbox-openapi.kujiale.com/v2/register';
// $url .= '?timestamp='.$sign['date'].'&';
// $url .= 'appkey='.$sign['appkey'].'&';
// $url .= 'appuid=1&';
// $url .= 'sign='.md5($sign['appSecret'].$sign['appkey'].'1'.$sign['date']);
echo '<b>URL:</b> '.$url.'<br>';
$data = array('name'=>'ABC');
//$data = array_merge($data,$addArray);
$headerArray = array('Content-Type:application/json;charset=utf-8;',',"Accept:application/json"');
// $echo = posturl($url,$data);
// echo '<b>建部门节点:</b><br><pre> ';
// var_dump($echo);
// echo '</pre><br>';

$data  = json_encode($data);    
        $headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
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
        echo json_decode($output，true);

?>