<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/19
 * Time: 9:34
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//多租户管理登录
$app->post('/sign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($name!=null||$name!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=','3')
            ->where('username','=',$name);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
            if($data['password']==$password){
                echo json_encode(array('result' => '0', 'desc' => '登录成功',"admin"=>$data['id']));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '密码错误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '用户不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '名字为空'));
    }
});

$app->options('/alterpw',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});


$app->put('/alterpw',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $password1=$body->password1;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    $password2=$body->password2;
    $password3=null;
    $str2=str_split($password2,3);
    for ($x=0;$x<count($str2);$x++){
        $password3.=$str1[$x].$x;
    }
    if($id!=null||$id!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=','3')
            ->where('id','=',$id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['password']==$password){
                $array=array();
                $array['password']=$password3;
                $updateStatement = $database->update($array)
                    ->table('admin')
                    ->where('id','=',$id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result' => '0', 'desc' => '登录成功'));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '旧密码错误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '用户不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => 'id为空'));
    }
});





//获取管理员下租户列表
$app->get('/gettenants',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $admin_id=$app->request->get('adminid');
    if($admin_id!=null||$admin_id!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=','3')
            ->where('id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('tenant_admin')
                ->where('exist','=',0)
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            $os_url="";
            if($data2!=null){
            for($i=0;$i<count($data2);$i++){
                $selectStament=$database->select()
                    ->from('tenant')
//                    ->where('exist','=',0)
                    ->where('tenant_id','=',$data2[$i]['tenant_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$i]['name']=$data3['company'];
                if($data3['nature']==0){
                    $os_url=$data3['os_url'];
                }
            }
                echo json_encode(array('result' => '0', 'desc' =>'','tenants'=>$data2,'os_url'=>$os_url));
            }else{
                echo json_encode(array('result' => '3', 'desc' =>'该管理账号下没有公司'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '管理员账号不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '管理员id为空'));
    }
});



$app->get('/getOrders',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $paymethod=$app->request->get('pay_method');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d',time());
        $time2=$time2.' 23:59:59';
    }else{
        $time2=date("Y-m-d",strtotime("+1 days",strtotime($time2)));
        $time2=$time2.' 00:00:00';
    }
    if($time1==null||$time1==null){
        $time1=date('Y-m-d H:i:s','0');
    }else{
        $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
        $time1=$time1.' 23:59:59';
    }
    if($admin_id!=null||$admin_id!=""){
        if($tenant_id!=null||$tenant_id!=""){
            if($paymethod!=null||$paymethod!=""){
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('goods.tenant_id','=',$tenant_id)
                ->where('orders.tenant_id','=',$tenant_id)
                ->where('orders.order_datetime1','>',$time1)
                ->where('orders.order_datetime1','<',$time2)
                ->where('orders.pay_method','=',$paymethod)
                ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1= $stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('goods_package')
                        ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data1[$i]['tenant_id'])
                        ->where('customer_id','=',$data1[$i]['sender_id']);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data6['pid']);
                    $stmt = $selectStatement->execute();
                    $data8 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data1[$i]['tenant_id'])
                        ->where('customer_id','=',$data1[$i]['receiver_id']);
                    $stmt=$selectStament->execute();
                    $data4=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data4['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data7['pid']);
                    $stmt = $selectStatement->execute();
                    $data9 = $stmt->fetch();
                    $selectStament=$database->select()
                        ->from('inventory_loc')
                        ->where('tenant_id','=',$data1[$i]['tenant_id'])
                        ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                    $stmt=$selectStament->execute();
                    $data5=$stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id', '=', $data1[$i]['order_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','<',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id','DESC');
                    $stmt = $selectStatement->execute();
                    $data11 = $stmt->fetchAll();
                    $selectStatement = $database->select()
                        ->from('orders')
                        ->where('id','>',$data10['id'])
                        ->where('order_id', '=', $data1[$i]['order_id'])
                        ->orderBy('id');
                    $stmt = $selectStatement->execute();
                    $data12 = $stmt->fetchAll();
                    $next_cost='';
                    if($data12!=null){
                        $next_cost=$data12[0]['transfer_cost'];
                    }
                    $is_transfer=null;
                    if($data11!=null){
                        $is_transfer=$data11[0]['is_transfer'];
                    }
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data13 = $stmt->fetch();
                    $data1[$i]['tenant_num']=$data13['tenant_num'];
                    $data1[$i]['next_cost']=$next_cost;
                    $data1[$i]['pre_company']=$is_transfer;
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
            }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }
        }else{
            $array1=array();
            $selectStament=$database->select()
                ->from('tenant_admin')
                ->where('exist','=',0)
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            for($j=0;$j<count($data2);$j++){
               array_push($array1,$data2[$j]['tenant_id']);
            }
            if($paymethod!=null||$paymethod!=""){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->where('orders.pay_method','=',$paymethod)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'管理员id不能为空'));
    }
});


