<?php
require 'Slim/Slim.php';
require 'connect.php';

use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->delete('/order', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
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
    $app->response->headers->set('Content-Type', 'application/json');
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
    $app->response->headers->set('Content-Type', 'application/json');
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


$app->get('/orders', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $page = $app->request->get('page');
    $per_page = $app->request->get("per_page");
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist', "=", 0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data4= $stmt->fetch();
        if ($data4 != null) {
        if ($page == null || $per_page == null) {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
        } else {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0)
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data));
        }
        } else {
            echo json_encode(array("result" => "1", "desc" => "租户不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "2", "desc" => "缺少租户id", "orders" => ""));
    }

});


$app->get('/order', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
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
    $app->response->headers->set('Content-Type', 'application/json');
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
                ->where('exist', "=", 0)
                ->where('wx_openid','=',$wx_openid)
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            if($data2==null){
                echo json_encode(array("result" => "0", "desc" => "用户不存在", "orders" => ""));
            }else{
                if($order_id==null){
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('exist', "=", 0)
                        ->where('sender_id','=',$data2['customer_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('exist', "=", 0)
                        ->where('customer_id','=',$data2['customer_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data6= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data6['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7= $stmt->fetch();
                    $num1=count($data3);
                    $array=array();
                    for($i=0;$i<$num1;$i++){
                        $array1=array();
                        $array1['sendcity']=$data7['name'];
                        $array1['sendname']=$data6['customer_name'];
                        $array1['order_id']=$data3[$i]['order_id'];
                        $array1['status']=$data3[$i]['order_status'];
                        $selectStatement = $database->select()
                            ->from('order_time')
                            ->where('exist', "=", 0)
                            ->where('order_id','=',$data3[$i]['order_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data8= $stmt->fetch();
                        if($array1['status']==0){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==5){
                            $array1['receive']='签收时间'.$data8['order_timef'];
                            $array1['status']='已签收';
                        }
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('exist', "=", 0)
                            ->where('customer_id','=',$data3[$i]['receiver_id'])
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
                        array_push($array,$array1);
                    }
                    echo json_encode(array("result" => "1", "desc" => "success", "orders" => $array));
                }else{
                    $array=array();
                    $array1=array();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('exist', "=", 0)
                        ->where('order_id','=',$order_id)
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    $array1['status']=$data3['order_status'];
                    $selectStatement = $database->select()
                        ->from('order_time')
                        ->where('exist', "=", 0)
                        ->where('order_id','=',$data3['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data8= $stmt->fetch();
                    if($array1['status']==0){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==1){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==2){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==3){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==4){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==5){
                        $array1['receive']='签收时间'.$data8['order_timef'];
                        $array1['status']='已签收';
                    }

                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('exist', "=", 0)
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
                        ->where('exist', "=", 0)
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
    $app->response->headers->set('Content-Type', 'application/json');
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
                ->where('exist', "=", 0)
                ->where('wx_openid','=',$wx_openid)
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            if($data2==null){
                echo json_encode(array("result" => "0", "desc" => "用户不存在", "orders" => ""));
            }else{
                if($order_id==null){
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('exist', "=", 0)
                        ->where('receiver_id','=',$data2['customer_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('exist', "=", 0)
                        ->where('customer_id','=',$data2['customer_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data6= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data6['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7= $stmt->fetch();
                    $num1=count($data3);
                    $array=array();
                    for($i=0;$i<$num1;$i++){
                        $array1=array();
                        $array1['acceptcity']=$data7['name'];
                        $array1['acceptname']=$data6['customer_name'];
                        $array1['order_id']=$data3[$i]['order_id'];
                        $array1['status']=$data3[$i]['order_status'];
                        $selectStatement = $database->select()
                            ->from('order_time')
                            ->where('exist', "=", 0)
                            ->where('order_id','=',$data3[$i]['order_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data8= $stmt->fetch();
                        if($array1['status']==0){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==1){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==2){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==3){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==4){
                            $array1['receive']='未签收';
                            $array1['status']='未签收';
                        }else if($array1['status']==5){
                            $array1['receive']='签收时间'.$data8['order_timef'];
                            $array1['status']='已签收';
                        }
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('exist', "=", 0)
                            ->where('customer_id','=',$data3[$i]['sender_id'])
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
                        ->where('exist', "=", 0)
                        ->where('order_id','=',$order_id)
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    $array1['status']=$data3['order_status'];
                    $selectStatement = $database->select()
                        ->from('order_time')
                        ->where('exist', "=", 0)
                        ->where('order_id','=',$data3['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data8= $stmt->fetch();
                    if($array1['status']==0){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==1){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==2){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==3){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==4){
                        $array1['receive']='未签收';
                        $array1['status']='未签收';
                    }else if($array1['status']==5){
                        $array1['receive']='签收时间'.$data8['order_timef'];
                        $array1['status']='已签收';
                    }
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('exist', "=", 0)
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
                        ->where('exist', "=", 0)
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
                }
            }
        } else {
            echo json_encode(array("result" => "2", "desc" => "租户不存在", "orders" => ""));
        }
    } else {
        echo json_encode(array("result" => "3", "desc" => "缺少租户id", "orders" => ""));
    }
});


$app->run();

function localhost()
{
    return connect();
}

?>