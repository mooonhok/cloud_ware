<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 11:31
 */

require 'Slim/Slim.php';
require 'connect.php';


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
        echo json_encode(array('result'=>'0','desc'=>'','tenant'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'尚未有数据','tenant'=>''));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>
