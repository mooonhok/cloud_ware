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
    $flag=$body->flag;
    $array=array();
    $array1=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    foreach($array as $key=>$value){
//        $array1['tenant_id']=$tenant_id;
        if($key!="flag"){
        $array1[$key]=$value;
        }
    }
    if ($sender_id != null || $sender_id != "") {
        if ($receiver_id != null || $receiver_id > 0) {
            if ($order_id != null || $order_id != "") {
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_id', '=', $order_id);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data){
                    echo json_encode(array("result" => "5", "desc" => "运单id重复"));
                }else{
                    if($tenant_id!=null||$tenant_id!=''){
                        $array1["is_schedule"]=0;
                        $array1["is_transfer"]=0;
                        date_default_timezone_set("PRC");
                        $array1['order_datetime0']=date('Y-m-d H:i:s',time());
                        if($flag==0){
                            $array1['order_datetime1']=date('Y-m-d H:i:s',time());
                        }else{
                            $array1['order_datetime1']=null;
                        }
                        $array1['exist']=0;
                        $array1['tenant_id']=$tenant_id;
                        $insertStatement = $database->insert(array_keys($array1))
                            ->into('orders')
                            ->values(array_values($array1));
                        $insertId = $insertStatement->execute(false);
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
                    }
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
                    ->where('scheduling_id', '=', $data5['schedule_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $data7='';
                if(substr($data6['sure_img'],0,4)!='http'){
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->where('tenant_id', '=', $data6['sure_img']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                }

                $data[$i]['sender']=$data1;
                $data[$i]['receiver']=$data2;
                $data[$i]['inventory_loc']=$data3;
                $data[$i]['pickup']=$data4;
                $data[$i]['scheduling']=$data6;
                $data[$i]['sure_company']=$data7;
            }

            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
        } else {
            echo json_encode(array("result" => "3", "desc" => "缺少运单id"));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
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
            $data[$i]['longitude']=$data1['longitude'];
            $data[$i]['latitude']=$data1['latitude'];
            $data[$i]['from_city']=$data2;
        }
        echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
    }
});

$app->get('/gwgetOrders1', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
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
            $selectStatement = $database->select()
                ->from('goods')
                ->where('exist', "=", 0)
                ->where('order_id','=',$data[$i]['order_id'])
                ->where('tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data3['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data10= $stmt->fetch();
           $data3['goodspackage']=$data10['goods_package'];
            $data[$i]['goods']=$data3;
            $selectStatement = $database->select()
                ->from('customer')
//                    ->where('exist', "=", 0)
                ->where('customer_id','=',$data[$i]['sender_id'])
                ->where('tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', "=", $data4['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6= $stmt->fetch();
          $data4['sender_city']=$data6['name'];
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', "=", $data6['pid']);
            $stmt = $selectStatement->execute();
            $data7= $stmt->fetch();
            $data4['sender_province']=$data7['name'];
            $data[$i]['sender']=$data4;
            $selectStatement = $database->select()
                ->from('customer')
//                    ->where('exist', "=", 0)
                ->where('customer_id','=',$data[$i]['receiver_id'])
                ->where('tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', "=", $data5['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data8= $stmt->fetch();
            $data5['receiver_city']=$data8['name'];
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', "=", $data8['pid']);
            $stmt = $selectStatement->execute();
            $data9= $stmt->fetch();
            $data5['receiver_province']=$data9['name'];
            $data[$i]['receiver']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
    }
});

$app->get('/getOrders0', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $tenant_num= $app->request->get('tenant_num');
    if($tenant_id!=null||$tenant_num!=""){
        $selectStatement = $database->select()
            ->from('orders')
            ->where('tenant_id', '=', $tenant_id)
            ->whereLike('order_id',$tenant_num.'%');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success", "count" => count($data)));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
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


$app->put('/alterOrder14',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    date_default_timezone_set("PRC");
    $order_datetime1= date('Y-m-d H:i:s',time());
    $pay_method = $body->pay_method;
    $transfer_cost=$body->transfer_cost;
    $collect_cost=null;
    foreach($body as $key=>$value){
        if($key=="collect_cost"){
            $collect_cost=$body->collect_cost;
        }
    }
    if($order_id!=null||$order_id!=''){
        if($order_datetime1!=null||$order_datetime1!=''){
                if($transfer_cost!=null||$transfer_cost!=''){
                    $updateStatement = $database->update(array('order_status'=>1,'order_datetime1'=>$order_datetime1,'inventory_type'=>0,'pay_method'=>$pay_method,'transfer_cost'=>$transfer_cost,'collect_cost'=>$collect_cost))
                        ->table('orders')
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$order_id);
                    $affectedRows = $updateStatement->execute();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('order_id',"=",$order_id);
                    $stmt = $selectStatement->execute();
                    $data1= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id',"=",$order_id)
                        ->where('id','<',$data1['id'])
                        ->orderBy('id','DESC')
                        ->limit(1);
                    $stmt = $selectStatement->execute();
                    $data2= $stmt->fetch();
                    if($data2['collect_cost']==null||$data2['collect_cost']==0){
                        echo json_encode(array("result" => "0", "desc" => "success","order_cost"=>$data2['order_cost']));
                    }else{
                        echo json_encode(array("result" => "0", "desc" => "success","order_cost"=>$data2['collect_cost']));
                    }
                }else{
                    echo json_encode(array("result" => "1", "desc" => "缺少转运费"));
                }
        }else{
            echo json_encode(array("result" => "3", "desc" => "缺少运单时间"));
        }
    }else{
        echo json_encode(array("result" => "4", "desc" => "缺少运单id"));
    }
});


$app->put('/alterOrder18',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            date_default_timezone_set("PRC");
            $shijian=date("Y-m-d H:i:s",time());
                $updateStatement = $database->update(array('order_status'=>7,'order_datetime5'=>$shijian))
                    ->table('orders')
                    ->where('exist','=',0)
                    ->where('order_id','=',$order_id);
                $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

//根据运单id和发货人名模糊查询
$app->get('/getOrders_orderid_or_sender', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $order_id_and_sender=$app->request->get('id_name');
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('orders')
            ->join('customer','customer.customer_id','=','orders.sender_id','INNER')
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('customer.tenant_id','=',$tenant_id)
            ->whereLike('orders.order_id','%'.$order_id_and_sender.'%')
            ->orWhereLike('customer.customer_name','%'.$order_id_and_sender.'%')
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('customer.tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->run();

function localhost()
{
    return connect();
}

?>