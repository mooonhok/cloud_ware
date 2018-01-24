<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 16:05
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getTenant1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id',"=",$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->leftJoin('customer','customer.customer_id','=','tenant.contact_id')
                ->where('tenant.tenant_id','=',$tenant_id)
                ->where('customer.tenant_id','=',$tenant_id)
                ->where('tenant.exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data1['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data1['city']=$data2;
            $data1['province']=$data3;
            echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data1));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"租户不存在",));
        }
    }else{
        echo  json_encode(array("result"=>"2","desc"=>"租户id不存在"));
    }

});

$app->put('/alterTenant1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $is_join=$body->is_join;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_join'=>$is_join))
            ->table('tenant')
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $affectedRows = $updateStatement->execute();
        echo  json_encode(array("result"=>"0","desc"=>"sucess"));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"租户id不存在"));
    }
});

$app->run();

function localhost(){
    return connect();
}

?>