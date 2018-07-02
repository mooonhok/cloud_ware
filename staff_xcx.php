<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 13:58
 */


require 'Slim/Slim.php';
require 'connect.php';
require "littleWeiXinPay/littleWeiXinPay.php";

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$weiXinPay=new littleWeixinPay();

$app->post('/staff_login',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $password=$body->password;
    if($tenant_id!=null||$tenant_id!=''){
        if($name!=null||$name!=''){
            if($password!=null||$password!=''){
                $selectStatement = $database->select()
                    ->from('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('username','=',$name)
                    ->where('password','=',encode($password,'cxphp'));
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data){
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'暂无该用户'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'缺少昵称'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少员工名字'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户'));
    }
});

$app->post('/makeOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $customer_name_a=$body->customer_name_a;
    $customer_phone_a=$body->customer_phone_a;
    $customer_address_a=$body->customer_address_a;
    $customer_city_a=$body->customer_city_a;
    $customer_name_b=$body->customer_name_b;
    $customer_phone_b=$body->customer_phone_b;
    $customer_address_b=$body->customer_address_b;
    $customer_city_b=$body->customer_city_b;
    $goods_name=$body->goods_name;
    $goods_count=$body->goods_count;
    $goods_capacity=$body->goods_capacity;
    $goods_weight=$body->goods_weight;
    $goods_package_id=$body->goods_package_id;
    $goods_value=$body->goods_value;
    $pay_method=$body->pay_method;
    $order_cost=$body->order_cost;
    $special_need=$body->special_need;
    $inventory_type=$body->inventory_type;
    $num1=0;
    $num2=0;
    $data1=array();
    $data3=array();
    if($special_need==0){
        $special_need='寄:门店自寄;  收:送货上门';
    }else{
        $special_need='寄:上门提货;  收:门店自提';
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name_a!=null||$customer_name_a!=''){
            if($customer_phone_a!=null||$customer_phone_a!=''){
                if($customer_address_a!=null||$customer_address_a!=''){
                    if($customer_city_a!=null||$customer_city_a!=''){
                        if($customer_name_b!=null||$customer_name_b!=''){
                                if($customer_phone_b!=null||$customer_phone_b!=''){
                                    if($customer_address_b!=null||$customer_address_b!=''){
                                        if($customer_city_b!=null||$customer_city_b!=''){
                                            if($goods_name!=null||$goods_name!=''){
                                                if($goods_weight!=null||$goods_weight!=''){
                                                    if($goods_package_id!=null||$goods_package_id!=''){
                                                        if($goods_value!=null||$goods_value!=''){
                                                            if($order_cost!=null||$order_cost!=''){
                                                                    $selectStatement = $database->select()
                                                                        ->from('customer')
                                                                        ->where('tenant_id','=',$tenant_id)
                                                                        ->where('customer_city_id','=',$customer_city_a)
                                                                        ->where('customer_address','=',$customer_address_a)
                                                                        ->where('customer_name','=',$customer_name_a)
                                                                        ->where('customer_phone','=',$customer_phone_a);
                                                                    $stmt = $selectStatement->execute();
                                                                    $data = $stmt->fetch();
                                                                    $customer_id1=$data['customer_id'];
                                                                    if(!$data){
                                                                        $selectStatement = $database->select()
                                                                            ->from('customer')
                                                                            ->where('tenant_id','=',$tenant_id);
                                                                        $stmt = $selectStatement->execute();
                                                                        $data1 = $stmt->fetchAll();
                                                                        for($i=0;$i<count($data);$i++){
                                                                            if(preg_match('/[a-zA-Z]/',$data1[$i]['customer_id'])){
                                                                                $num1++;
                                                                            }
                                                                        }
                                                                        $customer_id1=(count($data1)-$num1)+10000000001;
                                                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','type','exist'))
                                                                            ->into('customer')
                                                                            ->values(array($customer_id1,$tenant_id,$customer_city_a,$customer_address_a,$customer_name_a,$customer_phone_a,0,0));
                                                                        $insertId = $insertStatement->execute(false);
                                                                    }
                                                                    $selectStatement = $database->select()
                                                                        ->from('customer')
                                                                        ->where('tenant_id','=',$tenant_id)
                                                                        ->where('customer_city_id','=',$customer_city_b)
                                                                        ->where('customer_address','=',$customer_address_b)
                                                                        ->where('customer_name','=',$customer_name_b)
                                                                        ->where('customer_phone','=',$customer_phone_b);
                                                                    $stmt = $selectStatement->execute();
                                                                    $data2 = $stmt->fetch();
                                                                    $customer_id2=$data2['customer_id'];
                                                                    if(!$data2){
                                                                        $selectStatement = $database->select()
                                                                            ->from('customer')
                                                                            ->where('tenant_id','=',$tenant_id);
                                                                        $stmt = $selectStatement->execute();
                                                                        $data3 = $stmt->fetchAll();
                                                                        for($i=0;$i<count($data3);$i++){
                                                                            if(preg_match('/[a-zA-Z]/',$data3[$i]['customer_id'])){
                                                                                $num2++;
                                                                            }
                                                                        }
                                                                        $customer_id2=(count($data3)-$num2)+10000000001;
                                                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','type','exist'))
                                                                            ->into('customer')
                                                                            ->values(array($customer_id2,$tenant_id,$customer_city_b,$customer_address_b,$customer_name_b,$customer_phone_b,0,0));
                                                                        $insertId = $insertStatement->execute(false);
                                                                    }

                                                                    $selectStatement = $database->select()
                                                                        ->from('tenant')
                                                                        ->where('tenant_id','=',$tenant_id);
                                                                    $stmt = $selectStatement->execute();
                                                                    $data5= $stmt->fetch();
                                                                $selectStatement = $database->select()
                                                                    ->from('orders')
                                                                    ->whereLike('order_id',$data5['tenant_num'].'%')
                                                                    ->where('tenant_id','=',$tenant_id);
                                                                $stmt = $selectStatement->execute();
                                                                $data4 = $stmt->fetchAll();
                                                                    $order_id=0;
                                                                    if($data4){
                                                                        if(strlen((count($data4)+1).'')==1){
                                                                            $order_id=$data5['tenant_num'].'00000'.(count($data4)+1);
                                                                        }else if(strlen((count($data4)+1).'')==2){
                                                                            $order_id=$data5['tenant_num'].'0000'.(count($data4)+1);
                                                                        }else if(strlen((count($data4)+1).'')==3){
                                                                            $order_id=$data5['tenant_num'].'000'.(count($data4)+1);
                                                                        }else if(strlen((count($data4)+1).'')==4){
                                                                            $order_id=$data5['tenant_num'].'00'.(count($data4)+1);
                                                                        }else if(strlen((count($data4)+1).'')==5){
                                                                            $order_id=$data5['tenant_num'].'0'.(count($data4)+1);
                                                                        }else if(strlen((count($data4)+1).'')==6){
                                                                            $order_id=$data5['tenant_num'].''.(count($data4)+1);
                                                                        }
                                                                    }else{
                                                                        $order_id=$data5['tenant_num'].'00000'.'1';
                                                                    }
                                                                    date_default_timezone_set("PRC");
                                                                    $shijian=date("Y-m-d H:i:s",time());
                                                                    $insertStatement = $database->insert(array('order_id','tenant_id','sender_id','receiver_id','pay_method','pay_status','order_cost','order_status','exist','order_datetime0','is_sign','order_datetime1','inventory_type'))
                                                                        ->into('orders')
                                                                        ->values(array($order_id,$tenant_id,$customer_id1,$customer_id2,$pay_method,1,$order_cost,1,0,$shijian,'0',$shijian,$inventory_type));
                                                                    $insertId = $insertStatement->execute(false);
                                                                    $selectStatement = $database->select()
                                                                        ->from('goods')
                                                                        ->whereLike('goods_id',$data5['tenant_num'].'%')
                                                                        ->where('tenant_id','=',$tenant_id);
                                                                    $stmt = $selectStatement->execute();
                                                                    $data6= $stmt->fetchAll();
                                                                    $goods_id=0;
                                                                    if($data6){
                                                                        if(strlen((count($data6)+1).'')==1){
                                                                            $goods_id=$data5['tenant_num'].'00000'.(count($data6)+1);
                                                                        }else if(strlen((count($data6)+1).'')==2){
                                                                            $goods_id=$data5['tenant_num'].'0000'.(count($data6)+1);
                                                                        }else if(strlen((count($data6)+1).'')==3){
                                                                            $goods_id=$data5['tenant_num'].'000'.(count($data6)+1);
                                                                        }else if(strlen((count($data6)+1).'')==4){
                                                                            $goods_id=$data5['tenant_num'].'00'.(count($data6)+1);
                                                                        }else if(strlen((count($data6)+1).'')==5){
                                                                            $goods_id=$data5['tenant_num'].'0'.(count($data6)+1);
                                                                        }else if(strlen((count($data6)+1).'')==6){
                                                                            $goods_id=$data5['tenant_num'].''.(count($data6)+1);
                                                                        }
                                                                    }else{
                                                                        $goods_id=$data5['tenant_num'].'00000'.'1';
                                                                    }
                                                                    $insertStatement = $database->insert(array('goods_id','order_id','tenant_id','goods_name','goods_count','goods_capacity','goods_weight','goods_package_id','goods_value','exist','special_need'))
                                                                        ->into('goods')
                                                                        ->values(array($goods_id,$order_id,$tenant_id,$goods_name,$goods_count,$goods_capacity,$goods_weight,$goods_package_id,$goods_value,0,$special_need));
                                                                    $insertId = $insertStatement->execute(false);
                                                                    echo json_encode(array('result'=>'0','desc'=>'success','num1'=>$num1,'num2'=>$num2));
                                                            }else{
                                                                echo json_encode(array('result'=>'2','desc'=>'缺少运费'));
                                                            }
                                                        }else{
                                                            echo json_encode(array('result'=>'3','desc'=>'缺少货物价值'));
                                                        }
                                                    }else{
                                                        echo json_encode(array('result'=>'4','desc'=>'缺少包装'));
                                                    }
                                                }else{
                                                    echo json_encode(array('result'=>'5','desc'=>'缺少货物重量'));
                                                }
                                            }else{
                                                echo json_encode(array('result'=>'6','desc'=>'缺少货物名称'));
                                            }
                                        }else{
                                            echo json_encode(array('result'=>'7','desc'=>'缺少收货人城市'));
                                        }
                                    }else{
                                        echo json_encode(array('result'=>'8','desc'=>'缺少收货人地址'));
                                    }
                                }else{
                                    echo json_encode(array('result'=>'9','desc'=>'缺少收货人电话'));
                                }
                        }else{
                            echo json_encode(array('result'=>'10','desc'=>'缺少收货人名字'));
                        }
                    }else{
                        echo json_encode(array('result'=>'11','desc'=>'缺少发货人城市'));
                    }
                }else{
                    echo json_encode(array('result'=>'12','desc'=>'缺少发货人地址'));
                }
            }else{
                echo json_encode(array('result'=>'13','desc'=>'缺少发货人电话'));
            }
        }else{
            echo json_encode(array('result'=>'14','desc'=>'缺少发货人名字'));
        }
    }else{
        echo json_encode(array('result'=>'15','desc'=>'缺少租户'));
    }
});

