<?php
 function getBaseInfo(){

	 $appid = "wx15ef051f9f0bba92";
	 $redirect_uri = urlencode("http://mooonhok-cloudware.daoapp.io/weixin/test01.php");
	 $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=".$appid."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_base&state=123#wechat_redirect";
	 header('location:'.$url);
}
getBaseInfo();

?>