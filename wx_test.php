<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 11:31
 */

require 'Slim/Slim.php';
require 'connect.php';
require  'wx_interface.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('tenant');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    if($data1!=null||$data1!=""){
        echo json_encode(array('result'=>'1','desc'=>'尚未有数据','tenant'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'尚未有数据','tenant'=>''));
    }
});

$app->post('/testme',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $openid=$body->openid;
    $orderid=$body->orderid;
    $wechatObj = new wechatCallbackapiTest();
    $wechatObj->responseMsg2($openid,$orderid);
});

$app->run();

function localhost(){
    return connect();
}
?>
