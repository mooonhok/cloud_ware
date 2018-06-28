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
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id=$body->order_id;
    $pickup_name=$body->pickup_name;
    $pickup_phone=$body->pickup_phone;
    $pickup_number=$body->pickup_number;
    $type=$body->type;
    date_default_timezone_set("PRC");
    $order_datetime5 = date('Y-m-d H:i:s',time());
    $array=array();
    foreach($body as $key=>$value){
        if($key!="order_id"){
            $array[$key]=$value;
        }
    }
    if($pickup_name!=null||$pickup_name!=''){
        if($pickup_phone!=null||$pickup_phone!=''){
            if($pickup_number!=null||$pickup_number!=''){
                if($type!=null||$type!=""){
                    $selectStatement = $database->select()
                        ->from('pickup')
                        ->where('pickup_name','=',$pickup_name)
                        ->where('pickup_phone','=',$pickup_phone)
                        ->where('pickup_number','=',$pickup_number)
                        ->where('type','=',$type)
                        ->where('exist','=',0);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetch();
                    if($data==null){
                        $selectStatement = $database->select()
                            ->from('pickup');
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetchAll();
                        $array['pickup_id']=count($data2)+1000000001;
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('pickup')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);
                        $updateStatement = $database->update(array('pickup_id'=> $array['pickup_id'],'order_datetime5'=>$order_datetime5,'order_status'=>7))
                            ->table('orders')
                            ->where('exist','=',0)
                            ->where('order_id','=',$order_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        $updateStatement = $database->update(array('pickup_id'=> $data['pickup_id'],'order_datetime5'=>$order_datetime5,'order_status'=>7))
                            ->table('orders')
                            ->where('exist','=',0)
                            ->where('order_id','=',$order_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }
                }else{
                    echo json_encode(array("result" => "6", "desc" => "缺少类型"));
                }
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少收件人身份证"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少收件人电话"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少收件人名字"));
    }
});

$app->get('/getPickup0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
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
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->get('/getPickup1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $pickup_id=$app->request->get('pickup_id');
    $selectStatement = $database->select()
        ->from('pickup')
        ->where('pickup_id','=',$pickup_id)
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->get('/getPickups0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('pickup');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->get('/getPickups1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $type=$app->request->get('type');
    $selectStatement = $database->select()
        ->from('pickup')
        ->where('exist','=',0)
        ->where('type','=',$type);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->get('/limitPickups1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $type=$app->request->get('type');
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
        $selectStatement = $database->select()
            ->from('pickup')
            ->where('type','=',$type)
            ->where('exist', '=', 0)
            ->orderBy('pickup_id','DESC')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'pickups'=>$data));
});

$app->run();

function localhost(){
    return connect();
}