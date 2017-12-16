<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16
 * Time: 10:52
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//客户端获取app二维码
$app->get('/getLatest',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('client')
        ->orderBy('id','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $client=$data[0];
        echo  json_encode(array("result"=>"0","desc"=>"success","client"=>$client));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"无客户端版本"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>