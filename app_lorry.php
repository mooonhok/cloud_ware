<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/24
 * Time: 15:50
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//注册
$app->post('/addlorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $tel=$body->tel;
    $plate_number=$body->plate_number;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($name!=null||$name!=""){
        if($tel!=null||$tel!=""){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('driver_phone','=',$tel);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1==null){
                if($plate_number!=null||$plate_number!=""){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$plate_number);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    if($data2==null){
                        if($password1!=null||$password1!=""){
                            $selectStament=$database->select()
                                ->from('lorry');
                            $stmt=$selectStament->execute();
                            $data3=$stmt->fetchAll();
                            $insertStatement = $database->insert(array('lorry_id','plate_number','driver_name','driver_phone','password','app_chose'))
                                ->into('lorry')
                                ->values(array(count($data3),$plate_number,$name,$tel,$password,1));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result"=>"0","desc"=>"注册成功"));
                        }else{
                            echo json_encode(array("result"=>"4","desc"=>"密码为空"));
                        }
                    }else{
                        echo json_encode(array("result"=>"6","desc"=>"车牌号已经被注册"));
                    }
                }else{
                    echo json_encode(array("result"=>"3","desc"=>"车牌号为空"));
                }
            }else{
                echo json_encode(array("result"=>"5","desc"=>"手机号已经被注册"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"电话为空"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"姓名为空"));
    }
});
//登录
$app->post('/lorry_sign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $driver_phone=$body->driver_phone;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    $time=time();
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($driver_phone!=null||$driver_phone!=""){
        if($password1!=null||$password1!=""){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('tenant_id','=',0)
                ->where('app_chose','=',1)
                ->where('driver_phone','=',$driver_phone);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($password==$data['password']){
                    $arrays['signtime']=time();
                    $updateStatement = $database->update($arrays)
                        ->table('lorry')
                        ->where('driver_name','=',$data['driver_name'])
                        ->where('driver_phone','=',$data['driver_phone']);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'登录成功','lorry'=>$data,'time'=>$time));
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'密码不对','lorry'=>''));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'用户不存在','lorry'=>''));
            }
        }else{
            echo json_encode(array('result' => '2','desc' => '密码为空','lorry'=>''));
        }
    }else{
        echo json_encode(array('result' => '1','desc' => '电话为空','lorry'=>''));
    }
});
//根据清单号拿运单的具体信息
$app->get('/sandoandg',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $schedule_id = $app->request->get("schedule_id");
    $lorry_id = $app->request->get("lorry_id");
    $time=$app->request->get("time1");
    $database=localhost();
    $arrays=array();
    $arrays2=array();
    if($schedule_id!=null||$schedule_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('exist','=',0)
                ->where('flag','=',0)
                ->where('lorry_id','=',$lorry_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['signtime']==$time){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('exist','=',0)
                    ->where('scheduling_id','=',$schedule_id);
                $stmt=$selectStament->execute();
                $data1=$stmt->fetch();
                if($data1!=null){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('flag','=',0)
                        ->where('tenant_id','=',$data1['tenant_id'])
                        ->where('lorry_id','=',$data1['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    if($data3['driver_phone']==$data['driver_phone']&&$data3['driver_name']==$data['driver_name']){
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
                        $arrays2['pic']=$data1['sure_img'];
                        echo json_encode(array('result' => '0', 'desc' => '','goods'=>$arrays,'customer'=>$arrays2,'count'=>$num,'isreceive'=>$data1['scheduling_status']));
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '该清单不是您的','goods'=>''));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '该清单不存在','goods'=>''));
                }
                }else{
                    echo json_encode(array('result' => '9', 'desc' => '您的帐号已经在其他地方登录，请重新登录','goods'=>''));
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
        if($data4!=null||$data4!=""){
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
            $arrays['pic']=$data9['sure_img'];
            echo json_encode(array('result' => '0', 'desc' => '','order'=>$arrays,'is_sure'=>$data9['is_receive']));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '运单不存在','order'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号为空','order'=>''));
    }
});

