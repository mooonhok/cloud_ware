<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/21
 * Time: 16:03
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/scheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $send_city=$body->send_city;
    $receiver_id=$body->receiver_id;
    $receive_city=$body->receive_city;
    $lorry_id=$body->lorry_id;
    $is_transfer=$body->is_tranfer;
    $transfer_id=$body->transfer_id;
    $transfer_charges=$body->transfer_charges;
    $order_ids=$body->order_ids;
    $array=array();
    foreach($body as $key=>$value){
        if($key!="order_ids"){
            $array[$key]=$value;
        }
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($send_city!=null||$send_city!=''){
            if($receiver_id!=null||$receiver_id!=''){
                if($receive_city!=null||$receive_city!=''){
                        if($lorry_id!=null||$lorry_id!=''){
                            if($is_transfer!=null||$is_transfer!=''){
                                if($transfer_id!=null||$transfer_id!=''){
                                    if($transfer_charges!=null||$transfer_charges!=''){
                                        if(count($order_ids)!=0){
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->where('customer_id','=',$receiver_id)
                                                ->where('exist','=',"0")
                                                ->where('tenant_id','=',$tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data = $stmt->fetch();
                                            if($data!=null){
                                                $selectStatement = $database->select()
                                                    ->from('lorry')
                                                    ->where('lorry_id','=',$lorry_id)
                                                    ->where('exist','=',"0")
                                                    ->where('tenant_id','=',$tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data1 = $stmt->fetch();
                                                if($data1!=null){
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->where('customer_id','=',$transfer_id)
                                                        ->where('exist','=',"0")
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data2 = $stmt->fetch();
                                                    if($data2!=null){
                                                        $selectStatement = $database->select()
                                                            ->from('scheduling')
                                                            ->where('tenant_id','=',$tenant_id);
                                                        $stmt = $selectStatement->execute();
                                                        $data3 = $stmt->fetchAll();
                                                        if($data3!=null){
                                                            $scheduling_id=count($data3)+100000001;
                                                        }else{
                                                            $scheduling_id=100000001;
                                                        }
                                                        $now=date("Y-m-d H:i:s");
                                                        $array['scheduleing_datetime']=$now;
                                                        $array['tenant_id']=$tenant_id;
                                                        $array['scheduling_id']=$scheduling_id;
                                                        $array['exist']=0;
                                                        $insertStatement = $database->insert(array_keys($array))
                                                            ->into('scheduling')
                                                            ->values(array_values($array));
                                                        $insertId = $insertStatement->execute(false);
                                                        $num=count($order_ids);
                                                        for($i=0;$i<$num;$i++){
                                                            $insertStatement = $database->insert(array('schedule_id','order_id','tenant_id','exist'))
                                                                ->into('schedule_order')
                                                                ->values(array($scheduling_id,$order_ids[$i],$tenant_id,'0'));
                                                            $insertId = $insertStatement->execute(false);
                                                        }
                                                        echo json_encode(array("result"=>"0","desc"=>"success"));
                                                    }else{
                                                        echo json_encode(array("result"=>"0","desc"=>"中转接收人信息不存在"));
                                                    }
                                                }else{
                                                    echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                }
                                            }else{
                                                echo json_encode(array("result"=>"0","desc"=>"收货人信息不存在"));
                                            }

                                        }else{
                                            echo json_encode(array("result"=>"0","desc"=>"缺少订单信息"));
                                        }
                                    }else {
                                        echo json_encode(array("result"=>"0","desc"=>"缺少运费信息"));
                                    }
                                }else{
                                    echo json_encode(array('result'=>'1','desc'=>'缺少中转接收人信息'));
                                }
                            }else{
                                echo json_encode(array('result'=>'2','desc'=>'缺少中转信息'));
                            }
                        }else{
                            echo json_encode(array('result'=>'3','desc'=>'缺少车辆信息'));
                        }
                }else{
                    echo json_encode(array('result'=>'5','desc'=>'缺少收货人城市'));
                }
            }else{
                echo json_encode(array('result'=>'6','desc'=>'缺少收货人信息'));
            }
        }else{
            echo json_encode(array('result'=>'7','desc'=>'缺少发货人城市'));
        }
    }else{
        echo json_encode(array('result'=>'8','desc'=>'缺少租户id'));
    }
});