$app->get('/limitOrders',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $paymethod=$app->request->get('pay_method');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    $curr=$app->request->get('curr');
    $curr=(int)$curr-1;
    $perpage=$app->request->get('perpage');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time()+1);
        $time2=$time2.' 23:59:59';
    }else{
        $time2=date("Y-m-d",strtotime("+1 days",strtotime($time2)));
        $time2=$time2.' 00:00:00';
    }
    if($time1==null||$time1==null){
        $time1=date('Y-m-d H:i:s','0');
    }else{
        $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
        $time1=$time1.' 23:59:59';
    }
    if($admin_id!=null||$admin_id!=""){
        if($tenant_id!=null||$tenant_id!=""){
            if($paymethod!=null||$paymethod!=""){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->where('orders.pay_method','=',$paymethod)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_datetime1','DESC')
                    ->limit((int)$perpage, (int)$perpage * (int)$curr);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;

                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_datetime1','DESC')
                    ->limit((int)$perpage, (int)$perpage * (int)$curr);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }
        }else{
            $array1=array();
            $selectStament=$database->select()
                ->from('tenant_admin')
                ->where('exist','=',0)
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            for($j=0;$j<count($data2);$j++){
                array_push($array1,$data2[$j]['tenant_id']);
            }
            if($paymethod!=null||$paymethod!=""){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->where('orders.pay_method','=',$paymethod)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_datetime1','DESC')
                    ->limit((int)$perpage, (int)$perpage * (int)$curr);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_datetime1','DESC')
                    ->limit((int)$perpage, (int)$perpage * (int)$curr);
                $stmt = $selectStatement->execute();
                $data1= $stmt->fetchAll();
                if($data1!=null){
                    for($i=0;$i<count($data1);$i++){
                        $selectStament=$database->select()
                            ->from('goods_package')
                            ->where('goods_package_id','=',$data1[$i]['goods_package_id']);
                        $stmt=$selectStament->execute();
                        $data2=$stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['sender_id']);
                        $stmt=$selectStament->execute();
                        $data3=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data6['pid']);
                        $stmt = $selectStatement->execute();
                        $data8 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('customer_id','=',$data1[$i]['receiver_id']);
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data4['customer_city_id']);
                        $stmt = $selectStatement->execute();
                        $data7 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('province')
                            ->where('id', '=', $data7['pid']);
                        $stmt = $selectStatement->execute();
                        $data9 = $stmt->fetch();
                        $selectStament=$database->select()
                            ->from('inventory_loc')
                            ->where('tenant_id','=',$data1[$i]['tenant_id'])
                            ->where('inventory_loc_id','=',$data1[$i]['inventory_loc_id']);
                        $stmt=$selectStament->execute();
                        $data5=$stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('tenant_id','=',$tenant_id)
                            ->where('order_id', '=', $data1[$i]['order_id']);
                        $stmt = $selectStatement->execute();
                        $data10 = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','<',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id','DESC');
                        $stmt = $selectStatement->execute();
                        $data11 = $stmt->fetchAll();
                        $selectStatement = $database->select()
                            ->from('orders')
                            ->where('id','>',$data10['id'])
                            ->where('order_id', '=', $data1[$i]['order_id'])
                            ->orderBy('id');
                        $stmt = $selectStatement->execute();
                        $data12 = $stmt->fetchAll();
                        $next_cost='';
                        if($data12!=null){
                            $next_cost=$data12[0]['transfer_cost'];
                        }
                        $is_transfer=null;
                        if($data11!=null){
                            $is_transfer=$data11[0]['is_transfer'];
                        }
                        $data1[$i]['next_cost']=$next_cost;
                        $data1[$i]['pre_company']=$is_transfer;
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('tenant_id', '=', $data1[$i]['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data13 = $stmt->fetch();
                        $data1[$i]['tenant_num']=$data13['tenant_num'];
                        $data1[$i]['goods_package']=$data2;
                        $data1[$i]['sender']=$data3;
                        $data1[$i]['sender']['sender_city']=$data6;
                        $data1[$i]['sender']['sender_province']=$data8;
                        $data1[$i]['receiver']=$data4;
                        $data1[$i]['receiver']['receiver_city']=$data7;
                        $data1[$i]['receiver']['receiver_province']=$data9;
                        $data1[$i]['inventory_loc']=$data5;
                    }
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'管理员id不能为空'));
    }
});


