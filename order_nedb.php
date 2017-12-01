<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 8:20
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addOrder', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $sender_id = $body->sender_id;
    $receiver_id=$body->receiver_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if ($sender_id != null || $sender_id != "") {
        if ($receiver_id != null || $receiver_id > 0) {
            if ($order_id != null || $order_id != "") {
                if($tenant_id!=null||$tenant_id!=''){
                    $array["is_schedule"]=0;
                    $array["is_transfer"]=0;
                    $array['exist']=0;
                    $array['tenant_id']=$tenant_id;
                    $insertStatement = $database->insert(array_keys($array))
                                            ->into('orders')
                                            ->values(array_values($array));
                     $insertId = $insertStatement->execute(false);
                     echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                    echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
                       }
                 } else {
                echo json_encode(array("result" => "3", "desc" => "缺少运单id"));
            }
         } else {
            echo json_encode(array("result" => "2", "desc" => "缺少收货人id"));
        }
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少发货人id"));
    }
});


$app->get('/getOrder', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $pay_method= $app->request->get('pay_method');
    $order_cost= $app->request->get("order_cost");
    $order_datetime1= $app->request->get("order_datetime1");
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        if ($pay_method != null||$pay_method!='') {
            if ($order_cost != null || $order_cost != '') {
                if($order_datetime1!=null||$order_datetime1!=''){
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('pay_method', '=', $pay_method)
                        ->where('order_cost', '=', $order_cost)
                        ->where('order_datetime1', '=', $order_datetime1)
                        ->where('exist', "=", 0);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetch();
                    echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
                }else{
                    echo json_encode(array("result" => "1", "desc" => "时间不存在", "orders" => ""));
                }
            } else {
                echo json_encode(array("result" => "2", "desc" => "运单费不存在", "orders" => ""));
            }
        } else {
            echo json_encode(array("result" => "3", "desc" => "付款方式不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "orders" => ""));
    }
});


$app->get('/getOrder1', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $order_id= $app->request->get('order_id');
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        if ($order_id != null||$order_id!='') {
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('exist', "=", 0)
                        ->where('order_id', '=', $order_id);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetch();
                    echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
        } else {
            echo json_encode(array("result" => "3", "desc" => "缺少运单id", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->get('/getOrder2', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $order_id= $app->request->get('order_id');
    if ($tenant_id != null || $tenant_id != "") {
        if ($order_id != null||$order_id!='') {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0)
                ->where('order_id', '=', $order_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){

                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['sender_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('inventory_loc_id', '=', $data[$i]['inventory_loc_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('pickup')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('pickup_id', '=', $data[$i]['pickup_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_id', '=', $data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('scheduling_id', '=', $data5['scheduling_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $data[$i]['sender']=$data1;
                $data[$i]['receiver']=$data2;
                $data[$i]['inventory_loc']=$data3;
                $data[$i]['pickup']=$data4;
                $data[$i]['scheduling']=$data6;
            }

            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
        } else {
            echo json_encode(array("result" => "3", "desc" => "缺少运单id"));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
    }
});

$app->get('/getOrders0', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNull('wx_openid')->orWhere('wx_openid','=','')
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->get('/getOrders1', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $order_id= $app->request->get('order_id');
    if ($order_id != null || $order_id != "") {
        $selectStatement = $database->select()
            ->from('orders')
            ->where('order_id', '=', $order_id)
            ->orderBy('order_datetime1');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data1['from_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $data[$i]['tenant']=$data1;
            $data[$i]['from_city']=$data2;
        }
        echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
    }
});



$app->put('/alterOrder0', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $inventory_type = $body->inventory_type;
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update(array('inventory_type'=>$inventory_type))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder1', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $database = localhost();
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if ($tenant_id != null || $tenant_id != "") {
        if($order_id!=null||$order_id!=''){
                $updateStatement = $database->update($array)
                    ->table('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0)
                    ->where('order_id','=',$order_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少运单id", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id", "orders" => ""));
    }
});

//$app->put('/alterOrder1', function () use ($app) {
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $body = $app->request->getBody();
//    $body = json_decode($body);
//    $order_id = $body->order_id;
//    $inventory_type = $body->inventory_type;
//    $inventory_loc_id=$body->inventory_loc_id;
//    $database = localhost();
//    if ($tenant_id != null || $tenant_id != "") {
//        if($order_id!=null||$order_id!=''){
//            if($inventory_loc_id!=null||$inventory_loc_id!=''){
//                $updateStatement = $database->update(array('inventory_type'=>$inventory_type,'inventory_loc_id'=>$inventory_loc_id))
//                    ->table('orders')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('order_id','=',$order_id);
//                $affectedRows = $updateStatement->execute();
//                echo json_encode(array("result" => "0", "desc" => "success"));
//            }else {
//                echo json_encode(array("result" => "1", "desc" => "库位id", "orders" => ""));
//            }
//        }else{
//            echo json_encode(array("result" => "2", "desc" => "缺少运单id", "orders" => ""));
//        }
//    } else {
//        echo json_encode(array("result" => "3", "desc" => "缺少租户id", "orders" => ""));
//    }
//});

$app->put('/alterOrder2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $is_schedule = $body->is_schedule;
    $is_transfer=$body->is_transfer;
    if($tenant_id!=null||$tenant_id!=''){
       if($order_id!=null||$order_id!=''){
           $updateStatement = $database->update(array('is_schedule'=>$is_schedule,'is_transfer'=>$is_transfer))
               ->table('orders')
               ->where('tenant_id','=',$tenant_id)
               ->where('exist','=',0)
               ->where('order_id','=',$order_id);
           $affectedRows = $updateStatement->execute();
           echo json_encode(array("result" => "0", "desc" => "success"));
       }else{
           echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
       }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrders0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $is_schedule = $body->is_schedule;
    $is_transfer=$body->is_transfer;
    if($tenant_id!=null||$tenant_id!=''){
            $updateStatement = $database->update(array('is_transfer'=>$is_transfer,'is_schedule'=>$is_schedule))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('is_schedule','=',1);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
});

$app->put('/alterOrder3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $exception_id=$body->exception_id;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exception_id'=>$exception_id,'order_status'=>5))
            ->table('orders')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',0)
            ->where('order_id','=',$order_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $order_comment=$body->order_comment;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update(array('order_comment'=>$order_comment))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder5',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});


$app->put('/alterOrder6',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder7',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $array['order_status']=2;
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder8',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $array['order_status']=3;
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder9',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder10',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $array['order_status']=3;
            $updateStatement = $database->update($array)
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder11',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $is_schedule = $body->is_schedule;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update(array('is_schedule'=>$is_schedule))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->put('/alterOrder12',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $pickup_id = $body->pickup_id;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            if($pickup_id!=null||$pickup_id!=''){
                $updateStatement = $database->update(array('pickup_id'=>$pickup_id))
                    ->table('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0)
                    ->where('order_id','=',$order_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
            }
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id", "orders" => ""));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }
});

$app->run();

function localhost()
{
    return connect();
}

?>