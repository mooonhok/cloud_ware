<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 10:10
 */
require 'Slim/Slim.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/staff',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $username=$body->username;
    $name=$body->name;
    $telephone=$body->telephone;
    $position=$body->position;
    $staff_status=$body->staff_status;
    $permissions=$body->permissions;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if(($tenant_id!=null||$tenant_id!='')&&($username!=null||$username!='')&&($name!=null||$name!='')&&($telephone!=null||$telephone!='')&&($position!=null||$position!='')&&($staff_status!=null||$staff_status!='')&&($permissions!=null||$permissions!='')){
        $selectStatement = $database->select()
            ->from('staff')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            $staff_id=count($data)+1001;
        }else{
            $staff_id=1001;
        }
        $array['tenant_id']=$tenant_id;
        $array['staff_id']=$staff_id;
        $array['exist']=0;
        $insertStatement = $database->insert(array_keys($array))
            ->into('staff')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'信息不全'));
    }
});

$app->put('/staff',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $staff_id=$body->staff_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if(($tenant_id!=null||$tenant_id!='')&&($staff_id!=null||$staff_id!='')){
        $selectStatement = $database->select()
            ->from('staff')
            ->where('staff_id','=',$staff_id)
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $updateStatement = $database->update($array)
                ->table('staff')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('staff_id','=',$staff_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'该员工不存在'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'信息不全'));
    }
});

$app->put('/staff',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $staff_id=$body->staff_id;
    $password=$body->password;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if(($tenant_id!=null||$tenant_id!='')&&($staff_id!=null||$staff_id!='')&&($password!=null||$password!='')){
        $selectStatement = $database->select()
            ->from('staff')
            ->where('staff_id','=',$staff_id)
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $updateStatement = $database->update($array)
                ->table('staff')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('staff_id','=',$staff_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'该员工不存在'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'信息不全'));
    }
});

$app->get('/staff',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    if($tenant_id!=null||$tenant_id!=""){
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
        echo json_encode(array("result"=>"1","desc"=>"信息不全","staff"=>""));
    }
});

$app->delete('/staff',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $staff_id=$app->request->get('staff_id');
    if(($tenant_id!=null||$tenant_id!='')&&($staff_id!=null||$staff_id!='')){
        $selectStatement = $database->select()
            ->from('staff')
            ->where('tenant_id','=',$tenant_id)
            ->where('staff_id','=',$staff_id)
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $updateStatement = $database->update(array('exist'=>1))
                ->table('staff')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('staff_id','=',$staff_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"员工不存在"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"信息不全"));
    }
});

$app->run();
?>