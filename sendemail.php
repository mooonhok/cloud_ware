<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/22
 * Time: 9:08
 */

require 'Slim/Slim.php';
require 'connect.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './email/Exception.php';
require './email/PHPMailer.php';
require './email/SMTP.php';


$mail = new PHPMailer(true);

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/scheduling',function()use($app,$mail){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body = $app->request->getBody();
    $body=json_decode($body);
    $mail = new PHPMailer(true);                              // Passing `true` enables exceptions
    $mail->CharSet = "utf-8"; // 设置字符集编码 utf-8
    $mail->Encoding = "base64";//设置文本编码方式
    //Server settings
    $mail->SMTPDebug = 0;                                 // Enable verbose debug output
    $mail->isSMTP();                                      // Set mailer to use SMTP
    $mail->Host = 'smtp.126.com';  // Specify main and backup SMTP servers
    $mail->SMTPAuth = true;                               // Enable SMTP authentication
    $mail->Username = 'jshongxinbx@126.com';                 // SMTP username
    $mail->Password = '7060xbxhsj';                           // SMTP password
    $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
    $mail->Port = 465;                                    // TCP port to connect to
    $emailaddress=$body->sendtoemail;//收件邮箱地址
    $sendname=$body->sendname;//收件人称呼
//    $title=$body->title;//邮件标题
//    $emailaddress='jsjjrsbx@126.com';//收件邮箱地址
//    $sendname='江苏人寿保险';//收件人称呼
    $message=$body->text;//邮件内容
//    $message='<table border="1" cellspacing="0" cellpadding="0" width="600px;">'.
//                            '<thead bgcolor="white" >'.
//                              '<td colspan="5">南通物流公司货运险投保明细<td>'.
//                            '</thead>'.
//                            '<tr>'.
//                                '<td>投保人:</td>'.
//                                '<td colspan="4"></td>'.
//                            '</tr>'.
//                            '<tr>'.
//                            '<td>被保险人:</td>'.
//                            '<td colspan="4"></td>'.
//                            '</tr>'.
//                            '<tr>'.
//                                '<td colspan="3">实际货主名称及统一社会代码证号码：</td>'.
//                                '<td colspan="2">联系电话：</td>'.
//                            '</tr>'.
//                            '<tr>'.
//                            '<td colspan="3">启运地/中转地：</td>'.
//                            '<td colspan="2" rowspan="3">运输工具：</td>'.
//                            '</tr>'.
//                            '<tr>'.
//                            '<td colspan="3">目的地：</td>'.
//                            '</tr>'.
//                            '<tr>'.
//                            '<td colspan="3">车牌号/车型/吨位：</td>'.
//                            '</tr>'.
//                            '<tr>'.
//                            '<td colspan="5">起运日期：</td>'.
//                            '</tr>'.
//        '<tr>'.
//        '<td colspan="5">运单号/货票号码：</td>'.
//        '</tr>'.
//        '<tr>'.
//        '<td colspan="3">货物名称：</td>'.
//        '<td colspan="2">件数：</td>'.
//        '</tr>'.
//        '<tr>'.
//        '<td colspan="3">重量（吨）：</td>'.
//        '<td colspan="2">包装：</td>'.
//        '</tr>'.
//        '<tr>'.
//        '<td colspan="5">保险金额：</td>'.
//        '</tr>'.
//                       '</table>';
    $title='保险';//邮件标题
    if($emailaddress!=null||$emailaddress!=""){
        $mail->setFrom( 'jshongxinbx@126.com','江苏宏鑫保险靖江分公司');
        $mail->addAddress($emailaddress,$sendname);               //无称呼时使用
//        $mail->addAttachment('./1.png', 'new.doc');    // 添加附件
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = "=?UTF-8?B?" . base64_encode($title) . "?=";
        $mail->Body =$message;
        $mail->AltBody = '';
        if (!$mail->send()) {
            echo json_encode(array("result" => "2", "desc" =>"发送失败",'errortext'=>$mail));
            exit;
        }
        echo json_encode(array("result" => "0", "desc" =>"发送成功"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "收件邮箱不能为空"));
    }
});


$app->run();
function localhost(){
    return connect();
}
?>