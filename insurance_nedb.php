<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/24
 * Time: 17:50
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getInsurances0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('insurance')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success","insurances"=>$data1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户公司id为空"));
    }
});

$app->get('/getInsurances1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('insurance')
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success","insurances"=>$data1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户公司id为空"));
    }
});

$app->post('/addInsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $array=array();
    foreach($body as $key=>$value){
            $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        $array['exist']=0;
        $array['tenant_id']=$tenant_id;
        $insertStatement = $database->insert(array_keys($array))
            ->into('insurance')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户公司id为空","insurance_fee"=>''));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>
