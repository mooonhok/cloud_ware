<?php

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//业务员登录
$app->get('/usersign',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $username = $app->request->get("username");
    $password1=$app->request->get("password");
//    $body=$app->request->getBody();
//    $body=json_decode($body);
//    $username=$body->username;
//    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($username!=""||$username!=null){
        $selectStaement=$database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('telephone','=',$username);
        $stmt=$selectStaement->execute();
        $data=$stmt->fetch();
         if ($data!=null){
             $selectStaement=$database->select()
                 ->from('sales')
                 ->where('password','=',$password)
                 ->where('exist','=',0)
                 ->where('telephone','=',$username);
             $stmt=$selectStaement->execute();
             $data2=$stmt->fetch();
             if($data2!=null){
                 echo $_GET['callback']."(".json_encode(array('result'=>'0','desc'=>'登录成功','user'=>$data2)).")";
//                 echo json_encode(array('result'=>'0','desc'=>'登录成功','user'=>$data2));
             }else{
                 echo $_GET['callback']."(".json_encode(array('result'=>'3','desc'=>'密码错误','user'=>'')).")";
//                 echo json_encode(array('result'=>'3','desc'=>'密码错误','user'=>''));
             }
         }else{
             echo $_GET['callback']."(".json_encode(array('result'=>'2','desc'=>'用户不存在','user'=>'')).")";
//             echo json_encode(array('result'=>'2','desc'=>'用户不存在','user'=>''));
         }
    }else{
        echo $_GET['callback']."(".json_encode(array('result'=>'1','desc'=>'用户名为空','user'=>'')).")";
//        echo json_encode(array('result'=>'1','desc'=>'用户名为空','user'=>''));
    }
});

$app->get('/signtwo',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $username = $app->request->get("username");
    $password1=$app->request->get("password");
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($username!=""||$username!=null){
        $selectStaement=$database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('sales_id','=',$username);
        $stmt=$selectStaement->execute();
        $data=$stmt->fetch();
        if ($data!=null){
            $selectStaement=$database->select()
                ->from('sales')
                ->where('password','=',$password)
                ->where('exist','=',0)
                ->where('sales_id','=',$username);
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



//统计公司总数
$app->get('/alltenants',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $sales_id = $app->request->get("sales_id");
    $database=localhost();
    if($sales_id!=null||$sales_id!=""){
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist','=',0)
                ->where('sales_id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            echo json_encode(array('result'=>'0','desc'=>'','count'=>count($data2)));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'业务员不存在'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空'));
    }
});
//获取该业务员名下的公司
$app->get('/sales_tenanttwo',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    if($sales_id!=null||$sales_id!=""){
//            $arrays=array();
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist','=',0)
                ->where('sales_id', '=', $sales_id)
                ->orderBy('begin_time')
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            if($data2!=null){
                for($x=0;$x<count($data2);$x++){
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('tenant_id', '=', $data2[$x]['tenant_id'])
                        ->where('customer_id', '=', $data2[$x]['contact_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    //        $array['user_name']=$data1['user_name'];
                    $data2[$x]['customer_name']=$data3['customer_name'];
                    $data2[$x]['customer_phone']=$data3['customer_phone'];
//                        date_default_timezone_set("PRC");
//                        $begintime=date("Y-m-d",strtotime($data2[$x]['begin_time']));
//                        $data2[$x]['begin_time']=$begintime;
//                        array_push($arrays,$array);
                }
                echo json_encode(array('result'=>'0','desc'=>'','company'=>$data2));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'此页尚未有数据','company'=>''));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'业务员不存在','company'=>''));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空','company'=>''));
    }
});

