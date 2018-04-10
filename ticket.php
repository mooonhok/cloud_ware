<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 13:10
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/gettickets',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->leftJoin('ticket_lorry','ticket_lorry.company_id','=','ticket.id')
        ->orderBy('sign_img','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>$data));
});

$app->get('/getticket',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>$data));
});

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>