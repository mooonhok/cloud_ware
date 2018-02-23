<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/7
 * Time: 23:47
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

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
    if($jsoninfo["access_token"]!=null){
    $access_token = $jsoninfo["access_token"];
    echo  json_encode(array("result"=>"0","desc"=>$access_token));
    }else{
        $data=$jsoninfo["errcode"];
        echo  json_encode(array("result"=>"0","desc"=>""));
    }
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
    $file_url=file_url();
    $tenant_id=$body->tenant_id;
    $size=$body->size;
    $database = localhost();
    if($pic!=null||$pic!="") {
        $base64_image_content = $pic;
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/weixincontrol/image/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = $file_url."tenant/image/" . $time1 . ".{$type}";
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
                       $ch1 = curl_init();
                       $timeout = 5;
                       $real_path="{$file_info['filename']}";
                       if (class_exists('\CURLFile')) {
                           $data6 = array('fieldname' => new \CURLFile(realpath($real_path)),'form-data'=>$file_info);
                       } else {
                           $data6 = array('fieldname' => '@' . realpath($real_path),'form-data'=>$file_info);
                       }
//                       $data6= array("media"=>"@{$real_path}",'form-data'=>$file_info);
                       $data6=urldecode( json_encode( $data6 ) );
//                       $data6= array("media"=>'@'.$lujing1,'form-data'=>$file_info);
                       curl_setopt($ch1, CURLOPT_URL, $url);
                       curl_setopt($ch1, CURLOPT_POST, 1);
                       curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                       curl_setopt($ch1, CURLOPT_CONNECTTIMEOUT, $timeout);
                       curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, false);
                       curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, false);
                       curl_setopt($ch1, CURLOPT_POSTFIELDS, $data6);
                       $result = curl_exec($ch1);
                       if (curl_errno($ch1) == 0) {
                          $result = json_decode($result, true);
                           echo json_encode(array("result"=>"0","desc"=>$result));
                       } else {
                           echo json_encode(array("result"=>"3","desc"=>"上传微信公众号服务器失败"));
                       }
                       $dir="weixincontrol/image";
                       $dh=opendir($dir);
                       while ($file=readdir($dh)) {
                           if($file!="." && $file!="..") {
                               $fullpath=$dir."/".$file;
                               if(!is_dir($fullpath)) {
                                   unlink($fullpath);
                               } else {
                                   deldir($fullpath);
                               }
                           }
                       }
                       closedir($dh);
                       curl_close($ch1);
                   }else{
                       echo  json_encode(array("result"=>"5","desc"=>"数据库缺少微信账号信息"));
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
//新建图文消息素材
$app->post("/addmessage",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $picid=$body->pic;
    $content_img=$body->pic2;//正文中图片
    $autor=$body->autor;
    $title=$body->title;
    $jtext=$body->jtext;
    $message=$body->message;
    $url2=$body->fromwhere;
    $database = localhost();
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
    //新加图文素材；
              $thumb_media_id=$picid;
                 $url = "https://api.weixin.qq.com/cgi-bin/material/add_news?access_token=".$access_token;
         $array = array(
              "articles" => array(                                /*若新增的是多图文素材，则此处应还有几段articles结构  */
                 array(
                "title"               => urlencode($title),
                "thumb_media_id"      => $thumb_media_id,        //图文消息的封面图片素材id（必须是永久mediaID）
                "author"              => urlencode($autor),            //作者
                "digest"             => urlencode($jtext),            //图文消息的摘要，仅有单图文消息才有摘要，多图文此处为空
                "show_cover_pic"    => 1,            //是否显示封面，0为false，即不显示，1为true，即显示
                "content"             => urlencode("<img src='{$content_img}' /><br /><div>".$message."</div>"),            //图文消息的具体内容，支持HTML标签，必须少于2万字符，小于1M，且此处会去除JS
                "content_source_url" => urlencode($url2)            //图文消息的原文地址，即点击“阅读原文”后的URL
            )
        ),
    );
    $postJson = urldecode( json_encode( $array ) );
    //1.初始化curl
    $curl = curl_init();
    //2.设置curl的参数
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);

    //3.采集
              $output = curl_exec($curl);
    //4.关闭
              curl_close($curl);
//    echo json_decode($output,true);
                  $res2=json_decode($output,true);
                echo json_encode(array("result"=>"0","desc"=>$res2));
        }else{
            echo  json_encode(array("result"=>"3","desc"=>"尚未开通微信公众号"));
        }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"缺少租户不存在"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"未选择租户"));
    }
});

//发送消息
$app->post('/sendall',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $media_id=$body->media_id;
    $database = localhost();
    $teamid=$body->team;
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
                $url = "https://api.weixin.qq.com/cgi-bin/message/mass/sendall?access_token=".$access_token;
                $array=array(
                    'filter' => array(            //用于设定图文消息的接收者
                        'is_to_all' => true,            //是否向全部用户发送，值为true或false，选择true该消息群发给所有用户，选择false可根据tag_id发送给指定群组的用户
                        'tag_id'     =>$teamid,            //群发到的标签的tag_id，参加用户管理中用户分组接口，若is_to_all值为true，可不填写tag_id
                    ),
                    'mpnews' => array(            //用于设定即将发送的图文消息
                        'media_id'  => $media_id,            //用于群发的消息的media_id
                    ),
                    'msgtype'=> 'mpnews',                //群发的消息类型，图文消息为mpnews，文本消息为text，语音为voice，音乐为music，图片为image，视频为video，卡券为wxcard
                );
                $postJson = json_encode( $array );
                $curl = curl_init();
                //2.设置curl的参数
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST,2);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, $postJson);
               //3.采集
                $output = curl_exec($curl);
               //4.关闭
                curl_close($curl);
               // echo json_decode($output,true);
                $res2=json_decode($output,true);
                echo  json_encode(array("result"=>"0","desc"=>$res2));
              }else{
                 echo  json_encode(array("result"=>"3","desc"=>"尚未开通微信公众号"));
                }
             }else{
                echo  json_encode(array("result"=>"2","desc"=>"缺少租户不存在"));
            }
    }else{
    echo  json_encode(array("result"=>"1","desc"=>"未选择租户"));
  }
});

$app->post('/wxmomessage',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database = localhost();
    $tenant_id=$body->tenant_id;
    $openid=$body->openid;
    $templateid="0XdWHw-LDDWHgtrIbKq1F3JaGXQmQxE5SR2cb9iEf-c";
    $title=$body->title;
    $message=$body->message;
    date_default_timezone_set("PRC");
    $time = date("Y-m-d H:i",time());
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
                 $template=array("touser"=>$openid,"template_id"=>$templateid
                 ,"data"=>array("first"=>array("value"=>$title,"color"=>"#173177"),
                         "keyword1"=>array("value"=>$time,"color"=>"#173177"),
                         "keyword2"=>array("value"=>$message,"color"=>"#173177")));
                $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
                $postJson = urldecode( json_encode( $template));
                 $ch1 = curl_init();
                 curl_setopt($ch1, CURLOPT_URL, $url);
                 curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
                 // POST数据
                 curl_setopt($ch1, CURLOPT_POST, 1);
                // 把post的变量加上
                  curl_setopt($ch1, CURLOPT_POSTFIELDS, $postJson);
                  $output2 = curl_exec($ch1);
                  curl_close($ch1);
                $res2=json_decode($output2,true);
                echo  json_encode(array("result"=>"0","desc"=>$res2));
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

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}