$app->get('/getOrders_inventory_type', function () use ($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $inventory_type=$app->request->get('inventory_type');
    $database = localhost();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->where('goods.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->whereIn('orders.order_status',array(1,0))
            ->where('orders.exist','=',0)
            ->where('orders.inventory_type','=',$inventory_type);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data[$i]['sender_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data3['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data[$i]['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetch();
            $data[$i]['fa_city']=$data2['name'];
            $data[$i]['shou_city']=$data4['name'];
            $data[$i]['goods_package']=$data5['goods_package'];
        }
        echo json_encode(array("result" => "0", "desc" => "success", "orders" => $data,"inven"=>$inventory_type));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterOrderInventoryType',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $inventory_type=$body->inventory_type;
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
                $updateStatement = $database->update(array('inventory_type'=>$inventory_type))
                    ->table('orders')
                    ->where('order_id','=',$order_id)
                    ->where('tenant_id','=',$tenant_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少运单号'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'缺少租户id'));
    }
});

$app->get('/getTenantLorrys',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('flag','=',0)
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('app_lorry')
                ->where('exist','=',0)
                ->where('lorry_status','=',2)
                ->where('phone','=',$data[$i]['driver_phone'])
                ->where('plate_number','=',$data[$i]['plate_number'])
                ->where('name','=',$data[$i]['driver_name']);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('lorry_type_id','=',$data1['type']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $data[$i]['lorry_type']=$data2['lorry_type_name'];
        }
        echo json_encode(array("result"=>"0","desc"=>"success","lorrys"=>$data));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->get('/getOneLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry_id','=',$lorry_id)
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('lorry_status','=',2)
                    ->where('phone','=',$data['driver_phone'])
                    ->where('plate_number','=',$data['plate_number'])
                    ->where('name','=',$data['driver_name']);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data1['type']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_length')
                ->where('lorry_length_id','=',$data1['length']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
                $data['lorry_type']=$data2['lorry_type_name'];
            $data['lorry_length']=$data3['lorry_length'];
            $data['lorry_detail']=$data1;
            echo json_encode(array("result"=>"0","desc"=>"success","lorry"=>$data));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少车辆id'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/chooseLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $app_lorry_id = $body->app_lorry_id;
    $status=$body->status;
    $database=localhost();
    if($status==2){
        $updateStatement = $database->update(array("lorry_status"=>2))
            ->table('app_lorry')
            ->where('flag','=',0)
            ->where('app_lorry_id','=',$app_lorry_id);
        $affectedRows = $updateStatement->execute();
        if($tenant_id!=''||$tenant_id!=null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('app_lorry')
                ->where('flag','=',0)
                ->where('lorry_status','=',2)
                ->where('app_lorry_id','=',$app_lorry_id);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            if($data1){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0)
                    ->where('driver_phone','=',$data1['phone'])
                    ->where('plate_number','=',$data1['plate_number'])
                    ->where('driver_name','=',$data1['name']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                if($data2){
                    echo json_encode(array('result'=>'3','desc'=>'该车辆已添加'));
                }else{
                    $insertStatement = $database->insert(array('tenant_id','lorry_id','plate_number','driver_name','driver_phone','flag','exist'))
                        ->into('lorry')
                        ->values(array($tenant_id,(count($data)+100000001),$data1['plate_number'],$data1['name'],$data1['phone'],0,0));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'车辆未注册'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
        }
    }else{
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('flag','=',0)
            ->where('lorry_status','=',2)
            ->where('app_lorry_id','=',$app_lorry_id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1){
            echo json_encode(array('result'=>'4','desc'=>'无法驳回'));
        }else{
            $reason=$body->reason;
            date_default_timezone_set("PRC");
            $shijian=date("Y-m-d H:i:s",time());
            $insertStatement = $database->insert(array('tenant_id','app_lorry_id','reason','time'))
                ->into('app_lorry_reject')
                ->values(array($tenant_id,$app_lorry_id,$reason,$shijian));
            $insertId = $insertStatement->execute(false);
            $updateStatement = $database->update(array("lorry_status"=>1))
                ->table('app_lorry')
                ->where('flag','=',0)
                ->where('app_lorry_id','=',$app_lorry_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result'=>'3','desc'=>'已驳回'));
        }
    }
});

$app->post('/getAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $driver_name = $body->driver_name;
    $driver_phone = $body->driver_phone;
    $plate_number = $body->plate_number;
    $database=localhost();
        if($tenant_id!=''||$tenant_id!=null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('flag','=',0)
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->where('driver_phone','=',$driver_phone)
                ->where('plate_number','=',$plate_number)
                ->where('driver_name','=',$driver_name);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            if($data1){
                echo json_encode(array('result'=>'2','desc'=>'车辆已添加'));
            }else{
                $selectStatement = $database->select()
                    ->from('app_lorry')
                    ->whereIn('lorry_status',array(0,2))
                    ->where('exist','=',0)
                    ->where('flag','=',0)
                    ->where('phone','=',$driver_phone)
                    ->where('plate_number','=',$plate_number)
                    ->where('name','=',$driver_name);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                if($data2){
                    $selectStatement = $database->select()
                        ->from('lorry_type')
                        ->where('lorry_type_id','=',$data2['type']);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('lorry_length')
                        ->where('lorry_length_id','=',$data2['length']);
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetch();
                    $data2['lorry_type']=$data4['lorry_type_name'];
                    $data2['lorry_length']=$data5['lorry_length'];
                    echo json_encode(array('result'=>'0','desc'=>'success','app_lorry'=>$data2));
                }else{
                    $selectStatement = $database->select()
                        ->from('app_lorry')
                        ->where('lorry_status','=',1)
                        ->where('exist','=',0)
                        ->where('flag','=',0)
                        ->where('phone','=',$driver_phone)
                        ->where('plate_number','=',$plate_number)
                        ->where('name','=',$driver_name);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                    if($data3){
                        echo json_encode(array('result'=>'1','desc'=>'该车辆审核未通过'));
                    }else{
                        echo json_encode(array('result'=>'3','desc'=>'该车辆未注册'));
                    }
                }
            }
        }else{
            echo json_encode(array('result'=>'4','desc'=>'缺少租户id'));
        }
});

$app->put('/pullInBlack',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id = $body->lorry_id;
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        if($lorry_id!=''||$lorry_id!=null){
            $updateStatement = $database->update(array("exist"=>1))
                ->table('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('lorry_id','=',$lorry_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result'=>'0','desc'=>'success'));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少车辆信息'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'缺少租户id'));
    }
});

$app->get('/tenantLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('flag','=',0)
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','lorry'=>$data));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/addScheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $send_city_id=$body->send_city_id;
    $receive_city_id=$body->receive_city_id;
    $driver_name = $body->driver_name;
    $driver_phone = $body->driver_phone;
    $plate_number = $body->plate_number;
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $customer_address=$body->customer_address;
    $contact_tenant_id=$body->contact_tenant_id;
    $order_ids=$body->order_ids;
    $array1 = array();
    foreach ($order_ids as $key => $value) {
        $array1[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($send_city_id!=null||$send_city_id!=''){
            if($receive_city_id!=null||$receive_city_id!=''){
                    if($customer_name!=null||$customer_name!=''){
                        if($customer_phone!=null||$customer_phone!=''){
                            if($customer_address!=null||$customer_address!=''){
                              if($contact_tenant_id){
                                  $selectStatement = $database->select()
                                      ->from('tenant')
                                      ->where('exist','=',0)
                                      ->where('tenant_id','=',$contact_tenant_id);
                                  $stmt = $selectStatement->execute();
                                  $data= $stmt->fetch();
                                  if(!$data){
                                      echo json_encode(array('result'=>'8','desc'=>'商户编号不存在'));
                                      exit;
                                  }
                              }
                                $selectStatement = $database->select()
                                    ->from('lorry')
                                    ->where('exist','=',0)
                                    ->where('driver_phone','=',$driver_phone)
                                    ->where('plate_number','=',$plate_number)
                                    ->where('driver_name','=',$driver_name)
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $data1= $stmt->fetch();
                                if(!$data1){
                                    echo json_encode(array('result'=>'9','desc'=>'该车辆不存在'));
                                    exit;
                                }
                                $customer_id='';

                                $selectStatement = $database->select()
                                    ->from('customer')
                                    ->where('exist','=',0)
                                    ->where('customer_city_id','=',$receive_city_id)
                                    ->where('customer_address','=',$customer_address)
                                    ->where('customer_name','=',$customer_name)
                                    ->where('customer_phone','=',$customer_phone)
                                    ->where('contact_tenant_id','=',$contact_tenant_id)
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $data= $stmt->fetch();
                                if($data){
                                    $updateStatement = $database->update(array("times"=>($data['times']+1)))
                                        ->table('customer')
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('customer_id','=',$data['customer_id']);
                                    $affectedRows = $updateStatement->execute();
                                    $customer_id=$data['customer_id'];
                                }else{
                                    $selectStatement = $database->select()
                                        ->from('customer')
                                        ->where('tenant_id','=',$tenant_id);
                                    $stmt = $selectStatement->execute();
                                    $data= $stmt->fetchAll();
                                    $num1=0;
                                    for($i=0;$i<count($data);$i++){
                                        if(preg_match('/[a-zA-Z]/',$data[$i]['customer_id'])){
                                            $num1++;
                                        }
                                    }
                                    if($contact_tenant_id){
                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','contact_tenant_id','exist','type'))
                                            ->into('customer')
                                            ->values(array(((count($data)-$num1)+10000000001),$tenant_id,$receive_city_id,$customer_address,$customer_name,$customer_phone,$contact_tenant_id,'0',3));
                                        $insertId = $insertStatement->execute(false);
                                    }else{
                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','exist','type'))
                                            ->into('customer')
                                            ->values(array(((count($data)-$num1)+10000000001),$tenant_id,$receive_city_id,$customer_address,$customer_name,$customer_phone,'0',3));
                                        $insertId = $insertStatement->execute(false);
                                    }
                                    $customer_id=(count($data)-$num1)+10000000001;
                                }
                                $selectStatement = $database->select()
                                    ->from('scheduling')
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $data2= $stmt->fetchAll();
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $data3= $stmt->fetch();
                                $scheduling_id='';
                                    if(strlen((count($data2)+1).'')==1){
                                        $scheduling_id='QD'.$data3['tenant_num'].'00000'.(count($data2)+1);
                                    }else if(strlen((count($data2)+1).'')==2){
                                        $scheduling_id='QD'.$data3['tenant_num'].'0000'.(count($data2)+1);
                                    }else if(strlen((count($data2)+1).'')==3){
                                        $scheduling_id='QD'.$data3['tenant_num'].'000'.(count($data2)+1);
                                    }else if(strlen((count($data2)+1).'')==4){
                                        $scheduling_id='QD'.$data3['tenant_num'].'00'.(count($data2)+1);
                                    }else if(strlen((count($data2)+1).'')==5){
                                        $scheduling_id='QD'.$data3['tenant_num'].'0'.(count($data2)+1);
                                    }else if(strlen((count($data2)+1).'')==6){
                                        $scheduling_id='QD'.$data3['tenant_num'].''.(count($data2)+1);
                                    }else{
                                        echo json_encode(array('result'=>'10','desc'=>'调度满仓','aaa'=>$scheduling_id));
                                        exit;
                                    }
                                date_default_timezone_set("PRC");
                                $shijian=date("Y-m-d H:i:s",time());
                                $insertStatement = $database->insert(array('scheduling_id','tenant_id','scheduling_datetime','send_city_id','receive_city_id','lorry_id','receiver_id','scheduling_status','exist','is_show','is_alter','is_load','is_contract','is_insurance','is_scan'))
                                    ->into('scheduling')
                                    ->values(array($scheduling_id,$tenant_id,$shijian,$send_city_id,$receive_city_id,$data1['lorry_id'],$customer_id,'1',0,0,0,1,1,1,0));
                                $insertId = $insertStatement->execute(false);
                                for($i=0;$i<count($array1);$i++){
//                                    $updateStatement = $database->update(array("is_schedule"=>1))
//                                        ->table('orders')
//                                        ->where('tenant_id','=',$tenant_id)
//                                        ->where('order_id','=',$array1[$i]);
//                                    $affectedRows = $updateStatement->execute();
                                    $insertStatement = $database->insert(array('schedule_id','tenant_id','order_id','exist'))
                                        ->into('schedule_order')
                                        ->values(array($scheduling_id,$tenant_id,$array1[$i],0));
                                    $insertId = $insertStatement->execute(false);
                                }
                                echo json_encode(array('result'=>'0','desc'=>'success','scheduling_id'=>$scheduling_id));
                            }else{
                                echo json_encode(array('result'=>'7','desc'=>'缺少客户地址'));
                            }
                        }else{
                            echo json_encode(array('result'=>'6','desc'=>'缺少客户手机'));
                        }
                    }else{
                        echo json_encode(array('result'=>'5','desc'=>'缺少客户名字'));
                    }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'缺少收货城市'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少发货城市'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->get('/orderGoodsCity',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $inventory_type=$app->request->get('inventory_type');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0)
            ->where('orders.order_status','=',1)
            ->whereNull('orders.exception_id')
            ->where('orders.inventory_type','=',$inventory_type)
            ->where('orders.is_back','=',0)
            ->where('orders.is_schedule','=',0);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();

        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id','=',$data[$i]['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $data[$i]['receive_name']=$data1['customer_name'];
            $data[$i]['receive_city_name']=$data2['name'];
            $data[$i]['goods_package']=$data3['goods_package'];
        }
        echo json_encode(array('result'=>'0','desc'=>'success','orders'=>$data));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/orderCustomer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $receive_id=$body->receive_id;
    $database=localhost();
    $array=array();
    if($tenant_id!=null||$tenant_id!=''){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$receive_id);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetch();
        echo json_encode(array('result'=>'0','desc'=>'success','orderCustomer'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/changeOrderIsSchedule',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $is_schedule=$body->is_schedule;
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
                                    $updateStatement = $database->update(array("is_schedule"=>$is_schedule))
                                        ->table('orders')
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('order_id','=',$order_id);
                                    $affectedRows = $updateStatement->execute();
            echo json_encode(array('result'=>'0','desc'=>'success'));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少运单id'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->get('/IsSchedulingSchedulings',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('is_show','=',0)
            ->where('tenant_id','=',$tenant_id)
            ->whereIn('scheduling_status',array(1,2,3));
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('schedule_order.tenant_id','=',$tenant_id)
                ->where('schedule_order.schedule_id','=',$data1[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('lorry_id','=',$data1[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id','=',$data5['pid']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id','=',$data7['pid']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $data1[$i]['orders']=$data2;
            $data1[$i]['receiver']=$data3;
            $data1[$i]['lorry']=$data4;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','schedulings'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/changeScheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $send_city_id=$body->send_city_id;
    $receive_city_id=$body->receive_city_id;
    $driver_name = $body->driver_name;
    $driver_phone = $body->driver_phone;
    $plate_number = $body->plate_number;
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $customer_address=$body->customer_address;
    $contact_tenant_id=$body->contact_tenant_id;
    $order_ids=$body->order_ids;
    $array1 = array();
    foreach ($order_ids as $key => $value) {
        $array1[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($send_city_id!=null||$send_city_id!=''){
            if($receive_city_id!=null||$receive_city_id!=''){
                if($customer_name!=null||$customer_name!=''){
                    if($customer_phone!=null||$customer_phone!=''){
                        if($customer_address!=null||$customer_address!=''){
                            if($contact_tenant_id){
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('exist','=',0)
                                    ->where('tenant_id','=',$contact_tenant_id);
                                $stmt = $selectStatement->execute();
                                $data= $stmt->fetch();
                                if(!$data){
                                    echo json_encode(array('result'=>'8','desc'=>'商户编号不存在'));
                                    exit;
                                }
                            }
                            $selectStatement = $database->select()
                                ->from('lorry')
                                ->where('exist','=',0)
                                ->where('driver_phone','=',$driver_phone)
                                ->where('plate_number','=',$plate_number)
                                ->where('driver_name','=',$driver_name)
                                ->where('tenant_id','=',$tenant_id);
                            $stmt = $selectStatement->execute();
                            $data1= $stmt->fetch();
                            if(!$data1){
                                echo json_encode(array('result'=>'9','desc'=>'该车辆不存在'));
                                exit;
                            }
                            $customer_id='';

                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('exist','=',0)
                                ->where('customer_city_id','=',$receive_city_id)
                                ->where('customer_address','=',$customer_address)
                                ->where('customer_name','=',$customer_name)
                                ->where('customer_phone','=',$customer_phone)
                                ->where('contact_tenant_id','=',$contact_tenant_id)
                                ->where('tenant_id','=',$tenant_id);
                            $stmt = $selectStatement->execute();
                            $data= $stmt->fetch();
                            if($data){
                                $updateStatement = $database->update(array("times"=>($data['times']+1)))
                                    ->table('customer')
                                    ->where('tenant_id','=',$tenant_id)
                                    ->where('customer_id','=',$data['customer_id']);
                                $affectedRows = $updateStatement->execute();
                                $customer_id=$data['customer_id'];
                            }else{
                                $selectStatement = $database->select()
                                    ->from('customer')
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $data= $stmt->fetchAll();
                                $num1=0;
                                for($i=0;$i<count($data);$i++){
                                    if(preg_match('/[a-zA-Z]/',$data[$i]['customer_id'])){
                                        $num1++;
                                    }
                                }
                                if($contact_tenant_id){
                                    $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','contact_tenant_id','exist','type'))
                                        ->into('customer')
                                        ->values(array(((count($data)-$num1)+10000000001),$tenant_id,$receive_city_id,$customer_address,$customer_name,$customer_phone,$contact_tenant_id,'0',3));
                                    $insertId = $insertStatement->execute(false);
                                }else{
                                    $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','exist','type'))
                                        ->into('customer')
                                        ->values(array(((count($data)-$num1)+10000000001),$tenant_id,$receive_city_id,$customer_address,$customer_name,$customer_phone,'0',3));
                                    $insertId = $insertStatement->execute(false);
                                }
                                $customer_id=(count($data)-$num1)+10000000001;
                            }
                            date_default_timezone_set("PRC");
                            $time=time();
                            $shijian=date("Y-m-d H:i:s",time());
                            $updateStatement = $database->update(array("change_datetime"=>$time,"scheduling_datetime"=>$shijian,"send_city_id"=>$send_city_id,"receive_city_id"=>$receive_city_id,"lorry_id"=>$data1['lorry_id'],"receiver_id"=>$customer_id,"scheduling_status"=>1,"exist"=>0,"is_show"=>0,"is_alter"=>0,"is_load"=>1,"is_contract"=>1,"is_insurance"=>1,"is_scan"=>0))
                                ->table('scheduling')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('scheduling_id','=',$scheduling_id);
                            $affectedRows = $updateStatement->execute();
                            for($i=0;$i<count($array1);$i++){
                                $selectStatement = $database->select()
                                    ->from('schedule_order')
                                    ->where('order_id','=',$array1[$i])
                                    ->where('schedule_id','=',$scheduling_id)
                                    ->where('exist','=',0)
                                    ->where('tenant_id','=',$tenant_id);
                                $stmt = $selectStatement->execute();
                                $dataa= $stmt->fetch();
                                if(!$dataa){
                                    $insertStatement = $database->insert(array('schedule_id','tenant_id','order_id','exist'))
                                        ->into('schedule_order')
                                        ->values(array($scheduling_id,$tenant_id,$array1[$i],0));
                                    $insertId = $insertStatement->execute(false);
                                }
                            }
                            $updateStatement = $database->update(array("exist"=>1))
                                ->table('schedule_order')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('schedule_id','=',$scheduling_id)
                                ->whereNotIn('order_id',array_values($array1));
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result'=>'0','desc'=>'success','scheduling_id'=>$scheduling_id));
                        }else{
                            echo json_encode(array('result'=>'7','desc'=>'缺少客户地址'));
                        }
                    }else{
                        echo json_encode(array('result'=>'6','desc'=>'缺少客户手机'));
                    }
                }else{
                    echo json_encode(array('result'=>'5','desc'=>'缺少客户名字'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'缺少收货城市'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少发货城市'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/onRoadScheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $order_ids=$body->order_ids;
    $array1 = array();
    foreach ($order_ids as $key => $value) {
        $array1[$key] = $value;
    }
    $selectStatement = $database->select()
        ->from('scheduling')
        ->where('scheduling_id','=',$scheduling_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data= $stmt->fetch();
    if($data['scheduling_status']==2){
        date_default_timezone_set("PRC");
        $shijian=date("Y-m-d H:i:s",time());
        $updateStatement = $database->update(array("scheduling_datetime"=>$shijian,"scheduling_status"=>4,"exist"=>0,"is_show"=>1,"is_alter"=>0,"is_load"=>1,"is_contract"=>1,"is_insurance"=>1,"is_scan"=>0))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id);
        $affectedRows = $updateStatement->execute();
        $updateStatement = $database->update(array("is_schedule" => 2,'order_status'=>3,'order_datetime3'=>$shijian,'order_datetime2'=>$shijian))
            ->table('orders')
            ->where('tenant_id', '=', $tenant_id)
            ->whereIn('order_id', array_values($array1));
        $affectedRows = $updateStatement->execute();
        echo json_encode(array('result'=>'0','desc'=>'success'));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'请司机在交付帮手上确认'));
    }
});

$app->put('/orderIsSchedule',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $selectStatement = $database->select()
        ->from('orders')
        ->where('orders.tenant_id','=',$tenant_id)
        ->where('orders.exist','=',0)
        ->where('orders.order_status','=',1)
        ->whereNull('orders.exception_id')
        ->where('orders.is_back','=',0)
        ->where('orders.is_schedule','=',1);
    $stmt = $selectStatement->execute();
    $dataa = $stmt->fetchAll();
    for($i=0;$i<count($dataa);$i++){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',1)
            ->where('order_id','=',$dataa[$i]['order_id']);
        $stmt = $selectStatement->execute();
        $datab= $stmt->fetch();
        if($datab){
            $updateStatement = $database->update(array("is_schedule" => 0))
                ->table('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id','=',$dataa[$i]['order_id']);
            $affectedRows = $updateStatement->execute();
        }else{
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('order_id','=',$dataa[$i]['order_id']);
            $stmt = $selectStatement->execute();
            $datab= $stmt->fetch();
            if(!$datab){
                $updateStatement = $database->update(array("is_schedule" => 0))
                    ->table('orders')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_id','=',$dataa[$i]['order_id']);
                $affectedRows = $updateStatement->execute();
            }
        }
    }
});

$app->get('/insuranceSchedulings',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('scheduling.is_insurance','=',1)
            ->where('scheduling.exist','=',0)
            ->whereIn('scheduling.scheduling_status',array(1,2,3,4))
            ->whereLike('lorry.lorry_id','%'.$lorry_id.'%');
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->sum('goods_value','goods_value_zon')
                ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('schedule_order.tenant_id','=',$tenant_id)
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('schedule_order.tenant_id','=',$tenant_id)
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetchAll();
            $data[$i]['send_city_name']=$data1['name'];
            $data[$i]['receive_city_name']=$data2['name'];
            $data[$i]['orders_goods']=$data3;
            $data[$i]['goods_value_zon']=$data4['goods_value_zon'];
        }
        echo json_encode(array('result'=>'0','desc'=>'success','insuranceschedulings'=>$data));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->put('/changeIsInsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_insurance=$body->is_insurance;
        $updateStatement = $database->update(array("is_insurance" => $is_insurance))
            ->table('scheduling')
            ->where('tenant_id', '=', $tenant_id)
            ->where('exist','=',0)
            ->where('scheduling_id','=',$scheduling_id);
        $affectedRows = $updateStatement->execute();
});

$app->post('/addInsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_ids=$body->scheduling_ids;
    $driver_name=$body->driver_name;
    $plate_number=$body->plate_number;
    $driver_phone=$body->driver_phone;
    $transtime=$body->transtime;
    $insurance_price=$body->insurance_price;
    $insurance_amount=$body->insurance_amount;
    $array1 = array();
    $schedu='';
    foreach ($scheduling_ids as $key => $value) {
        $array1[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
       if($driver_name!=null||$driver_name!=''){
           if($plate_number!=null||$plate_number!=''){
               if($driver_phone!=null||$driver_phone!=''){
                   if($transtime!=null||$transtime!=''){
                       if($scheduling_ids){
                           $selectStatement = $database->select()
                               ->from('lorry')
                               ->where('exist','=',0)
                               ->where('driver_phone','=',$driver_phone)
                               ->where('plate_number','=',$plate_number)
                               ->where('driver_name','=',$driver_name)
                               ->where('tenant_id','=',$tenant_id);
                           $stmt = $selectStatement->execute();
                           $data1= $stmt->fetch();
                           if(!$data1){
                               echo json_encode(array('result'=>'99','desc'=>'该车辆不存在'));
                               exit;
                           }
                           for($i=0;$i<count($array1);$i++){
                               $selectStatement = $database->select()
                                   ->from('scheduling')
                                   ->where('scheduling_id','=',$array1[$i])
                                   ->where('tenant_id','=',$tenant_id)
                                   ->where('is_insurance','=',3)
                                   ->where('scheduling.exist','=',0)
                                   ->where('lorry_id','=',$data1['lorry_id']);
                               $stmt = $selectStatement->execute();
                               $data2= $stmt->fetch();
                               if(!$data2) {
                                   if ($schedu) {
                                       $schedu .= $array1[$i];
                                   } else {
                                       $schedu .= ',' . $array1[$i];
                                   }
                               }
                           }
                           if($schedu){
                               echo json_encode(array('result'=>'98','desc'=>'清单号：'.$schedu.'与车辆不符'));
                               exit;
                           }
                           $selectStatement = $database->select()
                               ->from('insurance')
                               ->where('tenant_id','=',$tenant_id);
                           $stmt = $selectStatement->execute();
                           $data3= $stmt->fetchAll();
                           $insurance_id=count($data3)+1000000001;
                           date_default_timezone_set("PRC");
                           $shijian=date("Y-m-d H:i:s",time());
                           $insertStatement = $database->insert(array('insurance_id','tenant_id','insurance_lorry_id','exist','insurance_price','insurance_amount','transtime','insurance_start_time'))
                               ->into('insurance')
                               ->values(array($insurance_id,$tenant_id,$data1['lorry_id'],0,$insurance_price,$insurance_amount,$transtime,$shijian));
                           $insertId = $insertStatement->execute(false);
                           for($i=0;$i<count($array1);$i++){
                               $insertStatement = $database->insert(array('insurance_id','tenant_id','scheduling_id','exist'))
                                   ->into('insurance_scheduling')
                                   ->values(array($insurance_id,$tenant_id,$array1[$i],0));
                               $insertId = $insertStatement->execute(false);
                               $updateStatement = $database->update(array("is_insurance" => 2))
                                   ->table('scheduling')
                                   ->where('tenant_id', '=', $tenant_id)
                                   ->where('scheduling_id','=',$array1[$i]);
                               $affectedRows = $updateStatement->execute();
                           }
                           echo json_encode(array('result'=>'0','desc'=>'success'));
                       }else{
                           echo json_encode(array('result'=>'6','desc'=>'缺少调度单'));
                       }
                   }else{
                       echo json_encode(array('result'=>'5','desc'=>'缺少起运时间'));
                   }
               }else{
                   echo json_encode(array('result'=>'4','desc'=>'缺少驾驶员电话'));
               }
           }else{
               echo json_encode(array('result'=>'3','desc'=>'缺少车牌号码'));
           }
       }else{
           echo json_encode(array('result'=>'2','desc'=>'缺少驾驶员名字'));
       }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->post('/getInsuranceObject',function()use($weiXinPay,$app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $code=$body->code;
    $appid = "wx639d19e5e9c11787";
    $secret = "41738390b7aad003cc044f71464298c4";
    $url="https://api.weixin.qq.com/sns/jscode2session?appid=$appid&secret=$secret&js_code=$code&grant_type=authorization_code";
    $json_obj=getOpenid($url);
    $openid=$json_obj['openid'];
    $weiXinPay->setBody('这是个商品');
    $weiXinPay->setOpenid($openid);
    $weiXinPay->setTotalFee('1');
    $return=$weiXinPay->pay();
    echo json_encode($return);
});

$app->get('/agreementSchedulings',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('scheduling.is_contract','=',1)
            ->where('scheduling.exist','=',0)
            ->whereIn('scheduling.scheduling_status',array(1,2,3,4,5))
            ->whereLike('lorry.lorry_id','%'.$lorry_id.'%');
        $stmt = $selectStatement->execute();
        $data= $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry_id','=',$data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('schedule_order.tenant_id','=',$tenant_id)
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetchAll();
            $data[$i]['send_city_name']=$data1['name'];
            $data[$i]['receive_city_name']=$data2['name'];
            $data[$i]['plate_number']=$data3['plate_number'];
            $data[$i]['driver_name']=$data3['driver_name'];
            $data[$i]['driver_phone']=$data3['driver_phone'];
            $data[$i]['orders']=$data4;
            if($data[$i]['scheduling_status']==1){
                $data[$i]['scheduling_status']='生成清单';
            }else if($data[$i]['scheduling_status']==2){
                $data[$i]['scheduling_status']='司机确认';
            }else if($data[$i]['scheduling_status']==3){
                $data[$i]['scheduling_status']='装车完成';
            }else if($data[$i]['scheduling_status']==4){
                $data[$i]['scheduling_status']='在途';
            }else if($data[$i]['scheduling_status']==5){
                $data[$i]['scheduling_status']='到达';
            }
        }
        echo json_encode(array('result'=>'0','desc'=>'success','agreementschedulings'=>$data));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
    }
});

$app->put('/changeIsContract',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_contract=$body->is_contract;
    $updateStatement = $database->update(array("is_contract" => $is_contract))
        ->table('scheduling')
        ->where('tenant_id', '=', $tenant_id)
        ->where('exist','=',0)
        ->where('scheduling_id','=',$scheduling_id);
    $affectedRows = $updateStatement->execute();
});

$app->post('/checkagreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_ids=$body->scheduling_ids;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $plate_number=$body->plate_number;
    $array1 = array();
    $schedu='';
    foreach ($scheduling_ids as $key => $value) {
        $array1[$key] = $value;
    }
    if($driver_name!=null||$driver_name!=''){
        if($driver_phone!=null||$driver_phone!=''){
            if($plate_number!=null||$plate_number!=''){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('driver_phone','=',$driver_phone)
                    ->where('driver_name','=',$driver_name)
                    ->where('plate_number','=',$plate_number)
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetch();
                if($data1){
                    for($i=0;$i<count($array1);$i++){
                        $selectStatement = $database->select()
                            ->from('scheduling')
                            ->where('scheduling_id','=',$array1[$i])
                            ->where('tenant_id','=',$tenant_id)
                            ->where('is_contract','=',3)
                            ->where('scheduling.exist','=',0)
                            ->where('lorry_id','=',$data1['lorry_id']);
                        $stmt = $selectStatement->execute();
                        $data2= $stmt->fetch();
                        if(!$data2) {
                            if ($schedu) {
                                $schedu .= $array1[$i];
                            } else {
                                $schedu .= ',' . $array1[$i];
                            }
                        }
                    }
                    if($schedu){
                        echo json_encode(array('result'=>'98','desc'=>'清单号：'.$schedu.'与车辆不符'));
                    }else{
                        $num=0;
                        $values=0;
                        $counts=0;
                        $capacitys=0;
                        $weights=0;
                        for($i=0;$i<count($array1);$i++) {

                            $selectStatement = $database->select()
                                ->count('*','num')
                                ->sum('goods.goods_value','all_values')
                                ->sum('goods.goods_count','counts')
                                ->sum('goods.goods_capacity','capacitys')
                                ->sum('	goods.goods_weight','weights')
                                ->from('schedule_order')
                                ->leftJoin('orders','orders.order_id','=','schedule_order.order_id')
                                ->leftJoin('goods','goods.order_id','=','schedule_order.order_id')
                                ->where('schedule_order.schedule_id', '=', $array1[$i])
                                ->where('orders.tenant_id', '=', $tenant_id)
                                ->where('goods.tenant_id', '=', $tenant_id)
                                ->where('schedule_order.tenant_id', '=', $tenant_id)
                                ->where('orders.exist', '=', 0)
                                ->where('goods.exist', '=', 0)
                                ->where('schedule_order.exist', '=', 0);
                            $stmt = $selectStatement->execute();
                            $data3 = $stmt->fetch();
                            $num+=$data3['num'];
                            $values+=$data3['all_values'];
                            $counts+=$data3['counts'];
                            $capacitys+=$data3['capacitys'];
                            $weights+=$data3['weights'];
                        }
                            $selectStatement = $database->select()
                                ->from('scheduling')
                                ->where('scheduling_id', '=', $array1[0])
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('exist', '=', 0);
                            $stmt = $selectStatement->execute();
                            $data8 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data8['send_city_id']);
                        $stmt = $selectStatement->execute();
                        $data9= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data8['receive_city_id']);
                        $stmt = $selectStatement->execute();
                        $data10= $stmt->fetch();
                        $array2=array();
                        $array2['num']=$num;
                        $array2['values']=$values;
                        $array2['counts']=$counts;
                        $array2['capacitys']=$capacitys;
                        $array2['weights']=$weights;
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('exist','=',0);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id','=',$data4['contact_id'])
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data5['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $array3=array();
                        $array3['company']=$data4['company'];
                        $array3['name']=$data5['customer_name'];
                        $array3['phone']=$data5['customer_phone'];
                        $array3['address']=$data7['name'].$data6['name'].$data5['customer_address'];
                        $array3['lutu']=$data9['name'].'到'.$data10['name'];
                        echo json_encode(array('result'=>'0','desc'=>'success','xingxi'=>$array2,'tenant'=>$array3));
                    }
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'该车辆不存在'));
                }
            }else{
                echo json_encode(array('result'=>'1','desc'=>'缺少车牌号码'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少驾驶员电话'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'缺少驾驶员名字'));
    }
});

$app->post('/add_agreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_ids=$body->scheduling_ids;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $plate_number=$body->plate_number;
    $deadline=$body->deadline;
    $pay_method=$body->pay_method;
    $freight=$body->freight;
    $agreement_comment=$body->agreement_comment;
    $array = array();
    foreach ($body as $key => $value) {
        if($key!='scheduling_ids'){
            $array[$key] = $value;
        }
    }
    $array1 = array();
    foreach ($scheduling_ids as $key => $value) {
        $array1[$key] = $value;
    }
    if($driver_name!=null||$driver_name!=''){
        if($driver_phone!=null||$driver_phone!=''){
            if($plate_number!=null||$plate_number!=''){
                if($freight!=null||$freight!=''){
                    if($deadline!=null||$deadline!=''){
                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('driver_phone','=',$driver_phone)
                            ->where('driver_name','=',$driver_name)
                            ->where('plate_number','=',$plate_number)
                            ->where('tenant_id','=',$tenant_id)
                            ->where('exist','=',0);
                        $stmt = $selectStatement->execute();
                        $data1= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->count('*','num')
                            ->from('agreement')
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data2= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('app_lorry')
                            ->where('driver_phone','=',$driver_phone)
                            ->where('driver_name','=',$driver_name)
                            ->where('plate_number','=',$plate_number)
                            ->where('lorry_status','=',2)
                            ->where('exist','=',0);
                        $stmt = $selectStatement->execute();
                        $data3= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('ticket_lorry')
                            ->where('lorry_id','=',$data3['app_lorry_id']);
                        $stmt = $selectStatement->execute();
                        $data4= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('scheduling')
                            ->where('scheduling_id','=',$array1[0])
                            ->where('tenant_id','=',$tenant_id)
                            ->where('is_contract','=',3)
                            ->where('exist','=',0)
                            ->where('lorry_id','=',$data1['lorry_id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data5['receive_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6= $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data7= $stmt->fetch();
                        $agreement_id='';
                        if(strlen(($data2['num']+1).'')==1){
                            $agreement_id='HT'.$data7['tenant_num'].'00000'.($data2['num']+1);
                        }else if(strlen(($data2['num']+1).'')==2){
                            $agreement_id='HT'.$data7['tenant_num'].'0000'.($data2['num']+1);
                        }else if(strlen(($data2['num']+1).'')==3){
                            $agreement_id='HT'.$data7['tenant_num'].'000'.($data2['num']+1);
                        }else if(strlen(($data2['num']+1).'')==4){
                            $agreement_id='HT'.$data7['tenant_num'].'00'.($data2['num']+1);
                        }else if(strlen(($data2['num']+1).'')==5){
                            $agreement_id='HT'.$data7['tenant_num'].'0'.($data2['num']+1);
                        }else if(strlen(($data2['num']+1).'')==6){
                            $agreement_id='HT'.$data7['tenant_num'].''.($data2['num']+1);
                        }else{
                            echo json_encode(array('result'=>'10','desc'=>'合同满仓'));
                            exit;
                        }
                        date_default_timezone_set("PRC");
                        $shijian=date("Y-m-d H:i:s",time());
                        $array['agreement_id']=$agreement_id;
                        $array['secondparty_id']=$data1['lorry_id'];
                        $array['agreement_time']=$shijian;
                        $array['rcity']=$data6['name'];
                        $array['company_id']=$data4['company_id'];
                        $array['exist']=0;
                        $array['tenant_id']=$tenant_id;
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('agreement')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);
                        for($i=0;$i<count($array1);$i++){
                            $insertStatement = $database->insert(array('tenant_id','agreement_id','scheduling_id','exist'))
                                ->into('agreement_schedule')
                                ->values(array($tenant_id,$agreement_id,$array1[$i],0));
                            $insertId = $insertStatement->execute(false);
                            $updateStatement = $database->update(array("is_contract" => 2))
                                ->table('scheduling')
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('scheduling_id','=',$array1[$i]);
                            $affectedRows = $updateStatement->execute();
                        }
                        echo json_encode(array('result'=>'0','desc'=>'success'));
                    }else{
                        echo json_encode(array('result'=>'1','desc'=>'缺少约定时间'));
                    }
                }else{
                    echo json_encode(array('result'=>'2','desc'=>'缺少运费'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'缺少车牌号码'));
            }
        }else{
            echo json_encode(array('result'=>'4','desc'=>'缺少驾驶员电话'));
        }
    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少驾驶员名字'));
    }
});

$app->run();

function localhost(){
    return connect();
}
//加密
function encode($string , $skey ) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

//解密
function decode($string, $skey) {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}

function getOpenid($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($output, true);
    return $json_obj;
}
?>