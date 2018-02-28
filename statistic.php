<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/28
 * Time: 9:48
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/getStatistic0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('customer_id');
    if($tenant_id!=null||$tenant_id!=""){
        if($customer_id!=null||$customer_id!=""){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('sender_id','=',$customer_id)
                ->where('tenant_id','=',$tenant_id)
                ->where('order_status','>',0)
                ->where('order_status','!=',6)
                ->orderBy('order_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('order_id','=',$data[$x]['order_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['goods']=$data2;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['sender_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$x]['fcity']=$data4['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['receiver_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data5['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data[$x]['tcity']=$data7['name'];
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});


$app->get('/getStatistic1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('customer_id');
    $time1=$app->request->get('begintime');
    $time2=$app->request->get('endtime');
    date_default_timezone_set("PRC");
    $time3=date("Y-m-d H:i:s",strtotime($time2)+86400);
    if($tenant_id!=null||$tenant_id!=""){
        if($customer_id!=null||$customer_id!=""){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('sender_id','=',$customer_id)
                ->where('order_datetime1','>',$time1)
                ->where('order_datetime1','<',$time3)
                ->where('tenant_id','=',$tenant_id)
                ->where('order_status','>',0)
                ->where('order_status','!=',6)
                ->orderBy('order_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('order_id','=',$data[$x]['order_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['goods']=$data2;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['sender_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$x]['fcity']=$data4['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['receiver_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data5['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data[$x]['tcity']=$data7['name'];
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});











$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>
