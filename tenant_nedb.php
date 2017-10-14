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
        ->join('city','city.id','=','customer.customer_city_id','INNER')
        ->join('province','city.pid','=','province.id','INNER')
        ->where('tenant.tenant_id','=',$tenant_id)
        ->where('customer.tenant_id','=',$tenant_id)
        ->where('tenant.exist',"=",0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data));
});

$app->run();

function localhost(){
    return connect();
}

?>