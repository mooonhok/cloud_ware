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
//登录方法
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
            ->where('type','=',1)
            ->where('username','=',$username);
        $stmt=$selectStaement->execute();
        $data=$stmt->fetch();
        if ($data!=null){
            $selectStaement=$database->select()
                ->from('admin')
                ->where('password','=',$password)
                ->where('exist','=',0)
                ->where('type','=',1)
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


//获取保险充值记录
$app->get('/insurance_rechanges',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $city_id=$app->request->get('city_id');
    $company=$app->request->get('company');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    if($page==null||$per_page==null){
      //  $page=(int)$page-1;
        if ($city_id != null || $city_id != '') {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
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
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
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
          //      $sum=ceil($num/(int)$per_page);
                echo json_encode(array('result' => '0', 'desc' => '', 'insurance_rechanges' => $arrays,'count'=>$num));
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'INNER')
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
         //   $sum=ceil($num/(int)$per_page);
            echo json_encode(array('result' => '0', 'desc' => '', 'insurance_rechanges' => $arrays,'count'=>$num));
        }
    }else {
        $page=(int)$page-1;
        if ($city_id != null || $city_id != '') {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
                    ->orderBy('rechanges_insurance.sure_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
          //      $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
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
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('rechanges_insurance.sure_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
          //      $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('rechanges_insurance')
                    ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
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
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'left')

                ->orderBy('rechanges_insurance.sure_time', 'desc');
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetchAll();
            $num=count($data4);
         //   $sum=ceil($num/(int)$per_page);
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->join('tenant', 'tenant.tenant_id', '=', 'rechanges_insurance.tenant_id', 'INNER')
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
            echo json_encode(array('result' => '0', 'desc' =>'', 'insurance_rechanges' => $arrays,'count'=>$num));
        }
    }
});


//获取保险充值记录总数每页显示10个的页数
$app->get('/insurance_rechanges_count',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $arrays=array();
    $city_id=$app->request->get('city_id');
    $company=$app->request->get('company');

    if($city_id!=null||$city_id!=''){
        if($company!=null||$company!=''){
            $selectStatement = $database->select()
                ->from('tenant')
                ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
                ->where('tenant.from_city_id', '=', $city_id)
                ->where('tenant.company','=',$company)
                ->orderBy('rechanges_insurance.sure_time','desc');
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $num=ceil(count($data1)/10);
            echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
                ->where('tenant.from_city_id', '=', $city_id)
                ->orderBy('rechanges_insurance.sure_time','desc');
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $num=ceil(count($data1)/10);
            echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
        }
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
            ->orderBy('rechanges_insurance.sure_time','desc');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        $num=ceil(count($data1)/10);
        echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
    }
});


$app->options('/sure_rechanges',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});