$app->get('/sales_tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $database=localhost();
    if($page==null||$per_page==null){
        $arrays=array();
        if($sales_id!=null||$sales_id!=""){
            $selectStatement = $database->select()
                ->from('sales')
                ->where('exist','=',0)
                ->where('id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data1!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('exist','=',0)
                    ->where('sales_id', '=', $sales_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                $num=count($data2);
                if($data2!=null){
                    for($x=0;$x<count($data2);$x++){
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('tenant_id', '=', $data2[$x]['tenant_id'])
                            ->where('customer_id', '=', $data2[$x]['contact_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $array['tenant_id']=$data2[$x]['tenant_id'];
                        //        $array['user_name']=$data1['user_name'];
                        $array['customer_name']=$data3['customer_name'];
                        $array['customer_phone']=$data3['customer_phone'];
                        date_default_timezone_set("PRC");
                        $begintime=date("Y-m-d",strtotime($data2[$x]['begin_time']));
                        $array['begin_time']=$begintime;

                        $array['company']=$data2[$x]['company'];
                        array_push($arrays,$array);
                    }
                    echo json_encode(array('result'=>'0','desc'=>'','company'=>$arrays,'count'=>$num));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'该业务员尚未有业务数据','company'=>''));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'业务员不存在','company'=>''));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空','company'=>''));
        }
    }else{
        $page=(int)$page-1;
        if($sales_id!=null||$sales_id!=""){
            $arrays=array();
            $selectStatement = $database->select()
                ->from('sales')
                ->where('exist','=',0)
                ->where('id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data1!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('exist','=',0)
                    ->where('sales_id', '=', $sales_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                $num=count($data3);
          //      $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('exist','=',0)
                    ->where('sales_id', '=', $sales_id)
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetchAll();
                if($data2!=null){
                    for($x=0;$x<count($data2);$x++){
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('tenant_id', '=', $data2[$x]['tenant_id'])
                            ->where('customer_id', '=', $data2[$x]['contact_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $array['tenant_id']=$data2[$x]['tenant_id'];
                        //        $array['user_name']=$data1['user_name'];
                        $array['customer_name']=$data3['customer_name'];
                        $array['customer_phone']=$data3['customer_phone'];
                        date_default_timezone_set("PRC");
                        $begintime=date("Y-m-d",strtotime($data2[$x]['begin_time']));
                        $array['begin_time']=$begintime;
                        $array['company']=$data2[$x]['company'];
                        array_push($arrays,$array);
                    }
                    echo json_encode(array('result'=>'0','desc'=>'','company'=>$arrays,'count'=>$num));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'该业务员尚未有业务数据','company'=>''));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'业务员不存在','company'=>''));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空','company'=>''));
        }
    }
});

$app->options('/tenantchange',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
        $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
  });
// 修改租户信息
$app->put('/tenantchange',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $tenant_id=$body->tenant_id;
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $address=$body->address;
    $jcompany=$body->jcompany;
    $long=$body->longitude;
    $lat=$body->latitude;
    $cityid=$body->fcity;
    $qq=$body->qq;
    $email=$body->email;
    $arrays=array();
    $array1=array();
    $arrays['address']=$address;
    $arrays['from_city_id']=$cityid;
    $arrays['jcompany']=$jcompany;
    $arrays['qq']=$qq;
    $arrays['email']=$email;
    $arrays['longitude']=$long;
    $arrays['latitude']=$lat;
    $array1['customer_name']=$customer_name;
    $array1['customer_phone']=$customer_phone;
    $array1['customer_city_id']=$cityid;
    $array1['customer_address']=$address;
    $array2['telephone']=$customer_phone;
    $array2['name']=$customer_name;
    if($sales_id!=null||$sales_id!=""){
        if($tenant_id!=null||$tenant_id!="") {
            $selectStatement = $database->select()
                ->from('sales')
                ->where('exist','=',0)
                ->where('id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if ($data1 != null) {
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('exist','=',0)
                    ->where('tenant_id', '=', $tenant_id);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                if ($data2 != null ) {
                    $updateStatement = $database->update($arrays)
                        ->table('tenant')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('exist','=',0)
                        ->where('sales_id', '=', $sales_id);
                    $affectedRows = $updateStatement->execute();
                    $updateStatement = $database->update($array1)
                        ->table('customer')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('customer_id', '=', $data2['contact_id']);
                    $affectedRows = $updateStatement->execute();
                    $updateStatement = $database->update($array2)
                        ->table('staff')
                        ->where('tenant_id', '=', $tenant_id)
                        ->where('staff_id', '=','100001');
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
                } else {
                    echo json_encode(array('result' => '1', 'desc' => '该公司不存在'));
                }
            } else {
                echo json_encode(array('result' => '2', 'desc' => '业务员不存在'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'操作公司为空'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'业务员id为空'));
    }
});
//统计业务员业务数据
$app->get('/tenantsum',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $database=localhost();
    $arrays=array();
    $arrays1=array();
    if($sales_id!=null||$sales_id!=""){
//        $lowtime=mktime(0,0,0,1,1,date('Y'));
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('sales_id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist','=',0)
                ->where('sales_id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            for($x=1;$x<=12;$x++){
               if($x<10){
                   $key='0'.$x;
               }else{
                   $key=$x.'';
               }
               $arrays[$key]=null;
           }
           for($x=0;$x<count($data2);$x++){
             date_default_timezone_set("PRC");
             $time=$data2[$x]['begin_time'];
             $timestrap=strtotime($time);
               $lowtime=mktime(0,0,0,1,1,date('Y'));
             if(strtotime($data2[$x]['begin_time'])>=$lowtime) {
                 $date = date('m', $timestrap);
                 if ($arrays['' . $date . ''] == null || $arrays['' . $date . ''] == "") {
                     $arrays['' . $date . ''] = 1;
                 } else {
                     $arrays['' . $date . '']++;
                 }
             }
           }
             for($y=1;$y<=12;$y++){
               if($y<10){
                   $key='0'.$y;
               }else{
                   $key=$y.'';
               }
               if($arrays[$key]==null||$arrays[$key]==""){
                   $arrays[$key]=0;
               }
              array_push($arrays1,$arrays[$key]);
           }
            echo json_encode(array('result'=>'0','desc'=>'','count'=>$arrays1));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'该业务员还没有数据','count'=>''));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'业务员id不能为空','count'=>''));
    }
});
//具体显示公司信息
$app->get('/tenantbyid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->get("tenant_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist','=',0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id', '=', $data2['contact_id'])
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=',$data2['from_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $array['customer_name']=$data3['customer_name'];
            $array['customer_phone']=$data3['customer_phone'];
            $array['company']=$data2['company'];
            $array['jcompany']=$data2['jcompany'];
            $array['province']=$data4['pid'];
            //$array['begin_time']=$data2['begin_time'];
            date_default_timezone_set("PRC");
            $array['location']=$data2['longitude'].','.$data2['latitude'];
            $array['cityid']=$data2['from_city_id'];
            $array['address']=$data2['address'];
            $array['qq']=$data2['qq'];
            $array['email']=$data2['email'];
            echo json_encode(array('result'=>'0','desc'=>'','tenant'=>$array));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'公司不存在','tenant'=>''));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'公司id为空','tenant'=>''));
    }
});

