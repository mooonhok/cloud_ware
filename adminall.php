<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 15:22
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/sign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
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
            ->whereIn('type',array(1,4))
            ->where('username','=',$name);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
           if($data['password']==$password){
               echo json_encode(array('result' => '0', 'desc' => '登录成功',"admin"=>$data));
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

$app->get('/schs',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $scheduling_id=$app->request->get('scheduling_id');
    if($page==null||$per_page==null){
        $selectStament=$database->select()
            ->from('scheduling')
            ->orderBy('scheduling_id','DESC');
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data[$i]['send_city_id']);
            $stmt=$selectStament->execute();
            $datam=$stmt->fetch();
            $data[$i]['send_city']=$datam['name'];
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt=$selectStament->execute();
            $dataf=$stmt->fetch();
            $data[$i]['receive_city']=$dataf['name'];
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('customer_id','=',$data[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $data[$i]['customer_id']=$data2['customer_id'];
            $data[$i]['customer_name']=$data2['customer_name'];
            $data[$i]['customer_phone']=$data2['customer_phone'];
            $selectStament=$database->select()
                ->from('tenant')
                ->where('tenant_id','=',$data[$i]['tenant_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data3['from_city_id']);
            $stmt=$selectStament->execute();
            $datasushu=$stmt->fetch();
            $data[$i]['tenant_id']=$data3['tenant_id'];
            $data[$i]['company']=$data3['company'];
            $data[$i]['address']=$data3['address'];
            $data[$i]['fromcity']=$datasushu['name'];
            $selectStament=$database->select()
                ->from('customer')
                ->where('customer_id','=',$data3['contact_id']);
            $stmt=$selectStament->execute();
            $data32=$stmt->fetch();
            $data[$i]['contact_id']=$data3['contact_id'];
            $data[$i]['contant_name']=$data32['customer_name'];
            $data[$i]['contant_tel']=$data32['customer_phone'];
            $selectStament=$database->select()
                ->from('lorry')
                ->where('lorry_id','=',$data[$i]['lorry_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $data[$i]['lorry_id']=$data4['lorry_id'];
            $data[$i]['plate_number']=$data4['plate_number'];
            $data[$i]['driver_name']=$data4['driver_name'];
            $data[$i]['driver_phone']=$data4['driver_phone'];
                $selectStament = $database->select()
                    ->from('schedule_order')
                    ->where('schedule_id', '=', $data[$i]['scheduling_id']);
                $stmt = $selectStament->execute();
                $data5 = $stmt->fetchAll();
                if ($data5 != null) {
                    for ($x = 0; $x < count($data5); $x++) {
                        $selectStament = $database->select()
                            ->from('orders')
                            ->where('order_id', '=', $data5[$x]['order_id']);
                        $stmt = $selectStament->execute();
                        $data6 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data6['sender_id']);
                        $stmt = $selectStament->execute();
                        $data62 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('city')
                            ->where('id', '=', $data62['customer_city_id']);
                        $stmt = $selectStament->execute();
                        $data622 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data6['receiver_id']);
                        $stmt = $selectStament->execute();
                        $data63 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('city')
                            ->where('id', '=', $data63['customer_city_id']);
                        $stmt = $selectStament->execute();
                        $data632 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('goods')
                            ->where('order_id', '=', $data5[$x]['order_id']);
                        $stmt = $selectStament->execute();
                        $data7 = $stmt->fetch();
                        $data5[$x]['order_id'] = $data6['order_id'];
                        $data5[$x]['sender_id'] = $data62['customer_id'];
                        $data5[$x]['sender_phone'] = $data62['customer_phone'];
                        $data5[$x]['sender_name'] = $data62['customer_name'];
                        $data5[$x]['sendcity'] = $data622['name'];
                        $data5[$x]['receiver_id'] = $data63['customer_id'];
                        $data5[$x]['receiver_name'] = $data63['customer_name'];
                        $data5[$x]['receiver_phone'] = $data63['customer_phone'];
                        $data5[$x]['receiver_city'] = $data632['name'];
                        $data5[$x]['goods_id'] = $data7['goods_id'];
                        $data5[$x]['goods_name'] = $data7['goods_name'];
                    }
                    $data[$i]['schedule_orders']=$data5;
                }
        }
        echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$data,'count'=>''));
    }else{
        $page=(int)$page-1;
        $selectStament=$database->select()
            ->from('scheduling')
            ->orderBy('scheduling_id');
        $stmt=$selectStament->execute();
        $datan=$stmt->fetchAll();
        $num=count($datan);
        $selectStament=$database->select()
            ->from('scheduling')
            ->limit((int)$per_page, (int)$per_page * (int)$page)
            ->orderBy('scheduling_id');
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();

        for($i=0;$i<count($data);$i++){
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data[$i]['send_city_id']);
            $stmt=$selectStament->execute();
            $datam=$stmt->fetch();
            $data[$i]['send_city']=$datam['name'];
            $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt=$selectStament->execute();
            $dataf=$stmt->fetch();
            $data[$i]['receive_city']=$dataf['name'];
            $selectStament=$database->select()
                ->from('customer')
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('customer_id','=',$data[$i]['receiver_id']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetch();
            $data[$i]['customer_id']=$data2['customer_id'];
            $data[$i]['customer_name']=$data2['customer_name'];
            $data[$i]['customer_phone']=$data2['customer_phone'];
            $selectStament=$database->select()
                ->from('tenant')
                ->where('tenant_id','=',$data[$i]['tenant_id']);
            $stmt=$selectStament->execute();
            $data3=$stmt->fetch();
            $data[$i]['tenant_id']=$data3['tenant_id'];
            $data[$i]['company']=$data3['company'];
            $data[$i]['address']=$data3['address'];
            $selectStament=$database->select()
                ->from('customer')
                ->where('customer_id','=',$data3['contact_id']);
            $stmt=$selectStament->execute();
            $data32=$stmt->fetch();
            $data[$i]['contact_id']=$data3['contact_id'];
            $data[$i]['contant_name']=$data32['customer_name'];
            $data[$i]['contant_tel']=$data32['customer_phone'];
            $selectStament=$database->select()
                ->from('lorry')
                ->where('lorry_id','=',$data[$i]['lorry_id']);
            $stmt=$selectStament->execute();
            $data4=$stmt->fetch();
            $data[$i]['lorry_id']=$data4['lorry_id'];
            $data[$i]['plate_number']=$data4['plate_number'];
            $data[$i]['driver_name']=$data4['driver_name'];
            $data[$i]['driver_phone']=$data4['driver_phone'];
            if($data!=null) {
                $selectStament = $database->select()
                    ->from('schedule_order')
                    ->where('schedule_id', '=', $data[$i]['scheduling_id']);
                $stmt = $selectStament->execute();
                $data5 = $stmt->fetchAll();
                if ($data5 != null) {
                    for ($x = 0; $x < count($data5); $x++) {
                        $selectStament = $database->select()
                            ->from('orders')
                            ->where('order_id', '=', $data5[$x]['order_id']);
                        $stmt = $selectStament->execute();
                        $data6 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data6['sender_id']);
                        $stmt = $selectStament->execute();
                        $data62 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('city')
                            ->where('id', '=', $data62['customer_city_id']);
                        $stmt = $selectStament->execute();
                        $data622 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data6['receiver_id']);
                        $stmt = $selectStament->execute();
                        $data63 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('city')
                            ->where('id', '=', $data63['customer_city_id']);
                        $stmt = $selectStament->execute();
                        $data632 = $stmt->fetch();
                        $selectStament = $database->select()
                            ->from('goods')
                            ->where('order_id', '=', $data5[$x]['order_id']);
                        $stmt = $selectStament->execute();
                        $data7 = $stmt->fetch();
                        $data5[$x]['order_id'] = $data6['order_id'];
                        $data5[$x]['sender_id'] = $data62['customer_id'];
                        $data5[$x]['sender_phone'] = $data62['customer_phone'];
                        $data5[$x]['sender_name'] = $data62['customer_name'];
                        $data5[$x]['sendcity'] = $data622['name'];
                        $data5[$x]['receiver_id'] = $data63['customer_id'];
                        $data5[$x]['receiver_name'] = $data63['customer_name'];
                        $data5[$x]['receiver_phone'] = $data63['customer_phone'];
                        $data5[$x]['receiver_city'] = $data632['name'];
                        $data5[$x]['goods_id'] = $data7['goods_id'];
                        $data5[$x]['goods_name'] = $data7['goods_name'];
                    }
                    $data[$i]['schedule_orders']=$data5;
                }
            }
        }
        echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$data,'count'=>$num));
    }
});
//根据id号拿信息
$app->get('/dbadmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id= $app->request->get("id");
    $database=localhost();
    $selectStament=$database->select()
        ->from('insurance_scheduling')
        ->where('id','=',$id);
    $stmt=$selectStament->execute();
    $dataa=$stmt->fetch();
    $array=array();
    $selectStament=$database->select()
        ->from('scheduling')
        ->where('scheduling_id','=',$dataa['scheduling_id']);
    $stmt=$selectStament->execute();
    $data=$stmt->fetch();
    $selectStament=$database->select()
        ->from('insurance_scheduling')
        ->where('tenant_id','=',$data['tenant_id'])
        ->where('scheduling_id','=',$dataa['scheduling_id']);
    $stmt=$selectStament->execute();
    $data1a=$stmt->fetch();
    $selectStament=$database->select()
        ->from('insurance')
        ->where('tenant_id','=',$data1a['tenant_id'])
        ->where('insurance_id','=',$data1a['insurance_id']);
    $stmt=$selectStament->execute();
    $data2a=$stmt->fetch();
    $data['transtime']=$data2a['transtime'];
    $selectStament=$database->select()
        ->from('city')
        ->where('id','=',$data['send_city_id']);
    $stmt=$selectStament->execute();
    $datam=$stmt->fetch();
    $data['send_city']=$datam['name'];
    $selectStament=$database->select()
        ->from('city')
        ->where('id','=',$data['receive_city_id']);
    $stmt=$selectStament->execute();
    $dataf=$stmt->fetch();
    $data['receive_city']=$dataf['name'];
    $selectStament=$database->select()
        ->from('customer')
        ->where('tenant_id','=',$data['tenant_id'])
        ->where('customer_id','=',$data['receiver_id']);
    $stmt=$selectStament->execute();
    $data2=$stmt->fetch();
    $data['customer_id']=$data2['customer_id'];
    $data['customer_name']=$data2['customer_name'];
    $data['customer_phone']=$data2['customer_phone'];
    $data['customer_address']=$data2['customer_address'];
    $selectStament=$database->select()
        ->from('tenant')
        ->where('tenant_id','=',$data['tenant_id']);
    $stmt=$selectStament->execute();
    $data3=$stmt->fetch();
    $data['tenant_id']=$data3['tenant_id'];
    $data['company']=$data3['company'];
    $data['address']=$data3['address'];
    $selectStament=$database->select()
        ->from('customer')
        ->where('customer_id','=',$data3['contact_id']);
    $stmt=$selectStament->execute();
    $data32=$stmt->fetch();
    $data['contact_id']=$data3['contact_id'];
    $data['contant_name']=$data32['customer_name'];
    $data['contant_tel']=$data32['customer_phone'];
    $selectStament=$database->select()
        ->from('lorry')
        ->where('lorry_id','=',$data['lorry_id']);
    $stmt=$selectStament->execute();
    $data4=$stmt->fetch();
    $data['lorry_id']=$data4['lorry_id'];
    $data['plate_number']=$data4['plate_number'];
    $data['driver_name']=$data4['driver_name'];
    $data['driver_phone']=$data4['driver_phone'];
    if($data!=null){
        $selectStament=$database->select()
            ->from('schedule_order')
            ->where('schedule_id','=',$dataa['scheduling_id']);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetchAll();
        if($data5!=null){
            for($x=0;$x<count($data5);$x++){
                $selectStament=$database->select()
                    ->from('orders')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data6=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['sender_id']);
                $stmt=$selectStament->execute();
                $data62=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data62['customer_city_id']);
                $stmt=$selectStament->execute();
                $data622=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['receiver_id']);
                $stmt=$selectStament->execute();
                $data63=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data63['customer_city_id']);
                $stmt=$selectStament->execute();
                $data632=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('goods')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data7=$stmt->fetch();
                $data5[$x]['order_id'] = $data6['order_id'];
                $data5[$x]['sender_id'] = $data62['customer_id'];
                $data5[$x]['sender_phone'] = $data62['customer_phone'];
                $data5[$x]['sender_name'] = $data62['customer_name'];
                $data5[$x]['sendcity'] = $data622['name'];
                $data5[$x]['receiver_id'] = $data63['customer_id'];
                $data5[$x]['receiver_name'] = $data63['customer_name'];
                $data5[$x]['receiver_phone'] = $data63['customer_phone'];
                $data5[$x]['receiver_city'] = $data632['name'];
                $data5[$x]['goods_id'] = $data7['goods_id'];
                $data5[$x]['goods_name'] = $data7['goods_name'];
                $data5[$x]['goods_weight']=$data7['goods_weight'];
                $data5[$x]['goods_capacity']=$data7['goods_capacity'];
                $data5[$x]['goods_count']=$data7['goods_count'];
                $data5[$x]['goods_value']=$data7['goods_value'];
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data7['goods_package_id']);
                $stmt=$selectStament->execute();
                $data8=$stmt->fetch();
                $data5[$x]['goods_package']=$data8['goods_package'];
            }
            $data['schedule_orders']=$data5;
            echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$data));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单没有关联运单'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单不存在'));
    }
});
//根据清单号
$app->get('/dbadmin1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $scheduling_id= $app->request->get("sch_id");
    $database=localhost();
    $array=array();
    $selectStament=$database->select()
        ->from('scheduling')
        ->where('scheduling_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data=$stmt->fetch();
    $selectStament=$database->select()
        ->from('insurance_scheduling')
        ->where('tenant_id','=',$data['tenant_id'])
        ->where('scheduling_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data1a=$stmt->fetch();
    $selectStament=$database->select()
        ->from('insurance')
        ->where('tenant_id','=',$data1a['tenant_id'])
        ->where('insurance_id','=',$data1a['insurance_id']);
    $stmt=$selectStament->execute();
    $data2a=$stmt->fetch();
    $data['transtime']=$data2a['transtime'];
    $selectStament=$database->select()
        ->from('city')
        ->where('id','=',$data['send_city_id']);
    $stmt=$selectStament->execute();
    $datam=$stmt->fetch();
    $data['send_city']=$datam['name'];
    $selectStament=$database->select()
        ->from('city')
        ->where('id','=',$data['receive_city_id']);
    $stmt=$selectStament->execute();
    $dataf=$stmt->fetch();
    $data['receive_city']=$dataf['name'];
    $selectStament=$database->select()
        ->from('customer')
        ->where('tenant_id','=',$data['tenant_id'])
        ->where('customer_id','=',$data['receiver_id']);
    $stmt=$selectStament->execute();
    $data2=$stmt->fetch();
    $data['customer_id']=$data2['customer_id'];
    $data['customer_name']=$data2['customer_name'];
    $data['customer_phone']=$data2['customer_phone'];
    $data['customer_address']=$data2['customer_address'];
    $selectStament=$database->select()
        ->from('tenant')
        ->where('tenant_id','=',$data['tenant_id']);
    $stmt=$selectStament->execute();
    $data3=$stmt->fetch();
    $data['tenant_id']=$data3['tenant_id'];
    $data['company']=$data3['company'];
    $data['address']=$data3['address'];
    $selectStament=$database->select()
        ->from('customer')
        ->where('customer_id','=',$data3['contact_id']);
    $stmt=$selectStament->execute();
    $data32=$stmt->fetch();
    $data['contact_id']=$data3['contact_id'];
    $data['contant_name']=$data32['customer_name'];
    $data['contant_tel']=$data32['customer_phone'];
    $selectStament=$database->select()
        ->from('lorry')
        ->where('lorry_id','=',$data['lorry_id']);
    $stmt=$selectStament->execute();
    $data4=$stmt->fetch();
    $data['lorry_id']=$data4['lorry_id'];
    $data['plate_number']=$data4['plate_number'];
    $data['driver_name']=$data4['driver_name'];
    $data['driver_phone']=$data4['driver_phone'];
    if($data!=null){
        $selectStament=$database->select()
            ->from('schedule_order')
            ->where('schedule_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetchAll();
        if($data5!=null){
            for($x=0;$x<count($data5);$x++){
                $selectStament=$database->select()
                    ->from('orders')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data6=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['sender_id']);
                $stmt=$selectStament->execute();
                $data62=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data62['customer_city_id']);
                $stmt=$selectStament->execute();
                $data622=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['receiver_id']);
                $stmt=$selectStament->execute();
                $data63=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data63['customer_city_id']);
                $stmt=$selectStament->execute();
                $data632=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('goods')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data7=$stmt->fetch();
                $data5[$x]['order_id'] = $data6['order_id'];
                $data5[$x]['sender_id'] = $data62['customer_id'];
                $data5[$x]['sender_phone'] = $data62['customer_phone'];
                $data5[$x]['sender_name'] = $data62['customer_name'];
                $data5[$x]['sendcity'] = $data622['name'];
                $data5[$x]['receiver_id'] = $data63['customer_id'];
                $data5[$x]['receiver_name'] = $data63['customer_name'];
                $data5[$x]['receiver_phone'] = $data63['customer_phone'];
                $data5[$x]['receiver_city'] = $data632['name'];
                $data5[$x]['goods_id'] = $data7['goods_id'];
                $data5[$x]['goods_name'] = $data7['goods_name'];
                $data5[$x]['goods_weight']=$data7['goods_weight'];
                $data5[$x]['goods_capacity']=$data7['goods_capacity'];
                $data5[$x]['goods_count']=$data7['goods_count'];
                $data5[$x]['goods_value']=$data7['goods_value'];
                $selectStament=$database->select()
                    ->from('goods_package')
                    ->where('goods_package_id','=',$data7['goods_package_id']);
                $stmt=$selectStament->execute();
                $data8=$stmt->fetch();
                $data5[$x]['goods_package']=$data8['goods_package'];
            }
            $data['schedule_orders']=$data5;
            echo json_encode(array('result' => '0', 'desc' => '','schedules'=>$data));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单没有关联运单'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单不存在'));
    }
});
//公司信息分页查询
$app->get('/tenants',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $admin_id=$app->request->get('admin_id');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $database=localhost();
    if($admin_id!=null||$admin_id!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('id','=',$admin_id)
            ->whereIn('type',array(1,4));
        $stmt=$selectStament->execute();
        $data15=$stmt->fetch();
        if($data15!=null){
        if($page==null||$per_page==null){
            $selectStament=$database->select()
                ->from('tenant');
            $stmt=$selectStament->execute();
            $data=$stmt->fetchAll();
            $num=count($data);
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data[$x]['tenant_id'])
                        ->where('customer_id','=',$data[$x]['contact_id'])
                        ->where('exist',"=",0);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data2['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $data2['customer_city']=$data3['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data[$x]['from_city_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data[$x]['from_city']=$data4['name'];

                    $data[$x]['customer']=$data2;
                    $selectStament=$database->select()
                        ->from('sales')
                        ->where('id','=',$data[$x]['sales_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $data[$x]['sales_name']=$data11['sales_name'];
                    $data[$x]['sales_phone']=$data11['telephone'];
                    //   array_push($arrayt,$array1);
//                    $selectStatement = $database->select()
//                        ->from('insurance')
//                        ->where('tenant_id','=',$data[$x]['tenant_id']);
//                    $stmt = $selectStatement->execute();
//                    $data6 = $stmt->fetchAll();
//                    for($y=0;$y<count($data6);$y++){
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id','=',$data6[$y]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data7 = $stmt->fetch();
//                        $data6[$y]['from_city']=$data7['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id','=',$data6[$y]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data8 = $stmt->fetch();
//                        $data6[$y]['receive_city']=$data8['name'];
//                        $selectStatement = $database->select()
//                            ->from('lorry')
//                            ->where('lorry_id','=',$data6[$y]['insurance_lorry_id']);
//                        $stmt = $selectStatement->execute();
//                        $data9 = $stmt->fetch();
//                        $data6[$y]['plate_number']=$data9['plate_number'];
//                        $data6[$y]['driver_name']=$data9['driver_name'];
//                        $data6[$y]['driver_phone']=$data9['driver_phone'];
//                    }
//                    $data[$x]['insurance']=$data6;
                    $selectStatement = $database->select()
                        ->from('rechanges_insurance')
                        ->where('tenant_id','=',$data[$x]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetchAll();
                    $data[$x]['rechanges']=$data10;
                }
                echo json_encode(array('result' => '0', 'desc' => '','tenants'=>$data,'count'=>$num));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '尚未有公司'));
            }
        }else {
            $page=(int)$page-1;
            $selectStament=$database->select()
                ->from('tenant');
            $stmt=$selectStament->execute();
            $datan=$stmt->fetchAll();
            $num=count($datan);
            $selectStament=$database->select()
                ->limit((int)$per_page, (int)$per_page * (int)$page)
                ->from('tenant');
            $stmt=$selectStament->execute();
            $data=$stmt->fetchAll();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id','=',$data[$x]['tenant_id'])
                        ->where('customer_id','=',$data[$x]['contact_id'])
                        ->where('exist',"=",0);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data2['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $data2['customer_city']=$data3['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data[$x]['from_city_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data[$x]['from_city']=$data4['name'];

                    $data[$x]['customer']=$data2;
                    $selectStament=$database->select()
                        ->from('sales')
                        ->where('id','=',$data[$x]['sales_id']);
                    $stmt=$selectStament->execute();
                    $data11=$stmt->fetch();
                    $data[$x]['sales_name']=$data11['sales_name'];
                    $data[$x]['sales_phone']=$data11['telephone'];
                    //   array_push($arrayt,$array1);
//                    $selectStatement = $database->select()
//                        ->from('insurance')
//                        ->where('tenant_id','=',$data[$x]['tenant_id']);
//                    $stmt = $selectStatement->execute();
//                    $data6 = $stmt->fetchAll();
//                    for($y=0;$y<count($data6);$y++){
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id','=',$data6[$y]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data7 = $stmt->fetch();
//                        $data6[$y]['from_city']=$data7['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id','=',$data6[$y]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data8 = $stmt->fetch();
//                        $data6[$y]['receive_city']=$data8['name'];
//                        $selectStatement = $database->select()
//                            ->from('lorry')
//                            ->where('lorry_id','=',$data6[$y]['insurance_lorry_id']);
//                        $stmt = $selectStatement->execute();
//                        $data9 = $stmt->fetch();
//                        $data6[$y]['plate_number']=$data9['plate_number'];
//                        $data6[$y]['driver_name']=$data9['driver_name'];
//                        $data6[$y]['driver_phone']=$data9['driver_phone'];
//                    }
//                    $data[$x]['insurance']=$data6;
                    $selectStatement = $database->select()
                        ->from('rechanges_insurance')
                        ->where('tenant_id','=',$data[$x]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data10 = $stmt->fetchAll();
                    $data[$x]['rechanges']=$data10;
                }
                echo json_encode(array('result' => '0', 'desc' => '','tenants'=>$data,'count'=>$num));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '尚未有公司'));
            }
        }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '您没有权限查看此数据'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
    }
});
//修改租户信息
$app->put('/uptenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $admin_id=$body->admin_id;
    $appid=$body->appid;
    $secret=$body->secret;
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $address=$body->address;
    $end_time=$body->note_remain;
    $qq=$body->qq;
    $email=$body->email;
    $arrays=array();
    $array1=array();
    $arrays['address']=$address;
    $arrays['note_remain']=$end_time;
    $arrays['qq']=$qq;
    $arrays['email']=$email;
    $array1['customer_name']=$customer_name;
    $array1['customer_phone']=$customer_phone;
    $arrays['appid']=$appid;
    $arrays['secret']=$secret;
    if($tenant_id!=null||$tenant_id!=""){
         if($admin_id!=null||$admin_id!=""){
             $selectStament=$database->select()
                 ->from('admin')
                 ->whereIn('type',array(1,4))
                 ->where('id','=',$admin_id);
             $stmt=$selectStament->execute();
             $data=$stmt->fetch();
             if($data!=null){
                 $selectStament=$database->select()
                     ->from('tenant')
                     ->where('tenant_id','=',$tenant_id);
                 $stmt=$selectStament->execute();
                 $data2=$stmt->fetch();
                 if($data2!=null){
                     $updateStatement = $database->update($arrays)
                         ->table('tenant')
                         ->where('tenant_id', '=', $tenant_id)
                         ->where('exist','=',0);
                     $affectedRows = $updateStatement->execute();
                     $updateStatement = $database->update($array1)
                         ->table('customer')
                         ->where('customer_id', '=', $data2['contact_id']);
                     $affectedRows = $updateStatement->execute();
                     date_default_timezone_set("PRC");
                     $shijian=date("Y-m-d H:i:s",time());
                     $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                         ->into('operate_admin')
                         ->values(array($admin_id,'tenant',$tenant_id,$tenant_id,$shijian));
                     $insertId = $insertStatement->execute(false);
                     $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                         ->into('operate_admin')
                         ->values(array($admin_id,'customer',$data2['contact_id'],$tenant_id,$shijian));
                     $insertId = $insertStatement->execute(false);
                     echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
                 }else{
                     echo json_encode(array('result' => '4', 'desc' => '租户不存在'));
                 }
              }else{
                 echo json_encode(array('result' => '3', 'desc' => '您没有操作权限'));
             }
         }else{
             echo json_encode(array('result' => '2', 'desc' => '后台管理员不存在'));
         }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未选择公司'));
    }
});
//根据tenant_id查询tenant信息
$app->get('/tenantbyid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id = $app->request->get("tenant_id");
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$data['tenant_id'])
                ->where('customer_id','=',$data['contact_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data2['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data2['customer_city']=$data3['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data['from_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data['from_city']=$data4['name'];

            $data['customer']=$data2;
            //   array_push($arrayt,$array1);
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetchAll();
            for($y=0;$y<count($data6);$y++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['from_c_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6[$y]['from_city']=$data7['name'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['receive_c_id']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $data6[$y]['receive_city']=$data8['name'];
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('lorry_id','=',$data6[$y]['insurance_lorry_id']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $data6[$y]['plate_number']=$data9['plate_number'];
                $data6[$y]['driver_name']=$data9['driver_name'];
                $data6[$y]['driver_phone']=$data9['driver_phone'];
            }
            $data['insurance']=$data6;
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetchAll();
            $data['rechanges']=$data10;
            echo json_encode(array('result' => '0', 'desc' => '','tenants'=>$data));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '公司不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => 'id为空'));
    }
});
//城市和公司名查询tenant信息
$app->get('/tenantbycityaname',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_name = $app->request->get("tenant_name");
    $city_id=$app->request->get("city_id");
    if($tenant_name!=null||$tenant_name!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('from_city_id','=',$city_id)
            ->where('tenant_id','=',$tenant_name);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$data['tenant_id'])
                ->where('customer_id','=',$data['contact_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data2['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data2['customer_city']=$data3['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data['from_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data['from_city']=$data4['name'];
            $data['customer']=$data2;
            //   array_push($arrayt,$array1);
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetchAll();
            for($y=0;$y<count($data6);$y++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['from_c_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6[$y]['from_city']=$data7['name'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['receive_c_id']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $data6[$y]['receive_city']=$data8['name'];
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('lorry_id','=',$data6[$y]['insurance_lorry_id']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $data6[$y]['plate_number']=$data9['plate_number'];
                $data6[$y]['driver_name']=$data9['driver_name'];
                $data6[$y]['driver_phone']=$data9['driver_phone'];
            }
            $data['insurance']=$data6;
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetchAll();
            $data['rechanges']=$data10;
            echo json_encode(array('result' => '0', 'desc' => '','tenants'=>$data));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '公司不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => 'id为空'));
    }
});
//依据统计公司数量在使用和不在使用（有city_id和无city_id）
$app->get('/countbycity',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $city_id=$app->request->get('city_id');
    if($city_id!=null||$city_id!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('from_city_id','=',$city_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',1)
            ->where('from_city_id','=',$city_id);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
        echo json_encode(array('result' => '0', 'desc' => '','count1'=>count($data),'count2'=>count($data2)));
    }else{
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',0);
        $stmt=$selectStament->execute();
        $data3=$stmt->fetchAll();
        $selectStament=$database->select()
            ->from('tenant')
            ->where('exist','=',1);
        $stmt=$selectStament->execute();
        $data4=$stmt->fetchAll();
        echo json_encode(array('result' => '0', 'desc' => '','count1'=>count($data3),'count2'=>count($data4)));
    }
});

//根据tenant_id查询保险记录分页
$app->get('/lastinsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $database = localhost();
    if($per_page==null||$page==null) {
        $page=(int)$page-1;
        $arrays = array();
//        if ($tenant_id != null || $tenant_id != "") {
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant','tenant.tenant_id','=','insurance.tenant_id','INNER')
                    ->whereLike('insurance.tenant_id','%'.$tenant_id.'%')
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                $num=count($data2);
                //  $arrays['count']=ceil($num/(int)$per_page);
                if ($data2 != null || $data2 != "") {
                    for ($i = 0; $i < count($data2); $i++) {
                        $arrays1['tenant_id']= $data2[$i]['tenant_id'];
                        $arrays1['company'] =  $data2[$i]['company'];
                        $arrays1['insurance_start_time'] = $data2[$i]['insurance_start_time'];
                        $arrays1['transtime'] = $data2[$i]['transtime'];
//                        $arrays1['duration'] = $data2[$i]['duration'];
                        $arrays1['insurance_amount'] = $data2[$i]['insurance_amount'];
                        $arrays1['insurance_price'] = $data2[$i]['insurance_price'];
                        $arrays1['insurance_id'] = $data2[$i]['insurance_id'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data2[$i]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['from_city'] = $data3['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data2[$i]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data4 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data4['name'];
                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
                            ->where('lorry_id', '=', $data2[$i]['insurance_lorry_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $arrays1['plate_number'] = $data5['plate_number'];
                        $arrays1['driver_name'] = $data5['driver_name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data2[$i]['contact_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $arrays1['customer_phone'] = $data6['customer_phone'];
//                        $arrays1['goods_name'] = $data2[$i]['g_type'];
                        array_push($arrays, $arrays1);
                    }
                    echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $arrays,'count'=>$num));
                } else {
                    echo json_encode(array('result' => '3', 'desc' => '该公司无历史保单', 'rechanges' => ''));
                }
//        } else {
//            echo json_encode(array('result' => '1', 'desc' => '租户id为空', 'rechanges' => ''));
//        }
    }else{
        $num111=0;
        $page=(int)$page-1;
        $arrays = array();
//        if ($tenant_id != null || $tenant_id != "") {
//            $selectStatement = $database->select()
//                ->from('tenant')
//                ->where('tenant_id', '=', $tenant_id);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetch();
//            if ($data1 != null || $data1 != "") {
        $selectStatement = $database->select()
            ->from('insurance')
            ->join('tenant','tenant.tenant_id','=','insurance.tenant_id','INNER')
            ->whereLike('insurance.tenant_id', '%'.$tenant_id.'%')
            ->orderBy('insurance.insurance_start_time', 'desc');
        $stmt = $selectStatement->execute();
        $data2a = $stmt->fetchAll();
        $a=0;
        for($x=0;$x<count($data2a);$x++){
            $a+=$data2a[$x]['insurance_amount'];
            $sss=(explode('.',$data2a[$x]['insurance_amount']));
            if(count($sss)>1) {
                if ($num111 < strlen($sss[1])) {
                    $num111 = strlen((explode('.', $data2a[$x]['insurance_amount']))[1]);
                        };
            }
            $a=sprintf("%.".$num111."f",$a);
        }
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant','tenant.tenant_id','=','insurance.tenant_id','INNER')
                    ->whereLike('insurance.tenant_id', '%'.$tenant_id.'%')
                    ->orderBy('insurance.insurance_start_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                $num=count($data2a);
                $sum=ceil($num/(int)$per_page);
//                $selectStatement = $database->select()
//                    ->from('insurance')
//                    ->where('tenant_id', '=', $tenant_id)
//                    ->orderBy('insurance_start_time', 'desc')
//                    ->limit((int)$per_page, (int)$per_page * (int)$page);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetchAll();
                if ($data2 != null || $data2 != "") {
                    for ($i = 0; $i < count($data2); $i++) {
                        $arrays1['tenant_id']= $data2[$i]['tenant_id'];
                        $arrays1['company'] =  $data2[$i]['company'];
                        $arrays1['insurance_start_time'] = $data2[$i]['insurance_start_time'];
//                        $arrays1['duration'] = $data2[$i]['duration'];
                        $arrays1['insurance_amount'] = $data2[$i]['insurance_amount'];
                        $arrays1['insurance_price'] = $data2[$i]['insurance_price'];
                        $arrays1['insurance_id'] = $data2[$i]['insurance_id'];
                        $arrays1['insurance_num'] = $data2[$i]['insurance_num'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data2[$i]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['from_city'] = $data3['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data2[$i]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data4 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data4['name'];
                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('tenant_id','=',$data2[$i]['tenant_id'])
                            ->where('lorry_id', '=', $data2[$i]['insurance_lorry_id']);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();
                        $arrays1['plate_number'] = $data5['plate_number'];
                        $arrays1['driver_name'] = $data5['driver_name'];
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('customer_id', '=', $data2[$i]['contact_id']);
                        $stmt = $selectStatement->execute();
                        $data6 = $stmt->fetch();
                        $arrays1['customer_phone'] = $data6['customer_phone'];
//                        $arrays1['goods_name'] = $data2[$i]['g_type'];
                        array_push($arrays, $arrays1);
                    }
                    echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $arrays,'count'=>$num,'countins'=>$a));
                } else {
                    echo json_encode(array('result' => '3', 'desc' => '该公司无历史保单', 'rechanges' => ''));
                }
//            } else {
//                echo json_encode(array('result' => '2', 'desc' => '该公司不存在', 'rechanges' => ''));
//            }

}
});
//根据tenant_id查询保险充值记录分页
$app->get('/insurance_rechanges',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant_id');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    if($page==null||$per_page==null){
        //  $page=(int)$page-1;
            if ($tenant_id != null || $tenant_id != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.tenant_id', '=', $tenant_id)
                    ->orderBy('rechanges_insurance.sure_time', 'desc');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['tenant_id'] = $data1[$x]['tenant_id'];
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['money'] = $data1[$x]['money'];
                        $arrays1['insurance_balance'] = $data1[$x]['insurance_balance'];
                        $arrays1['status'] = $data1[$x]['status'];
                        $arrays1['rechange_insurance_id'] = $data1[$x]['rechange_insurance_id'];
                        date_default_timezone_set("PRC");
                        $end = date("Y-m-d H:i:s", strtotime("+1 year", strtotime($data1[$x]['sure_time'])));
                        $arrays1['time'] = $data1[$x]['sure_time'] . '到' . $end;
                        array_push($arrays, $arrays1);
                    }
                }
                $num=count($data1);
                //       $sum=ceil($num/(int)$per_page);
                echo json_encode(array('result' => '0', 'desc' =>'', 'insurance_rechanges' => $arrays,'count'=>$num));
            } else {
                echo json_encode(array('result' => '1', 'desc' => '没有输入tenant_id'));
            }
    }else {
        $page=(int)$page-1;
            if ($tenant_id != null || $tenant_id != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.tenant_id', '=', $tenant_id)
                    ->orderBy('rechanges_insurance.sure_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
                //      $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.tenant_id', '=', $tenant_id)
                    ->orderBy('rechanges_insurance.sure_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['tenant_id'] = $data1[$x]['tenant_id'];
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['money'] = $data1[$x]['money'];
                        $arrays1['insurance_balance'] = $data1[$x]['insurance_balance'];
                        $arrays1['status'] = $data1[$x]['status'];
                        $arrays1['rechange_insurance_id'] = $data1[$x]['rechange_insurance_id'];
                        date_default_timezone_set("PRC");
                        $end = date("Y-m-d H:i:s", strtotime("+1 year", strtotime($data1[$x]['sure_time'])));
                        $arrays1['time'] = $data1[$x]['sure_time'] . '到' . $end;
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'insurance_rechanges' => $arrays,'count'=>$num));
            } else {
                echo json_encode(array('result' => '1', 'desc' => '没有tenant_id'));
            }
        }

});

//依据tenant_id保险记录统计数据
$app->get('/insurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id = $app->request->get("tenant_id");
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $num=0;
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            if($data2!=null){

              for($x=0;$x<count($data2);$x++){
                  $num+=$data2[$x]['insurance_price'];
              }
            }
            $sum=0;
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('status','=',1)
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetchAll();
           if($data3!=null){

               for($y=0;$y<count($data3);$y++){
                   $sum+=$data3[$y]['money'];
               }
           }
            $nsum=0;
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('status','=',0)
                ->where('tenant_id','=',$data['tenant_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetchAll();
            if($data4!=null){
                for($i=0;$i<count($data4);$i++){
                    $nsum+=$data4[$i]['money'];
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '','insurancecount'=>$num,'rechangescountsure'=>$sum,'rechangescountnot'=>$nsum,'balance'=>$data['insurance_balance']));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '公司不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => 'id为空'));
    }
});

//查询业务员所有信息
$app->get('/sales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id = $app->request->get("adminid");
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $num=0;
    if($page==null||$page==""){
    if($admin_id!=""||$admin_id!=null){
        $selectStament=$database->select()
            ->from('admin')
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
             if($data['type']==1){
                 $selectStament=$database->select()
                     ->from('sales');
                 $stmt=$selectStament->execute();
                 $data2=$stmt->fetchAll();
                 $num=count($data2);
                 if($data2!=null){
                     echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                 }else{
                     echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                 }
             }else{
                 echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
             }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
    }
    }else{
        $page=(int)$page-1;
        if($admin_id!=""||$admin_id!=null){
            $selectStament=$database->select()
                ->from('admin')
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['type']==1){
                    $selectStament=$database->select()
                        ->from('sales');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    $num=count($data3);
                    $selectStament=$database->select()
                        ->from('sales')
                        ->limit((int)$per_page, (int)$per_page * (int)$page);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    if($data2!=null){
                        echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
        }
    }
});
//修改业务员状态
$app->post('/upsales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $admin_id=$body->admin_id;
    $arrays['exist']=$body->change;
    if($admin_id!=null||$admin_id!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['type']==1){
                $selectStament=$database->select()
                    ->from('sales')
                    ->where('id','=',$sales_id);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                if($data2!=null){
                    $updateStatement = $database->update($arrays)
                        ->table('sales')
                        ->where('id', '=', $sales_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result' => '0', 'desc' => '修改成功'));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '业务员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您没有权限进行操作'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
    }
});

$app->post('/add_user',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $ry_type=$body->type;
    $ry_name=$body->username;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($ry_type!=null||$ry_type!=""){
        if($ry_name!=null||$ry_name!=""){
            $selectStament=$database->select()
                ->from('admin')
                ->where('username','=',$ry_name);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                echo json_encode(array('result' => '3', 'desc' => '管理员名字重复'));
            }else{
                $array['exist']=0;
                $password1='888888';
                $str1=str_split($password1,3);
                $password=null;
                for($x=0;$x<count($str1);$x++){
                    $password.=$str1[$x].$x;
                }
                $array['password']=$password;
                $insertStatement = $database->insert(array_keys($array))
                    ->into('admin')
                    ->values(array_values($array));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array('result' => '0', 'desc' => 'success'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员名字'));
        }
    }else{
        echo json_encode(array('result' => '2', 'desc' => '缺少管理员类型'));
    }
});

//获取contact_company列表
$app->get('/contact_company',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $page=$app->request->get("page");
    $page=$page-1;
    $per_page=$app->request->get("per_page");
    $telephone=$app->request->get('telephone');
    $selectStament=$database->select()
        ->from('contact_company')
        ->orderBy('id','DESC');
    $stmt=$selectStament->execute();
    $data0=$stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('contact_company')
        ->whereLike('telephone','%'.$telephone.'%')
        ->orderBy('id','DESC')
        ->limit((int)$per_page,(int)$per_page * (int)$page);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array("result"=>"0","desc"=>"success",'contact_companys'=>$data1,'count'=>count($data0)));
});

//获取feedback列表
$app->get('/feedback',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $page=$app->request->get("page");
    $page=$page-1;
    $per_page=$app->request->get("per_page");
    $selectStament=$database->select()
        ->from('feedback')
        ->orderBy('id','DESC');
    $stmt=$selectStament->execute();
    $data0=$stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('feedback')
        ->orderBy('id','DESC')
        ->limit((int)$per_page,(int)$per_page * (int)$page);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$data1[$i]['tenant_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('staff')
            ->where('staff_id','=',$data1[$i]['staff_id'])
            ->where('tenant_id','=',$data1[$i]['tenant_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data1[$i]['company_name']=$data2['company'];
        $data1[$i]['staff_name']=$data3['name'];
    }
    echo json_encode(array("result"=>"0","desc"=>"success",'feedbacks'=>$data1,'count'=>count($data0)));
});

$app->get('/get_tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $name=$app->request->get("name");
    $selectStatement = $database->select()
        ->from('tenant')
        ->join('city','city.id','=','tenant.from_city_id','INNER')
        ->where('tenant.exist','=',0)
        ->whereLike('tenant.tenant_id',$name)
        ->orWhereLike('city.name','%'.$name.'%')
        ->orWhereLike('tenant.jcompany','%'.$name.'%');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result"=>"0","desc"=>"success",'tenants'=>$data));
});

$app->get('/operate_admin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get("adminid");
    $selectStament=$database->select()
        ->from('admin')
        ->where('type','=',1)
        ->where('id','=',$admin_id);
    $stmt=$selectStament->execute();
    $data=$stmt->fetch();
    if($data!=null){
        $selectStatement = $database->select()
            ->from('operate_admin');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array("result"=>"0","desc"=>"success",'operate_admins'=>$data1));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"没有权限"));
    }
});


