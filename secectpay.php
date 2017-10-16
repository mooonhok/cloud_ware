<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/16
 * Time: 16:52
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/pay',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $goods_type=$body->type;
    $goods_weight=$body->weight;
    $goods_capacity=$body->big;
    $sendcity_id=$body->sendcity;
    $receivecity_id=$body->receive;
    $openid=$body->openid;


});

$app->run();
function localhost(){
    return connect();
}
?>