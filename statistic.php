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

$app->get('/getStatistic2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $lorry_id=$app->request->get('lorry_id');
    if($tenant_id!=null||$tenant_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('secondparty_id','=',$lorry_id)
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_status','!=',1)
                ->orderBy('agreement_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $count=0;
            $count1=0;
            $count2=0;
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('agreement_schedule')
                    ->where('agreement_id','=',$data[$x]['agreement_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
               for($j=0;$j<count($data2);$j++){
                   $selectStatement = $database->select()
                       ->from('schedule_order')
                       ->where('schedule_id','=',$data2[$j]['scheduling_id'])
                       ->where('tenant_id','=',$tenant_id);
                   $stmt = $selectStatement->execute();
                   $data3 = $stmt->fetchAll();
                   for($y=0;$y<count($data3);$y++){
                       $selectStatement = $database->select()
                           ->from('goods')
                           ->where('order_id','=',$data3[$y]['order_id'])
                           ->where('tenant_id','=',$tenant_id);
                       $stmt = $selectStatement->execute();
                       $data4 = $stmt->fetch();
                       $count+=$data4['goods_count'];//总件数
                       $count1+=$data4['goods_weight'];//总吨数
                       $selectStatement = $database->select()
                           ->from('orders')
                           ->where('order_id','=',$data3[$y]['order_id'])
                           ->where('tenant_id','=',$tenant_id);
                       $stmt = $selectStatement->execute();
                       $data5= $stmt->fetch();
                       $count2+=$data5['order_cost'];
                   }
               }
                $data[$x]['weight']=$count1;
                $data[$x]['count']=$count;
                $data[$x]['cost']=$count2;
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","agreement"=>$data));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});


$app->get('/getStatistic3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $lorry_id=$app->request->get('lorry_id');
    $time1=$app->request->get('begintime');
    $time2=$app->request->get('endtime');
    date_default_timezone_set("PRC");
    $time3=date("Y-m-d H:i:s",strtotime($time2)+86400);
    if($tenant_id!=null||$tenant_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('secondparty_id','=',$lorry_id)
                ->where('agreement_time','>',$time1)
                ->where('agreement_time','<',$time3)
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_status','!=',1)
                ->orderBy('agreement_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $count=0;
            $count1=0;
            $count2=0;
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('agreement_schedule')
                    ->where('agreement_id','=',$data[$x]['agreement_id'])
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                for($j=0;$j<count($data2);$j++){
                    $selectStatement = $database->select()
                        ->from('schedule_order')
                        ->where('schedule_id','=',$data2[$j]['scheduling_id'])
                        ->where('tenant_id','=',$tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetchAll();
                    for($y=0;$y<count($data3);$y++){
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data3[$y]['order_id'])
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $count+=$data4['goods_count'];//总件数
                        $count1+=$data4['goods_weight'];//总吨数
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data3[$y]['order_id'])
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $count2+=$data5['order_cost'];
                    }
                }
                $data[$x]['weight']=$count1;
                $data[$x]['count']=$count;
                $data[$x]['cost']=$count2;
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","agreement"=>$data));
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
