<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 13:51
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getGoodsOrders0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',0)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',1)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',2)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',3)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',4)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',0)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',1)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',2)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',3)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',4)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id','DESC')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});


$app->get('/getGoodsOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_id=$app->request->get('order_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_id','=',$order_id)
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['sender_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data6['pid']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['receiver_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data7['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('exception')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exception_id','=',$data1[$i]['exception_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
                $data1[$i]['exception']=$data10;
            }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/searchGoodsOrders0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',1)
                ->where('orders.is_schedule','=',0)
                ->where('orders.inventory_type','=',1)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/searchGoodsOrders1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',1)
                ->where('orders.is_schedule','=',0)
                ->where('orders.inventory_type','=',2)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/searchGoodsOrders2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',1)
                ->where('orders.is_schedule','=',0)
                ->where('orders.inventory_type','=',3)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/searchGoodsOrders3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',1)
                ->where('orders.is_schedule','=',0)
                ->where('orders.inventory_type','=',4)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/searchGoodsOrders3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',1)
                ->where('orders.is_schedule','=',0)
                ->where('orders.inventory_type','=',4)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_status','=',5)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['sender_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data6['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data7['pid']);
            $stmt = $selectStatement->execute();
            $data9 = $stmt->fetch();
            $selectStament=$database->select()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders6',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset = $app->request->get('offset');
    $size= $app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($offset!=null||$offset!=''){
            if($size!=null||$size!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',5)
                    ->where('orders.exist','=',0)
                    ->orderBy("orders.order_id")
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();

                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('exception')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('exception_id','=',$data1[$i]['exception_id']);
                    $stmt=$selectStament->execute();
                    $data10=$stmt->fetch();
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                    $data1[$i]['exception']=$data10;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'size为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'offset为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrderList',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_id = $app->request->get('order_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.exist','=',0)
                    ->where('orders.order_id','=',$order_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['sender_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data6['pid']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['receiver_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data7['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
            }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'运单id为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $customer_name = $app->request->get('customer_name');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name!=null||$customer_name!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->join('customer', 'customer.customer_id', '=', 'orders.sender_id', 'INNER')
                ->where('customer.tenant_id','=',$tenant_id)
                ->where('customer.customer_name','=',$customer_name)
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['sender_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data6['pid']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['receiver_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data7['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
            }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'客户名字为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders7',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $customer_name = $app->request->get('customer_name');
    $offset = $app->request->get('offset');
    $size= $app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name!=null||$customer_name!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->join('customer', 'customer.customer_id', '=', 'orders.sender_id', 'INNER')
                ->where('customer.tenant_id','=',$tenant_id)
                ->where('customer.customer_name','=',$customer_name)
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.exist','=',0)
                ->orderBy('orders.order_id')
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['sender_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data6['pid']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1[$i]['receiver_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data7['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
            }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'客户名字为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrder1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $order_id=$app->request->get('order_id');
        if($order_id!=null||$order_id!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('order_id','=',$order_id)
                ->where('exist','=',0)
                ->orderBy('datetime1','DESC');
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for($i=0;$i<count($data1);$i++){
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('tenant_id','=',$data1[$i]['tenant_id'])
                    ->where('order_id','=',$order_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data2['goods_package_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$data1[$i]['tenant_id'])
                    ->where('customer_id','=',$data1[$i]['sender_id']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data5['pid']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$data1[$i]['tenant_id'])
                    ->where('customer_id','=',$data1[$i]['receiver_id']);
                $stmt=$selectStament->execute();
                $data7=$stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data7['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data8['pid']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $selectStament=$database->select()
                    ->from('inventory_loc')
                    ->where('tenant_id','=',$data1[$i]['tenant_id'])
                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('exception')
                    ->where('tenant_id','=',$data1[$i]['tenant_id'])
                    ->where('exception_id','=',$data1[$i]['exception_id']);
                $stmt=$selectStament->execute();
                $data11=$stmt->fetch();
                if($data2==null){
                    $data2=array();
                }
                $data1[$i] = array_merge($data1[$i], $data2);
                $data1[$i]['goods_package']=$data3;
                $data1[$i]['sender']=$data4;
                $data1[$i]['sender']['sender_city']=$data5;
                $data1[$i]['sender']['sender_province']=$data6;
                $data1[$i]['receiver']=$data7;
                $data1[$i]['receiver']['receiver_city']=$data8;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data10;
                $data1[$i]['exception']=$data11;
            }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'size为空'));
        }

});

$app->run();
function localhost(){
    return connect();
}
?>