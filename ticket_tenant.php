<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 15:15
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getTicketTenants0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database = localhost();
        $selectStatement = $database->select()
            ->from('ticket_tenant')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_tenants'=>$data2));
});

$app->get('/getTicketTenant0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $company_id=$app->request->get('company_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_tenant')
        ->where('tenant_id','=',$tenant_id)
        ->where('company_id','=',$company_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_tenant'=>$data));
});

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>