<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 10:08
 */
require 'Slim/Slim.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
$app->post('/lorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $plate_number=$body->plate_number;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $driver_identycard=$body->driver_identycard;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $array['tenant_id']=$tenant_id;
    if(($tenant_id!=''||$tenant_id!=null)&&($plate_number!=null||$plate_number!='')&&($driver_name!=null||$driver_name!='')&&($driver_phone!=''||$driver_phone!=null)&&($driver_identycard!=''||$driver_identycard!=null)){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            $lorry_id=count($data)+100000001;
        }else{
            $lorry_id=100000001;
        }
        $array['lorry_id']=$lorry_id;
        $array['exist']=0;
        $insertStatement = $database->insert(array_keys($array))
            ->into('lorry')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全"));
    }
});

$app->put('/lorry',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $lorry_id=$body->lorry_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if(($tenant_id!=''||$tenant_id!=null)&&($lorry_id!=''||$lorry_id!=null)){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            $updateStatement = $database->update($array)
                ->table('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('lorry_id','=',$lorry_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"车辆不存在"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"信息不全"));
    }
});

$app->get('/lorry',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $page=$app->request->get("page");
    $per_page=$app->request->get("per_page");
    if(($tenant_id!=''||$tenant_id!=null)){
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","lorries"=>$data));
        }else{
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->limit((int)$per_page,(int)$per_page*(int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result"=>"0","desc"=>"success","lorries"=>$data));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全","lorries"=>""));
    }
});

$app->delete('/lorry',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $lorry_id=$app->request->get('lorryid');
    if(($tenant_id!=null||$tenant_id!='')&&($lorry_id!=""||$lorry_id!=null)){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist',"=",0)
            ->where('lorry_id','=',$lorry_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('schedule_id','=',$data['schedule_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('order_id','=',$data1['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            if($data1['order_status']==0||$data1['order_status']==5){
                $updateStatement = $database->update(array('exist' => 1))
                    ->table('lorry')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('lorry_id','=',$lorry_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"车辆在送货途中"));
            }

        }else{
            echo json_encode(array("result"=>"2","desc"=>'车辆不存在'));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"信息不全"));
    }
});

$app->run();
?>