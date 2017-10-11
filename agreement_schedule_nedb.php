<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/8
 * Time: 15:46
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addAgreementSchedule',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $agreement_id = $body->agreement_id;
    $scheduling_id = $body->scheduling_id;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($agreement_id!=null||$agreement_id!=''){
            if($scheduling_id!=null||$scheduling_id!=''){
                $array['tenant_id']=$tenant_id;
                $array['exist']=0;
                $insertStatement = $database->insert(array_keys($array))
                    ->into('agreement_schedule')
                    ->values(array_values($array));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少合同id"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});


$app->get('/getAgreementSchedules0',function()use($app) {
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