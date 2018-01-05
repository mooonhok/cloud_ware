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
    $name=$body->name;
    $orderid=$body->orderid;
    $title=$body->tenantname;
    $phone1=$body->tenantphone;
    $type=$body->type;
    $address1=$body->fcity;
    $address2=$body->tcity;
    if($phone!=null||$phone!=""){
        if($type==0){
            $msg = '【'.$title.'】{$var}，您好！您托运的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。微信关注'
                .$title.'公众号查询运单详情';
        }else if($type==1){
            $msg = '【'.$title.'】{$var}，您好！您即将签收的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。微信关注'
                .$title.'公众号查询运单详情';
         }else if($type==2){
            $msg = '【'.$title.'】{$var}，您好！您即将签收的运单号为'.$orderid.'的货物已到达'.$address1.'中转。';
        }else if($type==3){
            $msg = '【'.$title.'】{$var}，您好！您即将签收的运单号为'.$orderid.'的货物已到达'.$address1.'的'.$title.',请及时验收或拨打'.$phone1.'修改提货方式。';
        }
        $params = $phone.','.$name;
        $result = $clapi->sendVariableSMS($msg, $params);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                echo json_encode(array("result" => "0", "desc" => '发送成功'));
            }else{
             echo  json_encode(array("result" => "3", "desc" => $output['errorMsg'].$title));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "发送失败"));
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
            ->join('scheduling','scheduling.schedule_id','=','scheduling.scheduling_id','INNER')
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
            $title=$data1[$i]["company"];
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
            $msg = '【'.$title.'】{$var}，您好！您托运的运单号为'.$orderid.'的货物已被'.$customer_name.'签收，请及时验收或拨打'.$customer_phone.'确认。';
            $phone=$data2['customer_phone'];
            $name=$data2['customer_name'];
//            $params = $phone.','.$name;
//            $result = $clapi->sendVariableSMS($msg, $params);
//            if(!is_null(json_decode($result))){
//                $output=json_decode($result,true);
//                if(isset($output['code'])  && $output['code']=='0'){
//                    echo json_encode(array("result" => "0", "desc" => '发送成功'));
//                }else{
//                    echo  json_encode(array("result" => "3", "desc" => $output['errorMsg'].$title));
//                }
//            }else{
//                echo json_encode(array("result" => "2", "desc" => "发送失败"));
//            }
        }
        echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少清单号"));
        }
});



$app->run();

function localhost(){
    return connect();
}
?>