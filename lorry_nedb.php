<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 10:52
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addLorry',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $database = localhost();
    $plate_number= $body->plate_number;
    $driver_name= $body->driver_name;
    $driver_phone= $body->driver_phone;
    $flag=$body->flag;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
            if($plate_number!=null||$plate_number!=''){
                if($driver_name!=null||$driver_name!=''){
                    if($driver_phone!=null||$driver_phone!=''){
//                        $selectStatement = $database->select()
//                            ->from('app_lorry')
//                            ->where('plate_number', '=', $plate_number)
//                            ->where('flag','=',$flag)
//                            ->where('name', '=', $driver_name)
//                            ->where('exist', '=', 0)
//                            ->where('phone', '=', $driver_phone);
//                        $stmt = $selectStatement->execute();
//                        $data1 = $stmt->fetch();
//                        $selectStatement = $database->select()
//                            ->from('lorry')
//                            ->where('tenant_id', '=', $tenant_id)
//                            ->where('plate_number', '=', $plate_number)
//                            ->where('driver_name', '=', $driver_name)
//                            ->where('driver_phone', '=', $driver_phone);
//                        $stmt = $selectStatement->execute();
////                        $data4 = $stmt->fetch();
//                              if(!$data1){
//                                  echo json_encode(array("result" => "6", "desc" => "请司机下载交付帮手注册"));
//                              }else{
//                                  if($data1['lorry_status']==1){
//                                      echo json_encode(array("result" => "7", "desc" => "驾驶员正在修改个人资料"));
//                                  }else{
//                                 if($data4==null){
                                     $array['tenant_id']=$tenant_id;
                                     $array['exist']=0;
                                     $selectStatement = $database->select()
                                       ->from('lorry')
                                       ->where('tenant_id', '=', $tenant_id);
                                     $stmt = $selectStatement->execute();
                                     $data1 = $stmt->fetchAll();
                                     $array['lorry_id']=count($data1)+100000001;
                                     $insertStatement = $database->insert(array_keys($array))
                                         ->into('lorry')
                                         ->values(array_values($array));
                                      $insertId = $insertStatement->execute(false);
                                      echo json_encode(array("result" => "0", "desc" => "success",'app_lorry'=>$data1['app_lorry_id']));
//                                   }else if($data4['exist']==0){
//                                      echo json_encode(array("result" => "1", "desc" => "该车辆已被添加过"));
//                                    }else if($data4['exist']==1){
//                                     echo json_encode(array("result" => "8", "desc" => "车辆已被您加入了黑名单"));
//                                 }}}
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "缺少驾驶员手机号码"));
                    }
                }else{
                    echo json_encode(array("result" => "3", "desc" => "缺少驾驶员名字"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "缺少车牌号"));
            }
    }else{
        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
    }
});
//$app->post('/addLorry',function()use($app) {
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $body = $app->request->getBody();
//    $body = json_decode($body);
//    $database = localhost();
//    $lorry_id= $body->lorry_id;
//    $plate_number= $body->plate_number;
//    $driver_name= $body->driver_name;
//    $driver_phone= $body->driver_phone;
////    $flag=$body->flag;
//    $array = array();
//    foreach ($body as $key => $value) {
//        $array[$key] = $value;
//    }
//    if($tenant_id!=null||$tenant_id!=''){
//        if($lorry_id!=null||$lorry_id!=''){
//            if($plate_number!=null||$plate_number!=''){
//                if($driver_name!=null||$driver_name!=''){
//                    if($driver_phone!=null||$driver_phone!=''){
////                        $selectStatement = $database->select()
////                            ->from('lorry')
////                            ->where('tenant_id', '=', 0)
////                            ->where('driver_phone', '=', $driver_phone);
////                        $stmt = $selectStatement->execute();
////                        $data1 = $stmt->fetch();
//
//                        $selectStatement = $database->select()
//                            ->from('lorry')
//                            ->where('tenant_id', '=', $tenant_id)
//                            ->where('driver_phone', '=', $driver_phone);
//                        $stmt = $selectStatement->execute();
//                        $data4 = $stmt->fetch();
//
//                        $selectStatement = $database->select()
//                            ->from('lorry');
//                        $stmt = $selectStatement->execute();
//                        $data = $stmt->fetchAll();
//
////                        if(!$data1){
////                            $password1=123456;
////                            $str1=str_split($password1,3);
////                            $password=null;
////                            for ($x=0;$x<count($str1);$x++){
////                                $password.=$str1[$x].$x;
////                            }
////                            $insertStatement = $database->insert(array('lorry_id','plate_number','driver_name','driver_phone','password','flag','driving_license','vehicle_travel_license'))
////                                ->into('lorry')
////                                ->values(array((count($data)+100000001),$plate_number,$driver_name,$driver_phone,$password,$flag,"http://files.uminfo.cn:8000/lorry/photo1.png","http://files.uminfo.cn:8000/lorry/photo2.png"));
////                            $insertId = $insertStatement->execute(false);
////                            $array['tenant_id']=$tenant_id;
////                            $array['exist']=0;
////                            $array['driving_license']="http://files.uminfo.cn:8000/lorry/photo1.png";
////                            $array['vehicle_travel_license']="http://files.uminfo.cn:8000/lorry/photo2.png";
////
////                            $insertStatement = $database->insert(array_keys($array))
////                                ->into('lorry')
////                                ->values(array_values($array));
////                            $insertId = $insertStatement->execute(false);
////                        }
//                        if((!$data4)){
//                            $array['lorry_id']=count($data)+100000001;
////                            $array['signtime']=$data1['signtime'];
//                            $array['tenant_id']=$tenant_id;
//                            $array['exist']=0;
//                            $array['driving_license']="http://files.uminfo.cn:8000/lorry/photo1.png";
//                            $array['vehicle_travel_license']="http://files.uminfo.cn:8000/lorry/photo2.png";
//                            $insertStatement = $database->insert(array_keys($array))
//                                ->into('lorry')
//                                ->values(array_values($array));
//                            $insertId = $insertStatement->execute(false);
//                            echo json_encode(array("result" => "0", "desc" => "success"));
//                        }else{
//                            echo json_encode(array("result" => "1", "desc" => "该手机号已被使用"));
//                        }
//                    }else{
//                        echo json_encode(array("result" => "4", "desc" => "缺少驾驶员手机号码"));
//                    }
//                }else{
//                    echo json_encode(array("result" => "5", "desc" => "缺少驾驶员名字"));
//                }
//            }else{
//                echo json_encode(array("result" => "6", "desc" => "缺少车牌号"));
//            }
//        }else{
//            echo json_encode(array("result" => "7", "desc" => "缺少车辆id"));
//        }
//    }else{
//        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
//    }
//});


