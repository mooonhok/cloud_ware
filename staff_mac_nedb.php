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
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $staff_id=$body->staff_id;
    $tenant_id=$body->tenant_id;
    $login_time=$body->login_time;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                if($login_time!=null||$login_time!=''){
                    $insertStatement = $database->insert(array_keys($array))
                        ->into('staff_mac')
                        ->values(array_values($array));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'缺少登陆时间'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'缺少员工id'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'缺少mac'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'缺少租户id'));
    }
});


$app->get('/getStaffMac0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $mac=$app->request->get('mac');
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($mac!=null||$mac!=""){
            if($staff_id!=null||$staff_id!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('mac',"=",$mac)
                    ->where('staff_id',"=",$staff_id)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少staff_id"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少mac"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->delete('/deleteStaffMac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get("id");
    $database=localhost();
    if($id!=null||$id!=""){
        $deleteStatement = $database->delete()
            ->from('staff_mac')
            ->where('id', '=', $id);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少id"));
    }
});

$app->get('/getStaffMac1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
            if($staff_id!=null||$staff_id!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('staff_id',"=",$staff_id)
                    ->where('is_login',"=",1)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少staff_id"));
            }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->put('/alterStaffMac0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($id!=null||$id!=''){
                        $updateStatement = $database->update($array)
                            ->table('staff_mac')
                            ->where('id','=',$id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});

$app->put('/alterStaffMac1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $is_remember=$body->is_remember;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($id!=null||$id!=''){
        $updateStatement = $database->update(array('is_remember'=>$is_remember))
            ->table('staff_mac')
            ->where('id','=',$id);
        $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));

      }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});

$app->put('/alterStaffMac2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $tenant_id=$body->tenant_id;
    $staff_id=$body->staff_id;
    $is_login=$body->is_login;

    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                    $updateStatement = $database->update(array('is_login'=>$is_login))
                        ->table('staff_mac')
                        ->where('mac','=',$mac)
                        ->where('staff_id','=',$staff_id)
                        ->where('tenant_id','=',$tenant_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));

            }else{
                echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
            }

        }else{
            echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
        }

    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});

$app->get('/getStaffMacs0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $mac=$app->request->get('mac');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($mac!=null||$mac!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('mac',"=",$mac)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStatement = $database->select()
                        ->from('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$data1[$i]['staff_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $data1[$i]['staff']=$data2;
                }
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少mac"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->get("/getStaffMac2",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get("id");
    $database=localhost();
    if($id!=null||$id!=""){
            $selectStatement = $database->select()
                ->from('staff_mac')
                ->leftJoin("staff","staff.staff_id","=","staff_mac.staff_id")
                ->where('staff_mac.id',"=",$id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $data1[$i]['password']=chr($data1[$i]['password']);
            }
            echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少id"));
    }
});

$app->run();

function localhost(){
    return connect();
}

?>