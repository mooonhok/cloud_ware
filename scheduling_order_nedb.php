<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/12
 * Time: 16:23
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getSchedulingOrders0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;

        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('lorry.lorry_id', '=', $lorry_id)
            ->where('scheduling.scheduling_status', '=', 1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $ii=$app->request->get('i');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.is_show', '=',0)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){

            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data10['sender_id']);
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data11['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data10['receiver_id']);
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data12['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data14 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
            $data[$i]['order_sender']=$data11;
            $data[$i]['order_receiver']=$data12;
            $data[$i]['order_sender_city']=$data13;
            $data[$i]['order_receiver_city']=$data14;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'i'=>$ii));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulingOrders',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($offset!=null||$offset!=''){
            if($size!=null||$size!=''){
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                    ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
                    ->where('schedule_order.tenant_id', '=', $tenant_id)
                    ->where('scheduling.tenant_id', '=', $tenant_id)
                    ->where('orders.tenant_id', '=', $tenant_id)
                    ->where('schedule_order.exist', '=', 0)
                    ->where('scheduling.exist', '=', 0)
                    ->where('orders.exist', '=', 0)
                    ->orderBy('scheduling.scheduling_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                for($i=0;$i<count($data);$i++){
                    $selectStatement = $database->select()
                        ->from('lorry')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('lorry_id', '=', $data[$i]['lorry_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('goods')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('order_id', '=', $data[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('goods_package')
                        ->where('goods_package_id', '=', $data2['goods_package_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('scheduling')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('customer_id', '=', $data3['receiver_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data[$i]['send_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data[$i]['receive_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $data[$i]['lorry']=$data1;
                    $data[$i]['goods']=$data2;
                    $data[$i]['goods']['goods_package']=$data5;
                    $data[$i]['receiver']=$data4;
                    $data[$i]['sender_city']=$data6;
                    $data[$i]['sender_province']=$data8;
                    $data[$i]['receiver_city']=$data7;
                    $data[$i]['receiver_province']=$data9;
                }
                echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少size"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少偏移量"));
        }

    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
//            ->where('scheduling.scheduling_status', '=',2)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.inventory_type', '!=', 4)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders5',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $i=$app->request->get('i');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.scheduling_status', '=',2)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'i'=>$i));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders6',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.is_contract', '=',1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getSchedulingOrders7',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $i=$app->request->get('i');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.is_contract', '=',1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'i'=>$i));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//$app->get('/getSchedulingOrderList0',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $database = localhost();
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $lorry_id=$app->request->get('lorry_id');
//    if($tenant_id!=null||$tenant_id!=''){
//        $selectStatement = $database->select()
//            ->from('schedule_order')
//            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
//            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
//            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
//            ->where('schedule_order.tenant_id', '=', $tenant_id)
//            ->where('scheduling.tenant_id', '=', $tenant_id)
//            ->where('orders.tenant_id', '=', $tenant_id)
//            ->where('lorry.tenant_id', '=', $tenant_id)
//            ->where('lorry.lorry_id', '=', $lorry_id)
//            ->where('scheduling.scheduling_status', '=', 1)
//            ->where('schedule_order.exist', '=', 0)
//            ->where('scheduling.exist', '=', 0)
//            ->where('orders.exist', '=', 0);
//        $stmt = $selectStatement->execute();
//        $data = $stmt->fetchAll();
//        for($i=0;$i<count($data);$i++){
//            $selectStatement = $database->select()
//                ->from('lorry')
//                ->where('tenant_id', '=', $tenant_id)
//                ->where('lorry_id', '=', $data[$i]['lorry_id']);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('goods')
//                ->where('tenant_id', '=', $tenant_id)
//                ->where('order_id', '=', $data[$i]['order_id']);
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('goods_package')
//                ->where('goods_package_id', '=', $data2['goods_package_id']);
//            $stmt = $selectStatement->execute();
//            $data5 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('scheduling')
//                ->where('tenant_id', '=', $tenant_id)
//                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
//            $stmt = $selectStatement->execute();
//            $data3 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('customer')
//                ->where('tenant_id', '=', $tenant_id)
//                ->where('customer_id', '=', $data3['receiver_id']);
//            $stmt = $selectStatement->execute();
//            $data4 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data[$i]['send_city_id']);
//            $stmt = $selectStatement->execute();
//            $data6 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data[$i]['receive_city_id']);
//            $stmt = $selectStatement->execute();
//            $data7 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data6['pid']);
//            $stmt = $selectStatement->execute();
//            $data8 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data7['pid']);
//            $stmt = $selectStatement->execute();
//            $data9 = $stmt->fetch();
//            $data[$i]['lorry']=$data1;
//            $data[$i]['goods']=$data2;
//            $data[$i]['goods']['goods_package']=$data5;
//            $data[$i]['receiver']=$data4;
//            $data[$i]['sender_city']=$data6;
//            $data[$i]['sender_province']=$data8;
//            $data[$i]['receiver_city']=$data7;
//            $data[$i]['receiver_province']=$data9;
//        }
//        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});

$app->get('/getSchedulingOrderList0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('lorry.lorry_id', '=', $lorry_id)
//            ->where('scheduling.scheduling_status', '=', 2)
            ->where('scheduling.is_load','=',1)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrderList1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('lorry.lorry_id', '=', $lorry_id)
            ->where('scheduling.is_contract', '=', 1)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrderList2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('scheduling.is_contract', '=', 3)
            ->where('lorry.lorry_id', '=', $lorry_id)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrderList3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('scheduling.is_insurance', '=', 3)
            ->where('lorry.lorry_id', '=', $lorry_id)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders8',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            date_default_timezone_set("PRC");
            $changedatetime=date('Y-m-d H:i',$data3['change_datetime']);
            $data[$i]['change_datetime']=$changedatetime;
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data10=$data[$i]['sure_img'];
            if(substr($data[$i]['sure_img'],0,4)!='http'){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=',$data[$i]['sure_img']);
                $stmt = $selectStatement->execute();
                $data10 = $stmt->fetch();
            }
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data2['order_id']);
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data11['receiver_id']);
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data12['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data14 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data11['sender_id']);
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data13['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data15 = $stmt->fetch();
            $data[$i]['number']=$i+1;
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
            $data[$i]['sure_company']=$data10;
            $data[$i]['order_sender']=$data13;
            $data[$i]['order_receiver']=$data12;
            $data[$i]['order_sender_city']=$data15;
            $data[$i]['order_receiver_city']=$data14;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getSchedulingOrders12',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data17 = $stmt->fetchAll();
        for($x=0;$x<count($data17);$x++) {
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->join('orders', 'orders.order_id', '=', 'schedule_order.order_id', 'INNER')
                ->join('scheduling', 'scheduling.scheduling_id', '=', 'schedule_order.schedule_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'scheduling.lorry_id', 'INNER')
                ->where('schedule_order.tenant_id', '=', $data17[$x]['tenant_id'])
                ->where('scheduling.tenant_id', '=',$data17[$x]['tenant_id'])
                ->where('orders.tenant_id', '=', $data17[$x]['tenant_id'])
                ->where('lorry.tenant_id', '=', $data17[$x]['tenant_id'])
                ->where('scheduling.scheduling_id', '=', $scheduling_id)
                ->where('schedule_order.exist', '=', 0)
                ->where('scheduling.exist', '=', 0)
                ->where('orders.exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for ($i = 0; $i < count($data); $i++) {
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('order_id', '=', $data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('goods_package')
                    ->where('goods_package_id', '=', $data2['goods_package_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                date_default_timezone_set("PRC");
                $changedatetime = date('Y-m-d H:i', $data3['change_datetime']);
                $data[$i]['change_datetime'] = $changedatetime;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('customer_id', '=', $data3['receiver_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data6['pid']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data7['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $data10 = $data[$i]['sure_img'];
                if (substr($data[$i]['sure_img'], 0, 4) != 'http') {
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->where('tenant_id', '=', $data[$i]['sure_img']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                }
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('order_id', '=', $data2['order_id']);
                $stmt = $selectStatement->execute();
                $data11 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=',$data17[$x]['tenant_id'])
                    ->where('customer_id', '=', $data11['receiver_id']);
                $stmt = $selectStatement->execute();
                $data12 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data12['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data14 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $data17[$x]['tenant_id'])
                    ->where('customer_id', '=', $data11['sender_id']);
                $stmt = $selectStatement->execute();
                $data13 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data13['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data15 = $stmt->fetch();
                $data[$i]['number'] = $i + 1;
                $data[$i]['lorry'] = $data1;
                $data[$i]['goods'] = $data2;
                $data[$i]['goods']['goods_package'] = $data5;
                $data[$i]['receiver'] = $data4;
                $data[$i]['sender_city'] = $data6;
                $data[$i]['sender_province'] = $data8;
                $data[$i]['receiver_city'] = $data7;
                $data[$i]['receiver_province'] = $data9;
                $data[$i]['sure_company'] = $data10;
                $data[$i]['order_sender'] = $data13;
                $data[$i]['order_receiver'] = $data12;
                $data[$i]['order_sender_city'] = $data15;
                $data[$i]['order_receiver_city'] = $data14;

            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});
$app->get('/getSchedulingOrders9',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id', '=', $tenant_id);
    $stmt = $selectStatement->execute();
    $data19= $stmt->fetch();
    $selectStatement = $database->select()
        ->from('city')
        ->where('id', '=', $data19['from_city_id']);
    $stmt = $selectStatement->execute();
    $data20 = $stmt->fetch();
    if($scheduling_id!=null||$scheduling_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('schedule_id', '=', $scheduling_id)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $data18=array();
        $data17=array();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id', '=', $data[0]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data17 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id', '=', $data17['contact_id']);
            $stmt = $selectStatement->execute();
            $data18 = $stmt->fetch();
        }

        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('scheduling_id', '=', $scheduling_id);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('customer_id', '=', $data2['receiver_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data2['send_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data2['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data5['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data1['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('lorry_id', '=', $data2['lorry_id']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('customer_id', '=', $data10['sender_id']);
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data11['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data13['pid']);
            $stmt = $selectStatement->execute();
            $data14 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('customer_id', '=', $data10['receiver_id']);
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data12['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data15 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data15['pid']);
            $stmt = $selectStatement->execute();
            $data16 = $stmt->fetch();
            $data[$i]['jcompany']=$data17['jcompany'];
            $data[$i]['from_city']=$data20;
            $data[$i]['tenant']['contact']=$data18;
            $data[$i]['lorry']=$data9;
            $data[$i]['goods']=$data1;
            $data[$i]['goods']['goods_package']=$data8;
            $data[$i]['receiver']=$data3;
            $data[$i]['sender_city']=$data4;
            $data[$i]['sender_province']=$data6;
            $data[$i]['receiver_city']=$data5;
            $data[$i]['receiver_province']=$data7;
            if($data2==null){
                $data2=array();
            }
            $data[$i] = array_merge($data[$i], $data2);
            $data[$i]['order']=$data10;
            $data[$i]['order']['order_receiver']=$data12;
            $data[$i]['order']['order_sender']=$data11;
            $data[$i]['order']['sender_city']=$data13;
            $data[$i]['order']['receiver_city']=$data15;
            $data[$i]['order']['receiver_province']=$data15;
            $data[$i]['order']['sender_province']=$data14;
        }

        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少调度id"));
    }
});

$app->get('/getSchedulingOrders10',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.is_contract','=',3)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrders11',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.is_insurance', '=',1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data3['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender_city']=$data6;
            $data[$i]['sender_province']=$data8;
            $data[$i]['receiver_city']=$data7;
            $data[$i]['receiver_province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('scheduling','scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->where('schedule_order.schedule_id', '=', $scheduling_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('schedule_order.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->post('/addSchedulingOrder',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $tenant_num=$body->tenant_num;
    $send_city_id = $body->send_city_id;
    $receive_city_id = $body->receive_city_id;
    $lorry_id=$body->lorry_id;
    $receiver_id=$body->receiver_id;
    $is_load=$body->is_load;
    $array=array();
    $order_ary=$body->order_ary;
    $array1 = array();
    $array2=array();
    foreach ($order_ary as $key => $value) {
        $array2[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
            if($send_city_id!=null||$send_city_id!=''){
                if($receiver_id!=null||$receiver_id!=''){
                    if($receive_city_id!=null||$receive_city_id!=''){
                        if($lorry_id!=null||$lorry_id!=''){
                            $selectStatement = $database->select()
                                ->from('scheduling')
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetchAll();
                            $scheduling_id=null;
                            if((count($data)+1)<10){
                                $scheduling_id='QD'.$tenant_num."00000".(count($data)+1);
                            }else if((count($data)+1)<100&&(count($data)+1)>9){
                                $scheduling_id='QD'.$tenant_num."0000".(count($data)+1);
                            }else if((count($data)+1)<1000&&(count($data)+1)>99){
                                $scheduling_id='QD'.$tenant_num."000".(count($data)+1);
                            }else if((count($data)+1)<10000&&(count($data)+1)>999){
                                $scheduling_id='QD'.$tenant_num."00".(count($data)+1);
                            }else if((count($data)+1)<100000&&(count($data)+1)>9999){
                                $scheduling_id='QD'.$tenant_num."0".(count($data)+1);
                            }else if((count($data)+1)<1000000&&(count($data)+1)>99999){
                                $scheduling_id='QD'.$tenant_num.(count($data)+1);
                            }
                                $array['tenant_id']=$tenant_id;
                                date_default_timezone_set("PRC");
                                $array['scheduling_datetime']=date('Y-m-d H:i:s',time());
                                $array['exist']=0;
                                $array['scheduling_id']=$scheduling_id;
                                $array['send_city_id']=$send_city_id;
                                $array['receive_city_id']=$receive_city_id;
                                $array['lorry_id']=$lorry_id;
                                $array['receiver_id']=$receiver_id;
                                $array['is_load']=$is_load;
                                $array['scheduling_status']=1;
                                $array['is_show']=0;
                                $array['is_alter']=0;
                                $array['is_contract']=1;
                                $array['is_insurance']=1;
                                $insertStatement = $database->insert(array_keys($array))
                                    ->into('scheduling')
                                    ->values(array_values($array));
                                $insertId = $insertStatement->execute(false);
                                for($x=0;$x<count($array2);$x++){
                                    $insertStatement = $database->insert(array('tenant_id','schedule_id','order_id','exist'))
                                        ->into('schedule_order')
                                        ->values(array($tenant_id,$scheduling_id,$array2[$x],0));
                                     $insertId = $insertStatement->execute(false);
                                    $updateStatement = $database->update(array('is_schedule'=>2))
                                        ->table('orders')
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('order_id','=',$array2[$x]);
                                    $affectedRows = $updateStatement->execute();
                                }
                            echo json_encode(array("result" => "0", "desc" => "success","scheduling_id"=>$scheduling_id));
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
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }
});

$app->post('/addtest',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_ary=$body->order_ary;
    $array2=array();
    foreach ($order_ary as $key => $value) {
        $array2[$key] = $value;
    }
    echo json_encode(array("result" => "6", "desc" =>$array2[1]));
});



$app->run();
function localhost(){
    return connect();
}
?>