//获取司机的待交付清单列表
$app->get('/sbylorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $time=$app->request->get("time1");
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['signtime']==$time){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('tenant_id','!=',0)
                ->where('flag','=',0)
                ->where('driver_phone','=',$data['driver_phone'])
                ->where('driver_name','=',$data['driver_name']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            $sum=0;
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('scheduling_status','=',4)
                    ->where('tenant_id','=',$data2[$x]['tenant_id'])
                    ->where('lorry_id','=',$data2[$x]['lorry_id'])
                    ->orderBy('change_datetime','desc');
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
                echo json_encode(array('result' => '9', 'desc' => '您的帐号已经在其他地方登录，请重新登录','sch'=>''));
        }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在或该车辆不是默认车辆','sch'=>''));
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
    $time=$app->request->get("time1");
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($time==$data['signtime']){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('tenant_id','!=',0)
                ->where('flag','=',0)
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
                    ->where('tenant_id','=',$data2[$x]['tenant_id'])
                    ->where('lorry_id','=',$data2[$x]['lorry_id'])
                    ->orderBy('change_datetime','desc');
                $stmt=$selectStament->execute();
                $data3=$stmt->fetchAll();
                for($i=0;$i<count($data3);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data3[$i]['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data3[$i]['tenant_id'])
                        ->where('customer_id','=',$data3[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                    $arrays1['customer_name']=$data4['customer_name'];
                    $arrays1['plate_number']=$data5['plate_number'];
                    $arrays1['pic']=$data3[$i]['sure_img'];
                    $arrays1['status']=$data3[$i]['scheduling_status'];
                    array_push($arrays,$arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '','sch'=>$arrays));
        }else{
                echo json_encode(array('result' => '9', 'desc' => '您的帐号已经在其他地方登录请重新登录','sch'=>''));
        }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在','sch'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '司机信息为空','sch'=>''));
    }
});
//签字确认送到图片
$app->post('/suresch',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $time=$body->time1;
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1=time();
        $new_file = "/files/sure/".date('Ymd',$time1)."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.time().".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing="http://files.uminfo.cn:8000/sure/".date('Ymd',$time1)."/".$time1.".{$type}";
        }
    }
    $arrays['scheduling_status']=5;
    $arrays['is_contract']=0;
    $arrays['is_insurance']=0;
    $arrays['sure_img']=$lujing;
    $arrays['change_datetime']=time();
    date_default_timezone_set("PRC");
    $arrays1['order_status']=4;
    $arrays1['order_datetime4']=date("Y-m-d H:i:s",time());
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
                    ->where('flag','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    if($data1['signtime']==$time){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('app_chose','=',1)
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('flag','=',0)
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        $selectStament=$database->select()
                            ->from('schedule_order')
                            ->where('schedule_id','=',$schedule_id);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $updateStatement = $database->update($arrays1)
                            ->table('orders')
                            ->where('order_id', '=', $data3['order_id']);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '接单成功'));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在或车辆不是默认车辆'));
                    }
                    }else{
                        echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在或清单已经处理了'));
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
    $time=$body->time1;
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $arrays['scheduling_status']=6;
    $arrays['is_contract']=0;
    $arrays['is_insurance']=0;
    $arrays['change_datetime']=time();
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
                    ->where('app_chose','=',1)
                    ->where('exist','=',0)
                    ->where('flag','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    if($data1['signtime']==$time){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('flag','=',0)
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$data['tenant_id'])
                        ->where('lorry_id','=',$data['lorry_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        if($data2['plate_number']==$data1['plate_number']){
                        $updateStatement = $database->update($arrays)
                            ->table('scheduling')
                            ->where('scheduling_id', '=', $schedule_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array('result' => '0', 'desc' => '取消成功'));
                        }else{
                            echo json_encode(array('result' => '6', 'desc' => '车牌不是默认车牌'));
                        }
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在'));
                    }
                    }else{
                        echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在或清单已经处理了'));
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
    $time=$body->time1;
    $schedule_id=$body->schedule_id;
    $lorry_id=$body->lorry_id;
    $arrays['scheduling_status']=3;
    $arrays['is_contract']=1;
    $arrays['is_insurance']=1;
    $arrays['change_datetime']=time();
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
                    ->where('app_chose','=',1)
                    ->where('exist','=',0)
                    ->where('flag','=',0)
                    ->where('lorry_id','=',$lorry_id)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null){
                    if($time==$data1['signtime']){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data['tenant_id'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('exist','=',0)
                        ->where('flag','=',0);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null) {
                        if($data2['plate_number']==$data1['plate_number']) {
                            $updateStatement = $database->update($arrays)
                                ->table('scheduling')
                                ->where('scheduling_id', '=', $schedule_id);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                        }else{
                            echo json_encode(array('result' => '6', 'desc' => '车牌不是默认车牌'));
                        }
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '清单上驾驶员不存在或车辆不是默认车'));
                    }
                    }else{
                        echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                    }
                }else{
                    echo json_encode(array('result' => '5', 'desc' => '驾驶员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '驾驶员未登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在或清单已经处理了'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }
});

//orders未送到
$app->get('/obycourier',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id=$app->request->get('lorry_id');
    $time=$app->request->get('time1');
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($time==$data['signtime']){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('flag','=',1)
            ->where('app_chose','=',1)
            ->where('tenant_id','!=',0)
            ->where('driver_phone','=',$data['driver_phone'])
            ->where('driver_name','=',$data['driver_name']);
        $stmt=$selectStament->execute();
        $data6=$stmt->fetchAll();
        if($data6!=null) {
            $num=0;
            for($x=0;$x<count($data6);$x++) {
                $selectStament = $database->select()
                    ->from('delivery')
                    ->where('exist', '=', 0)
                    ->where('is_receive', '=', 0)
                    ->where('lorry_id', '=', $data6[$x]['lorry_id'])
                    ->orderBy('change_datetime','desc');
                $stmt = $selectStament->execute();
                $data2 = $stmt->fetchAll();
                $num += count($data2);
                for ($y = 0; $y < count($data2); $y++) {
                    $selectStament = $database->select()
                        ->from('delivery_order')
                        ->where('exist', '=', 0)
                        ->where('delivery_id', '=', $data2[$y]['delivery_id']);
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
            echo json_encode(array('result' => '2', 'desc' => '您还不是配送员'));
        }
        }else{
            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '配送员为空'));
    }
});
//orders送到
$app->get('/obycouriern',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id=$app->request->get('lorry_id');
    $time=$app->request->get('time1');
    $database=localhost();
    $arrays=array();
    if($lorry_id!=null||$lorry_id!="") {
        $selectStament = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if ($time == $data['signtime']) {
        $selectStament = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('flag', '=', 1)
            ->where('tenant_id','!=',0)
            ->where('driver_phone', '=', $data['driver_phone'])
            ->where('driver_name', '=', $data['driver_name']);
        $stmt = $selectStament->execute();
        $data6 = $stmt->fetchAll();
        if ($data6 != null) {
            for ($x = 0; $x < count($data6); $x++) {
                $selectStament = $database->select()
                    ->from('delivery')
                    ->where('exist', '=', 0)
                    ->where('is_receive', '!=', 2)
                    ->where('is_receive', '!=', 0)
                    ->where('lorry_id', '=', $data6[$x]['lorry_id'])
                    ->orderBy('change_datetime', 'desc');
                $stmt = $selectStament->execute();
                $data2 = $stmt->fetchAll();
                for ($y = 0; $y < count($data2); $y++) {
                    $selectStament = $database->select()
                        ->from('delivery_order')
                        ->where('exist', '=', 0)
                        ->where('delivery_id', '=', $data2[$y]['delivery_id']);
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
                    $arrays1['pic'] = $data2[$y]['sure_img'];
                    $arrays1['status'] = $data2[$y]['is_receive'];
                    array_push($arrays, $arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '', 'orders' => $arrays));

        } else {
            echo json_encode(array('result' => '2', 'desc' => '您还不是配送员'));
        }
        }else{
            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '配送员为空'));
    }
});
//order送到确认图片
$app->post('/ordersure',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    //   $courier_id = $app->request->params('courier_id');
    // $order_id = $app->request->params('order_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $time=$body->time1;
    $order_id=$body->order_id;
    $lorry_id=$body->lorry_id;
    $pic=$body->pic;
    $lujing=null;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1=time();
        $new_file = "/files/sureone/".date('Ymd',$time1)."/";
        if(!file_exists($new_file))
        {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file.time().".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
            $lujing="http://files.uminfo.cn:8000/sureone/".date('Ymd',$time1)."/".$time1.".{$type}";
        }
    }
    $arrays['is_receive']=1;
    $arrays['sure_img']=$lujing;
    $arrays['change_datetime']=time();
    date_default_timezone_set("PRC");
    $arrays1['order_status']=7;
    $arrays1['order_datetime5']=date("Y-m-d H:i:s",time());
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
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
                        ->from('lorry')
                        ->where('lorry_id','=',$lorry_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('app_chose','=',1)
                        ->where('flag','=',1)
                        ->where('plate_number','=',$data2['plate_number'])
                        ->where('driver_phone','=',$data2['driver_phone'])
                        ->where('driver_name','=',$data2['driver_name']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    if($data3!=null){
                        if($time==$data2['signtime']) {
                            $updateStatement = $database->update($arrays)
                                ->table('delivery')
                                ->where('delivery_id', '=', $data9['delivery_id']);
                            $affectedRows = $updateStatement->execute();
                            $updateStatement = $database->update($arrays1)
                                ->table('orders')
                                ->where('order_id', '=', $order_id);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                        }else{
                            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                        }
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '您还不是配送员或车辆不是默认车辆'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在或运单已经处理了'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});
//order取消
$app->post('/ordersurefor',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $lorry_id=$body->lorry_id;
    $time=$body->time1;
    $arrays['is_receive']=4;
    $database=localhost();
    $arrays['change_datetime']=time();
    if($order_id!=null||$order_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
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
                        ->from('lorry')
                        ->where('lorry_id','=',$lorry_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('app_chose','=',1)
                        ->where('flag','=',1)
                        ->where('plate_number','=',$data2['plate_number'])
                        ->where('driver_phone','=',$data2['driver_phone'])
                        ->where('driver_name','=',$data2['driver_name']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    if($data3!=null){
                        if($time==$data2['signtime']) {
                            $updateStatement = $database->update($arrays)
                                ->table('delivery')
                                ->where('delivery_id', '=', $data9['delivery_id']);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                        }else{
                            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                        }
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '您还不是配送员或车辆不是默认车辆'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在或运单已经处理了'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});

//配送员确认
$app->post('/ordersurethree',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $order_id=$body->order_id;
    $lorry_id=$body->lorry_id;
    $time=$body->time1;
    $arrays['is_receive']=0;
    $arrays['change_datetime']=time();
    $database=localhost();
    if($order_id!=null||$order_id!=""){
        if($lorry_id!=null||$lorry_id!=""){
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
                        ->from('lorry')
                        ->where('lorry_id','=',$lorry_id);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('app_chose','=',1)
                        ->where('flag','=',1)
                        ->where('plate_number','=',$data2['plate_number'])
                        ->where('driver_phone','=',$data2['driver_phone'])
                        ->where('driver_name','=',$data2['driver_name']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    if($data3!=null){
                        if($time==$data2['signtime']) {
                            $updateStatement = $database->update($arrays)
                                ->table('delivery')
                                ->where('delivery_id', '=', $data9['delivery_id']);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '确认成功'));
                        }else{
                            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                        }
                    }else{
                        echo json_encode(array('result' => '5', 'desc' => '您还不是配送员或车辆不是默认车辆'));
                    }
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '运单已经配送'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '运单不存在或运单已经处理了'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '配送员id为空'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号不能为空'));
    }
});
//app首页统计
$app->get('/tongji',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $time=$app->request->get('time1');
    $database=localhost();
    if($lorry_id!=null||$lorry_id!="") {
        $selectStament = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($time==$data['signtime']) {
            $selectStament = $database->select()
                ->from('lorry')
                ->where('tenant_id', '!=', 0)
                ->where('flag', '=', 0)
                ->where('plate_number', '=', $data['plate_number'])
                ->where('driver_phone', '=', $data['driver_phone'])
                ->where('driver_name', '=', $data['driver_name']);
            $stmt = $selectStament->execute();
            $data2 = $stmt->fetchAll();
            $sum = 0;
            for ($x = 0; $x < count($data2); $x++) {
                $selectStament = $database->select()
                    ->from('scheduling')
                    ->where('scheduling_status', '=', 4)
                    ->where('tenant_id', '=', $data2[$x]['tenant_id'])
                    ->where('lorry_id', '=', $data2[$x]['lorry_id']);
                $stmt = $selectStament->execute();
                $data3 = $stmt->fetchAll();
                $sum += count($data3);
            }
            $selectStament = $database->select()
                ->from('lorry')
                ->where('exist', '=', 0)
                ->where('lorry_id', '=', $lorry_id);
            $stmt = $selectStament->execute();
            $data5 = $stmt->fetch();
            $selectStament = $database->select()
                ->from('lorry')
                ->where('exist', '=', 0)
                ->where('flag', '=', 1)
                ->where('plate_number', '=', $data5['plate_number'])
                ->where('driver_phone', '=', $data5['driver_phone'])
                ->where('driver_name', '=', $data5['driver_name']);
            $stmt = $selectStament->execute();
            $data6 = $stmt->fetchAll();
            for ($x = 0; $x < count($data6); $x++) {
                $selectStament = $database->select()
                    ->from('delivery')
                    ->where('exist', '=', 0)
                    ->where('is_receive', '=', 0)
                    ->where('lorry_id', '=', $data6[$x]['lorry_id']);
                $stmt = $selectStament->execute();
                $data7 = $stmt->fetchAll();
                $sum += count($data7);
            }
            echo json_encode(array('result' => '0', 'desc' => '', 'count' => $sum));
        }else{
            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未登录'));
    }
});
//获取车辆列表
$app->get('/getplate',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $lorry_id = $app->request->get("lorry_id");
    $time=$app->request->get("time1");
    $database=localhost();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($data!=null){
            if($data['signtime']==$time) {
                $selectStament = $database->select()
                    ->from('lorry')
                    ->where('exist', '=', 0)
                    ->where('tenant_id', '=', 0)
                    ->where('driver_phone', '=', $data['driver_phone'])
                    ->where('driver_name', '=', $data['driver_name']);
                $stmt = $selectStament->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result' => '0', 'desc' => '', 'lorrys' => $data1));
            }else{
                echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});
//获取车辆类型列表
$app->get('/lorry_type',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament = $database->select()
        ->from('lorry_type');
    $stmt = $selectStament->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        echo json_encode(array('result' => '0', 'desc' => '','lorry_type'=>$data));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未有车辆类型数据'));
    }
});
//添加车辆
$app->post('/addplate_number',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id = $body->lorry_id;
    $plate_number=$body->plate_number;
    $lorry_size=$body->lorry_size;
    $lorry_age=$body->lorry_age;
    $lorry_text=$body->lorry_text;
    $lorry_type=$body->lorry_type;
    $lorry_weight=$body->lorry_weight;
    $time=$body->time1;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament = $database->select()
            ->from('lorry')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($data!=null){
            if($time==$data['signtime']) {
                $selectStament = $database->select()
                    ->from('lorry');
                $stmt = $selectStament->execute();
                $data3 = $stmt->fetchAll();
                $insertStatement = $database->insert(array('lorry_id', 'plate_number', 'driver_name', 'driver_phone', 'lorry_size', 'lorry_age', 'lorry_tx', 'lorry_type_id',
                    'password', 'lorry_weight', 'driver_identycard', 'driving_license', 'vehicle_travel_license', 'driver_address', 'driver_email','signtime'))
                    ->into('lorry')
                    ->values(array(count($data3), $plate_number, $data['driver_name'], $data['driver_phone'], $lorry_size, $lorry_age, $lorry_text, $lorry_type, $data['password']
                    , $lorry_weight, $data['driver_identycard'], $data['driving_license'], $data['vehicle_travel_license'], $data['driver_address'], $data['driver_email'],$time));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array('result' => '0', 'desc' => '添加成功'));
            }else{
                echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});
//选择默认车辆
$app->put('/upplate_number',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $time=$body->time1;
    $lorry_id = $body->lorry_id;
    $database=localhost();
    $lorry2=$body->lorry_id2;
    if($lorry_id!=null||$lorry_id!=""){
        if($lorry2!=null||$lorry2!=""){
            if($lorry_id!=$lorry2){
            $selectStament = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',0)
                ->where('exist', '=', 0)
                ->where('lorry_id', '=', $lorry_id);
            $stmt = $selectStament->execute();
            $data9 = $stmt->fetch();
            if($time==$data9['signtime']) {
                $selectStament = $database->select()
                    ->from('lorry')
                    ->where('exist', '=', 0)
                    ->where('lorry_id', '=', $lorry_id);
                $stmt = $selectStament->execute();
                $data = $stmt->fetch();
                $selectStament = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '!=', 0)
                    ->where('flag', '=', 0)
                    ->where('plate_number', '=', $data['plate_number'])
                    ->where('driver_phone', '=', $data['driver_phone'])
                    ->where('driver_name', '=', $data['driver_name']);
                $stmt = $selectStament->execute();
                $data2 = $stmt->fetchAll();
                $sum = 0;
                for ($x = 0; $x < count($data2); $x++) {
                    $selectStament = $database->select()
                        ->from('scheduling')
                        ->where('scheduling_status', '=', 4)
                        ->where('lorry_id', '=', $data2[$x]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data3 = $stmt->fetchAll();
                    $sum += count($data3);
                }
                $selectStament = $database->select()
                    ->from('lorry')
                    ->where('exist', '=', 0)
                    ->where('lorry_id', '=', $lorry_id);
                $stmt = $selectStament->execute();
                $data5 = $stmt->fetch();
                $selectStament = $database->select()
                    ->from('lorry')
                    ->where('exist', '=', 0)
                    ->where('flag', '=', 1)
                    ->where('plate_number', '=', $data5['plate_number'])
                    ->where('driver_phone', '=', $data5['driver_phone'])
                    ->where('driver_name', '=', $data5['driver_name']);
                $stmt = $selectStament->execute();
                $data6 = $stmt->fetchAll();
                for ($x = 0; $x < count($data6); $x++) {
                    $selectStament = $database->select()
                        ->from('delivery')
                        ->where('exist', '=', 0)
                        ->where('is_receive', '=', 0)
                        ->where('lorry_id', '=', $data6[$x]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7 = $stmt->fetchAll();
                    $sum += count($data7);
                }
                if ($sum == 0) {
                    if ($data9 != null) {
                        $selectStament = $database->select()
                            ->from('lorry')
                            ->where('tenant_id', '=', 0)
                            ->where('exist', '=', 0)
                            ->where('lorry_id', '=', $lorry2);
                        $stmt = $selectStament->execute();
                        $data10 = $stmt->fetch();
                        if ($data10 != null) {
                            if ($data9['driver_name'] == $data10['driver_name'] && $data9['driver_phone'] == $data10['driver_phone']) {
                                $updateStatement = $database->update(array('app_chose' => 1))
                                    ->table('lorry')
                                    ->where('plate_number', '=', $data10['plate_number'])
                                    ->where('driver_phone', '=', $data10['driver_phone'])
                                    ->where('driver_name', '=', $data10['driver_name']);
                                $affectedRows1 = $updateStatement->execute();
                                $updateStatement = $database->update(array('app_chose' => 0))
                                    ->table('lorry')
                                    ->where('plate_number', '=', $data9['plate_number'])
                                    ->where('driver_phone', '=', $data9['driver_phone'])
                                    ->where('driver_name', '=', $data9['driver_name']);
                                $affectedRows2 = $updateStatement->execute();
                                $selectStament = $database->select()
                                    ->from('lorry')
                                    ->where('lorry_id', '=', $lorry2);
                                $stmt = $selectStament->execute();
                                $data11 = $stmt->fetch();
                                echo json_encode(array('result' => '0', 'desc' => '修改默认车辆成功', 'lorry' => $data11));
                            } else {
                                echo json_encode(array('result' => '5', 'desc' => '该车辆不是你的'));
                            }
                        } else {
                            echo json_encode(array('result' => '4', 'desc' => '车辆不存在'));
                        }
                    } else {
                        echo json_encode(array('result' => '3', 'desc' => '司机不存在'));
                    }
                } else {
                    echo json_encode(array('result' => '6', 'desc' => '您还有未处理单子'));
                }
            }else{
                echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
            }
        }else{
                echo json_encode(array('result' => '3', 'desc' => '车辆相同无法修改'));
        }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '未选择车辆'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});
//删除车辆
$app->put('/upplate',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $time=$body->time1;
    $lorry_id = $body->lorry_id;
    $lorry2=$body->lorry_id2;
    if($lorry_id!=null||$lorry_id!=""){
        if($lorry2!=null||$lorry2!=""){
            $selectStament = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',0)
                ->where('exist', '=', 0)
                ->where('lorry_id', '=', $lorry_id);
            $stmt = $selectStament->execute();
            $data = $stmt->fetch();
            if($data!=null){
                if($data['signtime']==$time) {
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id', '=', 0)
                        ->where('exist', '=', 0)
                        ->where('lorry_id', '=', $lorry2);
                    $stmt = $selectStament->execute();
                    $data2 = $stmt->fetch();
                    if ($data2 != null) {
                        if ($data2['app_chose'] != 1) {
                            $arrays['exist'] = 1;
                            $updateStatement = $database->update($arrays)
                                ->table('lorry')
                                ->where('plate_number', '=', $data2['plate_number'])
                                ->where('driver_phone', '=', $data2['driver_phone'])
                                ->where('driver_name', '=', $data2['driver_name']);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array('result' => '0', 'desc' => '删除成功'));
                        } else {
                            echo json_encode(array('result' => '5', 'desc' => '该车辆为默认车辆不能删除'));
                        }
                    } else {
                        echo json_encode(array('result' => '4', 'desc' => '车辆不存在'));
                    }
                }else{
                    echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '该司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '未选择车辆'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});
//司机个人信息
$app->get('/lorrymessage',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id = $app->request->get("lorry_id");
    $time=$app->request->get("time1");
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',0)
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($data!=null){
            if($time==$data['signtime']) {
                echo json_encode(array('result' => '0', 'desc' => '', 'lorry' => $data));
            }else{
                echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});
//修改车辆信息信息
$app->put('/uplorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id = $body->lorry_id;
    $lorry_id2=$body->lorry_id2;
    $lorry_size=$body->lorry_size;
    $lorry_age=$body->lorry_age;
    $lorry_text=$body->lorry_text;
    $lorry_type=$body->lorry_type;
    $lorry_weight=$body->lorry_weight;
    $time=$body->time1;
    $database=localhost();
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',0)
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($data['signtime']==$time) {
            $selectStament = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', 0)
                ->where('exist', '=', 0)
                ->where('lorry_id', '=', $lorry_id2);
            $stmt = $selectStament->execute();
            $data4 = $stmt->fetch();
            if ($data != null) {
                if ($data4['driver_name'] == $data['driver_name'] && $data4['driver_phone'] == $data['driver_phone']) {
                    $arrays['lorry_size'] = $lorry_size;
                    $arrays['lorry_age'] = $lorry_age;
                    $arrays['lorry_type_id'] = $lorry_type;
                    $arrays['lorry_tx'] = $lorry_text;
                    $arrays['lorry_weight'] = $lorry_weight;
                    $updateStatement = $database->update($arrays)
                        ->table('lorry')
                        ->where('driver_name', '=', $data4['driver_name'])
                        ->where('driver_phone', '=', $data4['driver_phone'])
                        ->where('plate_number', '=', $data4['plate_number']);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
                } else {
                    echo json_encode(array('result' => '3', 'desc' => '车辆不是你的'));
                }
            } else {
                echo json_encode(array('result' => '2', 'desc' => '车辆不存在'));
            }
        }else{
            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});

//ajax修改个人信息
$app->put('/updriver',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id = $body->lorry_id;
    $driver_email=$body->email;
    $driver_identycard=$body->idcard;
    $driver_address=$body->driver_address;
    $pic=$body->pic;
    $lujing=null;
    $time=$body->time1;
    if($pic!=null){
        $base64_image_content = $pic;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/lorry/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . time() . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $lujing = "http://files.uminfo.cn:8000/lorry/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
        $arrays['driving_license']=$lujing;
    }
    $arrays['driver_address']=$driver_address;
    $arrays['driver_identycard']=$driver_identycard;
    $arrays['driver_email']=$driver_email;
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament = $database->select()
            ->from('lorry')
            ->where('tenant_id','=',0)
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id);
        $stmt = $selectStament->execute();
        $data = $stmt->fetch();
        if($data['signtime']==$time) {
            if ($data != null) {
                $updateStatement = $database->update($arrays)
                    ->table('lorry')
                    ->where('driver_name', '=', $data['driver_name'])
                    ->where('driver_phone', '=', $data['driver_phone']);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
            } else {
                echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
            }
        }else{
            echo json_encode(array('result' => '9', 'desc' => '您已经在其他地方登录，请重新登录'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '没有司机id'));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>
