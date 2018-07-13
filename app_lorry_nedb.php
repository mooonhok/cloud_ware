<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/27
 * Time: 15:25
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $platenumber=$app->request->get("plate_number");
    $name=$app->request->get("driver_name");
    $phone=$app->request->get("driver_phone");
    $type=$app->request->get('flag');
    if($platenumber!=null||$platenumber!=""){
        if($name!=null||$name!=""){
            if($phone!=null||$phone!=""){
             $selectStatement = $database->select()
                ->from('app_lorry')
                 ->where('exist','=',0)
                 ->where('name','=',$name)
                 ->where('flag','=',$type)
                 ->where('phone','=',$phone)
                ->where('plate_number','=',$platenumber);
              $stmt = $selectStatement->execute();
             $data= $stmt->fetch();
               if($data!=null){
                   echo json_encode(array("result"=>"0","desc"=>"","lorrys"=>$data));
               }else{
                   echo json_encode(array("result"=>"4","desc"=>"司机不存在"));
               }
            }else{
                echo json_encode(array("result"=>"3","desc"=>"缺少电话"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少姓名"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少车牌号"));
    }

});


$app->get('/getAppLorry1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $applorry=$app->request->get("app_lorry_id");
    if($applorry!=null||$applorry!=null){
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('app_lorry_id','=',$applorry);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();
        if($data!=null){
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data[$i]['length']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                $data[$i]['lorry_length_name']=$data2['lorry_length'];
//                $selectStatement = $database->select()
//                    ->from('lorry_load')
//                    ->where('lorry_load_id','=',$data[$i]['deadweight']);
//                $stmt = $selectStatement->execute();
//                $data3= $stmt->fetch();
                $data[$i]['lorry_load_name']=$data[$i]['deadweight'];
                $selectStatement = $database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data[$i]['type']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $data[$i]['lorry_type_name']=$data4['lorry_type_name'];
            }
            echo json_encode(array("result"=>"0","desc"=>"","lorrys"=>$data));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"司机不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少司机ID"));
    }
});

$app->put('/alterAppLorry0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $lorry_status=$body->lorry_status;
    $app_lorry_id=$body->app_lorry_id;
    $updateStatement = $database->update(array('lorry_status'=>$lorry_status))
            ->table('app_lorry')
            ->where('app_lorry_id','=',$app_lorry_id);
    $affectedRows = $updateStatement->execute();
    if($affectedRows){
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "没有app_lorry_id"));
    }
});

$app->get('/getAppLorry_app',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $applorry=$app->request->get("app_lorry_id");
    if($applorry!=null||$applorry!=null){
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('app_lorry_id','=',$applorry);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();
        if($data!=null){
            echo json_encode(array("result"=>"0","desc"=>"","lorry"=>$data));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"司机不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少司机ID"));
    }
});

$app->get('/checkAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $driver_name = $app->request->get('driver_name');
    $driver_phone = $app->request->get('driver_phone');
    $plate_number = $app->request->get('plate_number');
    $flag=$app->request->get('flag');
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('flag','=',$flag)
            ->where('exist','=',0)
            ->where('phone','=',$driver_phone)
            ->where('plate_number','=',$plate_number)
            ->where('name','=',$driver_name);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1==null){
            if($data1['lorry_status']!=1){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('flag','=',$flag)
                    ->where('tenant_id','=',$tenant_id)
                    ->where('driver_phone','=',$driver_phone)
                    ->where('plate_number','=',$plate_number)
                    ->where('driver_name','=',$driver_name);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                if($data2!=null){
                    if($data2['exist']==0){
                        echo json_encode(array('result'=>'4','desc'=>'车辆已添加过'));
                    }else if($data2['exist']==1){
                        echo json_encode(array('result'=>'3','desc'=>'车辆被加入黑名单'));
                    }
                }else{
                    $selectStatement = $database->select()
                        ->from('lorry_length')
                        ->where('lorry_length_id','=',$data1['length']);
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetch();
                    $data1['lorry_length_name']=$data5['lorry_length'];
//                    $data1['lorry_load_name']=$data1['deadweight'];
                    $selectStatement = $database->select()
                        ->from('lorry_type')
                        ->where('lorry_type_id','=',$data1['type']);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    $data1['lorry_type_name']=$data4['lorry_type_name'];
                    echo json_encode(array("result"=>"0","desc"=>"","lorrys"=>$data1));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'正在修改资料'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'未注册交付帮手'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'缺少租户id'));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>