<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 17:12
 */
function getuserinfo(){
    $appid='wx25c3261d32ae33f0';
    $redirect_uri = urlencode ( 'http://mooonhok-cloudware.daoapp.io/test12.php' );
    $url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
    header("Location:".$url);
}

getuserinfo();

?>