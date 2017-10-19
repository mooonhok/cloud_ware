<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 13:35
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//app注册司机和派件员
$app->post('/addlorryaexpress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $plate_number=$body->plate_number;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    $type=$body->type;
    if($type!=null||$type!=""){
        if($type==0){
            if($driver_name!=null||$driver_name!=""){
                if($driver_phone!=null||$driver_phone!=""){
                    if($plate_number!=null||$plate_number!=""){
                        if($password!=null||$password!=""){
                            $selectStatement = $database->select()
                                ->from('lorry')
                                ->where('exist','=',0)
                                ->where('driver_phone','=',$driver_phone)
                                ->where('plate_number','=',$plate_number)
                                ->where('tenant_id','=',0)
                                ->where('driver_name', '=', $driver_name);
                            $stmt = $selectStatement->execute();
                            $data1 = $stmt->fetch();
                            if($data1==null){
                                $selectStatement=$database->select()
                                    ->from('lorry');
                                $stmt=$selectStatement->execute();
                                $data2=$stmt->fetch();
                                $insertStatement = $database->insert(array('lorry_id','driver_name','plate_number','driver_phone','password','exist','tenant_id'))
                                    ->into('lorry')
                                    ->values(array(count($data2)+1,$driver_name,$plate_number,$driver_phone,$password,0,0));
                                $insertId = $insertStatement->execute(false);
                                echo json_encode(array('result' => '0', 'desc' => '注册成功','lorry'=>''));
                            }else{
                                echo json_encode(array('result' => '5', 'desc' => '该司机已经存在','lorry'=>''));
                            }
                        }else{
                            echo json_encode(array('result' => '4', 'desc' => '密码为空','lorry'=>''));
                        }
                    }else{
                        echo json_encode(array('result' => '3', 'desc' => '车牌号为空','lorry'=>''));
                    }
                }else{
                    echo json_encode(array('result' => '2', 'desc' => '司机电话为空','lorry'=>''));
                }
            }else{
                echo json_encode(array('result' => '1', 'desc' => '司机姓名为空','lorry'=>''));
            }
        }else{
            if($driver_name!=null||$driver_name!=""){
                if($driver_phone!=null||$driver_phone!=""){
                    if($plate_number!=null||$plate_number!=""){
                        if($password!=null||$password!=""){
                            $selectStatement = $database->select()
                                ->from('courier')
                                ->where('exist','=',0)
                                ->where('courier_name','=',$driver_name)
                                ->where('courier_phone','=',$driver_phone)
                                ->where('courier_plate', '=', $plate_number);
                            $stmt = $selectStatement->execute();
                            $data1 = $stmt->fetch();
                            if($data1==null){
                                $selectStatement=$database->select()
                                    ->from('courier');
                                $stmt=$selectStatement->execute();
                                $data2=$stmt->fetch();
                                $insertStatement = $database->insert(array('courier_id','courier_name','courier_plate','courier_phone','password','exist'))
                                    ->into('courier')
                                    ->values(array(count($data2)+1,$driver_name,$plate_number,$driver_phone,$password,0));
                                $insertId = $insertStatement->execute(false);
                                echo json_encode(array('result' => '0', 'desc' => '注册成功','lorry'=>''));
                            }else{
                                echo json_encode(array('result' => '5', 'desc' => '该配件员已经存在','lorry'=>''));
                            }
                        }else{
                            echo json_encode(array('result' => '4', 'desc' => '密码为空','lorry'=>''));
                        }
                    }else{
                        echo json_encode(array('result' => '3', 'desc' => '车牌号为空','lorry'=>''));
                    }
                }else{
                    echo json_encode(array('result' => '2', 'desc' => '司机电话为空','lorry'=>''));
                }
            }else{
                echo json_encode(array('result' => '1', 'desc' => '司机姓名为空','lorry'=>''));
            }
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '未选择注册类型','lorry'=>''));
    }

});

