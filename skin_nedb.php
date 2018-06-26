<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 9:50
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->post('/addSkin',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $file_url=file_url();
    $skin_id=$app->request->params('skin_id');
    $name=$app->request->params('name');
    date_default_timezone_set("PRC");
    $shijian=time();
    $img= $_FILES["img"]["name"];
//    $img1=iconv("UTF-8","gb2312", $img);
    $img1=substr(strrchr($img, '.'), 1);
    $img1=$shijian.'.'.$img1;
    move_uploaded_file($_FILES["img"]["tmp_name"], '/files/skin/'.$img1);
    $img=$file_url."skin/".$img1;
    $insertStatement = $database->insert(array('skin_id','img','name','used_num','exist'))
        ->into('skin')
        ->values(array($skin_id,$img,$name,0,0));
    $insertId = $insertStatement->execute(false);
});


$app->get('/getSkins',function()use($app){
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
function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>