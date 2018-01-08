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
    $xpi=3.14159265358979324*3000.0/180.0;
    if($long!=null||$long!=""){
         if($lat!=null||$lat!=""){
             $x=$long;
             $y = $lat;
             $z = sqrt($x * $x + $y * $y) + 0.00002 * sin($y * self::$x_pi);
             $theta = atan2($y, $x) + 0.000003 * cos($x * self::$x_pi);
             $long=$z * cos($theta) + 0.0065;
             $lat = $z * sin($theta) + 0.006;
             echo json_encode(array("result"=>"0","desc"=>$long.','.$lat));
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

