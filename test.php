<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/19
 * Time: 13:30
 */
function getBaseInfo(){

    $appid = "wx15ef051f9f0bba92";
    $redirect_uri = urlencode("http://mooonhok-cloudware.daoapp.io/test.php");
    $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
    header('location:'.$url);
}
getBaseInfo();


//获取到网页授权的access_token
$appid = "wx15ef051f9f0bba92";//填写公众号或服务号、测试号的appid
$secret = "57ea0ee4abf4f4c6d6e38c88a289e687";//填写对应的secriet

if(isset($_SESSION['openId'])){
    $openid = $_SESSION['openId'];
}else{
    $code = $_GET['code'];
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code ";

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res,true);
    $openid = $json_obj['openid'];
    $_SESSION['openId'] = $openid;
}
    echo '微信用户：'.$openid.'<br/>';
    echo '$json_obj 返回信息：';
    var_dump($json_obj);

    getUserInfo($json_obj);
/**
 * @param $json_obj
 * 根据用户openid 获取其所有信息
 */
function getUserInfo($json_obj){
    $access_token = $json_obj['access_token'];
    $openid = $json_obj['openid'];
    $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);

//解析json
    $user_obj = json_decode($res,true);
    var_dump($user_obj);
    echo 'Name:'.$user_obj['nickname'];
}


?>