$app->options('/sales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});
//业务员信息修改
$app->put('/sales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $arrays=array();
    foreach($body as $key=>$value){
        if($key!="sales_id"){
            $arrays[$key]=$value;
        }
    }
    if($sales_id!=null||$sales_id!=""){
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $updateStatement = $database->update($arrays)
                ->table('sales')
                ->where('id', '=', $sales_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '该业务员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少业务员id'));
    }
});

$app->options('/alterSaleTenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});

//业务员信息修改
$app->put('/alterSaleTenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $qq=$body->qq;
    $email=$body->email;
    $telephone=$body->telephone;
    $sales_name=$body->sales_name;
    $arrays2['qq']=$qq;
    $arrays2['email']=$email;
    $arrays3['customer_phone']=$telephone;
    $arrays3['customer_name']=$sales_name;
    $arrays4['name']=$sales_name;
    $arrays4['telephone']=$telephone;
    if($sales_id!=null||$sales_id!=""){
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('sales_id', '=', $sales_id)
                ->orderBy('tenant_id')
                ->limit(1);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $updateStatement = $database->update($arrays2)
                ->table('tenant')
                ->where('tenant_id','=',$data3['tenant_id'])
                ->where('sales_id', '=', $sales_id);
            $affectedRows = $updateStatement->execute();
            $updateStatement = $database->update($arrays3)
                ->table('customer')
                ->where('tenant_id','=',$data3['tenant_id'])
                ->where('customer_id', '=', $data3['contact_id']);
            $affectedRows = $updateStatement->execute();
            $updateStatement = $database->update($arrays4)
                ->table('staff')
                ->where('tenant_id','=',$data3['tenant_id'])
                ->where('staff_id', '=','100001');
            $affectedRows = $updateStatement->execute();
            echo json_encode(array('result' => '0', 'desc' => '修改信息成功'));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '该业务员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少业务员id'));
    }
});