//登录方法
$app->post('/lorrysign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $driver_phone=$body->driver_phone;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    $type=$body->type;
    if($type!=null||$type!=""){
        if($type==0){
            if($driver_phone!=null||$driver_phone!=""){
                $selectStament=$database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('tenant_id','=',0)
                    ->where('driver_phone','=',$driver_phone);
                $stmt=$selectStament->execute();
                $data=$stmt->fetch();
                if($data!=null){
                    if($data['password']==$password){
                        $arrays['lorry_id']=$data['lorry_id'];
                        echo json_encode(array('result' => '0', 'desc' => '登录成功','lorry'=>$arrays));
                    }else{
                        echo json_encode(array('result' => '3', 'desc' => '密码错误','lorry'=>''));
                    }
                }else{
                    echo json_encode(array('result' => '2', 'desc' => '该帐号不存在','lorry'=>''));
                }
            }else{
                echo json_encode(array('result' => '1', 'desc' => '电话为空','lorry'=>''));
            }
        }else{
            if($driver_phone!=null||$driver_phone!=""){
                $selectStament=$database->select()
                    ->from('courier')
                    ->where('exist','=',0)
                    ->where('tenant_id','=',0)
                    ->where('courier_phone','=',$driver_phone);
                $stmt=$selectStament->execute();
                $data=$stmt->fetch();
                if($data!=null){
                    if($data['password']==$password){
                        $arrays['lorry_id']=$data['courier_id'];
                        echo json_encode(array('result' => '0', 'desc' => '登录成功','lorry'=>$arrays));
                    }else{
                        echo json_encode(array('result' => '3', 'desc' => '密码错误','lorry'=>''));
                    }
                }else{
                    echo json_encode(array('result' => '2', 'desc' => '该帐号不存在','lorry'=>''));
                }
            }else{
                echo json_encode(array('result' => '1', 'desc' => '电话为空','lorry'=>''));
            }
        }
    }else{
        echo json_encode(array('result' => '5', 'desc' => '未选择登录类型','lorry'=>''));
    }
});
//获取司机的待交付清单列表
$app->get('/sbylorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data['plate_number'])
                ->where('driver_phone','=',$data['driver_phone'])
                ->where('driver_name','=',$data['driver_name']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            $sum=0;
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('scheduling_status','=',4)
                    ->where('lorry_id','=',$data2[$x]['lorry_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetchAll();
                $sum+=count($data3);
                for($i=0;$i<count($data3);$i++){
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data3[$i]['tenant_id'])
                        ->where('customer_id','=',$data3[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                    $arrays1['customer_name']=$data4['customer_name'];
                    array_push($arrays,$arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '','sch'=>$arrays,'count'=>$sum));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在','sch'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '司机信息为空','sch'=>''));
    }
});
//获取司机的已送到清单列表
$app->get('/sbylorryn',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('tenant_id','!=',0)
                ->where('plate_number','=',$data['plate_number'])
                ->where('driver_phone','=',$data['driver_phone'])
                ->where('driver_name','=',$data['driver_name']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('scheduling_status','!=',1)
                    ->where('scheduling_status','!=',2)
                    ->where('scheduling_status','!=',3)
                    ->where('scheduling_status','!=',4)
                    ->where('lorry_id','=',$data2[$x]['lorry_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetchAll();
                for($i=0;$i<count($data3);$i++){
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data3[$i]['tenant_id'])
                        ->where('customer_id','=',$data3[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                    $arrays1['customer_name']=$data4['customer_name'];
                    array_push($arrays,$arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '','sch'=>$arrays));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在','sch'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '司机信息为空','sch'=>''));
    }
});

//根据清单号拿运单的具体信息
$app->get('/sandoandg',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $schedule_id = $app->request->get("schedule_id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $arrays=array();
    $arrays2=array();
    if($schedule_id!=null||$schedule_id!=""){
         if($lorry_id!=null||$lorry_id!=""){
             $selectStament=$database->select()
                 ->from('lorry')
                 ->where('exist','=',0)
                 ->where('lorry_id','=',$lorry_id);
             $stmt=$selectStament->execute();
             $data=$stmt->fetch();
             if($data!=null){
                 $selectStament=$database->select()
                     ->from('scheduling')
                     ->where('exist','=',0)
                     ->where('scheduling_id','=',$schedule_id);
                 $stmt=$selectStament->execute();
                 $data1=$stmt->fetch();
                 if($data1!=null){
                     $selectStament=$database->select()
                         ->from('lorry')
                         ->where('exist','=',0)
                         ->where('tenant_id','=',$data1['tenant_id'])
                         ->where('lorry_id','=',$data1['lorry_id']);
                     $stmt=$selectStament->execute();
                     $data3=$stmt->fetch();
                     if($data3['driver_phone']==$data['driver_phone']&&$data3['plate_number']==$data['plate_number']&&$data3['driver_name']==$data['driver_name']){
                         $selectStament=$database->select()
                             ->from('schedule_order')
                             ->where('exist','=',0)
                             ->where('schedule_id','=',$schedule_id);
                         $stmt=$selectStament->execute();
                         $data4=$stmt->fetchAll();
                         $num=count($data4);
                         for($x=0;$x<count($data4);$x++){
                             $selectStament=$database->select()
                                 ->from('goods')
                                 ->where('exist','=',0)
                                 ->where('order_id','=',$data4[$x]['order_id']);
                             $stmt=$selectStament->execute();
                             $data5=$stmt->fetch();
                             $arrays1['order_id']=$data4[$x]['order_id'];
                             $arrays1['goods_name']=$data5['goods_name'];
                             $arrays1['goods_count']=$data5['goods_count'];
                             $arrays1['goods_capacity']=$data5['goods_capacity'];
                             $arrays1['goods_weight']=$data5['goods_weight'];
                             $selectStament=$database->select()
                                 ->from('goods_package')
                                 ->where('goods_package_id','=',$data5['goods_package_id']);
                             $stmt=$selectStament->execute();
                             $data6=$stmt->fetch();
                             $arrays1['goods_package']=$data6['goods_package'];
                             array_push($arrays,$arrays1);
                         }
                         $selectStament=$database->select()
                             ->from('city')
                             ->where('id','=',$data1['send_city_id']);
                         $stmt=$selectStament->execute();
                         $data7=$stmt->fetch();
                         $selectStament=$database->select()
                             ->from('city')
                             ->where('id','=',$data1['receive_city_id']);
                         $stmt=$selectStament->execute();
                         $data8=$stmt->fetch();
                         $selectStament=$database->select()
                             ->from('customer')
                             ->where('customer_id','=',$data1['receiver_id']);
                         $stmt=$selectStament->execute();
                         $data9=$stmt->fetch();
                        $arrays2['name']=$data9['customer_name'];
                        $arrays2['phone']=$data9['customer_phone'];
                        $arrays2['sendcity']=$data7['name'];
                        $arrays2['receivecity']=$data8['name'];
                         echo json_encode(array('result' => '0', 'desc' => '','goods'=>$arrays,'customer'=>$arrays2,'count'=>$num,'isreceive'=>$data1['scheduling_status']));
                     }else{
                         echo json_encode(array('result' => '5', 'desc' => '该清单不是您的','goods'=>''));
                     }
                 }else{
                     echo json_encode(array('result' => '4', 'desc' => '该清单不存在','goods'=>''));
                 }
             }else{
                 echo json_encode(array('result' => '3', 'desc' => '司机不存在','goods'=>''));
             }
         }else{
             echo json_encode(array('result' => '2', 'desc' => '司机未登录','goods'=>''));
         }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空','goods'=>''));
    }
});

//根据orderid获取信息
$app->get('/byorderid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $order_id = $app->request->get("order_id");
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        $selectStament=$database->select()
            ->from('orders')
            ->where('order_id','=',$order_id);
        $stmt=$selectStament->execute();
        $data4=$stmt->fetch();
        $selectStament=$database->select()
            ->from('delivery_order')
            ->where('delivery_order_id','=',$order_id);
        $stmt=$selectStament->execute();
        $data6=$stmt->fetch();
        $selectStament=$database->select()
            ->from('delivery')
            ->where('delivery_id','=',$data6['delivery_id']);
        $stmt=$selectStament->execute();
        $data9=$stmt->fetch();
        $selectStament=$database->select()
            ->from('customer')
            ->where('customer_id','=',$data4['receiver_id']);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetch();
        $selectStament=$database->select()
            ->from('goods')
            ->where('exist','=',0)
            ->where('order_id','=',$order_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        $arrays['order_id']=$data4['order_id'];
        $arrays['customer_name']=$data5['customer_name'];
        $arrays['customer_phone']=$data5['customer_phone'];
        $arrays['goods_name']=$data['goods_name'];
        $arrays['goods_count']=$data['goods_count'];
        $arrays['goods_weight']=$data['goods_weight'];
        $arrays['goods_capacity']=$data['goods_capacity'];
        $selectStament=$database->select()
            ->from('goods_package')
            ->where('goods_package_id','=',$data['goods_package_id']);
        $stmt=$selectStament->execute();
        $data7=$stmt->fetch();
        $arrays['goods_package']=$data7['goods_package'];
        $selectStament=$database->select()
            ->from('city')
            ->where('id','=',$data5['customer_city_id']);
        $stmt=$selectStament->execute();
        $data8=$stmt->fetch();
        $arrays['address']=$data8['name'].$data5['customer_address'];
        if($data!=null||$data!=""){
            echo json_encode(array('result' => '0', 'desc' => '','order'=>$arrays,'is_sure'=>$data9['is_receive']));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '运单不存在','order'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号为空','order'=>''));
    }
});

//pic
$app->post('/suresch',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
 //  $schedule_id = $app->request->params('schedule_id');
//    $lorry_id = $app->request->params('lorry_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $new_file = "/files/sure/".date('Ymd',time())."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.time().".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing= $new_file;
        }
    }
    $arrays['scheduling_status']=5;
    $arrays['sure_img']=$lujing;
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','=',4)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($lorry_id!=null||$lorry_id!=""){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$data1['plate_number'])
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '接单成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }

});
//清单取消
$app->post('/sureschfor',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $arrays['scheduling_status']=6;
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','!=',5)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($lorry_id!=null||$lorry_id!=""){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$data1['plate_number'])
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '取消成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }

});
//确认拉货
$app->post('/sureschthree',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $arrays['scheduling_status']=3;
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','=',2)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($lorry_id!=null||$lorry_id!=""){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$data1['plate_number'])
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }

});


