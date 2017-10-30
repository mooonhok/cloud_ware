<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 14:17
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addstaff_mac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $staff_id=$body->staff_id;
    $is_login=$body->is_login;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){

            }else{
                echo json_encode(array('result'=>'8','desc'=>'缺少租户id'));
            }
        }else{
            echo json_encode(array('result'=>'8','desc'=>'缺少租户id'));
        }
    }else{
        echo json_encode(array('result'=>'8','desc'=>'缺少租户id'));
    }
});



$app->run();

function localhost(){
    return connect();
}
?>