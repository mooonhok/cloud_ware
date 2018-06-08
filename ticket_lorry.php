<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 15:21
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getTicketLorrys0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id=$app->request->get('lorry_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_lorry')
        ->where('lorry_id','=',$lorry_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_lorrys'=>$data2));
});

$app->get('/getTicketLorrys1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $company_id=$app->request->get('company_id');
    $is_check=$app->request->get('is_check');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_lorry')
        ->where('is_check','=',$is_check)
        ->where('company_id','=',$company_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    if($data2!=null){
      for($i=0;$i<count($data2);$i++){
          $selectStatement = $database->select()
              ->from('app_lorry')
              ->where('app_lorry_id','=',$data2[$i]['lorry_id']);
          $stmt = $selectStatement->execute();
          $data3 = $stmt->fetch();
          $data2[$i]['lorry']=$data3;
      }
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_lorrys'=>$data2));
});

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>