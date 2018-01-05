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
    $orderid=$body->order_id;
    $type=$body->type;
    $address1=$body->fcity;
    $address2=$body->tcity;
    $tenantid=$body->tenant_id;
    $database=localhost();
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
                    if($name!=null||$name!=""){


                                $selectStatement = $database->select()
                                    ->from('customer')
                                    ->where('customer_id','=',$data['contact_id'])
                                    ->where('tenant_id', '=', $tenantid);
                                $stmt = $selectStatement->execute();
                                $data2 = $stmt->fetch();
                                $phone1=$data2['customer_phone'];
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
                                }else if($type==4){
                                    $msg = '【'.$title.'】{$var}，您好！您的运单号为'.$orderid.'的货物已经签收';
                                }
                                 $params = $phone.','.$name;
                                 $result = $clapi->sendVariableSMS($msg, $params);
                                    if(!is_null(json_decode($result))){
                                        $output=json_decode($result,true);
                                        if(isset($output['code'])  && $output['code']=='0'){
                                            $arrays1['note_remain']=(int)$data['note_remain']-1;
                                            $updateStatement = $database->update($arrays1)
                                                ->table('tenant')
                                                ->where('tenant_id', '=', $tenantid);
                                            $affectedRows = $updateStatement->execute();
                                            $insertStatement = $database->insert(array('tenant_id','order_id','fcity','tcity','phone','name','type','exist'))
                                                ->into('note')
                                                ->values(array($tenantid,$orderid,$address1,$address2,$phone,$name,$type,0));
                                            $insertId = $insertStatement->execute(false);
                                            echo json_encode(array("result" => "0", "desc" => '发送成功'));
                                        }else{
                                         echo  json_encode(array("result" => "9", "desc" => $output['errorMsg'].$title));
                                        }
                                    }else{
                                        echo json_encode(array("result" => "8", "desc" => "发送失败"));
                                    }

                    }else{
                        echo json_encode(array("result" => "6", "desc" => "缺少短信接收人姓名"));
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




$app->run();

function localhost(){
    return connect();
}
?>