//$app->get('/paixun',function()use($app){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $selectStatement = $database->select()
//        ->from('admin');
//    $stmt = $selectStatement->execute();
//    $data = $stmt->fetchAll();
//    if($data!=null){
//        for($x=0;$x<count($data);$x++){
//            $arrays['id']=(int)count($data)-(int)$x;
//            $selectStatement = $database->select()
//                ->from('admin')
//                ->where('id', '>', count($data))
//                ->orderBy('id','desc')
//                ->limit(1);
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetch();
//            if($data2!=null){
//            $updateStatement = $database->update($arrays)
//                ->table('admin')
//                ->where('id','=',$data2['id']);
//            $affectedRows = $updateStatement->execute();
//            }
//        }
//    }
//    echo json_encode(array("result"=>"1","desc"=>"操作成功"));
//});

$app->get('/get_tenant_boss',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('admin')
        ->where('type','=',3);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result"=>"0","desc"=>"success",'admins'=>$data));
});

$app->get('/get_tenant_son',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('tenant_admin');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    $array=array();
    for($i=0;$i<count($data);$i++){
        if($array){
            array_push($array,$data[$i]['tenant_id']);
        }else{
            $array[0]=$data[$i]['tenant_id'];
        }
    }
    $data=array();
    if($array){
        $selectStatement = $database->select()
            ->from('tenant')
            ->whereNotIn('tenant_id',array_values($array));
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
    }else{
        $selectStatement = $database->select()
            ->from('tenant');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
    }

    echo json_encode(array("result"=>"0","desc"=>"success",'tenants'=>$data));
});

