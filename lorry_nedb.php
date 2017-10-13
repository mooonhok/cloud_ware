<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 10:52
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
$app->post('/addLorry',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $database = localhost();
    $lorry_id= $body->lorry_id;
    $plate_number= $body->plate_number;
    $driver_name= $body->driver_name;
    $driver_phone= $body->driver_phone;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
            if($plate_number!=null||$plate_number!=''){
                if($driver_name!=null||$driver_name!=''){
                    if($driver_phone!=null||$driver_phone!=''){
                                    $array['tenant_id']=$tenant_id;
                                    $array['exist']=0;
                                    $array['driving_license']="http://api.uminfo.cn/lorry/photo1.png";
                                    $array['vehicle_travel_license']="http://api.uminfo.cn/lorry/photo2.png";
                                    $insertStatement = $database->insert(array_keys($array))
                                        ->into('lorry')
                                        ->values(array_values($array));
                                    $insertId = $insertStatement->execute(false);
                                    echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "缺少驾驶员手机号码"));
                    }
                }else{
                    echo json_encode(array("result" => "5", "desc" => "缺少驾驶员名字"));
                }
            }else{
                echo json_encode(array("result" => "6", "desc" => "缺少车牌号"));
            }
        }else{
            echo json_encode(array("result" => "7", "desc" => "缺少车辆id"));
        }
    }else{
        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $plate_number= $app->request->get('plate_number');
    if($tenant_id!=null||$tenant_id!=''){
        if($plate_number!=null||$plate_number!=''){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('plate_number', '=', $plate_number);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少车牌号码"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorrys0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorrys1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitLorrys',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
            ->leftJoin('lorry_type','lorry_type.lorry_type_id','=','lorry.lorry_type_id')
            ->where('lorry.exist', '=', 0)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->orderBy('lorry.lorry_id','desc')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->delete('/deleteLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $lorry_id= $app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>1))
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
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $lorry_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少车辆id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $lorry_id= $body->lorry_id;
    $plate_number= $body->plate_number;
    $driver_name= $body->driver_name;
    $driver_phone= $body->driver_phone;
//    $driving_license=$body->driving_license;
//    $vehicle_travel_license=$body->vehicle_travel_license;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
            if($plate_number!=null||$plate_number!=''){
                if($driver_name!=null||$driver_name!=''){
                    if($driver_phone!=null||$driver_phone!=''){
//                        if($driving_license!=null||$driving_license!=''){
//                            if($vehicle_travel_license!=null||$vehicle_travel_license!=''){
                                    $updateStatement = $database->update($array)
                                        ->table('lorry')
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('lorry_id','=',$lorry_id);
                                    $affectedRows = $updateStatement->execute();
                                    echo json_encode(array("result" => "0", "desc" => "success"));
//                            }else{
//                                echo json_encode(array("result" => "2", "desc" => "缺少行驶证"));
//                            }
//                        }else{
//                            echo json_encode(array("result" => "3", "desc" => "缺少驾驶证"));
//                        }
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "缺少驾驶员手机号码"));
                    }
                }else{
                    echo json_encode(array("result" => "5", "desc" => "缺少驾驶员名字"));
                }
            }else{
                echo json_encode(array("result" => "6", "desc" => "缺少车牌号码"));
            }
        }else{
            echo json_encode(array("result" => "7", "desc" => "缺少车辆id"));
        }
    }else{
        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
    }
});

$app->post('/uploadLorry',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $lorry_id=$app->request->params('lorry_id');
    $tenant_id=$app->request->params('tenant_id');
    $database = localhost();
    $array=null;
    if(isset($_FILES["driving_license"])){
        $name1 = $_FILES["driving_license"]["name"];
        $name1 = iconv("UTF-8", "UTF-8", $name1);
        $shijian = time();
        $name1 = $shijian . $name1;
        move_uploaded_file($_FILES["driving_license"]["tmp_name"], "lorry/" . $name1);
        $array['driving_license']="http://api.uminfo.cn/".$name1;
    }
    if(isset($_FILES["vehicle_travel_license"])){
        $name2 = $_FILES["vehicle_travel_license"]["name"];
        $name2 = iconv("UTF-8", "UTF-8", $name2);
        $shijian = time();
        $name2 = $shijian . $name2;
        move_uploaded_file($_FILES["vehicle_travel_license"]["tmp_name"], "lorry/" . $name2);
        $array['vehicle_travel_license']="http://api.uminfo.cn/".$name2;
    }

    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update($array)
            ->table('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('lorry_id','=',$lorry_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
    }
});

$app->get('/getLorrys2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist','=',1);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitLorrys2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size = $app->request->get("size");
    $offset = $app->request->get("offset");
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist','=',1)
                    ->orderBy('lorry_id','DESC')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo json_encode(array("result" => "0", "desc" => "success",'lorrys'=>$data));
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少偏移量"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少size"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});

$app->put('/recoverLorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $lorry_id= $body->lorry_id;
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
                                $updateStatement = $database->update(array('exist'=>0))
                                    ->table('lorry')
                                    ->where('tenant_id','=',$tenant_id)
                                    ->where('lorry_id','=',$lorry_id);
                                $affectedRows = $updateStatement->execute();
                                echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>