<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 16:44
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//司机发消息
$app->post('/addmessagelorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $city=$body->city_id;
    $text=$body->message;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            if($city!=null||$city!=""){
                  if($text!=null||$text!=""){
                      $insertStatement = $database->insert(array('send_id','type','me_text','sendtime','city_id'))
                          ->into('chathall')
                          ->values(array($lorry_id,0,$text,time(),$city));
                      $insertId = $insertStatement->execute(false);
                      echo json_encode(array("result"=>"0","desc"=>"发送成功"));
                  }else{
                      echo json_encode(array("result"=>"4","desc"=>"消息内容为空"));
                  }
            }else{
                echo json_encode(array("result"=>"2","desc"=>"未选择消息所在区域"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"司机不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"司机id不存在"));
    }
});
//根据城市获取消息
$app->get('/bycity',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $city_id = $app->request->get("city_id");
    $time1=time()-592200;
    if($city_id!=null||$city_id!=""){
        $selectStament=$database->select()
            ->from('chathall')
            ->where('city_id','=',$city_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetchAll();
        if($data1!=null){
            $deleteStatement = $database->delete()
                ->from('chathall')
                ->where('sendtime', '<=', $time1);
            $affectedRows = $deleteStatement->execute();
            $selectStament=$database->select()
                ->from('chathall')
                ->orderBy('sendtime')
                ->where('city_id','=',$city_id);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                  if($data2[$x]['type']==0){
                      $selectStament=$database->select()
                          ->from('lorry')
                          ->where('lorry_id','=',$data2[$x]['send_id']);
                      $stmt=$selectStament->execute();
                      $data3=$stmt->fetch();
                      $data2[$x]['send_name']=$data3['driver_name'];
                      $data2[$x]['send_tel']=$data3['driver_phone'];
                      $data2[$x]['send_tenant']=null;
                      $data2[$x]['send_plate']=$data3['plate_number'];
                  }else{
                      $selectStament=$database->select()
                          ->from('tenant')
                          ->where('tenant_id','=',$data2[$x]['send_id']);
                      $stmt=$selectStament->execute();
                      $data4=$stmt->fetch();
                      $selectStament=$database->select()
                          ->from('customer')
                          ->where('customer_id','=',$data4['contact_id']);
                      $stmt=$selectStament->execute();
                      $data5=$stmt->fetch();
                      $data2[$x]['send_name']=$data5['customer_name'];
                      $data2[$x]['send_tel']=$data5['customer_phone'];
                      $data2[$x]['send_tenant']=$data4['company'];
                      $data2[$x]['send_plate']=null;
                  }
                }
                echo json_encode(array("result"=>"0","desc"=>"",'message'=>$data2));
            }else{
                echo json_encode(array("result"=>"3","desc"=>"该大厅目前没有消息"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"该大厅目前没有消息"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"未选择城市"));
    }
});
//租户发消息
$app->post('/addmessagetenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $city=$body->city_id;
    $text=$body->message;
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        if($data1!=null){
            if($city!=null||$city!=""){
                if($text!=null||$text!=""){
                    $insertStatement = $database->insert(array('send_id','type','me_text','sendtime','city_id'))
                        ->into('chathall')
                        ->values(array($tenant_id,1,$text,time(),$city));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result"=>"0","desc"=>"发送成功"));
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"消息内容为空"));
                }
            }else{
                echo json_encode(array("result"=>"2","desc"=>"未选择消息所在区域"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"租户id不存在"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>