//获取业务员信息
$app->get('/sales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $database=localhost();
    if($sales_id!=null||$sales_id!=""){
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            echo json_encode(array('result'=>'0','desc'=>'','sales'=>$data1));
        }else{
            echo json_encode(array('result' => '1', 'desc' => '业务员不存在','sales'=>''));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少销售人员id','sales'=>''));
    }
});
//添加业务员
$app->post('/addsales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_name=$body->sales_name;
    $sex=$body->sex;
    $card_id=$body->card_id;
    $telephone=$body->telephone;
    $address=$body->address;
    $email=$body->email;
    $qq=$body->qq;
    $weixin=$body->weixin;
    $higherlevel=$body->higherlevel;
    $mac=$body->mac;
    if($sales_name!=null||$sales_name!=""){
         if($sex!=null||$sex!=""){
              if($card_id!=null||$card_id!=""){
                  $selectStatement = $database->select()
                      ->from('sales')
                      ->where('exist','=',0)
                      ->where('card_id', '=', $card_id);
                  $stmt = $selectStatement->execute();
                  $data1 = $stmt->fetch();
                  if($data1==null){
                       if($telephone!=null||$telephone!=""){
                           if($address!=null||$address!=""){
                                   if($higherlevel!=null||$higherlevel!=""){
                                       $selectStatement = $database->select()
                                           ->from('sales')
                                           ->where('exist','=',0)
                                           ->where('id', '=', $higherlevel);
                                       $stmt = $selectStatement->execute();
                                       $data5 = $stmt->fetch();
                                           $password1=substr($card_id,-6);
                                      // $num2=rand(1000000,10000000000);
                                       $str1=str_split($password1,3);
                                      $password=null;
                                       for ($x=0;$x<count($str1);$x++){
                                           $password.=$str1[$x].$x;
                                       }
                                       $sales_id=null;
                                       if($data5['team_id']<10){
                                         $sales_id='MT00'.$data5['team_id'];
                                       }else if($data5['team_id']>9&&$data5['team_id']<100){
                                         $sales_id='MT0'.$data5['team_id'];
                                       }
                                       $data6=array();
                                       if($data5['team_id']==null){
                                           $selectStatement = $database->select()
                                               ->from('sales')
                                               ->whereNull('team_id');
                                           $stmt = $selectStatement->execute();
                                           $data6 = $stmt->fetchAll();
                                       }else{
                                           $selectStatement = $database->select()
                                               ->from('sales')
                                               ->where('team_id','=',$data5['team_id']);
                                           $stmt = $selectStatement->execute();
                                           $data6 = $stmt->fetchAll();
                                       }


                                       $num2=count($data6)+1;
                                       if(count($data6)<10){
                                           $sales_id.='000'.$num2.'';
                                       }else if(count($data6)>9&&count($data6)<100){
                                           $sales_id.='00'.$num2.'';
                                       }else if(count($data6)>99&&count($data6)<1000){
                                           $sales_id.='0'.$num2.'';
                                       }else{
                                           $sales_id.=$num2.'';
                                       }
                                       $insertStatement = $database->insert(array('exist','sales_name','sex','card_id','telephone','address'
                                         ,'email','qq','weixin','password','higher_id','team_id','sales_id','mac'))
                                           ->into('sales')
                                           ->values(array(0,$sales_name,$sex,$card_id,$telephone,$address,$email,$qq,$weixin,$password
                                           ,$higherlevel,$data5['team_id'],$sales_id,$mac));
                                       $insertId = $insertStatement->execute(false);
                                       $arrays['password']=$password1;
                                       $selectStatement = $database->select()
                                           ->from('sales')
                                           ->where('card_id', '=',$card_id);
                                       $stmt = $selectStatement->execute();
                                       $data10 = $stmt->fetch();
                                       $arrays['id']=$data10['id'];
                                       echo json_encode(array('result' => '0', 'desc' => '添加成功','sales'=>$arrays,'data'=>$data6));
                                   }else{
                                       echo json_encode(array('result' => '8', 'desc' => '上一级不能为空','sales'=>''));
                                   }

                           }else{
                               echo json_encode(array('result' => '6', 'desc' => '地址不能为空','sales'=>''));
                           }
                       }else{
                           echo json_encode(array('result' => '5', 'desc' => '电话不能为空','sales'=>''));
                       }
                  }else{
                      echo json_encode(array('result' => '4', 'desc' => '业务员已经存在','sales'=>''));
                  }
              }else{
                  echo json_encode(array('result' => '3', 'desc' => '业务员身份证编号为空','sales'=>''));
              }
         }else{
             echo json_encode(array('result' => '2', 'desc' => '没有选择性别','sales'=>''));
         }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '业务员姓名为空','sales'=>''));
    }
});
//名下业务员
$app->get('/salesdown',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    if($sales_id!=null||$sales_id!=""){
//            $arrays=array();
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('sales')
                ->where('exist','=',0)
                ->where('higher_id', '=', $sales_id)
                ->limit((int)$size,(int)$offset);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            echo json_encode(array('result'=>'0','desc'=>'','sales'=>$data2));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'业务员不存在','sales'=>''));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空','sales'=>''));
    }
});
//名下业务员总数
$app->get('/countds',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $sales_id = $app->request->get("sales_id");
    $database=localhost();
    if($sales_id!=null||$sales_id!=""){
//            $arrays=array();
        $selectStatement = $database->select()
            ->from('sales')
            ->where('exist','=',0)
            ->where('id', '=', $sales_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('sales')
                ->where('exist','=',0)
                ->where('higher_id', '=', $sales_id);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            echo json_encode(array('result'=>'0','desc'=>'','count'=>count($data2)));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'业务员不存在','sales'=>''));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'业务员id不能为空','sales'=>''));
    }
});



