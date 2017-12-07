<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1
 * Time: 10:26
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addPickup',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $pickup_id=$body->pickup_id;
    $pickup_name=$body->pickup_name;
    $pickup_phone=$body->pickup_phone;
    $pickup_number=$body->pickup_number;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($pickup_id!=null||$pickup_id!=''){
            if($pickup_name!=null||$pickup_name!=''){
                if($pickup_phone!=null||$pickup_phone!=''){
                    if($pickup_number!=null||$pickup_number!=''){
                        $array['tenant_id']=$tenant_id;
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('pickup')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "缺少收件人身份证"));
                    }
                }else{
                    echo json_encode(array("result" => "2", "desc" => "缺少收件人电话"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少收件人名字"));
            }
        }else{
            echo json_encode(array("result" => "4", "desc" => "缺少收货人id"));
        }
    }else{
        echo json_encode(array("result" => "5", "desc" => "缺少租户公司id"));
    }
   });

$app->get('/getPickup',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $pickup_name=$app->request->get('pickup_name');
    $pickup_phone=$app->request->get('pickup_phone');
    $pickup_number=$app->request->get('pickup_number');
    $type=$app->request->get('type');
    $selectStatement = $database->select()
        ->from('pickup')
        ->where('pickup_name','=',$pickup_name)
        ->where('pickup_phone','=',$pickup_phone)
        ->where('pickup_number','=',$pickup_number)
        ->where('type','=',$type)
        ->where('exist','=',0)
        ->where('tenant_id', '=', $tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo json_encode(array("result" => "0", "desc" => "success",'pickup'=>$data));
});

$app->get('/getPickups0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('pickup')
        ->where('tenant_id', '=', $tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->run();

function localhost(){
    return connect();
}