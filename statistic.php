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
            $num1=0;
            $num2=0;
            $num3=0;
            $num4=0;
            for($x=0;$x<count($data);$x++){
                if($data[$x]['pay_method']==0){
                    $num1=$num1+1;
                }else if($data[$x]['pay_method']==1){
                    $num2=$num2+1;
                }else if($data[$x]['pay_method']==2){
                    $num3=$num3+1;
                }else if($data[$x]['pay_method']==3){
                    $num4=$num4+1;
                }
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
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data,'count1'=>$num1,'count2'=>$num2,'count3'=>$num3,'count4'=>$num4));
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
    $time3=date("Y-m-d H:i:s",strtotime($time2)+86401);
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
            $num1=0;
            $num2=0;
            $num3=0;
            $num4=0;
            for($x=0;$x<count($data);$x++){
                if($data[$x]['pay_method']==0){
                    $num1=$num1+1;
                }else if($data[$x]['pay_method']==1){
                    $num2=$num2+1;
                }else if($data[$x]['pay_method']==2){
                    $num3=$num3+1;
                }else if($data[$x]['pay_method']==3){
                    $num4=$num4+1;
                }
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
            echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data,'count1'=>$num1,'count2'=>$num2,'count3'=>$num3,'count4'=>$num4));
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
    $array=array();
    $array2=array();
    $array3=array();
    $num111=0;
    $num222=0;
    if($tenant_id!=null||$tenant_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('secondparty_id','=',$lorry_id)
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_status','!=',2)
                ->orderBy('agreement_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $count=0;
                $count1=0;
                $count2=0;
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
//                       $count=bcadd($left=$count, $right=$data4['goods_count'], 3);
//                       $count1=bcadd($left=$count1, $right=$data4['goods_weight'], 3);
                       $count+=$data4['goods_count'];//总件数
                       $count1+=$data4['goods_weight'];//总吨数
                       $sss=(explode('.',$data4['goods_weight']));
                       if(count($sss)>1){
                           if($num111<strlen($sss[1])){
                               $num111=strlen((explode('.',$data4['goods_weight']))[1]);
                           };
                       }

                       $selectStatement = $database->select()
                           ->from('orders')
                           ->where('order_id','=',$data3[$y]['order_id'])
                           ->where('tenant_id','=',$tenant_id);
                       $stmt = $selectStatement->execute();
                       $data5= $stmt->fetch();
//                       $count2=bcadd($left=$count2, $right=$data5['order_cost'], 3);

                       $count2+=$data5['order_cost'];
                       $sss=(explode('.',$data5['order_cost']));
                       if(count($sss)>1) {
                           if ($num222 < strlen($sss[1])) {
                               $num222 = strlen((explode('.', $data5['order_cost']))[1]);
                           };
                       }
                   }
               }
                $data[$x]['weight']=sprintf("%.".$num111."f",$count1);
//                $arr=explode('.',$data[$x]['weight']);
//                if(substr($arr[1],2,1)){
//                    $data[$x]['weight']=$data[$x]['weight'];
//                }else if(substr($arr[1],1,1)){
//                    $data[$x]['weight']=sprintf("%.2f",$count1);
//                }else if(substr($arr[1],0,1)){
//                    $data[$x]['weight']=sprintf("%.1f",$count1);
//                }else{
//                    $data[$x]['weight']=sprintf("%.0f",$count1);
//                }
                $data[$x]['count']=$count;
                $data[$x]['cost']=sprintf("%.".$num222."f",$count2);