$app->put('/scheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $send_city=$body->send_city;
    $receiver_id=$body->receiver_id;
    $receive_city=$body->receive_city;
    $lorry_id=$body->lorry_id;
    $is_transfer=$body->is_tranfer;
    $scheduling_id=$body->scheduling_id;
    $order_ids=$body->order_ids;
    $array=array();
    foreach($body as $key=>$value){
        if($key!="order_ids"){
            $array[$key]=$value;
        }
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($send_city!=null||$send_city!=''){
            if($receiver_id!=null||$receiver_id!=''){
                if($receive_city!=null||$receive_city!=''){
                    if($lorry_id!=null||$lorry_id!=''){
                        if($is_transfer!=null||$is_transfer!=''){
                                    if(count($order_ids)!=0){
                                        if($scheduling_id!=null||$scheduling_id!=""){
                                            $selectStatement = $database->select()
                                                ->from('scheduling')
                                                ->where('scheduling_id','=',$scheduling_id)
                                                ->where('exist','=',"0")
                                                ->where('tenant_id','=',$tenant_id);
                                            $stmt = $selectStatement->execute();
                                            $data = $stmt->fetch();
                                            if($data!=null){
                                                $selectStatement = $database->select()
                                                    ->from('lorry')
                                                    ->where('lorry_id','=',$lorry_id)
                                                    ->where('exist','=',"0")
                                                    ->where('tenant_id','=',$tenant_id);
                                                $stmt = $selectStatement->execute();
                                                $data1 = $stmt->fetch();
                                                if($data1!=null){
                                                    $selectStatement = $database->select()
                                                        ->from('customer')
                                                        ->where('customer_id','=',$receiver_id)
                                                        ->where('exist','=',"0")
                                                        ->where('tenant_id','=',$tenant_id);
                                                    $stmt = $selectStatement->execute();
                                                    $data2 = $stmt->fetch();
                                                        if($data2!=null){
                                                            $num=count($order_ids);
                                                            $num1=0;
                                                            for($i=0;$i<$num;$i++){
                                                                $selectStatement = $database->select()
                                                                    ->from('orders')
                                                                    ->where('order_id','=',$order_ids[$i])
                                                                    ->where('exist','=',"0")
                                                                    ->where('tenant_id','=',$tenant_id);
                                                                $stmt = $selectStatement->execute();
                                                                $data3 = $stmt->fetch();

                                                                $selectStatement = $database->select()
                                                                    ->from('schedule_order')
                                                                    ->where('order_id','=',$order_ids[$i])
                                                                    ->where('exist','=',"0")
                                                                    ->where('tenant_id','=',$tenant_id);
                                                                $stmt = $selectStatement->execute();
                                                                $data4 = $stmt->fetch();
                                                                if($data3==null){
                                                                    $num1=1;
                                                                    break;
                                                                }
                                                                if($data3['order_status']==5){
                                                                    $num1=2;
                                                                    break;
                                                                }
                                                                if($data4!=null){
                                                                    $num1=3;
                                                                    break;
                                                                }
                                                            }
                                                            if($num1==0){
                                                                $updateStatement = $database->update($array)
                                                                    ->table('scheduling')
                                                                    ->where('tenant_id','=',$tenant_id)
                                                                    ->where('exist',"=",0)
                                                                    ->where('scheduling_id','=',$scheduling_id);
                                                                $affectedRows = $updateStatement->execute();
                                                              for($a=0;$a<$num;$a++){
                                                                  $updateStatement=$database->update(array("exist"=>"1"))
                                                                      ->table('scheduling')
                                                                      ->where('tenant_id','=',$tenant_id)
                                                                      ->where('exist',"=",0)
                                                                      ->where('scheduling_id','=',$scheduling_id);
                                                                  $affectedRows = $updateStatement->execute();
                                                              }
                                                            }else if($num1==1){
                                                                echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                            }else if($num1==2){
                                                                echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                            }else if($num1==3){
                                                                echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                            }
                                                        }else{
                                                            echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                        }

//                                                    if($data3!=null){
//                                                        $scheduling_id=count($data3)+100000001;
//                                                    }else{
//                                                        $scheduling_id=100000001;
//                                                    }
//                                                    $now=date("Y-m-d H:i:s");
//                                                    $array['scheduleing_datetime']=$now;
//                                                    $array['tenant_id']=$tenant_id;
//                                                    $array['scheduling_id']=$scheduling_id;
//                                                    $array['exist']=0;
//                                                    $insertStatement = $database->insert(array_keys($array))
//                                                        ->into('scheduling')
//                                                        ->values(array_values($array));
//                                                    $insertId = $insertStatement->execute(false);
//                                                    $num=count($order_ids);
//                                                    for($i=0;$i<$num;$i++){
//                                                        $insertStatement = $database->insert(array('schedule_id','order_id','tenant_id','exist'))
//                                                            ->into('schedule_order')
//                                                            ->values(array($scheduling_id,$order_ids[$i],$tenant_id,'0'));
//                                                        $insertId = $insertStatement->execute(false);
//                                                    }
//                                                    echo json_encode(array("result"=>"0","desc"=>"success"));
                                        }else{
                                                    echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                                }
                                            }else{
                                                echo json_encode(array("result"=>"0","desc"=>"车辆信息不存在"));
                                            }
                                        }else{
                                            echo json_encode(array("result"=>"0","desc"=>"收货人信息不存在"));
                                        }

                                    }else{
                                        echo json_encode(array("result"=>"0","desc"=>"缺少订单信息"));
                                    }

                        }else{
                            echo json_encode(array('result'=>'2','desc'=>'缺少中转信息'));
                        }
                    }else{
                        echo json_encode(array('result'=>'3','desc'=>'缺少车辆信息'));
                    }
                }else{
                    echo json_encode(array('result'=>'5','desc'=>'缺少收货人城市'));
                }
            }else{
                echo json_encode(array('result'=>'6','desc'=>'缺少收货人信息'));
            }
        }else{
            echo json_encode(array('result'=>'7','desc'=>'缺少发货人城市'));
        }
    }else{
        echo json_encode(array('result'=>'8','desc'=>'缺少租户id'));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>