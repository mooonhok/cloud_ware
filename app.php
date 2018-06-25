<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 17:27
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->post('/change_app',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $file_url=file_url();
    $phone = $app->request->params('client_version');

    if(isset($_FILES["app_apk"]["name"])){
        unlink('/files/app/jiaofu.apk');
        move_uploaded_file($_FILES["app_apk"]["tmp_name"],"/files/app/jiaofu.apk");
    }
    if(isset($_FILES["app_ipa"]["name"])){
        unlink('/files/app/jiaofu.ipa');
        move_uploaded_file($_FILES["app_ipa"]["tmp_name"],"/files/app/jiaofu.ipa");
    }
});

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
    $body=json_decode($body);
    $tel=$body->telephone;
    $password1=$body->password;
    $str1=str_split($password1,2);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $a=strlen($password1);
        $password.=$str1[$x].$a;
        $a=$a-1;
    }
    if($tel!=null||$tel!=""){
        if($password!=null||$password!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('phone','=',$tel)
                ->where('exist','=',0);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1){
                echo  json_encode(array("result"=>"3","desc"=>"电话号码已经被注册"));
            }else{
//                $deleteStatement = $database->delete()
//                    ->from('app_lorry')
//                    ->where('phone', '=', $tel);
//                $affectedRows = $deleteStatement->execute();
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('phone','=',$tel)
                    ->where('exist','=',1);
                $stmt=$selectStament->execute();
                $data1a=$stmt->fetch();
                if($data1a){
                    $deleteStatement = $database->delete()
                        ->from('app_lorry')
                        ->where('id', '=', $data1a['id']);
                    $affectedRows = $deleteStatement->execute();
                    $selectStament=$database->select()
                        ->from('app_lorry')
                        ->orderBy('id','desc')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $insertStatement = $database->insert(array('app_lorry_id','phone','password','exist'))
                        ->into('app_lorry')
                        ->values(array($data2['id']+1,$tel,$password,1));
                    $insertId = $insertStatement->execute(false);
                    echo  json_encode(array("result"=>"0","desc"=>"success",'lorryid'=>$data2['id']+1));
                }else{
                    $selectStament=$database->select()
                        ->from('app_lorry')
                        ->orderBy('id','desc')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $insertStatement = $database->insert(array('app_lorry_id','phone','password','exist'))
                        ->into('app_lorry')
                        ->values(array($data2['id']+1,$tel,$password,1));
                    $insertId = $insertStatement->execute(false);
                    echo  json_encode(array("result"=>"0","desc"=>"",'lorryid'=>$data2['id']+1));
                }

            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"密码不能为空"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"您未填写电话号码"));
    }
});

$app->get("/schedule_on",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $scheduling_id = $app->request->get("scheduling_id");
    if($scheduling_id!=null||$scheduling_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('scheduling_status','=',4)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1){
            echo json_encode(array('result' => '0', 'desc' => ''));
        }else{
            echo json_encode(array('result' => '1', 'desc' => ''));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '缺少司机id'));
    }
});

//app司机注册2
$app->post('/addlorry1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $idcard=$body->idcard;
    $lorryid=$body->lorryid;
    if($name!=null||$name!=""){
        if($idcard!=null||$idcard!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $arrays['name']=$name;
                $arrays['id_number']=$idcard;
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo  json_encode(array("result"=>"0","desc"=>"","lorryid"=>$lorryid));
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"您未填写电话和密码"));
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
        echo json_encode(array('result' => '0', 'desc' => 'success','lorry_type'=>$data));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未有车辆类型数据'));
    }
});

//获取车辆吨位列表
$app->get('/lorry_weight',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament = $database->select()
        ->from('lorry_load');
    $stmt = $selectStament->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        echo json_encode(array('result' => '0', 'desc' => 'success','vehiche_weight'=>$data));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未有车辆类型数据'));
    }
});


//获取车辆长度列表
$app->get('/lorry_long',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament = $database->select()
        ->from('lorry_length');
    $stmt = $selectStament->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        echo json_encode(array('result' => '0', 'desc' => 'success','vehiche_long'=>$data));
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
    $body=json_decode($body);
    $type=$body->type;
    $long=$body->long;
    $ctype=$body->ctype;
    $cweight=$body->cweight;
    $lorryid=$body->lorryid;
    $plate_number=$body->plate_number;
    if($lorryid!=null||$lorryid!="") {
        if ($type == 0) {
            if ($long != null || $long != "") {
                if ($ctype != null || $ctype != "") {
                    if($plate_number!=null||$plate_number!=""){
                        $selectStament = $database->select()
                            ->from('app_lorry')
                            ->where('app_lorry_id', '=', $lorryid);
                        $stmt = $selectStament->execute();
                        $data1 = $stmt->fetch();
                        if ($data1 != null) {
                            $arrays['flag'] = $type;
                            $arrays['type'] = $ctype;
                            $arrays['length'] = $long;
                            $arrays['plate_number']=$plate_number;
                            $arrays['deadweight']=$cweight;
                            $updateStatement = $database->update($arrays)
                                ->table('app_lorry')
                                ->where('app_lorry_id', '=', $lorryid);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '', 'lorryid' => $lorryid));
                        } else {
                            echo json_encode(array('result' => '4', 'desc' => '未填写电话号码'));
                        }
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '车牌号不能为空'));
                    }
                } else {
                    echo json_encode(array('result' => '3', 'desc' => '未选择车辆类型'));
                }
            } else {
                echo json_encode(array('result' => '2', 'desc' => '未填写车辆长度'));
            }
        } else {
            $arrays['exist'] = 0;
            $updateStatement = $database->update($arrays)
                ->table('app_lorry')
                ->where('app_lorry_id', '=', $lorryid);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '注册成功', 'lorryid' => ''));
        }
    }else{
        echo json_encode(array('result' => '5', 'desc' => '未填写电话号码'));
    }
});

//司机注册4(图片1)
$app->post('/addlorry3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $pic1=$body->pic1;
    $pic2=$body->pic2;
    $pic3=$body->pic3;
    $pic4=$body->pic4;
    $type=$body->type;
    $lujing1=null;
    $lujing2=null;
    $lujing3=null;
    $lujing4=null;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = $file_url."lorry/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driver_license_fp']=$lujing1;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing2 = $file_url."lorry2/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driver_license_tp']=$lujing2;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing3 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driving_license_fp']=$lujing3;
            if($pic4!=null) {
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
                    $new_file = $new_file . $time1 . ".{$type}";
                    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                        $lujing4 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                    }
                }
            }
            $arrays['driving_license_tp']=$lujing4;

            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $arrays['exist']=0;
                $arrays['flag']=$type;
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'','lorryid'=>$lorryid,'phone'=>$data1['phone']));
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
$app->post('/addlorry4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_fp']=$lujing3;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing2 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driving_license_tp']=$lujing4;
            $arrays['exist']=0;
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
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

//司机注册6（图片3）
$app->post('/addlorry5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $id_pic5=$body->id_pic5;
    $id_pic6=$body->id_pic6;
    $type_a=$body->type;
    $lujing5=null;
    $lujing6=null;
    if($id_pic5!=null){
        $base64_image_content = $id_pic5;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/idcard5/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing5 = $file_url."idcard5/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['identity_card_z']=$lujing5;
        if($id_pic6!=null){
            $base64_image_content = $id_pic6;
//匹配出图片的格式
            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type = $result[2];
                date_default_timezone_set("PRC");
                $time1 = time();
                $new_file = "/files/idcard6/" . date('Ymd', $time1) . "/";
                if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing6 = $file_url."idcard6/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['identity_card_f']=$lujing6;
            $arrays['exist']=1;
            if($type_a==1){
                $arrays['exist']=0;
            }
            $arrays['flag']=$type_a;
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'','lorryid'=>$lorryid,'phone'=>$data1['phone']));
            }else {
                echo json_encode(array('result' => '4', 'desc' => '车辆信息有误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有驾驶证反面图片'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有驾驶证正面图片'));
    }
});


