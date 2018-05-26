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

//统计运单
$app->get('/ordertongji',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $tenant_id=$app->request->get('tenant-id');
    $page=$app->request->get('page');
    $perpage=$app->request->get('perpage');
    $paymethod=$app->request->get('payway');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    $page=(int)$page-1;
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time());
    }
    $array1=explode(',',$tenant_id);
    if($paymethod!=null||$paymethod!=""){
    if($tenant_id!=null||$tenant_id!=''){
        if($time1!=null||$time1!="") {
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('orders.order_datetime1','>',$time1)
                ->where('orders.order_datetime1','<',$time2)
                ->whereIn('goods.tenant_id', $array1)
                ->whereIn('orders.tenant_id', $array1)
                ->where('pay_method','=',$paymethod)
                ->limit((int)$perpage, (int)$perpage * (int)$page)
                ->whereNotIn('orders.order_status', array(-1, -2, 0, 6))
                ->where('orders.exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $page=(int)$page-1;
            if ($data != null) {
                $num1 = 0;
                $num2 = 0;
                $num3 = 0;
                for ($i = 0; $i < count($data); $i++) {
                    $num1 += $data[$i]['order_cost'];
                    $num2 += $data[$i]['transfer_cost'];
                    if ($data[$i]['pay_method'] == 1) {
                        $num3 += $data[$i]['order_cost'];
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'countorder' => $num1, 'countorder1' => $num2, 'countorder2' => $num3));
            } else {
                echo json_encode(array('result' => '2', 'desc' => '尚未有数据'));
            }
        }else{
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->where('orders.order_datetime1','<',$time2)
                ->whereIn('goods.tenant_id', $array1)
                ->whereIn('orders.tenant_id', $array1)
                ->where('pay_method','=',$paymethod)
                ->limit((int)$perpage, (int)$perpage * (int)$page)
                ->whereNotIn('orders.order_status', array(-1, -2, 0, 6))
                ->where('orders.exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            if ($data != null) {
                $num1 = 0;
                $num2 = 0;
                $num3 = 0;
                for ($i = 0; $i < count($data); $i++) {
                    $num1 += $data[$i]['order_cost'];
                    $num2 += $data[$i]['transfer_cost'];
                    if ($data[$i]['pay_method'] == 1) {
                        $num3 += $data[$i]['order_cost'];
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'countorder' => $num1, 'countorder1' => $num2, 'countorder2' => $num3));
            } else {
                echo json_encode(array('result' => '2', 'desc' => '尚未有数据'));
            }

        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
    }else{
        if($tenant_id!=null||$tenant_id!=''){
            if($time1!=null||$time1!="") {
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('orders.order_datetime1','>',$time1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereIn('goods.tenant_id', $array1)
                    ->whereIn('orders.tenant_id', $array1)
                    ->limit((int)$perpage, (int)$perpage * (int)$page)
                    ->whereNotIn('orders.order_status', array(-1, -2, 0, 6))
                    ->where('orders.exist', '=', 0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                $page=(int)$page-1;
                if ($data != null) {
                    $num1 = 0;
                    $num2 = 0;
                    $num3 = 0;
                    for ($i = 0; $i < count($data); $i++) {
                        $num1 += $data[$i]['order_cost'];
                        $num2 += $data[$i]['transfer_cost'];
                        if ($data[$i]['pay_method'] == 1) {
                            $num3 += $data[$i]['order_cost'];
                        }
                    }
                    echo json_encode(array('result' => '0', 'desc' => '', 'countorder' => $num1, 'countorder1' => $num2, 'countorder2' => $num3));
                } else {
                    echo json_encode(array('result' => '2', 'desc' => '尚未有数据'));
                }
            }else{
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereIn('goods.tenant_id', $array1)
                    ->whereIn('orders.tenant_id', $array1)
                    ->limit((int)$perpage, (int)$perpage * (int)$page)
                    ->whereNotIn('orders.order_status', array(-1, -2, 0, 6))
                    ->where('orders.exist', '=', 0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                if ($data != null) {
                    $num1 = 0;
                    $num2 = 0;
                    $num3 = 0;
                    for ($i = 0; $i < count($data); $i++) {
                        $num1 += $data[$i]['order_cost'];
                        $num2 += $data[$i]['transfer_cost'];
                        if ($data[$i]['pay_method'] == 1) {
                            $num3 += $data[$i]['order_cost'];
                        }
                    }
                    echo json_encode(array('result' => '0', 'desc' => '', 'countorder' => $num1, 'countorder1' => $num2, 'countorder2' => $num3));
                } else {
                    echo json_encode(array('result' => '2', 'desc' => '尚未有数据'));
                }

            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
        }
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
            if($data2!=null){
            for($i=0;$i<count($data2);$i++){
                $selectStament=$database->select()
                    ->from('tenant')
//                    ->where('exist','=',0)
                    ->where('tenant_id','=',$data2[$i]['tenant_id']);
                $stmt=$selectStament->execute();
                $data3=$stmt->fetch();
                $data2[$i]['name']=$data3['company'];
            }
                echo json_encode(array('result' => '0', 'desc' =>'','tenants'=>$data2));
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
//台账历史运单
$app->get('/getGoodsOrders',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant-id');
    $page=$app->request->get('page');
    $perpage=$app->request->get('perpage');
    $paymethod=$app->request->get('payway');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time());
    }
    $array1=explode(',',$tenant_id);
    if($time1!=null||$time1!=""){
    if($paymethod!=null||$paymethod!=""){
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('orders.order_datetime1','>',$time1)
            ->where('orders.order_datetime1','<',$time2)
            ->whereIn('goods.tenant_id', $array1)
            ->whereIn('orders.tenant_id', $array1)
            ->where('pay_method','=',$paymethod)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $num=count($data);
        $page=(int)$page-1;
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('orders.order_datetime1','>',$time1)
            ->where('orders.order_datetime1','<',$time2)
            ->whereIn('goods.tenant_id', $array1)
            ->whereIn('orders.tenant_id', $array1)
            ->where('pay_method','=',$paymethod)
            ->whereNotIn('orders.order_status',array(-1,-2,0,6))
            ->limit((int)$perpage, (int)$perpage * (int)$page)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
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
            $data1[$i]['goods_package']=$data2;
            $data1[$i]['sender']=$data3;
            $data1[$i]['sender']['sender_city']=$data6;
            $data1[$i]['sender']['sender_province']=$data8;
            $data1[$i]['receiver']=$data4;
            $data1[$i]['receiver']['receiver_city']=$data7;
            $data1[$i]['receiver']['receiver_province']=$data9;
            $data1[$i]['inventory_loc']=$data5;
        }
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1,'count'=>$num));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
    }else{
        if($tenant_id!=null||$tenant_id!=''){
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
            $data = $stmt->fetchAll();
            $num=count($data);
            $page=(int)$page-1;
            $selectStatement = $database->select()
                ->from('orders')
                ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                ->whereIn('goods.tenant_id',$array1)
                ->where('orders.order_datetime1','>',$time1)
                ->where('orders.order_datetime1','<',$time2)
                ->whereIn('orders.tenant_id',$array1)
                ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                ->limit((int)$perpage, (int)$perpage * (int)$page)
                ->where('orders.exist','=',0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
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
                $data1[$i]['goods_package']=$data2;
                $data1[$i]['sender']=$data3;
                $data1[$i]['sender']['sender_city']=$data6;
                $data1[$i]['sender']['sender_province']=$data8;
                $data1[$i]['receiver']=$data4;
                $data1[$i]['receiver']['receiver_city']=$data7;
                $data1[$i]['receiver']['receiver_province']=$data9;
                $data1[$i]['inventory_loc']=$data5;
            }
            echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1,'count'=>$num));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
        }
    }
    }else{
        if($paymethod!=null||$paymethod!=""){
            if($tenant_id!=null||$tenant_id!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('pay_method','=',$paymethod)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                $num=count($data);
                $page=(int)$page-1;
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('pay_method','=',$paymethod)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->limit((int)$perpage, (int)$perpage * (int)$page)
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
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
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1,'count'=>$num));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
            }
        }else{
            if($tenant_id!=null||$tenant_id!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->whereIn('orders.tenant_id',$array1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                $num=count($data);
                $page=(int)$page-1;
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->whereIn('goods.tenant_id',$array1)
                    ->where('orders.order_datetime1','<',$time2)
                    ->whereIn('orders.tenant_id',$array1)
                    ->whereNotIn('orders.order_status',array(-1,-2,0,6))
                    ->limit((int)$perpage, (int)$perpage * (int)$page)
                    ->where('orders.exist','=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
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
                    $data1[$i]['goods_package']=$data2;
                    $data1[$i]['sender']=$data3;
                    $data1[$i]['sender']['sender_city']=$data6;
                    $data1[$i]['sender']['sender_province']=$data8;
                    $data1[$i]['receiver']=$data4;
                    $data1[$i]['receiver']['receiver_city']=$data7;
                    $data1[$i]['receiver']['receiver_province']=$data9;
                    $data1[$i]['inventory_loc']=$data5;
                }
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1,'count'=>$num));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
            }
        }
    }
});
//历史清单列表
$app->get('/lsch',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant-id');
    $page=$app->request->get('page');
    $perpage=$app->request->get('perpage');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $time2=date('Y-m-d H:i:s',time());
    }
    $array1=explode(',',$tenant_id);
    if($time1!=null||$time1!=''){
   if($tenant_id!=null||$tenant_id!=""){
       $page=(int)$page-1;
       $selectStament=$database->select()
           ->from('scheduling')
           ->where('scheduling_datetime','>',$time1)
           ->where('scheduling_datetime','<',$time2)
           ->where('exist','=',0)
           ->whereIn('tenant_id',$array1);
       $stmt=$selectStament->execute();
       $data=$stmt->fetchAll();
       $num=count($data);
    $selectStament=$database->select()
        ->from('scheduling')
        ->where('scheduling_datetime','>',$time1)
        ->where('scheduling_datetime','<',$time2)
        ->where('exist','=',0)
        ->whereIn('tenant_id',$array1)
        ->limit((int)$perpage, (int)$perpage * (int)$page);
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
        }
        echo json_encode(array('result'=>'0','desc'=>'','lschs'=>$data3,'count'=>$num));
    }else{
        echo json_encode(array('result'=>'2','desc'=>'该公司尚未有清单'));
    }
   }else{
       echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
   }
    }else{
        if($tenant_id!=null||$tenant_id!=""){
            $page=(int)$page-1;
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1);
            $stmt=$selectStament->execute();
            $data=$stmt->fetchAll();
            $num=count($data);
            $selectStament=$database->select()
                ->from('scheduling')
                ->where('scheduling_datetime','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->limit((int)$perpage, (int)$perpage * (int)$page);
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
                }
                echo json_encode(array('result'=>'0','desc'=>'','lschs'=>$data3,'count'=>$num));
            }else{
                echo json_encode(array('result'=>'2','desc'=>'该公司尚未有清单'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
        }
    }
});