$app->post('/sureschtwo',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $arrays['scheduling_status']=5;
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_status','=',4)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($lorry_id!=null||$lorry_id!=""){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$data1['plate_number'])
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '接单成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }

});



//orders未送到
$app->get('/obycourier',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $courier_id=$app->request->get('courierid');
    $database=localhost();
    $arrays=array();
    if($courier_id!=null||$courier_id!=""){
        $selectStament=$database->select()
            ->from('courier')
            ->where('exist','=',0)
            ->where('courier_id','=',$courier_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        $selectStament=$database->select()
            ->from('courier')
            ->where('exist','=',0)
        ->where('courier_name','=',$data['courier_name'])
            ->where('courier_phone','=',$data['courier_phone'])
            ->where('courier_plate', '=', $data['courier_plate']);
        $stmt=$selectStament->execute();
        $data6=$stmt->fetchAll();
     if($data6!=null) {
         $num=0;
       for($x=0;$x<count($data6);$x++) {
           $selectStament = $database->select()
               ->from('delivery')
               ->where('exist', '=', 0)
               ->where('is_receive', '=', 0)
               ->where('courier_id', '=', $data6[$x]['courier_id']);
           $stmt = $selectStament->execute();
           $data2 = $stmt->fetchAll();
           $num += count($data2);
               for ($x = 0; $x < count($data2); $x++) {
                   $selectStament = $database->select()
                       ->from('delivery_order')
                       ->where('exist', '=', 0)
                       ->where('delivery_id', '=', $data2[$x]['delivery_id']);
                   $stmt = $selectStament->execute();
                   $data3 = $stmt->fetch();
                   $selectStament = $database->select()
                       ->from('orders')
                       ->where('order_id', '=', $data3['delivery_order_id']);
                   $stmt = $selectStament->execute();
                   $data4 = $stmt->fetch();
                   $arrays1['order_id'] = $data4['order_id'];
                   $selectStament = $database->select()
                       ->from('customer')
                       ->where('customer_id', '=', $data4['receiver_id']);
                   $stmt = $selectStament->execute();
                   $data5 = $stmt->fetch();
                   $arrays1['customer_name'] = $data5['customer_name'];
                   array_push($arrays, $arrays1);
               }
       }
         echo json_encode(array('result' => '0', 'desc' => '', 'orders' => $arrays, 'count' => $num));
     }else{
         echo json_encode(array('result' => '2', 'desc' => '配送员不存在'));
     }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '配送员为空'));
    }
});