$app->get('/getSchedules',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant_id');
    $admin_id=$app->request->get('admin_id');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time()+1);
        $time2=$time2.' 23:59:59';
    }
    if($tenant_id!=null||$tenant_id!=""){
        if($time1!=null||$time1!=''){
            $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
            $time1=$time1.' 23:59:59';
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','>',$time1)
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->orderBy('scheduling_datetime','DESC');
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }else{
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->orderBy('scheduling_datetime','DESC');
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }

            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }
    }else{
        $array1=array();
        $selectStament=$database->select()
            ->from('tenant_admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        for($j=0;$j<count($data2);$j++){
            array_push($array1,$data2[$j]['tenant_id']);
        }
        if($time1!=null||$time1!=''){
            $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
            $time1=$time1.' 23:59:59';
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','>',$time1)
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->orderBy('scheduling_datetime','DESC');
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }
            }
               echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
            }else{
                    $selectStament=$database->select()
                        ->from('scheduling')
                        ->where('scheduling_datetime','<',$time2)
                        ->where('exist','=',0)
                        ->whereIn('tenant_id',$array1)
                        ->orderBy('scheduling_datetime','DESC');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    if($data3!=null) {
                        for ($j = 0; $j < count($data3); $j++) {
                            $selectStament = $database->select()
                                ->from('city')
                                ->where('id', '=', $data3[$j]['send_city_id']);
                            $stmt = $selectStament->execute();
                            $data4 = $stmt->fetch();
                            $data3[$j]['sendcity'] = $data4['name'];
                            $selectStament = $database->select()
                                ->from('city')
                                ->where('id', '=', $data3[$j]['receive_city_id']);
                            $stmt = $selectStament->execute();
                            $data5 = $stmt->fetch();
                            $data3[$j]['receivercity'] = $data5['name'];
                            $selectStament = $database->select()
                                ->from('customer')
                                ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                                ->where('customer_id', '=', $data3[$j]['receiver_id']);
                            $stmt = $selectStament->execute();
                            $data6 = $stmt->fetch();
                            $data3[$j]['receivername'] = $data6['customer_name'];
                            $data3[$j]['receivertel'] = $data6['customer_phone'];
                            $selectStament = $database->select()
                                ->from('lorry')
                                ->where('tenant_id','=',$data3[$j]['tenant_id'])
                                ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                            $stmt = $selectStament->execute();
                            $data7= $stmt->fetch();
                            $data3[$j]['driver_name']=$data7['driver_name'];
                            $data3[$j]['platenumber']=$data7['plate_number'];
                            $data3[$j]['driver_phone']=$data7['driver_phone'];
                            $selectStatement = $database->select()
                                ->sum('order_cost','zon')
                                ->from('schedule_order')
                                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                                ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                                ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                                ->where('orders.pay_method','=',1)
                                ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                            $stmt = $selectStatement->execute();
                            $data1 = $stmt->fetch();
                            $data3[$j]['sum']=$data1['zon'];
                        }
                    }
                echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
            }
    }
});

