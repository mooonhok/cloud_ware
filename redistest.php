<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:38
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$redis=new Redis();
$app->get('/province',function ()use($app,$redis){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $redis->connect('127.0.0.1', 6379);
    $redis->set('cat','111');
    echo $redis->get('cat');
});

$app->run();

function localhost(){
    return connect();
}
?>