//司机登录
$app->post('/lorrysign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $username=$body->tel;
    $password1=$body->password;
    $str1=str_split($password1,2);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $a=strlen($password1);
        $password.=$str1[$x].$a;
        $a=$a-1;
    }
    if($username!=null||$username!=""){
        if($password!=null||$password!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('phone','=',$username)
                ->orderBy('time','DESC')
                ->limit(1);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                if($data1['password']==$password){
                    $time1=time();
                    $arrays['time']=$time1;
                    $updateStatement = $database->update($arrays)
                        ->table('app_lorry')
                        ->where('exist','=',0)
                        ->where('app_lorry_id','=',$data1['app_lorry_id']);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result' => '0', 'desc' => '登录成功','lorry'=>$data1,'time'=>$time1));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '密码错误'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您尚未注册'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '密码不能为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有电话号码'));
    }
});



//判断多次登录
$app->post('/check',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $time=$body->time;
    if($lorryid!=null||$lorryid!=""){
        if($time!=null||$time!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                if($data1['time']==$time){
                    echo json_encode(array('result' => '0', 'desc' => ''));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '您已经在其他地方登录该账号，请重新登录'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有登录时间'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});




//app我的资料
$app->post('/persoonmessage',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            echo json_encode(array('result' => '0', 'desc' => '','lorry'=>$data1));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机的编号'));
    }
});

//app修改行驶证
$app->post('/updriveringpic',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $type=$body->type;
//	$platenumber=$body->platenumber;
    $clong=$body->clong;
    $ctype=$body->ctype;
    $cweight=$body->cweight;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_fp']=$lujing3;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing4 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driving_license_tp']=$lujing4;
//            $arrays['plate_number']=$platenumber;
            $arrays['flag']=$type;
            $arrays['length']=$clong;
            $arrays['type']=$ctype;
            $arrays['deadweight']=$cweight;
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'修改行驶证成功'));
            }else {
                echo json_encode(array('result' => '4', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有行驶证反面图片'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有行驶证正面图片'));
    }
});