$app->get('/limitSchedules',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant_id');
    $admin_id=$app->request->get('admin_id');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    $curr=$app->request->get('curr');
    $curr=(int)$curr-1;
    $perpage=$app->request->get('perpage');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time());
        $time2=$time2.' 23:59:59';
    }
    if($tenant_id!=null||$tenant_id!=""){
        if($time1!=null||$time1!=''){
            $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
            $time1=$time1.' 23:59:59';
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','>',$time1)
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->orderBy('scheduling_datetime','DESC')
                ->limit((int)$perpage, (int)$perpage * (int)$curr);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }else{
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->orderBy('scheduling_datetime','DESC')
                ->limit((int)$perpage, (int)$perpage * (int)$curr);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }

            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }
    }else{
        $array1=array();
        $selectStament=$database->select()
            ->from('tenant_admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        for($j=0;$j<count($data2);$j++){
            array_push($array1,$data2[$j]['tenant_id']);
        }
        if($time1!=null||$time1!=''){
            $time1=date("Y-m-d",strtotime("-1 days",strtotime($time1)));
            $time1=$time1.' 23:59:59';
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','>',$time1)
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->orderBy('scheduling_datetime','DESC')
                ->limit((int)$perpage, (int)$perpage * (int)$curr);;
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }else{
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->orderBy('scheduling_datetime','DESC')
                ->limit((int)$perpage, (int)$perpage * (int)$curr);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetchAll();
            if($data3!=null) {
                for ($j = 0; $j < count($data3); $j++) {
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['send_city_id']);
                    $stmt = $selectStament->execute();
                    $data4 = $stmt->fetch();
                    $data3[$j]['sendcity'] = $data4['name'];
                    $selectStament = $database->select()
                        ->from('city')
                        ->where('id', '=', $data3[$j]['receive_city_id']);
                    $stmt = $selectStament->execute();
                    $data5 = $stmt->fetch();
                    $data3[$j]['receivercity'] = $data5['name'];
                    $selectStament = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data3[$j]['tenant_id'])
                        ->where('customer_id', '=', $data3[$j]['receiver_id']);
                    $stmt = $selectStament->execute();
                    $data6 = $stmt->fetch();
                    $data3[$j]['receivername'] = $data6['customer_name'];
                    $data3[$j]['receivertel'] = $data6['customer_phone'];
                    $selectStament = $database->select()
                        ->from('lorry')
                        ->where('tenant_id','=',$data3[$j]['tenant_id'])
                        ->where('lorry_id', '=', $data3[$j]['lorry_id']);
                    $stmt = $selectStament->execute();
                    $data7= $stmt->fetch();
                    $data3[$j]['driver_name']=$data7['driver_name'];
                    $data3[$j]['platenumber']=$data7['plate_number'];
                    $data3[$j]['driver_phone']=$data7['driver_phone'];
                    $selectStatement = $database->select()
                        ->sum('order_cost','zon')
                        ->from('schedule_order')
                        ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                        ->where('schedule_order.schedule_id','=',$data3[$j]['scheduling_id'])
                        ->where('schedule_order.tenant_id', '=',$data3[$j]['tenant_id'])
                        ->where('orders.pay_method','=',1)
                        ->where('orders.tenant_id', '=',$data3[$j]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $data3[$j]['sum']=$data1['zon'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','schedulings'=>$data3));
        }
    }
});


