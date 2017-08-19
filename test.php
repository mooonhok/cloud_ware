<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/18
 * Time: 17:12
 */
$appid='wx15ef051f9f0bba92';
$redirect_uri = urlencode ( 'http://mooonhok-cloudware.daoapp.io/test12.php' );
$url ="https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
header("Location:".$url);


?>