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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
                        ->from('customer')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('customer_id', '=', $data[$i]['sender_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('customer_id', '=', $data[$i]['receiver_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('goods_package')
                        ->where('goods_package_id', '=', $data2['goods_package_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data8['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $data[$i]['lorry']=$data1;
                    $data[$i]['goods']=$data2;
                    $data[$i]['goods']['goods_package']=$data5;
                    $data[$i]['sender']=$data3;
                    $data[$i]['receiver']=$data4;
                    $data[$i]['sender']['city']=$data6;
                    $data[$i]['receiver']['city']=$data8;
                    $data[$i]['sender']['province']=$data7;
                    $data[$i]['receiver']['province']=$data9;
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
            ->where('scheduling.scheduling_status', '=',1)
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
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
            ->where('scheduling.scheduling_status', '=',1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$j]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$j]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$j]['lorry']=$data1;
            $data[$j]['goods']=$data2;
            $data[$j]['goods']['goods_package']=$data5;
            $data[$j]['sender']=$data3;
            $data[$j]['receiver']=$data4;
            $data[$j]['sender']['city']=$data6;
            $data[$j]['receiver']['city']=$data8;
            $data[$j]['sender']['province']=$data7;
            $data[$j]['receiver']['province']=$data9;
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
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$j]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$j]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$j]['lorry']=$data1;
            $data[$j]['goods']=$data2;
            $data[$j]['goods']['goods_package']=$data5;
            $data[$j]['sender']=$data3;
            $data[$j]['receiver']=$data4;
            $data[$j]['sender']['city']=$data6;
            $data[$j]['receiver']['city']=$data8;
            $data[$j]['sender']['province']=$data7;
            $data[$j]['receiver']['province']=$data9;
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
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$j]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$j]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$j]['lorry']=$data1;
            $data[$j]['goods']=$data2;
            $data[$j]['goods']['goods_package']=$data5;
            $data[$j]['sender']=$data3;
            $data[$j]['receiver']=$data4;
            $data[$j]['sender']['city']=$data6;
            $data[$j]['receiver']['city']=$data8;
            $data[$j]['sender']['province']=$data7;
            $data[$j]['receiver']['province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'i'=>$i));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrderList0',function()use($app){
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
            ->where('scheduling.scheduling_status', '=',1)
            ->where('schedule_order.exist', '=', 0)
            ->where('scheduling.exist', '=', 0)
            ->where('orders.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$j]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$j]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$j]['lorry']=$data1;
            $data[$j]['goods']=$data2;
            $data[$j]['goods']['goods_package']=$data5;
            $data[$j]['sender']=$data3;
            $data[$j]['receiver']=$data4;
            $data[$j]['sender']['city']=$data6;
            $data[$j]['receiver']['city']=$data8;
            $data[$j]['sender']['province']=$data7;
            $data[$j]['receiver']['province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'ii'=>$i));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulingOrderList1',function()use($app){
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
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$j]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data[$j]['order_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$j]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$j]['lorry']=$data1;
            $data[$j]['goods']=$data2;
            $data[$j]['goods']['goods_package']=$data5;
            $data[$j]['sender']=$data3;
            $data[$j]['receiver']=$data4;
            $data[$j]['sender']['city']=$data6;
            $data[$j]['receiver']['city']=$data8;
            $data[$j]['sender']['province']=$data7;
            $data[$j]['receiver']['province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data,'i'=>$i));
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
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data2['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['goods']=$data2;
            $data[$i]['goods']['goods_package']=$data5;
            $data[$i]['sender']=$data3;
            $data[$i]['receiver']=$data4;
            $data[$i]['sender']['city']=$data6;
            $data[$i]['receiver']['city']=$data8;
            $data[$i]['sender']['province']=$data7;
            $data[$i]['receiver']['province']=$data9;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>