$app->get('/getAgreements',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $a=date('Y',time());
        $b=date('m',time());
        $c=date('d',time())+1;
        $time2=$a.'年'.$b.'月'.$c.'日';
    }else{
        $a=date('Y',strtotime($time2));
        $b=date('m',strtotime($time2));
        $c=date('d',strtotime($time2))+1;
        $time2=$a.'年'.$b.'月'.$c.'日';
    }
    if($tenant_id!=null||$tenant_id!=""){
        if($time1!=null||$time1!=""){
                $e=date('Y',strtotime($time1));
                $f=date('m',strtotime($time1));
                $g=date('d',strtotime($time1))-1;
                $time3=$e.'年'.$f.'月'.$g.'日';
                    $selectStament=$database->select()
                        ->from('agreement')
                        ->where('exist','=',0)
                        ->where('tenant_id','=',$tenant_id)
                        ->where('agreement_time','>',$time3)
                        ->where('agreement_time','<',$time2)
                        ->orderBy('agreement_time','DESC');
                    $stmt=$selectStament->execute();
                    $data1=$stmt->fetchAll();
                    if($data1!=null){
                        for($i=0;$i<count($data1);$i++){
                            $selectStament=$database->select()
                                ->from('lorry')
                                ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                                ->where('tenant_id','=',$data1[$i]['tenant_id']);
                            $stmt=$selectStament->execute();
                            $data2=$stmt->fetch();
                            $data1[$i]['platenumber']=$data2['plate_number'];
                            $data1[$i]['driver_name']=$data2['driver_name'];
                            $data1[$i]['driver_phone']=$data2['driver_phone'];
                        }
                    }
                    echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
            }else{
                    $selectStament=$database->select()
                        ->from('agreement')
                        ->where('exist','=',0)
                        ->where('agreement_time','<',$time2)
                        ->where('tenant_id','=',$tenant_id)
                        ->orderBy('agreement_time','DESC');
                    $stmt=$selectStament->execute();
                    $data1=$stmt->fetchAll();
                    if($data1!=null){
                        for($i=0;$i<count($data1);$i++){
                            $selectStament=$database->select()
                                ->from('lorry')
                                ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                                ->where('tenant_id','=',$data1[$i]['tenant_id']);
                            $stmt=$selectStament->execute();
                            $data2=$stmt->fetch();
                            $data1[$i]['platenumber']=$data2['plate_number'];
                            $data1[$i]['driver_name']=$data2['driver_name'];
                            $data1[$i]['driver_phone']=$data2['driver_phone'];
                        }
                    }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
          }
    }else{
            $array1=array();
              $selectStament=$database->select()
                ->from('tenant_admin')
                ->where('exist','=',0)
               ->where('admin_id','=',$admin_id);
              $stmt=$selectStament->execute();
               $data2=$stmt->fetchAll();
              for($j=0;$j<count($data2);$j++){
                array_push($array1,$data2[$j]['tenant_id']);
             }
        if($time1!=null||$time1!=""){
            $e=date('Y',strtotime($time1));
            $f=date('m',strtotime($time1));
            $g=date('d',strtotime($time1))-1;
            $time3=$e.'年'.$f.'月'.$g.'日';
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->where('agreement_time','>',$time3)
                ->where('agreement_time','<',$time2)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }else{
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->where('agreement_time','<',$time2)
                ->whereIn('tenant_id',$array1)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }
     }
});