$app->get('/getLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $driver_phone= $app->request->get('driver_phone');
    $plate_number= $app->request->get('plate_number');
    $driver_name= $app->request->get('driver_name');
    $flag=$app->request->get('flag');
    if($tenant_id!=null||$tenant_id!=''){
        if($driver_phone!=null||$driver_phone!=''){

            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('flag', '=', $flag)
                ->where('driver_phone', '=', $driver_phone)
                ->where('plate_number','=',$plate_number)
                ->where('driver_name','=',$driver_name)
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
//            for($i=0;$i<count($data);$i++){
//                $selectStatement = $database->select()
//                    ->from('lorry_length')
//                    ->where('lorry_length.lorry_length_id', '=', $data[$i]['length']);
//                $stmt = $selectStatement->execute();
//                $data1 = $stmt->fetch();
////                $selectStatement = $database->select()
////                    ->from('lorry_load')
////                    ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
////                $stmt = $selectStatement->execute();
////                $data2 = $stmt->fetch();
//                $selectStatement = $database->select()
//                    ->from('lorry_type')
//                    ->where('lorry_type.lorry_type_id', '=', $data[$i]['type']);
//                $stmt = $selectStatement->execute();
//                $data3 = $stmt->fetch();
//                $data[$i]['lorry_length_name']=$data1['lorry_length'];
//                $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
////                $data[$i]['lorry_load_name']=$data2['lorry_load'];
//            }

            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少车牌号码"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorry1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $driver_phone= $app->request->get('driver_phone');
    $plate_number=$app->request->get('plate_number');
    $driver_name=$app->request->get('driver_name');
    if($tenant_id!=null||$tenant_id!=''){
        if($driver_phone!=null||$driver_phone!=''){

            $selectStatement = $database->select()
                ->from('app_lorry')
                ->join('lorry','app_lorry.phone','=','lorry.driver_phone','INNER')
                ->where('lorry.tenant_id', '=', $tenant_id)
                ->where('lorry.plate_number', '=', $plate_number)
                ->where('app_lorry.plate_number', '=', $plate_number)
                ->where('lorry.driver_name', '=', $driver_name)
                ->where('app_lorry.name', '=', $driver_name)
                ->where('lorry.driver_phone', '=', $driver_phone)
                ->orderBy('lorry.id','DESC');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('lorry_length')
                    ->where('lorry_length.lorry_length_id', '=', $data[$i]['length']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
//                $selectStatement = $database->select()
//                    ->from('lorry_load')
//                    ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry_type')
                    ->where('lorry_type.lorry_type_id', '=', $data[$i]['type']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $data[$i]['lorry_length_name']=$data1['lorry_length'];
                $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
//                $data[$i]['lorry_load_name']=$data2['lorry_load'];
            }

            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少电话号码"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

//$app->get('/getLorrys0',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $database = localhost();
//    if($tenant_id!=null||$tenant_id!=''){
//        $selectStatement = $database->select()
//            ->from('lorry')
//            ->where('tenant_id', '=', $tenant_id);
//        $stmt = $selectStatement->execute();
//        $data = $stmt->fetchAll();
////        for($i=0;$i<count($data);$i++){
////            $selectStatement = $database->select()
////                ->from('lorry_length')
////                ->where('lorry_length.lorry_length_id', '=', $data[$i]['length']);
////            $stmt = $selectStatement->execute();
////            $data1 = $stmt->fetch();
//////            $selectStatement = $database->select()
//////                ->from('lorry_load')
//////                ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
//////            $stmt = $selectStatement->execute();
//////            $data2 = $stmt->fetch();
////            $selectStatement = $database->select()
////                ->from('lorry_type')
////                ->where('lorry_type.lorry_type_id', '=', $data[$i]['type']);
////            $stmt = $selectStatement->execute();
////            $data3 = $stmt->fetch();
////            $data[$i]['lorry_length_name']=$data1['lorry_length'];
////            $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
////        }
//        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});

$app->get('/getLorrys1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $exist= $app->request->get('exist');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
//            ->leftJoin('app_lorry','app_lorry.phone','=','lorry.driver_phone')
//            ->where('app_lorry.exist', '=', 0)
            ->where('exist', '=',$exist)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        for($i=0;$i<count($data);$i++){
//            $selectStatement = $database->select()
//                ->from('lorry_length')
//                ->where('lorry_length.lorry_length_id', '=', $data[$i]['length']);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetch();
////            $selectStatement = $database->select()
////                ->from('lorry_load')
////                ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
////            $stmt = $selectStatement->execute();
////            $data2 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('lorry_type')
//                ->where('lorry_type.lorry_type_id', '=', $data[$i]['type']);
//            $stmt = $selectStatement->execute();
//            $data3 = $stmt->fetch();
//            $data[$i]['lorry_length_name']=$data1['lorry_length'];
//            $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
//        }
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitLorrys1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $exist= $app->request->get('exist');
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('exist', '=',$exist)
            ->where('tenant_id', '=',$tenant_id)
            ->orderBy('lorry_id','desc')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('app_lorry')
                ->where('flag','=',$data[$i]['flag'])
                ->where('name','=',$data[$i]['driver_name'])
                ->where('phone','=',$data[$i]['driver_phone'])
                ->where('plate_number', '=', $data[$i]['plate_number']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_length')
                ->where('lorry_length.lorry_length_id', '=', $data2['length']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('lorry_type.lorry_type_id', '=', $data2['type']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data[$i]['lorry_length_name']=$data1['lorry_length'];
            $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
            $data[$i]['lorry_load_name']=$data2['deadweight'];
//            $data[$i]['lorry_status']=$data2['lorry_status'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitLorrys3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $flag=$app->request->get('flag');
//    $plate_number=$app->request->get('plate_number');
//    $driver_name=$app->request->get('driver_name');
//    $driver_phone=$app->request->get('driver_phone');
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
//            ->leftJoin('app_lorry','app_lorry.phone','=','lorry.driver_phone')
//            ->where('app_lorry.exist', '=', 0)
//
//            ->where('app_lorry.phone', '=', $driver_phone)
//            ->where('app_lorry.plate_number', '=', $plate_number)
//            ->where('app_lorry.name', '=', $driver_name)
//            ->where('app_lorry.flag', '=', $flag)
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->where('flag', '=', $flag)
            ->orderBy('lorry_id','desc')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('app_lorry')
            ->where('phone', '=', $data[$i]['driver_phone'])
            ->where('plate_number','=',$data[$i]['plate_number'])
            ->where('name', '=', $data[$i]['driver_name'])
            ->where('flag', '=', $data[$i]['flag']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();

            $selectStatement = $database->select()
                ->from('lorry_length')
                ->where('lorry_length.lorry_length_id', '=', $data2['length']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('lorry_load')
//                ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('lorry_type.lorry_type_id', '=', $data2['type']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data[$i]['lorry_length_name']=$data1['lorry_length'];
            $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
            $data[$i]['lorry_load_name']=$data2['deadweight'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorrys3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $flag=$app->request->get('flag');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('flag', '=', $flag)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        for($i=0;$i<count($data);$i++){
//            $selectStatement = $database->select()
//                ->from('lorry_length')
//                ->where('lorry_length.lorry_length_id', '=', $data[$i]['length']);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetch();
////            $selectStatement = $database->select()
////                ->from('lorry_load')
////                ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
////            $stmt = $selectStatement->execute();
////            $data2 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('lorry_type')
//                ->where('lorry_type.lorry_type_id', '=', $data[$i]['type']);
//            $stmt = $selectStatement->execute();
//            $data3 = $stmt->fetch();
//            $data[$i]['lorry_length_name']=$data1['lorry_length'];
//            $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
////            $data[$i]['lorry_load_name']=$data2['lorry_load'];
//        }
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id= $body->lorry_id;
    $exist= $body->exist;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>$exist))
            ->table('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('lorry_id','=',$lorry_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/searchLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $lorry_id= $app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
            $selectStatement = $database->select()
                ->from('lorry')
//                ->leftJoin('app_lorry','app_lorry.phone','=','lorry.driver_phone')
//                ->where('app_lorry.exist', '=', 0)
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $lorry_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('app_lorry')
                    ->where('name','=',$data[$i]['driver_name'])
                    ->where('phone','=',$data[$i]['driver_phone'])
                    ->where('plate_number', '=', $data[$i]['plate_number']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry_length')
                    ->where('lorry_length.lorry_length_id', '=', $data2['length']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
//                $selectStatement = $database->select()
//                    ->from('lorry_load')
//                    ->where('lorry_load.lorry_load_id', '=', $data[$i]['deadweight']);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry_type')
                    ->where('lorry_type.lorry_type_id', '=', $data2['type']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $data[$i]['lorry_length_name']=$data1['lorry_length'];
                $data[$i]['lorry_type_name']=$data3['lorry_type_name'];
				$data[$i]=array_merge($data2,$data[$i]);
            }
            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少车辆id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});


$app->post('/uploadLorry',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $lorry_id=$app->request->params('lorry_id');
    $tenant_id=$app->request->params('tenant_id');
    $database = localhost();
    $file_url=file_url();
    $array=array();
    if(isset($_FILES["driving_license"])){
        $name11 = $_FILES["driving_license"]["name"];
        if($name11){
            $name1=substr(strrchr($name11, '.'), 1);
//        $name1 = iconv("UTF-8", "gb2312", $name11);
            $shijian = time();
            $name1 = $shijian .".". $name1;
            move_uploaded_file($_FILES["driving_license"]["tmp_name"], "/files/lorry/" . $name1);
            $array['driving_license']=$file_url."lorry/".$name1;
        }
    }else{
        $array['driving_license']=$file_url."lorry/photo1.png";
    }
    if(isset($_FILES["vehicle_travel_license"])){
        $name21 = $_FILES["vehicle_travel_license"]["name"];
        if($name21){
            $name2=substr(strrchr($name21, '.'), 1);
//        $name2 = iconv("UTF-8", "gb2312", $name21);
            $shijian = time().'a';
            $name2 = $shijian .'.'. $name2;
            move_uploaded_file($_FILES["vehicle_travel_license"]["tmp_name"], "/files/lorry/" . $name2);
            $array['vehicle_travel_license']=$file_url."lorry/".$name2;
        }
    }else{
        $array['vehicle_travel_license']=$file_url."lorry/photo2.png";
    }

    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update($array)
            ->table('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('lorry_id','=',$lorry_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
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