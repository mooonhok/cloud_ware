<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 13:28
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
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

//获得所有city
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

//根据名字获得城市
$app->get('/getCity',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $name=$app->request->get('city_name');
    if($name!=null||$name!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('name','=',$name);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"城市名字为空"));
    }
});

//根据名字获得城市
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