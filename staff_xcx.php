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
//    if($special_need==0){
//        $special_need='寄:门店自寄;  收:送货上门';
//    }else{
//        $special_need='寄:上门提货;  收:门店自提';
//    }
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
                                                                        ->where('customer_phone','=',$customer_phone_a)
                                                                        ->where('type','=',1);
                                                                    $stmt = $selectStatement->execute();
                                                                    $data = $stmt->fetch();
                                                                  $customer_id1='';
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
                                                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','type','exist','times'))
                                                                            ->into('customer')
                                                                            ->values(array($customer_id1,$tenant_id,$customer_city_a,$customer_address_a,$customer_name_a,$customer_phone_a,1,0,1));
                                                                        $insertId = $insertStatement->execute(false);
                                                                    }else{
                                                                        $customer_id1=$data['customer_id'];
                                                                        $updateStatement = $database->update(array('times'=>($data['times']+1)))
                                                                            ->table('customer')
                                                                            ->where('customer_id','=',$customer_id1)
                                                                            ->where('tenant_id','=',$tenant_id);
                                                                        $affectedRows = $updateStatement->execute();
                                                                    }
                                                                    $selectStatement = $database->select()
                                                                        ->from('customer')
                                                                        ->where('tenant_id','=',$tenant_id)
                                                                        ->where('customer_city_id','=',$customer_city_b)
                                                                        ->where('customer_address','=',$customer_address_b)
                                                                        ->where('customer_name','=',$customer_name_b)
                                                                        ->where('customer_phone','=',$customer_phone_b)
                                                                        ->where('type','=',0);
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
                                                                        $insertStatement = $database->insert(array('customer_id','tenant_id','customer_city_id','customer_address','customer_name','customer_phone','type','exist',"times"))
                                                                            ->into('customer')
                                                                            ->values(array($customer_id2,$tenant_id,$customer_city_b,$customer_address_b,$customer_name_b,$customer_phone_b,0,0,0));
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
                                                                    $insertStatement = $database->insert(array('order_id','tenant_id','sender_id','receiver_id','pay_method','order_cost','order_status','exist','order_datetime0','is_sign','order_datetime1','inventory_type'))
                                                                        ->into('orders')
                                                                        ->values(array($order_id,$tenant_id,$customer_id1,$customer_id2,$pay_method,$order_cost,1,0,$shijian,'0',$shijian,$inventory_type));
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
//            if($data1){
//                $selectStatement = $database->select()
//                    ->from('lorry')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('exist','=',0)
//                    ->where('driver_phone','=',$data1['phone'])
//                    ->where('plate_number','=',$data1['plate_number'])
//                    ->where('driver_name','=',$data1['name']);
//                $stmt = $selectStatement->execute();
//                $data2= $stmt->fetch();
//                if($data2){
//                    echo json_encode(array('result'=>'3','desc'=>'该车辆已添加'));
//                }else{
                    $insertStatement = $database->insert(array('tenant_id','lorry_id','plate_number','driver_name','driver_phone','flag','exist'))
                        ->into('lorry')
                        ->values(array($tenant_id,(count($data)+100000001),$data1['plate_number'],$data1['name'],$data1['phone'],0,0));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array('result'=>'0','desc'=>'success'));
//                }
//            }else{
//                echo json_encode(array('result'=>'2','desc'=>'车辆未注册'));
//            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少租户id'));
        }
    }else{
//        $selectStatement = $database->select()
//            ->from('app_lorry')
//            ->where('flag','=',0)
//            ->where('lorry_status','=',2)
//            ->where('app_lorry_id','=',$app_lorry_id);
//        $stmt = $selectStatement->execute();
//        $data1= $stmt->fetch();
//        if($data1){
//            echo json_encode(array('result'=>'4','desc'=>'无法驳回'));
//        }else{
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
//        }
    }
});

