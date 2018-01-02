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