//修改支付状态
$app->put('/sure_rechanges',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $user_id=$body->user_id;
    $pay_id=$body->id;
    $array=array();
    $key='insurance_balance';
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('rechange_insurance_id', '=', $pay_id)
                ->where('status','=',0);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
               ->from('tenant')
               ->where('tenant_id', '=', $data2['tenant_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data2!=null||$data2!=""){
                $array[$key]=$data2['money']+$data1['insurance_balance'];
                $updateStatement = $database->update($array)
                    ->table('tenant')
                    ->where('tenant_id','=',$data2['tenant_id']);
                $affectedRows1 = $updateStatement->execute();
                if($affectedRows1>0){
                    date_default_timezone_set("PRC");
                    $now=date("Y-m-d H:i:s",time());
                $updateStatement = $database->update(array('status'=>1,'sure_time'=>$now,'admin_id'=>$user_id))
                        ->table('rechanges_insurance')
                        ->where('rechange_insurance_id','=',$pay_id);
                $affectedRows2 = $updateStatement->execute();
                    if($affectedRows1>0) {
                        echo json_encode(array('result' => '0', 'desc' => 'success'));
                    }else{
                        echo json_encode(array('result'=>'1','desc'=>'保险公司确认失败'));
                    }
                }else{
                    echo json_encode(array('result'=>'2','desc'=>'公司余额未改变'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'充值记录不存在'));
            }

});
$app->get('/insurances_sure',function ()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $company = $app->request->get('company');
    $datetime11=$app->request->get('datetime1');
    $datetime22 = $app->request->get('datetime2');

    $page = $app->request->get('page');
    $page=$page-1;
    $per_page = $app->request->get('per_page');
    $database=localhost();
    if((!$datetime11)||(!$datetime22)) {
        if ($company) {
            $selectStatement = $database->select()
                ->from('insurance')
                ->leftJoin('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id')
                ->whereLike('tenant.company', '%' . $company . '%')
                ->orderBy('insurance.insurance_start_time', 'desc')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for ($i = 0; $i < count($data1); $i++) {
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer.tenant_id', '=', $data1[$i]['tenant_id'])
                    ->where('customer.customer_id', '=', $data1[$i]['contact_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('lorry.tenant_id', '=', $data1[$i]['tenant_id'])
                    ->where('lorry.lorry_id', '=', $data1[$i]['insurance_lorry_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data1[$i]['contact_phone'] = $data3['customer_phone'];
                $data1[$i]['lorry_plate_number'] = $data2['plate_number'];
                $data1[$i]['lorry_name'] = $data2['driver_name'];
            }
        } else {
            $selectStatement = $database->select()
                ->from('insurance')
                ->leftJoin('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id')
                ->orderBy('insurance.insurance_start_time', 'desc')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            for ($i = 0; $i < count($data1); $i++) {
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer.tenant_id', '=', $data1[$i]['tenant_id'])
                    ->where('customer.customer_id', '=', $data1[$i]['contact_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('lorry.tenant_id', '=', $data1[$i]['tenant_id'])
                    ->where('lorry.lorry_id', '=', $data1[$i]['insurance_lorry_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data1[$i]['contact_phone'] = $data3['customer_phone'];
                $data1[$i]['lorry_plate_number'] = $data2['plate_number'];
                $data1[$i]['lorry_name'] = $data2['driver_name'];
            }
        }
    }else{
        $array=array();
        $array2=array();
    $datetime1=strtotime($datetime11);
    $datetime2=strtotime($datetime22);
        $selectStatement = $database->select()
            ->from('insurance')
            ->leftJoin('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id')
            ->orderBy('insurance.insurance_start_time', 'desc');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            if(strtotime($data1[$i]['insurance_start_time'])>=$datetime1&&strtotime($data1[$i]['insurance_start_time'])<=$datetime2){
                array_push($array,$data1[$i]);
            }
        }
        $num=0;
        if((int)$per_page*(int)$page<(count($array)-$per_page)){
            $num=(int)$per_page*(int)($page+1);
        }else{
            $num=count($array);
        }
        for ($i =(int)$per_page*(int)$page; $i <$num; $i++) {
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer.tenant_id', '=', $array[$i]['tenant_id'])
                ->where('customer.customer_id', '=', $array[$i]['contact_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('lorry.tenant_id', '=', $array[$i]['tenant_id'])
                ->where('lorry.lorry_id', '=', $array[$i]['insurance_lorry_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $array[$i]['contact_phone'] = $data3['customer_phone'];
            $array[$i]['lorry_plate_number'] = $data2['plate_number'];
            $array[$i]['lorry_name'] = $data2['driver_name'];
            array_push($array2,$array[$i]);
        }
        $data1=$array2;
    }

    echo json_encode(array('result'=>0,'desc'=>"",'insurances'=>$data1,'datetime1'=>$datetime11,'datetime2'=>$datetime22));
});

//根据保单号查调度单号信息
$app->get('/insurances_id',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $insurance_id=$app->request->get('insurance_id');
    $page = $app->request->get('page');
    $page=$page-1;
    $per_page = $app->request->get('per_page');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('insurance')
        ->where('id', '=', $insurance_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('insurance_scheduling')
        ->where('tenant_id', '=', $data1['tenant_id'])
        ->where('insurance_id', '=', $data1['insurance_id']);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('insurance_scheduling')
        ->where('tenant_id', '=', $data1['tenant_id'])
        ->where('insurance_id', '=', $data1['insurance_id'])
        ->limit((int)$per_page, (int)$per_page * (int)$page);
    $stmt = $selectStatement->execute();
    $data3 = $stmt->fetchAll();
    echo json_encode(array('result'=>'0','desc'=>'','insurance_schedulings'=>$data3,"count"=>count($data2)));
});

//获取保险记录
$app->get('/insurances',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $company=$app->request->get('company');
    $city_id=$app->request->get('city_id');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $database=localhost();

    if($page==null||$per_page==null){
     //   $page=(int)$page-1;
        if ($city_id != null || $city_id != "") {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                $num=count($data1);
    //            $sum=ceil($num/(int)$per_page);
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data2 = $stmt->fetch();
//                        $arrays1['from_city'] = $data2['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'rechange_insurance' => $arrays,'count'=>$num));
            } else {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                //  ->limit((int)10, (int)10 * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                $num=count($data1);
             //   $sum=ceil($num/(int)$per_page);
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data2 = $stmt->fetch();
//                        $arrays1['from_city'] = $data2['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'rechange_insurance' => $arrays,'count'=>$num));
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->orderBy('insurance.insurance_start_time', 'desc');
            //         ->limit((int)10, (int)10 * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $num=count($data1);
          //  $sum=ceil($num/(int)$per_page);
            if ($data1 != "" || $data1 != null) {
                for ($x = 0; $x < count($data1); $x++) {
                    $arrays1['company'] = $data1[$x]['company'];
                    $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                    $arrays1['plate_number'] = $data1[$x]['plate_number'];
                    $arrays1['driver_name'] = $data1[$x]['driver_name'];
                    $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                    $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                    $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                    $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
//                    $selectStatement = $database->select()
//                        ->from('city')
//                        ->where('id', '=', $data1[$x]['from_c_id']);
//                    $stmt = $selectStatement->execute();
//                    $data2 = $stmt->fetch();
//                    $arrays1['from_city'] = $data2['name'];
//                    $selectStatement = $database->select()
//                        ->from('city')
//                        ->where('id', '=', $data1[$x]['receive_c_id']);
//                    $stmt = $selectStatement->execute();
//                    $data3 = $stmt->fetch();
//                    $arrays1['receive_city'] = $data3['name'];
                    array_push($arrays, $arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '', 'rechange_insurance' => $arrays,'count'=>$num));
        }
    }else{
        $page=(int)$page-1;
        if ($city_id != null || $city_id != "") {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
             //   $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data2 = $stmt->fetch();
//                        $arrays1['from_city'] = $data2['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' =>'', 'rechange_insurance' => $arrays,'count'=>$num));
            } else {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
             //   $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['from_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data2 = $stmt->fetch();
//                        $arrays1['from_city'] = $data2['name'];
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data1[$x]['receive_c_id']);
//                        $stmt = $selectStatement->execute();
//                        $data3 = $stmt->fetch();
//                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'rechange_insurance' => $arrays,'count'=>$num));
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->where('insurance.sure_insurance', '=', '1')
                ->orderBy('insurance.insurance_start_time', 'desc');
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetchAll();
            $num=count($data4);
           // $sum=ceil($num/(int)$per_page);
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->where('insurance.sure_insurance', '=', '1')
                ->orderBy('insurance.insurance_start_time', 'desc')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            if ($data1 != "" || $data1 != null) {
                for ($x = 0; $x < count($data1); $x++) {
                    $arrays1['company'] = $data1[$x]['company'];
                    $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                    $arrays1['plate_number'] = $data1[$x]['plate_number'];
                    $arrays1['driver_name'] = $data1[$x]['driver_name'];
                    $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                    $arrays1['duration'] = $data1[$x]['duration'];
                    $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                    $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                    $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['from_c_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $arrays1['from_city'] = $data2['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['receive_c_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $arrays1['receive_city'] = $data3['name'];
                    array_push($arrays, $arrays1);
                }
            }
            echo json_encode(array('result' => '0', 'desc' =>'', 'rechange_insurance' => $arrays,'count'=>$num));
        }
    }
});


//点击合同详情
$app->get('/year_insurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    if($id!=null||$id!=''){
        $selectStatement = $database->select()
            ->from('rechanges_insurance')
            ->join('tenant','tenant.tenant_id','=','rechanges_insurance.tenant_id','INNER')
            ->join('customer','customer.customer_id','=','tenant.contact_id','INNER')
            ->where('rechanges_insurance.rechange_insurance_id', '=', $id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        date_default_timezone_set("PRC");
        $data1['one_year_time']=date("Y-m-d H:i:s",strtotime("+1 year",strtotime($data1['sure_time'])));
        echo json_encode(array('result'=>'0','desc'=>'success','insurance'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'id为空','insurance'=>''));
    }
});

//点击历史保险的货物详情
$app->get('/one_goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $insurance_id=$app->request->get('insurance_id');
    $database=localhost();
    if($insurance_id!=null||$insurance_id!=''){
        $selectStatement = $database->select()
            ->from('insurance')
            ->join('insurance_scheduling','insurance_scheduling.insurance_id','=','insurance.insurance_id','INNER')
            ->join('schedule_order','insurance_scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->where('insurance.insurance_id','=',$insurance_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
       // $value="";
       // for($i=0;$i<count($data1);$i++){
      //     $value.=$data1[$i]['goods_name'].',';
       //j }
        echo json_encode(array('result'=>'0','desc'=>'success','goods'=>$data1,'count'=>count($data1)));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'单个保险id为空','goods'=>''));
    }
});



//获取保险记录总数能分多少页
$app->get('/insurances_count',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $company=$app->request->get('company');
    $city_id=$app->request->get('city_id');
    $database=localhost();
    $arrays=array();
    if($city_id!=null||$city_id!=""){
        if($company!=null||$company!=''){
            $selectStatement = $database->select()
                ->from('tenant')
                ->join('insurance','tenant.tenant_id','=','insurance.tenant_id','INNER')
                ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
                ->where('tenant.from_city_id','=',$city_id)
                ->where('insurance.sure_insurance','=','1')
                ->where('tenant.company','=',$company);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
        //    $num=ceil(count($data1)/10);
            echo json_encode(array('result'=>'1','desc'=>'success','count'=>count($data1)));
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->join('insurance','tenant.tenant_id','=','insurance.tenant_id','INNER')
                ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
                ->where('insurance.sure_insurance','=','1')
                ->where('tenant.from_city_id','=',$city_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $num=ceil(count($data1)/10);
            echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
        }
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->join('insurance','tenant.tenant_id','=','insurance.tenant_id','INNER')
            ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
            ->where('insurance.sure_insurance','=','1');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        $num=ceil(count($data1)/10);
        echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
    }
});


//官网，xx公司支付的保险金额（通过id)
$app->get('/rechange_insurance_id',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('rechanges_insurance')
        ->where('rechange_insurance_id', "=", $id);
    $stmt = $selectStatement->execute();
    $data2= $stmt->fetch();
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id', '=', $data2['tenant_id']);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('customer')
        ->where('customer_id', '=', $data1['contact_id']);
    $stmt = $selectStatement->execute();
    $data3= $stmt->fetch();
    $data3=$data1['company'].'支付'.$data2['money'].'。'.$data3['customer_name'].':'.$data3['customer_phone'];
    echo json_encode(array('result'=>'1','desc'=>'success','data'=>$data3));
});

//获取该公司下的所有的历史保险记录
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
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if ($data1 != null || $data1 != "") {
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id', '=', $tenant_id)
                ->orderBy('insurance_start_time', 'desc');
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            $num=count($data2);
          //  $arrays['count']=ceil($num/(int)$per_page);
            if ($data2 != null || $data2 != "") {
                for ($i = 0; $i < count($data2); $i++) {
                    $arrays1['company'] = $data1['company'];
                    $arrays1['insurance_start_time'] = $data2[$i]['insurance_start_time'];
                    $arrays1['duration'] = $data2[$i]['duration'];
                    $arrays1['insurance_amount'] = $data2[$i]['insurance_amount'];
                    $arrays1['insurance_price'] = $data2[$i]['insurance_price'];
                    $arrays1['insurance_id'] = $data2[$i]['insurance_id'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data2[$i]['from_c_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $arrays1['from_city'] = $data3['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data2[$i]['receive_c_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $arrays1['receive_city'] = $data4['name'];
                    $selectStatement = $database->select()
                        ->from('lorry')
                        ->where('lorry_id', '=', $data2[$i]['insurance_lorry_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $arrays1['plate_number'] = $data5['plate_number'];
                    $arrays1['driver_name'] = $data5['driver_name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data1['contact_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $arrays1['customer_phone'] = $data6['customer_phone'];
                    $arrays1['goods_name'] = $data2[$i]['g_type'];
                    array_push($arrays, $arrays1);
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $arrays,'count'=>$num));
            } else {
                echo json_encode(array('result' => '3', 'desc' => '该公司无历史保单', 'rechanges' => ''));
            }
        } else {
            echo json_encode(array('result' => '2', 'desc' => '该公司不存在', 'rechanges' => ''));
        }
    } else {
        echo json_encode(array('result' => '1', 'desc' => '租户id为空', 'rechanges' => ''));
    }
}else{
    $page=(int)$page-1;
    $arrays = array();
    if ($tenant_id != null || $tenant_id != "") {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if ($data1 != null || $data1 != "") {
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id', '=', $tenant_id)
                ->orderBy('insurance_start_time', 'desc');
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            $num=count($data2);
            $sum=ceil($num/(int)$per_page);
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id', '=', $tenant_id)
                ->orderBy('insurance_start_time', 'desc')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            if ($data2 != null || $data2 != "") {
                for ($i = 0; $i < count($data2); $i++) {
                    $arrays1['company'] = $data1['company'];
                    $arrays1['insurance_start_time'] = $data2[$i]['insurance_start_time'];
                    $arrays1['duration'] = $data2[$i]['duration'];
                    $arrays1['insurance_amount'] = $data2[$i]['insurance_amount'];
                    $arrays1['insurance_price'] = $data2[$i]['insurance_price'];
                    $arrays1['insurance_id'] = $data2[$i]['insurance_id'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data2[$i]['from_c_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $arrays1['from_city'] = $data3['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data2[$i]['receive_c_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $arrays1['receive_city'] = $data4['name'];
                    $selectStatement = $database->select()
                        ->from('lorry')
                        ->where('lorry_id', '=', $data2[$i]['insurance_lorry_id']);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetch();
                    $arrays1['plate_number'] = $data5['plate_number'];
                    $arrays1['driver_name'] = $data5['driver_name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id', '=', $data1['contact_id']);
                    $stmt = $selectStatement->execute();
                    $data6 = $stmt->fetch();
                    $arrays1['customer_phone'] = $data6['customer_phone'];
                    $arrays1['goods_name'] = $data2[$i]['g_type'];
                    array_push($arrays, $arrays1);
                }
                echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $arrays,'count'=>$num));
            } else {
                echo json_encode(array('result' => '3', 'desc' => '该公司无历史保单', 'rechanges' => ''));
            }
        } else {
            echo json_encode(array('result' => '2', 'desc' => '该公司不存在', 'rechanges' => ''));
        }
    } else {
        echo json_encode(array('result' => '1', 'desc' => '租户id为空', 'rechanges' => ''));
    }
}
});


//根据时间
$app->get('/company',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $company=$app->request->get('company');
    $page = $app->request->get('page');
    $page=$page-1;
    $per_page=$app->request->get('per_page');
    $selectStatement = $database->select()
        ->count('insurance.tenant_id','zon')
        ->from('insurance')
        ->join('tenant','tenant.tenant_id','=','insurance.tenant_id','INNER')
        ->whereLike('tenant.company','%'.$company.'%')
        ->groupBy('insurance.tenant_id');
    $stmt = $selectStatement->execute();
    $dataa = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->count('insurance.tenant_id','zon')
        ->from('insurance')
        ->join('tenant','tenant.tenant_id','=','insurance.tenant_id','INNER')
        ->whereLike('tenant.company','%'.$company.'%')
        ->groupBy('insurance.tenant_id')
        ->limit((int)$per_page,(int)$per_page*(int)$page);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$data1[$i]['tenant_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('customer_id','=',$data2['contact_id'])
            ->where('tenant_id','=',$data1[$i]['tenant_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data1[$i]['company']=$data2['company'];
        $data1[$i]['contact_phone']=$data3['customer_phone'];
        $data1[$i]['contact_customer']=$data3['customer_name'];
    }
    echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $data1,'count'=>count($dataa)));
});

//根据调度单号，查询保单相关的
$app->get('/scheduling_id',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $scheduling_id = $app->request->get('scheduling_id');
    $tenant_id = $app->request->get('tenant_id');
    $selectStatement = $database->select()
        ->from('insurance_scheduling')
        ->where('scheduling_id','=',$scheduling_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data11 = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('nature','=',0)
        ->where('business_l', '=', $data11['business_l']);
    $stmt = $selectStatement->execute();
    $data1a = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('insurance_scheduling')
        ->leftJoin('tenant','tenant.tenant_id','=','insurance_scheduling.tenant_id')
        ->where('insurance_scheduling.insurance_id','=',$data1['insurance_id'])
        ->where('tenant.tenant_id','=',$tenant_id)
        ->where('insurance_scheduling.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    echo json_encode(array('result' => '0', 'desc' => '', 'rechanges' => $data2,'company'=>$data1a));
});
$app->run();

function localhost(){
    return connect();
}
?>
