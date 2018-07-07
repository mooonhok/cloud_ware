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
//            ->where('schedule_order.exist', '=', 0)
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
    //清单添加
    $array1=array();
    $array2=array();
    $send_city_name=$body->send_city_name;
    $receive_city_name=$body->receive_city_name;
    $plate_number=$body->plate_number;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $flag=$body->flag;
    $tenant_num=$body->tenant_num;
    $tenant_flag=$body->tenant_flag;
    $receive_tenant_id=$body->receive_tenant_id;
    $customer_name = $body->customer_name;
    $customer_phone = $body->customer_phone;
    $customer_address = $body->customer_address;
    $type=$body->type;
    $contact_tenant_id=$body->contact_tenant_id;
    $times=$body->times;
    $is_load=$body->is_load;
    $order_ary=$body->order_ary;
    //运单号数组
    $array6=null;
    foreach ($order_ary as $key => $value) {
        $array6[$key] = $value;
    }
    $array4=null;
    $partner_name=$body->partner_name;
    $partner_phone=$body->partner_phone;
    $partner_city_id=$body->partner_city_id;
    $partner_address=$body->partner_address;
    $partner_type=$body->partner_type;
    $partner_times=$body->partner_times;
    $array5=null;
    $num=0;
    $oids=array();
    for($y=0;$y<count($array6);$y++){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id)
            ->where("order_id",'=',$array6[$y])
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data20 = $stmt->fetchAll();
        $num=$num+count($data20);
        if($data20!=null){
            array_push($oids,$array6[$y]);
        }
    }
    if($num==0){
    if($send_city_name!=null||$send_city_name!=''){
        if($receive_city_name!=null||$receive_city_name!=''){
            $selectStatement = $database->select()
                ->from('city')
                ->where('name','=',$send_city_name);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            if($data1!=null){
                $array1['send_city_id'] = $data1['id'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('name','=',$receive_city_name);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                if($data2!=null){
                    $array1['receive_city_id'] = $data2['id'];
                    $selectStatement = $database->select()
                        ->from('app_lorry')
                        ->where('plate_number', '=', $plate_number)
                        ->where('flag','=',$flag)
                        ->where('name', '=', $driver_name)
                        ->where('exist', '=', 0)
                        ->where('phone', '=', $driver_phone);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    if($data4!=null){
                        if($data4['lorry_status']!=1){
                                $selectStatement = $database->select()
                                    ->from('lorry')
                                    ->where('driver_phone', '=', $driver_phone)
                                    ->where('plate_number','=',$plate_number)
                                    ->where('driver_name','=',$driver_name)
                                    ->where('tenant_id', '=', $tenant_id)
                                    ->where('exist','=',1)
                                    ->where('flag', '=', $flag);
                                $stmt = $selectStatement->execute();
                                $data5 = $stmt->fetch();
                                if($data5==null){
                                    $array1['lorry_id']=null;
                                    $selectStatement = $database->select()
                                        ->from('lorry')
                                        ->where('driver_phone', '=', $driver_phone)
                                        ->where('plate_number','=',$plate_number)
                                        ->where('driver_name','=',$driver_name)
                                        ->where('tenant_id', '=', $tenant_id)
                                        ->where('flag', '=', $flag)
                                        ->where("exist",'=',0);
                                    $stmt = $selectStatement->execute();
                                    $data15 = $stmt->fetch();
                                    if($data15==null){
                                        $array2['plate_number']=$plate_number;
                                        $array2['driver_name']=$driver_name;
                                        $array2['driver_phone']=$driver_phone;
                                        $array2['flag']=$flag;
                                        $array2['tenant_id']=$tenant_id;
                                        $array2['exist']=0;
                                        $selectStatement = $database->select()
                                            ->from('lorry')
                                            ->where('tenant_id', '=', $tenant_id);
                                        $stmt = $selectStatement->execute();
                                        $data6 = $stmt->fetchAll();
                                        $array2['lorry_id']=count($data6)+100000001;
                                        $insertStatement = $database->insert(array_keys($array2))
                                            ->into('lorry')
                                            ->values(array_values($array2));
                                        $insertId = $insertStatement->execute(false);
                                        $updateStatement = $database->update(array('lorry_status'=>2))
                                            ->table('app_lorry')
                                            ->where('app_lorry_id','=',$data4['app_lorry_id']);
                                        $affectedRows = $updateStatement->execute();
                                        $array1['lorry_id']=$array2['lorry_id'];
                                    }else{
                                        $array1['lorry_id']=$data15['lorry_id'];
                                    }
                                    $selectStatement = $database->select()
                                        ->from('scheduling')
                                        ->where('tenant_id', '=', $tenant_id);
                                    $stmt = $selectStatement->execute();
                                    $data9 = $stmt->fetchAll();
                                    $scheduling_id=null;
                                    if((count($data9)+1)<10){
                                        $scheduling_id='QD'.$tenant_num."00000".(count($data9)+1);
                                    }else if((count($data9)+1)<100&&(count($data9)+1)>9){
                                        $scheduling_id='QD'.$tenant_num."0000".(count($data9)+1);
                                    }else if((count($data9)+1)<1000&&(count($data9)+1)>99){
                                        $scheduling_id='QD'.$tenant_num."000".(count($data9)+1);
                                    }else if((count($data9)+1)<10000&&(count($data9)+1)>999){
                                        $scheduling_id='QD'.$tenant_num."00".(count($data9)+1);
                                    }else if((count($data9)+1)<100000&&(count($data9)+1)>9999){
                                        $scheduling_id='QD'.$tenant_num."0".(count($data9)+1);
                                    }else if((count($data9)+1)<1000000&&(count($data9)+1)>99999){
                                        $scheduling_id='QD'.$tenant_num.(count($data9)+1);
                                    }
                                    if($tenant_flag==0){
                                        $receiver_id=null;
                                        if( $contact_tenant_id==null|| $contact_tenant_id=="") {
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->whereNull('wx_openid')
                                                ->where('customer_name', '=', $customer_name)
                                                ->where('customer_phone', '=', $customer_phone)
                                                ->where('customer_city_id', '=',$array1['receive_city_id'])
                                                ->where('customer_address', '=', $customer_address)
                                                ->where('type', '=', $type)
                                                ->where('exist', '=', 0)
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data6 = $stmt->fetch();
                                            if ($data6 == null) {
                                                $array4['tenant_id'] = $tenant_id;
                                                $array4['exist'] = 0;
                                                $array4['customer_name'] = $customer_name;
                                                $array4['customer_phone'] = $customer_phone;
                                                $array4['customer_city_id'] = $array1['receive_city_id'];
                                                $array4['customer_address'] = $customer_address;
                                                $array4['type']=$type;
                                                $array4['contact_tenant_id']=$contact_tenant_id;
                                                $array4['times']=$times;
                                                $selectStatement = $database->select()
                                                    ->from('tenant')
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data7 = $stmt->fetch();
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_id', '!=', $data7['contact_id'])
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data8 = $stmt->fetchAll();
                                                $array4['customer_id'] = count($data8) + 10000000001;
                                                $insertStatement = $database->insert(array_keys($array4))
                                                    ->into('customer')
                                                    ->values(array_values($array4));
                                                $insertId = $insertStatement->execute(false);
                                                $receiver_id="".$array4['customer_id'];
                                            } else {
                                                $receiver_id="".$data6['customer_id'];
                                                $a=$times+1;
                                                $updateStatement = $database->update(array('times'=>$a))
                                                    ->table('customer')
                                                    ->where('customer_id','=',$data6['customer_id'])
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                            }
                                        }else{
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->whereNull('wx_openid')
                                                ->where('customer_name','=',$customer_name)
                                                ->where('customer_phone','=',$customer_phone)
                                                ->where('customer_city_id','=',$array1['receive_city_id'])
                                                ->where('customer_address','=',$customer_address)
                                                ->where('type','=',$type)
                                                ->where('contact_tenant_id','=',$contact_tenant_id)
                                                ->where('exist','=',0)
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data6 = $stmt->fetch();
                                            if($data6==null){
                                                $array4['tenant_id'] = $tenant_id;
                                                $array4['exist'] = 0;
                                                $array4['customer_name'] = $customer_name;
                                                $array4['customer_phone'] = $customer_phone;
                                                $array4['customer_city_id'] = $array1['receive_city_id'];
                                                $array4['customer_address'] = $customer_address;
                                                $array4['type']=$type;
                                                $array4['contact_tenant_id']=$contact_tenant_id;
                                                $array4['times']=$times;
                                                $array4['tenant_id']=$tenant_id;
                                                $array4['exist']=0;
                                                $selectStatement = $database->select()
                                                    ->from('tenant')
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data7 = $stmt->fetch();
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_id', '!=', $data7['contact_id'])
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data8 = $stmt->fetchAll();
                                                $array4['customer_id']=count($data8)+10000000001;
                                                $insertStatement = $database->insert(array_keys($array4))
                                                    ->into('customer')
                                                    ->values(array_values($array4));
                                                $insertId = $insertStatement->execute(false);
                                                $receiver_id="".$array4['customer_id'];
                                            } else {
                                                $receiver_id="".$data6['customer_id'];
                                                $a=$data6['times']+1;
                                                $updateStatement = $database->update(array('times'=>$a))
                                                    ->table('customer')
                                                    ->where('customer_id','=',$data6['customer_id'])
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                            }
                                        }
                                        $array1['tenant_id'] = $tenant_id;
                                        date_default_timezone_set("PRC");
                                        $array1['scheduling_datetime'] = date('Y-m-d H:i:s', time());
                                        $array1['exist'] = 0;
                                        $array1['scheduling_id'] = $scheduling_id;
//                                        $array1['lorry_id'] = $array2['lorry_id'];
                                        $array1['receiver_id'] = $receiver_id;
                                        $array1['is_load'] = $is_load;
                                        $array1['scheduling_status'] = 1;
                                        $array1['is_show'] = 0;
                                        $array1['is_alter'] = 0;
                                        $array1['is_contract'] = 1;
                                        $array1['is_insurance'] = 1;
                                        $insertStatement = $database->insert(array_keys($array1))
                                            ->into('scheduling')
                                            ->values(array_values($array1));
                                        $insertId = $insertStatement->execute(false);
                                        for ($x = 0; $x < count($array6); $x++) {
                                            $selectStatement = $database->select()
                                                ->from('schedule_order')
                                                ->where('schedule_id', '=', $scheduling_id)
                                                ->where('tenant_id', '=', $tenant_id)
                                                ->where("order_id",'=',$array6[$x]);
                                            $stmt = $selectStatement->execute();
                                            $data16 = $stmt->fetch();
                                            if($data16==null){
                                            $insertStatement = $database->insert(array('tenant_id', 'schedule_id', 'order_id', 'exist'))
                                                ->into('schedule_order')
                                                ->values(array($tenant_id, $scheduling_id, $array6[$x], 0));
                                            $insertId = $insertStatement->execute(false);
                                            $updateStatement = $database->update(array('is_schedule' => 2))
                                                ->table('orders')
                                                ->where('tenant_id', '=', $tenant_id)
                                                ->where('order_id', '=', $array6[$x]);
                                            $affectedRows = $updateStatement->execute();
                                            }else{
                                                $updateStatement = $database->update(array('exist' => 0))
                                                    ->table('schedule_order')
                                                    ->where('schedule_id', '=', $scheduling_id)
                                                    ->where('tenant_id', '=', $tenant_id)
                                                    ->where("order_id",'=',$array6[$x]);
                                                $affectedRows = $updateStatement->execute();
                                            }
                                        }
                                        echo json_encode(array("result" => "0", "desc" => "success","scheduling_id"=>$scheduling_id));
//                                        echo json_encode(array("result" => "0", "desc" => "success","scheduling"=>$array1));
                                    }else{
                                        $selectStatement = $database->select()
                                            ->from('tenant')
                                            ->where('tenant_id','=',$receive_tenant_id)
                                            ->where('exist','=',0);
                                        $stmt = $selectStatement->execute();
                                        $data10= $stmt->fetch();
                                        if($data10!=null){
                                            if($array1['receive_city_id']==$data10['from_city_id']){
                                                $receiver_id=null;
                                                if( $contact_tenant_id==null|| $contact_tenant_id=="") {
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->whereNull('wx_openid')
                                                        ->where('customer_name', '=', $customer_name)
                                                        ->where('customer_phone', '=', $customer_phone)
                                                        ->where('customer_city_id', '=',$array1['receive_city_id'])
                                                        ->where('customer_address', '=', $customer_address)
                                                        ->where('type', '=', $type)
                                                        ->where('exist', '=', 0)
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data6 = $stmt->fetch();
                                                    if ($data6 == null) {
                                                        $array4['tenant_id'] = $tenant_id;
                                                        $array4['exist'] = 0;
                                                        $array4['customer_name'] = $customer_name;
                                                        $array4['customer_phone'] = $customer_phone;
                                                        $array4['customer_city_id'] = $array1['receive_city_id'];
                                                        $array4['customer_address'] = $customer_address;
                                                        $array4['type']=$type;
                                                        $array4['contact_tenant_id']=$contact_tenant_id;
                                                        $array4['times']=$times;
                                                        $selectStatement = $database->select()
                                                            ->from('tenant')
                                                            ->where('tenant_id', '=', $tenant_id);
                                                        $stmt = $selectStatement->execute();
                                                        $data7 = $stmt->fetch();
                                                        $selectStatement = $database->select()
                                                            ->from('customer')
                                                            ->whereNull('wx_openid')
                                                            ->where('customer_id', '!=', $data7['contact_id'])
                                                            ->where('tenant_id', '=', $tenant_id);
                                                        $stmt = $selectStatement->execute();
                                                        $data8 = $stmt->fetchAll();
                                                        $array4['customer_id'] = count($data8) + 10000000001;
                                                        $insertStatement = $database->insert(array_keys($array4))
                                                            ->into('customer')
                                                            ->values(array_values($array4));
                                                        $insertId = $insertStatement->execute(false);
                                                        $receiver_id="".$array4['customer_id'];
                                                    } else {
                                                        $receiver_id="".$data6['customer_id'];
                                                        $a=$data6['times']+1;
                                                        $updateStatement = $database->update(array('times'=>$a))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data6['customer_id'])
                                                            ->where('tenant_id','=',$tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }else{
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->whereNull('wx_openid')
                                                        ->where('customer_name','=',$customer_name)
                                                        ->where('customer_phone','=',$customer_phone)
                                                        ->where('customer_city_id','=',$array1['receive_city_id'])
                                                        ->where('customer_address','=',$customer_address)
                                                        ->where('type','=',$type)
                                                        ->where('contact_tenant_id','=',$contact_tenant_id)
                                                        ->where('exist','=',0)
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data6 = $stmt->fetch();
                                                    if($data6==null){
                                                        $array4['tenant_id'] = $tenant_id;
                                                        $array4['exist'] = 0;
                                                        $array4['customer_name'] = $customer_name;
                                                        $array4['customer_phone'] = $customer_phone;
                                                        $array4['customer_city_id'] = $array1['receive_city_id'];
                                                        $array4['customer_address'] = $customer_address;
                                                        $array4['type']=$type;
                                                        $array4['contact_tenant_id']=$contact_tenant_id;
                                                        $array4['times']=$times;
                                                        $array4['tenant_id']=$tenant_id;
                                                        $array4['exist']=0;
                                                        $selectStatement = $database->select()
                                                            ->from('tenant')
                                                            ->where('tenant_id', '=', $tenant_id);
                                                        $stmt = $selectStatement->execute();
                                                        $data7 = $stmt->fetch();
                                                        $selectStatement = $database->select()
                                                            ->from('customer')
                                                            ->whereNull('wx_openid')
                                                            ->where('customer_id', '!=', $data7['contact_id'])
                                                            ->where('tenant_id', '=', $tenant_id);
                                                        $stmt = $selectStatement->execute();
                                                        $data8 = $stmt->fetchAll();
                                                        $array4['customer_id']=count($data8)+10000000001;
                                                        $insertStatement = $database->insert(array_keys($array4))
                                                            ->into('customer')
                                                            ->values(array_values($array4));
                                                        $insertId = $insertStatement->execute(false);
                                                        $receiver_id="".$array4['customer_id'];
                                                    } else {
                                                        $receiver_id="".$data6['customer_id'];
                                                        $a=$data6['times']+1;
                                                        $updateStatement = $database->update(array('times'=>$a))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data6['customer_id'])
                                                            ->where('tenant_id','=',$tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_name', '=', $partner_name)
                                                    ->where('customer_phone', '=', $partner_phone)
                                                    ->where('customer_city_id', '=', $partner_city_id)
                                                    ->where('customer_address', '=', $partner_address)
                                                    ->where('type', '=', $partner_type)
                                                    ->where('exist', '=', 0)
                                                    ->where('tenant_id', '=', $contact_tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data12 = $stmt->fetch();
                                                if ($data12 == null) {
                                                    $array5['tenant_id'] = $contact_tenant_id;
                                                    $array5['exist'] = 0;
                                                    $array5['customer_name'] = $partner_name;
                                                    $array5['customer_phone'] = $partner_phone;
                                                    $array5['customer_city_id'] = $partner_city_id;
                                                    $array5['customer_address'] = $partner_address;
                                                    $array5['type']=$partner_type;
                                                    $array5['contact_tenant_id']=$tenant_id;
                                                    $array5['times']=$partner_times;
                                                    $selectStatement = $database->select()
                                                        ->from('tenant')
                                                        ->where('tenant_id', '=', $contact_tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data13 = $stmt->fetch();
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->whereNull('wx_openid')
                                                        ->where('customer_id', '!=', $data13['contact_id'])
                                                        ->where('tenant_id', '=', $contact_tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data14 = $stmt->fetchAll();
                                                    $array5['customer_id'] = count($data14) + 10000000001;
                                                    $insertStatement = $database->insert(array_keys($array5))
                                                        ->into('customer')
                                                        ->values(array_values($array5));
                                                    $insertId = $insertStatement->execute(false);
                                                } else {
                                                    $a=$data12['times']+1;
                                                    $updateStatement = $database->update(array('times'=>$a))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data12['customer_id'])
                                                        ->where('tenant_id','=',$contact_tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }
                                                $array1['tenant_id'] = $tenant_id;
                                                date_default_timezone_set("PRC");
                                                $array1['scheduling_datetime'] = date('Y-m-d H:i:s', time());
                                                $array1['exist'] = 0;
                                                $array1['scheduling_id'] = $scheduling_id;
//                                                $array1['lorry_id'] = $array2['lorry_id'];
                                                $array1['receiver_id'] = $receiver_id;
                                                $array1['is_load'] = $is_load;
                                                $array1['scheduling_status'] = 1;
                                                $array1['is_show'] = 0;
                                                $array1['is_alter'] = 0;
                                                $array1['is_contract'] = 1;
                                                $array1['is_insurance'] = 1;
                                                $insertStatement = $database->insert(array_keys($array1))
                                                    ->into('scheduling')
                                                    ->values(array_values($array1));
                                                $insertId = $insertStatement->execute(false);
                                                for ($x = 0; $x < count($array6); $x++) {
                                                    $selectStatement = $database->select()
                                                        ->from('schedule_order')
                                                        ->where('schedule_id', '=', $scheduling_id)
                                                        ->where('tenant_id', '=', $tenant_id)
                                                        ->where("order_id",'=',$array6[$x]);
                                                    $stmt = $selectStatement->execute();
                                                    $data16 = $stmt->fetch();
                                                    if($data16==null){
                                                        $insertStatement = $database->insert(array('tenant_id', 'schedule_id', 'order_id', 'exist'))
                                                            ->into('schedule_order')
                                                            ->values(array($tenant_id, $scheduling_id, $array6[$x], 0));
                                                        $insertId = $insertStatement->execute(false);
                                                        $updateStatement = $database->update(array('is_schedule' => 2))
                                                            ->table('orders')
                                                            ->where('tenant_id', '=', $tenant_id)
                                                            ->where('order_id', '=', $array6[$x]);
                                                        $affectedRows = $updateStatement->execute();
                                                    }else{
                                                        $updateStatement = $database->update(array('exist' => 0))
                                                            ->table('schedule_order')
                                                            ->where('schedule_id', '=', $scheduling_id)
                                                            ->where('tenant_id', '=', $tenant_id)
                                                            ->where("order_id",'=',$array6[$x]);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }
                                                echo json_encode(array("result" => "0", "desc" => "success","scheduling_id"=>$scheduling_id));
                                            }else{
                                                $selectStatement = $database->select()
                                                    ->from('city')
                                                    ->where('id','=',$data10['from_city_id']);
                                                $stmt = $selectStatement->execute();
                                                $data11 = $stmt->fetch();
                                                echo json_encode(array("result" => "8", "desc" => "收货公司所在城市不匹配",'receivecityname'=>$data11['name']));
                                            }
                                        }else{
                                            echo json_encode(array("result" => "7", "desc" => "该租户公司不存在"));
                                        }
                                    }
                                }else{
                                    echo json_encode(array("result" => "6", "desc" => "该车辆已经被加入黑名单"));
                                }
                        }else{
                            echo json_encode(array("result" => "10", "desc" => "驾驶员正在修改个人资料"));
                        }
                    }else{
                        echo json_encode(array("result" => "5", "desc" => "该车辆还未在交付帮手上注册过"));
                    }
                }else{
                    echo json_encode(array("result" => "4", "desc" => "收货城市不存在"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "发货城市不存在"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少收货城市名称"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少发货城市名称"));
    }
    }else{
        echo json_encode(array("result" => "11", "desc" => "无法生成清单","oids"=>$oids));
    }
});



$app->put('/finishSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    date_default_timezone_set("PRC");
    $time=date("Y-m-d H:i:s", time());
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('scheduling_id', '=',$scheduling_id)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('schedule_id', '=',$scheduling_id)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data!=null) {
            if ($data['scheduling_status']==3) {
                $updateStatement = $database->update(array('is_show' => 1, "scheduling_status" => 4))
                    ->table('scheduling')
                    ->where('scheduling_id', '=', $scheduling_id)
                    ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                if($data2!=null){
                for ($x = 0; $x < count($data2); $x++) {
                    $updateStatement = $database->update(array('order_status' =>3, "order_datetime3" => $time))
                        ->table('orders')
                        ->where('order_id', '=', $data2[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data2[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data3['sender_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $data2[$x]['sender_customer_phone']=$data4['customer_phone'];
                    $data2[$x]['sender_city_name']=$data5['name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data3['receiver_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data6['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $data2[$x]['receiver_city_name']=$data7['name'];
                    $data2[$x]['receiver_customer_phone']=$data6['customer_phone'];
                }
                }
                echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data2));
            } else if ($data['scheduling_status']==2 && $data['is_load']==3) {
                $updateStatement = $database->update(array('is_show' => 1, "scheduling_status" => 4))
                    ->table('scheduling')
                    ->where('scheduling_id', '=', $scheduling_id)
                    ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                if($data2!=null){
                for ($x = 0; $x < count($data2); $x++) {
                    $updateStatement = $database->update(array('order_status' => 3, "order_datetime3" => $time, "order_datetime2" => $time))
                        ->table('orders')
                        ->where('order_id', '=', $data2[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data2[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data3['sender_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $data2[$x]['sender_customer_phone']=$data4['customer_phone'];
                    $data2[$x]['sender_city_name']=$data5['name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data3['receiver_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data6['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $data2[$x]['receiver_city_name']=$data7['name'];
                    $data2[$x]['receiver_customer_phone']=$data6['customer_phone'];
                }
               }
                echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data2));
            }else{
                echo json_encode(array("result" => "3", "desc" => "success", "scheduling_status" => $data['scheduling_status']));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "该清单不存在"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->put('/loadSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    date_default_timezone_set("PRC");
    $time=date("Y-m-d H:i:s", time());
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('scheduling_id', '=',$scheduling_id)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('schedule_id', '=',$scheduling_id)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data!=null) {
            if ($data['scheduling_status']==2) {
                $updateStatement = $database->update(array('is_load' =>0, "scheduling_status" => 3))
                    ->table('scheduling')
                    ->where('scheduling_id', '=', $scheduling_id)
                    ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                for ($x = 0; $x < count($data2); $x++) {
                    $updateStatement = $database->update(array('order_status' => 2, "order_datetime2" => $time))
                        ->table('orders')
                        ->where('order_id', '=', $data2[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                }
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "3", "desc" => "success", "scheduling_status" => $data['scheduling_status']));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "该清单不存在"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/cancelSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $exception_source=$body->exception_source;
    $exception_person=$body->exception_person;
    $exception_comment=$body->exception_comment;
    date_default_timezone_set("PRC");
    $time=date("Y-m-d H:i:s", time());
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('schedule_id', '=', $scheduling_id)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist','=',0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('exception')
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data10= $stmt->fetchAll();
            $exception_id=count($data10)+100000001;
            if($data!=null){
                  for($x=0;$x<count($data);$x++){
                      $insertStatement = $database->insert(array("order_id","tenant_id","exception_source","exception_person","exception_comment","exist","exception_time","exception_id"))
                          ->into('exception')
                          ->values(array($data[$x]["order_id"],$tenant_id,$exception_source,$exception_person,$exception_comment,0,$time,$exception_id));
                      $insertId = $insertStatement->execute(false);
                      $updateStatement = $database->update(array('is_back'=>2,"exception_id"=>$exception_id,"order_status"=>5))
                        ->table('orders')
                        ->where('order_id', '=', $data[$x]["order_id"])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                      $updateStatement = $database->update(array('exist'=>1))
                          ->table('schedule_order')
                          ->where('order_id', '=', $data[$x]["order_id"])
                          ->where('schedule_id',"=",$scheduling_id)
                          ->where('tenant_id', '=', $tenant_id);
                      $affectedRows = $updateStatement->execute();
                  }
                $updateStatement = $database->update(array('scheduling_status'=>7))
                        ->table('scheduling')
                        ->where('scheduling_id', '=', $scheduling_id)
                        ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                $selectStatement = $database->select()
                    ->from('agreement_schedule')
                    ->where('scheduling_id', '=', $scheduling_id)
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist','=',0);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                if($data2==null){
                    $updateStatement = $database->update(array('is_contract'=>0,'is_insurance'=>0))
                        ->table('scheduling')
                        ->where('scheduling_id', '=', $scheduling_id)
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                }else{
                    $updateStatement = $database->update(array('agreement_status'=>2))
                        ->table('agreement')
                        ->where('agreement_id', '=', $data2["agreement_id"])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                }
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "3", "desc" => "清单尚未管理运单"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少调度单id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/transitSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('schedule_id', '=', $scheduling_id)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist','=',0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    $updateStatement = $database->update(array('is_back'=>0))
                        ->table('orders')
                        ->where('order_id', '=', $data[$x]["order_id"])
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                }
                $updateStatement = $database->update(array('scheduling_status'=>4))
                    ->table('scheduling')
                    ->where('scheduling_id', '=', $scheduling_id)
                    ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "3", "desc" => "清单尚未管理运单"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少调度单id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->post('/addtest',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $array6=null;
    $order_ary=$body->order_ary;
    foreach ($order_ary as $key => $value) {
        $array6[$key] = $value;
    }
    $num=0;
    $oid_ary=array();
    for($y=0;$y<count($array6);$y++){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id)
            ->where("order_id",'=',$array6[$y])
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data20 = $stmt->fetchAll();
        $num+=count($data20);
        if($data20!=null){
            array_push($oid_ary,$data20);
        }
    }
    if($num==0){
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "11", "desc" => "无法生成清单","num"=>$num));
    }
});

$app->put('/deleteSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('scheduling_id', '=', $scheduling_id)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist','=',0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                if($data['scheduling_status']==2||$data['scheduling_status']==1){
                    $updateStatement = $database->update(array('is_alter'=>2,"exist"=>1))
                        ->table('scheduling')
                        ->where('scheduling_id', '=', $scheduling_id)
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data['receiver_id'])
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('exist','=',0);
                    $stmt = $selectStatement->execute();
                    $data2= $stmt->fetch();
                    if($data2!=null){
                        if($data2['times']==1){
                            $updateStatement = $database->update(array('exist'=>1))
                                ->table('customer')
                                ->where('customer_id','=',$data2['customer_id'])
                                ->where('tenant_id','=',$tenant_id);
                            $affectedRows = $updateStatement->execute();
                        }else{
                            $a=$data2['times']-1;
                            $updateStatement = $database->update(array('times'=>$a))
                                ->table('customer')
                                ->where('customer_id','=',$data2['customer_id'])
                                ->where('tenant_id','=',$tenant_id);
                            $affectedRows = $updateStatement->execute();
                        }
                        if($data2['contact_tenant_id']!=null){
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('contact_tenant_id', '=', $tenant_id)
                                ->where('tenant_id', '=', $data2['contact_tenant_id'])
                                ->where('exist','=',0);
                            $stmt = $selectStatement->execute();
                            $data3= $stmt->fetch();
                            if($data3!=null){
                                if($data3['times']==1){
                                    $updateStatement = $database->update(array('exist'=>1))
                                        ->table('customer')
                                        ->where('customer_id','=',$data3['customer_id'])
                                        ->where('tenant_id','=',$data2['contact_tenant_id']);
                                    $affectedRows = $updateStatement->execute();
                                }else{
                                    $a=$data3['times']-1;
                                    $updateStatement = $database->update(array('times'=>$a))
                                        ->table('customer')
                                        ->where('customer_id','=',$data3['customer_id'])
                                        ->where('tenant_id','=',$data2['contact_tenant_id']);
                                    $affectedRows = $updateStatement->execute();
                                }
                            }
                        }
                    }
                    $updateStatement = $database->update(array('exist'=>1))
                        ->table('schedule_order')
                        ->where('schedule_id', '=', $scheduling_id)
                        ->where('tenant_id', '=', $tenant_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result" => "0", "desc" => "success",'scheduling_status'=>$data['scheduling_status']));
                }else{
                    echo json_encode(array("result" => "4", "desc" => "不可以修改该清单"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "清单不存在"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少调度单id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->put('/alterSchedulingOrder',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    //清单添加
    $array1=array();
    $array2=array();
    $send_city_name=$body->send_city_name;
    $receive_city_name=$body->receive_city_name;
    $plate_number=$body->plate_number;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $flag=$body->flag;
    $tenant_flag=$body->tenant_flag;
    $receive_tenant_id=$body->receive_tenant_id;
    $customer_name = $body->customer_name;
    $customer_phone = $body->customer_phone;
    $customer_address = $body->customer_address;
    $type=$body->type;
    $contact_tenant_id=$body->contact_tenant_id;
    $times=$body->times;
    $is_load=$body->is_load;
    $order_ary=$body->order_ary;
    $scheduling_id=$body->scheduling_id;
    //运单号数组
    $array6=null;
    foreach ($order_ary as $key => $value) {
        $array6[$key] = $value;
    }
    $array4=null;
    $partner_name=$body->partner_name;
    $partner_phone=$body->partner_phone;
    $partner_city_id=$body->partner_city_id;
    $partner_address=$body->partner_address;
    $partner_type=$body->partner_type;
    $partner_times=$body->partner_times;
    $array5=null;
    if($send_city_name!=null||$send_city_name!=''){
        if($receive_city_name!=null||$receive_city_name!=''){
            $selectStatement = $database->select()
                ->from('city')
                ->where('name','=',$send_city_name);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            if($data1!=null){
                $array1['send_city_id'] = $data1['id'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('name','=',$receive_city_name);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                if($data2!=null){
                    $array1['receive_city_id'] = $data2['id'];
                    $selectStatement = $database->select()
                        ->from('app_lorry')
                        ->where('plate_number', '=', $plate_number)
                        ->where('flag','=',$flag)
                        ->where('name', '=', $driver_name)
                        ->where('exist', '=', 0)
                        ->where('phone', '=', $driver_phone);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    if($data4!=null){
                        if($data4['lorry_status']!=1){
                            $selectStatement = $database->select()
                                ->from('lorry')
                                ->where('driver_phone', '=', $driver_phone)
                                ->where('plate_number','=',$plate_number)
                                ->where('driver_name','=',$driver_name)
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('exist','=',1)
                                ->where('flag', '=', $flag);
                            $stmt = $selectStatement->execute();
                            $data5 = $stmt->fetch();
                            if($data5==null){
                                $array1['lorry_id']=null;
                                $selectStatement = $database->select()
                                    ->from('lorry')
                                    ->where('driver_phone', '=', $driver_phone)
                                    ->where('plate_number','=',$plate_number)
                                    ->where('driver_name','=',$driver_name)
                                    ->where('tenant_id', '=', $tenant_id)
                                    ->where('flag', '=', $flag)
                                    ->where("exist",'=',0);
                                $stmt = $selectStatement->execute();
                                $data15 = $stmt->fetch();
                                if($data15==null){
                                    $array2['plate_number']=$plate_number;
                                    $array2['driver_name']=$driver_name;
                                    $array2['driver_phone']=$driver_phone;
                                    $array2['flag']=$flag;
                                    $array2['tenant_id']=$tenant_id;
                                    $array2['exist']=0;
                                    $selectStatement = $database->select()
                                        ->from('lorry')
                                        ->where('tenant_id', '=', $tenant_id);
                                    $stmt = $selectStatement->execute();
                                    $data6 = $stmt->fetchAll();
                                    $array2['lorry_id']=count($data6)+100000001;
                                    $insertStatement = $database->insert(array_keys($array2))
                                        ->into('lorry')
                                        ->values(array_values($array2));
                                    $insertId = $insertStatement->execute(false);
                                    $updateStatement = $database->update(array('lorry_status'=>2))
                                        ->table('app_lorry')
                                        ->where('app_lorry_id','=',$data4['app_lorry_id']);
                                    $affectedRows = $updateStatement->execute();
                                    $array1['lorry_id']=$array2['lorry_id'];
                                }else{
                                    $array1['lorry_id']=$data15['lorry_id'];
                                }
                                if($tenant_flag==0){
                                    $receiver_id=null;
                                    if( $contact_tenant_id==null|| $contact_tenant_id=="") {
                                        $selectStatement = $database->select()
                                            ->from('customer')
                                            ->whereNull('wx_openid')
                                            ->where('customer_name', '=', $customer_name)
                                            ->where('customer_phone', '=', $customer_phone)
                                            ->where('customer_city_id', '=',$array1['receive_city_id'])
                                            ->where('customer_address', '=', $customer_address)
                                            ->where('type', '=', $type)
//                                            ->where('exist', '=', 0)
                                            ->where('tenant_id', '=', $tenant_id);
                                        $stmt = $selectStatement->execute();
                                        $data6 = $stmt->fetch();
                                        if ($data6 == null) {
                                            $array4['tenant_id'] = $tenant_id;
                                            $array4['exist'] = 0;
                                            $array4['customer_name'] = $customer_name;
                                            $array4['customer_phone'] = $customer_phone;
                                            $array4['customer_city_id'] = $array1['receive_city_id'];
                                            $array4['customer_address'] = $customer_address;
                                            $array4['type']=$type;
                                            $array4['contact_tenant_id']=$contact_tenant_id;
                                            $array4['times']=$times;
                                            $selectStatement = $database->select()
                                                ->from('tenant')
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data7 = $stmt->fetch();
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->whereNull('wx_openid')
                                                ->where('customer_id', '!=', $data7['contact_id'])
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data8 = $stmt->fetchAll();
                                            $array4['customer_id'] = count($data8) + 10000000001;
                                            $insertStatement = $database->insert(array_keys($array4))
                                                ->into('customer')
                                                ->values(array_values($array4));
                                            $insertId = $insertStatement->execute(false);
                                            $receiver_id="".$array4['customer_id'];
                                        } else {
                                            $receiver_id="".$data6['customer_id'];
                                            if($data6['times']==1){
//                                               $a=$data6['times'];
                                                if($data6['exist']==1){
                                                    $updateStatement = $database->update(array('exist'=>0))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data6['customer_id'])
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }else{
                                                    $a=$data6['times']+1;
                                                    $updateStatement = $database->update(array('times'=>$a))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data6['customer_id'])
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }
                                            }else if($data6['times']>1){
                                                $a=$data6['times']+1;
                                                $updateStatement = $database->update(array('times'=>$a))
                                                    ->table('customer')
                                                    ->where('customer_id','=',$data6['customer_id'])
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                            }
                                        }
                                    }else{
                                        $selectStatement = $database->select()
                                            ->from('customer')
                                            ->whereNull('wx_openid')
                                            ->where('customer_name','=',$customer_name)
                                            ->where('customer_phone','=',$customer_phone)
                                            ->where('customer_city_id','=',$array1['receive_city_id'])
                                            ->where('customer_address','=',$customer_address)
                                            ->where('type','=',$type)
                                            ->where('contact_tenant_id','=',$contact_tenant_id)
//                                            ->where('exist','=',0)
                                            ->where('tenant_id', '=', $tenant_id);
                                        $stmt = $selectStatement->execute();
                                        $data6 = $stmt->fetch();
                                        if($data6==null){
                                            $array4['tenant_id'] = $tenant_id;
                                            $array4['exist'] = 0;
                                            $array4['customer_name'] = $customer_name;
                                            $array4['customer_phone'] = $customer_phone;
                                            $array4['customer_city_id'] = $array1['receive_city_id'];
                                            $array4['customer_address'] = $customer_address;
                                            $array4['type']=$type;
                                            $array4['contact_tenant_id']=$contact_tenant_id;
                                            $array4['times']=$times;
                                            $array4['tenant_id']=$tenant_id;
                                            $array4['exist']=0;
                                            $selectStatement = $database->select()
                                                ->from('tenant')
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data7 = $stmt->fetch();
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->whereNull('wx_openid')
                                                ->where('customer_id', '!=', $data7['contact_id'])
                                                ->where('tenant_id', '=', $tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data8 = $stmt->fetchAll();
                                            $array4['customer_id']=count($data8)+10000000001;
                                            $insertStatement = $database->insert(array_keys($array4))
                                                ->into('customer')
                                                ->values(array_values($array4));
                                            $insertId = $insertStatement->execute(false);
                                            $receiver_id="".$array4['customer_id'];
                                        } else {
                                            $receiver_id="".$data6['customer_id'];
                                            if($data6['times']==1){
//                                                $a=$data6['times'];
                                                if($data6['exist']==1){
                                                    $updateStatement = $database->update(array('exist'=>0))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data6['customer_id'])
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }else{
                                                    $a=$data6['times']+1;
                                                    $updateStatement = $database->update(array('times'=>$a))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data6['customer_id'])
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }
                                            }else if($data6['times']>1){
                                                $a=$data6['times']+1;
                                                $updateStatement = $database->update(array('times'=>$a))
                                                    ->table('customer')
                                                    ->where('customer_id','=',$data6['customer_id'])
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                            }
                                        }
                                    }
                                    $array1['exist'] = 0;
                                    $array1['receiver_id'] = $receiver_id;
                                    $array1['is_load'] = $is_load;
                                    $array1['exist']=0;
                                    $array1['is_alter']=0;
                                    $updateStatement = $database->update($array1)
                                        ->table('scheduling')
                                        ->where('scheduling_id', '=', $scheduling_id)
                                        ->where('tenant_id', '=', $tenant_id);
                                    $affectedRows = $updateStatement->execute();
                                    for ($x = 0; $x < count($array6); $x++) {
                                        $selectStatement = $database->select()
                                            ->from('schedule_order')
                                            ->where('schedule_id', '=', $scheduling_id)
                                            ->where('tenant_id', '=', $tenant_id)
                                            ->where("order_id",'=',$array6[$x]);
                                        $stmt = $selectStatement->execute();
                                        $data16 = $stmt->fetch();
                                        if($data16==null){
                                            $insertStatement = $database->insert(array('tenant_id', 'schedule_id', 'order_id', 'exist'))
                                                ->into('schedule_order')
                                                ->values(array($tenant_id, $scheduling_id, $array6[$x], 0));
                                            $insertId = $insertStatement->execute(false);
                                            $updateStatement = $database->update(array('is_schedule' => 2))
                                                ->table('orders')
                                                ->where('tenant_id', '=', $tenant_id)
                                                ->where('order_id', '=', $array6[$x]);
                                            $affectedRows = $updateStatement->execute();
                                        }else{
                                            $updateStatement = $database->update(array('exist' => 0))
                                                ->table('schedule_order')
                                                ->where('schedule_id', '=', $scheduling_id)
                                                ->where('tenant_id', '=', $tenant_id)
                                                ->where("order_id",'=',$array6[$x]);
                                            $affectedRows = $updateStatement->execute();
                                        }
                                    }
                                    $deleteStatement = $database->delete()
                                        ->from('schedule_order')
                                        ->where('schedule_id', '=', $scheduling_id)
                                        ->where('tenant_id', '=', $tenant_id)
                                        ->where("exist",'=',1);
                                    $affectedRows = $deleteStatement->execute();
                                    echo json_encode(array("result" => "0", "desc" => "success"));
                                }else{
                                    $selectStatement = $database->select()
                                        ->from('tenant')
                                        ->where('tenant_id','=',$receive_tenant_id)
                                        ->where('exist','=',0);
                                    $stmt = $selectStatement->execute();
                                    $data10= $stmt->fetch();
                                    if($data10!=null){
                                        if($array1['receive_city_id']==$data10['from_city_id']){
                                            $receiver_id=null;
                                            if( $contact_tenant_id==null|| $contact_tenant_id=="") {
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_name', '=', $customer_name)
                                                    ->where('customer_phone', '=', $customer_phone)
                                                    ->where('customer_city_id', '=',$array1['receive_city_id'])
                                                    ->where('customer_address', '=', $customer_address)
                                                    ->where('type', '=', $type)
//                                                    ->where('exist', '=', 0)
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data6 = $stmt->fetch();
                                                if ($data6 == null) {
                                                    $array4['tenant_id'] = $tenant_id;
                                                    $array4['exist'] = 0;
                                                    $array4['customer_name'] = $customer_name;
                                                    $array4['customer_phone'] = $customer_phone;
                                                    $array4['customer_city_id'] = $array1['receive_city_id'];
                                                    $array4['customer_address'] = $customer_address;
                                                    $array4['type']=$type;
                                                    $array4['contact_tenant_id']=$contact_tenant_id;
                                                    $array4['times']=$times;
                                                    $selectStatement = $database->select()
                                                        ->from('tenant')
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data7 = $stmt->fetch();
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->whereNull('wx_openid')
                                                        ->where('customer_id', '!=', $data7['contact_id'])
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data8 = $stmt->fetchAll();
                                                    $array4['customer_id'] = count($data8) + 10000000001;
                                                    $insertStatement = $database->insert(array_keys($array4))
                                                        ->into('customer')
                                                        ->values(array_values($array4));
                                                    $insertId = $insertStatement->execute(false);
                                                    $receiver_id="".$array4['customer_id'];
                                                } else {
                                                    $receiver_id="".$data6['customer_id'];
                                                    if($data6['times']==1){
//                                                        $a=$data6['times'];
                                                        if($data6['exist']==1){
                                                            $updateStatement = $database->update(array("exist"=>0))
                                                                ->table('customer')
                                                                ->where('customer_id','=',$data6['customer_id'])
                                                                ->where('tenant_id','=',$tenant_id);
                                                            $affectedRows = $updateStatement->execute();
                                                        }else{
                                                            $a=$data6['times']+1;
                                                            $updateStatement = $database->update(array('times'=>$a))
                                                                ->table('customer')
                                                                ->where('customer_id','=',$data6['customer_id'])
                                                                ->where('tenant_id','=',$tenant_id);
                                                            $affectedRows = $updateStatement->execute();
                                                        }
                                                    }else if($data6['times']>1){
                                                        $a=$data6['times']+1;
                                                        $updateStatement = $database->update(array('times'=>$a))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data6['customer_id'])
                                                            ->where('tenant_id','=',$tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }
                                            }else{
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_name','=',$customer_name)
                                                    ->where('customer_phone','=',$customer_phone)
                                                    ->where('customer_city_id','=',$array1['receive_city_id'])
                                                    ->where('customer_address','=',$customer_address)
                                                    ->where('type','=',$type)
                                                    ->where('contact_tenant_id','=',$contact_tenant_id)
//                                                    ->where('exist','=',0)
                                                    ->where('tenant_id', '=', $tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data6 = $stmt->fetch();
                                                if($data6==null){
                                                    $array4['tenant_id'] = $tenant_id;
                                                    $array4['exist'] = 0;
                                                    $array4['customer_name'] = $customer_name;
                                                    $array4['customer_phone'] = $customer_phone;
                                                    $array4['customer_city_id'] = $array1['receive_city_id'];
                                                    $array4['customer_address'] = $customer_address;
                                                    $array4['type']=$type;
                                                    $array4['contact_tenant_id']=$contact_tenant_id;
                                                    $array4['times']=$times;
                                                    $array4['tenant_id']=$tenant_id;
                                                    $array4['exist']=0;
                                                    $selectStatement = $database->select()
                                                        ->from('tenant')
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data7 = $stmt->fetch();
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->whereNull('wx_openid')
                                                        ->where('customer_id', '!=', $data7['contact_id'])
                                                        ->where('tenant_id', '=', $tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data8 = $stmt->fetchAll();
                                                    $array4['customer_id']=count($data8)+10000000001;
                                                    $insertStatement = $database->insert(array_keys($array4))
                                                        ->into('customer')
                                                        ->values(array_values($array4));
                                                    $insertId = $insertStatement->execute(false);
                                                    $receiver_id="".$array4['customer_id'];
                                                } else {
                                                    $receiver_id="".$data6['customer_id'];
                                                    if($data6['times']==1){
//                                                        $a=$data6['times'];
                                                        if($data6['exist']==1){
                                                            $updateStatement = $database->update(array('exist'=>0))
                                                                ->table('customer')
                                                                ->where('customer_id','=',$data6['customer_id'])
                                                                ->where('tenant_id','=',$tenant_id);
                                                            $affectedRows = $updateStatement->execute();
                                                        }else{
                                                            $a=$data6['times']+1;
                                                            $updateStatement = $database->update(array('times'=>$a))
                                                                ->table('customer')
                                                                ->where('customer_id','=',$data6['customer_id'])
                                                                ->where('tenant_id','=',$tenant_id);
                                                            $affectedRows = $updateStatement->execute();
                                                        }
                                                    }else if($data6['times']>1){
                                                        $a=$data6['times']+1;
                                                        $updateStatement = $database->update(array('times'=>$a))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data6['customer_id'])
                                                            ->where('tenant_id','=',$tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }
                                            }
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->whereNull('wx_openid')
                                                ->where('customer_name', '=', $partner_name)
                                                ->where('customer_phone', '=', $partner_phone)
                                                ->where('customer_city_id', '=', $partner_city_id)
                                                ->where('customer_address', '=', $partner_address)
                                                ->where('type', '=', $partner_type)
//                                                ->where('exist', '=', 0)
                                                ->where('tenant_id', '=', $contact_tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data12 = $stmt->fetch();
                                            if ($data12 == null) {
                                                $array5['tenant_id'] = $contact_tenant_id;
                                                $array5['exist'] = 0;
                                                $array5['customer_name'] = $partner_name;
                                                $array5['customer_phone'] = $partner_phone;
                                                $array5['customer_city_id'] = $partner_city_id;
                                                $array5['customer_address'] = $partner_address;
                                                $array5['type']=$partner_type;
                                                $array5['contact_tenant_id']=$tenant_id;
                                                $selectStatement = $database->select()
                                                    ->from('tenant')
                                                    ->where('tenant_id', '=', $contact_tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data13 = $stmt->fetch();
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->whereNull('wx_openid')
                                                    ->where('customer_id', '!=', $data13['contact_id'])
                                                    ->where('tenant_id', '=', $contact_tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data14 = $stmt->fetchAll();
                                                $array5['customer_id'] = count($data14) + 10000000001;
                                                $array5['times']=$partner_times;
                                                $insertStatement = $database->insert(array_keys($array5))
                                                    ->into('customer')
                                                    ->values(array_values($array5));
                                                $insertId = $insertStatement->execute(false);
                                            } else {
                                                if($data12['times']==1){
                                                    if($data12['exist']==1){
                                                        $updateStatement = $database->update(array("exist"=>0))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data12['customer_id'])
                                                            ->where('tenant_id','=',$contact_tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }else{
                                                        $a=$data12['times']+1;
                                                        $updateStatement = $database->update(array('times'=>$a))
                                                            ->table('customer')
                                                            ->where('customer_id','=',$data12['customer_id'])
                                                            ->where('tenant_id','=',$contact_tenant_id);
                                                        $affectedRows = $updateStatement->execute();
                                                    }
                                                }else if($data12['times']>1){
                                                    $a=$data12['times']+1;
                                                    $updateStatement = $database->update(array('times'=>$a))
                                                        ->table('customer')
                                                        ->where('customer_id','=',$data12['customer_id'])
                                                        ->where('tenant_id','=',$contact_tenant_id);
                                                    $affectedRows = $updateStatement->execute();
                                                }
                                            }
                                            $array1['exist'] = 0;
                                            $array1['receiver_id'] = $receiver_id;
                                            $array1['is_load'] = $is_load;
                                            $array1['exist']=0;
                                            $array1['is_alter']=0;
                                            $updateStatement = $database->update($array1)
                                                ->table('scheduling')
                                                ->where('scheduling_id', '=', $scheduling_id)
                                                ->where('tenant_id', '=', $tenant_id);
                                            $affectedRows = $updateStatement->execute();
                                            for ($x = 0; $x < count($array6); $x++) {
                                                $selectStatement = $database->select()
                                                    ->from('schedule_order')
                                                    ->where('schedule_id', '=', $scheduling_id)
                                                    ->where('tenant_id', '=', $tenant_id)
                                                    ->where("order_id",'=',$array6[$x]);
                                                $stmt = $selectStatement->execute();
                                                $data16 = $stmt->fetch();
                                                if($data16==null){
                                                    $insertStatement = $database->insert(array('tenant_id', 'schedule_id', 'order_id', 'exist'))
                                                        ->into('schedule_order')
                                                        ->values(array($tenant_id, $scheduling_id, $array6[$x], 0));
                                                    $insertId = $insertStatement->execute(false);
                                                    $updateStatement = $database->update(array('is_schedule' => 2))
                                                        ->table('orders')
                                                        ->where('tenant_id', '=', $tenant_id)
                                                        ->where('order_id', '=', $array6[$x]);
                                                    $affectedRows = $updateStatement->execute();
                                                }else{
                                                    $updateStatement = $database->update(array('exist' => 0))
                                                        ->table('schedule_order')
                                                        ->where('schedule_id', '=', $scheduling_id)
                                                        ->where('tenant_id', '=', $tenant_id)
                                                        ->where("order_id",'=',$array6[$x]);
                                                    $affectedRows = $updateStatement->execute();
                                                }

                                            }
                                            $deleteStatement = $database->delete()
                                                ->from('schedule_order')
                                                ->where('schedule_id', '=', $scheduling_id)
                                                ->where('tenant_id', '=', $tenant_id)
                                                ->where("exist",'=',1);
                                            $affectedRows = $deleteStatement->execute();
                                            echo json_encode(array("result" => "0", "desc" => "success"));
                                        }else{
                                            $selectStatement = $database->select()
                                                ->from('city')
                                                ->where('id','=',$data10['from_city_id']);
                                            $stmt = $selectStatement->execute();
                                            $data11 = $stmt->fetch();
                                            echo json_encode(array("result" => "8", "desc" => "收货公司所在城市不匹配",'receivecityname'=>$data11['name']));
                                        }
                                    }else{
                                        echo json_encode(array("result" => "7", "desc" => "该租户公司不存在"));
                                    }
                                }
                            }else{
                                echo json_encode(array("result" => "6", "desc" => "该车辆已经被加入黑名单"));
                            }
                        }else{
                            echo json_encode(array("result" => "10", "desc" => "驾驶员正在修改个人资料"));
                        }
                    }else{
                        echo json_encode(array("result" => "5", "desc" => "该车辆还未在交付帮手上注册过"));
                    }
                }else{
                    echo json_encode(array("result" => "4", "desc" => "收货城市不存在"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "发货城市不存在"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少收货城市名称"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少发货城市名称"));
    }
});



$app->put('/recoverSchedulingOrder',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('is_alter', '=',2)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',1);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id', '=', $data[$x]['receiver_id'])
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                if($data2!=null){
                    if($data2['exist']==1){
                        $updateStatement = $database->update(array('exist'=>0))
                            ->table('customer')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('customer_id','=',$data2['customer_id']);
                        $affectedRows = $updateStatement->execute();
                    }else if($data2['exist']==0){
                        $a=$data2['times']+1;
                        $updateStatement = $database->update(array('times'=>$a))
                            ->table('customer')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('customer_id','=',$data2['customer_id']);
                        $affectedRows = $updateStatement->execute();
                    }
                }
                if($data2['contact_tenant_id']!=null){
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('contact_tenant_id', '=', $tenant_id)
                        ->where('tenant_id', '=', $data2['contact_tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    if($data4!=null){
                        if($data4['exist']==1){
                            $updateStatement = $database->update(array('exist'=>0))
                                ->table('customer')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('customer_id','=',$data4['customer_id']);
                            $affectedRows = $updateStatement->execute();
                        }else if($data4['exist']==0){
                            $a=$data4['times']+1;
                            $updateStatement = $database->update(array('times'=>$a))
                                ->table('customer')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('customer_id','=',$data4['customer_id']);
                            $affectedRows = $updateStatement->execute();
                        }
                    }
                }
                $updateStatement = $database->update(array('exist'=>0))
                    ->table('schedule_order')
                    ->where('schedule_id', '=', $data[$x]['scheduling_id'])
                    ->where('tenant_id', '=', $tenant_id);
                $affectedRows = $updateStatement->execute();
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('schedule_id', '=', $data[$x]['scheduling_id'])
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                for($i=0;$i<count($data3);$i++){
                    $updateStatement = $database->update(array('is_schedule'=>2))
                        ->table('orders')
                        ->where('order_id','=',$data3[$i]['order_id'])
                        ->where('tenant_id','=',$tenant_id);
                    $affectedRows = $updateStatement->execute();
                }
            }
        }
        $updateStatement = $database->update(array('is_alter'=>0,"exist"=>0))
            ->table('scheduling')
            ->where('is_alter', '=',2)
            ->where('tenant_id', '=', $tenant_id)
            ->where("exist",'=',1);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});





$app->run();
function localhost(){
    return connect();
}
?>