//app修改驾驶证
$app->post('/updriverpic',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = $file_url."lorry/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driver_license_fp']=$lujing1;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing2 = $file_url."lorry2/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driver_license_tp']=$lujing2;
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'修改驾驶证成功'));
            }else {
                echo json_encode(array('result' => '4', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有驾驶证反面图片'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有驾驶证正面图片'));
    }
});
//个人名片修改
$app->post('/uphead',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $pic1=$body->pic1;
    $introduce=$body->introduction;
    if($pic1!=null){
        $base64_image_content = $pic1;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/lorryhead/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = $file_url."lorryhead/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['head_img']=$lujing1;
    }
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $arrays['route']=$introduce;
            $updateStatement = $database->update($arrays)
                ->table('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '名片已保存'));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//经营范围修改
$app->post('/upintro',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $introduce=$body->introduction;
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $arrays['route']=$introduce;
            $updateStatement = $database->update($arrays)
                ->table('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '名片已保存'));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

$app->get('/wait_schedulings',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id = $app->request->get("lorry_id");
    $arrays=array();
//    $arraysa=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','!=','0')
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('scheduling_status','=',1)
                        ->whereIn('is_alter',array(0,3))
                        ->where('tenant_id','=',$data2[$x]['tenant_id'])
                        ->where('lorry_id','=',$data2[$x]['lorry_id'])
                        ->orderBy('change_datetime','desc');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();

                    for($i=0;$i<count($data3);$i++){
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data3[$i]['tenant_id'])
                            ->where('customer_id','=',$data3[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $arrays1['sure_image']=$data3[$i]['sure_img'];
                        $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                        $arrays1['customer_name']=$data4['customer_name'];
                        $arrays1['customer_phone']=$data4['customer_phone'];
                        $arrays1['scheduling_status']=$data3[$i]['scheduling_status'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data4['customer_city_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $arrays1['address']=$data4['customer_address'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['send_city_id']);
                        $stmt=$selectStament->execute();
                        $data6=$stmt->fetch();
                        $arrays1['sendcity']=$data6['name'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['receive_city_id']);
                        $stmt=$selectStament->execute();
                        $data7=$stmt->fetch();
                        $arrays1['receivecity']=$data7['name'];
                        array_push($arrays,$arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$arrays));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '暂无数据'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//历史清单列表
$app->get('/schistory',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id = $app->request->get("lorry_id");
    $arrays=array();
//    $arraysa=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','!=','0')
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->whereIn('scheduling_status',array(5,7,8,9))
                        ->where('tenant_id','=',$data2[$x]['tenant_id'])
                        ->where('lorry_id','=',$data2[$x]['lorry_id'])
                        ->orderBy('change_datetime','desc');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();

                    for($i=0;$i<count($data3);$i++){
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data3[$i]['tenant_id'])
                            ->where('customer_id','=',$data3[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $arrays1['sure_image']=$data3[$i]['sure_img'];
                        $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                        $arrays1['customer_name']=$data4['customer_name'];
                        $arrays1['customer_phone']=$data4['customer_phone'];
                        $arrays1['scheduling_status']=$data3[$i]['scheduling_status'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data4['customer_city_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $arrays1['address']=$data4['customer_address'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['send_city_id']);
                        $stmt=$selectStament->execute();
                        $data6=$stmt->fetch();
                        $arrays1['sendcity']=$data6['name'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['receive_city_id']);
                        $stmt=$selectStament->execute();
                        $data7=$stmt->fetch();
                        $arrays1['receivecity']=$data7['name'];
                        array_push($arrays,$arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$arrays));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '暂无数据'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//带交付清单列表
$app->get('/scnoaccept',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id = $app->request->get("lorry_id");
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','!=','0')
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('scheduling_status','!=',1)
                        ->where('scheduling_status','!=',5)
//                        ->where('scheduling_status','!=',6)
                        ->where('scheduling_status','!=',8)
                        ->where('scheduling_status','!=',7)
                        ->where('scheduling_status','!=',9)
                        ->where('tenant_id','=',$data2[$x]['tenant_id'])
                        ->where('lorry_id','=',$data2[$x]['lorry_id'])
                        ->orderBy('change_datetime','desc');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    for($i=0;$i<count($data3);$i++){
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data3[$i]['tenant_id'])
                            ->where('customer_id','=',$data3[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                        $arrays1['customer_name']=$data4['customer_name'];
                        $arrays1['customer_phone']=$data4['customer_phone'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data4['customer_city_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $arrays1['address']=$data4['customer_address'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['send_city_id']);
                        $stmt=$selectStament->execute();
                        $data6=$stmt->fetch();
                        $arrays1['sendcity']=$data6['name'];
                        $selectStament=$database->select()
                            ->from('city')
                            ->where('id','=',$data3[$i]['receive_city_id']);
                        $stmt=$selectStament->execute();
                        $data7=$stmt->fetch();
                        $arrays1['receivecity']=$data7['name'];
                        array_push($arrays,$arrays1);
                    }

                }
                echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$arrays));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '暂无数据'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '您还不是运输车辆'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//根据清单号查看清单信息
$app->get('/sandoandg',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $schedule_id = $app->request->get("schedule_id");
    $database=localhost();
    $arrays=array();
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null) {
            $selectStament = $database->select()
                ->from('schedule_order')
                ->where('tenant_id', '=', $data1['tenant_id'])
                ->where('exist', '=', 0)
                ->where('schedule_id', '=', $schedule_id);
            $stmt = $selectStament->execute();
            $data4 = $stmt->fetchAll();
            for ($x = 0; $x < count($data4); $x++) {
                $selectStament = $database->select()
                    ->from('goods')
                    ->where('exist', '=', 0)
                    ->where('order_id', '=', $data4[$x]['order_id']);
                $stmt = $selectStament->execute();
                $data5 = $stmt->fetch();
                $arrays1['order_id'] = $data4[$x]['order_id'];
                $arrays1['goods_name'] = $data5['goods_name'];
                if($data5['goods_count']){
                    $arrays1['goods_count'] = $data5['goods_count'];
                }else{
                    $arrays1['goods_count'] = '—';
                }
                if($data5['goods_capacity']){
                    $arrays1['goods_capacity'] = $data5['goods_capacity'];
                }else{
                    $arrays1['goods_capacity'] = '—';
                }
                $arrays1['goods_weight'] = $data5['goods_weight'];
                $selectStament = $database->select()
                    ->from('goods_package')
                    ->where('goods_package_id', '=', $data5['goods_package_id']);
                $stmt = $selectStament->execute();
                $data6 = $stmt->fetch();
                $arrays1['goods_package'] = $data6['goods_package'];
                array_push($arrays, $arrays1);
            }
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data1['send_city_id']);
            $stmt=$selectStament->execute();
            $data7=$stmt->fetch();
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data1['receive_city_id']);
            $stmt=$selectStament->execute();
            $data8=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$data1['tenant_id'])
                ->where('customer_id','=',$data1['receiver_id']);
            $stmt=$selectStament->execute();
            $data9=$stmt->fetch();
            $arrays2['name']=$data9['customer_name'];
            $arrays2['phone']=$data9['customer_phone'];
            $arrays2['sendcity']=$data7['name'];
            $arrays2['receivecity']=$data8['name'];
            $arrays2['receiveraddress']=$data9['customer_address'];
            $selectStament = $database->select()
                ->from('tenant')
                ->where('tenant_id', '=', $data1['sure_img']);
            $stmt = $selectStament->execute();
            $data10 = $stmt->fetch();
            if ($data10 != null) {
                $arrays2['pic'] = $data10['jcompany'] . '--收';
            } else {
                $arrays2['pic'] = $data1['sure_img'];
            }
            echo json_encode(array('result' => '0', 'desc' => '', 'goods' => $arrays,'schedule'=>$arrays2, 'isreceive' => $data1['scheduling_status']));

        }else{
            echo json_encode(array('result' => '4', 'desc' => '该清单不存在','goods'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空','goods'=>''));
    }
});
//获取待交付清单数量
$app->get('/tongji',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $num=0;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($i=0;$i<count($data2);$i++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','!=',1)
                        ->where('scheduling_status','!=',5)
                        ->where('scheduling_status','!=',7)
                        ->where('scheduling_status','!=',8)
                        ->where('scheduling_status','!=',9)
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                        ->where('lorry_id','=',$data2[$i]['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    if($data3!=null){
                        $num+=count($data3);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '','count'=>$num));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您还没拉过清单'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//上传清单地理位置
$app->post('/scmap',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    $time=time();
    if($longitude!=null||$longitude!=""||$latitude!=null||$latitude!=""){
        if($lorryid!=null||$lorryid!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $selectStament=$database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('tenant_id','!=',0)
                    ->where('plate_number','=',$data1['plate_number'])
                    ->where('driver_phone','=',$data1['phone']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetchAll();
                if($data2!=null){
                    for($i=0;$i<count($data2);$i++){
                        $selectStament=$database->select()
                            ->from('scheduling')
                            ->where('exist','=',0)
                            ->where('scheduling_status','!=',1)
                            ->where('scheduling_status','!=',5)
                            ->where('scheduling_status','!=',6)
                            ->where('scheduling_status','!=',7)
                            ->where('scheduling_status','!=',8)
                            ->where('scheduling_status','!=',9)
                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
                            ->where('lorry_id','=',$data2[$i]['lorry_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetchAll();
                        if($data3!=null){
                            for ($y = 0; $y < count($data3); $y++) {
                                $selectStament=$database->select()
                                    ->from('map')
                                    ->where('scheduling_id','=',$data3[$y]['scheduling_id'])
                                    ->orderBy('accept_time');
                                $stmt=$selectStament->execute();
                                $data4=$stmt->fetchAll();
                                if ($data4 != null) {
//                                    if($data4[count($data4)-1]['longitude']==$longitude&&$data4[count($data4)-1]['latitude']==$latitude) {
//                                        $arrays['accept_time'] = $time;
//                                        $updateStatement = $database->update($arrays)
//                                            ->table('map')
//                                            ->where('id', '=', $data4[count($data4)-1]['id']);
//                                        $affectedRows = $updateStatement->execute();
//                                    } else {
                                    if ($time - $data4[count($data4) - 1]['accept_time'] > 1200) {
//                                        if ($time - $data4[count($data4) - 1]['accept_time'] > 60) {
                                        $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                            ->into('map')
                                            ->values(array($data3[$y]['scheduling_id'], $longitude, $latitude, $time));
                                        $insertId = $insertStatement->execute(false);
                                    }
//                                    }
                                }else {
                                    $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                        ->into('map')
                                        ->values(array($data3[$y]['scheduling_id'], $longitude, $latitude, $time));
                                    $insertId = $insertStatement->execute(false);
                                }
                            }
                        }
                    }
                    echo json_encode(array('result' => '0', 'desc' => '上传地理位置成功'));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '司机尚未有过订单'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机信息为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '坐标缺少'));
    }
});


$app->get('/check_scheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $scheduling_id= $app->request->get("scheduling_id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    if($scheduling_id!=null||$scheduling_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('app_lorry_id','=',$lorry_id);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data2['plate_number'])
                ->where('driver_phone','=',$data2['phone']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            $a=0;
            for($i=0;$i<count($data3);$i++){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('exist','=',0)
                    ->where('scheduling_status','=',5)
                    ->where('tenant_id','=',$data3[$i]['tenant_id'])
                    ->where('scheduling_id','=',$scheduling_id)
                    ->where('lorry_id','=',$data3[$i]['lorry_id']);
                $stmt=$selectStament->execute();
                $data1=$stmt->fetch();
                if($data1){
                    $a=1;
                    break;
                }
            }

            if($a==1){
                echo json_encode(array('result' => '0', 'desc' => 'success'));
            }else{
                echo json_encode(array('result' => '2', 'desc' => 'success'));
            }
        }else{
            echo json_encode(array('result' => '3', 'desc' => '缺少司机id'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少调度id'));
    }
});


//清单确认拉货
$app->post('/suresc1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorryid;
    $arrays['scheduling_status']=2;
    $arrays['change_datetime']=time();
    date_default_timezone_set("PRC");
    $arrays1['order_datetime2']=date("Y-m-d H:i:s",time());
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('exist','=',0)
                ->where('scheduling_status','=',1)
                ->where('scheduling_id','=',$schedule_id);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            if($data3!=null){
                $selectStament=$database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('tenant_id','=',$data3['tenant_id'])
                    ->where('lorry_id','=',$data3['lorry_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                if($data4!=null){
                    if($data1['phone']==$data4['driver_phone']&&$data1['plate_number']==$data4['plate_number']){
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        if($affectedRows>0){
                            echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                        }else{
                            echo json_encode(array('result' => '6', 'desc' => '该清单不是您的'));
                        }

                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '该清单不是您的'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '该清单上的司机不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '清单不存在或已经被确认'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//签字签收
$app->post('/receivesc',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $pic=$body->pic;
    $is_wuliu=$body->is_wuliu;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1=time();
        $new_file = "/files/sure/".date('Ymd',$time1)."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.$time1.".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing=$file_url."sure/".date('Ymd',$time1)."/".$time1.".{$type}";
        }
    }
    if($is_wuliu==2){
        $arrays['scheduling_status']=5;
//    $arrays['is_contract']=0;
//    $arrays['is_insurance']=0;
        $arrays['sure_img']=$lujing;
        $arrays['change_datetime']=time();
        date_default_timezone_set("PRC");
        $arrays1['sure_img']=$lujing;
        $arrays1['order_status']=7;
        $arrays1['order_datetime4']=date("Y-m-d H:i:s",time());
        $arrays1['order_datetime5']=date("Y-m-d H:i:s",time());
        if($lorry_id!=null||$lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('app_lorry_id','=',$lorry_id);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('exist','=',0)
                    ->where('scheduling_status','=',4)
                    ->where('scheduling_id','=',$schedule_id);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();

                if(substr($data7['name'],((strlen($data7['name']))-3),3)=='市'){
                    $arrays1['reach_city']=substr($data7['name'],0,((strlen($data7['name']))-3));
                }else{
                    $arrays1['reach_city']=$data7['name'];
                }
                if($data3!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$data3['tenant_id'])
                        ->where('lorry_id','=',$data3['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    if($data4!=null){
                        if($data1['phone']==$data4['driver_phone']&&$data1['plate_number']==$data4['plate_number']){
                            $updateStatement = $database->update($arrays)
                                ->table('scheduling')
                                ->where('scheduling_id', '=', $schedule_id);
                            $affectedRows = $updateStatement->execute();
                            $selectStament=$database->select()
                                ->from('schedule_order')
                                ->where('exist','=',0)
                                ->where('schedule_id','=',$schedule_id);
                            $stmt=$selectStament->execute();
                            $data3=$stmt->fetchAll();
                            for($x=0;$x<count($data3);$x++) {
                                $updateStatement = $database->update($arrays1)
                                    ->table('orders')
                                    ->where('tenant_id','=',$data3[$x]['tenant_id'])
                                    ->where('order_id','=',$data3[$x]['order_id']);
                                $affectedRows = $updateStatement->execute();
                                $updateStatement = $database->update(array('order_status'=>7,'sure_img'=>$lujing))
                                    ->table('orders')
                                    ->where('tenant_id','!=',$data3[$x]['tenant_id'])
                                    ->where('order_id','=',$data3[$x]['order_id']);
                                $affectedRows = $updateStatement->execute();
                                echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                            }
                        }else{
                            echo json_encode(array('result' => '5', 'desc' => '该清单不是您的'));
                        }
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '该清单上的司机不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '清单已被接收或不存在'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
        }
    }else{
        $arrays['scheduling_status']=5;
//    $arrays['is_contract']=0;
//    $arrays['is_insurance']=0;
        $arrays['sure_img']=$lujing;
        $arrays['change_datetime']=time();
        date_default_timezone_set("PRC");
        $arrays1['sure_img']=$lujing;
        $arrays1['order_status']=4;
        $arrays1['order_datetime4']=date("Y-m-d H:i:s",time());
        $arrays1['is_sign']=1;
//        $arrays1['order_datetime5']=date("Y-m-d H:i:s",time());
        if($lorry_id!=null||$lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('app_lorry_id','=',$lorry_id);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('exist','=',0)
                    ->where('scheduling_status','=',4)
                    ->where('scheduling_id','=',$schedule_id);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();

                if(substr($data7['name'],((strlen($data7['name']))-3),3)=='市'){
                    $arrays1['reach_city']=substr($data7['name'],0,((strlen($data7['name']))-3));
                }else{
                    $arrays1['reach_city']=$data7['name'];
                }
                if($data3!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$data3['tenant_id'])
                        ->where('lorry_id','=',$data3['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    if($data4!=null){
                        if($data1['phone']==$data4['driver_phone']&&$data1['plate_number']==$data4['plate_number']){
                            $updateStatement = $database->update($arrays)
                                ->table('scheduling')
                                ->where('scheduling_id', '=', $schedule_id);
                            $affectedRows = $updateStatement->execute();
                            $selectStament=$database->select()
                                ->from('schedule_order')
                                ->where('exist','=',0)
                                ->where('schedule_id','=',$schedule_id);
                            $stmt=$selectStament->execute();
                            $data3=$stmt->fetchAll();
                            for($x=0;$x<count($data3);$x++) {
                                $updateStatement = $database->update($arrays1)
                                    ->table('orders')
                                    ->where('tenant_id','=',$data3[$x]['tenant_id'])
                                    ->where('order_id','=',$data3[$x]['order_id']);
                                $affectedRows = $updateStatement->execute();
                                $updateStatement = $database->update(array('order_status'=>4,'sure_img'=>$lujing))
                                    ->table('orders')
                                    ->where('tenant_id','!=',$data3[$x]['tenant_id'])
                                    ->where('order_id','=',$data3[$x]['order_id']);
                                $affectedRows = $updateStatement->execute();
                                echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                            }
                        }else{
                            echo json_encode(array('result' => '5', 'desc' => '该清单不是您的'));
                        }
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '该清单上的司机不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '清单已被接收或不存在'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
        }
    }

});


$app->post('/last_map1',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $schedule_id = $body->schedule_id;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    $time=time();
    if($schedule_id!=''||$schedule_id!=null){

        $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
            ->into('map')
            ->values(array($schedule_id, $longitude, $latitude, $time));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array('result' => '0', 'desc' => '上传成功'));

    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号不存在'));
    }
});

$app->post('/last_map',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $schedule_id = $body->schedule_id;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    $time=time();
    if($schedule_id!=''||$schedule_id!=null){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','=',4)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data3=$stmt->fetch();
        if($data3){
            $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                ->into('map')
                ->values(array($schedule_id, $longitude, $latitude, $time));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array('result' => '0', 'desc' => '上传成功'));
        }else {
            $selectStament = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('scheduling_status', '=', 8)
                ->where('scheduling_id', '=', $schedule_id);
            $stmt = $selectStament->execute();
            $data4 = $stmt->fetch();
            if($data4){
                echo json_encode(array('result' => '2', 'desc' => '调度单出险中'));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '调度单退单中'));
            }
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号不存在'));
    }
});

$app->post('/changpass',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $idcard=$body->idcard;
    $telephone=$body->telephone;
    $password1=$body->password;
    $str1=str_split($password1,2);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $a=strlen($password1);
        $password.=$str1[$x].$a;
        $a=$a-1;
    }
    if($name!=null||$name!=""){
        if($idcard!=null||$idcard!=""){
            if($telephone!=null||$telephone!=""){
                if($password!=null||$password!=""){
                    $selectStament=$database->select()
                        ->from('app_lorry')
                        ->where('exist','=',0)
                        ->where('name','=',$name)
                        ->where('id_number','=',$idcard)
                        ->where('phone','=',$telephone);
                    $stmt=$selectStament->execute();
                    $data1=$stmt->fetch();
                    if($data1!=null){
                        $updateStatement = $database->update(array('password'=>$password))
                            ->table('app_lorry')
                            ->where('exist','=',0)
                            ->where('phone','=',$telephone);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '修改成功'));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '您尚未注册或填写信息有误'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '您没有填写新的密码'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您没有填写电话号码'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '您没有填写身份证'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '您没有填写姓名'));
    }
});

//验证用户信息
$app->post('/match_user',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $idcard=$body->idcard;
    $telephone=$body->mobile;
    if($name!=null||$name!=""){
        if($idcard!=null||$idcard!=""){
            if($telephone!=null||$telephone!=""){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('name','=',$name)
                    ->where('id_number','=',$idcard)
                    ->where('phone','=',$telephone);
                $stmt=$selectStament->execute();
                $data1=$stmt->fetch();
                if($data1){
                    echo json_encode(array('result' => '0', 'desc' => 'success'));
                }else{
                    echo json_encode(array('result' => '1', 'desc' => '用户不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您没有填写电话号码'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '您没有填写身份证'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '您没有填写姓名'));
    }
});

$app->get('/getOrderCosts',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $scheduling_id = $app->request->get("scheduling_id");

    $selectStament=$database->select()
        ->from('schedule_order')
        ->where('schedule_order.exist','=',0)
        ->where('schedule_order.schedule_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data2=$stmt->fetch();
    $selectStament=$database->select()
        ->from('schedule_order')
        ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
        ->where('orders.tenant_id','=',$data2['tenant_id'])
        ->where('orders.pay_method','=',1)
        ->where('schedule_order.exist','=',0)
        ->where('schedule_order.schedule_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data1=$stmt->fetchAll();
    if($data1){
        echo json_encode(array('result' => '0', 'desc' => 'success','orderCosts'=>$data1));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '无到付单'));
    }

});


$app->post('/t_change_password',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $password_o=$body->password_o;
    $password_n=$body->password_n;
    $str1=str_split($password_o,2);
    $str2=str_split($password_n,2);
    $password1=null;
    $password2=null;
    for ($x=0;$x<count($str1);$x++){
        $a=strlen($password_o);
        $password1.=$str1[$x].$a;
        $a=$a-1;
    }
    for ($x=0;$x<count($str2);$x++){
        $a=strlen($password_n);
        $password2.=$str2[$x].$a;
        $a=$a-1;
    }
    if($lorry_id!=null||$lorry_id!=""){
        if($password1!=null||$password1!=""){
            if($password2!=null||$password2!=""){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('app_lorry_id', '=', $lorry_id);
                $stmt=$selectStament->execute();
                $data1=$stmt->fetch();
                $updateStatement = $database->update(array('password'=>$password2))
                    ->table('app_lorry')
                    ->where('password', '=', $password1)
//                    ->where('exist','=',0)
                    ->where('phone','=',$data1['phone']);
                $affectedRows = $updateStatement->execute();
                if($affectedRows>0){
                    echo json_encode(array('result' => '0', 'desc' => '修改成功'));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '旧密码有误，修改未成功'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您没有填写新密码'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '您没有填写旧密码'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '您没有填写车辆id'));
    }
});

$app->post('/search_back_scheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    if($scheduling_id!=null||$scheduling_id!=''){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','=',6)
            ->where('scheduling_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            echo json_encode(array('result' => '0', 'desc' => '该调度单退单审核中'));
        }else{
            echo json_encode(array('result' => '1', 'desc' => ''));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => ''));
    }
});

$app->post('/change_orders_status',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $scheduling_id=$body->scheduling_id;
    $back_comment=$body->reason;
    $time=time();
    $selectStament=$database->select()
        ->from('scheduling')
        ->where('exist','=',0)
        ->where('scheduling_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $dataa=$stmt->fetch();
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','=',$dataa['tenant_id'])
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $aaa=0;
            if($data2!=null){
//                    for($i=0;$i<count($data2);$i++){
//                        $selectStament=$database->select()
//                            ->from('scheduling')
//                            ->where('exist','=',0)
//                            ->where('scheduling_status','=',4)
//                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
//                            ->where('scheduling_id','=',$scheduling_id)
//                            ->where('lorry_id','=',$data2[$i]['lorry_id']);
//                        $stmt=$selectStament->execute();
//                        $data3=$stmt->fetch();
                date_default_timezone_set("PRC");
                $shijian=date("Y-m-d H:i:s",time());
                $updateStatement = $database->update(array('scheduling_status'=>6,'back_reason'=> '驾驶员申请退单','back_time'=>$shijian,'back_comment'=>$back_comment,))
                    ->table('scheduling')
                    ->where('exist','=',0)
                    ->where('scheduling_status','=',4)
                    ->where('tenant_id','=',$data2['tenant_id'])
                    ->where('scheduling_id','=',$scheduling_id)
                    ->where('lorry_id','=',$data2['lorry_id']);
                $affectedRows = $updateStatement->execute();
                if($affectedRows>0){
                    $selectStament=$database->select()
                        ->from('schedule_order')
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$data2['tenant_id'])
                        ->where('schedule_id','=',$scheduling_id);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetchAll();
                    if($data4!=null){
                        for ($y = 0; $y < count($data4); $y++) {
                            $updateStatement = $database->update(array('is_back'=>1))
                                ->table('orders')
                                ->where('tenant_id','=',$data2['tenant_id'])
                                ->where('order_id','=',$data4[$y]['order_id'])
                                ->where('exist',"=","0");
                            $affectedRows = $updateStatement->execute();
//                                    date_default_timezone_set("PRC");
//                                    $time=time();
//                                    $date=date("Y-m-d h:i:sa", $time);
//                                    $selectStatement = $database->select()
//                                        ->from('exception')
//                                        ->where('tenant_id', '=', $data2[$i]['tenant_id']);
//                                    $stmt = $selectStatement->execute();
//                                    $data5 = $stmt->fetchAll();
//                                    $insertStatement = $database->insert(array('exception_source','exception_person','exception_time','tenant_id','order_id','exception_id'))
//                                        ->into('exception')
//                                        ->values(array_values(array('交付帮手',$data1['name'].'('.$data1['plate_number'].')',$date,$data2[$i]['tenant_id'],$data4[$y]['order_id'],(count($data5)+100000001))));
//                                    $insertId = $insertStatement->execute(false);
//                                    $updateStatement = $database->update(array('exception_id'=>(count($data5)+100000001),'order_status'=>5,'is_back'=>1))
//                                        ->table('orders')
//                                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
//                                        ->where('order_id','=',$data4[$y]['order_id'])
//                                        ->where('exist',"=","0");
//                                    $affectedRows = $updateStatement->execute();
                        }
                    }
                    $aaa++;
                }else{
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->whereIn('scheduling_status',array(2,3))
                        ->where('tenant_id','=',$data2['tenant_id'])
                        ->where('scheduling_id','=',$scheduling_id)
                        ->where('lorry_id','=',$data2['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','=',6)
                        ->where('tenant_id','=',$data2['tenant_id'])
                        ->where('scheduling_id','=',$scheduling_id)
                        ->where('lorry_id','=',$data2['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data6=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','=',8)
                        ->where('tenant_id','=',$data2['tenant_id'])
                        ->where('scheduling_id','=',$scheduling_id)
                        ->where('lorry_id','=',$data2['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data7=$stmt->fetch();
                }

//                    }
                if($aaa!=0){
                    echo json_encode(array('result' => '0', 'desc' => '退单中，待审核'));
                }else{
                    if($data5){
                        echo json_encode(array('result' => '6', 'desc' => '物流公司尚未发车，无法退单'));
                    }else{
                        if($data6){
                            echo json_encode(array('result' => '5', 'desc' => '该调度单退单审核中'));
                        }else{
                            if($data7){
                                echo json_encode(array('result' => '7', 'desc' => '该调度单出险中'));
                            }
                        }

                    }

                }
            }else{
                echo json_encode(array('result' => '4', 'desc' => '该车辆不属于本公司司机'));
            }
        }else{
            echo json_encode(array('result' => '3', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '司机id为空'));
    }
});

$app->post('/change_orders_status2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
//    $scheduling_id=$body->scheduling_id;
//    $time=time();
    $aaa=0;
    $aab=0;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($i=0;$i<count($data2);$i++){
//                        $selectStament=$database->select()
//                            ->from('scheduling')
//                            ->where('exist','=',0)
//                            ->where('scheduling_status','=',4)
//                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
//                            ->where('scheduling_id','=',$scheduling_id)
//                            ->where('lorry_id','=',$data2[$i]['lorry_id']);
//                        $stmt=$selectStament->execute();
//                        $data3=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','=',4)
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                        ->where('lorry_id','=',$data2[$i]['lorry_id']);
                    $stmt=$selectStament->execute();
                    $datad=$stmt->fetchAll();
                    for($g=0;$g<count($datad);$g++){
                        $selectStament=$database->select()
                            ->from('agreement_schedule')
                            ->where('exist','=',0)
                            ->where('tenant_id','=',$datad[$g]['tenant_id'])
                            ->where('scheduling_id','=',$datad[$g]['scheduling_id']);
                        $stmt=$selectStament->execute();
                        $datada=$stmt->fetch();
                        $updateStatement = $database->update(array('agreement_status'=>3))
                            ->table('agreement')
                            ->where('exist','=',0)
                            ->where('tenant_id','=',$datad[$g]['tenant_id'])
//                            ->where('scheduling_id','=',$scheduling_id)
                            ->where('agreement_id','=',$datada['agreement_id']);
                        $affectedRows = $updateStatement->execute();
                        if($affectedRows==0){
                            $updateStatement = $database->update(array('is_contract'=>0))
                                ->table('scheduling')
                                ->where('exist','=',0)
                                ->where('tenant_id','=',$datad[$g]['tenant_id'])
//                            ->where('scheduling_id','=',$scheduling_id)
                                ->where('scheduling_id','=',$datad[$g]['scheduling_id']);
                            $affectedRows = $updateStatement->execute();
                        }
                    }
                    date_default_timezone_set("PRC");
                    $shijian=date("Y-m-d H:i:s",time());
                    $updateStatement = $database->update(array('scheduling_status'=>8,'back_time'=>$shijian,'back_reason'=>'事故出险',))
                        ->table('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','=',4)
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
//                            ->where('scheduling_id','=',$scheduling_id)
                        ->where('lorry_id','=',$data2[$i]['lorry_id']);
                    $affectedRows = $updateStatement->execute();
                    if($affectedRows>0&&$datad){
                        $aaa=1;
                        for($x=0;$x<count($datad);$x++){
                            $time=time();
                            $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                ->into('map')
                                ->values(array($datad[$x]['scheduling_id'], $longitude, $latitude, $time));
                            $insertId = $insertStatement->execute(false);
                            $selectStament=$database->select()
                                ->from('schedule_order')
                                ->where('exist','=',0)
                                ->where('tenant_id','=',$data2[$i]['tenant_id'])
                                ->where('schedule_id','=',$datad[$x]['scheduling_id']);
                            $stmt=$selectStament->execute();
                            $data4=$stmt->fetchAll();
                            if($data4!=null){
                                for ($y = 0; $y < count($data4); $y++) {

                                    date_default_timezone_set("PRC");
                                    $time=time();

                                    $date=date("Y-m-d H:i:s", $time);
                                    $selectStatement = $database->select()
                                        ->from('exception')
                                        ->where('tenant_id', '=', $data2[$i]['tenant_id']);
                                    $stmt = $selectStatement->execute();
                                    $data5 = $stmt->fetchAll();
                                    $insertStatement = $database->insert(array('exception_source','exception_person','exception_time','exception_comment','tenant_id','exception_id','order_id'))
                                        ->into('exception')
                                        ->values(array_values(array('交付帮手',$data1['name'].'('.$data1['plate_number'].')',$date,'事故出险',$data2[$i]['tenant_id'],(count($data5)+100000001),$data4[$y]['order_id'])));
                                    $insertId = $insertStatement->execute(false);
                                    $updateStatement = $database->update(array('exception_id'=>(count($data5)+100000001),'order_status'=>5))
                                        ->table('orders')
                                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                                        ->where('order_id','=',$data4[$y]['order_id'])
                                        ->where('exist',"=","0");
                                    $affectedRows = $updateStatement->execute();
                                }
                            }
                        }
                    }
                }
                if($aaa==1){
                    echo json_encode(array('result' => '0', 'desc' => '正在上报物流公司'));
                }else{
                    echo json_encode(array('result' => '1', 'desc' => '无清单可上报或已经上报物流公司，请耐心等待'));
                }

            }else{
                echo json_encode(array('result' => '4', 'desc' => '该车辆不属于本公司司机'));
            }
        }else{
            echo json_encode(array('result' => '3', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '司机id为空'));
    }
});


$app->get('/company_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $scheduling_id = $app->request->get("scheduling_id");

    $selectStament=$database->select()
        ->from('scheduling')
        ->where('exist','=',0)
        ->where('scheduling_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data1=$stmt->fetch();
    $selectStament=$database->select()
        ->from('customer')
        ->whereNotNull('contact_tenant_id')
        ->where('tenant_id','=',$data1['tenant_id'])
        ->where('customer_id','=',$data1['receiver_id']);
    $stmt=$selectStament->execute();
    $data2=$stmt->fetch();
    if($data2){
        echo json_encode(array('result' => '0', 'desc' => 'success'));
    }else{
        echo json_encode(array('result' => '1', 'desc' => ''));
    }

});

$app->get('/agreement_lorrys',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id = $app->request->get("lorry_id");
    $arrays=array();
    $arrays1=array();
    if($lorry_id!=null||$lorry_id!=''){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null) {
            $selectStament = $database->select()
                ->from('lorry')
                ->where('exist', '=', 0)
                ->where('tenant_id', '!=', 0)
                ->where('plate_number', '=', $data1['plate_number'])
                ->where('driver_phone', '=', $data1['phone']);
            $stmt = $selectStament->execute();
            $data2 = $stmt->fetchAll();
            if($data2!=null){
                for($i=0;$i<count($data2);$i++){
                    $selectStatement = $database->select()
                        ->from('agreement')
                        ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
                        ->join('tenant','tenant.tenant_id','=','agreement.tenant_id','INNER')
                        ->where('agreement.exist','=','0')
                        ->where('tenant.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('lorry.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('lorry.lorry_id','=', $data2[$i]['lorry_id'])
                        ->where('agreement.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('agreement.secondparty_id', '=', $data2[$i]['lorry_id'])
                        ->where('agreement.agreement_status', '=',0)
                        ->orderBy('agreement.agreement_id','DESC');
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetchAll();
                    $arrays=array_merge($arrays,$data3);
                    $selectStatement = $database->select()
                        ->from('agreement')
                        ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
                        ->join('tenant','tenant.tenant_id','=','agreement.tenant_id','INNER')
                        ->where('agreement.exist','=','0')
                        ->where('tenant.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('lorry.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('lorry.lorry_id','=', $data2[$i]['lorry_id'])
                        ->where('agreement.tenant_id','=', $data2[$i]['tenant_id'])
                        ->where('agreement.secondparty_id', '=', $data2[$i]['lorry_id'])
                        ->where('agreement.agreement_status', '!=',0)
                        ->orderBy('agreement.agreement_id','DESC');
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetchAll();
                    $arrays1=array_merge($arrays1,$data4);
                }
                $arrays=array_merge($arrays,$arrays1);
                echo json_encode(array('result' => '0','desc' => '','agreements'=>$arrays));
            }else{
                echo json_encode(array('result' => '1', 'desc' => '司机尚未有过订单'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机尚未注册'));
        }
    }else{
        echo json_encode(array('result' => '3', 'desc' => '车辆id不正确'));
    }
});

$app->get('/agreement_scheduling_id',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $data5=array();
    $agreement_id = $app->request->get("agreement_id");
    $selectStatement = $database->select()
        ->from('agreement')
        ->join('tenant','tenant.tenant_id','=','agreement.tenant_id','INNER')
        ->join('city','city.id','=','tenant.from_city_id','INNER')
        ->where('agreement_id','=',$agreement_id);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetch();
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=', $data1['tenant_id'])
        ->where('lorry_id','=',$data1['secondparty_id']);
    $stmt = $selectStatement->execute();
    $data4= $stmt->fetch();
    $data1['lorry_name']=$data4['driver_name'];
    $selectStatement = $database->select()
        ->from('agreement')
        ->join('agreement_schedule','agreement_schedule.agreement_id','=','agreement.agreement_id','INNER')
        ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
        ->join('tenant','tenant.tenant_id','=','agreement.tenant_id','INNER')
        ->where('agreement.exist','=','0')
        ->where('tenant.tenant_id','=', $data1['tenant_id'])
        ->where('lorry.tenant_id','=', $data1['tenant_id'])
        ->where('lorry.lorry_id','=', $data1['secondparty_id'])
        ->where('agreement.tenant_id','=', $data1['tenant_id'])
        ->where('agreement_schedule.agreement_id','=',$agreement_id)
        ->where('agreement_schedule.tenant_id','=', $data1['tenant_id'])
        ->where('agreement.secondparty_id', '=', $data1['secondparty_id']);
    $stmt = $selectStatement->execute();
    $data2= $stmt->fetchAll();
    for($i=0;$i<count($data2);$i++){
        $selectStatement = $database->select()
            ->sum('goods.goods_weight','weight_zon')
            ->sum('goods.goods_capacity','capacity_zon')
            ->sum('goods.goods_count','count_zon')
            ->from('schedule_order')
            ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->where('schedule_order.schedule_id','=',$data2[$i]['scheduling_id'])
            ->where('schedule_order.tenant_id', '=', $data2[$i]['tenant_id'])
            ->where('goods.tenant_id', '=', $data2[$i]['tenant_id'])
            ->where('orders.tenant_id', '=',$data2[$i]['tenant_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('tenant_id', '=', $data2[$i]['tenant_id'])
            ->where('scheduling_id','=',$data2[$i]['scheduling_id']);
        $stmt = $selectStatement->execute();
        $data6 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id','=',$data6['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        if($data4['name']!=$data2[$i]['rcity']){
            array_push($data5,$data4['name']);
        }
        $data2[$i]['weight_zon']=$data3['weight_zon'];
        if($data3['capacity_zon']){
            $data2[$i]['capacity_zon']=$data3['capacity_zon'];
        }else{
            $data2[$i]['capacity_zon']='—';
        }
        if($data3['count_zon']){
            $data2[$i]['count_zon']=$data3['count_zon'];
        }else{
            $data2[$i]['count_zon']='—';
        }
    }
    $data5=array_unique($data5);
    for($x=0;$x<count($data5);$x++){
        if($x!=0){
            $data1['receive_tj_citys'].=','.$data5[$x];
        }else{
            $data1['receive_tj_citys']=$data5[$x];
        }

    }
    echo json_encode(array('result' => '0', 'desc' => '','agreement_schedulings'=>$data2,'agreement'=>$data1,'data'=>$data5));
});

$app->get('/agreement_status',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();

    $agreement_id = $app->request->get("agreement_id");
    $selectStatement = $database->select()
        ->from('agreement')
        ->where('agreement_id','=',$agreement_id);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetch();
    echo json_encode(array('result' => '0', 'desc' => '','agreement'=>$data1));
});

$app->post('/sign_agreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorryid;
    $agreement_id=$body->agreement_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1=time();
        $new_file = "/files/agreement_sure/".date('Ymd',$time1)."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.$time1.".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing=$file_url."agreement_sure/".date('Ymd',$time1)."/".$time1.".{$type}";
        }
    }
    if($lorry_id!=null||$lorry_id!=''){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament = $database->select()
                ->from('lorry')
                ->where('exist', '=', 0)
                ->where('tenant_id', '!=', 0)
                ->where('plate_number', '=', $data1['plate_number'])
                ->where('driver_name', '=', $data1['name'])
                ->where('driver_phone', '=', $data1['phone']);
            $stmt = $selectStament->execute();
            $data2 = $stmt->fetchAll();
            for($i=0;$i<count($data2);$i++){
//                $selectStatement = $database->select()
//                    ->from('agreement')
//                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
//                    ->where('secondparty_id', '=', $data2[$i]['lorry_id'])
//                    ->where('agreement_id','=',$agreement_id);
//                $stmt = $selectStatement->execute();
//                $data3= $stmt->fetch();
                date_default_timezone_set("PRC");
                $year=date("Y");
                $month=date("m");
                $day=date("d");
                $agreement_time=$year.'年'.$month.'月'.$day.'日';
                $updateStatement = $database->update(array('sign_img'=>$lujing,'agreement_status'=>1,'agreement_time'=>$agreement_time))
                    ->table('agreement')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('secondparty_id', '=', $data2[$i]['lorry_id'])
                    ->where('agreement_id','=',$agreement_id);
                $affectedRows = $updateStatement->execute();
            }
            echo json_encode(array('result' => '0', 'desc' => '成功'));
        }else{
            echo json_encode(array('result' => '1', 'desc' => '该司机未注册'));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '车辆id为空'));
    }

});

$app->post('/sign_agreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorryid;
    $agreement_id=$body->agreement_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1=time();
        $new_file = "/files/agreement_sure/".date('Ymd',$time1)."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.$time1.".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing=$file_url."agreement_sure/".date('Ymd',$time1)."/".$time1.".{$type}";
        }
    }
    if($lorry_id!=null||$lorry_id!=''){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament = $database->select()
                ->from('lorry')
                ->where('exist', '=', 0)
                ->where('tenant_id', '!=', 0)
                ->where('plate_number', '=', $data1['plate_number'])
                ->where('driver_name', '=', $data1['name'])
                ->where('driver_phone', '=', $data1['phone']);
            $stmt = $selectStament->execute();
            $data2 = $stmt->fetchAll();
            for($i=0;$i<count($data2);$i++){
//                $selectStatement = $database->select()
//                    ->from('agreement')
//                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
//                    ->where('secondparty_id', '=', $data2[$i]['lorry_id'])
//                    ->where('agreement_id','=',$agreement_id);
//                $stmt = $selectStatement->execute();
//                $data3= $stmt->fetch();
                date_default_timezone_set("PRC");
                $year=date("Y");
                $month=date("m");
                $day=date("d");
                $agreement_time=$year.'年'.$month.'月'.$day.'日';
                $updateStatement = $database->update(array('sign_img'=>$lujing,'agreement_status'=>1,'agreement_time'=>$agreement_time,'pic'=>$pic))
                    ->table('agreement')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('secondparty_id', '=', $data2[$i]['lorry_id'])
                    ->where('agreement_id','=',$agreement_id);
                $affectedRows = $updateStatement->execute();
            }
            echo json_encode(array('result' => '0', 'desc' => '成功'));
        }else{
            echo json_encode(array('result' => '1', 'desc' => '该司机未注册'));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '车辆id为空'));
    }

});


$app->get('/tongji_agreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $num=0;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($i=0;$i<count($data2);$i++){
                    $selectStament=$database->select()
                        ->from('agreement')
                        ->where('exist','=',0)
                        ->where('agreement_status','=',0)
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                        ->where('secondparty_id','=',$data2[$i]['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    if($data3!=null){
                        $num+=count($data3);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '','count'=>$num));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您还没拉过清单'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

$app->get('/get_agreement_html',function()use($app){
    echo '<div style="font-size:20px;text-align:center;">甲方的权利与义务</div>
    <div>1.甲方须如实填写货物信息，严禁夹带法律禁运物品，造成后果由甲方负责。</div>
    <div>2.甲方应对所托货物按照行业标准妥善包装，使其适合运输。</div>
    <div>3.甲方应确保所提供收货人信息准确，电话通畅，如因此造成收货延误，乙方不负违约责任。乙方确认交货后，甲方如有异议须在三天内以书面方式提出，否则视同乙方运输义务完成。运输完成后，甲方应主动按约定支付运费。</div>
    <div style="font-size:20px;text-align:center;">乙方的权利与义务</div>
    <div>1.所运货物经乙方验点准确捆扎牢固后方可行驶，路途一切费用均由乙方负承担。</div>
    <div>2.在合同规定的期限内，将货物运到指定的地点，按时向收货人发出货物到达的通知，对托运的货物要负责安全，保证货物无短缺，无损坏，否则应承担由此引起的一切赔偿责任。</div>
    <div>3.乙方保证运输途中通讯畅通，如遇异常与甲方及时联系。</div>
    <div>4.货物途中如被查超载造成罚款，由乙方承担，货物途中如被查超载造成卸货费用及货物到达卸货地点前一切费用由乙方负全责，甲方不予列支。</div>
    <div>5.因发生自然灾害等不可抗力造成货物无法按期运达目的地时，乙方应将情况及时通知甲方并取得相关证明，以便甲方与客户协调；非因自然灾害等不可抗力造成货物无法按时到达，乙方须在最短时间内运至甲方指定的收货地点并交给收货人，且赔偿逾期承运给甲方造成的全部经济损失。
    本合同不得无故违约，如有法律纠纷，在甲方所在地人民法院受理。</div>
    </div><div class="pop_close">关闭</div>';
});


$app->get("/get_lorry_status",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('lorry_status','=',1)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data){
            $selectStament=$database->select()
                ->from('app_lorry_reject')
                ->join('tenant','tenant.tenant_id','=','app_lorry_reject.tenant_id',"INNER")
                ->where('app_lorry_reject.app_lorry_id','=',$lorry_id)
                ->orderBy('app_lorry_reject.time','DESC')
                ->limit(1);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            echo json_encode(array('result' => '0', 'desc' => '司机不行','reject'=>$data1));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机良好'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

$app->post('/change_lorry_status',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body = $app->request->getBody();
    $body=json_decode($body);
    $file_url=file_url();
    $lorry_id=$body->lorryid;
    $pic1=$body->pic1;
    $pic2=$body->pic2;
    $pic3=$body->pic3;
    $pic4=$body->pic4;
    $pic5=$body->pic5;
    $pic6=$body->pic6;
    $plate_number=$body->plate_number;
    $phone=$body->phone;
    $id_number=$body->id_card;
    $length=$body->length;
    $name=$body->name;
    $weight=$body->weight;
    $type=$body->type;
    $arrays['plate_number']=$plate_number;
    $arrays['phone']=$phone;
    $arrays['id_number']=$id_number;
    $arrays['length']=$length;
    $arrays['name']=$name;
    $arrays['deadweight']=$weight;
    $arrays['type']=$type;
    if($pic1){
        $base64_image_content = $pic1;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/idcard5/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."idcard5/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['identity_card_z']=$lujing3;
    }
    if($pic2){
        $base64_image_content = $pic2;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/idcard6/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."idcard6/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['identity_card_f']=$lujing3;
    }
    if($pic3){
        $base64_image_content = $pic3;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driver_license_fp']=$lujing3;
    }
    if($pic4){
        $base64_image_content = $pic4;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry2/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driver_license_tp']=$lujing3;
    }
    if($pic5){
        $base64_image_content = $pic5;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_fp']=$lujing3;
    }
    if($pic6){
        $base64_image_content = $pic6;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_tp']=$lujing3;
    }
    $arrays['lorry_status']=0;
    $updateStatement = $database->update($arrays)
        ->table('app_lorry')
        ->where('app_lorry_id','=',$lorry_id);
    $affectedRows = $updateStatement->execute();
    if($affectedRows>0){
        echo json_encode(array('result' => '0', 'desc' => 'success'));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '未找到该司机信息'));
    }
});

$app->post('/change_lorry_status1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body = $app->request->getBody();
    $body=json_decode($body);
    $file_url=file_url();
    $lorry_id=$body->lorryid;
    $pic5=$body->pic5;
    $pic6=$body->pic6;
    $plate_number=$body->plate_number;
    $length=$body->length;
    $weight=$body->weight;
    $type=$body->type;
    $arrays['plate_number']=$plate_number;
    $arrays['length']=$length;
    $arrays['deadweight']=$weight;
    $arrays['type']=$type;
    if($pic5){
        $base64_image_content = $pic5;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_fp']=$lujing3;
    }
    if($pic6){
        $base64_image_content = $pic6;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing3 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_tp']=$lujing3;
    }
    $arrays['lorry_status']=0;
    $updateStatement = $database->update($arrays)
        ->table('app_lorry')
        ->where('app_lorry_id','=',$lorry_id);
    $affectedRows = $updateStatement->execute();
    if($affectedRows>0){
        echo json_encode(array('result' => '0', 'desc' => 'success'));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '未找到该司机信息'));
    }
});


$app->get('/checkSchedulings',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $num=0;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data1['plate_number'])
                ->where('driver_phone','=',$data1['phone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($i=0;$i<count($data2);$i++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('exist','=',0)
                        ->where('scheduling_status','!=',1)
                        ->where('scheduling_status','!=',5)
                        ->where('scheduling_status','!=',7)
                        ->where('scheduling_status','!=',8)
                        ->where('scheduling_status','!=',9)
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                        ->where('lorry_id','=',$data2[$i]['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    if($data3!=null){
                        $num+=count($data3);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '','count'=>$num));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您还没拉过清单'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机id'));
    }
});

//$app->post('/changeIs_bind',function()use($app){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $body = $app->request->getBody();
//    $body=json_decode($body);
//    $lorry_id=$body->lorry_id;
//    $database=localhost();
//    if($lorry_id!=''||$lorry_id!=null){
//        $updateStatement = $database->update(array('is_bind'=>1))
//            ->table('app_lorry')
//            ->where('is_bind','=',0)
//            ->where('exist','=',0)
//            ->where('app_lorry_id','=',$lorry_id);
//        $affectedRows = $updateStatement->execute();
//        echo json_encode(array('result'=>'0','desc'=>'success'));
//    }else{
//      echo json_encode(array('result'=>'1','desc'=>'缺少司机id'));
//    }
//});

$app->get('/getAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $phone= $app->request->get("phone");
    $database=localhost();
    if($phone!=null||$phone!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('phone','=',$phone);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetchAll();
        echo json_encode(array('result' => '0', 'desc' => 'success','app_lorrys'=>$data1));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少司机电话'));
    }
});