$app->get('/limitAgreements',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    $curr=$app->request->get('curr');
    $curr=(int)$curr-1;
    $perpage=$app->request->get('perpage');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $a=date('Y',time());
        $b=date('m',time());
        $c=date('d',time());
        $time2=$a.'年'.$b.'月'.$c.'日';
    }else{
        $a=date('Y',strtotime($time2));
        $b=date('m',strtotime($time2));
        $c=date('d',strtotime($time2))+1;
        $time2=$a.'年'.$b.'月'.$c.'日';
    }

    if($tenant_id!=null||$tenant_id!=""){
        if($time1!=null||$time1!=""){
            $e=date('Y',strtotime($time1));
            $f=date('m',strtotime($time1));
            $g=date('d',strtotime($time1))-1;
            $time3=$e.'年'.$f.'月'.$g.'日';
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_time','>',$time3)
                ->where('agreement_time','<',$time2)
                ->limit((int)$perpage, (int)$perpage * (int)$curr)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }else{
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->where('agreement_time','<',$time2)
                ->where('tenant_id','=',$tenant_id)
                ->limit((int)$perpage, (int)$perpage * (int)$curr)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }
    }else{
        $array1=array();
        $selectStament=$database->select()
            ->from('tenant_admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        for($j=0;$j<count($data2);$j++){
            array_push($array1,$data2[$j]['tenant_id']);
        }
        if($time1!=null||$time1!=""){
            $e=date('Y',strtotime($time1));
            $f=date('m',strtotime($time1));
            $g=date('d',strtotime($time1))-1;
            $time3=$e.'年'.$f.'月'.$g.'日';
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->where('agreement_time','>',$time3)
                ->where('agreement_time','<',$time2)
                ->limit((int)$perpage, (int)$perpage * (int)$curr)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }else{
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->where('agreement_time','<',$time2)
                ->whereIn('tenant_id',$array1)
                ->limit((int)$perpage, (int)$perpage * (int)$curr)
                ->orderBy('agreement_time','DESC');
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            if($data1!=null){
                for($i=0;$i<count($data1);$i++){
                    $selectStament=$database->select()
                        ->from('lorry')
                        ->where('lorry_id','=',$data1[$i]['secondparty_id'])
                        ->where('tenant_id','=',$data1[$i]['tenant_id']);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetch();
                    $data1[$i]['platenumber']=$data2['plate_number'];
                    $data1[$i]['driver_name']=$data2['driver_name'];
                    $data1[$i]['driver_phone']=$data2['driver_phone'];
                }
            }
            echo json_encode(array('result'=>'0','desc'=>'','agreements'=>$data1));
        }
    }
});

