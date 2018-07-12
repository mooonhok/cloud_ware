<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/7/11
 * Time: 14:22
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/test',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    echo  json_encode(array("result" => "0", "desc" => "success"));
    set_time_limit(0);
    ob_end_clean();     //在循环输出前，要关闭输出缓冲区
    echo str_pad('',1024);     //浏览器在接受输出一定长度内容之前不会显示缓冲输出，这个长度值 IE是256，火狐是1024
    for ($i = 1; $i <= 1000; $i++) {
        echo json_encode(array("result" => "0", "desc" => "success", "tenant" => $i));
        ob_flush();
        flush();
        sleep(1);
    }
});


$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>
