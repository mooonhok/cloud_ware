<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/26
 * Time: 11:18
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getMiniRecord',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $openid=$app->request->get('openid');
    if($openid!=null||$openid!=""){
        $selectStatement = $database->select()
            ->from('mini_record')
            ->where('openid','=',$openid)
            ->orderBy('login_time','DESC')
            ->limit(1);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data1));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少openid"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>