//orders送到
$app->get('/obycouriern',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $courier_id=$app->request->get('courierid');
    $database=localhost();
    $arrays=array();
    if($courier_id!=null||$courier_id!=""){
        $selectStament=$database->select()
            ->from('courier')
            ->where('exist','=',0)
            ->where('courier_id','=',$courier_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        $selectStament=$database->select()
            ->from('courier')
            ->where('exist','=',0)
            ->where('courier_name','=',$data['courier_name'])
            ->where('courier_phone','=',$data['courier_phone'])
            ->where('courier_plate', '=', $data['courier_plate']);
        $stmt=$selectStament->execute();
        $data6=$stmt->fetchAll();
        if($data6!=null) {
            for($x=0;$x<count($data6);$x++) {
                $selectStament = $database->select()
                    ->from('delivery')
                    ->where('exist', '=', 0)
                    ->where('is_receive', '!=', 2)
                    ->where('is_receive', '!=', 0)
                    ->where('courier_id', '=', $data6[$x]['courier_id']);
                $stmt = $selectStament->execute();
                $data2 = $stmt->fetchAll();
                    for ($x = 0; $x < count($data2); $x++) {
                        $selectStament = $database->select()
                            ->from('delivery_order')
                            ->where('exist', '=', 0)
                            ->where('delivery_id', '=', $data2[$x]['delivery_id']);
                        $stmt = $selectStament->execute();
                        $data3 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('orders')
                            ->where('order_id', '=', $data3['delivery_order_id']);
                        $stmt = $selectStament->execute();
                        $data4 = $stmt->fetch();
                        $arrays1['order_id'] = $data4['order_id'];
                        $selectStament = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data4['receiver_id']);
                        $stmt = $selectStament->execute();
                        $data5 = $stmt->fetch();
                        $arrays1['customer_name'] = $data5['customer_name'];
                        array_push($arrays, $arrays1);
                    }
            }
                    echo json_encode(array('result' => '0', 'desc' => '', 'orders' => $arrays));

        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '配送员为空'));
    }
});



