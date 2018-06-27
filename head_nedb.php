<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/30
 * Time: 15:46
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getHeads',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $type=$app->request->get('type');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('head')
        ->where('type','=',$type)
        ->orderBy('id');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'0','desc'=>'success','heads'=>$data1));
});



$app->run();

function localhost(){
    return connect();
}
?>