////查询运单具体信息
$app->get('/agredet',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $num111=0;
    $num222=0;
    $num333=0;
    $agreement_id=$app->request->get('agreementid');
    if($agreement_id!=null||$agreement_id!=""){
        $selectStament=$database->select()
            ->from('agreement')
            ->where('exist','=',0)
            ->where('agreement_id','=',$agreement_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('tenant')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            $data['company']=$data1['company'];
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$data1['tenant_id'])
                ->where('customer_id','=',$data1['contact_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $data['sname']=$data2['customer_name'];
            $data['stelephone']=$data2['customer_phone'];
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data2['customer_city_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $data['saddress']=$data3['name'].$data2['customer_address'];
            $data['scity']=$data3['name'];
            $selectStament=$database->select()
                ->from('lorry')
                ->where('lorry_id','=',$data['secondparty_id'])
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $data['plate_number']=$data4['plate_number'];
            $data['driver_name']=$data4['driver_name'];
            $data['driver_phone']=$data4['driver_phone'];
            $selectStament=$database->select()
                ->from('agreement_schedule')
                ->where('tenant_id','=',$data['tenant_id'])
                ->where('agreement_id','=',$data['agreement_id']);
            $stmt=$selectStament->execute();
            $data5=$stmt->fetchAll();
           $sum="";
           $num1=0;
           $num2=0;
           $num3=0;
           $num4=0;
           $num5=0;
            $city=array();
            $city2=array();
            for($j=0;$j<count($data5);$j++){
               if($j==count($data5)-1){
                   $sum.=$data5[$j]['scheduling_id'];
               }else{
                   $sum.=$data5[$j]['scheduling_id'].",";
               }

                $selectStament=$database->select()
                    ->from('schedule_order')
                    ->where('tenant_id','=',$data['tenant_id'])
                    ->where('schedule_id','=',$data5[$j]['scheduling_id']);
                $stmt=$selectStament->execute();
                $data6=$stmt->fetchAll();
                $num5+=count($data6);
                $selectStament=$database->select()
                    ->from('scheduling')
                    ->where('tenant_id','=',$data['tenant_id'])
                    ->where('scheduling_id','=',$data5[$j]['scheduling_id']);
                $stmt=$selectStament->execute();
                $data9=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data9['receive_city_id']);
                $stmt=$selectStament->execute();
                $data10=$stmt->fetch();
                array_push($city,$data10['name']);
                $city2=array_values(array_unique($city));
                for($x=0;$x<count($data6);$x++){
                    $selectStament=$database->select()
                        ->from('goods')
                        ->where('tenant_id','=',$data['tenant_id'])
                        ->where('order_id','=',$data6[$x]['order_id']);
                    $stmt=$selectStament->execute();
                    $data8=$stmt->fetch();
                    $num1+=$data8['goods_capacity'];
                    $num2+=$data8['goods_count'];
                    $num3+=$data8['goods_weight'];
                    $num4+=$data8['goods_value'];
                    $sss=(explode('.',$data8['goods_capacity']));
                    if(count($sss)>1) {
                        if ($num111 < strlen($sss[1])) {
                            $num111 = strlen((explode('.', $data8['goods_capacity']))[1]);
                        };
                    }
                    $sss=(explode('.',$data8['goods_weight']));
                    if(count($sss)>1) {
                        if ($num222 < strlen($sss[1])) {
                            $num222 = strlen((explode('.', $data8['goods_weight']))[1]);
                        };
                    }
                    $sss=(explode('.',$data8['goods_value']));
                    if(count($sss)>1) {
                        if ($num333 < strlen($sss[1])) {
                            $num333 = strlen((explode('.', $data8['goods_value']))[1]);
                        };
                    }
                }
            }
            $data['schedules']=$sum;
            $data['ordersize']=$num1;
            $data['ordercountgood']=$num2;
            $num3=sprintf("%.".$num222."f",$num3);
            $data['orderweight']=$num3;
            $num4=sprintf("%.".$num333."f",$num4);
            $data['ordervalue']=$num4;
            $data['ordercount']=$num5;
            $rcity="";
            if(count($city2)>1) {
                $rcity=$city2[0].'，经';
                for ($i = 1; $i < count($city2)-1; $i++) {
                    $rcity.=$city2[$i].',';
                }
                $rcity.=$city2[count($city2)-1];
            }else{
                $rcity=$city2[0];
            }
            $data['cityname']=$rcity;
            echo json_encode(array('result'=>'0','desc'=>'','agree'=>$data));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'合同不存在'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'合同id为空'));
    }
});

$app->get('/getLorrys',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id','DESC');
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        $data2= array_values(array_unset_tt($data2,'driver_phone'));
        if($data2!=null){
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('phone','=',$data2[$x]['driver_phone'])
                    ->where('plate_number','=',$data2[$x]['plate_number'])
                    ->where('name','=',$data2[$x]['driver_name']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$x]['deadweight']=$data3['deadweight'];
                $selectStament=$database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data3['type']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $data2[$x]['typename']=$data4['lorry_type_name'];
                $data2[$x]['applorryid']=$data3['app_lorry_id'];
                $selectStament=$database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data3['length']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data2[$x]['length']=$data5['lorry_length'];
            }
        }

        echo json_encode(array('result'=>'0','desc'=>'','lorrys'=>$data2));
    }else{
        $array1=array();
        $selectStament=$database->select()
            ->from('tenant_admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id)
            ->orderBy('id','DESC');
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        for($j=0;$j<count($data);$j++){
            array_push($array1,$data[$j]['tenant_id']);
        }
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->whereIn('tenant_id',$array1);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        $data2= array_values(array_unset_tt($data2,'driver_phone'));
        if($data2!=null){
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('phone','=',$data2[$x]['driver_phone'])
                    ->where('plate_number','=',$data2[$x]['plate_number'])
                    ->where('name','=',$data2[$x]['driver_name']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$x]['deadweight']=$data3['deadweight'];
                $selectStament=$database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data3['type']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $data2[$x]['typename']=$data4['lorry_type_name'];
                $data2[$x]['applorryid']=$data3['app_lorry_id'];
                $selectStament=$database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data3['length']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data2[$x]['length']=$data5['lorry_length'];
            }
        }

        echo json_encode(array('result'=>'0','desc'=>'','lorrys'=>$data2));
    }
});