$app->post('/ordersure',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
 //   $courier_id = $app->request->params('courier_id');
   // $order_id = $app->request->params('order_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $courier_id=$body->courier_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $new_file = "/files/sureone/".date('Ymd',time())."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.time().".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing= $new_file;
        }
    }
    $arrays['is_receive']=1;
    $arrays['sure_img']=$lujing;
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($courier_id!=null||$courier_id!=""){
            $selectStament=$database->select()
                ->from('orders')
                ->where('order_id','=',$order_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                $selectStament=$database->select()
                    ->from('delivery_order')
                    ->where('delivery_order_id','=',$order_id);
                $stmt=$selectStament->execute();
                $data9=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('delivery')
                    ->where('is_receive','=',0)
                    ->where('delivery_id','=',$data9['delivery_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                if($data10!=null){
                    $selectStament=$database->select()
                        ->from('courier')
                        ->where('courier_id','=',$courier_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null){
                        $updateStatement = $database->update($arrays)
                            ->table('delivery')
                            ->where('delivery_id', '=', $data9['delivery_id']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '配送员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});
$app->post('/ordersurefor',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    //   $courier_id = $app->request->params('courier_id');
    // $order_id = $app->request->params('order_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $courier_id=$body->courier_id;
    $arrays['is_receive']=4;
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($courier_id!=null||$courier_id!=""){
            $selectStament=$database->select()
                ->from('orders')
                ->where('order_id','=',$order_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                $selectStament=$database->select()
                    ->from('delivery_order')
                    ->where('delivery_order_id','=',$order_id);
                $stmt=$selectStament->execute();
                $data9=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('delivery')
                    ->where('is_receive','!=',1)
                    ->where('delivery_id','=',$data9['delivery_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                if($data10!=null){
                    $selectStament=$database->select()
                        ->from('courier')
                        ->where('courier_id','=',$courier_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null){
                        $updateStatement = $database->update($arrays)
                            ->table('delivery')
                            ->where('delivery_id', '=', $data9['delivery_id']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '配送员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});
$app->post('/ordersurethree',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    //   $courier_id = $app->request->params('courier_id');
    // $order_id = $app->request->params('order_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $courier_id=$body->courier_id;
    $arrays['is_receive']=0;
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($courier_id!=null||$courier_id!=""){
            $selectStament=$database->select()
                ->from('orders')
                ->where('order_id','=',$order_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                $selectStament=$database->select()
                    ->from('delivery_order')
                    ->where('delivery_order_id','=',$order_id);
                $stmt=$selectStament->execute();
                $data9=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('delivery')
                    ->where('is_receive','=',2)
                    ->where('delivery_id','=',$data9['delivery_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                if($data10!=null){
                    $selectStament=$database->select()
                        ->from('courier')
                        ->where('courier_id','=',$courier_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null){
                        $updateStatement = $database->update($arrays)
                            ->table('delivery')
                            ->where('delivery_id', '=', $data9['delivery_id']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '配送员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});
$app->post('/ordersuretwo',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    //   $courier_id = $app->request->params('courier_id');
    // $order_id = $app->request->params('order_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $courier_id=$body->courier_id;
    $arrays['is_receive']=1;
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($courier_id!=null||$courier_id!=""){
            $selectStament=$database->select()
                ->from('orders')
                ->where('order_id','=',$order_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                $selectStament=$database->select()
                    ->from('delivery_order')
                    ->where('delivery_order_id','=',$order_id);
                $stmt=$selectStament->execute();
                $data9=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('delivery')
                    ->where('is_receive','=',0)
                    ->where('delivery_id','=',$data9['delivery_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                if($data10!=null){
                    $selectStament=$database->select()
                        ->from('courier')
                        ->where('courier_id','=',$courier_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null){
                        $updateStatement = $database->update($arrays)
                            ->table('delivery')
                            ->where('delivery_id', '=', $data9['delivery_id']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '配送员不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});

//获取个人信息
$app->get('/lacx',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $type=$app->request->get('type');
    $database=localhost();
    if($type!=null||$type!=""){
         if($type==0){
             if($id!=null||$id!=""){
                 $selectStatement = $database->select()
                     ->from('lorry')
                     ->where('exist','=',0)
                     ->where('tenant_id','=',0)
                     ->where('lorry_id', '=', $id);
                 $stmt = $selectStatement->execute();
                 $data1 = $stmt->fetch();
                 if($data1!=null){
                     echo json_encode(array('result' => '0', 'desc' => '','lorry'=>$data1));
                 }else{
                     echo json_encode(array('result' => '5', 'desc' => '该司机不存在'));
                 }
             }else{
                 echo json_encode(array('result' => '4', 'desc' => '没有司机id'));
             }
         }else{
             if($id!=null||$id!=""){
                 $selectStatement = $database->select()
                     ->from('courier')
                     ->where('exist','=',0)
                     ->where('courier_id', '=', $id);
                 $stmt = $selectStatement->execute();
                 $data1 = $stmt->fetch();
                 if($data1!=null){
                     echo json_encode(array('result' => '0', 'desc' => '','lorry'=>$data1));
                 }else{
                     echo json_encode(array('result' => '3', 'desc' => '该配送员不存在'));
                 }
             }else{
                 echo json_encode(array('result' => '2', 'desc' => '没有配送员id'));
             }
         }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有类型信息'));
    }
});
//修改个人信息（密码）
$app->put('/updatelac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $type=$body->type;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    $arrays['password']=$password;
    if($type!=null||$type!=""){
       if($type==0){
           if($id!=null||$id!=""){
               $selectStatement = $database->select()
                   ->from('lorry')
                   ->where('exist','=',0)
                   ->where('tenant_id','=',0)
                   ->where('lorry_id', '=', $id);
               $stmt = $selectStatement->execute();
               $data1 = $stmt->fetch();
               if($data1!=null){
                    if($password1!=null||$password1!=""){
                        $updateStatement = $database->update($arrays)
                            ->from('lorry')
                            ->where('plate_number','=',$data1['plate_number'])
                            ->where('driver_phone','=',$data1['driver_phone'])
                            ->where('driver_name','=',$data1['driver_name']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '密码不能为空'));
                    }
               }else{
                   echo json_encode(array('result' => '3', 'desc' => '该司机不存在'));
               }
           }else{
               echo json_encode(array('result' => '2', 'desc' => '没有id'));
           }
       }else{
           if($id!=null||$id!=""){
               $selectStatement = $database->select()
                   ->from('courier')
                   ->where('exist','=',0)
                   ->where('courier_id', '=', $id);
               $stmt = $selectStatement->execute();
               $data1 = $stmt->fetch();
               if($data1!=null){
                   if($password1!=null||$password1!=""){
                       $updateStatement = $database->update($arrays)
                           ->from('courier_id')
                           ->where('courier_id','=',$id);
                       $affectedRows = $updateStatement->execute();
                       echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
                   }else{
                       echo json_encode(array('result' => '4', 'desc' => '密码不能为空'));
                   }
               }else{
                   echo json_encode(array('result' => '3', 'desc' => '该配送员不存在'));
               }
           }else{
               echo json_encode(array('result' => '2', 'desc' => '没有配送员id'));
           }
       }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有类型信息'));
    }
});



$app->run();

function localhost(){
    return connect();
}

?>