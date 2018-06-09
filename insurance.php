<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 14:27
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/userlogin',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $username=$body->username;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
       $password.=$str1[$x].$x;
    }
    if($username!=""||$username!=null){
        $selectStaement=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=',0)
            ->where('username','=',$username);
        $stmt=$selectStaement->execute();
        $data=$stmt->fetch();
        if ($data!=null){
            $selectStaement=$database->select()
                ->from('admin')
                ->where('password','=',$password)
                ->where('exist','=',0)
                ->where('type','=',0)
                ->where('username','=',$username);
            $stmt=$selectStaement->execute();
            $data2=$stmt->fetch();
            if($data2!=null){
                echo json_encode(array('result'=>'0','desc'=>'登录成功','user'=>$data2));
            }else{
                echo json_encode(array('result'=>'3','desc'=>'密码错误','user'=>''));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'用户不存在','user'=>''));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'用户名为空','user'=>''));
    }

});

//客户端,生成去投保险
$app->get('/to_one_insurance',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array2=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $offset=$app->request->get('offset');
    $per_page=$app->request->get('size');
    $selectStatement = $database->select(array('lorry.lorry_id'))
        ->from('scheduling')
        ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
        ->where('scheduling.is_insurance', '=', 1)
//        ->whereIn('scheduling.scheduling_status',array(2,3,4))
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('lorry.tenant_id','=',$tenant_id)
        ->limit((int)$per_page, (int)$offset)
        ->groupBy('lorry.lorry_id');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('lorry_id','=',$data1[$i]['lorry_id'])
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        array_push($array2,$data2);
    }
//    for($i=0;$i<count($data1);$i++){
//        $array1=array();
//        $array1['lorry']=$data1[$i];
//        $selectStatement = $database->select()
//            ->from('scheduling')
//            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
//            ->where('scheduling.is_insurance', '=', 0)
//            ->where('scheduling.scheduling_status','=',1)
//            ->where('scheduling.tenant_id','=',$tenant_id)
//            ->where('lorry.tenant_id','=',$tenant_id)
//            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id']);
//        $stmt = $selectStatement->execute();
//        $data2 = $stmt->fetchAll();
//        if($data2!=null){
//            $array3=array();
//            for($j=0;$j<count($data2);$j++){
//                $selectStatement = $database->select()
//                    ->from('schedule_order')
//                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
//                    ->join('goods','goods.order_id','=','orders.order_id','INNER')
//                    ->where('schedule_order.schedule_id', '=', $data2[$j]['scheduling_id'])
//                    ->where('orders.tenant_id','=',$tenant_id)
//                    ->where('goods.tenant_id','=',$tenant_id)
//                    ->where('schedule_order.tenant_id','=',$tenant_id);
//                $stmt = $selectStatement->execute();
//                $data3 = $stmt->fetchAll();
//                array_push($array3,$data3);
//            }
//            $array1['goods']=$array3;
//            array_push($array2,$array1);
//        }
//    }
    echo json_encode(array('result'=>'1','desc'=>'success','lorrys'=>$array2));
});

//客户端，要做保险车辆总数
$app->get('/lorry_insurance_count',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array2=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select(array('lorry.lorry_id'))
        ->from('scheduling')
        ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
        ->where('scheduling.is_insurance', '=', 1)
//        ->whereIn('scheduling.scheduling_status',array(2,3,4))
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('lorry.tenant_id','=',$tenant_id)
        ->groupBy('lorry.lorry_id');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','count'=>count($data1)));
});

