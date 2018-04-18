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
    $tenant_id=$app->request->headers->get("tenant-id");
    $database=localhost();
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id', '=', $tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('customer')
        ->where('tenant_id', '=', $tenant_id)
        ->where('customer_id', '=', $data1['contact_id']);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
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
//    $emailaddress=$body->sendtoemail;//收件邮箱地址
//    $sendname=$body->sendname;//收件人称呼
    $scity=$body->scity;
    $ecity=$body->ecity;
    $lorry_id=$body->lorry_id;
    $stime=$body->stime;
    $count=$body->count;
    $weight=$body->weight;
    $value=$body->value;
    $schedulings=$body->schedulings;
    $price=$body->price;
    $cost=$body->cost;
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id', '=', $tenant_id)
        ->where('lorry_id','=',$lorry_id);
    $stmt = $selectStatement->execute();
    $data3= $stmt->fetch();
    $selectStatement = $database->select()
        ->from('app_lorry')
        ->leftJoin('lorry_type','app_lorry.type','=','lorry_type.lorry_type_id')
        ->where('plate_number', '=', $data3['plate_number'])
        ->where('name', '=', $data3['driver_name'])
        ->where('phone', '=',$data3['driver_phone']);
    $stmt = $selectStatement->execute();
    $data4 = $stmt->fetch();

//    $title=$body->title;//邮件标题
    $emailaddress='jsjjrsbx@126.com';//收件邮箱地址
    $sendname='江苏人寿保险';//收件人称呼
//    $message=$body->text;//邮件内容
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

    $message='<table cellspacing="0" cellpadding="0" style="border:1px solid #000000">'.
    '<tr style="height:30px">'.
    '<td colspan="4" style="height:40px;font:bold 17px 微软雅黑;text-align:center;border:1px solid #000000">'.$data1["company"].'承运人责任险投保明细</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">投保人：'.$data1["company"].'</td>'.
    '<td colspan="2" rowspan="2" style="font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">已付款</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">被投保人：'.$data1["company"].'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">统一社会代码证号码：'.$data1["business_l"].'</td>'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">联系电话：'.$data2["customer_phone"].'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">启运地：'.$scity.'</td>'.
    '<td colspan="2" rowspan="3" style="font:normal 15px 微软雅黑;border:1px solid #000000">运输工具：货车</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">中转地/目的地：'.$ecity.'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">车牌号/车型/吨位：'.$data3["plate_number"].'、'.$data4["lorry_type_name"].'、'.$data4["deadweight"].'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="4" style="font:normal 15px 微软雅黑;border:1px solid #000000">起运日期：'.$stime.'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">货物名称：普通货物</td>'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">件数：'.$count.'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">重量（吨）：'.$weight.'</td>'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">包装：见清单</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="4" style="font:normal 15px 微软雅黑;border:1px solid #000000">货物实际价值：'.$value.'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">保险金额：'.$price.'</td>'.
    '<td colspan="2" style="font:normal 15px 微软雅黑;border:1px solid #000000">保险费：'.$cost.'</td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td colspan="4" style="border:1px solid #000000"></td>'.
    '</tr>'.
    '<tr style="height:30px">'.
    '<td style="font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">清单号</td>'.
    '<td style="font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">件数</td>'.
    '<td style="font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">重量</td>'.
    '<td style="font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">实际价值</td>'.
    '</tr>';
//    $num=count($schedulings);
//    for($i=0;$i<$num;$i++) {
        $array1 = array();
        $nuuu='';
        foreach ($schedulings as $key => $value) {
            $array1[$key] = $value;
     $selectStatement = $database->select()
        ->sum('order_cost','zon_cost')
        ->sum('goods_weight','zon_weight')
        ->sum('goods_count','zon_count')
        ->from('schedule_order')
        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
        ->join('goods','goods.order_id','=','orders.order_id','INNER')
        ->where('schedule_order.schedule_id','=',$value.'')
        ->where('schedule_order.tenant_id', '=', $tenant_id)
        ->where('goods.tenant_id', '=',$tenant_id)
        ->where('orders.tenant_id', '=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data5= $stmt->fetch();
    $message.='<tr style="height:30px">'.
        '<th style="width:600px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">'.$value.''.'</th>'.
        '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">'.$data5["zon_count"].'</th>'.
        '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">'.$data5["zon_weight"].'</th>'.
        '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">'.$data5["zon_cost"].'</th>'.
        '</tr>';
        }

//        foreach($array1 as $value){
//
//
//        }

//    }
//    foreach($schedulings as $key=>$value){
//        $array[$key]=$value;
////        $message.='<tr style="height:30px">'.
////            '<th style="width:600px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">$value->scheduling_id</th>'.
////            '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">件数</th>'.
////            '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">重量</th>'.
////            '<th style="width:300px;font:normal 15px 微软雅黑;text-align:center;border:1px solid #000000">实际价值</th>'.
////            '</tr>';
//    }

    $message.= '</table>';

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
        echo json_encode(array("result" => "0", "desc" =>"发送成功",'num'=>$array1,'nnn'=>$nuuu));
    }else{
        echo json_encode(array("result" => "1", "desc" => "收件邮箱不能为空"));
    }
});


$app->run();
function localhost(){
    return connect();
}
?>