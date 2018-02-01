<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 9:35
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addAdmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
         $insertStatement = $database->insert(array_keys($array))
                   ->into('lisence_admin')
                   ->values(array_values($array));
         $insertId = $insertStatement->execute(false);
         echo json_encode(array("result"=>"0","desc"=>"success"));

});

$app->get('/getAdmin0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $username=$app->request->get('username');
    $passwd=$app->request->get('passwd');
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0)
        ->where('username','=',$username)
        ->where('passwd','=',$passwd);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data));
});

$app->get('/getAdmin1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $username=$app->request->get('username');
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0)
        ->where('username','=',$username);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","lisence_admins"=>$data));
});

$app->get('/getTenant0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $tenant_id=$app->request->get('tenant_id');
    $company=$app->request->get('company');
    $tenant_num=$app->request->get('tenant_num');
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('company','=',$company)
        ->where('tenant_num','=',$tenant_num)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data));
});

$app->run();
function localhost(){
    return connect();
}
?>