$app->post('/changeTime',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $database=localhost();
    if($lorry_id!=''||$lorry_id!=null){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('app_lorry_id','=',$lorry_id)
            ->where('exist','=',0);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1){
            $time=time();
            $updateStatement = $database->update(array('time'=>$time))
                ->table('app_lorry')
                ->where('app_lorry_id','=',$lorry_id)
                ->where('exist','=',0);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result'=>'0','desc'=>'success','time'=>$time));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'该车是不存在'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少司机id'));
    }
});

$app->post('/changeIsExist',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $num=0;
    $database=localhost();
        if($lorry_id!=null||$lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('app_lorry_id','=',$lorry_id);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $selectStament=$database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('flag','=',0)
                    ->where('tenant_id','!=',0)
                    ->where('plate_number','=',$data1['plate_number'])
                    ->where('driver_phone','=',$data1['phone']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetchAll();
                if($data2!=null){
                    for($i=0;$i<count($data2);$i++){
                        $selectStament=$database->select()
                            ->from('scheduling')
                            ->where('exist','=',0)
                            ->where('scheduling_status','!=',1)
                            ->where('scheduling_status','!=',5)
                            ->where('scheduling_status','!=',7)
                            ->where('scheduling_status','!=',8)
                            ->where('scheduling_status','!=',9)
                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
                            ->where('lorry_id','=',$data2[$i]['lorry_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetchAll();
                        if($data3!=null){
                            $num+=count($data3);
                        }
                    }
                }
                if($num==0){
                    $updateStatement = $database->update(array('exist'=>2))
                        ->table('app_lorry')
                        ->where('exist','=',0)
                        ->where('app_lorry_id','=',$lorry_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'您还有清单未处理'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '车辆为当前选中车辆，不可删除'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少司机id'));
        }
});

$app->post('/addlorryA',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $long=$body->long;
    $ctype=$body->ctype;
    $cweight=$body->cweight;
    $lorryid=$body->lorryid;
    $plate_number=$body->plate_number;
    if($lorryid!=null||$lorryid!="") {
            if ($long != null || $long != "") {
                if ($ctype != null || $ctype != "") {
                    if($plate_number!=null||$plate_number!=""){
                        $selectStament=$database->select()
                            ->from('app_lorry')
                            ->orderBy('id','desc')
                            ->limit(1);
                        $stmt=$selectStament->execute();
                        $datab=$stmt->fetch();
                        $selectStament = $database->select()
                            ->from('app_lorry')
                            ->where('app_lorry_id', '=', $lorryid);
                        $stmt = $selectStament->execute();
                        $dataa = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('app_lorry')
                            ->where('exist', '=', 0)
                            ->where('plate_number', '=', $plate_number)
                            ->where('phone', '=', $dataa['phone']);
                        $stmt=$selectStament->execute();
                        $datac=$stmt->fetch();
                        if(!$datac){
                            $insertStatement = $database->insert(array('app_lorry_id','phone','password','id_number','name','driver_license_fp','driver_license_tp','identity_card_z','identity_card_f','exist','time'))
                                ->into('app_lorry')
                                ->values(array($datab['id']+1,$dataa['phone'],$dataa['password'],$dataa['id_number'],$dataa['name'],$dataa['driver_license_fp'],$dataa['driver_license_tp'],$dataa['identity_card_z'],$dataa['identity_card_f'],1,$dataa['time']));
                            $insertId = $insertStatement->execute(false);
                            if ($dataa != null) {
                                $arrays['flag'] = 0;
                                $arrays['type'] = $ctype;
                                $arrays['length'] = $long;
                                $arrays['plate_number']=$plate_number;
                                $arrays['deadweight']=$cweight;
                                $updateStatement = $database->update($arrays)
                                    ->table('app_lorry')
                                    ->where('app_lorry_id', '=', ($datab['id']+1));
                                $affectedRows = $updateStatement->execute();
                                echo json_encode(array('result' => '0', 'desc' => '', 'lorryid' => ($datab['id']+1)));
                            } else {
                                echo json_encode(array('result' => '4', 'desc' => '未填写电话号码'));
                            }
                        }else{
                            echo json_encode(array('result' => '6', 'desc' => '该车辆已注册'));
                        }
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '车牌号不能为空'));
                    }
                } else {
                    echo json_encode(array('result' => '3', 'desc' => '未选择车辆类型'));
                }
            } else {
                echo json_encode(array('result' => '2', 'desc' => '未填写车辆长度'));
            }
    }else{
        echo json_encode(array('result' => '5', 'desc' => '未填写电话号码'));
    }
});

$app->post('/addlorryB',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorryid=$body->lorryid;
    $id_pic5=$body->id_pic5;
    $id_pic6=$body->id_pic6;
    $lujing5=null;
    $lujing6=null;
    if($id_pic5!=null){
        $base64_image_content = $id_pic5;
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
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing5 = $file_url."lorry3/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license_fp']=$lujing5;
        if($id_pic6!=null){
            $base64_image_content = $id_pic6;
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
                $new_file = $new_file . $time1 . ".{$type}";
                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                    $lujing6 = $file_url."lorry4/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                }
            }
            $arrays['driving_license_tp']=$lujing6;
            $arrays['flag']=0;
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $arrays['exist']=0;
                $updateStatement = $database->update($arrays)
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success'));
            }else {
                echo json_encode(array('result' => '4', 'desc' => '车辆信息有误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '没有行驶证反面图片'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有行驶证正面图片'));
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