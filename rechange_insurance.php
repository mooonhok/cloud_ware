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
//获取租户列表
$app->get('/tenants',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $arrays=array();
    $selectStatement = $database->select()
        ->from('tenant');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    if($data1!=null){
        for($i=0;$i<count($data1);$i++){
            $array1['id']=$data1['tenant_id'];
            $array1['company']=$data1['company'];
            array_push($arrays,$array1);
        }
        echo json_encode(array('result'=>'0','desc'=>'操作成功','tenants'=>$arrays));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'系统故障','tenants'=>''));
    }
});
//获取保险充值记录
$app->get('/insurance_rechanges',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $arrays=array();
    $city_id=$app->request->get('city_id');
    $company=$app->request->get('company');
    $page = $app->request->get('page');
    $page=(int)$page-1;
    if($city_id!=null||$city_id!=''){
       if($company!=null||$company!=''){
           $selectStatement = $database->select()
               ->from('tenant')
               ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
               ->where('tenant.from_city_id', '=', $city_id)
               ->where('tenant.company','=',$company)
               ->orderBy('rechanges_insurance.sure_time','desc');
 //              ->limit((int)10, (int)10 * (int)$page);
           $stmt = $selectStatement->execute();
           $data1 = $stmt->fetchAll();
           echo json_encode(array('result'=>'1','desc'=>'success','insurance_rechanges'=>$data1));
       }else{
               $selectStatement = $database->select()
                   ->from('tenant')
                   ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
                   ->where('tenant.from_city_id', '=', $city_id)
                   ->orderBy('rechanges_insurance.sure_time','desc');
  //                 ->limit((int)10, (int)10 * (int)$page);
               $stmt = $selectStatement->execute();
               $data1 = $stmt->fetchAll();
               echo json_encode(array('result'=>'1','desc'=>'success','insurance_rechanges'=>$data1));
       }
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
            ->orderBy('rechanges_insurance.sure_time','desc');
//            ->limit((int)10, (int)10 * (int)$page);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'3','desc'=>'城市id为空','insurance_rechanges'=>$data1));
    }
});


//获取保险充值记录总数每页显示10个的页数
$app->get('/insurance_rechanges_count',function()use($app){
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

//修改支付状态
$app->put('/sure_rechanges',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $user_id=$body->user_id;
    $pay_id=$body->id;
    $array=array();
    $key='insurance_balance';
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('id', '=', $pay_id);
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
                        ->where('id','=',$pay_id);
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
//获取保险记录
$app->get('/insurances',function ()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $company=$app->request->get('company');
    $city_id=$app->request->get('city_id');
    $page = $app->request->get('page');
    $page=(int)$page-1;
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
                ->where('tenant.company','=',$company)
                ->orderBy('insurance.insurance_start_time','desc');
//                ->limit((int)10, (int)10 * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            echo json_encode(array('result'=>'1','desc'=>'success','rechange_insurance'=>$data1));
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->join('insurance','tenant.tenant_id','=','insurance.tenant_id','INNER')
                ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
                ->where('insurance.sure_insurance','=','1')
                ->where('tenant.from_city_id','=',$city_id)
                ->orderBy('insurance.insurance_start_time','desc');
              //  ->limit((int)10, (int)10 * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            echo json_encode(array('result'=>'1','desc'=>'租户公司id为空','rechange_insurance'=>$data1));
        }
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->join('insurance','tenant.tenant_id','=','insurance.tenant_id','INNER')
            ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
            ->where('insurance.sure_insurance','=','1')
            ->orderBy('insurance.insurance_start_time','desc');
   //         ->limit((int)10, (int)10 * (int)$page);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'1','desc'=>'城市信息为空','rechange_insurance'=>$data1));
    }
});


//点击合同详情
$app->get('/year_insurance',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    if($id!=null||$id!=''){
        $selectStatement = $database->select()
            ->from('rechanges_insurance')
            ->join('tenant','tenant.tenant_id','=','rechanges_insurance.tenant_id','INNER')
            ->where('rechanges_insurance.id', '=', $id);
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetch();
        echo json_encode(array('result'=>'1','desc'=>'id为空','insurance'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'id为空','insurance'=>''));
    }
});

//点击历史保险的货物详情
$app->get('/one_goods',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $insurance_id=$app->request->get('insurance_id');
    $database=localhost();
    if($insurance_id!=null||$insurance_id!=''){
        $selectStatement = $database->select()
            ->from('insurance')
            ->join('insurance_scheduling','insurance_scheduling.insurance_id','=',$insurance_id,'INNER')
            ->join('schedule_order','insurance_scheduling.scheduling_id','=','schedule_order.schedule_id','INNER')
            ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
            ->join('goods','goods.order_id','=','orders.order_id','INNER');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        $value="";
        for($i=0;$i<count($data1);$i++){
           $value.=$data1[$i]['goods_name'].',';
        }
        echo json_encode(array('result'=>'1','desc'=>'success','goods'=>$value,'count'=>count($data1)));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'单个保险id为空','goods'=>''));
    }
});



//获取保险记录总数能分多少页
$app->get('/insurances_count',function ()use($app){
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
            $num=ceil(count($data1)/10);
            echo json_encode(array('result'=>'1','desc'=>'success','count'=>$num));
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
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('rechanges_insurance')
        ->where('id', '=', $id);
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

$app->run();

function localhost(){
    return connect();
}
?>
