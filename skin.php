<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 9:50
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->post('/addSkin',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $skin_id=$app->request->params('skin_id');
    $name=$app->request->params('name');
    date_default_timezone_set("PRC");
    $shijian=time();
    $img= $_FILES["img"]["name"];
    $img=iconv("UTF-8","UTF-8", $img);
    $img=$shijian.$img;
    move_uploaded_file($_FILES["img"]["tmp_name"], 'skin/'.$img);
    $insertStatement = $database->insert(array('skin_id','img','name','used_num','exist'))
        ->into('skin')
        ->values(array($skin_id,$img,$name,0,0));
    $insertId = $insertStatement->execute(false);
});

$app->get('/getSkins0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('skin');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","skins"=>$data));
});

$app->get('/getSkins1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('skin')
        ->where('exist','=',0)
        ->orderBy('skin_id');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","skins"=>$data));
});

$app->get('/getSkin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $skin_id=$app->request->get('skin_id');
    $selectStatement = $database->select()
        ->from('skin')
        ->where('skin_id','=',$skin_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","skin"=>$data));
});

$app->run();
function localhost(){
    return connect();
}
?>