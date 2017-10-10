<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 11:30
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//查货物清单
$app->get('/goods_package',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('goods_package');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'','goods_package'=>$data1));
});


//查车辆类型
$app->get('/lorry_type',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('lorry_type');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'','lorry_type'=>$data1));
});



$app->run();
function localhost(){
    return connect();
}
?>