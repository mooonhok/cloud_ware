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
    if($city_id!=null||$city_id!=''){
       if($company!=null||$company!=''){
           $selectStatement = $database->select()
               ->from('tenant')
               ->where('from_city_id', '=', $city_id);
           $stmt = $selectStatement->execute();
           $data1 = $stmt->fetchAll();
           if($data1!=null){
              for($i=0;$i<count($data1);$i++){
                  $selectStatement = $database->select()
                      ->from('rechanges_insurance')
                      ->where('tenant_id', '=', $data1[$i]['tenant_id'])
                      ->orderBy('rechanges_insurance.sure_time');;
                  $stmt = $selectStatement->execute();
                  $data2 = $stmt->fetchAll();
                  for($k=0;$k<count($data2);$k++){
                          $data2[$k]['company']=$data1[$i]['company'];
                  }
                  array_push($arrays,$data2);
              }
               echo json_encode(array('result'=>'1','desc'=>'success','insurance_rechanges'=>$arrays));
           }else{
               echo json_encode(array('result'=>'2','desc'=>'合作公司不存在','insurance_rechanges'=>''));
           }
       }else{
           $selectStatement = $database->select()
               ->from('tenant')
               ->where('from_city_id', '=', $city_id)
               ->where('company','=',$company);
           $stmt = $selectStatement->execute();
           $data1 = $stmt->fetch();
           if($data1!=null){
               $selectStatement = $database->select()
                   ->from('rechanges_insurance')
                   ->where('tenant_id', '=', $data1['tenant_id'])
                   ->orderBy('rechanges_insurance.sure_ttime');
               $stmt = $selectStatement->execute();
               $data2 = $stmt->fetchAll();
               for($k=0;$k<count($data2);$k++){
                   $data2[$k]['company']=$data1['company'];
               }
               echo json_encode(array('result'=>'1','desc'=>'success','insurance_rechanges'=>$data2));
           }else{
               echo json_encode(array('result'=>'2','desc'=>'合作公司不存在','insurance_rechanges'=>''));
           }
       }
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->join('rechanges_insurance','rechanges_insurance.tenant_id','=','tenant.tenant_id','INNER')
            ->orderBy('rechanges_insurance.sure_ttime');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'3','desc'=>'城市id为空','insurance_rechanges'=>$data1));
    }
});
//修改支付状态
$app->put('/sure_rechanges',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $pay_id=$body->id;
    $array=array();
    $key='insurance_balance';
    if($tenant_id!=""||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=""||$data1!=null){
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('id', '=', $pay_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            if($data2!=null||$data2!=""){
                $array[$key]=$data2['money']+$data1['insurance_balance'];
                $updateStatement = $database->update($array)
                    ->table('tenant')
                    ->where('tenant_id','=',$tenant_id);
                $affectedRows1 = $updateStatement->execute();
                if($affectedRows1>0){
                    date_default_timezone_set("PRC");
                    $now=date("Y-m-d H:i:s",time());
                $updateStatement = $database->update(array('status'=>1,'sure_time'=>$now))
                        ->table('rechanges_insurance')
                        ->where('id','=',$pay_id);
                $affectedRows2 = $updateStatement->execute();
                    if($affectedRows1>0) {
                        echo json_decode(array('result' => '0', 'desc' => 'success'));
                    }else{
                        echo json_decode(array('result'=>'1','desc'=>'保险公司确认失败'));
                    }
                }else{
                    echo json_decode(array('result'=>'2','desc'=>'公司余额未改变'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'充值记录不存在'));
            }
        }else{
            echo json_encode(array('result'=>'4','desc'=>'租户不存在'));
        }
    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});
//获取保险记录
$app->get('/insurances',function ()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $company=$app->request->get('company');
    $city_id=$app->request->get('city_id');
    $database=localhost();
    $arrays=array();
    if($city_id!=null||$city_id!=""){
        if($company!=null||$company!=''){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('from_city_id', '=', $city_id)
                ->where('company', '=', $company);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data1!=null||$data1!=''){
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->where('tenant_id', '=', $data1['tenant_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();

                if($data2!=null){
                    for($i=0;$i<count($data2);$i++){
                        $array1=array();
                        $array=array();
                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('lorry_id', '=', $data2[$i]['insurance_lorry_id'])
                            ->where('tenant_id', '=', $data1['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data2a = $stmt->fetch();
                        $selectStatement = $database->select()
                            ->from('insurance_scheduling')
                            ->where('insurance_id', '=', $data2[$i]['insurance_id'])
                            ->where('tenant_id','=',$data1['tenant_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetchAll();
                        if($data3!=null){
                           for($j=0;$j<count($data3);$j++){
                               $selectStatement = $database->select()
                                   ->from('schedule_order')
                                   ->where('schedule_id', '=', $data3[$j]['scheduling_id'])
                                   ->where('tenant_id','=',$data1['tenant_id']);
                               $stmt = $selectStatement->execute();
                               $data4 = $stmt->fetchAll();
                               if($data4!=null){
                                  for($k=0;$k<count($data4);$k++){
                                      $selectStatement = $database->select()
                                          ->from('orders')
                                          ->where('order_id', '=', $data4[$k]['order_id'])
                                          ->where('tenant_id','=',$data1['tenant_id']);
                                      $stmt = $selectStatement->execute();
                                      $data5 = $stmt->fetch();
                                      if($data5!=null){
                                          $selectStatement = $database->select()
                                              ->from('goods')
                                              ->where('goods_id', '=', $data5['goods_id'])
                                              ->where('tenant_id','=',$data1['tenant_id']);
                                          $stmt = $selectStatement->execute();
                                          $data6 = $stmt->fetch();
                                          array_push($array,$data6);
                                      }else{
                                          echo json_encode(array('result'=>'1','desc'=>'货物不存在','rechange_insurance'=>''));
                                      }
                                  }
                               }else{
                                   echo json_encode(array('result'=>'1','desc'=>'调度对应订单不存在','rechange_insurance'=>''));
                               }
                           }
                        }else{
                            echo json_encode(array('result'=>'1','desc'=>'保险对应调度不存在','rechange_insurance'=>''));
                        }
                        $data2[$i]['goods']=$array;
                        $data2[$i]['lorry']=$data2a;
                    }
                    echo json_encode(array('result'=>'1','desc'=>'保险对应调度不存在','rechange_insurance'=>$data2));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'保险不存在','rechange_insurance'=>''));
                }

            }else{
                echo json_encode(array('result'=>'1','desc'=>'租户公司不存在','rechange_insurance'=>''));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户公司id为空','rechange_insurance'=>''));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'城市信息为空','insurances'=>''));
    }
});
$app->run();

function localhost(){
    return connect();
}
?>
