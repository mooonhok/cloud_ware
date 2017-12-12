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
               ->from('applorry')
               ->where('telephone','=',$tel);
               $stmt=$selectStament->execute();
               $data1=$stmt->fetch();
               if($data1!=null){
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
                       ->values(array(count($data2)+1,$tel,$password,1));
                   $insertId = $insertStatement->execute(false);
                   echo  json_encode(array("result"=>"0","desc"=>"",'lorryid'=>count($data2)+1));
               }else{
                   echo  json_encode(array("result"=>"3","desc"=>"电话号码已经被注册"));
               }
       }else{
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
    $body=json_decode($body);
    $name=$body->name;
    $idcard=$body->idcard;
    $lorryid=$body->lorryid;
    if($name!=null||$name!=""){
         if($idcard!=null||$idcard!=""){
                 $selectStament=$database->select()
                     ->from('applorry')
                     ->where('lorryid','=',$lorryid);
                 $stmt=$selectStament->execute();
                 $data1=$stmt->fetch();
                 if($data1!=null){
                 $arrays['name']=$name;
                 $arrays['carid']=$idcard;
                 $updateStatement = $database->update($arrays)
                     ->table('applorry')
                     ->where('lorryid','=',$lorryid);
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
    $body=json_decode($body);
    $type=$body->type;
    $long=$body->long;
    $ctype=$body->ctype;
    $lorryid=$body->lorryid;
    $plate_number=$body->plate_number;
    if($type!=null||$type!=""){
        if($lorryid!=null||$lorryid!="") {
            if ($type = 0) {
                if ($long != null || $long != "") {
                    if ($ctype != null || $ctype != "") {
                        if($plate_number!=null||$plate_number!=""){
                        $selectStament = $database->select()
                            ->from('applorry')
                            ->where('lorryid', '=', $lorryid);
                        $stmt = $selectStament->execute();
                        $data1 = $stmt->fetch();
                        if ($data1 != null) {
                            $arrays['type'] = $type;
                            $arrays['ctype'] = $ctype;
                            $arrays['clong'] = $long;
                            $arrays['platenumber']=$plate_number;
                            $updateStatement = $database->update($arrays)
                                ->table('applorry')
                                ->where('lorryid', '=', $lorryid);
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
                    ->table('applorry')
                    ->where('lorryid', '=', $lorryid);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result' => '0', 'desc' => '注册成功', 'lorryid' => ''));
            }
        }else{
            echo json_encode(array('result' => '5', 'desc' => '未填写电话号码'));
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
                ->from('applorry')
                ->where('exist','=',0)
                ->where('telephone','=',$username);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                if($data1['password']==$password){
                    $time1=time();
                    $arrays['time']=$time1;
                    $updateStatement = $database->update($arrays)
                        ->table('applorry')
                        ->where('lorryid','=',$data1['lorryid']);
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
                ->from('applorry')
                ->where('exist','=',0)
                ->where('lorry','=',$lorryid);
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
            ->from('applorry')
            ->where('exist','=',0)
            ->where('lorryid','=',$lorryid);
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
//app修改驾驶证
$app->post('/updriverpic',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
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
                ->where('exist','=',0)
                ->where('lorryid','=',$lorryid);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $updateStatement = $database->update($arrays)
                    ->table('applorry')
                    ->where('lorryid','=',$lorryid);
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
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing1 = "http://files.uminfo.cn:8000/lorryhead/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['head_img']=$lujing1;
    }
    if($lorryid!=null||$lorryid!=""){
        $selectStament=$database->select()
            ->from('applorry')
            ->where('exist','=',0)
            ->where('lorryid','=',$lorryid);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $arrays['wayinctroduction']=$introduce;
            $updateStatement = $database->update($arrays)
                ->table('applorry')
                ->where('lorryid','=',$lorryid);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '名片已保存'));
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
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('applorry')
            ->where('exist','=',0)
            ->where('lorryid','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('tenant_id','!=','0')
                ->where('driver_phone','=',$data1['telephone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                        $selectStament=$database->select()
                            ->from('scheduling')
                            ->where('scheduling_status','!=',2)
                            ->where('scheduling_status','!=',3)
                            ->where('scheduling_status','!=',4)
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
                        $arrays1['address']=$data5['name'].$data4['customer_address'];
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
                    echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$arrays));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您还未拉过货物'));
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
            ->from('applorry')
            ->where('exist','=',0)
            ->where('lorryid','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('tenant_id','!=','0')
                ->where('driver_phone','=',$data1['telephone']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('scheduling_status','!=',1)
                        ->where('scheduling_status','!=',5)
                        ->where('scheduling_status','!=',6)
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
                        $arrays1['address']=$data5['name'].$data4['customer_address'];
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
                    echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$arrays));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您还未拉过货物'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
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
                $arrays1['goods_count'] = $data5['goods_count'];
                $arrays1['goods_capacity'] = $data5['goods_capacity'];
                $arrays1['goods_weight'] = $data5['goods_weight'];
                $selectStament = $database->select()
                    ->from('goods_package')
                    ->where('goods_package_id', '=', $data5['goods_package_id']);
                $stmt = $selectStament->execute();
                $data6 = $stmt->fetch();
                $arrays1['goods_package'] = $data6['goods_package'];
                array_push($arrays, $arrays1);
            }
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
            echo json_encode(array('result' => '0', 'desc' => '', 'goods' => $arrays, 'isreceive' => $data1['scheduling_status']));

        }else{
            echo json_encode(array('result' => '4', 'desc' => '该清单不存在','goods'=>''));
        }
     }else{
    echo json_encode(array('result' => '1', 'desc' => '清单号为空','goods'=>''));
}
});



$app->run();
function localhost(){
    return connect();
}
?>