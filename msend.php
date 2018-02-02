<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/4
 * Time: 10:02
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
require_once 'ChuanglanSmsHelper/ChuanglanSmsApi.php';
$clapi  = new ChuanglanSmsApi();
//短信发送方法
$app->post('/sendm',function()use($app,$clapi){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $phone=$body->tel;
    if($phone!=null||$phone!=""){
        $code = mt_rand(100000,999999);
        $result = $clapi->sendSMS($phone, '【江苏酉铭】您好，您的验证码是'. $code);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                echo json_encode(array("result" => "0", "desc" =>"短信发送成功","code"=>$code)) ;
            }else{
                echo json_encode(array("result" => "3", "desc" => $output['errorMsg']));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "短信缺少内容"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少电话号码"));
    }
});
//短信
$app->post("/sendtwo",function()use($app,$clapi){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $phone=$body->phone;
    $orderid=$body->order_id;
    $type=$body->type;
    $address1=$body->fcity;
    $address2=$body->tcity;
    $tenantid=$body->tenant_id;
    $database=localhost();
    date_default_timezone_set("PRC");
    $time = date("Y-m-d H:i",time());
    if($phone!=null||$phone!=""){
      if($tenantid!=null||$tenantid!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('tenant_id', '=', $tenantid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=0){
            if($data['note_remain']>=1){
                $title=$data['jcompany'];
                if($orderid!=null||$orderid!=""){
                                $selectStatement = $database->select()
                                    ->from('customer')
                                    ->where('customer_id','=',$data['contact_id'])
                                    ->where('tenant_id', '=', $tenantid);
                                $stmt = $selectStatement->execute();
                                $data2 = $stmt->fetch();
                                $phone1=$data2['customer_phone'];
                                if($type==0){
                                    $msg = '【'.$title.'】{$var}！您托运的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。关注'.$title.'公众号查询详情。';
                                }else if($type==1){
                                    $msg = '【'.$title.'】{$var}！您即将签收的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。关注'.$title.'公众号查询详情。';
                                 }else if($type==3){
                                    $msg = '【'.$title.'】{$var}！您的运单号为'.$orderid.'的货物已到达'.$address1.'。请联系电话'.$phone1.'确认';
                                }
                                 $params = $phone.',您好';
                                 $result = $clapi->sendVariableSMS($msg, $params);
                                    if(!is_null(json_decode($result))){
                                        $output=json_decode($result,true);
                                        if(isset($output['code'])  && $output['code']=='0'){
                                            $arrays1['note_remain']=(int)$data['note_remain']-1;
                                            $updateStatement = $database->update($arrays1)
                                                ->table('tenant')
                                                ->where('tenant_id', '=', $tenantid);
                                            $affectedRows = $updateStatement->execute();
                                            $insertStatement = $database->insert(array('tenant_id','order_id','fcity','tcity','phone','type','exist','time'))
                                                ->into('note')
                                                ->values(array($tenantid,$orderid,$address1,$address2,$phone,$type,0,$time));
                                            $insertId = $insertStatement->execute(false);
                                            echo json_encode(array("result" => "0", "desc" => '发送成功'));
                                        }else{
                                            $insertStatement = $database->insert(array('tenant_id','order_id','fcity','tcity','phone','type','exist','time','error_desc'))
                                                ->into('note')
                                                ->values(array($tenantid,$orderid,$address1,$address2,$phone,$type,0,$time,$output['errorMsg']));
                                            $insertId = $insertStatement->execute(false);
                                         echo  json_encode(array("result" => "9", "desc" => $output['errorMsg'].$title));
                                        }
                                    }else{
                                        echo json_encode(array("result" => "8", "desc" => "发送失败"));
                                    }
                }else{
                    echo json_encode(array("result" => "5", "desc" => "缺少运单的id"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "您账户上没有短信条数了，请充值"));
            }
        }else{
            echo json_encode(array("result" => "3", "desc" => "租户不存在"));
        }
      }else{
          echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
      }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少电话号码"));
    }
});

//到达单子短信通知货主
$app->post("/schedules_sign",function()use($app,$clapi){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $scheduling_id=$body->scheduling_id;
    if($scheduling_id!=null||$scheduling_id!=""){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('schedule_order.schedule_id','=',$scheduling_id)
            ->where('schedule_order.exist','=',0);
        $stmt = $selectStatement->execute();
        $dataa= $stmt->fetch();
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->join('scheduling','schedule_order.schedule_id','=','scheduling.scheduling_id','INNER')
            ->join('tenant','tenant.tenant_id','=','schedule_order.tenant_id','INNER')
            ->join('customer','customer.customer_id','=','scheduling.receiver_id','INNER')
            ->where('customer.tenant_id','=',$dataa['tenant_id'])
            ->where('scheduling.tenant_id','=',$dataa['tenant_id'])
            ->where('schedule_order.tenant_id','=',$dataa['tenant_id'])
            ->where('schedule_order.schedule_id','=',$scheduling_id)
            ->where('schedule_order.exist','=',0)
            ->where('scheduling.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
          $title=$data1[$i]["jcompany"];
//            $title='靖江万事鑫';
            $orderid=$data1[$i]["order_id"];
            $customer_name=$data1[$i]["customer_name"];
            $customer_phone=$data1[$i]["customer_phone"];
            $selectStatement = $database->select()
                ->from('orders')
                ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                ->where('orders.order_id','=',$data1[$i]['order_id'])
                ->where('orders.exist','=',0)
                ->where('customer.tenant_id','=',$data1[$i]['tenant_id'])
                ->where('orders.tenant_id','=',$data1[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data1[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data1[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $msg = '【'.$title.'】{$var}！您托运的运单号为'.$orderid.'的货物已被'.$customer_name.'签收，联系电话：'.$customer_phone;
            $phone=$data2['customer_phone'];
//            $name=$data2['customer_name'];
            $params = $phone.',您好';
            if($data1[$i]['note_remain']>=1){
            $result = $clapi->sendVariableSMS($msg, $params);

            if(!is_null(json_decode($result))){
                $output=json_decode($result,true);
                if(isset($output['code'])  && $output['code']=='0'){
                    $arrays1['note_remain']=(int)$data1[$i]['note_remain']-1;
                    $updateStatement = $database->update($arrays1)
                        ->table('tenant')
                        ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                    $affectedRows = $updateStatement->execute();
                    date_default_timezone_set("PRC");
                    $insertStatement = $database->insert(array('tenant_id','order_id','fcity','tcity','phone','type','exist','time'))
                        ->into('note')
                        ->values(array($data1[$i]['tenant_id'],$orderid,$data3['name'],$data4['name'],$phone,4,0,date("Y-m-d H:i",time())));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result" => "0", "desc" => '发送成功'));
                }else{
                    $insertStatement = $database->insert(array('tenant_id','order_id','fcity','tcity','phone','type','exist','time','error_desc'))
                        ->into('note')
                        ->values(array($data1[$i]['tenant_id'],$orderid,$data3['name'],$data4['name'],$phone,4,0,date("Y-m-d H:i",time()),$output['errorMsg']));
                    $insertId = $insertStatement->execute(false);
                    echo  json_encode(array("result" => "3", "desc" => $output['errorMsg'].$title));
                }
            }else{
                echo json_encode(array("result" => "2", "desc" => "发送失败"));
            }
            }else{
                echo json_encode(array("result" => "5", "desc" => '请充值'));
            }
        }
        echo json_encode(array("result" => "4", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少清单号"));
    }
});
$app->run();

function localhost(){
    return connect();
}
?>