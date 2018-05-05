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
    $fee=$app->request->get('fee');
    $title=$app->request->get('title');
    $input->SetBody($title);
    $input->SetAttach("test2");
    date_default_timezone_set("PRC");
    $num=WxPayConfig::MCHID.date("YmdHis");
    $input->SetOut_trade_no(WxPayConfig::MCHID.date("YmdHis"));
    $input->SetTotal_fee($fee);
    $input->SetTime_start(date("YmdHis"));
    $input->SetTime_expire(date("YmdHis", time() + 600));
    $input->SetGoods_tag("test3");
    $input->SetNotify_url("http://api.uminfo.cn/weixinpay/example/notify.php");
    $input->SetTrade_type("NATIVE");
    $input->SetProduct_id("123456789");
    $result = $notify->GetPayUrl($input);
    $url2 = $result["code_url"];
    echo  json_encode(array("result"=>"1","desc"=>$num,'ticket'=>'http://api.uminfo.cn/weixinpay/example/qrcode.php?data='.urlencode($url2)));
});

$app->get('/get_agreement_html',function($app){
    return '<div style="font-size:20px;text-align:center;">甲方的权利与义务</div>
    <div>1.甲方须如实填写货物信息，严禁夹带法律禁运物品，造成后果由甲方负责。</div>
    <div>2.甲方应对所托货物按照行业标准妥善包装，使其适合运输。</div>
    <div>3.甲方应确保所提供收货人信息准确，电话通畅，如因此造成收货延误，乙方不负违约责任。乙方确认交货后，甲方如有异议须在三天内以书面方式提出，否则视同乙方运输义务完成。运输完成后，甲方应主动按约定支付运费。</div>
    <div style="font-size:20px;text-align:center;">乙方的权利与义务</div>
    <div>1.所运货物经乙方验点准确捆扎牢固后方可行驶，路途一切费用均由乙方负承担。</div>
    <div>2.在合同规定的期限内，将货物运到指定的地点，按时向收货人发出货物到达的通知，对托运的货物要负责安全，保证货物无短缺，无损坏，否则应承担由此引起的一切赔偿责任。</div>
    <div>3.乙方保证运输途中通讯畅通，如遇异常与甲方及时联系。</div>
    <div>4.货物途中如被查超载造成罚款，由乙方承担，货物途中如被查超载造成卸货费用及货物到达卸货地点前一切费用由乙方负全责，甲方不予列支。</div>
    <div>5.因发生自然灾害等不可抗力造成货物无法按期运达目的地时，乙方应将情况及时通知甲方并取得相关证明，以便甲方与客户协调；非因自然灾害等不可抗力造成货物无法按时到达，乙方须在最短时间内运至甲方指定的收货地点并交给收货人，且赔偿逾期承运给甲方造成的全部经济损失。
    本合同不得无故违约，如有法律纠纷，在甲方所在地人民法院受理。</div>
    </div><div class="pop_close">关闭</div>';
});



$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>