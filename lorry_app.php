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
//app注册司机
$app->post('/addlorry',function()use($app){
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
                      if($data1==null||$data1==""){
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
});

//司机登录方法
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
    if($driver_phone!=null||$driver_phone!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('tenant_id','=',0)
            ->where('driver_phone','=',$driver_phone);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
            if($data['password']==$password){
                 echo json_encode(array('result' => '0', 'desc' => '登录成功','lorry'=>$data));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '密码错误','lorry'=>''));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '该帐号不存在','lorry'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '电话为空','lorry'=>''));
    }
});
//获取司机的清单列表
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
        if($data!=null||$data!=""){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('plate_number','=',$data['plate_number'])
                ->where('driver_phone','=',$data['driver_phone'])
                ->where('driver_name','=',$data['driver_name']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->join('customer','scheduling.receiver_id','=','customer.customer_id','INNER')
                    ->where('lorry_id','=',$data2[$x]['lorry_id'])
                    ->orderBy('scheduling_datetime','desc');
                $stmt=$selectStament->execute();
                $data3=$stmt->fetchAll();
                for($i=0;$i<count($data3);$i++){
                    $arrays1['scheduling_id']=$data3[$i]['scheduling_id'];
                    $arrays1['customer_name']=$data3[$i]['customer_name'];
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
             if($data!=null||$data!=""){
                 $selectStament=$database->select()
                     ->from('scheduling')
                     ->where('exist','=',0)
                     ->where('scheduling_id','=',$schedule_id);
                 $stmt=$selectStament->execute();
                 $data1=$stmt->fetch();
                 if($data1!=null||$data1!=""){
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
                         echo json_encode(array('result' => '0', 'desc' => '','goods'=>$arrays,'customer'=>$arrays2,'count'=>$num));
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
            ->from('goods')
            ->where('exist','=',0)
            ->where('order_id','=',$order_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
            echo json_encode(array('result' => '0', 'desc' => '','order'=>$data));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '运单不存在','order'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '运单号为空','order'=>''));
    }
});

//司机确认
$app->put('/suresch',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $schedule_id = $app->request->get("schedule_id");
    $lorry_id = $app->request->get("lorry_id");
    $database=localhost();
    $arrays['is_sure']=0;
    if($schedule_id!=null||$schedule_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('exist','=',0)
            ->where('scheduling_id','=',$schedule_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
            if($lorry_id!=null||$lorry_id!=""){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('exist','=',0)
                    ->where('tenant_id','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1!=null||$data1!=""){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('plate_number','=',$data1['plate_number'])
                        ->where('driver_phone','=',$data1['driver_phone'])
                        ->where('lorry_id','=',$data['lorry_id'])
                        ->where('driver_name','=',$data1['driver_name']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    if($data2!=null||$data2!="") {
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

//清单交付



$app->run();

function localhost(){
    return connect();
}

?>