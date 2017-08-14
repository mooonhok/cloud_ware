<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:38
 */
require 'Slim/Slim.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/province',function ()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $selectStatement = $database->select()
        ->from('province');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});

$app->post('/city',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $pid=$body->pid;
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    if($pid!=null||$pid!=""){
            $selectStatement = $database->select()
                ->from('city')
                ->where('pid','=',$pid);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }
});

$app->run();
?>
