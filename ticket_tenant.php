<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/11
 * Time: 15:15
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getTicketTenants0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database = localhost();
        $selectStatement = $database->select()
            ->from('ticket_tenant')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_tenants'=>$data2));
});

$app->get('/getTicketTenant0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $company_id=$app->request->get('company_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_tenant')
        ->where('tenant_id','=',$tenant_id)
        ->where('company_id','=',$company_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket_tenant'=>$data));
});

$app->post('/addTicketTenant',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $body = $app->request->getBody();
    $file_url = file_url();
    $body = json_decode($body);
    $database = localhost();
    $tenant_id = $app->request->params('tenant_id');
    $company_id = $app->request->params('company_id');
    $company_name = $app->request->params('name');
    $business = $app->request->params('business');
    $business_img=null;
//    $business_img=$body->business_img;
    if (isset($_FILES["business_img"]["name"])) {
    $name = $_FILES["business_img"]["name"];
        if($name){
            $name1=substr(strrchr($name, '.'), 1);
            $shijian = time();
            $name1 = $shijian .".". $name1;
            move_uploaded_file($_FILES["business_img"]["tmp_name"], "/files/business/" . $name1);
            $business_img=$file_url."business/".$name1;
        }
   }
    if($tenant_id!=null||$tenant_id!=null){
        if($company_id!=null||$company_id!=""){
            $selectStatement = $database->select()
                ->from('ticket_tenant')
                ->where('tenant_id','=',$tenant_id)
                ->where('company_id','=',$company_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data==null){
                $selectStatement = $database->select()
                    ->from('ticket_tenant');
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                $insertStatement = $database->insert(array('id','tenant_id','company_id','name','business','business_img','is_check','commit_time'))
                    ->into('ticket_tenant')
                    ->values(array(count($data2)+1,$tenant_id,$company_id,$company_name,$business,$business_img,0,date('Y-m-d H:i:s',time())));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"该纪录已存在"));
            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"缺少公司id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
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