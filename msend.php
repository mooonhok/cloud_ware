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

$app->post("/sendtwo",function()use($app,$clapi){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $phone=$body->phone;
    $phone2=$body->phone1;
    $name=$body->name;
    $name2=$body->name2;
    $title=$body->tenantname;
    $order_id=$body->orderid;
    $message=$body->type;
    if($phone!=null||$phone!=""){
        if($phone2!=null||$phone2!=""){
            if($message==1){
                $msg = '【'.$title.'】{$var},你好！,您的运单{$var}已经从'.$title.'出发';
            }else if($message==2){
                $msg = '【'.$title.'】{$var},你好！,您的运单{$var}已经被'.$title.'签收';
            }else if($message==3){
                $msg = '【'.$title.'】{$var},你好！,您的运单{$var}在'.$title.'中转';
            }
            $params = $phone.','.$name.','.$order_id.';'.$phone2.','.$name2.','.$order_id;
            $result = $clapi->sendVariableSMS($msg, $params);
            if(!is_null(json_decode($result))){
                $output=json_decode($result,true);
                if(isset($output['code'])  && $output['code']=='0'){
                    echo '短信发送成功！' ;
                }else{
                    echo $output['errorMsg'];
                }
            }else{
                echo $result;
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少电话号码"));
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