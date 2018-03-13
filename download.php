<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 9:59
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getDownload',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament=$database->select()
        ->from('download');
    $stmt=$selectStament->execute();
    $data=$stmt->fetch();
    echo json_encode(array('result' => '0', 'desc' => 'success', 'download'=>$data));
});




$app->run();
function localhost(){
    return connect();
}
?>