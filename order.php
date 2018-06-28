<?php
require 'Slim/Slim.php';
require 'connect.php';

use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//微信添加订单
//$app->post('/wx_order_n', function () use ($app) {
//    $app->response->headers->set('Content-Type', 'application/json');
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $orderid = $app->request->get("orderid");
//    $database = localhost();
//
//});



$app->delete('/order', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $orderid = $app->request->get("orderid");
    $database = localhost();
//云端客户的id和订单id都不为空
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        if ($data4 != null) {
            if ($orderid != null || $orderid != "") {
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_id', '=', $orderid);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if ($data["order_status"] == 3 || $data["order_status"] == 2 || $data["order_status"] == 4) {
                    $updateStatement = $database->update(array('exist' => '1'))
                        ->table('orders')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('id', '=', $orderid);
                    $affectedRows = $updateStatement->execute();
                    if ($affectedRows > 0) {
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    } else {
                        echo json_encode(array("result" => "1", "desc" => "记录不存在"));
                    }
                } else {
                    echo json_encode(array("result" => "2", "desc" => "记录不可以删除"));
                }
            } else {
                echo json_encode(array("result" => "3", "desc" => "缺少订单id"));
            }
        } else {
            echo json_encode(array("result" => "4", "desc" => "租户不存在"));
        }
    } else {
        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
    }
});


$app->post('/order', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $sender_id = $body->sender_id;
    $receiver_id = $body->receiver_id;
    $order_cost = $body->order_cost;
    $pay_method = $body->pay_method;
    $order_status = $body->order_status;
    $order_datetime = $body->order_datetime;
    $selectStatement = $database->select()
        ->from('orders')
        ->where('tenant_id', '=', $tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();

    if ($data == null) {
        $order_id = 100000001;
    } else {
        $order_id = count($data) + 100000001;
    }
    if ($sender_id != null || $sender_id != "") {
        if ($receiver_id != null || $receiver_id > 0) {
            if ($order_cost != null || $order_cost != "") {
                if ($pay_method != null || $pay_method != "") {
                    if ($order_status != null || $order_status != "") {
                        if ($order_datetime != null || $order_datetime != "") {
                            if ($tenant_id != null || $tenant_id != "") {
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('exist', "=", 0)
                                    ->where('tenant_id', '=', $tenant_id);
                                $stmt = $selectStatement->execute();
                                $data4= $stmt->fetch();
                                if ($data4 != null) {
                                    $selectStatement = $database->select()
                                        ->from('customer')
                                        ->where('customer_id', '=', $sender_id)
                                        ->where('tenant_id', '=', $tenant_id)
                                        ->where('exist', "=", 0);
                                    $stmt = $selectStatement->execute();
                                    $data1 = $stmt->fetch();
                                    $selectStatement = $database->select()
                                        ->from('customer')
                                        ->where('customer_id', '=', $receiver_id)
                                        ->where('tenant_id', '=', $tenant_id)
                                        ->where('exist', "=", 0);
                                    $stmt = $selectStatement->execute();
                                    $data2 = $stmt->fetch();
                                    if ($data1 != null && $data2 != null) {
                                        $selectStatement=$database->select()
                                            ->from('inventory_location')
                                            ->where('tenant_id','=',$tenant_id);
                                            $stmt=$selectStatement->execute();
                                            $data3=$stmt->fetch();
                                            $sum=count($data3)+1;
                                        $insertStatement = $database->insert(array('order_id', 'sender_id', 'receiver_id', 'pay_method', 'order_cost', 'order_status', 'order_datetime', 'tenant_id', 'exist',
                                            'inventory_loc_id','inventory_type'))
                                            ->into('orders')
                                            ->values(array($order_id, $sender_id, $receiver_id, $pay_method, $order_cost, $order_status, $order_datetime, $tenant_id, 0,$sum,2));

                                        $insertId = $insertStatement->execute(false);
                                        echo json_encode(array("result" => "0", "desc" => "success"));
                                    } else {
                                        echo json_encode(array("result" => "1", "desc" => "发货人或者收货人不存在"));
                                    }
                                } else {
                                    echo json_encode(array("result" => "2", "desc" => "租户不存在"));
                                }
                            } else {
                                echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
                            }
                        } else {
                            echo json_encode(array("result" => "4", "desc" => "缺少运单生成时间"));
                        }
                    } else {
                        echo json_encode(array("result" => "5", "desc" => "缺少订单状态"));
                    }
                } else {
                    echo json_encode(array("result" => "6", "desc" => "缺少付款方式"));
                }
            } else {
                echo json_encode(array("result" => "7", "desc" => "缺少订单金额"));
            }
        } else {
            echo json_encode(array("result" => "8", "desc" => "缺少收货人"));
        }
    } else {
        echo json_encode(array("result" => "9", "desc" => "缺少发货人"));
    }
});


$app->put("/order", function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $array = array();
    foreach ($body as $key => $value) {
        if ($key != "order_id" && ($value != null || $value != "" || $value > 0)) {
            $array[$key] = $value;
        }
        if ($key == "sender_id") {
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id', '=', $value)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
        }
        if ($key == "receiver_id") {
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id', '=', $value)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
        }
    }
    if($tenant_id != null || $tenant_id != ""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data4= $stmt->fetch();
        if ($data4 != null) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('order_id', '=', $order_id)
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetchAll();
            if($data5!=null){
                if( $data1 != null){
                    if($data2 != null){
                        if (count($array) > 0  ) {
                            $updateStatement = $database->update($array)
                                ->table('orders')
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('order_id', '=', $order_id);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "1", "desc" => "数据不全"));
                        }
                    } else {
                        echo json_encode(array("result" => "2", "desc" => "收货人不存在"));
                    }
                }else{
                    echo json_encode(array("result" => "3", "desc" => "发货人不存在"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "该租户下订单不存在"));
            }
        }else{
            echo json_encode(array("result" => "5", "desc" => "租户不存在"));
        }
    }else{
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }

});

