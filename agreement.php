<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 9:10
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/agreement',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $firstparty_id=$body->firstparty_id;
    $secondparty_id=$body->secondparty_id;
    $schedule_id=$body->schedule_id;
    $freight=$body->freight;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=""||$tenant_id!=null){
        if($firstparty_id!=""||$firstparty_id!=null){
            if($secondparty_id>0||$secondparty_id!=null){
                    if ($schedule_id != "" || $schedule_id != null) {
                        if ($freight != "" || $freight != null) {
                            $selectStatement = $database->select()
                                ->from('agreement')
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetchAll();
                            if ($data == null) {
                                $agreement_id = 10000001;
                            } else {
                                $agreement_id = count($data) + 10000001;
                            }
                            $array["agreement_id"] = $agreement_id;
                            $array["tenant_id"] = $tenant_id;
                            $array["exist"] = 0;
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('exist','=','0')
                                ->where('customer_id', '=', $firstparty_id);
                            $stmt = $selectStatement->execute();
                            $data1 = $stmt->fetch();
                            if($data1!=null){
                                $selectStatement = $database->select()
                                    ->from('lorry')
                                    ->where('exist','=','0')
                                    ->where('lorry_id', '=', $secondparty_id);
                                $stmt = $selectStatement->execute();
                                $data2 = $stmt->fetch();
                                if($data2!=null){
                                    $selectStatement = $database->select()
                                        ->from('scheduling')
                                        ->where('exist','=','0')
                                        ->where('scheduling_id', '=', $schedule_id);
                                    $stmt = $selectStatement->execute();
                                    $data3 = $stmt->fetch();
                                    if($data3!=null){
                                        $insertStatement = $database->insert(array_keys($array))
                                            ->into('agreement')
                                            ->values(array_values($array));
                                        $insertId = $insertStatement->execute(false);
                                        echo json_encode(array("result" => "0", "desc" => "success"));
                                    }else{
                                        echo json_encode(array("result" => "1", "desc" => "关联调度信息不存在"));
                                    }
                                }else{
                                    echo json_encode(array("result" => "1", "desc" => "关联车辆信息不存在"));
                                }
                            }else{
                                echo json_encode(array("result" => "1", "desc" => "关联客户信息不存在"));
                            }
                        } else {
                            echo json_encode(array("result" => "1", "desc" => "缺少运费信息"));
                        }
                    } else {
                        echo json_encode(array("result" => "2", "desc" => "缺少调度信息"));
                    }
            }else{
                echo json_encode(array("result"=>"4","desc"=>"缺少车辆信息"));
            }
        }else{
            echo json_encode(array("result"=>"5","desc"=>"缺少客户信息"));
        }
    }else{
        echo json_encode(array("result"=>"6","desc"=>"缺少租户id"));
    }
});

