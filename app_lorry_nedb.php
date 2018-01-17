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
$app->run();

function localhost(){
    return connect();
}
?>