//控制后台
$app->get('/orders', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $page = $app->request->get('page');
    $page=$page-1;
    $tenant_id = $app->request->get("company");
    $per_page = $app->request->get("per_page");
    $order_id=$app->request->get('order_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('orders')
        ->whereLike('order_id',"%".$order_id."%")
        ->whereLike('tenant_id',"%".$tenant_id."%")
        ->whereNotNull('tenant_id');
    $stmt = $selectStatement->execute();
    $count=$stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('orders')
                ->whereLike('order_id',"%".$order_id."%")
                ->whereLike('tenant_id',"%".$tenant_id."%")
                ->whereNotNull('tenant_id')
                ->orderBy('exist')
                ->orderBy('id','DESC')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', "=", $data[$i]['tenant_id'])
                    ->where('customer_id', "=", $data[$i]['sender_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', "=", $data[$i]['tenant_id'])
                    ->where('customer_id', "=", $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', "=", $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data3['from_city_id']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5= $stmt->fetch();
                $data[$i]['tenant']=$data3;
                $data[$i]['receiver']=$data2;
                $data[$i]['receiver']['receiver_city']=$data5['name'];
                $data[$i]['sender']=$data1;
                $data[$i]['from_city']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data,'count'=>count($count)));
});


$app->get('/order', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $order_id = $app->request->get('orderid');
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data4= $stmt->fetch();
        if ($data4 != null) {
        if ($order_id != null || $order_id != "") {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('exist', "=", 0)
                ->where('order_id', '=',$order_id);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetch();
            if ($data5 != null) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $order_id)
                ->where('exist', "=", 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            echo json_encode(array("result" => "0", "desc" => "success", "order" => $data));
            } else {
                echo json_encode(array("result" => "1", "desc" => "订单不存在", "order" => ""));
            }
        } else {
            echo json_encode(array("result" => "2", "desc" => "缺少运单id", "order" => ""));
        }
        } else {
            echo json_encode(array("result" => "3", "desc" => "租户不存在", "order" => ""));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "order" => ""));
    }
});