//合同列表
$app->get('/lagrs',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant-id');
    $page=$app->request->get('page');
    $perpage=$app->request->get('perpage');
    $time1=$app->request->get('time1');
    $time2=$app->request->get('time2');
    date_default_timezone_set("PRC");
    if($time2==null||$time2==""){
        $a=date('Y',time());
        $b=date('m',time());
        $c=date('d',time());
        $time2=$a.'年'.$b.'月'.$c.'日';
    }
    $array1=explode(',',$tenant_id);
    if($time1!=null||$time1!=""){
    if($tenant_id!=null||$tenant_id!=""){
        $e=date('Y',strtotime($time1));
        $f=date('m',strtotime($time1));
        $g=date('d',strtotime($time1));
        $time3=$e.'年'.$f.'月'.$g.'日';
        $selectStament=$database->select()
            ->from('agreement')
            ->where('agreement_time','>',$time3)
            ->where('agreement_time','<',$time2)
            ->where('exist','=',0)
            ->whereIn('tenant_id',$array1);
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        $num=count($data);
        $num2=0;
        if($data!=null){
             $page=(int)$page-1;
            $selectStament=$database->select()
                ->from('agreement')
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1)
                ->where('agreement_time','>',$time3)
                ->where('agreement_time','<',$time2)
                ->limit((int)$perpage, (int)$perpage * (int)$page);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetchAll();
            for($j=0;$j<count($data1);$j++){
                $num2+=$data1[$j]['freight'];
            }
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
                echo json_encode(array('result'=>'0','desc'=>'','argees'=>$data1,'count'=>$num,'count1'=>$num2));
            }else{
                echo json_encode(array('result'=>'3','desc'=>'该公司尚未有历史合同'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'该公司尚未有历史合同'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
    }else{
        if($tenant_id!=null||$tenant_id!=""){
            $selectStament=$database->select()
                ->from('agreement')
                ->where('agreement_time','<',$time2)
                ->where('exist','=',0)
                ->whereIn('tenant_id',$array1);
            $stmt=$selectStament->execute();
            $data=$stmt->fetchAll();
            $num=count($data);
            $num2=0;
            if($data!=null){
                $page=(int)$page-1;
                $selectStament=$database->select()
                    ->from('agreement')
                    ->where('exist','=',0)
                    ->where('agreement_time','<',$time2)
                    ->whereIn('tenant_id',$array1)
                    ->limit((int)$perpage, (int)$perpage * (int)$page);
                $stmt=$selectStament->execute();
                $data1=$stmt->fetchAll();
                for($j=0;$j<count($data1);$j++){
                    $num2+=$data1[$j]['freight'];
                }
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
                    echo json_encode(array('result'=>'0','desc'=>'','argees'=>$data1,'count'=>$num,'count1'=>$num2));
                }else{
                    echo json_encode(array('result'=>'3','desc'=>'该公司尚未有历史合同'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'该公司尚未有历史合同'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
        }
    }
});


//查询运单具体信息
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
//                $sum.=$data5[$j]['scheduling_id'].",";

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


$app->get('/lorrys',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenant-id');
    $page=$app->request->get('page');
    $perpage=$app->request->get('perpage');
    $array1=explode(',',$tenant_id);
    if($tenant_id!=null||$tenant_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->whereIn('tenant_id',$array1);
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();
        $num=count($data);
        $page=(int)$page-1;
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->whereIn('tenant_id',$array1)
            ->limit((int)$perpage, (int)$perpage * (int)$page);
        $stmt=$selectStament->execute();
        $data2=$stmt->fetchAll();
//        $data2= array_values(array_unset_tt($data2,'lorry_id'));
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
            echo json_encode(array('result'=>'0','desc'=>'','lorrys'=>$data2,'count'=>$num));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'该公司尚未有合作车辆'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'缺少租户id'));
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