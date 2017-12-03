<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 14:11
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addScheduling',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $send_city_id = $body->send_city_id;
    $receiver_id = $body->receiver_id;
    $receive_city_id = $body->receive_city_id;
    $lorry_id = $body->lorry_id;
    $array = array();
    foreach ($body as $key => $value) {
            $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            if($send_city_id!=null||$send_city_id!=''){
                if($receiver_id!=null||$receiver_id!=''){
                    if($receive_city_id!=null||$receive_city_id!=''){
                        if($lorry_id!=null||$lorry_id!=''){
                            $array['tenant_id']=$tenant_id;
                            $array['exist']=0;
                            $array['is_load']=0;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('scheduling')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "1", "desc" => "缺少车辆id"));
                        }
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "缺少收货城市id"));
                    }
                }else{
                    echo json_encode(array("result" => "3", "desc" => "缺少收货人id"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "缺少发货城市id"));
            }
        }else{
            echo json_encode(array("result" => "5", "desc" => "缺少调度id"));
        }
    }else{
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
           for($i=0;$i<count($data);$i++){
               $selectStatement = $database->select()
                   ->from('customer')
                   ->where('tenant_id', '=', $tenant_id)
                   ->where('customer_id', '=', $data[$i]['receiver_id']);
               $stmt = $selectStatement->execute();
               $data1 = $stmt->fetch();
               $selectStatement = $database->select()
                   ->from('city')
                   ->where('id', '=', $data[$i]['send_city_id']);
               $stmt = $selectStatement->execute();
               $data2 = $stmt->fetch();
               $selectStatement = $database->select()
                   ->from('city')
                   ->where('id', '=', $data[$i]['receive_city_id']);
               $stmt = $selectStatement->execute();
               $data3 = $stmt->fetch();
               $selectStatement = $database->select()
                   ->from('lorry')
                   ->where('tenant_id', '=', $tenant_id)
                   ->where('lorry_id', '=', $data[$i]['lorry_id']);
               $stmt = $selectStatement->execute();
               $data4 = $stmt->fetch();
               $data[$i]['receiver']=$data1;
               $data[$i]['send_city']=$data2;
               $data[$i]['receive_city']=$data3;
               $data[$i]['lorry']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getScheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $scheduling_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$i]['receiver']=$data1;
                $data[$i]['send_city']=$data2;
                $data[$i]['receive_city']=$data3;
                $data[$i]['lorry']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "调度id为空"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
                ->where('scheduling.exist', '=', 0)
                ->where('scheduling.scheduling_status','=',1)
                ->where('scheduling.tenant_id', '=', $tenant_id)
                ->where('lorry.tenant_id', '=', $tenant_id)
                ->orderBy('scheduling.scheduling_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
        }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('scheduling.exist', '=', 0)
            ->where('scheduling.is_contract','=',1)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('is_show','=',0)
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings5',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 1)
            ->where('is_alter','=',2)
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/limitSchedulings0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_id','DESC')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $scheduling_comment=$body->scheduling_comment;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('scheduling_comment'=>$scheduling_comment))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_alter=$body->is_alter;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_alter'=>$is_alter))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        if($affectedRows>0){
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "2", "desc" => "success"));
        }

    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/deleteScheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id = $body->scheduling_id;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>1))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//$app->delete('/deleteScheduling',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $database = localhost();
//    $scheduling_id= $app->request->get('scheduling_id');
//    if($tenant_id!=null||$tenant_id!=''){
//        $deleteStatement = $database->delete()
//            ->from('scheduling')
//            ->where('scheduling_id', '=', $scheduling_id)
//            ->where('tenant_id','=',$tenant_id)
//            ->where('exist','=',0);
//        echo json_encode(array("result" => "0", "desc" => "success"));
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});

$app->put('/alterScheduling2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $scheduling_status=$body->scheduling_status;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('scheduling_status'=>$scheduling_status))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_show=$body->is_show;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_show'=>$is_show))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_contract=$body->is_contract;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_contract'=>$is_contract))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling5',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $scheduling_datetime=$body->scheduling_datetime;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('scheduling_datetime'=>$scheduling_datetime))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling6',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $send_city_id=$body->send_city_id;
    $receive_city_id=$body->receive_city_id;
    $lorry_id=$body->lorry_id;
    $receiver_id=$body->receiver_id;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($send_city_id!=null||$send_city_id!=''){
            if($receive_city_id!=null||$receive_city_id!=''){
                if($lorry_id!=null||$lorry_id!=''){
                    if($receiver_id!=null||$receiver_id!=''){
                        if($scheduling_id!=null||$scheduling_id!=''){
                            $array['is_alter']=0;
                            $array['exist']=0;
                            $updateStatement = $database->update($array)
                                ->table('scheduling')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('scheduling_id','=',$scheduling_id);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
                        }
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "缺少收货人id"));
                    }
                }else{
                    echo json_encode(array("result" => "3", "desc" => "缺少车辆id"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "缺少收货城市id"));
            }
        }else{
            echo json_encode(array("result" => "5", "desc" => "缺少发货城市id"));
        }
    }else{
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduling7',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $sure_img=$body->sure_img;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
           if($scheduling_id!=null||$scheduling_id!=''){
                            $array['scheduling_status']=5;
                            $updateStatement = $database->update($array)
                                ->table('scheduling')
                                ->where('scheduling_id','=',$scheduling_id);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                  echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
          }
});

$app->put('/alterScheduling8',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_scan=$body->is_scan;
        if($scheduling_id!=null||$scheduling_id!=''){
            $updateStatement = $database->update(array('is_scan'=>$is_scan))
                ->table('scheduling')
                ->where('scheduling_id','=',$scheduling_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
        }
});

$app->put('/alterScheduling9',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_load=$body->is_load;
    if($scheduling_id!=null||$scheduling_id!=''){
        $updateStatement = $database->update(array('is_load'=>$is_load))
            ->table('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_id','=',$scheduling_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
    }
});

//$app->put('/alterSchedulings0',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $database = localhost();
//    if($tenant_id!=null||$tenant_id!=''){
//        $updateStatement = $database->update(array('is_alter'=>1))
//            ->table('scheduling')
//            ->where('tenant_id','=',$tenant_id)
//            ->where('is_alter','=',2)
//            ->where('exist','=',0);
//        $affectedRows = $updateStatement->execute();
//        echo json_encode(array("result" => "0", "desc" => "success"));
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});

$app->put('/alterSchedulings0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_alter'=>1,'exist'=>0))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('is_alter','=',2)
            ->where('exist','=',1);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//根据清单id或发站模糊查询清单数
$app->get('/getSchedulings_scheduling_id_or_sendercity',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $id_sendcity=$app->request->get('id_sendcity');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->leftJoin('city','city.id','=','scheduling.send_city_id')
                ->where('scheduling.exist', '=', 0)
                ->where('scheduling.tenant_id', '=', $tenant_id)
                ->whereLike('scheduling.scheduling_id','%'.$id_sendcity.'%')
                ->orWhereLike('city.name','=','%'.$id_sendcity.'%');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$i]['receiver']=$data1;
                $data[$i]['send_city']=$data2;
                $data[$i]['receive_city']=$data3;
                $data[$i]['lorry']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "租户id为空"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>