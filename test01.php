<?php

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
    return 'Name:'.$user_obj['nickname'];
}
echo '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta content="width=device-width,initial-scale=1.0,maximum-scale=1,minimum-scale=0.1,user-scalable=0" name="viewport">
    <title></title>
    <script type="text/javascript">
        $(function(){
            //改变div的高度
            $(".content").css("height", $(window).height());
        });
    </script>
    <style>
        *{
            margin:0 auto;
            padding:0;
            box-sizing:border-box;
        }
        .content{
            background-color:#24D9C9;
            width:100%;
        }
        .box_top{
            width: 100%;
            height:150px;
            background-color:#24D9C9;
        }
        .box_top1{
            width: 100%;
            height: 60px;
            padding-top: 80px;
            font: 40px "微软雅黑";
            text-align: center;
            color:white;
        }
        .box_center{
            border-radius:10px;
            width: 90%;
            height: 250px;
            background-color:#2EBEB1;
            font: 30px "微软雅黑";
            text-align: center;
            color:white;
        }
        .box_center1{
            width: 100%;
            height: 50px;
            margin-top: 15px;
            line-height:50px;
        }
        .box_center1 span{
            height: 50px;
            font: 18px "微软雅黑";
            color:white;
        }
        .box_center1 input{
            border-radius:50px;
            width: 75%;
            height:45px;
            background-color:#42A199;
            line-height: 45px;
            border-style: none;
            font: 15px "微软雅黑";
            color:#FFFFFF;
            padding:0 18px;
            outline: 0;
        }
        .box_center1 input::-webkit-input-placeholder {
            color:#9CD2CD;
        }
        .box_center1 input:-moz-placeholder {
            color:#9CD2CD;
            opacity:1;
        }
        .box_center1 input::-moz-placeholder {
            color:#9CD2CD;
            opacity:1;
        }
        .box_center1 input:-ms-input-placeholder {
            color:#9CD2CD;
        }
        .box_buttom{
            width: 100%;
            height: 100px;
            text-align: center;
            margin-top: 20px;
        }
        #submit{
            border-radius:50px;
            width: 75%;
            height:50px;
            background-color:#005757;
            margin-top: 22px;
            font: 25px "微软雅黑";
            line-height: 50px;
            text-align: center;
            color:#48D1CC;
            background-color:white;
        }
    </style>
</head>
<body>
<div class="content">
    <div class="box_top"><div class="box_top1">Welcome</div></div>
    <div class="box_center">
        <div class="box_center1"><span>注册</span></div>
        <div class="box_center1"><input id="customername" placeholder="货主名称"></input></div>
        <div class="box_center1"><input id="customertel"  placeholder="手机号码"></input></div>
    </div>
    <div class="box_buttom">
        <div id="submit">注册</div>
    </div>
</div>
</body>

</html>
'




?>