$app->post('/addSaleTenant',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $qq = $body->qq;
    $address=$body->address;
    $business_l=$body->business;
    $company = $body->company;
    $contact_name = $body->name;
    $from_city_id = $body->city;
    $c_introduction = $body->introduction;
    $email =$body->email;
    $loca =$body->location;
    $jcompany = $body->jcompany;
    $sales_id =  $body->sales_id;
    $service_items =  $body->service;
    $telephone= $body->phone;
    $pic1=$body->order_file;
    $order_t_p=null;
    $pic2=$body->agreement_file;
    $trans_c_p=null;
    $pic3=$body->logo_file;
    $order_img=null;
    $pic4=$body->business_file;
    $business_l_p=null;
    if($pic1!=null) {
        $base64_image_content = $pic1;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/order_t_p/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $order_t_p = $file_url."order_t_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
    }
    if($pic2!=null) {
        $base64_image_content = $pic2;
//匹配出图片的格式
//            if (preg_match('/^(data:\s*application\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type2 = "doc";
        date_default_timezone_set("PRC");
        $time1 = time();
        $new_file = "/files/trans_contract_p/" . date('Ymd', $time1) . "/";
        if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file . $time1 . ".{$type2}";
        $arr=explode(",",$base64_image_content);
        $a=$arr[0];
        if (file_put_contents($new_file, base64_decode(str_replace($a, '', $base64_image_content)))) {
            $trans_c_p = $file_url."trans_contract_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type2}";
        }
//            }
    }
    if($pic3!=null) {
        $base64_image_content = $pic3;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/tenant/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $order_img = $file_url."tenant/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
    }else{
        $order_img=$file_url."tenant/5130001_order_logo.png";
    }
    if($pic4!=null) {
        $base64_image_content = $pic4;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/business_l_p/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $business_l_p = $file_url."business_l_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
    }
    if($company!=null||$company!=""){
//        if($business_l!=""||$business_l!=null){
            if($loca!=""||$loca!=null){
                $arr=explode(",",$loca);
                $longitude=$arr[0];
                $latitude=$arr[1];
                if($contact_name!=null||$contact_name!=""){
                    if($telephone!=null||$telephone!=""){
                        if($address!=""||$address!=null){
                            if($from_city_id!=""||$from_city_id=null){
                                date_default_timezone_set("PRC");
                                $begin_time=date("Y-m-d H:i", time());

                                if($sales_id!=null||$sales_id!=""){
//                                    $selectStatement = $database->select()
//                                        ->from('tenant')
//                                        ->where('company','=',$company)
//                                        ->where('exist','=',0);
//                                    $stmt = $selectStatement->execute();
//                                    $data5 = $stmt->fetch();
//                                    if($data5==null) {
                                        $selectStatement = $database->select()
                                            ->from('sales')
                                            ->where('id','=',$sales_id)
                                            ->where('exist',"=",0);
                                        $stmt = $selectStatement->execute();
                                        $data1 = $stmt->fetch();
                                        if($data1!=null||$data1!=""){
                                            $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
                                            $str1 = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            do{
                                                $str1.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            }while(strlen($str1)<4);
                                            $time=base_convert(time(), 10, 32);
                                            $num=$time.$str1;
                                            $insertStatement = $database->insert(array('customer_id','customer_name','customer_phone','exist'
                                            ,'customer_city_id','customer_address'))
                                                ->into('customer')
                                                ->values(array($num,$contact_name,$telephone,0,$from_city_id,$address));
                                            $insertId = $insertStatement->execute(false);
                                            if($insertId!=null||$insertId!=""){
                                                $selectStatement = $database->select()
                                                    ->from('city')
                                                    ->where('id','=',$from_city_id);
                                                $stmt = $selectStatement->execute();
                                                $data01 = $stmt->fetch();
                                                $selectStatement = $database->select()
                                                    ->from('tenant')
                                                    ->whereLike('tenant_id','9'."%");
                                                $stmt = $selectStatement->execute();
                                                $data02 = $stmt->fetchAll();
                                                $num01=0;
                                                for($i=0;$i<count($data02);$i++){
                                                    if(substr($data02[$i]['tenant_num'],0,3)==$data01['area_code']){
                                                        $num01++;
                                                    }
                                                }
//                                                         $username='u'.$data01['area_code'].'0001';
                                                $num01++;
                                                while(strlen($num01)<4){
                                                    $num01='0'.$num01;
                                                }
                                                $tenant_num=$data01['area_code'].$num01;

                                                $tenant_id=999999999-count($data02);
                                                $tenant_num=9999999-count($data02);
                                                $username='u'.$tenant_num;
                                                $ad_img1=$file_url.'client/advertise/ad_img1.png';
                                                $ad_img2=$file_url.'client/advertise/ad_img2.png';
                                                $ad_img3=$file_url.'client/advertise/ad_img3.png';
                                                $ad_img4=$file_url.'client/advertise/ad_img4.png';
                                                $ad_img5=$file_url.'client/advertise/ad_img5.png';
                                                $ad_img6=$file_url.'client/advertise/ad_img6.png';
                                                $ad_img7=$file_url.'client/advertise/ad_img7.png';
//                                                         $order_img='http://files.uminfo.cn:8000/tenant/5230001_order.jpg';
                                                $insertStatement = $database->insert(array('company','from_city_id','contact_id','exist','business_l','business_l_p'
                                                ,'sales_id','address','order_t_p','trans_contract_p','service_items','c_introduction'
                                                ,'begin_time','qq','email','insurance_balance','tenant_num','tenant_id','longitude','latitude','jcompany','ad_img1','ad_img2','ad_img3','ad_img4','ad_img5','ad_img6','ad_img7','order_img'))
                                                    ->into('tenant')
                                                    ->values(array($company,$from_city_id,$num,0,$business_l,$business_l_p
                                                    ,$sales_id,$address,$order_t_p, $trans_c_p
                                                    ,$service_items,$c_introduction,
                                                        $begin_time,$qq,$email,0,$tenant_num,$tenant_id,$longitude,$latitude,$jcompany,$ad_img1,$ad_img2,$ad_img3,$ad_img4,$ad_img5,$ad_img6,$ad_img7,$order_img));
                                                $insertId = $insertStatement->execute(false);

                                                if($insertId!=""||$insertId!=null){
                                                    $selectStatement = $database->select()
                                                        ->from('tenant')
                                                        ->where('company','=',$company)
                                                        ->where('business_l','=',$business_l)
                                                        ->where('contact_id','=',$num);
                                                    $stmt = $selectStatement->execute();
                                                    $data4 = $stmt->fetch();
                                                    $array=array();
                                                    $key='tenant_id';
                                                    $array[$key]=$data4['tenant_id'];
                                                    $updateStatement = $database->update($array)
                                                        ->table('customer')
                                                        ->where('customer_id','=',$num);
                                                    $affectedRows = $updateStatement->execute();
                                                    $insertStatement = $database->insert(array('tenant_id','staff_id','username','password'
                                                    ,'name','telephone','position','status','permission','bg_img','head_img','exist'))
                                                        ->into('staff')
                                                        ->values(array($data4['tenant_id'],100001,$username,encode('888888','cxphp'),$contact_name,$telephone,'演示员',1,1111111,$file_url.'client/skin/bg1.jpg',$file_url."staff/5230001_head.jpg",0));
                                                    $insertId = $insertStatement->execute(false);
                                                    echo json_encode(array('result'=>'0','desc'=>'添加成功','tenant_id'=>$tenant_id,'tenant_num'=>$tenant_num));
//                                                    $app->redirect('http://www.uminfo.cn/zhuce.html?desc=企业登记成功');
                                                }else{
//                                                    $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加租户信息失败');
                                                    echo json_encode(array("result"=>"1","desc"=>"添加租户信息失败"));
                                                }
                                            }else{
//                                                $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加负责人信息失败');
                                                echo json_encode(array("result"=>"2","desc"=>"添加负责人信息失败"));
                                            }
                                        }else {
//                                            $app->redirect('http://www.uminfo.cn/zhuce.html?desc=该业务员不存在');
                                            echo json_encode(array("result"=>"3","desc"=>"该业务员不存在"));
                                        }
//                                    }else {
////
//                                        echo json_encode(array("result"=>"4","desc"=>"该公司名已存在"));
//                                    }
                                }else{
                                    echo json_encode(array("result"=>"5","desc"=>"缺少sales_id"));
                                }
                            }else{
                                echo json_encode(array("result"=>"9","desc"=>"缺少发货城市"));
                            }
                        }else {
                            echo json_encode(array("result" => "10", "desc" => "缺少经营地址"));
                        }
                    }else{
                        echo json_encode(array("result"=>"11","desc"=>"缺少负责人电话"));
                    }
                }else{
                    echo json_encode(array("result"=>"12","desc"=>"缺少负责人姓名"));
                }
            }else{
                echo json_encode(array("result"=>"13","desc"=>"地理坐标不能为空"));
            }
//        }else{
//            echo json_encode(array("result"=>"14","desc"=>"缺少营业执照号码"));
//        }
    }else{
        echo json_encode(array("result"=>"15","desc"=>"缺少公司名称"));
    }
});