$app->get('/limitLorrys',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $curr=$app->request->get('curr');
    $curr=(int)$curr-1;
    $perpage=$app->request->get('perpage');
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id','DESC')
            ->limit((int)$perpage, (int)$perpage * (int)$curr);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        if($data2!=null){
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('phone','=',$data2[$x]['driver_phone'])
                    ->where('plate_number','=',$data2[$x]['plate_number'])
                    ->where('name','=',$data2[$x]['driver_name']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$x]['deadweight']=$data3['deadweight'];
                $selectStament=$database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data3['type']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $data2[$x]['typename']=$data4['lorry_type_name'];
                $data2[$x]['applorryid']=$data3['app_lorry_id'];
                $selectStament=$database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data3['length']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data2[$x]['length']=$data5['lorry_length'];
            }
        }
        echo json_encode(array('result'=>'0','desc'=>'','lorrys'=>$data2));
    }else{
        $array1=array();
        $selectStament=$database->select()
            ->from('tenant_admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        for($j=0;$j<count($data);$j++){
            array_push($array1,$data[$j]['tenant_id']);
        }

        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->whereIn('tenant_id',$array1)
            ->orderBy('id','DESC');
        $stmt=$selectStament->execute();
        $data6=$stmt->fetchAll();
        $data6= array_values(array_unset_tt($data6,'driver_phone'));
        $data2=array();
        if((((int)$curr*(int)$perpage)+(int)$perpage)<=count($data6)){
            for($i=(int)$curr*(int)$perpage;$i<(int)$curr*(int)$perpage+(int)$perpage;$i++){
                array_push($data2,$data6[$i]);
            }
        }else{
            for($j=(int)$curr*(int)$perpage;$j<count($data6);$j++){
                array_push($data2,$data6[$j]);
            }
        }
        if($data2!=null){
            for($x=0;$x<count($data2);$x++){
                $selectStament=$database->select()
                    ->from('app_lorry')
                    ->where('exist','=',0)
                    ->where('phone','=',$data2[$x]['driver_phone'])
                    ->where('plate_number','=',$data2[$x]['plate_number'])
                    ->where('name','=',$data2[$x]['driver_name']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$x]['deadweight']=$data3['deadweight'];
                $selectStament=$database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data3['type']);
                $stmt=$selectStament->execute();
                $data4=$stmt->fetch();
                $data2[$x]['typename']=$data4['lorry_type_name'];
                $data2[$x]['applorryid']=$data3['app_lorry_id'];
                $selectStament=$database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data3['length']);
                $stmt=$selectStament->execute();
                $data5=$stmt->fetch();
                $data2[$x]['length']=$data5['lorry_length'];
            }
        }
       echo json_encode(array('result'=>'0','desc'=>'','lorrys'=>$data2));
    }
});



$app->get('/lorrydetil',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $lorry_id=$app->request->get('id');
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetch();
        $selectStament=$database->select()
            ->from('lorry_type')
            ->where('lorry_type_id','=',$data2['type']);
        $stmt=$selectStament->execute();
        $data3=$stmt->fetch();
        $data2['typename']=$data3['lorry_type_name'];
        $selectStament=$database->select()
            ->from('lorry_length')
            ->where('lorry_length_id','=',$data2['length']);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetch();
         $data2['length']=$data5['lorry_length'];

        echo json_encode(array('result'=>'0','desc'=>'','lorrydetil'=>$data2));
    }else{
        echo json_encode(array('result'=>'3','desc'=>'缺少司机id'));
    }
});


$app->run();

function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项
        if(isset($res[$value[$key]])){
            //有：销毁
            unset($value[$key]);
        }
        else{
            $res[$value[$key]] = $value;
        }
    }
    return $res;
}



function localhost(){
    return connect();
}

?>