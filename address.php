<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/12
 * Time: 17:15
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addAddress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $name=$body->name;
    $phone=$body->phone;
    $province_id=$body->province_id;
    $city_id=$body->city_id;
    $address=$body->address;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('address')
            ->where('tenant_id','=',$tenant_id)
            ->where('phone','=',$phone)
            ->where('address','=',$address)
            ->where('city_id','=',$city_id)
            ->where('province_id','=',$province_id)
            ->where('name','=',$name);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data==null){
        $insertStatement = $database->insert(array_keys($array))
            ->into('address')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "2", "desc" => "该邮寄地址已经存在"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/limitAddresses',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant_id");
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('address')
            ->where('tenant_id','=',$tenant_id)
            ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
           $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data[$x]['city_id']);
        $stmt = $selectStatement->execute();
        $data7 = $stmt->fetch();
          $selectStatement = $database->select()
             ->from('province')
             ->where('id', '=', $data[$x]['province_id']);
         $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$x]['cityname']=$data7['name'];
            $data[$x]['provincename']=$data9['name'];
        }
        echo json_encode(array("result"=>"0","desc"=>"success","addresses"=>$data));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->get('/getAddresses',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('address')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result"=>"0","desc"=>"success","addresses"=>$data));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});


$app->delete('/deleteAddress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get("id");
    $database=localhost();
    if($id!=null||$id!=""){
        $selectStatement = $database->select()
            ->from('address')
            ->where('id','=',$id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
        $deleteStatement = $database->delete()
            ->from('address')
            ->where('id','=',$id);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"该邮寄地址不存在"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少id"));
    }
});


$app->get('/getAddress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    if($id!=null||$id!=""){
        $selectStatement = $database->select()
            ->from('address')
            ->where('id','=',$id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data['city_id']);
//            $stmt = $selectStatement->execute();
//            $data7 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data['province_id']);
//            $stmt = $selectStatement->execute();
//            $data9 = $stmt->fetch();
//            $data['cityname']=$data7['name'];
//            $data['provincename']=$data9['name'];
        echo json_encode(array("result"=>"0","desc"=>"success","address"=>$data));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->options('/alterAddress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});



$app->put('/alterAddress',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $tenant_id=$body->tenant_id;
    $name=$body->name;
    $phone=$body->phone;
    $province_id=$body->province_id;
    $city_id=$body->city_id;
    $address=$body->address;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($id!=''||$id!=null){
        $selectStatement = $database->select()
            ->from('address')
            ->where('id','=',$id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('address')
                ->where('tenant_id','=',$tenant_id)
                ->where('phone','=',$phone)
                ->where('address','=',$address)
                ->where('city_id','=',$city_id)
                ->where('province_id','=',$province_id)
                ->where('name','=',$name);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            if($data2==null){
            $updateStatement = $database->update($array)
                ->table('address')
                ->where('id','=',$id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "2", "desc" => "该邮寄地址已经存在"));
            }
        }else{
            echo json_encode(array("result"=>"1","desc"=>"该邮寄地址不存在"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
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