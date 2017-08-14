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
    $database=localhost();
    $selectStatement = $database->select()
        ->from('province');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});

$app->post('/city',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $body = $app->request->getBody();
    $body=json_decode($body);
    $pid=$body->pid;
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
function localhost(){
    return connect();
}
?>