$app->post('/checkAppLorry',function()use($app){
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
            ->from('app_lorry')
            ->where('flag','=',0)
            ->where('exist','=',0)
            ->where('phone','=',$driver_phone)
            ->where('plate_number','=',$plate_number)
            ->where('name','=',$driver_name);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        if($data1!=null){
            if($data1['lorry_status']!=1){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('flag','=',0)
                    ->where('tenant_id','=',$tenant_id)
                    ->where('driver_phone','=',$driver_phone)
                    ->where('plate_number','=',$plate_number)
                    ->where('driver_name','=',$driver_name);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                if($data2!=null){
                    if($data2['exist']==0){
                        echo json_encode(array('result'=>'4','desc'=>'车辆已添加过'));
                    }else if($data2['exist']==1){
                        echo json_encode(array('result'=>'3','desc'=>'车辆被加入黑名单'));
                    }else{
                            $selectStatement = $database->select()
                                ->from('lorry_length')
                                ->where('lorry_length_id','=',$data1['length']);
                            $stmt = $selectStatement->execute();
                            $data5= $stmt->fetch();
                            $data1['lorry_length_name']=$data5['lorry_length'];
//                    $data1['lorry_load_name']=$data1['deadweight'];
                            $selectStatement = $database->select()
                                ->from('lorry_type')
                                ->where('lorry_type_id','=',$data1['type']);
                            $stmt = $selectStatement->execute();
                            $data4= $stmt->fetch();
                            $data1['lorry_type_name']=$data4['lorry_type_name'];
                            echo json_encode(array("result"=>"0","desc"=>"","lorry_id"=>$data1['app_lorry_id']));
                        }
                }else{
                    $selectStatement = $database->select()
                        ->from('lorry_length')
                        ->where('lorry_length_id','=',$data1['length']);
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetch();
                    $data1['lorry_length_name']=$data5['lorry_length'];
                    $selectStatement = $database->select()
                        ->from('lorry_type')
                        ->where('lorry_type_id','=',$data1['type']);
                    $stmt = $selectStatement->execute();
                    $data4= $stmt->fetch();
                    $data1['lorry_type_name']=$data4['lorry_type_name'];
                    echo json_encode(array("result"=>"0","desc"=>"","lorry_id"=>$data1['app_lorry_id']));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'正在修改资料'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'未注册交付帮手'));
        }
    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});

$app->get('/getAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $applorry=$app->request->get("app_lorry_id");
    if($applorry!=null||$applorry!=null){
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('app_lorry_id','=',$applorry);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('lorry_length')
                ->where('lorry_length_id','=',$data['length']);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetch();
            $data['lorry_length_name']=$data5['lorry_length'];
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('lorry_type_id','=',$data['type']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetch();
            $data['lorry_type_name']=$data4['lorry_type_name'];
            echo json_encode(array("result"=>"0","desc"=>"","lorry"=>$data));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"司机不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少司机ID"));
    }
});


