<?php
header('Content-type:text/html;charset=utf-8');
$str=$_SERVER["QUERY_STRING"];
$arr=explode("=",$str);
$tenant_id=substr($arr[1],0,10);
$page=$arr[4];
$appid=substr($arr[2],0,18);
$secret=substr($arr[3],0,32);


function getOpenID($appid,$appsecret,$code){
$url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=". 
$appsecret."&code=".$code."&grant_type=authorization_code";

$weixin=file_get_contents($url);//通过code换取网页授权access_token
$jsondecode=json_decode($weixin); //对JSON格式的字符串进行编码
$array = get_object_vars($jsondecode);//转换成数组
$openid = $array['openid'];//输出openid
return $openid;
}

echo getOpenID($appid,$appsecret,$code);
?>
