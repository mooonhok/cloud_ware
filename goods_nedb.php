<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 10:00
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();

$app->put('/alterGood',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id=$body->order_id;
    $goods_name=$body->goods_name;
    $goods_weight=$body->goods_weight;
    $goods_package_id=$body->goods_package_id;
    $goods_value=$body->goods_value;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            if($goods_name!=null||$goods_name!=''){
                if($goods_weight!=null||$goods_weight!=''){
                    if($goods_package_id!=null||$goods_package_id!=''){
                        if($goods_value!=null||$goods_value!=''){
                                $updateStatement = $database->update($array)
                                    ->table('goods')
                                    ->where('tenant_id','=',$tenant_id)
                                    ->where('order_id','=',$order_id);
                                $affectedRows = $updateStatement->execute();
                                echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
                        }
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
                    }
                }else{
                    echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
                }
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
            }
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterGood1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id=$body->order_id;
    $special_need=$body->special_need;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            if($special_need!=null||$special_need!=''){
                $updateStatement = $database->update($array)
                    ->table('goods')
                    ->where('order_id','=',$order_id)
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少特殊需求"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少运单id"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>