$app->post('/add_tenant_admin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $tenant_id=$body->tenant_id;
    $admin_id=$body->adminid;
    $insertStatement = $database->insert(array('admin_id','tenant_id'))
        ->into('tenant_admin')
        ->values(array($id,$tenant_id));
    $insertId = $insertStatement->execute(false);
    if($insertId){
        $selectStatement = $database->select()
            ->from('tenant_admin');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        date_default_timezone_set("PRC");
        $shijian=date("Y-m-d H:i:s",time());
        $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
            ->into('operate_admin')
            ->values(array($admin_id,'tenant_admin',count($data),-1,$shijian));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息出错"));
    }
});

$app->post('/add_type',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $type=$body->type;
    $admin_id=$body->adminid;
    $selectStatement = $database->select()
        ->from('lorry_type')
        ->where('lorry_type_name','=',$type);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    if($data1){
        echo json_encode(array("result"=>"2","desc"=>"不可重复添加"));
    }else{
        $selectStatement = $database->select()
            ->from('lorry_type');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $insertStatement = $database->insert(array('lorry_type_id','lorry_type_name'))
            ->into('lorry_type')
            ->values(array((count($data)+1),$type));
        $insertId = $insertStatement->execute(false);
        if($insertId){
            date_default_timezone_set("PRC");
            $shijian=date("Y-m-d H:i:s",time());
            $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                ->into('operate_admin')
                ->values(array($admin_id,'lorry_type',(count($data)+1),-1,$shijian));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"信息出错"));
        }
    }

});