//客户端，确认一个投保,暂不用
$app->post('/one_insurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $insurance_price=$body->insurance_price;
    $transtime=$body->transtime;
    $insurance_amount=$body->insurance_amount;
    date_default_timezone_set("PRC");
    $insurance_start_time=date('Y-m-d H:i:s',time());
    $selectStatement = $database->select()
        ->from('scheduling')
        ->join('schedule_order','schedule_order.schedule_id','=','scheduling.scheduling_id','INNER')
        ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
        ->where('scheduling.is_insurance', '=', 1)
//        ->where('scheduling.scheduling_status','=',2)
        ->where('scheduling.lorry_id','=',$lorry_id)
        ->where('schedule_order.tenant_id','=',$tenant_id)
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('goods.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('insurance')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    $insertStatement = $database->insert(array('insurance_price', 'insurance_lorry_id', 'insurance_start_time', '	tenant_id', 'transtime', 'insurance_amount', 'from_c_id', 'receive_c_id','insurance_id'))
        ->into('insurance')
        ->values(array($insurance_price,$lorry_id, $insurance_start_time, $tenant_id, $transtime, $insurance_amount, $data1[0]['send_city_id'],  $data1[0]['receive_city_id'],(count($data2)+1)));
    $insertId = $insertStatement->execute(false);
//    for($i=0;$i<count($data1);$i++){
//        $updateStatement = $database->update(array('is_insurance'=>2))
//            ->table('scheduling')
//            ->where('scheduling_id','=',$data1[$i]['scheduling_id'])
//            ->where('tenant_id','=',$tenant_id)
//            ->where('exist',"=",0);
//        $affectedRows = $updateStatement->execute();
//        $insertStatement = $database->insert(array('scheduling_id', 'insurance_id','tenant_id'))
//            ->into('insurance_scheduling')
//            ->values(array($data1[$i]['scheduling_id'],(count($data2)+1),$tenant_id));
//        $insertId = $insertStatement->execute(false);
//    }
//    $selectStatement = $database->select()
//        ->from('tenant')
//        ->where('tenant_id','=',$tenant_id)
//        ->where('exist',"=",0);
//    $stmt = $selectStatement->execute();
//    $data2 = $stmt->fetch();
    $updateStatement = $database->update(array('insurance_balance'=>($data2['insurance_balance']-$insurance_amount)))
        ->table('tenant')
        ->where('tenant_id','=',$tenant_id)
        ->where('exist',"=",0);
    $affectedRows = $updateStatement->execute();
    echo json_encode(array('result'=>'1','desc'=>'success'));
});


//客户端，未做保险时，获得该车的货物详情（总的不用）
$app->post('/one_insurance_goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $selectStatement = $database->select()
        ->from('scheduling')
        ->join('schedule_order','schedule_order.schedule_id','=','scheduling.scheduling_id','INNER')
        ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
        ->where('scheduling.is_insurance', '=', 1)
//        ->where('scheduling.scheduling_status','=',2)
        ->where('scheduling.lorry_id','=',$lorry_id)
        ->where('schedule_order.tenant_id','=',$tenant_id)
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('goods.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','insurance'=>$data1));
});

//客户端，获得该车的调度
$app->post('/insurance_scheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $selectStatement = $database->select()
        ->from('scheduling')
        ->where('scheduling.is_insurance', '=', 1)
//        ->whereIn('scheduling.scheduling_status',array(2,3,4))
        ->where('scheduling.lorry_id','=',$lorry_id)
        ->where('scheduling.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['send_city_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data1[$i]['send_city']=$data3['name'];
        $data1[$i]['receive_city']=$data2['name'];
    }
    echo json_encode(array('result'=>'1','desc'=>'success','insurance_scheduling'=>$data1));
});

//客户端，通过该车的调度，获得goods
$app->post('/insurance_scheduling_orders',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $selectStatement = $database->select()
        ->from('schedule_order')
        ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
        ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
        ->join('goods_package','goods.goods_package_id','=','goods_package.goods_package_id','INNER')
        ->where('schedule_order.schedule_id','=',$scheduling_id)
        ->where('goods.tenant_id','=',$tenant_id)
        ->where('orders.tenant_id','=',$tenant_id)
        ->where('schedule_order.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','insurance_scheduling'=>$data1));
});