//$app->put('/customer',function()use($app){
//    $app->response->headers->set('Content-type','application/json');
//    $tenant_id=$app->request->headers->get('tenant-id');
//    $database=localhost();
//    $body=$app->request->getBody();
//    $body=json_decode($body);
//    $firstparty_id=$body->firstparty_id;
//    $secondparty_id=$body->secondparty_id;
//    $schedule_id=$body->schedule_id;
//    $freight=$body->freight;
//    $array=array();
//    foreach($body as $key=>$value){
//        $array[$key]=$value;
//    }
//    if($tenant_id!=""||$tenant_id!=null){
//        if($firstparty_id!=""||$firstparty_id!=null){
//            if($secondparty_id>0||$secondparty_id!=null){
//                if ($schedule_id != "" || $schedule_id != null) {
//                    if ($freight != "" || $freight != null) {
//                        $selectStatement = $database->select()
//                            ->from('agreement')
//                            ->where('tenant_id', '=', $tenant_id);
//                        $stmt = $selectStatement->execute();
//                        $data = $stmt->fetchAll();
//                        if ($data == null) {
//                            $agreement_id = 10000001;
//                        } else {
//                            $agreement_id = count($data) + 10000001;
//                        }
//                        $array["agreement_id"] = $agreement_id;
//                        $array["tenant_id"] = $tenant_id;
//                        $array["exist"] = 0;
//                        $selectStatement = $database->select()
//                            ->from('customer')
//                            ->where('exist','=','0')
//                            ->where('customer_id', '=', $firstparty_id);
//                        $stmt = $selectStatement->execute();
//                        $data1 = $stmt->fetch();
//                        if($data1!=null){
//                            $selectStatement = $database->select()
//                                ->from('lorry')
//                                ->where('exist','=','0')
//                                ->where('lorry_id', '=', $secondparty_id);
//                            $stmt = $selectStatement->execute();
//                            $data2 = $stmt->fetch();
//                            if($data2!=null){
//                                $selectStatement = $database->select()
//                                    ->from('scheduling')
//                                    ->where('exist','=','0')
//                                    ->where('scheduling_id', '=', $schedule_id);
//                                $stmt = $selectStatement->execute();
//                                $data3 = $stmt->fetch();
//                                if($data3!=null){
//                                    $updateStatement = $database->update(array('exist'=>1))
//                                        ->table('agreement')
//                                        ->where('tenant_id','=',$tenant_id)
//                                        ->where('customer_id','=',$customer_id)
//                                        ->where('exist',"=",0);
//                                    $affectedRows = $updateStatement->execute();
//                                    echo json_encode(array("result" => "0", "desc" => "success"));
//                                }else{
//                                    echo json_encode(array("result" => "1", "desc" => "关联调度信息不存在"));
//                                }
//                            }else{
//                                echo json_encode(array("result" => "1", "desc" => "关联车辆信息不存在"));
//                            }
//                        }else{
//                            echo json_encode(array("result" => "1", "desc" => "关联客户信息不存在"));
//                        }
//                    } else {
//                        echo json_encode(array("result" => "1", "desc" => "缺少运费信息"));
//                    }
//                } else {
//                    echo json_encode(array("result" => "2", "desc" => "缺少调度信息"));
//                }
//            }else{
//                echo json_encode(array("result"=>"4","desc"=>"缺少车辆信息"));
//            }
//        }else{
//            echo json_encode(array("result"=>"5","desc"=>"缺少客户信息"));
//        }
//    }else{
//        echo json_encode(array("result"=>"6","desc"=>"缺少租户id"));
//    }
//});

$app->get('/agreements',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!="") {
        if ($page == null || $per_page == null) {
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $agreements = array();
            $num = count($data);
            for ($i = 0; $i < $num; ++$i) {
                $agreement = array();
                foreach ($data[$i] as $value) {
                    $agreement['agreement_id'] = $value['agreement_id'];
                    $selectStatement = $database->select()
                        ->from('lorry')
                        ->where('lorry_id', '=', $value['lorry_id'])
                        ->where('exist', "=", 0);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $agreement["plate_number"] = $data1["plate_number"];
                    $agreement["driver_name"] = $data1["driver_name"];
                    $agreement["driver_phone"] = $data1["driver_phone"];
                    $selectStatement = $database->select()
                        ->from('scheduling')
                        ->where('scheduling_id', '=', $value['schedule_id'])
                        ->where('exist', "=", 0);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $agreement["receive_city"] = $data2["receive_city"];

                }
                array_push($agreements, $agreement);
            }
            echo json_encode(array("result" => "0", "desc" => "success", "agreements" => $agreements));
        } else {
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0)
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $agreements = array();
            $num = count($data);
            for ($i = 0; $i < $num; ++$i) {
                $agreement = array();
                foreach ($data[$i] as $k => $v) {
                    if ($k == "agreement_id") {
                        $agreement['agreement_id'] = $v;
                    }
                    if ($k == "lorry_id") {
                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('lorry_id', '=', $v)
                            ->where('exist', "=", 0);
                        $stmt = $selectStatement->execute();
                        $data1 = $stmt->fetch();
                        $agreement["plate_number"] = $data1["plate_number"];
                        $agreement["driver_name"] = $data1["driver_name"];
                        $agreement["driver_phone"] = $data1["driver_phone"];
                    }
                    if ($k == "schedule_id") {
                        $selectStatement = $database->select()
                            ->from('scheduling')
                            ->where('schedule_id', '=', $v)
                            ->where('exist', "=", 0);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $agreement["receive_city"] = $data2["receive_city"];
                    }
                }
                array_push($agreements, $agreement);
            }
            echo json_encode(array("result" => "0", "desc" => "success", "agreements" => $agreements));
        }
    }else{
    echo json_encode(array("result"=>"1","desc"=>"信息不全","agreements"=>""));
}
});