$app->options('/addSaleLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $app->response->headers->set("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,tenant-id");
});
$app->post('/addSaleLorry',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $database = localhost();
    $lorry_id= $body->lorry_id;
    $plate_number= $body->plate_number;
    $driver_name= $body->driver_name;
    $driver_phone= $body->driver_phone;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($lorry_id!=null||$lorry_id!=''){
            if($plate_number!=null||$plate_number!=''){
                if($driver_name!=null||$driver_name!=''){
                    if($driver_phone!=null||$driver_phone!=''){
                        $selectStatement = $database->select()
                            ->from('app_lorry')
                            ->where('plate_number', '=', $plate_number)
                            ->where('name', '=', $driver_name)
                            ->where('exist', '=', 0)
                            ->where('phone', '=', $driver_phone);
                        $stmt = $selectStatement->execute();
                        $data1 = $stmt->fetch();

                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('tenant_id', '=', $tenant_id)
                            ->where('exist','=',0)
                            ->where('plate_number', '=', $plate_number)
                            ->where('driver_name', '=', $driver_name)
                            ->where('driver_phone', '=', $driver_phone);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();

                        $selectStatement = $database->select()
                            ->from('lorry')
                            ->where('exist','=',0)
                            ->where('tenant_id', '=', $tenant_id)
                            ->where('driver_phone', '=', $driver_phone);
                        $stmt = $selectStatement->execute();
                        $data5 = $stmt->fetch();

                        $selectStatement = $database->select()
                            ->from('lorry');
                        $stmt = $selectStatement->execute();
                        $data = $stmt->fetchAll();

//                        if(!$data1){
//                            $password1=123456;
//                            $str1=str_split($password1,3);
//                            $password=null;
//                            for ($x=0;$x<count($str1);$x++){
//                                $password.=$str1[$x].$x;
//                            }
//                            $insertStatement = $database->insert(array('lorry_id','plate_number','driver_name','driver_phone','password','flag','driving_license','vehicle_travel_license'))
//                                ->into('lorry')
//                                ->values(array((count($data)+100000001),$plate_number,$driver_name,$driver_phone,$password,$flag,"http://files.uminfo.cn:8000/lorry/photo1.png","http://files.uminfo.cn:8000/lorry/photo2.png"));
//                            $insertId = $insertStatement->execute(false);
//                            $array['tenant_id']=$tenant_id;
//                            $array['exist']=0;
//                            $array['driving_license']="http://files.uminfo.cn:8000/lorry/photo1.png";
//                            $array['vehicle_travel_license']="http://files.uminfo.cn:8000/lorry/photo2.png";
//
//                            $insertStatement = $database->insert(array_keys($array))
//                                ->into('lorry')
//                                ->values(array_values($array));
//                            $insertId = $insertStatement->execute(false);
//                        }
                        if($data5){
                            echo json_encode(array("result" => "9", "desc" => "该电话号码已经注册过了"));
                        }else{
                            if(!$data1){
                                echo json_encode(array("result" => "10", "desc" => "请司机下载交付帮手注册"));
                            }else{
                                if(!$data4){
                                    $array['tenant_id']=$tenant_id;
                                    $array['exist']=0;
                                    $insertStatement = $database->insert(array_keys($array))
                                        ->into('lorry')
                                        ->values(array_values($array));
                                    $insertId = $insertStatement->execute(false);
                                    echo json_encode(array("result" => "0", "desc" => "success"));
                                }else{
                                    echo json_encode(array("result" => "1", "desc" => "司机已经添加"));
                                }
                            }

                        }
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "缺少驾驶员手机号码"));
                    }
                }else{
                    echo json_encode(array("result" => "5", "desc" => "缺少驾驶员名字"));
                }
            }else{
                echo json_encode(array("result" => "6", "desc" => "缺少车牌号"));
            }
        }else{
            echo json_encode(array("result" => "7", "desc" => "缺少车辆id"));
        }
    }else{
        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
    }
});

