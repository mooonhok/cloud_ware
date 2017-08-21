<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 17:12
 */
//function getuserinfo(){
//    $appid='wx15ef051f9f0bba92';
//    $redirect_uri = urlencode ( 'http://mooonhok-cloudware.daoapp.io/test12.php' );
//    $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
//    header("Location:".$url);
//}
//
//getuserinfo();

header('Content-type:text/html;charset=utf-8');
if(!isset($_GET['code'])){
    $appid='wx15ef051f9f0bba92';
    $redirect_uri = urlencode ( 'http://mooonhok-cloudware.daoapp.io/test.php' );
    $scope='snsapi_base';
    $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
    header('Location:'.$url);
}else{
    $appid = "wx15ef051f9f0bba92";
    $secret = "57ea0ee4abf4f4c6d6e38c88a289e687";
    $code = $_GET["code"];
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $json_obj=json_decode($output, true);
    $_SESSION['openid']=$json_obj['openid'];
//    echo $json_obj['openid'];
    header('Location:http://mooonhok-cloudware.daoapp.io/weixin/chenggong.html');
}

?>