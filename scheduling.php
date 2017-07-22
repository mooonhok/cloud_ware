<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 16:03
 */
require 'Slim/Slim.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/scheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $order_id=$body->order_id;
    if(($tenant_id!=null||$tenant_id!='')&&($order_id!=null||$order_id!='')){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',0)
            ->where('order_id', '=',$order_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){

        }else{

        }
    }else{

    }
});
?>