//客户端，获取保险余额
$app->get('/insurance_balance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    echo json_encode(array('result'=>'1','desc'=>'success','insurance'=>$data1));
});


//客户端，获得历史保险
$app->get('/insurances',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('insurance')
        ->where('tenant_id','=',$tenant_id)
        ->orderBy('insurance.insurance_start_time',"DESC");
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$data1));
});


//客户端，分页获得历史保险
$app->get('/per_insurances',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $selectStatement = $database->select()
        ->from('insurance')
        ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
        ->where('insurance.tenant_id','=',$tenant_id)
        ->where('lorry.tenant_id','=',$tenant_id)
        ->orderBy('insurance.insurance_start_time','DESC')
        ->limit((int)$size,(int)$offset);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$data1));
});

//客户端根据车牌，获得历史保险
$app->get('/insurances_platenumber',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array=array();
    $array1=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $plate_number=$app->request->get("plate_number");
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$tenant_id)
        ->where('plate_number','=',$plate_number);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('insurance_scheduling','insurance_scheduling.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->where('insurance_scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id'])
            ->groupBy('insurance_scheduling.insurance_id');
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetchAll();
//        if($array){
//            array_push($array,$data2);
//        }else{
            $array=$data2;
//        }
    }
//    for($j=0;$j<count($array);$j++){
//        $selectStatement = $database->select()
//            ->from('insurance')
//            ->where('tenant_id','=',$tenant_id)
//            ->where('insurance_id','=',$array[$j]['insurance_id']);
//        $stmt = $selectStatement->execute();
//        $data3= $stmt->fetch();
//            array_push($array1,$data3);
//    }
//    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$array1));
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$array));
});

$app->get('/per_insurances_platenumber',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array=array();
    $array1=array();
    $array2=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $plate_number=$app->request->get("plate_number");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$tenant_id)
        ->where('plate_number','=',$plate_number);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('insurance_scheduling','insurance_scheduling.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->where('insurance_scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id'])
            ->groupBy('insurance_scheduling.insurance_id');
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetchAll();
        if($array){
            array_push($array,$data2);
        }else{
            $array=$data2;
        }
    }
    for($j=0;$j<count($array);$j++){
        $selectStatement = $database->select()
            ->from('insurance')
            ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('insurance.tenant_id','=',$tenant_id)
            ->where('insurance.insurance_id','=',$array[$j]['insurance_id']);
        $stmt = $selectStatement->execute();
        $data3= $stmt->fetch();
            array_push($array1,$data3);
    }
    $num=0;
    if((int)$offset<(count($array1)-$size)){
        $num=(int)$offset;
    }else{
        $num=count($array1);
    }
for ($i =(int)$offset; $i <$num; $i++) {
  array_push($array2,$array1[$i]);
}
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$array2));
});

//客户端，通过调度id修改is_insurance
$app->put('/is_insurance',function()use($app){
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


//通过insuranceid查goods
$app->get('/insurance_goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $insurance_id=$app->request->get('insurance_id');
    $selectStatement = $database->select()
        ->from('insurance')
        ->join('insurance_scheduling','insurance_scheduling.insurance_id','=','insurance.insurance_id','INNER')
        ->join('scheduling','scheduling.scheduling_id','=','insurance_scheduling.scheduling_id','INNER')
        ->where('insurance_scheduling.tenant_id','=',$tenant_id)
        ->where('insurance.tenant_id','=',$tenant_id)
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('insurance.insurance_id','=',$insurance_id)
        ->where('insurance_scheduling.insurance_id','=',$insurance_id)
        ->orderBy('scheduling.scheduling_id');
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['send_city_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data1[$i]['receive_city']=$data2['name'];
        $data1[$i]['send_city']=$data3['name'];
    }
    echo json_encode(array('result'=>'1','desc'=>'success','insurance_goods'=>$data1));
});



$app->run();

function localhost(){
    return connect();
}
?>
