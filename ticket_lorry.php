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



$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>