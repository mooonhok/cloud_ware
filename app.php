<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 17:27
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//客户端获取app二维码
$app->get('/getApp',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('app')
        ->orderBy('id','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $app=$data[0];
        echo  json_encode(array("result"=>"0","desc"=>"success","app"=>$app));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"无app版本"));
    }
});

//app司机注册1
$app->post('/addlorry0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $tel=$body->telephone;
    $password=$body->password;
    if($tel!=null||$tel!=""){
       if($password!=null||$password!=""){
           $selectStament=$database->select()
               ->from('applorry')
               ->where('telephone','=',$tel);
               $stmt=$selectStament->execute();
               $data1=$stmt->fetch();
               if($data1['exist']==1){
                   $deleteStatement = $database->delete()
                       ->from('applorry')
                       ->where('telephone', '=', $tel);
                   $affectedRows = $deleteStatement->execute();
                   $selectStament=$database->select()
                       ->from('applorry');
                   $stmt=$selectStament->execute();
                   $data2=$stmt->fetchAll();
                   $insertStatement = $database->insert(array('lorryid','telephone','password','exist'))
                       ->into('applorry')
                       ->values(array(count($data2)+1,$tel,var_dump($password),1));
                   $insertId = $insertStatement->execute(false);
                   echo  json_encode(array("result"=>"0","desc"=>"",'lorryid'=>count($data2)+1));
               }else{
                   echo  json_encode(array("result"=>"3","desc"=>"电话号码已经被注册"));
               }
       }else{
           echo  json_encode(array("result"=>"2","desc"=>"密码不能为空"));
       }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"您未填写电话号码"));
    }
});

//app司机注册2
$app->post('/addlorry1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $name=$body->name;
    $idcard=$body->idcard;
    $plate_number=$body->plate_number;
    $lorryid=$body->lorryid;
    if($name!=null||$name!=""){
         if($idcard!=null||$idcard!=""){
             if($plate_number!=null||$plate_number!=""){
                 $selectStament=$database->select()
                     ->from('applorry')
                     ->where('lorryid','=',$lorryid);
                 $stmt=$selectStament->execute();
                 $data1=$stmt->fetch();
                 if($data1!=null){
                 $arrays['name']=$name;
                 $arrays['carid']=$idcard;
                 $arrays['platenumber']=$plate_number;
                 $updateStatement = $database->update($arrays)
                     ->table('applorry')
                     ->where('lorryid','=',$lorryid);
                 $affectedRows = $updateStatement->execute();
                     echo  json_encode(array("result"=>"0","desc"=>"","lorryid"=>$lorryid));
                 }else{
                     echo  json_encode(array("result"=>"3","desc"=>"您未填写电话和密码"));
                 }
             }else{
                 echo  json_encode(array("result"=>"3","desc"=>"您未填写车牌号"));
             }
         }else{
             echo  json_encode(array("result"=>"2","desc"=>"您未填写身份证号"));
         }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"您未填写姓名"));
    }
});

//获取车辆类型列表
$app->get('/lorry_type',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament = $database->select()
        ->from('lorry_type');
    $stmt = $selectStament->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        echo json_encode(array('result' => '0', 'desc' => '','lorry_type'=>$data));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未有车辆类型数据'));
    }
});



//app司机注册3
$app->post('/addlorry2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $type=$body->type;
    $long=$body->long;
    $ctype=$body->ctype;
    $lorryid=$body->lorryid;
    if($type!=null||$type!=""){
      if($long!=null||$long!=""){
          if($ctype!=null||$ctype!=""){
              $selectStament=$database->select()
                  ->from('applorry')
                  ->where('lorryid','=',$lorryid);
              $stmt=$selectStament->execute();
              $data1=$stmt->fetch();
              if($data1!=null){
                  $arrays['type']=$type;
                  $arrays['ctype']=$ctype;
                  $arrays['clong']=$long;
                  $updateStatement = $database->update($arrays)
                      ->table('applorry')
                      ->where('lorryid','=',$lorryid);
                  $affectedRows = $updateStatement->execute();
                  echo json_encode(array('result'=>'0','desc'=>'','lorryid'=>$lorryid));
              }else{
                  echo json_encode(array('result' => '4', 'desc' => '未填写电话号码'));
              }
          }else{
              echo json_encode(array('result' => '3', 'desc' => '未选择车辆类型'));
          }
      }else{
          echo json_encode(array('result' => '2', 'desc' => '未填写车辆长度'));
      }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '未选择车辆类别'));
    }
});

//司机注册4(图片1)
$app->post('/addlorry3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $lorryid=$body->lorryid;
    $pic1=$body->pic1;
    $pic2=$body->pic2;
    $lujing1=null;
    $lujing2=null;
    if($pic1!=null){
        $base64_image_content = $pic1;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/lorry/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = "http://files.uminfo.cn:8000/lorry/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driverlicensefp']=$lujing1;
       if($pic2!=null){
           $base64_image_content = $pic2;
//匹配出图片的格式
           if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
               $type = $result[2];
               date_default_timezone_set("PRC");
               $time1 = time();
               $new_file = "/files/lorry2/" . date('Ymd', $time1) . "/";
               if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                   mkdir($new_file, 0700);
               }
               $new_file = $new_file . time() . ".{$type}";
               if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                   $lujing2 = "http://files.uminfo.cn:8000/lorry2/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
               }
           }
           $arrays['driverlicensetp']=$lujing2;
           $selectStament=$database->select()
               ->from('applorry')
               ->where('lorryid','=',$lorryid);
           $stmt=$selectStament->execute();
           $data1=$stmt->fetch();
           if($data1!=null){
               $updateStatement = $database->update($arrays)
                   ->table('applorry')
                   ->where('lorryid','=',$lorryid);
               $affectedRows = $updateStatement->execute();
               echo json_encode(array('result'=>'0','desc'=>'','lorryid'=>$lorryid));
           }else {
               echo json_encode(array('result' => '4', 'desc' => '未填写电话号码'));
           }
       }else{
           echo json_encode(array('result' => '2', 'desc' => '没有驾驶证反面图片'));
       }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有驾驶证正面图片'));
    }
});
//司机注册5（图片2）
$app->post('addlorry4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $lorryid=$body->lorryid;
    $pic3=$body->pic3;
    $pic4=$body->pic4;
    $lujing3=null;
    $lujing4=null;
    if($pic3!=null){
        $base64_image_content = $pic3;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/lorry3/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = "http://files.uminfo.cn:8000/lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driveringlicensefp']=$lujing3;
        if($pic4!=null){
            $base64_image_content = $pic4;
//匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type = $result[2];
                date_default_timezone_set("PRC");
                $time1 = time();
                $new_file = "/files/lorry4/" . date('Ymd', $time1) . "/";
                if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . time() . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing2 = "http://files.uminfo.cn:8000/lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driveringlicensetp']=$lujing4;
            $arrays['exist']=0;
            $selectStament=$database->select()
                ->from('applorry')
                ->where('lorryid','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('applorry')
                    ->where('lorryid','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'','lorryid'=>$lorryid));
            }else {
                echo json_encode(array('result' => '4', 'desc' => '未填写电话号码'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有驾驶证反面图片'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有驾驶证正面图片'));
    }
});


$app->run();
function localhost(){
    return connect();
}
?>