//                $arr=explode('.',$data[$x]['cost']);
//                if(substr($arr[1],2,1)){
//                    $data[$x]['cost']=$data[$x]['cost'];
//                }else if(substr($arr[1],1,1)){
//                    $data[$x]['cost']=sprintf("%.2f",$count2);
//                }else if(substr($arr[1],0,1)){
//                    $data[$x]['cost']=sprintf("%.1f",$count2);
//                }else{
//                    $data[$x]['cost']=sprintf("%.0f",$count2);
//                }
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
//    $time3=date("Y-m-d H:i:s",strtotime($time2)+86400);
    $array1=array();
    $num111=0;
    $num222=0;
    if($tenant_id!=null||$tenant_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('secondparty_id','=',$lorry_id)
//                ->where('agreement_time','>',$time1)
//                ->where('agreement_time','<',$time3)
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_status','!=',2)
                ->orderBy('agreement_id');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $count=0;
                $count1=0;
                $count2=0;
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
                        $sss=(explode('.',$data4['goods_weight']));
                        if(count($sss)>1){
                            if($num111<strlen($sss[1])){
                                $num111=strlen((explode('.',$data4['goods_weight']))[1]);
                            };
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data3[$y]['order_id'])
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        $count2+=$data5['order_cost'];
                        $sss=(explode('.',$data5['order_cost']));
                        if(count($sss)>1) {
                            if ($num222 < strlen($sss[1])) {
                                $num222 = strlen((explode('.', $data5['order_cost']))[1]);
                            };
                        }
                    }
                }
                $data[$x]['weight']=sprintf("%.".$num111."f",$count1);

//                $arr=explode('.',$data[$x]['weight']);
//                if(substr($arr[1],2,1)){
//                    $data[$x]['weight']=$data[$x]['weight'];
//                }else if(substr($arr[1],1,1)){
//                    $data[$x]['weight']=sprintf("%.2f",$count1);
//                }else if(substr($arr[1],0,1)){
//                    $data[$x]['weight']=sprintf("%.1f",$count1);
//                }else{
//                    $data[$x]['weight']=sprintf("%.0f",$count1);
//                }
                $data[$x]['count']=$count;
                $data[$x]['cost']=sprintf("%.".$num222."f",$count2);
//                $arr=explode('.',$data[$x]['cost']);
//                if(substr($arr[1],2,1)){
//                    $data[$x]['cost']=$data[$x]['cost'];
//                }else if(substr($arr[1],1,1)){
//                    $data[$x]['cost']=sprintf("%.2f",$count2);
//                }else if(substr($arr[1],0,1)){
//                    $data[$x]['cost']=sprintf("%.1f",$count2);
//                }else{
//                    $data[$x]['cost']=sprintf("%.0f",$count2);
//                }
                $arr = date_parse_from_format ( "Y年m月d日" ,$data[$x]['agreement_time']);
                $timestamp = mktime(0,0,0,$arr['month'],$arr['day'],$arr['year']);
                if($timestamp<(strtotime($time2)+86400)){
                    if($timestamp>=strtotime($time1)){
                        array_push($array1,$data[$x]);
                    }
                }
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","agreement"=>$array1));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});

