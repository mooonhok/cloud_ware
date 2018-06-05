<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 13:46
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/testone',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    date_default_timezone_set("PRC");
    $time1=time();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(6,8))
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $time2=time();
        $num=$time2-$time1;
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$num));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});




$app->get('/testtwo',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $array=array();
    date_default_timezone_set("PRC");
    $time1=time();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
//            ->whereIn('scheduling_status',array(6,8))
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
            if($data[$x]['scheduling_status']==6||$data[$x]['scheduling_status']==6){
                array_push($array,$data[$x]);
            }
        }
        $time2=time();
        $num=$time2-$time1;
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$num));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
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