$app->options('/addSaleGood',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $app->response->headers->set("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,tenant-id");
});

$app->post('/addSaleGood',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $goods_id=$body->goods_id;
    $order_id = $body->order_id;
    $goods_name = $body->goods_name;
    $goods_weight= $body->goods_weight;
    $goods_package_id= $body->goods_package_id;
    $goods_count=$body->goods_count;
    $goods_value=$body->goods_value;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($goods_id!=null||$goods_id!=''){
            if($order_id!=null||$order_id!=''){
                if($goods_name!=null||$goods_name!=''){
                    if($goods_weight!=null||$goods_weight!=''){
                        if($goods_count!=null||$goods_count!=''){
                            $array['tenant_id']=$tenant_id;
                            $array['exist']=0;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('goods')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "1", "desc" => "缺少货物数量"));
                        }
                    }else{
                        echo json_encode(array("result" => "2", "desc" => "缺少货物重量"));
                    }
                }else{
                    echo json_encode(array("result" => "3", "desc" => "缺少货物名称"));
                }
            }else{
                echo json_encode(array("result" => "4", "desc" => "缺少运单id"));
            }
        }else{
            echo json_encode(array("result" => "5", "desc" => "缺少货物id"));
        }
    }else{
        echo json_encode(array("result" => "6", "desc" => "缺少租户id"));
    }
});

