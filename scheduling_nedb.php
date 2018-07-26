<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 14:11
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();



$app->get('/getSchedulings1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('scheduling_status',"!=",10)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['sum']=$data1['zon'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings16',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('is_scan','=',1)
                ->where('tenant_id', '=', $data[$x]['tenant_id'])
                ->where('receiver_id', '=', $data[$x]['customer_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data2[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data2[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data2[$i]['sum']=$data1['zon'];
                array_push($arrays1,$data2[$i]);
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$arrays1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->get('/limitSchedulings6',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $arrays1=array();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($x=0;$x<count($data);$x++){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('is_scan','=',1)
                ->where('tenant_id', '=', $data[$x]['tenant_id'])
                ->where('receiver_id', '=', $data[$x]['customer_id'])
                ->limit((int)$size,(int)$offset)
                ->orderby('change_datetime','DESC');
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('lorry_id', '=', $data2[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['from_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data2[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data2[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data2[$i]['sum']=$data1['zon'];
                $data2[$i]['drivername']=$data3['driver_name'];
                $data2[$i]['driverphone']=$data3['driver_phone'];
                $data2[$i]['platenumber']=$data3['plate_number'];
                $data2[$i]['companyname']=$data4['company'];
                $data2[$i]['jcompany']=$data4['jcompany'];
                $data2[$i]['fromcity']=$data5['name'];
                array_push($arrays1,$data2[$i]);
            }

        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$arrays1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getScheduling',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $scheduling_id=$app->request->get('scheduling_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($scheduling_id!=null||$scheduling_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('tenant_id', '=', $tenant_id)
                ->where('scheduling_id', '=', $scheduling_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$i]['receiver']=$data1;
                $data[$i]['send_city']=$data2;
                $data[$i]['receive_city']=$data3;
                $data[$i]['lorry']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "调度id为空"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "租户id为空"));
    }
});




$app->get('/getSchedulings4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('is_show','=',0)
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});



$app->get('/getSchedulings6',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('scheduling_id','=',$scheduling_id)
            ->where('scheduling_status','!=',10)
            ->where('tenant_id', '=', $tenant_id)
            ->where('exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['sum']=$data1['zon'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings17',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetchAll();
        for($x=0;$x<count($data3);$x++) {
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('is_scan','=',1)
                ->where('scheduling_id', '=', $scheduling_id)
                ->where('tenant_id', '=', $data3[$x]['tenant_id'])
                ->where('exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for ($i = 0; $i < count($data); $i++) {
                $selectStatement = $database->select()
                    ->sum('order_cost', 'zon')
                    ->from('schedule_order')
                    ->join('orders', 'schedule_order.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('schedule_order.schedule_id', '=', $data[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=',$data3[$x]['tenant_id'])
                    ->where('orders.pay_method', '=', 1)
                    ->where('orders.tenant_id', '=', $data3[$x]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data[$i]['sum'] = $data1['zon'];
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});



$app->get('/getSchedulings7',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $receive_city_name=$app->request->get('receive_city_name');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('city','city.id','=','scheduling.receive_city_id','INNER')
            ->where('city.name','=',$receive_city_name)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('scheduling.exist', '=', 0)
            ->where('scheduling.scheduling_status','!=',10);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['sum']=$data1['zon'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});


$app->get('/getSchedulings18',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $from_city_name=$app->request->get('from_city_name');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $datab=array();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        for($x=0;$x<count($data9);$x++) {
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('city','city.id','=','scheduling.send_city_id','INNER')
            ->where('scheduling.is_scan','=',1)
            ->where('city.name','=',$from_city_name)
            ->where('scheduling.tenant_id', '=', $data9[$x]['tenant_id'])
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $data[$i]['tenant_id'])
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data4['from_city_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $data[$i]['tenant_id'])
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=', $data[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['sum']=$data1['zon'];
            $data[$i]['drivername']=$data3['driver_name'];
            $data[$i]['driverphone']=$data3['driver_phone'];
            $data[$i]['platenumber']=$data3['plate_number'];
            $data[$i]['companyname']=$data4['company'];
            $data[$i]['jcompany']=$data4['jcompany'];
            $data[$i]['fromcity']=$data5['name'];
            array_push($datab,$data[$i]);
        }
            $datab=array_values(array_unset_tt($datab,'scheduling_id'));
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});
$app->get('/getSchedulings8',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $plate_number=$app->request->get('plate_number');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.plate_number','=',$plate_number)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->where('scheduling.scheduling_status','!=',10)
            ->where('scheduling.exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['sum']=$data1['zon'];
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});


$app->get('/getSchedulings19',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $plate_number=$app->request->get('plate_number');
    $database = localhost();
    $datab=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        for($x=0;$x<count($data9);$x++) {
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('lorry', 'lorry.lorry_id', '=', 'scheduling.lorry_id', 'INNER')
                ->where('lorry.plate_number', '=', $plate_number)
                ->where('scheduling.is_scan','=',1)
                ->where('lorry.tenant_id', '=', $data9[$x]['tenant_id'])
                ->where('scheduling.tenant_id', '=',$data9[$x]['tenant_id'])
                ->where('scheduling.exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for ($i = 0; $i < count($data); $i++) {
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['from_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data[$i]['sum']=$data1['zon'];
                $data[$i]['drivername']=$data3['driver_name'];
                $data[$i]['driverphone']=$data3['driver_phone'];
                $data[$i]['platenumber']=$data3['plate_number'];
                $data[$i]['companyname']=$data4['company'];
                $data[$i]['jcompany']=$data4['jcompany'];
                $data[$i]['fromcity']=$data5['name'];
                array_push($datab,$data[$i]);
            }
            $datab=array_values(array_unset_tt($datab,'scheduling_id'));
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户id为空"));
    }
});

$app->get('/getSchedulings20',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $is_insurance=$app->request->get('is_insurance');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereNotIn('scheduling_status',array(5,6,8,7,9))
            ->where('is_insurance', '=', $is_insurance)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
                ->where('lorry.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();

            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_city']=$data4;
            $data[$i]['receive_province']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings21',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $is_insurance=$app->request->get('is_insurance');
    $lorry_id=$app->request->get('lorry_id');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id)
            ->whereNotIn('scheduling_status',array(5,6,8,7,9))
            ->where('is_insurance', '=', $is_insurance)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
                ->where('lorry.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();

            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_city']=$data4;
            $data[$i]['receive_province']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings9',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('scheduling_status','!=',10)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $dataa=array();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();

            if($data1['zon']!=0){
               $data[$i]['sum']=$data1['zon'];
              array_push($dataa,$data[$i]);
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$dataa));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings10',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_status=$app->request->get('scheduling_status');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('scheduling_status', '=', $scheduling_status)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getSchedulings11',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(6,8))
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        for($i=0;$i<count($data);$i++){
//            $selectStatement = $database->select()
//                ->from('lorry')
//                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
//                ->where('lorry.tenant_id', '=', $tenant_id);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('app_lorry')
//                ->join('lorry_length','app_lorry.length','=','lorry_length.lorry_length_id','INNER')
//                ->join('lorry_type','app_lorry.type','=','lorry_type.lorry_type_id','INNER')
//                ->where('phone', '=', $data1['driver_phone'])
//                ->where('name', '=', $data1['driver_name'])
//                ->where('plate_number', '=', $data1['plate_number'])
//                ->where('exist','=','0');
//            $stmt = $selectStatement->execute();
//            $data6= $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data[$i]['send_city_id']);
//            $stmt = $selectStatement->execute();
//            $data2 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data2['pid']);
//            $stmt = $selectStatement->execute();
//            $data3 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('city')
//                ->where('id', '=', $data[$i]['receive_city_id']);
//            $stmt = $selectStatement->execute();
//            $data4 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data4['pid']);
//            $stmt = $selectStatement->execute();
//            $data5 = $stmt->fetch();
//            $data[$i]['lorry']=$data1;
//            $data[$i]['app_lorry']=$data6;
//            $data[$i]['send_city']=$data2;
//            $data[$i]['send_province']=$data3;
//            $data[$i]['receive_city']=$data4;
//            $data[$i]['receive_province']=$data5;
//        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings12',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $is_contract=$app->request->get('is_contract');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereNotIn('scheduling_status',array(6,8,7,9))
            ->where('is_contract', '=', $is_contract)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
                ->where('lorry.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();

            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_city']=$data4;
            $data[$i]['receive_province']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getSchedulings13',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $is_contract=$app->request->get('is_contract');
    $lorry_id=$app->request->get('lorry_id');
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('lorry_id', '=', $lorry_id)
            ->whereNotIn('scheduling_status',array(6,8,7,9))
            ->where('is_contract', '=', $is_contract)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
                ->where('lorry.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();

            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['send_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_city']=$data4;
            $data[$i]['receive_province']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/limitSchedulings5',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(6,8))
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
                ->where('lorry.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('app_lorry')
                ->join('lorry_length','app_lorry.length','=','lorry_length.lorry_length_id','INNER')
                ->join('lorry_type','app_lorry.type','=','lorry_type.lorry_type_id','INNER')
                ->where('phone', '=', $data1['driver_phone'])
                ->where('name', '=', $data1['driver_name'])
//                ->where('plate_number', '=', $data1['	plate_number'])
                ->where('exist','=','0');
            $stmt = $selectStatement->execute();
            $data6= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data4['pid']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data[$i]['lorry']=$data1;
            $data[$i]['app_lorry']=$data6;
            $data[$i]['send_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_city']=$data4;
            $data[$i]['receive_province']=$data5;
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulings0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $datab=array();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->whereIn('scheduling_status',array(6,8))
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->whereIn('scheduling_status',array(1,2,3,4))
            ->orderBy('scheduling_status','DESC')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $dataa = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->whereIn('scheduling_status',array(5,7,9))
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $datad = $stmt->fetchAll();
        $data=array_merge($data,$dataa,$datad);
        $num=0;
        if($offset<count($data)&&$offset<(count($data)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($data);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();


            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data6='';
            if($data1['contact_tenant_id']!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data1['contact_tenant_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6=$data7['jcompany'];
            }


            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['receiver']['jcompany']=$data6;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
            $data[$i]['sum']=$data5['zon'];
            array_push($datab,$data[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulings4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(6,8))
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(1,2,3,4))
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('scheduling_status','DESC')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $datac = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('tenant_id', '=', $tenant_id)
            ->whereIn('scheduling_status',array(5,7,9))
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $datad = $stmt->fetchAll();
        $data=array_merge($data,$datac,$datad);
        $dataa=array();
        $datab=array();
        for($j=0;$j<count($data);$j++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$j]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            if($data5['zon']!=0){
                $data[$j]['sum']=$data5['zon'];
                array_push($dataa,$data[$j]);
            }
        }
        $num=0;
        if($offset<count($dataa)&&$offset<(count($dataa)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($dataa);
        }
        for($i=$offset;$i<$num;$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $dataa[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data6='';
                if($data1['contact_tenant_id']!=null){
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->where('tenant_id', '=', $data1['contact_tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data7 = $stmt->fetch();
                    $data6=$data7['jcompany'];
                }
              $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $dataa[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $dataa[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('lorry_id', '=', $dataa[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $dataa[$i]['receiver']=$data1;
                $dataa[$i]['receiver']['jcompany']=$data6;
                $dataa[$i]['send_city']=$data2;
                $dataa[$i]['receive_city']=$data3;
                $dataa[$i]['lorry']=$data4;
                array_push($datab,$dataa[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/limitSchedulings1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
//        $datab=array();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->where('scheduling_id', '=', $scheduling_id)
            ->where('tenant_id', '=', $tenant_id)
            ->where('scheduling_status','!=',10)
            ->orderBy('scheduling_status')
            ->orderBy('scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        $selectStatement = $database->select()
//            ->from('scheduling')
//            ->where('exist', '=', 0)
//            ->where('scheduling_id', '=', $scheduling_id)
//            ->where('tenant_id', '=', $tenant_id)
//            ->whereIn('scheduling_status',array(1,2,3,4))
//            ->orderBy('scheduling_status','DESC')
//            ->orderBy('scheduling_id','DESC');
//        $stmt = $selectStatement->execute();
//        $dataa = $stmt->fetchAll();
//        $selectStatement = $database->select()
//            ->from('scheduling')
//            ->where('exist', '=', 0)
//            ->where('tenant_id', '=', $tenant_id)
//            ->whereIn('scheduling_status',array(5,7,9))
//            ->orderBy('scheduling_status')
//            ->orderBy('scheduling_id','DESC');
//        $stmt = $selectStatement->execute();
//        $datad = $stmt->fetchAll();
//        $data=array_merge($data,$dataa);
//        $num=0;
//        if($offset<count($data)&&$offset<(count($data)-$size)){
//            $num=$offset+$size;
//        }else{
//            $num=count($data);
//        }
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data6='';
            if($data1['contact_tenant_id']!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data1['contact_tenant_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6=$data7['jcompany'];
            }
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['receiver']['jcompany']=$data6;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
            $data[$i]['sum']=$data5['zon'];
//            array_push($datab,$data[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulings7',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $arrays1=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        for($x=0;$x<count($data9);$x++) {
            $selectStatement = $database->select()
                ->from('scheduling')
                ->where('exist', '=', 0)
                ->where('is_scan','=',1)
                ->where('scheduling_id', '=', $scheduling_id)
                ->where('tenant_id', '=', $data9[$x]['tenant_id'])
                ->orderBy('scheduling_status')
                ->orderBy('change_datetime', 'DESC');
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            for($i=0;$i<count($data2);$i++){
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('lorry_id', '=', $data2[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data2[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['from_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data2[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data2[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data2[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data2[$i]['sum']=$data1['zon'];
                $data2[$i]['drivername']=$data3['driver_name'];
                $data2[$i]['driverphone']=$data3['driver_phone'];
                $data2[$i]['platenumber']=$data3['plate_number'];
                $data2[$i]['companyname']=$data4['company'];
                $data2[$i]['jcompany']=$data4['jcompany'];
                $data2[$i]['fromcity']=$data5['name'];
                array_push($arrays1,$data2[$i]);
            }
            $arrays1=array_values(array_unset_tt($arrays1,'scheduling_id'));
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$arrays1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/limitSchedulings8',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $from_city_name=$app->request->get('from_city_name');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        $datab=array();
        for($x=0;$x<count($data9);$x++) {
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('city', 'city.id', '=', 'scheduling.send_city_id', 'INNER')
                ->where('city.name', '=', $from_city_name)
                ->where('scheduling.is_scan','=',1)
                ->where('scheduling.exist', '=', 0)
                ->whereIn('scheduling.scheduling_status', array(6, 8))
                ->where('scheduling.tenant_id', '=', $data9[$x]['tenant_id'])
                ->orderBy('change_datetime', 'DESC');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('city', 'city.id', '=', 'scheduling.send_city_id', 'INNER')
                ->where('city.name', '=', $from_city_name)
                ->where('scheduling.is_scan','=',1)
                ->where('scheduling.exist', '=', 0)
                ->whereIn('scheduling.scheduling_status', array(1, 2, 3, 4))
                ->where('scheduling.tenant_id', '=', $data9[$x]['tenant_id'])
                ->orderBy('change_datetime', 'DESC');
            $stmt = $selectStatement->execute();
            $dataa = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('city', 'city.id', '=', 'scheduling.send_city_id', 'INNER')
                ->where('city.name', '=', $from_city_name)
                ->where('scheduling.exist', '=', 0)
                ->where('scheduling.is_scan','=',1)
                ->whereIn('scheduling.scheduling_status', array(5, 7, 9))
                ->where('scheduling.tenant_id', '=',$data9[$x]['tenant_id'])
                ->orderBy('change_datetime', 'DESC');
            $stmt = $selectStatement->execute();
            $datad = $stmt->fetchAll();
            $data = array_merge($data, $dataa, $datad);
            $num = 0;
            if ($offset < count($data) && $offset < (count($data) - $size)) {
                $num = $offset + $size;
            } else {
                $num = count($data);
            }
            for ($i = $offset; $i < $num; $i++) {
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['from_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data[$i]['sum']=$data1['zon'];
                $data[$i]['drivername']=$data3['driver_name'];
                $data[$i]['driverphone']=$data3['driver_phone'];
                $data[$i]['platenumber']=$data3['plate_number'];
                $data[$i]['companyname']=$data4['company'];
                $data[$i]['jcompany']=$data4['jcompany'];
                $data[$i]['fromcity']=$data5['name'];
                array_push($datab, $data[$i]);
            }
            $datab=array_values(array_unset_tt($datab,'scheduling_id'));
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulings2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $receive_city_name=$app->request->get('receive_city_name');
    if($tenant_id!=null||$tenant_id!=''){
        $datab=array();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('city','city.id','=','scheduling.receive_city_id','INNER')
            ->where('city.name','=',$receive_city_name)
            ->where('scheduling.exist', '=', 0)
            ->whereIn('scheduling.scheduling_status',array(6,8))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_status')
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('city','city.id','=','scheduling.receive_city_id','INNER')
            ->where('city.name','=',$receive_city_name)
            ->where('scheduling.exist', '=', 0)
            ->whereIn('scheduling.scheduling_status',array(1,2,3,4))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_status','DESC')
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $dataa = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('city','city.id','=','scheduling.receive_city_id','INNER')
            ->where('city.name','=',$receive_city_name)
            ->where('scheduling.exist', '=', 0)
            ->whereIn('scheduling.scheduling_status',array(5,7,9))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_status')
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $datad = $stmt->fetchAll();
        $data=array_merge($data,$dataa,$datad);
        $num=0;
        if($offset<count($data)&&$offset<(count($data)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($data);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();


            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data6='';
            if($data1['contact_tenant_id']!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data1['contact_tenant_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6=$data7['jcompany'];
            }


            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['receiver']['jcompany']=$data6;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
            $data[$i]['sum']=$data5['zon'];
            array_push($datab,$data[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});




$app->get('/limitSchedulings3',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $plate_number=$app->request->get('plate_number');
    if($tenant_id!=null||$tenant_id!=''){
        $datab=array();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.plate_number','=',$plate_number)
            ->where('scheduling.exist', '=', 0)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->whereIn('scheduling.scheduling_status',array(6,8))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_status')
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();

        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.plate_number','=',$plate_number)
            ->where('scheduling.exist', '=', 0)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->whereIn('scheduling.scheduling_status',array(1,2,3,4))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_status','DESC')
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $dataa = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('lorry.plate_number','=',$plate_number)
            ->where('scheduling.exist', '=', 0)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->whereIn('scheduling.scheduling_status',array(5,7,9))
            ->where('scheduling.tenant_id', '=', $tenant_id)
            ->orderBy('scheduling.scheduling_id','DESC');
        $stmt = $selectStatement->execute();
        $datad = $stmt->fetchAll();
        $data=array_merge($data,$dataa,$datad);
        $num=0;
        if($offset<count($data)&&$offset<(count($data)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($data);
        }
        for($i=$offset;$i<$num;$i++){
            $selectStatement = $database->select()
                ->sum('order_cost','zon')
                ->from('schedule_order')
                ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                ->where('schedule_order.tenant_id', '=', $tenant_id)
                ->where('orders.pay_method','=',1)
                ->where('orders.tenant_id', '=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();


            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $data[$i]['receiver_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data6='';
            if($data1['contact_tenant_id']!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data1['contact_tenant_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6=$data7['jcompany'];
            }


            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id', '=', $tenant_id)
                ->where('lorry_id', '=', $data[$i]['lorry_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['receiver']=$data1;
            $data[$i]['receiver']['jcompany']=$data6;
            $data[$i]['send_city']=$data2;
            $data[$i]['receive_city']=$data3;
            $data[$i]['lorry']=$data4;
            $data[$i]['sum']=$data5['zon'];
            array_push($datab,$data[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitSchedulings9',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $plate_number=$app->request->get('plate_number');
    if($tenant_id!=null||$tenant_id!=''){
        $datab=array();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('contact_tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        for($x=0;$x<count($data9);$x++) {
            $selectStatement = $database->select()
                ->from('scheduling')
                ->join('lorry', 'lorry.lorry_id', '=', 'scheduling.lorry_id', 'INNER')
                ->where('lorry.plate_number', '=', $plate_number)
                ->where('scheduling.exist', '=', 0)
                ->where('scheduling.is_scan', '=', 1)
                ->where('lorry.tenant_id', '=', $data9[$x]['tenant_id'])
                ->where('scheduling.tenant_id', '=', $data9[$x]['tenant_id'])
                ->orderBy('change_datetime', 'DESC')
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for ($i = 0; $i < count($data); $i++) {
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data4['from_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->sum('order_cost','zon')
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id','=',$data[$i]['scheduling_id'])
                    ->where('schedule_order.tenant_id', '=', $data[$i]['tenant_id'])
                    ->where('orders.pay_method','=',1)
                    ->where('orders.tenant_id', '=', $data[$i]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $data[$i]['sum']=$data1['zon'];
                $data[$i]['drivername']=$data3['driver_name'];
                $data[$i]['driverphone']=$data3['driver_phone'];
                $data[$i]['platenumber']=$data3['plate_number'];
                $data[$i]['companyname']=$data4['company'];
                $data[$i]['jcompany']=$data4['jcompany'];
                $data[$i]['fromcity']=$data5['name'];
                array_push($datab, $data[$i]);
            }
            $datab=array_values(array_unset_tt($datab,'scheduling_id'));
       }
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$datab));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->put('/alterScheduling0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $scheduling_comment=$body->scheduling_comment;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('scheduling_comment'=>$scheduling_comment))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->put('/alterScheduling2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $scheduling_status=$body->scheduling_status;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('scheduling_status'=>$scheduling_status))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});



$app->put('/alterScheduling4',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $is_contract=$body->is_contract;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_contract'=>$is_contract))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('scheduling_id','=',$scheduling_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->put('/alterScheduling10',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $is_insurance=$body->is_insurance;
    $scheduling_id=$body->scheduling_id;
    $updateStatement = $database->update(array('is_insurance'=>$is_insurance))
        ->table('scheduling')
        ->where('scheduling_id','=',$scheduling_id)
        ->where('tenant_id','=',$tenant_id)
        ->where('exist',"=",0);
    $affectedRows = $updateStatement->execute();
    if($affectedRows>0){
        echo json_encode(array('result'=>'1','desc'=>'success'));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'false'));
    }
});





$app->put('/alterSchedulings1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_contract'=>1))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('is_contract','=',3)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterSchedulings2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('is_insurance'=>1))
            ->table('scheduling')
            ->where('tenant_id','=',$tenant_id)
            ->where('is_insurance','=',3)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//根据清单id或发站模糊查询清单数
$app->get('/getSchedulings_scheduling_id_or_sendercity',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $id_sendcity=$app->request->get('id_sendcity');
    if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('scheduling')
                ->leftJoin('city','city.id','=','scheduling.send_city_id')
                ->where('scheduling.exist', '=', 0)
                ->where('scheduling.tenant_id', '=', $tenant_id)
                ->whereLike('scheduling.scheduling_id','%'.$id_sendcity.'%')
                ->orWhereLike('city.name','%'.$id_sendcity.'%');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data[$i]['receiver_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('lorry_id', '=', $data[$i]['lorry_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$i]['receiver']=$data1;
                $data[$i]['send_city']=$data2;
                $data[$i]['receive_city']=$data3;
                $data[$i]['lorry']=$data4;
            }
            echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "租户id为空"));
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