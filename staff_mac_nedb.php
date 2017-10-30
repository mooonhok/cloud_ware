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
$app->post('/addStaffMac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $staff_id=$body->staff_id;
    $tenant_id=$body->tenant_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                $insertStatement = $database->insert(array_keys($array))
                    ->into('staff_mac')
                    ->values(array_values($array));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'缺少员工id'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少mac'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'缺少租户id'));
    }
});


$app->get('/getStaffMac0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $mac=$app->request->get('mac');
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            if($page==null||$per_page==null){
                $selectStatement = $database->select()
                    ->from('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo  json_encode(array("result"=>"0","desc"=>"success","staff"=>$data));
            }else{
                $selectStatement = $database->select()
                    ->from('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->limit((int)$per_page,(int)$per_page*(int)$page);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","staff"=>$data));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'该租户不存在'));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id","staff"=>""));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>