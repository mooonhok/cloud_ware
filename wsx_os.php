<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/16
 * Time: 16:21
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getProvinces',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('province')
        ->orderBy('id');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});



$app->get('/getCitys0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('city');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
});


$app->get('/getGoodsPackages',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('goods_package');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'','goods_package'=>$data1));
});


$app->get('/getCitys1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $pid=$app->request->get('province_id');
    if($pid!=null||$pid!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid','=',$pid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"省份的id为空"));
    }
});

$app->get('/getCity1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $city_id=$app->request->get('city_id');
    if($city_id!=null||$city_id!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('id','=',$city_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"城市id为空"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>