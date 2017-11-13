<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/13
 * Time: 17:27
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getApp',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('app')
        ->orderBy('id','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $app=$data[0]['url'];
        echo  json_encode(array("result"=>"0","desc"=>"success","app"=>$app));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"无app版本"));
    }

});

$app->run();
function localhost(){
    return connect();
}
?>