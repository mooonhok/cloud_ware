<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 13:58
 */


require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
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
    $num1=0;
    $num2=0;
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
                                                                            if(!(preg_match('/[A-Za-z]*/',$data1[$i]['customer_id']))){
                                                                                $num1++;
                                                                            }
                                                                        }
                                                                        $customer_id1=$num1+10000000001;
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
                                                                            if(!(preg_match('/[A-Za-z]*/',$data3[$i]['customer_id']))){
                                                                                $num2++;
                                                                            }
                                                                        }
                                                                        $customer_id2=$num2+10000000001;
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
                                                                        ->values(array($order_id,$tenant_id,$customer_id1,$customer_id2,$pay_method,1,$order_cost,1,0,$shijian,'0',$shijian,0));
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
                                                                        $goods_id=$data6['tenant_num'].'00000'.'1';
                                                                    }
                                                                    $insertStatement = $database->insert(array('goods_id','order_id','tenant_id','goods_name','goods_count','goods_capacity','goods_weight','goods_package_id','goods_value','exist','special_need'))
                                                                        ->into('goods')
                                                                        ->values(array($goods_id,$order_id,$tenant_id,$goods_name,$goods_count,$goods_capacity,$goods_weight,$goods_package_id,$goods_value,0,$special_need));
                                                                    $insertId = $insertStatement->execute(false);
                                                                    echo json_encode(array('result'=>'0','desc'=>'success'));
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
?>