//微信通过货主的openid，货主为发货方，获得订单总数
$app->post('/wx_orders_s', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $wx_openid=$body->wx_openid;
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('business_l', '=', $data1['business_l']);
        $stmt = $selectStatement->execute();
        $data1a= $stmt->fetchAll();
        $array_tenant=array();
        for($u=0;$u<count($data1a);$u++){
         array_push($array_tenant,$data1a[$u]['tenant_id']);
        }
        if ($data1 != null) {
            $selectStatement = $database->select()
                ->from('customer')
                ->join('orders','orders.sender_id','=','customer.customer_id','INNER')
				->join('wx_message','orders.order_id','=','wx_message.order_id','INNER')
                ->where('wx_message.is_show', "=", 0)
                ->where('customer.customer_address','!=','-1')
                ->where('customer.customer_city_id','!=','-1')
                ->where('customer.wx_openid','=',$wx_openid)
//                ->where('orders.tenant_id', '=', $tenant_id)
                ->whereIn('customer.tenant_id',$array_tenant)
//				->where('wx_message.tenant_id', '=', $tenant_id)
                ->whereIn('wx_message.tenant_id',$array_tenant)
				->orderBy('wx_message.ms_date','DESC');
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            if($data2==null){
                echo json_encode(array("result" => "0", "desc" => "用户不存在", "orders" => ""));
            }else{
                if($order_id==null){
                    $num1=count($data2);
                    $array=array();
                    for($i=0;$i<$num1;$i++){
                        $array1=array();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data2[$i]['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7= $stmt->fetch();
                        $array1['sendcity']=$data7['name'];
                        $array1['sendname']=$data2[$i]['customer_name'];

                        //隐藏微信id
                        $array1['order_idd']=$data2[$i]['message_id'];

                        if($data2[$i]['order_status']==-1||$data2[$i]['order_status']==-2||$data2[$i]['order_status']==0){
                            $array1['order_id']='暂无';
                        }else{
                            $array1['order_id']=$data2[$i]['order_id'];
                        }

                        $array1['status']=$data2[$i]['order_status'];
                        $array1['order_cost']=$data2[$i]['order_cost'];
                        if($array1['order_cost']==null||$array1['order_cost']==''){
                            $array1['order_cost']='暂无';
                        }else{
                            $array1['order_cost']=$array1['order_cost'].'元';
                        }
                        if(($array1['status']==0&&$array1['order_cost']=='暂无')||($array1['status']==5&&$array1['order_cost']=='暂无')){
//                            $array1['order_cost']='受理中';
                            $array1['order_id']='暂无';
                            $array1['receive']='未签收';
                            $array1['status']='受理中';
                            $array1['order_cost']='暂无';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==7){
                            $array1['receive']='签收时间'.$data2[$i]['order_datetime5'];
//							$array1['order_cost']='未签收';
                            $array1['status']='已签收';
                        }else if($array1['status']==-1||$array1['status']==6){
//                            $array1['order_cost']='拒受理';
                            $array1['receive']='拒受理';
                            $array1['status']='拒受理';
                        }else if($array1['status']==-2){
//                            $array1['order_cost']='未受理';
                            $array1['receive']='未受理';
                            $array1['status']='未受理';
                        }else if($array1['status']==0&&$array1['order_cost']!='暂无'){
                            $array1['receive']='未签收';
                            $array1['status']='已受理';
//							$array1['order_cost']='未签收';
                        }
                        $selectStatement = $database->select()
                            ->from('customer')
                           // ->where('exist', "=", 0)
                            ->where('customer_id','=',$data2[$i]['receiver_id'])
                            ->where('tenant_id', '=', $data2[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $array1['acceptname']=$data4['customer_name'];

                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $array1['acceptcity']=$data5['name'];
                        array_push($array,$array1);
                    }
                    echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array,"data"=>$data2));
                }else{
                    $array=array();
                    $array1=array();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->join('wx_message','wx_message.order_id','=','orders.order_id','INNER')
                        ->whereIn('wx_message.tenant_id', $array_tenant)
                        ->whereIn('orders.tenant_id',$array_tenant)
                        ->where('wx_message.is_show', "=", 0)
                        ->where('orders.order_id','=',$order_id);
//                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    if($data3!=null){
                        //隐藏微信id
                        $array1['order_idd']=$data3['message_id'];

                        if($data3['tenant_id']!=null||$data3['tenant_id']!=''){
                            $array1['order_id']='暂无';
                        }else{
                            $array1['order_id']=$data3['order_id'];
                        }
                        $array1['order_id']=$data3['order_id'];
                        $array1['status']=$data3['order_status'];
                        $array1['order_cost']=$data3['order_cost'];
                        if($array1['order_cost']==null||$array1['order_cost']==''){
                            $array1['order_cost']='暂无';
                        }else{
                            $array1['order_cost']=$array1['order_cost'].'元';
                        }
                        if(($array1['status']==0&&$array1['order_cost']=='暂无')||($array1['status']==5&&$array1['order_cost']=='暂无')){
//                            $array1['order_cost']='受理中';
                            $array1['order_id']='暂无';
                            $array1['receive']='未签收';
                            $array1['status']='受理中';
                            $array1['order_cost']='暂无';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==7){
                            $array1['receive']='签收时间'.$data3['order_datetime5'];
                            $array1['status']='已签收';
//							$array1['order_cost']='已签收';
                        }else if($array1['status']==-1||$array1['status']==6){
//                            $array1['order_cost']='拒受理';
                            $array1['receive']='拒受理';
                            $array1['status']='拒受理';
                        }else if($array1['status']==-2){
//                            $array1['order_cost']='未受理';
                            $array1['receive']='未受理';
                            $array1['status']='未受理';
                        }else if($array1['status']==0&&$array1['order_cost']!='暂无'){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='已受理';
                        }

                        $selectStatement = $database->select()
                            ->from('customer')
                            //->where('exist', "=", 0)
                            ->where('customer_id','=',$data3['receiver_id'])
                            ->where('tenant_id', '=', $data3['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $array1['acceptname']=$data4['customer_name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $array1['acceptcity']=$data5['name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            //->where('exist', "=", 0)
                            ->where('customer_id','=',$data3['sender_id'])
                            ->where('tenant_id', '=', $data3['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data6= $stmt->fetch();
                        $array1['sendname']=$data6['customer_name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7= $stmt->fetch();
                        $array1['sendcity']=$data7['name'];
                        array_push($array,$array1);
                        echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
                    }
                }
            }
        } else {
            echo json_encode(array("result" => "2", "desc" => "租户不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id", "orders" => ""));
    }
});


//微信通过货主的openid，货主为收货方，获得订单总数
$app->post('/wx_orders_r', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $wx_openid=$body->wx_openid;
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if ($data1 != null) {
            $selectStatement = $database->select()
                ->from('customer')
                //->where('exist', "=", 0)
                ->where('customer_address','=',-1)
                ->where('customer_city_id','<',0)
                ->where('wx_openid','=',$wx_openid)
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $dataa= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->join('orders','orders.receiver_id','=','customer.customer_id','INNER')
				->join('wx_message','orders.order_id','=','wx_message.order_id','INNER')
                ->where('wx_message.is_show', "=", 0)
                ->where('customer_address','!=',-1)
                ->where('customer_city_id','>',0)
                ->where('customer.customer_phone','=',$dataa['customer_phone'])
//                ->where('orders.tenant_id', '=', $tenant_id)
				->where('wx_message.tenant_id', '=', $tenant_id)
                ->where('customer.tenant_id', '=', $tenant_id)
				->orderBy('wx_message.ms_date','DESC');
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            if($data2==null){
                echo json_encode(array("result" => "0", "desc" => "没有收货", "orders" => ""));
            }else{
                if($order_id==null){
                    $num1=count($data2);
                    $array=array();
                    for($i=0;$i<$num1;$i++){
                        $array1=array();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data2[$i]['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7= $stmt->fetch();

                        //隐藏运单号
                        $array1['order_idd']=$data2[$i]['message_id'];

                        if($data2[$i]['order_status']==-1||$data2[$i]['order_status']==-2||$data2[$i]['order_status']==0||$data2[$i]['order_status']==5){
                            $array1['order_id']='暂无';
                        }else{
                            $array1['order_id']= $data2[$i]['order_id'];
                        }
                        $array1['acceptcity']=$data7['name'];
                        $array1['acceptname']= $data2[$i]['customer_name'];
//                        $array1['order_id']= $data2[$i]['order_id'];
                        $array1['status']= $data2[$i]['order_status'];
                        $array1['order_cost']= $data2[$i]['order_cost'];
                        if($array1['order_cost']==null||$array1['order_cost']==''){
                            $array1['order_cost']='暂无';
                        }else{
                            $array1['order_cost']=$array1['order_cost'].'元';
                        }
                        if(($array1['status']==0&&$array1['order_cost']=='暂无')||$array1['status']==5){
//                            $array1['order_cost']='受理中';
                            $array1['receive']='未签收';
                            $array1['status']='受理中';
                            $array1['order_cost']='暂无';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==7){
                            $array1['receive']='签收时间'. $data2[$i]['order_datetime5'];
                            $array1['status']='已签收';
//							$array1['order_cost']='已签收';
                        }else if($array1['status']==-1||$array1['status']==6){
//                            $array1['order_cost']='拒受理';
                            $array1['receive']='拒受理';
                            $array1['status']='拒受理';
                        }else if($array1['status']==-2){
//                            $array1['order_cost']='未受理';
                            $array1['receive']='未受理';
                            $array1['status']='未受理';
                        }else if($array1['status']==0&&$array1['order_cost']!='暂无'){
                            $array1['receive']='未签收';
                            $array1['status']='已受理';
//							$array1['order_cost']='未签收';
                        }
                        $selectStatement = $database->select()
                            ->from('customer')
                           // ->where('exist', "=", 0)
                            ->where('customer_id','=', $data2[$i]['sender_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $array1['sendname']=$data4['customer_name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $array1['sendcity']=$data5['name'];
                        array_push($array,$array1);
                    }
                    echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
                }else{
                    $array=array();
                    $array1=array();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->join('wx_message','wx_message.order_id','=','orders.order_id','INNER')
                        ->where('wx_message.tenant_id','=',$tenant_id)
                        ->where('orders.tenant_id','=',$tenant_id)
                        ->where('wx_message.is_show', "=", 0)
                        ->where('wx_message.order_id','=',$order_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    if($data3!=null){

                        //隐藏运单号
                        $array1['order_idd']=$data3['message_id'];

                        if($data3['tenant_id']!=null||$data3['tenant_id']!=''){
                            $array1['order_id']= $data3['order_id'];
                        }else{
                            $array1['order_id']='暂无';
                        }
//                        $array1['order_id']=$data3['order_id'];
                        $array1['status']=$data3['order_status'];
                        $array1['order_cost']=$data3['order_cost'];
                        if($array1['order_cost']==null||$array1['order_cost']==''){
                            $array1['order_cost']='暂无';
                        }else{
                            $array1['order_cost']=$array1['order_cost'].'元';
                        }
                        if(($array1['status']==0&&$array1['order_cost']=='暂无')||$array1['status']==5){
//                            $array1['order_cost']='受理中';
                            $array1['receive']='未签收';
                            $array1['status']='受理中';
                            $array1['order_cost']='暂无';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
                            $array1['status']='托运中';
//							$array1['order_cost']='未签收';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
//							$array1['order_cost']='未签收';
                            $array1['status']='托运中';
                        }else if($array1['status']==7){
                            $array1['receive']='签收时间'.$data3['order_datetime5'];
                            $array1['status']='已签收';
//							$array1['order_cost']='已签收';
                        }else if($array1['status']==-1|| $array1['status']==6){
//                            $array1['order_cost']='拒受理';
                            $array1['receive']='拒受理';
                            $array1['status']='拒受理';
                        }else if($array1['status']==-2){
//                            $array1['order_cost']='未受理';
                            $array1['receive']='未受理';
                            $array1['status']='未受理';
                        }else if($array1['status']==0&&$array1['order_cost']!='暂无'){
                            $array1['receive']='未签收';
                            $array1['status']='已受理';
//							$array1['order_cost']='未签收';
                        }
                        $selectStatement = $database->select()
                            ->from('customer')
                           // ->where('exist', "=", 0)
                            ->where('customer_id','=',$data3['receiver_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $array1['acceptname']=$data4['customer_name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $array1['acceptcity']=$data5['name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            //->where('exist', "=", 0)
                            ->where('customer_id','=',$data3['sender_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data6= $stmt->fetch();
                        $array1['sendname']=$data6['customer_name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7= $stmt->fetch();
                        $array1['sendcity']=$data7['name'];
                        array_push($array,$array1);
                        echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "", "orders" => $array));
                    }

                }
            }
        } else {
            echo json_encode(array("result" => "3", "desc" => "租户不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "orders" => ""));
    }
});


//根据订单order_id和wx_openid查出对应的订单
$app->post('/wx_order', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $wx_openid=$body->wx_openid;
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if ($data1 != null) {
                    $array=array();
                    $array1=array();
                    $array2=array();
                    $array2a=array();
                    $array2b=array();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->join('customer','orders.sender_id','=','customer.customer_id','INNER')
                        ->join('wx_message','orders.order_id','=','wx_message.order_id','INNER')
                        ->where('orders.exist', "=", 0)
                        ->where('wx_message.is_show', "=", 0)
                        ->whereLike('orders.order_id',$order_id.'%')
                        ->where('customer.customer_address','!=','-1')
                        ->where('customer.customer_city_id','>',0)
                        ->where('customer.wx_openid','=',$wx_openid)
                        ->where('customer.tenant_id', '=', $tenant_id)
                        ->where('orders.tenant_id', '=', $tenant_id)
                        ->where('wx_message.tenant_id', '=', $tenant_id)
						->orderBy('orders.order_datetime0','DESC');
                    $stmt = $selectStatement->execute();
                    $data2a= $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('customer')
                        //->where('exist', "=", 0)
                        ->where('customer_address','=',-1)
                        ->where('customer_city_id','<',0)
                        ->where('wx_openid','=',$wx_openid)
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $dataa= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->join('orders','orders.receiver_id','=','customer.customer_id','INNER')
                        ->join('wx_message','wx_message.order_id','=','orders.order_id','INNER')
                        ->where('wx_message.is_show', "=", 0)
                        ->whereLike('orders.order_id',$order_id.'%')
                        ->where('customer.customer_address','!=','-1')
                        ->where('customer.customer_city_id','>',0)
                        ->where('customer.customer_phone','=',$dataa['customer_phone'])
                        ->where('orders.tenant_id', '=', $tenant_id)
                        ->where('wx_message.tenant_id', '=', $tenant_id)
                        ->where('customer.tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data2b= $stmt->fetchAll();
                    if($data2b!=null) {
                        $num2b = count($data2b);
                        for ($i = 0; $i < $num2b; $i++) {
                            $selectStatement = $database->select()
                                ->from('city')
                                ->where('id', '=', $data2b[$i]['customer_city_id']);
                            $stmt = $selectStatement->execute();
                            $data7 = $stmt->fetch();
                            $array2['acceptcity'] = $data7['name'];
                            $array2['acceptname'] = $data2b[$i]['customer_name'];
//                            $array2['order_id'] = $data2b[$i]['order_id'];

                            //隐藏运单号
                            $array2['order_idd']=$data2b[$i]['message_id'];

                            if($data2b[$i]['tenant_id']!=null||$data2b[$i]['order_id']!=''){
                                $array2['order_id']= $data2b[$i]['order_id'];
                            }else{
                                $array2['order_id']='暂无';
                            }

                            $array2['status'] = $data2b[$i]['order_status'];
                            $array2['order_cost'] = $data2b[$i]['order_cost'];
                            if($array2['order_cost']==null||$array2['order_cost']==''){
                                $array2['order_cost']='暂无';
                            }else{
                                $array1['order_cost']=$array1['order_cost'].'元';
                            }
                            if (($array2['status'] == 0 && $array2['order_cost'] == '暂无')||($array2['status']==5&&$array2['order_cost']=='暂无')) {
//                                $array2['order_cost'] = '受理中';
                                $array2['order_id']='暂无';
                                $array2['receive'] = '未签收';
                                $array2['status'] = '受理中';
                                $array2['order_cost']='暂无';
                            } else if ($array2['status'] == 1) {
                                $array2['receive'] = '未签收';
//								$array1['order_cost']='未签收';
                                $array2['status'] = '托运中';
                            } else if ($array2['status'] == 2) {
                                $array2['receive'] = '未签收';
                                $array2['status'] = '托运中';
//								$array1['order_cost']='未签收';
                            } else if ($array2['status'] == 3) {
                                $array2['receive'] = '未签收';
                                $array2['status'] = '托运中';
//								$array1['order_cost']='未签收';
                            } else if ($array2['status'] == 4) {
                                $array2['receive'] = '未签收';
                                $array2['status'] = '托运中';
//								$array1['order_cost']='未签收';
                            } else if ($array2['status'] == 7) {
                                $array2['receive'] = '签收时间' . $data2b['order_datetime5'];
                                $array2['status'] = '已签收';
//								$array1['order_cost']='已签收';
                            } else if ($array2['status'] == -1||$array2['status']==6) {
//                                $array2['order_cost'] = '拒受理';
                                $array2['receive'] = '拒受理';
                                $array2['status'] = '拒受理';
                            } else if ($array2['status'] == -2) {
//                                $array2['order_cost'] = '未受理';
                                $array2['receive'] = '未受理';
                                $array2['status'] = '未受理';
                            } else if ($array2['status'] == 0 && $array2['order_cost'] != '暂无') {
                                $array2['receive'] = '未签收';
                                $array2['status'] = '已受理';
//								$array1['order_cost']='未签收';
                            }
                            $selectStatement = $database->select()
                                ->from('customer')
                                //->where('exist', "=", 0)
                                ->where('customer_id', '=', $data2b[$i]['sender_id'])
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data4 = $stmt->fetch();
                            $array2['sendname'] = $data4['customer_name'];
                            $selectStatement = $database->select()
                                ->from('city')
                                ->where('id', '=', $data4['customer_city_id']);
                            $stmt = $selectStatement->execute();
                            $data5 = $stmt->fetch();
                            $array2['sendcity'] = $data5['name'];
                            array_push($array2b, $array2);
                        }
                    }
                        if($data2a!=null){
                            $num2a=count($data2a);
                            for($i=0;$i<$num2a;$i++){
                                $array1=array();
                                $selectStatement = $database->select()
                                    ->from('city')
                                    ->where('id', '=', $data2a[$i]['customer_city_id']);
                                $stmt = $selectStatement->execute();
                                $data7= $stmt->fetch();
                                $array1['sendcity']=$data7['name'];
                                $array1['sendname']=$data2a[$i]['customer_name'];
                                $array1['order_id']=$data2a[$i]['order_id'];

                                //隐藏运单号
                                $array2['order_idd']=$data2a[$i]['message_id'];

                                if($data2a[$i]['tenant_id']!=null||$data2a[$i]['order_id']!=''){
                                    $array1['order_id']= $data2a[$i]['order_id'];
                                }else{
                                    $array1['order_id']='暂无';
                                }
                                $array1['status']=$data2a[$i]['order_status'];
                                $array1['order_cost']=$data2a[$i]['order_cost'];
                                if($array1['order_cost']==null||$array1['order_cost']==''){
                                    $array1['order_cost']='暂无';
                                }else{
                                    $array1['order_cost']=$array1['order_cost'].'元';
                                }
                                if(($array1['status']==0&&$array1['order_cost']=='暂无')||($array1['status']==5&&$array1['order_cost']=='暂无')) {
//                                    $array1['order_cost']='受理中';
                                    $array1['order_id']='暂无';
                                    $array1['receive']='未签收';
                                    $array1['status']='受理中';
                                    $array1['order_cost']='暂无';
                                }else if($array1['status']==1){
                                    $array1['receive']='未签收';
//									$array1['order_cost']='未签收';
                                    $array1['status']='托运中';
                                }else if($array1['status']==2){
                                    $array1['receive']='未签收';
//									$array1['order_cost']='未签收';
                                    $array1['status']='托运中';
                                }else if($array1['status']==3){
                                    $array1['receive']='未签收';
//									$array1['order_cost']='未签收';
                                    $array1['status']='托运中';
                                }else if($array1['status']==4){
                                    $array1['receive']='未签收';
//									$array1['order_cost']='未签收';
                                    $array1['status']='托运中';
                                }else if($array1['status']==7){
                                    $array1['receive']='签收时间'.$data2a[$i]['order_datetime5'];
                                    $array1['status']='已签收';
//									$array1['order_cost']='已签收';
                                }else if($array1['status']==-1||$array1['status']==6){
//                                    $array1['order_cost']='拒受理';
                                    $array1['receive']='拒受理';
                                    $array1['status']='拒受理';
                                }else if($array1['status']==-2){
//                                    $array1['order_cost']='未受理';
                                    $array1['receive']='未受理';
                                    $array1['status']='未受理';
                                }else if($array1['status']==0&&$array1['order_cost']!='暂无'){
                                    $array1['receive']='未签收';
                                    $array1['status']='已受理';
//									$array1['order_cost']='未签收';
                                }
                                $selectStatement = $database->select()
                                    ->from('customer')
                                    //->where('exist', "=", 0)
                                    ->where('customer_id','=',$data2a[$i]['receiver_id'])
                                    ->where('tenant_id', '=', $tenant_id);
                                $stmt = $selectStatement->execute();
                                $data4= $stmt->fetch();
                                $array1['acceptname']=$data4['customer_name'];

                                $selectStatement = $database->select()
                                    ->from('city')
                                    ->where('id', '=', $data4['customer_city_id']);
                                $stmt = $selectStatement->execute();
                                $data5= $stmt->fetch();
                                $array1['acceptcity']=$data5['name'];
                                array_push($array2a,$array1);
                            }
                        }
                            $array['fa']=$array2a;
                            $array['shou']=$array2b;
                        echo json_encode(array("result" => "1", "desc" => "", "orders" => $array));
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "", "orders" => ''));
                    }
        } else {
            echo json_encode(array("result" => "3", "desc" => "租户id为空", "orders" => ""));
        }

});


//根据订单order_id查出对应的订单详细信息
$app->post('/wx_order_z', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $database = localhost();
    $array=array();
    if ($tenant_id != null || $tenant_id != "") {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('exist', "=", 0)
                ->where('order_id','=',$order_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
           if($data2!=null){
               $selectStatement = $database->select()
                   ->from('orders')
                   ->where('exist', "=", 0)
                   ->where('order_id','=',$order_id)
                  ->orderBy('order_datetime0')
                  ->limit(1);
               $stmt = $selectStatement->execute();
               $data3= $stmt->fetch();
               $array['sure_img']=$data3['sure_img'];
                $array['order_time0']=$data3['order_datetime0'];
                $array['order_time1']=$data3['order_datetime1'];
                $array['order_time2']=$data3['order_datetime2'];
                $array['order_time3']=$data3['order_datetime3'];
               $array['order_time4'] = $data3['order_datetime4'];
               $selectStatement = $database->select()
                   ->from('customer')
                   ->where('customer_id','=',$data3['sender_id'])
                   ->where('tenant_id', '=', $data3['tenant_id']);
               $stmt = $selectStatement->execute();
               $data7= $stmt->fetch();
               $selectStatement = $database->select()
                   ->from('city')
                   ->where('id', '=', $data7['customer_city_id']);
               $stmt = $selectStatement->execute();
               $data8= $stmt->fetch();
               $array['sendcity']=$data8['name'];
               if($data3['is_transfer']==1&&$data3['order_status']==4){
                   $array['order_status']=$data3['order_status']-1;
               }else{
                   $array['order_status']=$data3['order_status'];
               }
               if($data3['order_status']>=3&&$data3['is_transfer']==1) {
                   $selectStatement = $database->select()
                       ->from('orders')
                       ->where('exist', "=", 0)
                       ->where('order_id', '=', $order_id)
                       ->orderBy('order_datetime0', 'DESC')
                       ->limit(1);
                   $stmt = $selectStatement->execute();
                   $data4 = $stmt->fetch();
                   if($data4['order_status']>=4){
                       $array['order_status']=$data4['order_status'];
                       $array['order_time4'] = $data4['order_datetime4'];
                       $array['order_time5'] = $data4['order_datetime5'];
                       $array['sure_img']=$data4['sure_img'];
                   }
               }
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data3['receiver_id'])
                    ->where('tenant_id', '=', $data3['tenant_id']);
                $stmt = $selectStatement->execute();
                $data9= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data9['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data10= $stmt->fetch();
                $array['receivercity']=$data10['name'];
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('exist', "=", 0)
                    ->where('order_id','=',$order_id);
                $stmt = $selectStatement->execute();
                $data5= $stmt->fetchAll();
               for($i=0;$i<count($data5);$i++){
                   $selectStatement = $database->select()
                       ->from('scheduling')
                       ->where('exist', "=", 0)
                       ->where('scheduling_id','=',$data5[$i]['schedule_id'])
                       ->where('scheduling_status', '=',4);
                   $stmt = $selectStatement->execute();
                   $data6= $stmt->fetch();
                   $selectStatement = $database->select()
                       ->from('lorry')
                       ->where('exist', "=", 0)
                       ->where('lorry_id','=',$data6['lorry_id'])
                       ->where('tenant_id', '=', $data6['tenant_id']);
                   $stmt = $selectStatement->execute();
                   $data11= $stmt->fetch();
                   $array['plate_number']=$data11['plate_number'];
               }
               if($data3['pickup_id']!=null){
                   $selectStatement = $database->select()
                       ->from('pickup')
                       ->where('pickup_id','=',$data3['pickup_id']);
                   $stmt = $selectStatement->execute();
                   $data12= $stmt->fetch();
                   $array['pickupname']=$data12['pickup_name'];
                   $array['pickupphone']=$data12['pickup_phone'];
                   $array['pickupnumber']=$data12['pickup_number'];
               }
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('tenant_id','=',$data3['tenant_id'])
                    ->where('order_id', '=', $order_id);
                $stmt = $selectStatement->execute();
                $data7= $stmt->fetch();
                 $array['special']=$data7['special_need'];
                echo json_encode(array("result" => "0", "desc" => "success", "orders" => $array));
         }else{
              echo json_encode(array("result" => "1", "desc" => "订单不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id", "orders" => ""));
    }
});




//客户端对微信的订单受理
$app->post('/wx_orders_accept', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $message_id = $body->message_id;
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('exist', "=", 0)
                ->where('tenant_id','=',$tenant_id)
                ->where('message_id','=',$message_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            if($data2!=null){
                $array=array();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('exist', "=", 0)
                    ->where('order_id','=',$data2['order_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $array['order']=$data3;
                if($data3!=null){
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $tenant_id)
//                        ->where('exist', "=", 0)
                        ->where('customer_id', '=', $data3['sender_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $array['sender']=$data4;
                    if($data4!=null){
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id','=',$data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $array['sender_city']=$data5['name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('tenant_id', '=', $tenant_id)
//                            ->where('exist', "=", 0)
                            ->where('customer_id', '=', $data3['receiver_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        if($data6!=null){
                            $selectStatement = $database->select()
                                ->from('city')
                                ->where('id','=',$data6['customer_city_id']);
                            $stmt = $selectStatement->execute();
                            $data7 = $stmt->fetch();
                            $array['receiver_city']=$data7['name'];
                            $array['receiver'] = $data6;
                            $selectStatement = $database->select()
                                ->from('goods')
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('exist', "=", 0)
                                ->where('order_id', '=', $data2['order_id']);
                            $stmt = $selectStatement->execute();
                            $data8 = $stmt->fetch();
                            if($data8!=null){
                                $array['goods']=$data8;
                                $selectStatement = $database->select()
                                    ->from('goods_package')
                                    ->where('goods_package_id', '=', $data8['goods_package_id']);
                                $stmt = $selectStatement->execute();
                                $data9 = $stmt->fetch();
                                $array['goods_package'] = $data9;
                                echo json_encode(array("result" => "1", "desc" => "success", "wx_message" => $array));
                            }else{
                                echo json_encode(array("result" => "2", "desc" => "货物不存在", "wx_message" =>""));
                            }
                        }else{
                            echo json_encode(array("result" => "3", "desc" => "收货人不存在", "wx_message" =>""));
                        }
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "发货人不存在", "wx_message" =>""));
                    }

                }else{
                    echo json_encode(array("result" => "5", "desc" => "订单不存在", "wx_message" =>""));
                }
            }else{
                echo json_encode(array("result" => "6", "desc" => "", "wx_message" =>""));
            }
        }else{
            echo json_encode(array("result" => "7", "desc" => "", "wx_message" => ""));
        }
    }else{
        echo json_encode(array("result" => "8", "desc" => "", "wx_message" => ""));
    }
});

//获得微信下单受理总数
$app->get('/wx_orders_num', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1!=null){
            $array1=array();
            $selectStatement = $database->select()
                ->from('orders')
                ->join('wx_message','wx_message.order_id','=','orders.order_id','INNER')
                ->where('orders.exist', "=", 0)
                ->where('orders.order_status','=',0)
                ->where('wx_message.tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            $num1=count($data2);
            echo json_encode(array("result" => "1", "desc" => "success", "num" => $num1));
        }else{
            echo json_encode(array("result" => "2", "desc" => "没有该租户"));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});

//分页显示微信的单子
$app->post('/wx_orders_order_source', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $offset = $body->offset;
    $size=$body->size;
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1!=null){
            $array=array();
            $array1=array();
            $selectStatement = $database->select()
                ->from('wx_message')
                ->join('orders', 'wx_message.order_id', '=', 'orders.order_id','right')
                ->where('wx_message.exist', "=", 0)
                ->where('wx_message.tenant_id', '=', $tenant_id)
                ->where('orders.order_status','=',0)
                ->orderBy("wx_message.ms_date",'DESC')
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            if($data2!=null){
                  $num1=count($data2);
                for($i=0;$i<$num1;$i++){
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('order_id', "=", $data2[$i]['order_id'])
                        ->where('order_status','=',0)
                        ->where('exist','=',0);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('goods')
                        ->where('order_id', "=", $data3['order_id'])
                        ->where('tenant_id','=',$data3['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('wx_message')
                        ->where('exist', "=", 0)
                        ->where('order_id','=',$data2[$i]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    $num=$offset+$i+1;
                        $array1['num']=$num;
                        $array1["special_need"]=$data5['special_need'];
                        $array1['message']=$data4;
                        array_push($array,$array1);
                 }
                echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
            }else{
                echo json_encode(array("result" => "2", "desc" => "不存在"));
            }
        }else{
            echo json_encode(array("result" => "3", "desc" => "没有该租户"));
        }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
    }
});

//受理的单子（更改order_status）
$app->put('/order_status', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id=$body->order_id;
    $order_status = $body->order_status;
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('exist', "=", 0)
                ->where('order_id','=',$order_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            if($data2!=null){
                if($order_status>0){
                    $updateStatement = $database->update(array('order_status' => $order_status,'tenant_id'=>$tenant_id))
                        ->table('orders')
                        ->where('exist','=',0)
                        ->where('order_id', '=', $order_id);
                    $affectedRows = $updateStatement->execute();
                }else{
                    $updateStatement = $database->update(array('order_status' => $order_status))
                        ->table('orders')
                        ->where('exist','=',0)
                        ->where('order_id', '=', $order_id);
                    $affectedRows = $updateStatement->execute();
                }
                if($affectedRows!=null){
                    echo json_encode(array("result" => "1", "desc" => "success"));
                }else{
                    echo json_encode(array("result" => "2", "desc" => "未执行"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "没有该订单"));
            }
        }else{
            echo json_encode(array("result" => "4", "desc" => "没有该租户"));
        }
    } else {
        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
    }
});



//根据order_id获得orders、goods、customer
$app->get('/orders_goods_customer', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $order_id = $app->request->get('order_id');
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($order_id!=null||$order_id!=''){
            if($data1!=null){
                $array=array();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('exist', "=", 0)
                    ->where('order_id','=',$order_id)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('exist', "=", 0)
                    ->where('order_id','=',$order_id)
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data3['goods_package_id']);
                $stmt = $selectStatement->execute();
                $data10= $stmt->fetch();
                $array['order']=$data2;
                $data3['good_package_name']=$data10['goods_package'];
                $array['goods']=$data3;
                $selectStatement = $database->select()
                    ->from('customer')
//                    ->where('exist', "=", 0)
                    ->where('customer_id','=',$data2['sender_id'])
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', "=", $data4['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6= $stmt->fetch();
                $array['sender_city']=$data6;
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', "=", $data6['pid']);
                $stmt = $selectStatement->execute();
                $data7= $stmt->fetch();
                $array['sender_province']=$data7;
                $selectStatement = $database->select()
                    ->from('customer')
//                    ->where('exist', "=", 0)
                    ->where('customer_id','=',$data2['receiver_id'])
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data5= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', "=", $data5['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data8= $stmt->fetch();
                $array['receiver_city']=$data8;
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', "=", $data8['pid']);
                $stmt = $selectStatement->execute();
                $data9= $stmt->fetch();
                $array['receiver_province']=$data9;
                $array['sender']=$data4;
                $array['receiver']=$data5;
                echo json_encode(array("result" => "0", "desc" => "success",'orders'=>$array));
            }else{
                echo json_encode(array("result" => "1", "desc" => "没有该租户"));
            }
        }else{
           echo json_encode(array('result'=>'2','desc'=>'订单id缺失'));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});
//客户端，根据order_id改金额
$app->put('/update_order_cost',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id=$body->order_id;
    $order_cost=$body->order_cost;
    if($tenant_id!=null||$tenant_id!=''){
       if($order_id!=null||$order_id!=''){
           $selectStatement = $database->select()
               ->from('tenant')
               ->where('tenant_id', "=",$tenant_id)
               ->where('exist','=',0);
           $stmt = $selectStatement->execute();
           $data1= $stmt->fetch();
           if($data1!=null){
               $selectStatement = $database->select()
                   ->from('orders')
                   ->where('order_id','=',$order_id)
                   ->where('exist','=',0);
               $stmt = $selectStatement->execute();
               $data2= $stmt->fetch();
               if($data2!=null){
                   $updateStatement = $database->update(array('order_cost' => $order_cost))
                       ->table('orders')
                       ->where('exist','=',0)
                       ->where('order_id', '=', $order_id);
                   $affectedRows = $updateStatement->execute();
                   if($affectedRows!=null){
                       echo json_encode(array("result" => "1", "desc" => "success"));
                   }else{
                       echo json_encode(array("result" => "2", "desc" => "未执行"));
                   }
               }else{
                   echo json_encode(array("result" => "3", "desc" => "没有该订单"));
               }
           }else{
               echo json_encode(array("result" => "4", "desc" => "没有该租户"));
           }
       }else{
           echo json_encode(array("result" => "5", "desc" => "缺少订单id"));
       }
    }else{
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }
});

//客户端，根据order_id更改微信的下单数据
$app->put('/update_order_id',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id1=$body->order_id_o;
    $order_id2=$body->order_id_n;
    $order_cost=$body->order_cost;
    $order_status=$body->order_status;
    date_default_timezone_set("PRC");
    $order_datetime1=date('Y-m-d H:i:s',time());
    $inventory_type=$body->inventory_type;
    $pay_method=$body->pay_method;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id1!=null||$order_id1!=''){
            if($order_id2!=null||$order_id2!=''){
                if($order_cost!=null||$order_cost!=''){
                    if($order_datetime1!=null||$order_datetime1!=''){
                        $updateStatement = $database->update(array('order_id' => $order_id2,'order_cost' => $order_cost,'order_status' => $order_status,'order_datetime1' => $order_datetime1,'inventory_type' => $inventory_type,'tenant_id'=>$tenant_id,'pay_method'=>$pay_method))
                            ->table('orders')
                            ->where('exist','=',0)
                            ->where('order_id', '=', $order_id1);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "缺少入库时间"));
                    }
                }else{
                    echo json_encode(array("result" => "2", "desc" => "缺少运费"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少新运单id"));
            }
        }else{
            echo json_encode(array("result" => "4", "desc" => "缺少旧运单id"));
        }
    }else{
        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
    }
});

//获得所有未受理的运单
$app->get('/order_unaccept',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get("tenant_id");
    $selectStatement = $database->select()
        ->from('wx_message')
        ->rightJoin('orders', 'wx_message.order_id', '=', 'orders.order_id')
        ->where('wx_message.exist', "=", 0)
        ->where('wx_message.tenant_id', '=', $tenant_id)
        ->where('orders.order_status','=',-2);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    echo json_encode(array("result"=>"0","desc"=>"success","count"=>count($data1)));
});

//批量上传，有改无增
$app->post('/order_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $order_id=$body->order_id;
    foreach($body as $key=>$value){
        $array[$key]=$value; 
    }
    $selectStatement = $database->select()
        ->from('orders')
        ->where('order_id','=',$order_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('orders')
            ->where('tenant_id','=',$tenant_id)
            ->where('order_id','=',$order_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $array['tenant_id']=$tenant_id;
        $insertStatement = $database->insert(array_keys($array))
            ->into('orders')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});


$app->get('/wsxorder1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $wx_openid=$app->request->get('wx_openid');
    $database=localhost();
    $array=array();
        if($wx_openid!=null||$wx_openid!=""){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('wx_openid','=',$wx_openid);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            if($data2!=null){
               $data2=array_values(array_unset_tt($data2,'customer_phone'));
                for($i=0;$i<count($data2);$i++){
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->whereNull('order_datetime1')
                        ->where('sender_id','=',$data2[$i]['customer_id'])
                        ->where('tenant_id','=',$data2[$i]['tenant_id'])
                        ->where('wx_openid','=',$wx_openid);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetchAll();
                    if($data3!=null){
                    for($j=0;$j<count($data3);$j++){
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['sender_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $data3[$j]['sender']=$data5;
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['receiver_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $data3[$j]['receiver']=$data6;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data3[$j]['goods']=$data4;
                    }
//                        array_push($array,$data3);
                        $array=array_merge($array,$data3);
                    }
                }
                echo json_encode(array("result"=>"0","desc"=>"success",'orders'=>$array));
            }else{
                echo json_encode(array("result"=>"2","desc"=>"",'orders'=>$array));
            }
        }else{
            echo json_encode(array("result"=>"1","desc"=>"缺少openid"));
        }
});


$app->get('/limitwsxorder1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $wx_openid=$app->request->get('wx_openid');
    $offset = $app->request->get('offset');
    $size=$app->request->get('size');
    $database=localhost();
    $array=array();
    $array1=array();
    if($wx_openid!=null||$wx_openid!=""){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('wx_openid','=',$wx_openid);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data2!=null){
            $data2=array_values(array_unset_tt($data2,'customer_phone'));
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->whereNull('order_datetime1')
                    ->where('sender_id','=',$data2[$i]['customer_id'])
                    ->where('tenant_id','=',$data2[$i]['tenant_id'])
                    ->where('wx_openid','=',$wx_openid)
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                if($data3!=null){
                    for($j=0;$j<count($data3);$j++){
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();

                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['sender_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $data7['sender']=$data5;
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['receiver_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $data7['receiver']=$data6;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data7['goods']=$data4;
                        $num=$offset+$j+1;
                        $array1['num']=$num;
                        $array1['order']=$data7;
                        array_push($array,$array1);
                    }
                }
            }
            echo json_encode(array("result"=>"0","desc"=>"success",'orders'=>$array));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"",'orders'=>$array));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少openid"));
    }
});







$app->get('/wsxorder2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $wx_openid=$app->request->get('wx_openid');
    $database=localhost();
    $array=array();
    if($wx_openid!=null||$wx_openid!=""){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('wx_openid','=',$wx_openid);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data2!=null){
            $data2=array_values(array_unset_tt($data2,'customer_phone'));
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->whereNotNull('order_datetime1')
                    ->where('sender_id','=',$data2[$i]['customer_id'])
                    ->where('tenant_id','=',$data2[$i]['tenant_id'])
                    ->where('wx_openid','=',$wx_openid);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                if($data3!=null){
                    for($j=0;$j<count($data3);$j++){
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['sender_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $data3[$j]['sender']=$data5;
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['receiver_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $data3[$j]['receiver']=$data6;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data3[$j]['goods']=$data4;
                    }
//                        array_push($array,$data3);
                    $array=array_merge($array,$data3);
                }
            }
            echo json_encode(array("result"=>"0","desc"=>"success",'orders'=>$array));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"",'orders'=>$array));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少openid"));
    }
});
$app->get('/limitwsxorder2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $wx_openid=$app->request->get('wx_openid');
    $offset = $app->request->get('offset');
    $size=$app->request->get('size');
    $database=localhost();
    $array=array();
    $array1=array();
    if($wx_openid!=null||$wx_openid!=""){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('wx_openid','=',$wx_openid);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data2!=null){
            $data2=array_values(array_unset_tt($data2,'customer_phone'));
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->whereNotNull('order_datetime1')
                    ->where('sender_id','=',$data2[$i]['customer_id'])
                    ->where('tenant_id','=',$data2[$i]['tenant_id'])
                    ->where('wx_openid','=',$wx_openid)
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                if($data3!=null){
                    for($j=0;$j<count($data3);$j++){
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();

                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['sender_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $data7['sender']=$data5;
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data3[$j]['receiver_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $data7['receiver']=$data6;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data3[$j]['order_id'])
                            ->where('tenant_id','=',$data3[$j]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data7['goods']=$data4;
                        $num=$offset+$j+1;
                        $array1['num']=$num;
                        $array1['order']=$data7;
                        array_push($array,$array1);
                    }
                }
            }
            echo json_encode(array("result"=>"0","desc"=>"success",'orders'=>$array));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"",'orders'=>$array));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少openid"));
    }
});


$app->get('/orderbyid', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->get("tenant_id");
    $order_id = $app->request->get('order_id');
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
            if ($order_id != null || $order_id != "") {
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('exist', "=", 0)
                    ->where('order_id', '=',$order_id);
                $stmt = $selectStatement->execute();
                $data5= $stmt->fetch();
                if ($data5 != null) {
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id','=',$data5['sender_id'])
                        ->where('tenant_id','=',$data5['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data['sendcity']=$data4['name'];
                    $data5['sender']=$data;

                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id','=',$data5['receiver_id'])
                        ->where('tenant_id','=',$data5['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data2['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $data2['receivecity']=$data6['name'];
                    $data5['receiver']=$data2;
                    $selectStatement = $database->select()
                        ->from('goods')
                        ->where('order_id','=',$data5['order_id'])
                        ->where('tenant_id','=',$data5['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data3['goods_package_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $data3['packagename']=$data7['goods_package'];
                    $data5['goods']=$data3;
                    echo json_encode(array("result" => "0", "desc" => "success", "order" => $data5));
                } else {
                    echo json_encode(array("result" => "1", "desc" => "订单不存在", "order" => ""));
                }
            } else {
                echo json_encode(array("result" => "2", "desc" => "缺少运单id", "order" => ""));
            }
    } else {
        echo json_encode(array("result" => "4", "desc" => "缺少租户id", "order" => ""));
    }
});




$app->run();

function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项

        if(isset($res[$value[$key]])){
            //有：销毁
            unset($value[$key]);
        }
        else{
            $res[$value[$key]] = $value;
        }
    }
    return $res;
}


function localhost()
{
    return connect();
}

?>