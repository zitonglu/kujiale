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
    curl_setopt($curl, CURLOPT_URL, $url);//设置路径
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);//不做服务器认证
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,FALSE);//不做客户端认证
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);//POST相关数据
    curl_setopt($curl,CURLOPT_HTTPHEADER,$headerArray);//设置头部信息
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
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
    curl_setopt($ch, CURLOPT_URL, $url);//设置路径
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); //不做服务器认证
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); //不做客户端认证
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$headerArray);//设置头部信息
    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    return $output;
}
// 以上内容可以删除
/**
 * CURL请求
 * @param $url 请求url地址
 * @param $method 请求方法 get post
 * @param null $postfields post数据数组
 * @param array $headers 请求header信息
 * @param bool|false $debug  调试开启 默认false
 * @return mixed
 */
function httpRequest($method='get',$url,$headers=array(),$postfields=null,$debug=false) {
    $method = strtoupper($method);
    $ci = curl_init();
    /* Curl settings */
    curl_setopt($ci, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
    curl_setopt($ci, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.2; WOW64; rv:34.0) Gecko/20100101 Firefox/34.0");
    curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 60); /* 在发起连接前等待的时间，如果设置为0，则无限等待 */
    curl_setopt($ci, CURLOPT_TIMEOUT, 7); /* 设置cURL允许执行的最长秒数 */
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    switch ($method) {
        case "POST":
            curl_setopt($ci, CURLOPT_POST, true);
            if (!empty($postfields)) {
                $tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
                curl_setopt($ci, CURLOPT_POSTFIELDS, $tmpdatastr);
            }
            break;
        default:
            curl_setopt($ci, CURLOPT_CUSTOMREQUEST, $method); /* //设置请求方式 */
            break;
    }
    $ssl = preg_match('/^https:\/\//i',$url) ? TRUE : FALSE;
    curl_setopt($ci, CURLOPT_URL, $url);
    if($ssl){
        curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, FALSE); // https请求 不验证证书和hosts
        curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, FALSE); // 不从证书中检查SSL加密算法是否存在
    }
    //curl_setopt($ci, CURLOPT_HEADER, true); /*启用时会将头文件的信息作为数据流输出*/
    curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ci, CURLOPT_MAXREDIRS, 2);/*指定最多的HTTP重定向的数量，这个选项是和CURLOPT_FOLLOWLOCATION一起使用的*/
    curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ci, CURLINFO_HEADER_OUT, true);
    /*curl_setopt($ci, CURLOPT_COOKIE, $Cookiestr); * *COOKIE带过去** */
    $response = curl_exec($ci);
    $requestinfo = curl_getinfo($ci);
    $http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
    if ($debug) {
        echo "=====post data======\r\n";
        var_dump($postfields);
        echo "=====info===== \r\n";
        print_r($requestinfo);
        echo "=====response=====\r\n";
        print_r($response);
    }
    curl_close($ci);
    return $response;
    //return array($http_code, $response,$requestinfo);
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
$url = 'https://sandbox-openapi.kujiale.com/v2/root/node';
$url .= '?timestamp='.$sign['date'].'&';
$url .= 'appkey='.$sign['appkey'].'&';
$url .= 'sign='.md5($sign['appSecret'].$sign['appkey'].$sign['date']);
echo '<b>URL:</b> '.$url.'<br>';
echo '<b>timestamp:</b> '.$timestamp.'<br>';
echo '<b>sign:</b> '.md5($sign['appSecret'].$sign['appkey'].$sign['date']).'<br>';
$echo1 = httpRequest('get',$url);
$echo = json_decode($echo1,true);
// $echo = geturl($url);
echo '<b>获取账号体系根节点信息:</b><br><pre>';
var_dump($echo);
echo '</pre><br>';

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
// $echo = posturl($url,$data);
// echo '<b>建部门节点:</b><br><pre> ';
// var_dump($echo);
// echo '</pre><br>';
  
$headerArray =array("Content-type:application/json;charset='utf-8'","Accept:application/json");
// echo httpRequest($url,'POST',$data,$headerArray);

?>