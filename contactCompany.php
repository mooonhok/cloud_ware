<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/26
 * Time: 14:37
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addCompany',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }

    $insertStatement = $database->insert(array_keys($array))
                ->into('contact_company')
                ->values(array_values($array));
    $insertId = $insertStatement->execute(false);
    if($insertId){
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "信息提交不成功"));
    }


});



$app->run();

function localhost(){
    return connect();
}
?>