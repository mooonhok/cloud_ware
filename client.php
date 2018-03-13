<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16
 * Time: 10:52
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//客户端获取app二维码
$app->get('/getLatest',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('client')
        ->orderBy('id','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $client=$data[0];
        $pack = explode('/',$client['package_url']);
        $client_url='/'.$pack[3].'/'.$pack[4].'/app.asar';
        $size = filesize('/files'.$client_url);
        echo  json_encode(array("result"=>"0","desc"=>"success","client"=>$client,'size'=>$size));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"无客户端版本"));
    }
});

$app->post('/client_version',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $client_version = $app->request->params('client_version');
    $database = localhost();
    $file_url=file_url();
    $version_asar = $_FILES["version_asar"]["name"];
    $i=0;
    $lujing1='';
    $lujing2='';
    if($version_asar){
        $new_file = "/files/client/" . $client_version . "/";
        if (!file_exists($new_file)) {
            mkdir($new_file, 0700);
        }
        move_uploaded_file($_FILES["version_asar"]["tmp_name"], $new_file . $version_asar);
        $lujing1 = $file_url."client/".$client_version."/".$version_asar;
        $i++;
    }
    $version_json = $_FILES["version_json"]["name"];
    if($version_json){
        $new_file = "/files/client/" . $client_version . "/";
        if (!file_exists($new_file)) {
            mkdir($new_file, 0700);
        }
        move_uploaded_file($_FILES["version_json"]["tmp_name"], $new_file . $version_json);
        $lujing2 = $file_url."client/".$client_version."/".$version_json;
        $i++;
    }
    if($i==2){
        $array['version']=$client_version;
        $array['asar_url']=$lujing1;
        $array['package_url']=$lujing2;
        $insertStatement = $database->insert(array_keys($array))
            ->into('client')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        $app->redirect('http://api.uminfo.cn/background/add_client.html');
    }

});


$app->get('/getMust',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $now_version = $app->request->get("now_version");
    $database = localhost();
    $selectStatement = $database->select()
        ->from('client')
        ->where('is_must','=',1)
        ->orderBy('id','DESC')
        ->limit(1);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    if($data!=null){
      $aa=explode(".",$data['version']);
      $bb=explode(".",$now_version);
      if($aa[0]>$bb[0]){
          echo  json_encode(array("result"=>"0","desc"=>"success","client"=>$data));
      }else if($aa[0]==$bb[0]){
          if($aa[1]>$bb[1]){
              echo  json_encode(array("result"=>"0","desc"=>"success","client"=>$data));
          }else if($aa[1]==$bb[1]){
              if($aa[2]>$bb[2]){
                  echo  json_encode(array("result"=>"0","desc"=>"success","client"=>$data));
              }else if($aa[2]==$bb[2]){
                  echo  json_encode(array("result"=>"0","desc"=>"success","client"=>null));
              }else{
                  echo  json_encode(array("result"=>"0","desc"=>"success","client"=>null));
              }
          }else{
              echo  json_encode(array("result"=>"0","desc"=>"success","client"=>null));
          }
      }else{
          echo  json_encode(array("result"=>"0","desc"=>"success","client"=>null));
      }
    }else{
        echo  json_encode(array("result"=>"0","desc"=>"success","client"=>null));
    }
});

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}

?>