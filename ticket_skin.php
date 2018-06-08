<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/8
 * Time: 17:15
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getTicketSkins',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
//    $lorry_id=$app->request->get('lorry_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_skin')
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    $array=array();
    $array1=array();
    for($i=0;$i<count($data2);$i++){
        $num=$i+1;
        $array1['num']=$num;
        $array1["ticket_tenant"]=$data2[$i];
        $array1['count']=count($data2);
        array_push($array,$array1);
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_skins'=>$array));
});

$app->get('/getTicketSkin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_skin')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_skin'=>$data2));
});





$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>