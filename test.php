<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 12:37
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/test',function()use($app){
    $num=rand(100,1000);
    echo json_encode(array('result' => '1', 'desc' => '业','sales'=>$num));
});
$app->run();

function localhost(){
    return connect();
}
?>
