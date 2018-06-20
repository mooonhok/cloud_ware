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
    $array=array();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            if(!(preg_match('/[a-zA-Z]/',$data1[$i]['order_id']))){
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
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id', '=', $data1[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data10 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('id','<',$data10['id'])
                    ->where('order_id', '=', $data1[$i]['order_id'])
                    ->orderBy('id','DESC');
                $stmt = $selectStatement->execute();
                $data11 = $stmt->fetchAll();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('id','>',$data10['id'])
                    ->where('order_id', '=', $data1[$i]['order_id'])
                    ->orderBy('id');
                $stmt = $selectStatement->execute();
                $data12 = $stmt->fetchAll();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->whereNotNull('reach_city')
                    ->where('order_id', '=', $data1[$i]['order_id'])
                    ->orderBy('id','DESC');
                $stmt = $selectStatement->execute();
                $data13 = $stmt->fetchAll();
                $next_cost='';
                $last_order_status='';
                $last_reach_city='';
                if($data12!=null){
                    $next_cost=$data12[0]['transfer_cost'];
                    $last_order_status=$data12[(count($data12)-1)]['order_status'];
                }
                if($data13!=null){
                    $last_reach_city=$data13[0]['reach_city'];
                }
                $is_transfer=null;
                if($data11!=null){
                    $is_transfer=$data11[0]['is_transfer'];
                }
                $data1[$i]['last_reach_city']=$last_reach_city;
                $data1[$i]['last_order_status']=$last_order_status;
                $data1[$i]['next_cost']=$next_cost;
                $data1[$i]['pre_company']=$is_transfer;
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
                array_push($array,$data1[$i]);
            }

        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$array));
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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;
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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;

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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;

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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;

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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;
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
    $array=array();
    $array1=array();
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_status')
                    ->orderBy('orders.order_datetime1','DESC')
                    ->orderBy('orders.id','DESC');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($j=0;$j<count($data1);$j++){
                    if(!(preg_match('/[a-zA-Z]/',$data1[$j]['order_id']))){
                        array_push($array,$data1[$j]);
                    }
                }
                $num=0;
                if($offset<count($array)&&$offset<(count($array)-$size)){
                    $num=$offset+$size;
                }else{
                    $num=count($array);
                }
                for($i=$offset;$i<$num;$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$array[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$array[$i]['sender_id']);
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
                        ->where('customer_id','=',$array[$i]['receiver_id']);
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
                        ->where('inventory_loc_id','=',$array[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $array[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $array[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $array[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNotNull('reach_city')
                        ->where('order_id', '=', $array[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $array[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data14 = $stmt->fetchAll();
                    $next_cost='';
                    $last_order_status='';
                    $last_reach_city='';
                    $last_is_sign=$data14[0]['is_sign'];
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                        $last_order_status=$data12[(count($data12)-1)]['order_status'];
                    }
                    if($data13!=null){
                        $last_reach_city=$data13[0]['reach_city'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $array[$i]['last_reach_city']=$last_reach_city;
                    $array[$i]['last_order_status']=$last_order_status;
                    $array[$i]['last_is_sign']=$last_is_sign;
                    $array[$i]['next_cost']=$next_cost;
                    $array[$i]['pre_company']=$is_transfer;
                    $array[$i]['goods_package']=$data2;
                    $array[$i]['sender']=$data3;
                    $array[$i]['sender']['sender_city']=$data6;
                    $array[$i]['sender']['sender_province']=$data8;
                    $array[$i]['receiver']=$data4;
                    $array[$i]['receiver']['receiver_city']=$data7;
                    $array[$i]['receiver']['receiver_province']=$data9;
                    $array[$i]['inventory_loc']=$data5;
                    array_push($array1,$array[$i]);
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$array1));
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
                $selectStament=$database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id','=',$data1[$i]['order_id']);
                $stmt=$selectStament->execute();
                $data11=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('orders')
                    ->where('id','<',$data11['id'])
                    ->where('order_id','=',$data1[$i]['order_id'])
                    ->orderBy('id','DESC')
                    ->limit(1);
                $stmt=$selectStament->execute();
                $data12=$stmt->fetch();
                $is_transfer=null;
                if($data12!=null){
                    $is_transfer=$data12['is_transfer'];
                }
                $data1[$i]['pre_company']=$is_transfer;
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
    $inventory_type=$app->request->get('inventory_type');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.is_schedule','=',0)
            ->where('orders.inventory_type','=',$inventory_type)
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
    $order_id=$app->request->get('order_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.order_id','=',$order_id)
            ->where('orders.is_schedule','=',0)
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

//$app->get('/searchGoodsOrders2',function()use($app){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $tenant_id=$app->request->headers->get('tenant-id');
//    if($tenant_id!=null||$tenant_id!=''){
//            $selectStatement = $database->select()
//                ->from('orders')
//                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
//                ->where('goods.tenant_id','=',$tenant_id)
//                ->where('orders.tenant_id','=',$tenant_id)
//                ->where('orders.order_status','=',1)
//                ->where('orders.is_schedule','=',0)
//                ->where('orders.inventory_type','=',3)
//                ->where('orders.exist','=',0);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetchAll();
//        for($i=0;$i<count($data1);$i++){
//            $selectStament=$database->select()
//                ->from('goods_package')
//                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
//            $stmt=$selectStament->execute();
//            $data2=$stmt->fetch();
//            $selectStament=$database->select()
//                ->from('customer')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('customer_id','=',$data1[$i]['sender_id']);
//            $stmt=$selectStament->execute();
//            $data3=$stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data3['customer_city_id']);
//            $stmt = $selectStatement->execute();
//            $data6 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data6['pid']);
//            $stmt = $selectStatement->execute();
//            $data8 = $stmt->fetch();
//            $selectStament=$database->select()
//                ->from('customer')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('customer_id','=',$data1[$i]['receiver_id']);
//            $stmt=$selectStament->execute();
//            $data4=$stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data4['customer_city_id']);
//            $stmt = $selectStatement->execute();
//            $data7 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data7['pid']);
//            $stmt = $selectStatement->execute();
//            $data9 = $stmt->fetch();
//            $selectStament=$database->select()
//                ->from('inventory_loc')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//            $stmt=$selectStament->execute();
//            $data5=$stmt->fetch();
//            $data1[$i]['goods_package']=$data2;
//            $data1[$i]['sender']=$data3;
//            $data1[$i]['sender']['sender_city']=$data6;
//            $data1[$i]['sender']['sender_province']=$data8;
//            $data1[$i]['receiver']=$data4;
//            $data1[$i]['receiver']['receiver_city']=$data7;
//            $data1[$i]['receiver']['receiver_province']=$data9;
//            $data1[$i]['inventory_loc']=$data5;
//        }
//            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
//    }else{
//        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
//    }
//});
//
//$app->get('/searchGoodsOrders3',function()use($app){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $tenant_id=$app->request->headers->get('tenant-id');
//    if($tenant_id!=null||$tenant_id!=''){
//            $selectStatement = $database->select()
//                ->from('orders')
//                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
//                ->where('goods.tenant_id','=',$tenant_id)
//                ->where('orders.tenant_id','=',$tenant_id)
//                ->where('orders.order_status','=',1)
//                ->where('orders.is_schedule','=',0)
//                ->where('orders.inventory_type','=',4)
//                ->where('orders.exist','=',0);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetchAll();
//        for($i=0;$i<count($data1);$i++){
//            $selectStament=$database->select()
//                ->from('goods_package')
//                ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
//            $stmt=$selectStament->execute();
//            $data2=$stmt->fetch();
//            $selectStament=$database->select()
//                ->from('customer')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('customer_id','=',$data1[$i]['sender_id']);
//            $stmt=$selectStament->execute();
//            $data3=$stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data3['customer_city_id']);
//            $stmt = $selectStatement->execute();
//            $data6 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data6['pid']);
//            $stmt = $selectStatement->execute();
//            $data8 = $stmt->fetch();
//            $selectStament=$database->select()
//                ->from('customer')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('customer_id','=',$data1[$i]['receiver_id']);
//            $stmt=$selectStament->execute();
//            $data4=$stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data4['customer_city_id']);
//            $stmt = $selectStatement->execute();
//            $data7 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data7['pid']);
//            $stmt = $selectStatement->execute();
//            $data9 = $stmt->fetch();
//            $selectStament=$database->select()
//                ->from('inventory_loc')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//            $stmt=$selectStament->execute();
//            $data5=$stmt->fetch();
//            $data1[$i]['goods_package']=$data2;
//            $data1[$i]['sender']=$data3;
//            $data1[$i]['sender']['sender_city']=$data6;
//            $data1[$i]['sender']['sender_province']=$data8;
//            $data1[$i]['receiver']=$data4;
//            $data1[$i]['receiver']['receiver_city']=$data7;
//            $data1[$i]['receiver']['receiver_province']=$data9;
//            $data1[$i]['inventory_loc']=$data5;
//        }
//            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
//    }else{
//        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
//    }
//});

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

$app->get('/getGoodsOrdersNum6',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('city','city.id','=','customer.customer_city_id','INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('customer.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',1)
            ->whereLike('city.name','%'.$reach_city.'%')
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

$app->get('/getGoodsOrdersNum7',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('city','city.id','=','customer.customer_city_id','INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('customer.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',3)
            ->whereLike('city.name','%'.$reach_city.'%')
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

$app->get('/getGoodsOrdersNum8',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('city','city.id','=','customer.customer_city_id','INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('customer.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',4)
            ->whereLike('city.name','%'.$reach_city.'%')
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
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name!=null||$customer_name!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->join('customer', 'customer.customer_id', '=', 'orders.sender_id', 'INNER')
                ->where('customer.tenant_id','=',$tenant_id)
                ->whereLike('orders.order_id',$tenant_num.'%')
                ->where('customer.customer_name','=',$customer_name)
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $data1a=array();
            for($i=0;$i<count($data1);$i++){
                $cc=0;
                for($j=0;$j<$i;$j++){
                    if($data1[$j]['sender_id']==$data1[$i]['sender_id']&&$data1[$j]['receiver_id']==$data1[$i]['receiver_id']&&$data1[$j]['goods_name']==$data1[$i]['goods_name']){
                        break;
                    }
                    $cc++;
                }
                if($cc==$i){
//                $selectStament=$database->select()
//                    ->from('goods_package')
//                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
//                $stmt=$selectStament->execute();
//                $data2=$stmt->fetch();
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
//                $selectStament=$database->select()
//                    ->from('inventory_loc')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//                $stmt=$selectStament->execute();
//                $data5=$stmt->fetch();
                    $data1a[$i]=$data1[$i];
//                $data1a[$i]['goods_package']=$data2;
                    $data1a[$i]['sender']=$data3;
                    $data1a[$i]['sender']['sender_city']=$data6;
                    $data1a[$i]['sender']['sender_province']=$data8;
                    $data1a[$i]['receiver']=$data4;
                    $data1a[$i]['receiver']['receiver_city']=$data7;
                    $data1a[$i]['receiver']['receiver_province']=$data9;
//                $data1a[$i]['inventory_loc']=$data5;
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1a,'count'=>count($data1a)));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'客户名字为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        $data1a=array();
        for($i=0;$i<count($data1);$i++){
            $cc=0;
            for($j=0;$j<$i;$j++){
                if($data1[$j]['sender_id']==$data1[$i]['sender_id']&&$data1[$j]['receiver_id']==$data1[$i]['receiver_id']&&$data1[$j]['goods_name']==$data1[$i]['goods_name']){
                    break;
                }
                $cc++;
            }
            if($cc==$i){
//                $selectStament=$database->select()
//                    ->from('goods_package')
//                    ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
//                $stmt=$selectStament->execute();
//                $data2=$stmt->fetch();
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
//                $selectStament=$database->select()
//                    ->from('inventory_loc')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//                $stmt=$selectStament->execute();
//                $data5=$stmt->fetch();
                $data1a[$i]=$data1[$i];
//                $data1a[$i]['goods_package']=$data2;
                $data1a[$i]['sender']=$data3;
                $data1a[$i]['sender']['sender_city']=$data6;
                $data1a[$i]['sender']['sender_province']=$data8;
                $data1a[$i]['receiver']=$data4;
                $data1a[$i]['receiver']['receiver_city']=$data7;
                $data1a[$i]['receiver']['receiver_province']=$data9;
//                $data1a[$i]['inventory_loc']=$data5;
            }
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1a,'count'=>count($data1a)));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders7',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
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
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $data1[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13= $stmt->fetchAll();
            $last_order_status='';
            $next_cost='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
               $data1[$i]['last_reach_city']=$last_reach_city;
               $data1[$i]['last_order_status']=$last_order_status;
               $data1[$i]['next_cost']=$next_cost;
                $data1[$i]['pre_company']=$is_transfer;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
            }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1,'count'=>count($data1)));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});


$app->get('/limitGoodsOrders7',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $customer_name = $app->request->get('customer_name');
    $tenant_num=$app->request->get('tenant_num');
    $offset = $app->request->get('offset');
    $size= $app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name!=null||$customer_name!=''){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->join('customer', 'customer.customer_id', '=', 'orders.sender_id', 'INNER')
                ->whereLike('orders.order_id',$tenant_num.'%')
                ->where('customer.tenant_id','=',$tenant_id)
                ->where('customer.customer_name','=',$customer_name)
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.exist','=',0)
                ->orderBy('orders.order_id','DESC')
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $data1a=array();
            $data1b=array();
            for($i=0;$i<count($data1);$i++){
                $cc=0;
                for($j=0;$j<$i;$j++){
                    if($data1[$j]['sender_id']==$data1[$i]['sender_id']&&$data1[$j]['receiver_id']==$data1[$i]['receiver_id']&&$data1[$j]['goods_name']==$data1[$i]['goods_name']){
                        break;
                    }
                    $cc++;
                }
                if($cc==$i) {
                    $data1a[$i]=$data1[$i];
                }
            }
            $num=0;
            if($offset<count($data1a)&&$offset<(count($data1a)-$size)){
                $num=$offset+$size;
            }else{
                $num=count($data1a);
            }
            for($g=$offset;$g<$num;$g++){
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1a[$g]['sender_id']);
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
                    ->where('customer_id','=',$data1a[$g]['receiver_id']);
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
//                $selectStament=$database->select()
//                    ->from('inventory_loc')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//                $stmt=$selectStament->execute();
//                $data5=$stmt->fetch();
//                $data1a[$i]['goods_package']=$data2;
                $data1a[$g]['sender']=$data3;
                $data1a[$g]['sender']['sender_city']=$data6;
                $data1a[$g]['sender']['sender_province']=$data8;
                $data1a[$g]['receiver']=$data4;
                $data1a[$g]['receiver']['receiver_city']=$data7;
                $data1a[$g]['receiver']['receiver_province']=$data9;
//                $data1a[$i]['inventory_loc']=$data5;
                $data1b[$g]=$data1a[$g];
            }



            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1b));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'客户名字为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders8',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereLike('orders.order_id',$tenant_num.'%')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id','DESC');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                $data1a=array();
                $data1b=array();
                for($i=0;$i<count($data1);$i++){
                    $cc=0;
//                    if($i==0){
//                      array_push($data1a,$data1[$i]);
//                    }
                    for($j=0;$j<$i;$j++){
                        if($data1[$j]['sender_id']==$data1[$i]['sender_id']&&$data1[$j]['receiver_id']==$data1[$i]['receiver_id']&&$data1[$j]['goods_name']==$data1[$i]['goods_name']){
                            break;
                        }
                        $cc++;
                    }
                    if($cc==$i) {
                        array_push($data1a,$data1[$i]);
//                        $data1a[$i]=$data1[$i];
                    }
                }
                $num=0;
                if($offset<count($data1a)&&$offset<(count($data1a)-$size)){
                    $num=$offset+$size;
                }else{
                    $num=count($data1a);
                }
                for($g=$offset;$g<$num;$g++){
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$data1a[$g]['sender_id']);
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
                        ->where('customer_id','=',$data1a[$g]['receiver_id']);
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
//                $selectStament=$database->select()
//                    ->from('inventory_loc')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
//                $stmt=$selectStament->execute();
//                $data5=$stmt->fetch();
//                $data1a[$i]['goods_package']=$data2;
                    $data1a[$g]['sender']=$data3;
                    $data1a[$g]['sender']['sender_city']=$data6;
                    $data1a[$g]['sender']['sender_province']=$data8;
                    $data1a[$g]['receiver']=$data4;
                    $data1a[$g]['receiver']['receiver_city']=$data7;
                    $data1a[$g]['receiver']['receiver_province']=$data9;
//                $data1a[$i]['inventory_loc']=$data5;
//                    $data1b[$g]=$data1a[$g];
                    array_push($data1b,$data1a[$g]);
                }



                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1b));
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

$app->get('/limitGoodsOrders12',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereLike('orders.order_id',$tenant_num.'%')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_status')
                    ->orderBy('orders.order_datetime1','DESC')
                    ->orderBy('orders.id','DESC')
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
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $data1[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNotNull('reach_city')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data14 = $stmt->fetchAll();
                    $next_cost='';
                    $last_order_status='';
                    $last_reach_city='';
                    $last_is_sign=$data14[0]['is_sign'];
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                        $last_order_status=$data12[(count($data12)-1)]['order_status'];

                    }
                    if($data13!=null){
                        $last_reach_city=$data13[0]['reach_city'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $data1[$i]['last_reach_city']=$last_reach_city;
                    $data1[$i]['last_order_status']=$last_order_status;
                    $data1[$i]['last_is_sign']=$last_is_sign;
                    $data1[$i]['next_cost']=$next_cost;
                    $data1[$i]['pre_company']=$is_transfer;
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
            ->orderBy('order_datetime1');
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

$app->get('/getGoodsOrders4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_id=$app->request->get('order_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('orders.order_id','=',$order_id)
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
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
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $data1[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $data1[$i]['last_reach_city']=$last_reach_city;
            $data1[$i]['last_order_status']=$last_order_status;
            $data1[$i]['next_cost']=$next_cost;
            $data1[$i]['pre_company']=$is_transfer;
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

$app->get('/getGoodsOrders5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $customer_name=$app->request->get('customer_name');
    if($tenant_id!=null||$tenant_id!=''){
        $data1=array();
        $data10=array();
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->join('customer','customer.customer_id','=','orders.sender_id','INNER')
            ->where('customer.tenant_id','=',$tenant_id)
            ->where('customer.customer_name','=',$customer_name)
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('city','city.id','=','customer.customer_city_id','INNER')
            ->where('customer.tenant_id','=',$tenant_id)
            ->whereLike('city.name','%'.$customer_name."%")
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0))
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data10 = $stmt->fetchAll();
        $data1 = array_merge($data1, $data10);
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
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $data1[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $data1[$i]['last_reach_city']=$last_reach_city;
            $data1[$i]['last_order_status']=$last_order_status;

            $data1[$i]['next_cost']=$next_cost;
            $data1[$i]['pre_company']=$is_transfer;
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

$app->get('/getGoodsOrders6',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_datetime1=$app->request->get('order_datetime1');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereLike('orders.order_datetime1',$order_datetime1.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
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
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $data1[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $data1[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $data1[$i]['last_reach_city']=$last_reach_city;
            $data1[$i]['last_order_status']=$last_order_status;
            $data1[$i]['next_cost']=$next_cost;
            $data1[$i]['pre_company']=$is_transfer;
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

$app->get('/getGoodsOrders9',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereNotLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0)
//            ->orderBy('orders.order_id','DESC')
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id', '<', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC')
                ->limit(1);
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetch();
            if ($data3b&&($data3b['is_transfer']==0)) {
                array_push($dataa,$data2[$g]);
            }
        }
        for($i=0;$i<count($dataa);$i++) {
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13= $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$dataa));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});


$app->get('/getGoodsOrders8',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
//        $selectStatement = $database->select()
//            ->from('tenant')
//            ->where('tenant_id','=',$tenant_id);
//        $stmt = $selectStatement->execute();
//        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereNotLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
//            ->orderBy('orders.order_id','DESC')
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id', '<', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC');
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetchAll();
            if ($data3b&&($data3b[0]['is_transfer']==1)) {
                array_push($dataa,$data2[$g]);
            }
        }
        for($i=0;$i<count($dataa);$i++) {
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
                $last_reach_city=$data12[(count($data12)-1)]['reach_city'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$dataa));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders10',function()use($app){
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
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('transfer_cost')
                ->where('id', '>', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC');
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetchAll();
            if ($data3b) {
                array_push($dataa,$data2[$g]);
            }
        }
        for($i=0;$i<count($dataa);$i++) {
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$dataa));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});


$app->get('/limitGoodsOrders13',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $tenant_num=$app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereNotLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        $datab=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id', '<', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC');
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetchAll();
            if ($data3b&&($data3b[0]['is_transfer']==1)) {
                array_push($dataa,$data2[$g]);
            }
        }

        $num=0;
        if($offset<count($dataa)&&$offset<(count($dataa)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($dataa);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data14 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            $last_is_sign=$data14[0]['is_sign'];
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];

            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['last_is_sign']=$last_is_sign;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
            array_push($datab,$dataa[$i]);
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$datab));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});


$app->get('/limitGoodsOrders14',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $tenant_num=$app->request->get('tenant_num');
    $datab=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->whereNotLike('orders.order_id',$tenant_num.'%')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id', '<', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC');
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetchAll();
            if ($data3b&&($data3b[0]['is_transfer']==0)) {
                array_push($dataa,$data2[$g]);
            }
        }
        $num=0;
        if($offset<count($dataa)&&$offset<(count($dataa)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($dataa);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data14 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            $last_is_sign=$data14[0]['is_sign'];
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];

            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['last_is_sign']=$last_is_sign;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
            array_push($datab,$dataa[$i]);
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$datab));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders15',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0)
            ->orderBy('orders.order_status')
            ->orderBy('orders.order_datetime1','DESC')
            ->orderBy('orders.id','DESC');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $dataa=array();
        $datab=array();
        for($g=0;$g<count($data2);$g++) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $data2[$g]['tenant_id'])
                ->where('order_id', '=', $data2[$g]['order_id']);
            $stmt = $selectStatement->execute();
            $data3a = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('transfer_cost')
                ->where('id', '>', $data3a['id'])
                ->where('order_id', '=', $data2[$g]['order_id'])
                ->orderBy('id', 'DESC');
            $stmt = $selectStatement->execute();
            $data3b = $stmt->fetchAll();
            if ($data3b) {
                array_push($dataa,$data2[$g]);
            }
        }
        $num=0;
        if($offset<count($dataa)&&$offset<(count($dataa)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($dataa);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStament=$database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$dataa[$i]['goods_package_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$dataa[$i]['sender_id']);
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
                ->where('customer_id','=',$dataa[$i]['receiver_id']);
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
                ->where('inventory_loc_id','=',$dataa[$i]['inventory_loc_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id', '=', $dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','<',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data11 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('id','>',$data10['id'])
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id');
            $stmt = $selectStatement->execute();
            $data12 = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereNotNull('reach_city')
                ->where('order_id', '=', $dataa[$i]['order_id'])
                ->orderBy('id','DESC');
            $stmt = $selectStatement->execute();
            $data13 = $stmt->fetchAll();
            $next_cost='';
            $last_order_status='';
            $last_reach_city='';
            if($data12!=null){
                $next_cost=$data12[0]['transfer_cost'];
                $last_order_status=$data12[(count($data12)-1)]['order_status'];
            }
            if($data13!=null){
                $last_reach_city=$data13[0]['reach_city'];
            }
            $is_transfer=null;
            if($data11!=null){
                $is_transfer=$data11[0]['is_transfer'];
            }
            $dataa[$i]['last_reach_city']=$last_reach_city;
            $dataa[$i]['last_order_status']=$last_order_status;
            $dataa[$i]['next_cost']=$next_cost;
            $dataa[$i]['pre_company']=$is_transfer;
            $dataa[$i]['goods_package']=$data2;
            $dataa[$i]['sender']=$data3;
            $dataa[$i]['sender']['sender_city']=$data6;
            $dataa[$i]['sender']['sender_province']=$data8;
            $dataa[$i]['receiver']=$data4;
            $dataa[$i]['receiver']['receiver_city']=$data7;
            $dataa[$i]['receiver']['receiver_province']=$data9;
            $dataa[$i]['inventory_loc']=$data5;
            array_push($datab,$dataa[$i]);
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$datab));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});


$app->get('/limitGoodsOrders16',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                    ->join('city','city.id','=','customer.customer_city_id','INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('customer.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',1)
                    ->where('orders.exist','=',0)
                    ->whereLike('city.name','%'.$reach_city.'%')
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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;

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

$app->get('/limitGoodsOrders17',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                    ->join('city','city.id','=','customer.customer_city_id','INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('customer.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',3)
                    ->whereLike('city.name','%'.$reach_city.'%')
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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;

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

$app->get('/limitGoodsOrders18',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $reach_city=$app->request->get('reach_city');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                    ->join('city','city.id','=','customer.customer_city_id','INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('customer.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',4)
                    ->whereLike('city.name','%'.$reach_city.'%')
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
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$data1[$i]['order_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('orders')
                        ->where('id','<',$data11['id'])
                        ->where('order_id','=',$data1[$i]['order_id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt=$selectStament->execute();
                    $data12=$stmt->fetch();
                    $is_transfer=null;
                    if($data12!=null){
                        $is_transfer=$data12['is_transfer'];
                    }
                    $data1[$i]['pre_company']=$is_transfer;
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

$app->get('/limitGoodsOrders9',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_id=$app->request->get('order_id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('orders.order_id','=',$order_id)
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.exist','=',0)
                    ->whereNotIn('orders.order_status',array(-1,-2,0))
                    ->orderBy('orders.order_datetime1','DESC')
                    ->orderBy('orders.id','DESC')
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
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $data1[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNotNull('reach_city')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data14 = $stmt->fetchAll();
                    $next_cost='';
                    $last_order_status='';
                    $last_reach_city='';
                    $last_is_sign=$data14[0]['is_sign'];
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                        $last_order_status=$data12[(count($data12)-1)]['order_status'];

                    }
                    if($data13!=null){
                        $last_reach_city=$data13[0]['reach_city'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $data1[$i]['last_reach_city']=$last_reach_city;
                    $data1[$i]['last_order_status']=$last_order_status;
                    $data1[$i]['last_is_sign']=$last_is_sign;
                    $data1[$i]['next_cost']=$next_cost;
                    $data1[$i]['pre_company']=$is_transfer;
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

$app->get('/limitGoodsOrders10',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $customer_name=$app->request->get('customer_name');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $datab=array();
                $data1=array();
                $data10=array();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->join('customer','customer.customer_id','=','orders.sender_id','INNER')
                    ->where('customer.customer_name','=',$customer_name)
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->whereNotIn('orders.order_status',array(-1,-2,0))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_status')
                    ->orderBy('orders.order_datetime1','DESC')
                    ->orderBy('orders.id','DESC');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();

                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                    ->join('city','city.id','=','customer.customer_city_id','INNER')
                    ->where('customer.tenant_id','=',$tenant_id)
                    ->whereLike('city.name','%'.$customer_name."%")
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->whereNotIn('orders.order_status',array(-1,-2,0))
                    ->orderBy('orders.order_status')
                    ->orderBy('orders.order_datetime5')
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data10 = $stmt->fetchAll();
                $data1 = array_merge($data1, $data10);
                $num=count($data1);

                if(count($data1)>($offset+$size)){
                    $num=($offset+$size);
                }
                for($i=$offset;$i<$num;$i++){
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
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $data1[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNotNull('reach_city')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data14= $stmt->fetchAll();
                    $next_cost='';
                    $last_order_status='';
                    $last_reach_city='';
                    $last_is_sign=$data14[0]['is_sign'];
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                        $last_order_status=$data12[(count($data12)-1)]['order_status'];
                    }
                    if($data13!=null){
                        $last_reach_city=$data13[0]['reach_city'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $data1[$i]['last_reach_city']=$last_reach_city;
                    $data1[$i]['last_order_status']=$last_order_status;
                    $data1[$i]['last_is_sign']=$last_is_sign;
                    $data1[$i]['next_cost']=$next_cost;
                    $data1[$i]['pre_company']=$is_transfer;
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                    array_push($datab,$data1[$i]);
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$datab));
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

$app->get('/limitGoodsOrders11',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $order_datetime1=$app->request->get('order_datetime1');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereLike('orders.order_datetime1',$order_datetime1."%")
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.exist','=',0)
                    ->whereNotIn('orders.order_status',array(-1,-2,0))
                    ->orderBy('orders.order_status')
                    ->orderBy('orders.order_datetime1','DESC')
                    ->orderBy('orders.id','DESC')
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
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $data1[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNotNull('reach_city')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data14 = $stmt->fetchAll();
                    $next_cost='';
                    $last_order_status='';
                    $last_reach_city='';
                    $last_is_sign=$data14[0]['is_sign'];
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                        $last_order_status=$data12[(count($data12)-1)]['order_status'];
                        $last_reach_city=$data12[(count($data12)-1)]['reach_city'];

                    }
                    if($data13!=null){
                        $last_reach_city=$data13[0]['reach_city'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $data1[$i]['last_reach_city']=$last_reach_city;
                    $data1[$i]['last_is_sign']=$last_is_sign;
                    $data1[$i]['last_order_status']=$last_order_status;
                    $data1[$i]['next_cost']=$next_cost;
                    $data1[$i]['pre_company']=$is_transfer;
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

$app->run();
function localhost(){
    return connect();
}
?>