$app->get("/agreement",function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $agreement_id=$app->request->get("agreementid");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($agreement_id!=null||$agreement_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_id','=',$agreement_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            $agreement=array();
            $agreement['agreement_id']=$data['agreement_id'];
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('lorry_id','=',$data['secondparty_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $lorry=array();
            $lorry['lorry_id']=$data1['lorry_id'];
            $lorry['plate_number']=$data1['plate_number'];
            $lorry['driver_name']=$data1['driver_name'];
            $lorry['driver_phone']=$data1['driver_phone'];
            $lorry['driver_identycard']=$data1['driver_identycard'];
            $lorry['driving_license']=$data1['driving_license'];
            $lorry['vehicle_travel_license']=$data1['vehicle_travel_license'];
            array_push($agreement,$lorry);
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('tenant_id','=',$tenant_id)
                ->where('schedule_id','=',$data['schedule_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id','=',$data2['order_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data3= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('goods')
                ->where('tenant_id','=',$tenant_id)
                ->where('goods_id','=',$data3['goods_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data4= $stmt->fetchAll();
            array_push($agreement,$data4);
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('tenant_id','=',$tenant_id)
                ->where('scheduling_id','=',$data['schedule_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $agreement['send_city']=$data5['send_city'];
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data5['receiver_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
            $agreement['receive_city']=$data6['customer_city'];
            $agreement['receiver_name']=$data6['customer_name'];
            $agreement['receiver_phone']=$data6['customer_phone'];
            $agreement['receiver_address']=$data6['customer_address'];
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data7= $stmt->fetch();
            $agreement['receive_company']=$data7['company'];
            echo json_encode(array("result"=>"0","desc"=>"success","agreement"=>$agreement));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"缺少客户id","agreement"=>""));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id","agreement"=>""));
    }
});




$app->delete('/agreement',function()use($app){
    $app->response->headers->set('Content-type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $agreement_id=$app->request->get('agreementid');
    if($tenant_id!=null||$tenant_id!=""){
        if($agreement_id!=null||$agreement_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_id','=',$agreement_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('schedule_id','=',$data['schedule_id'])
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if($data1==null){
                    $updateStatement = $database->update(array('exist'=>1))
                        ->table('agreement')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('agreement_id','=',$agreement_id)
                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    $num = count($data1);
                    $num1=0;
                    for($i=0;$i<$num;++$i){
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id','=',data1['order_id'])
                            ->where('exist',"=",0);
                        $stmt = $selectStatement->execute();
                        $data2= $stmt->fetch();
                        if(!($data2['order_status']==5)){
                             $num1=1;
                             break;
                        }
                    }
                    if($num1==0){
                        $updateStatement = $database->update(array('exist'=>1))
                            ->table('agreement')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('agreement_id','=',$agreement_id)
                            ->where('exist',"=",0);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result"=>"0","desc"=>"success"));
                    }else{
                        echo json_encode(array("result"=>"1","desc"=>"有订单未完结"));
                    }
                }

            }else{
                echo json_encode(array("result"=>"2","desc"=>"合同不存在"));
            }
        }else{
            echo json_encode(array("result"=>"3",'desc'=>'缺少合同id'));
        }
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>