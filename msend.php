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
            $msg = '【'.$title.'】{$var},您好！,您托运的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。微信搜索'
                .$title.'并关注微信公众号查询运单轨迹或拨打电话'.$phone1.'查询运单详情';
        }else if($type==1){
            $msg = '【'.$title.'】{$var},您好！,您即将签收的运单号为'.$orderid.'的货物已从'.$address1.'发往'.$address2.'。微信搜索'
                .$title.'并关注微信公众号查询运单轨迹或拨打电话'.$phone1.'查询运单详情';
         }else if($type==2){
            $msg = '【'.$title.'】{$var},您好！,您即将签收的运单号为'.$orderid.'的货物已到达'.$address1.'中转。';
        }else if($type==3){
            $msg = '【'.$title.'】{$var},您好！,您即将签收的运单号为'.$orderid.'的货物已到达'.$address1.'的'.$title.',请及时验收或拨打'.$phone1.'修改提货方式';
        }
        $params = $phone.','.$name;
        $result = $clapi->sendVariableSMS($msg, $params);
        if(!is_null(json_decode($result))){
            $output=json_decode($result,true);
            if(isset($output['code'])  && $output['code']=='0'){
                echo json_encode(array("result" => "1", "desc" => '发送成功'));
            }else{
             echo  json_encode(array("result" => "1", "desc" => $output['errorMsg']));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "发送失败"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少电话号码"));
    }
});




$app->run();

function localhost(){
    return connect();
}
?>