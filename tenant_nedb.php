<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 16:05
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getTenant1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant_id');
    $selectStatement = $database->select()
        ->from('tenant')
        ->join('customer','customer.customer_id','=','tenant.contact_id','INNER')
        ->where('tenant.tenant_id','=',$tenant_id)
        ->where('customer.tenant_id','=',$tenant_id)
        ->where('tenant.exist',"=",0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('city')
        ->where('id', '=', $data['customer_city_id']);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('province')
        ->where('id', '=', $data1['pid']);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    $data['city']=$data1;
    $data['province']=$data2;
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data));
});

$app->run();

function localhost(){
    return connect();
}

?>