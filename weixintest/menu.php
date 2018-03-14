<?php
//header("Access-Control-Allow-Origin:*");
//header("Access-Control-Allow-Methods:GET,POST,PUT,DELETE,OPTIONS");
header('Content-type:text/html;charset=utf-8');
$str=$_SERVER["QUERY_STRING"];
$arr=explode("=",$str);
$tenant_id=substr($arr[1],0,10);
$page=$arr[4];
$appid=substr($arr[2],0,18);
$secret=substr($arr[3],0,32);

if ($_COOKIE['openid'.$tenant_id] == null) {
    if (!isset($_GET['code'])) {
      //  $appid = 'wx81d659de6151801e';
        $redirect_uri = urlencode('http://api.uminfor.cn/weixintest/menu.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret.'&page='.$page);
        $scope = 'snsapi_base';
        $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
        header('Location:' . $url);
    } else {
       // $appid = "wx81d659de6151801e";
       // $secret = "a777207a723e6f5ce885687caa5198e3";
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
        setcookie('openid'.$tenant_id, $json_obj['openid']);
         if ($page==7){
            header('location:http://api.uminfor.cn/weixintest/build.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==6){
            header('location:http://api.uminfor.cn/weixintest/my_consignment_note.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==5){
            header('location:http://api.uminfor.cn/weixintest/register.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==4){
            header('location:http://api.uminfor.cn/weixintest/c_tenant.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
       }else if($page==3){
            header('location:http://api.uminfor.cn/weixintest/c_name.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==2){
           header('location:http://api.uminfor.cn/weixintest/query.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==1){
            header('location:http://api.uminfor.cn/weixintest/send.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }
    }
}else{
        if ($page==7){
            header('location:http://api.uminfor.cn/weixintest/build.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==6){
            header('location:http://api.uminfor.cn/weixintest/my_consignment_note.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==5){
            header('location:http://api.uminfor.cn/weixintest/register.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==4){
            header('location:http://api.uminfor.cn/weixintest/c_tenant.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
       }else if($page==3){
            header('location:http://api.uminfor.cn/weixintest/c_name.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==2){
           header('location:http://api.uminfor.cn/weixintest/query.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }else if($page==1){
            header('location:http://api.uminfor.cn/weixintest/send.php?tenant_id='.$tenant_id.'&appid='.$appid.'&secret='.$secret);
        }
}

?>
