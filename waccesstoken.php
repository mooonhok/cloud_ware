<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/7
 * Time: 23:47
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/sign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($name!=null||$name!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=','4')
            ->where('username','=',$name);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
            if($data['password']==$password){
                echo json_encode(array('result' => '0', 'desc' => '登录成功',"admin"=>$data['id']));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '密码错误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '用户不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '名字为空'));
    }
});


//手动输入微信appid
$app->post('/access',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $appid=$body->appid;
    $appsecret=$body->appsecret;
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $jsoninfo = json_decode($output, true);
    $access_token = $jsoninfo["access_token"];
//    echo $access_token;
    echo  json_encode(array("result"=>"0","desc"=>$access_token));
});


//微信后台依据租户获取accesstoken
$app->post('/addpic',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('admin_id','=',$tenant_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetch();
        if($data2!=null){
           if($data2['appid']!=null&&$data2['secret']!=null){
               $appid=$data2['appid'];
               $appsecret=$data2['appsecret'];
               $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
               $ch = curl_init();
               curl_setopt($ch, CURLOPT_URL, $url);
               curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
               curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
               curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
               $output = curl_exec($ch);
               curl_close($ch);
               $jsoninfo = json_decode($output, true);
               $access_token = $jsoninfo["access_token"];
               echo  json_encode(array("result"=>"0",'desc'=>'',"access"=>$access_token));
           }else{
               echo  json_encode(array("result"=>"2","desc"=>"数据库缺少微信账号信息"));
           }
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"租户不存在"));
        }
    }else{
        echo  json_encode(array("result"=>"0","desc"=>"缺少租户id"));
    }
});

//上传永久的图片素材



$app->run();

function localhost(){
    return connect();
}