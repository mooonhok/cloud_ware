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
$app->post('/showacc',function()use($app){
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
            ->where('tenant_id','=',$tenant_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetch();
        if($data2!=null){
           if($data2['appid']!=null&&$data2['secret']!=null){
               $appid=$data2['appid'];
               $appsecret=$data2['secret'];
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
        echo  json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

//上传永久的图片素材到服务器
$app->post('/addpic',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $pic=$body->pic;
    $tenant_id=$body->tenant_id;
    $size=$body->size;
    $database = localhost();
    if($pic!=null||$pic!="") {
        $base64_image_content = $pic;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/weixinsucai/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = "http://files.uminfo.cn:8000/weixinsucai/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
       if($size!=null||$size!=""){
           if($tenant_id!=null||$tenant_id!=null){
               $selectStament=$database->select()
                   ->from('tenant')
                   ->where('exist','=',0)
                   ->where('tenant_id','=',$tenant_id);
               $stmt=$selectStament->execute();
               $data2=$stmt->fetch();
               if($data2!=null){
                   if($data2['appid']!=null&&$data2['secret']!=null){
                       $appid=$data2['appid'];
                       $appsecret=$data2['secret'];
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
                       $medid=null;
                       $file_info = array('filename' => $lujing1, //国片相对于网站根目录的路径
                           'content-type' => 'image', //文件类型
                           'filelength' => $size //图文大小
                       );
                       $url = "https://api.weixin.qq.com/cgi-bin/material/add_material?access_token={$access_token}&type=image";
                       $ch1 = curl_init ();
                       $timeout = 5;
                       $real_path="{$file_info['filename']}";
                       $data= array("media"=>"@{$real_path}",'form-data'=>$file_info);
                       curl_setopt($ch1, CURLOPT_URL, $url);
                       curl_setopt($ch1, CURLOPT_POST, 1);
                       curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                       curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, $timeout);
                       curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                       curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
                       curl_setopt($ch1, CURLOPT_POSTFIELDS, $data);
                       $result = curl_exec($ch1);
                       curl_close($ch1);
                       if (curl_errno() == 0) {
                           $result = json_decode($result, true);
//                           var_dump($result);
                          $medid=$result['media_id'];
                           echo json_encode(array("result"=>"0","desc"=>"","medid"=>$medid,'error'=>$result));
                       } else {
                           echo json_encode(array("result"=>"3","desc"=>"上传微信公众号服务器失败"));
                       }
                   }else{
                       echo  json_encode(array("result"=>"2","desc"=>"数据库缺少微信账号信息"));
                   }
               }else{
                   echo  json_encode(array("result"=>"1","desc"=>"租户不存在"));
               }
           }else{
               echo  json_encode(array("result"=>"3","desc"=>"未选择租户"));
           }
       }else{
           echo  json_encode(array("result"=>"2","desc"=>"缺少图片大小数据"));
       }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少图片"));
    }
});


$app->run();

function localhost(){
    return connect();
}