$app->options('/addSaleOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $app->response->headers->set("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,tenant-id");
});
$app->post('/addSaleOrder', function () use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    $sender_id = $body->sender_id;
    $receiver_id=$body->receiver_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if ($sender_id != null || $sender_id != "") {
        if ($receiver_id != null || $receiver_id > 0) {
            if ($order_id != null || $order_id != "") {
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('order_id', '=', $order_id);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data){
                    echo json_encode(array("result" => "5", "desc" => "运单id重复"));

                }else{
                    if($tenant_id!=null||$tenant_id!=''){
                        $array["is_schedule"]=0;
                        $array["is_transfer"]=0;
                        $array['exist']=0;
                        $array['tenant_id']=$tenant_id;
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('orders')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);
                        echo json_encode(array("result" => "0", "desc" => "success"));
                    }else{
                        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
                    }
                }

            } else {
                echo json_encode(array("result" => "3", "desc" => "缺少运单id"));
            }
        } else {
            echo json_encode(array("result" => "2", "desc" => "缺少收货人id"));
        }
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少发货人id"));
    }
});

$app->options('/addSaleCustomer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $app->response->headers->set("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,tenant-id");
});
$app->post('/addSaleCustomer',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $customer_id=$body->customer_id;
    $customer_name = $body->customer_name;
    $customer_phone = $body->customer_phone;
    $customer_city_id = $body->customer_city_id;
    $customer_address = $body->customer_address;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_id!=null||$customer_id!=''){
            if($customer_name!=null||$customer_name!=''){
                if($customer_phone!=null||$customer_phone!=''){
                    if($customer_city_id!=null||$customer_city_id!=''){
                        if($customer_address!=null||$customer_address!=''){
                            $array['tenant_id']=$tenant_id;
                            $array['exist']=0;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('customer')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        }else{
                            echo json_encode(array("result" => "2", "desc" => "缺少用户城市的地址"));
                        }
                    }else{
                        echo json_encode(array("result" => "3", "desc" => "缺少用户的城市id"));
                    }
                }else{
                    echo json_encode(array("result" => "4", "desc" => "缺少用户手机"));
                }
            }else{
                echo json_encode(array("result" => "5", "desc" => "缺少用户名字"));
            }
        }else{
            echo json_encode(array("result" => "6", "desc" => "缺少用户id"));
        }
    }else{
        echo json_encode(array("result" => "7", "desc" => "缺少租户id"));
    }
});
$app->run();

function localhost(){
    return connect();
}

function file_url(){
    return files_url();
}

//加密
function encode($string , $skey ) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}
?>
