<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/8
 * Time: 17:15
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getTicketSkins',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
//    $lorry_id=$app->request->get('lorry_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_skin');
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_skins'=>$data2));
});

$app->get('/getTicketSkin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_skin')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_skin'=>$data2));
});





$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>