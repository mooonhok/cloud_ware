<?php
header('Content-type:text/html;charset=utf-8');
$str=$_SERVER["QUERY_STRING"];
$arr=explode("=",$str);
$tenant_id=substr($arr[1],0,10);
$page=substr($arr[2],0,1);
$appid=substr($arr[3],0,18);
$secret=$arr[4];
if ($_COOKIE['openid'] == null) {
    if (!isset($_GET['code'])) {
        //     $appid = 'wx15ef051f9f0bba92';
        $redirect_uri = urlencode('http://api.uminfo.cn/test.php?tenant_id='.$tenant_id.'&page='.$page.'&appid='.$appid.'$secret='.$secret);
        $scope = 'snsapi_base';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        header('Location:' . $url);
    } else {
        //     $appid = "wx15ef051f9f0bba92";
        //     $secret = "57ea0ee4abf4f4c6d6e38c88a289e687";
        $code = $_GET["code"];
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        curl_close($ch);
        $json_obj = json_decode($output, true);
        // echo $json_obj['openid'];
        setcookie('openid', $json_obj['openid']);
        if ($page==7){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==6){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==5){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==4){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==3){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==2){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }else if($page==1){
            header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
        }
    }
}else{
    if ($page==7){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==6){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==5){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==4){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==3){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==2){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }else if($page==1){
        header('location:http://weixin.uminfo.cn/build.html?tenant_id='.$tenant_id);
    }

}
?>
