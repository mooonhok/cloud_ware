<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/12
 * Time: 15:28
 */
require "weixinpay/lib/WxPay.Api.php";
require "weixinpay/example/WxPay.NativePay.php";
require 'weixinpay/example/log.php';
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$notify = new NativePay();
$input = new WxPayUnifiedOrder();

$app->get('/gettickets',function()use($app,$notify,$input){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');

    $input->SetBody("test");
    $input->SetAttach("test");
    $num=WxPayConfig::MCHID.date("YmdHis");
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee("1");
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
    $input->SetNotify_url("http://api.uminfo.cn/weixinpay/example/notify.php");
    $input->SetTrade_type("NATIVE");
    $input->SetProduct_id("123456789");
    $result = $notify->GetPayUrl($input);
    $url2 = $result["code_url"];
    echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>''));
});





$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>