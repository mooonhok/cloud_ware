<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 10:27
 */
require 'Slim/Slim.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/wxmessage',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
    $order_id=$body->order_id;
    $from_user=$body->from_user;
    $mobilephone=$body->mobilephone;
    $title=$body->title;
    $content=$body->content;
    $array=array();
    foreach($body as $key=>$value){
         $array[$key]=$value;
    }
    if(($tenant_id!=''||$tenant_id!=null)&&($order_id!=''||$order_id!=null)&&($from_user!=''||$from_user!=null)&&($mobilephone!=''||$mobilephone!=null)&&($title!=''||$title!=null)&&($content!=''||$content!=null)){
        $array['exist']=0;
        $array['tenant_id']=$tenant_id;
        $selectStatement = $database->select()
                                    ->from('wx_message')
                                    ->where('tenant_id', '=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $messageid;
        if($data==null){
            $messageid=100000001;
        }else{
            $messageid=count($data)+100000001;
        }
        $array['message_id']=$messageid;
        $insertStatement = $database->insert(array_keys($array))
                         ->into('wx_message')
                         ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array('result'=>'0','desc'=>'success'));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全"));
    }
});

$app->get('/wxmessages',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $page=$app->request->get("page");
    $per_page=$app->request->get("per_page");
    if(($tenant_id!=''||$tenant_id!=null)){
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                             ->from('wx_message')
                             ->where('tenant_id','=',$tenant_id)
                             ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
        }else{
            $selectStatement = $database->select()
                             ->from('wx_message')
                             ->where('tenant_id','=',$tenant_id)
                             ->where('exist',"=",0)
                             ->limit((int)$per_page,(int)$per_page*(int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全","orders"=>""));
    }
});

$app->get('/wxmessage/isread',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $message_id=$app->request->get('messageid');
    if(($tenant_id!=''||$tenant_id!=null)&&($message_id!=''||$message_id!=null)){
        $selectStatement = $database->select()
                         ->from('wx_message')
                         ->where('tenant_id','=',$tenant_id)
                         ->where('message_id','=',$message_id)
                         ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            echo json_encode(array('code'=>0,'is_read'=>$data['is_read']));
        }else{
            echo json_encode(array('code'=>1,'is_read'=>''));
        }

    }else{
        echo json_encode(array('code'=>2,'is_read'=>''));
    }
});

$app->get('/wxmessage/set-read',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $message_id=$app->request->get('messageid');
    if(($tenant_id!=''||$tenant_id!=null)&&($message_id!=''||$message_id!=null)){
        $selectStatement = $database->select()
                         ->from('wx_message')
                         ->where('tenant_id','=',$tenant_id)
                         ->where('message_id','=',$message_id)
                         ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $updateStatement = $database->update(array('is_read' => 1))
                             ->table('wx_message')
                             ->where('tenant_id','=',$tenant_id)
                             ->where('message_id','=',$message_id)
                             ->where('exist',"=",0);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"不存在"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"信息不全"));
    }
});

$app->delete("/wxmessage",function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $message_id=$app->request->get('messageid');
    if(($tenant_id!=''||$tenant_id!=null)&&($message_id!=''||$message_id!=null)){
        $selectStatement = $database->select()
                         ->from('wx_message')
                         ->where('tenant_id','=',$tenant_id)
                         ->where('message_id','=',$message_id)
                         ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id','=',$data['order_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data1['order_status']==0||$data1['order_status']==5){
                $updateStatement = $database->update(array('exist' => 1))
                    ->table('wx_message')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('message_id','=',$message_id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"订单已发出"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"不存在"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"信息不全"));
    }
});

$app->run();
?>