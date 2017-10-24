<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 11:31
 */

require 'Slim/Slim.php';
require 'connect.php';
ini_set('date.timezone','Asia/Shanghai');
//error_reporting(E_ERROR);

require_once "../lib/WxPay.Api.php";
require_once "WxPay.NativePay.php";
require_once 'log.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $notify = new NativePay();

    $input = new WxPayUnifiedOrder();
    $input->SetBody("test");
    $input->SetAttach("test");
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee("600000");
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test");
    $input->SetNotify_url("http://paysdk.weixin.qq.com/example/notify.php");
    $input->SetTrade_type("NATIVE");
    $input->SetProduct_id("123456789");
    $result = $notify->GetPayUrl($input);
    $url2 = $result["code_url"];
    echo json_encode(array('result' => '0', 'desc' => '','lorry'=>urlencode($url2)));
});

$app->run();

function localhost(){
    return connect();
}
?>
