<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8
 * Time: 17:31
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/getcityname',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $long=$body->long;
    $lat=$body->lat;
    if($long!=null||$long!=""){
         if($lat!=null||$lat!=""){
             $x = (double)$lat ;
             $y = (double)$long;
             $x_pi = 3.14159265358979324;
             $z = sqrt($x * $x+$y * $y) + 0.00002 * sin($y * $x_pi);

             $theta = atan2($y,$x) + 0.000003 * cos($x*$x_pi);

             $gb = number_format($z * cos($theta) + 0.0065,6);
             $ga = number_format($z * sin($theta) + 0.006,6);
             echo json_encode(array("result"=>"0","desc"=>$ga.','.$gb));
         }else{
             echo json_encode(array("result"=>"1","desc"=>"缺少坐标"));
         }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少坐标"));
    }
});



$app->run();

function localhost(){
    return connect();
}

?>

