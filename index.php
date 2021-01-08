<?php
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
                $tmpdatastr = is_array($postfields) ? json_encode($postfields) : $postfields;
                //$tmpdatastr = is_array($postfields) ? http_build_query($postfields) : $postfields;
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
        echo "=====post data======\r\n".'<br>';
        var_dump($postfields);
        echo '<br>'."=====info===== \r\n".'<br>';
        print_r($requestinfo);
        echo '<br>'."=====response=====\r\n".'<br>';
        print_r($response);
    }
    curl_close($ci);
    return $response;
    //return array($http_code, $response,$requestinfo);
}
/**
 * URL增加？后缀
 * @param $url 请求url地址
 * @param $appuid 酷家乐要求的对应参数
 * @return string url 新网址
 */
function UrlMD5($url,$appuid=null){
    $timestamp = date_timestamp_get(date_create())*1000;
    $appkey = 'nnukVb2cN0';
    $appSecret = 'PFND9hffNZHmtgZvUCEq2Bn4TDHgazjI';

    $url .= '?timestamp='.$timestamp;
    $url .= '&appkey='.$appkey;
    if (empty($appuid)) {
        $sign = md5($appSecret.$appkey.$timestamp);
        $url .= '&sign='.$sign;
    }else{
        $sign = md5($appSecret.$appkey.$appuid.$timestamp);
        $url .= '&sign='.$sign;
        $url .= '&appuid='.$appuid;
    }
    return $url;
}

// 获取账号体系根节点信息 
//open.kujiale.com/open/apps/1/docs?doc_id=434&tab_id=api&path=0_58_433_434&tag_id=3
$url = 'https://sandbox-openapi.kujiale.com/v2/root/node';
$url = UrlMD5($url);
echo '<b>URL:</b> '.$url.'<br>';
$echo1 = httpRequest('get',$url);
$echo = json_decode($echo1,true);
echo '<b>获取账号体系根节点信息:</b><br><pre>';
var_dump($echo);
echo '</pre><br>';

//账号注册绑定接口
//open.kujiale.com/open/apps/1/docs?doc_id=524&tab_id=api&path=0_58_433_524&tag_id=3
$url = 'https://sandbox-openapi.kujiale.com/v2/register';
$urlMD5 = urlMD5($url,'sogie');
$data = array(
    "name"=>"newName",
    "email"=>"user@kujiale.com"
);
$headerArray =array("Content-Type: application/json;charset='utf-8'");

echo '<b>URL:</b> '.$url.'<br>';
$echo1 = httpRequest('POST',$urlMD5,$headerArray,$postfields);
$echo = json_decode($echo1,true);
echo '<br><b>账号注册绑定接口:</b><br><pre>';
var_dump($echo);
echo '</pre><br>';
?>