$app->put('/changeLorryExist',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id = $body->lorry_id;
    $database=localhost();
    $exist=$body->exist;
    if($tenant_id!=''||$tenant_id!=null){
        if($lorry_id!=''||$lorry_id!=null){
            $updateStatement = $database->update(array("exist"=>$exist))
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
        for($x=0;$x<count($data);$x++){
            $selectStatement = $database->select()
                ->from('app_lorry')
                ->where('flag','=',0)
                ->where('phone','=',$data[$x]['driver_phone'])
                ->where('plate_number','=',$data[$x]['plate_number'])
                ->where('name','=',$data[$x]['driver_name']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry_length')
                ->where('lorry_length_id','=',$data2['length']);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetch();
            $data[$x]['lorry_length_name']=$data5['lorry_length'];
            $selectStatement = $database->select()
                ->from('lorry_type')
                ->where('lorry_type_id','=',$data2['type']);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetch();
            $data[$x]['lorry_type_name']=$data4['lorry_type_name'];
            $data[$x]['deadweight']=$data2['deadweight'];
        }
        echo json_encode(array('result'=>'0','desc'=>'success','lorry'=>$data));
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
            $data[$i]['receive_address']=$data1['customer_address'];
            $data[$i]['receive_phone']=$data1['customer_phone'];
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
    $i=$body->i;
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=''){
        if($order_id!=null||$order_id!=''){
            $updateStatement = $database->update(array("is_schedule"=>$is_schedule))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result'=>'0','desc'=>'success','i'=>$i));
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
            if($datab){
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
                ->where('lorry_id','=',$data[$i]['lorry_id'])
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
                ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
                ->join('goods_package','goods.goods_package_id','=','goods_package.goods_package_id','INNER')
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
//            $selectStatement = $database->select()
//                ->from('goods_package')
//                ->where('goods_package_id','=',$data4['goods_package_id']);
//            $stmt = $selectStatement->execute();
//            $data5= $stmt->fetch();
//            $data4['goods_package']=$data5['goods_package'];
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
    $i=$body->i;
    $updateStatement = $database->update(array("is_contract" => $is_contract))
        ->table('scheduling')
        ->where('tenant_id', '=', $tenant_id)
        ->where('exist','=',0)
        ->where('scheduling_id','=',$scheduling_id);
    $affectedRows = $updateStatement->execute();
    echo json_encode(array('result'=>'0','desc'=>'success','i'=>$i));
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
                            if ($schedu==null||$schedu=="") {
                                $schedu .= $array1[$i];
                            } else {
                                $schedu .= '、'.$array1[$i];
                            }
                        }
                    }
                    if($schedu){
                        echo json_encode(array('result'=>'98','desc'=>'清单号：'.$schedu.'，与车辆不符'));
                    }else{
                        $array2=array();
                        for($i=0;$i<count($array1);$i++){
                            $selectStatement = $database->select()
                                ->from('scheduling')
                                ->where('scheduling_id', '=', $array1[$i])
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('exist', '=', 0);
                            $stmt = $selectStatement->execute();
                            $data8 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data8['receive_city_id']);
                        $stmt = $selectStatement->execute();
                        $data10= $stmt->fetch();
                        array_push($array2,$data10['name']);
                        }
                        $selectStatement = $database->select()
                            ->from('scheduling')
                            ->where('scheduling_id', '=', $array1[0])
                            ->where('tenant_id', '=', $tenant_id)
                            ->where('exist', '=', 0);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data9['send_city_id']);
                        $stmt = $selectStatement->execute();
                        $data11= $stmt->fetch();
                        echo json_encode(array('result'=>'0','desc'=>'success','ecity'=>$array2,'scity'=>$data11['name']));
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


$app->put('/newChangeIsContract',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $updateStatement = $database->update(array("is_contract" => 1))
        ->table('scheduling')
        ->where('tenant_id', '=', $tenant_id)
        ->where('exist','=',0)
        ->where('is_contract','=',3);
    $affectedRows = $updateStatement->execute();
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

$app->get('/getTenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $array1=array();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id',"=",$tenant_id)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        $array1['company']=$data['company'];
        if($data!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id',"=",$data['contact_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id',"=",$data1['customer_city_id']);
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetch();
            $array1['address']=$data1['customer_address'];
            $array1['customer_name']=$data1['customer_name'];
            $array1['customer_phone']=$data1['customer_phone'];
            echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$array1));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
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
                                                $array4['customer_id'] = (count($data8)+10000000001)."";
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
                                                $array4['customer_id']=(count($data8)+10000000001)."";
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
                                                        $array4['customer_id'] = (count($data8) + 10000000001)."";
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
                                                        $array4['customer_id']=(count($data8)+10000000001)."";
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
                                                    $array5['customer_id'] = (count($data14) + 10000000001)."";
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
    $array6=array();
    foreach ($order_ary as $key => $value) {
        $array6[$key] = $value;
    }
    $array4=array();
    $partner_name=$body->partner_name;
    $partner_phone=$body->partner_phone;
    $partner_city_id=$body->partner_city_id;
    $partner_address=$body->partner_address;
    $partner_type=$body->partner_type;
    $partner_times=$body->partner_times;
    $array5=array();
    $array8=array();
    for($n=0;$n<count($array6);$n++){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id)
            ->where("order_id",'=',$array6[$n])
            ->where("exist","=",1)
            ->orderBy('id');
        $stmt = $selectStatement->execute();
        $data20 = $stmt->fetchAll();
        if(count($data20)==0){

        }else if(count($data20)>0){
            $m=count($data20)-1;
            if($data20[$m]['schedule_id']==$scheduling_id){

            }else{
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('tenant_id', '=',$tenant_id)
                    ->where("schedule_id",'=',$data20[$m]['schedule_id']);
                $stmt = $selectStatement->execute();
                $data21 = $stmt->fetchAll();
                if(count($data21)==1){
                    array_push($array8,$data20[$m]['schedule_id']);
                }else if(count($data21)>1){

                }
            }
        }
    }
    if(count($array8)==0){
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
                                                $array4['customer_id'] = (count($data8) + 10000000001)."";
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
                                                $array4['customer_id']=(count($data8)+10000000001)."";
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
                                                        $array4['customer_id']=(count($data8)+10000000001)."";
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
                                                        $array4['customer_id']=(count($data8)+10000000001)."";
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
                                                    $array5['customer_id'] =(count($data14)+10000000001)."";
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
    }else{
        echo json_encode(array("result" => "11","desc" => "","sids"=>$array8));
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

$app->post('/addAgreementScheduling',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $secondparty_id=$body->secondparty_id;
    $pay_method=$body->pay_method;
    $deadline=$body->deadline;
    $agreement_require=$body->agreement_require;
    $rcity=$body->rcity;
    $is_ticket=$body->is_ticket;
    $company_id=$body->company_id;
    $scheduling_ary=$body->scheduling_ary;
    $freight=$body->freight;
    $tenant_num=$body->tenant_num;
    $array1=null;
    foreach ($scheduling_ary as $key => $value) {
        $array1[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('agreement')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        $agreement_id=null;
        if((count($data9)+1)<10){
            $agreement_id='HT'.$tenant_num."00000".(count($data9)+1);
        }else if((count($data9)+1)<100&&(count($data9)+1)>9){
            $agreement_id='HT'.$tenant_num."0000".(count($data9)+1);
        }else if((count($data9)+1)<1000&&(count($data9)+1)>99){
            $agreement_id='HT'.$tenant_num."000".(count($data9)+1);
        }else if((count($data9)+1)<10000&&(count($data9)+1)>999){
            $agreement_id='HT'.$tenant_num."00".(count($data9)+1);
        }else if((count($data9)+1)<100000&&(count($data9)+1)>9999){
            $agreement_id='HT'.$tenant_num."0".(count($data9)+1);
        }else if((count($data9)+1)<1000000&&(count($data9)+1)>99999){
            $agreement_id='HT'.$tenant_num.(count($data9)+1);
        }
        for($x=0;$x<count($array1);$x++){
            $selectStatement = $database->select()
                ->from('agreement_schedule')
                ->where('tenant_id', '=', $tenant_id)
                ->where('agreement_id','=',$agreement_id)
                ->where('scheduling_id','=',$array1[$x])
                ->where("exist",'=',0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $updateStatement = $database->update(array('is_contract'=>2))
                ->table('scheduling')
                ->where('scheduling_id','=',$array1[$x])
                ->where('tenant_id','=',$tenant_id);
            $affectedRows = $updateStatement->execute();
            if($data2==null) {
                $insertStatement = $database->insert(array("tenant_id", "agreement_id", "scheduling_id", "exist"))
                    ->into('agreement_schedule')
                    ->values(array($tenant_id, $agreement_id, $array1[$x], 0));
                $insertId = $insertStatement->execute(false);
            }
        }
        $insertStatement = $database->insert(array("agreement_id","secondparty_id","pay_method","deadline","agreement_require","rcity","is_ticket","company_id","freight","tenant_id"))
            ->into('agreement')
            ->values(array($agreement_id,$secondparty_id,$pay_method,$deadline,$agreement_require,$rcity,$is_ticket,$company_id,$freight,$tenant_id));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getDepartList',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('scheduling_status', '=',1)
            ->where('is_load','=',3)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry_id', '=', $data[$x]['lorry_id'])
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $data[$x]['lorry']=$data8;
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id', '=', $data[$x]['receiver_id'])
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data5['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $data6['customer_city_name']=$data6['name'];
            $data[$x]['receiver']=$data5;
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$x]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $data[$x]['send_city']=$data2['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$x]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $data[$x]['receive_city']=$data3['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","schedulings"=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getDepartDetail',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_id', '=', $scheduling_id)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('customer_id', '=', $data['receiver_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data['send_city_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data['send_city']=$data3;
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        $data['receive_city']=$data4['name'];
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data2['customer_city_id']);
        $stmt = $selectStatement->execute();
        $data5 = $stmt->fetch();
        $data2['customer_city']=$data5['name'];
        $data['receiver']=$data2;
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('schedule_id', '=', $scheduling_id)
            ->where('tenant_id','=',$tenant_id)
            ->where("exist",'=',0);
        $stmt = $selectStatement->execute();
        $data6= $stmt->fetchAll();
        for($x=0;$x<count($data6);$x++){
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data6[$x]['order_id']);
            $stmt = $selectStatement->execute();
            $data7 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods_package')
                ->where('goods_package_id', '=', $data7['goods_package_id']);
            $stmt = $selectStatement->execute();
            $data8 = $stmt->fetch();
            $data7['goods_package']=$data8['goods_package'];
            $data6[$x]['goods']=$data7;
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('order_id', '=', $data6[$x]['order_id']);
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
            $data13['customer_city']=$data15['name'];
            $data11['order_sender']=$data13;
            $data12['customer_city']=$data14['name'];
            $data11['order_receiver']=$data12;
            $data6[$x]['orders']=$data11;
        }
        $data['orders']=$data6;
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
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