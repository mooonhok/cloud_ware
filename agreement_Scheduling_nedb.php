<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 11:00
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getAgreementSchedulings0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement_schedule')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getAgreementSchedules1',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement_schedule')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>