$app->get('/getStatistic4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('tenant-id');
    $tenant_num=$app->request->get('tenant_num');
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=""){
        if($tenant_id!=null||$tenant_id!=""){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('contact_tenant_id','=',$customer_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('receiver_id','=',$data[$x]['customer_id'])
                    ->whereNotIn('scheduling_status',array(6,7,8,9))
                    ->where('is_scan','=',1)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                for($j=0;$j<count($data2);$j++){
                    $count=0;
                    $count1=0;
                    $count2=0;
                    $count3=0;
                    $count4=0;
                    $selectStatement = $database->select()
                        ->from('schedule_order')
                        ->where('schedule_id','=',$data2[$j]['scheduling_id'])
                        ->where('tenant_id','=',$tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetchAll();
                    for($y=0;$y<count($data3);$y++){
                        $next_cost=0;
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
                        if($data5['pay_method']==1){
                            if($data5['collect_cost']!=null||$data5['collect_cost']!=0){
                                $count2+=$data5['collect_cost'];
                            }else{
                                $count2+=$data5['order_cost'];
                            }
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data3[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data3[$y]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data3[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        if(substr($data3[$y]['order_id'],0,7)==$tenant_num&&$is_transfer==1){
                            $count3+=$data11[0]['transfer_cost'];
                        }
                        if($next_cost!=''||$next_cost!=null){
                           $count4+=$next_cost;
                        }
                    }
                    $data2[$j]['weight']=$count1;
                    $data2[$j]['count']=$count;
                    $data2[$j]['d_cost']=$count2;
                    $data2[$j]['transfer_cost']=$count3;
                    $data2[$j]['next_cost']=$count4;
                    array_push($arrays1,$data2[$j]);
                }
            }


            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$customer_id)
                ->where('contact_tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data100 = $stmt->fetchAll();
            for($x=0;$x<count($data100);$x++){
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('receiver_id','=',$data100[$x]['customer_id'])
                    ->whereNotIn('scheduling_status',array(6,7,8,9))
                    ->where('is_scan','=',1)
                    ->where('tenant_id','=',$customer_id);
                $stmt = $selectStatement->execute();
                $data200 = $stmt->fetchAll();
                for($j=0;$j<count($data200);$j++){
                    $count=0;
                    $count1=0;
                    $count2=0;
                    $count3=0;
                    $count4=0;
                    $selectStatement = $database->select()
                        ->from('schedule_order')
                        ->where('schedule_id','=',$data200[$j]['scheduling_id'])
                        ->where('tenant_id','=',$customer_id);
                    $stmt = $selectStatement->execute();
                    $data300 = $stmt->fetchAll();
                    for($y=0;$y<count($data300);$y++){
                        $next_cost=0;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data300[$y]['order_id'])
                            ->where('tenant_id','=',$customer_id);
                        $stmt = $selectStatement->execute();
                        $data400= $stmt->fetch();
                        $count+=$data400['goods_count'];//总件数
                        $count1+=$data400['goods_weight'];//总吨数
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data300[$y]['order_id'])
                            ->where('tenant_id','=',$customer_id);
                        $stmt = $selectStatement->execute();
                        $data500= $stmt->fetch();
                        if($data500['pay_method']==1){

                            if($data500['collect_cost']!=null||$data500['collect_cost']!=0){
                                $count2+=$data500['collect_cost'];
                            }else{
                                $count2+=$data500['order_cost'];
                            }
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$customer_id)
                            ->where('order_id', '=', $data300[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data1000 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data1000['id'])
                            ->where('order_id', '=', $data300[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data1100 = $stmt->fetchAll();
                        $is_transfer=null;
                        if($data500!=null){
                            $is_transfer=$data500['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data300[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data1400 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data1400['id'])
                            ->where('order_id', '=', $data300[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data1200 = $stmt->fetchAll();
                        if($data1200!=null){
                            $next_cost=$data1200[0]['transfer_cost'];
                        }
                        if(substr($data300[$y]['order_id'],0,7)!=$tenant_num&&$is_transfer==1){
                            if($data1100!=null){
                            $count3+=$data1100[0]['transfer_cost'];
                            }
                        }
                        if($next_cost!=''||$next_cost!=null){
                            $count4+=$next_cost;
                        }
                    }
                    $data200[$j]['weight']=$count1;
                    $data200[$j]['count']=$count;
                    $data200[$j]['d_cost']=$count2;
                    $data200[$j]['transfer_cost']=$count3;
                    $data200[$j]['next_cost']=$count4;
                    array_push($arrays1,$data200[$j]);
                }
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","scheduling"=>$arrays1));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});

$app->get('/getStatistic5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('tenant-id');
    $time1=$app->request->get('begintime');
    $time2=$app->request->get('endtime');
    date_default_timezone_set("PRC");
    $time3=date("Y-m-d H:i:s",strtotime($time2)+86401);
    $tenant_num=$app->request->get('tenant_num');
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=""){
        if($tenant_id!=null||$tenant_id!=""){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('contact_tenant_id','=',$customer_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('receiver_id','=',$data[$x]['customer_id'])
                    ->where('scheduling_datetime','>',$time1)
                    ->where('scheduling_datetime','<',$time3)
                    ->whereNotIn('scheduling_status',array(6,7,8,9))
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                for($j=0;$j<count($data2);$j++){
                    $count=0;
                    $count1=0;
                    $count2=0;
                    $count3=0;
                    $count4=0;
                    $selectStatement = $database->select()
                        ->from('schedule_order')
                        ->where('schedule_id','=',$data2[$j]['scheduling_id'])
                        ->where('tenant_id','=',$tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetchAll();
                    for($y=0;$y<count($data3);$y++){
                        $next_cost=0;
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
                        if($data5['pay_method']==1){
                            if($data5['collect_cost']!=null||$data5['collect_cost']!=0){
                                $count2+=$data5['collect_cost'];
                            }else{
                                $count2+=$data5['order_cost'];
                            }
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data3[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data3[$y]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data3[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        if(substr($data3[$y]['order_id'],0,7)==$tenant_num&&$is_transfer==1){
                            $count3+=$data5['transfer_cost'];
                        }
                        if($next_cost!=''||$next_cost!=null){
                            $count4+=$next_cost;
                        }
                    }
                    $data2[$j]['weight']=$count1;
                    $data2[$j]['count']=$count;
                    $data2[$j]['d_cost']=$count2;
                    $data2[$j]['transfer_cost']=$count3;
                    $data2[$j]['next_cost']=$count4;
                    array_push($arrays1,$data2[$j]);
                }
            }
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$customer_id)
                ->where('contact_tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data100 = $stmt->fetchAll();
            for($x=0;$x<count($data100);$x++){
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('receiver_id','=',$data100[$x]['customer_id'])
                    ->whereNotIn('scheduling_status',array(6,7,8,9))
                    ->where('scheduling_datetime','>',$time1)
                    ->where('scheduling_datetime','<',$time3)
                    ->where('is_scan','=',1)
                    ->where('tenant_id','=',$customer_id);
                $stmt = $selectStatement->execute();
                $data200 = $stmt->fetchAll();
                for($j=0;$j<count($data200);$j++){
                    $count=0;
                    $count1=0;
                    $count2=0;
                    $count3=0;
                    $count4=0;
                    $selectStatement = $database->select()
                        ->from('schedule_order')
                        ->where('schedule_id','=',$data200[$j]['scheduling_id'])
                        ->where('tenant_id','=',$customer_id);
                    $stmt = $selectStatement->execute();
                    $data300 = $stmt->fetchAll();
                    for($y=0;$y<count($data300);$y++){
                        $next_cost=0;
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id','=',$data300[$y]['order_id'])
                            ->where('tenant_id','=',$customer_id);
                        $stmt = $selectStatement->execute();
                        $data400= $stmt->fetch();
                        $count+=$data400['goods_count'];//总件数
                        $count1+=$data400['goods_weight'];//总吨数
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('order_id','=',$data300[$y]['order_id'])
                            ->where('tenant_id','=',$customer_id);
                        $stmt = $selectStatement->execute();
                        $data500= $stmt->fetch();
                        if($data500['pay_method']==1){
                            if($data500['collect_cost']!=null||$data500['collect_cost']!=0){
                                $count2+=$data500['collect_cost'];
                            }else{
                                $count2+=$data500['order_cost'];
                            }
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$customer_id)
                            ->where('order_id', '=', $data300[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data1000 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data1000['id'])
                            ->where('order_id', '=', $data300[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data1100 = $stmt->fetchAll();
                        $is_transfer=null;
                        if($data500!=null){
                            $is_transfer=$data500['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data300[$y]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data1400 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data1400['id'])
                            ->where('order_id', '=', $data300[$y]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data1200 = $stmt->fetchAll();
                        if($data1200!=null){
                            $next_cost=$data1200[0]['transfer_cost'];
                        }
                        if(substr($data300[$y]['order_id'],0,7)!=$tenant_num&&$is_transfer==1){
                            if($data1100!=null){
                                $count3+=$data1100[0]['transfer_cost'];
                            }
                        }
                        if($next_cost!=''||$next_cost!=null){
                            $count4+=$next_cost;
                        }
                    }
                    $data200[$j]['weight']=$count1;
                    $data200[$j]['count']=$count;
                    $data200[$j]['d_cost']=$count2;
                    $data200[$j]['transfer_cost']=$count3;
                    $data200[$j]['next_cost']=$count4;
                    array_push($arrays1,$data200[$j]);
                }
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","scheduling"=>$arrays1));
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});

$app->get('/getStatistic6',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('customer_id');
    $pay_method=$app->request->get('pay_method');
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=""){
        if($customer_id!=null||$customer_id!=""){
            if($pay_method!=null||$pay_method!=""){
                $length=str_split($pay_method,1);
                for($i=0;$i<count($length);$i++) {
//                    echo $length[$i];
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('sender_id', '=', $customer_id)
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('pay_method', '=',(int)$length[$i]-1)
                        ->where('order_status', '>', 0)
                        ->where('order_status', '!=', 6)
                        ->orderBy('order_id');
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetchAll();
                    for ($x = 0; $x < count($data); $x++) {
                        $selectStatement = $database->select()
                            ->from('goods')
                            ->where('order_id', '=', $data[$x]['order_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $data[$x]['goods'] = $data2;
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data[$x]['sender_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data[$x]['fcity'] = $data4['name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data[$x]['receiver_id'])
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data5['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $data[$x]['tcity'] = $data7['name'];
                    }
                    if($data!=null){
                     $arrays1=array_merge($arrays1,$data);
                    }
                }
                echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$arrays1));
            }else{
                echo  json_encode(array("result"=>"2","desc"=>"缺少付款方式"));
            }
        }else{
            echo  json_encode(array("result"=>"1","desc"=>"缺少客户id"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"缺少租户id"));
    }
});


$app->get('/getStatistic7',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $customer_id=$app->request->get('customer_id');
    $pay_method=$app->request->get('pay_method');
    $time1=$app->request->get('begintime');
    $time2=$app->request->get('endtime');
    date_default_timezone_set("PRC");
    $arrays1=array();
    $time3=date("Y-m-d H:i:s",strtotime($time2)+86401);
    if($tenant_id!=null||$tenant_id!=""){
        if($customer_id!=null||$customer_id!=""){
            if($pay_method!=null||$pay_method!=""){
                $length=str_split($pay_method,1);
                for($i=0;$i<count($length);$i++) {
//                    echo $length[$i];
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('sender_id', '=', $customer_id)
                    ->where('order_datetime1', '>', $time1)
                    ->where('order_datetime1', '<', $time3)
                    ->where('pay_method', '=',(int)$length[$i]-1)
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_status', '>', 0)
                    ->where('order_status', '!=', 6)
                    ->orderBy('order_id');
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                for ($x = 0; $x < count($data); $x++) {
                    $selectStatement = $database->select()
                        ->from('goods')
                        ->where('order_id', '=', $data[$x]['order_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $data[$x]['goods'] = $data2;
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data[$x]['sender_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data[$x]['fcity'] = $data4['name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data[$x]['receiver_id'])
                        ->where('tenant_id', '=', $tenant_id);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data5['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $data[$x]['tcity'] = $data7['name'];
                }
                    if($data!=null){
                        $arrays1=array_merge($arrays1,$data);
                    }
                }
                echo json_encode(array("result" => "0", "desc" => "success", "orders" => $arrays1));
            }else{
                echo  json_encode(array("result"=>"2","desc"=>"缺少付款方式"));
            }
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
