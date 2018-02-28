<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28
 * Time: 9:48
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/getStatistic0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('customer_id');
    if($tenant_id!=null||$tenant_id!=""){
        if($customer_id!=null||$customer_id!=""){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('sender_id','=',$customer_id)
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
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