$app->post('/add_length',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $length=$body->length;
    $admin_id=$body->adminid;
    $selectStatement = $database->select()
        ->from('lorry_length')
        ->where('lorry_length','=',$length);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    if($data){
        echo json_encode(array("result"=>"2","desc"=>"不可重复添加"));
    }else{
        $selectStatement = $database->select()
            ->from('lorry_length');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $insertStatement = $database->insert(array('lorry_length_id','lorry_length'))
            ->into('lorry_length')
            ->values(array((count($data)+1),$length));
        $insertId = $insertStatement->execute(false);
        if($insertId){
            date_default_timezone_set("PRC");
            $shijian=date("Y-m-d H:i:s",time());
            $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                ->into('operate_admin')
                ->values(array($admin_id,'lorry_length',(count($data)+1),-1,$shijian));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"信息出错"));
        }
    }
});

$app->get('/schedulingdetil',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $num111=0;
    $scheduling_id=$app->request->get('scheduling_id');
    $selectStatement = $database->select()
        ->from('scheduling')
        ->where('scheduling_id','=',$scheduling_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('schedule_order')
        ->where('tenant_id','=',$data1['tenant_id'])
        ->where('schedule_id','=',$scheduling_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$data1['tenant_id'])
        ->where('lorry_id','=',$data1['lorry_id']);
    $stmt = $selectStatement->execute();
    $data3 = $stmt->fetch();
    $num=0;
    for($i=0;$i<count($data2);$i++){
        $selectStatement = $database->select()
            ->from('goods')
            ->where('tenant_id','=',$data2[$i]['tenant_id'])
            ->where('order_id','=',$data2[$i]['order_id']);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        $num+=$data4['goods_value'];
        $sss=(explode('.',$data4['goods_value']));
        if(count($sss)>1) {
            if ($num111 < strlen($sss[1])) {
                $num111 = strlen((explode('.', $data4['goods_value']))[1]);
                        };
        }

        $num=sprintf("%.".$num111."f",$num);
        $data2[$i]['goods_name']=$data4['goods_name'];
        $data2[$i]['goods_count']=$data4['goods_count'];
        $data2[$i]['goods_capacity']=$data4['goods_capacity'];
        $data2[$i]['goods_weight']=$data4['goods_weight'];
        $data2[$i]['need']=$data4['special_need'];
        $data2[$i]['goods_value']=$data4['goods_value'];
        $selectStatement = $database->select()
            ->from('goods_package')
            ->where('goods_package_id','=',$data4['goods_package_id']);
        $stmt = $selectStatement->execute();
        $data5 = $stmt->fetch();
        $data2[$i]['goodpackage']=$data5['goods_package'];
    }
    echo json_encode(array("result"=>"0","desc"=>"success",'order_goods'=>$data2,'lorry'=>$data3,'allgoods'=>$num));
});




$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}

?>