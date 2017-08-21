<?php


$appid = "wx15ef051f9f0bba92";
$secret = "57ea0ee4abf4f4c6d6e38c88a289e687";

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
    if ($_COOKIE==null){
       setcookie('openid',$openid);
    }
}
//    echo '用户信息'.$openid.'<br/>';
//    echo $_COOKIE['opendid'];
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


//����json
    $user_obj = json_decode($res,true);
    var_dump($user_obj);
    return 'Name:'.$user_obj['nickname'];
}
echo '<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta content="width=device-width,initial-scale=1.0,maximum-scale=1,minimum-scale=0.1,user-scalable=0" name="viewport">
		<link rel="stylesheet" href="zhuce.css">
		<title>注册</title>
		 <script type="text/javascript" src="jquery-3.2.1.min.js"></script>
        <script type="text/javascript">
            $(function(){
                //改变div的高度
                $(".content").css("height", $(window).height());
            });
        </script>
		
	</head>
	<body>
		<div class="content">
			<div class="box_top"><div class="box_top1">Welcome</div></div>
			<div class="box_center">

				<div class="box_center1"><span>注<span class="kong"></span>册</span></div>

				<div class="box_center1"><input id="customername" placeholder="货主名称"></div>

				<div class="box_center1"><input id="customertel"  placeholder="手机号码"></div>

				</div>
			<div class="box_buttom">
				<div id="submit"  onclick="a()">注<span class="kong"></span>册</div>
			</div>
		</div>
	</body>
	<script>
	function  a(){
		var customername=$("#customername").val();
		var customertel=$("#customertel").val();
		alert(customername+customertel);
	   $.ajax({
		            url :"http://mooonhok-cloudware.daoapp.io/customer.php/customer",
		             beforeSend: function(request) {  
                        request.setRequestHeader("tenant-id", "Chenxizhang");  
                    },
			        dataType:\'json\',
			        type:\'post\',
			        contentType:"application/json;charset=utf-8",
			        data:JSON.stringify({
					customer_name:customername,
                    customer_phone:customertel,
                     wx_openid:';$_COOKIE['openid'];
			      echo  '}),
			        success:function(msg){
		        	 alert("用户注册成功"+msg.result+"/////"+msg.desc+"//////"+msg.customer);
                         window.location.href="chenggong.html";
				    },
			        error:function(xhr){
			            alert("用户注册失败！"+xhr.responseText);
			        }
	            });
         });
            </script>
</html>
'
?>

