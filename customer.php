<?php
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();

$app->post('/customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
	$tenant_id=$app->request->headers->get("tenant-id");
	$body = $app->request->getBody();
	$body=json_decode($body);
	$customer_name=$body->customer_name;
	$customer_phone=$body->customer_phone;
	$customer_city_id=$body->customer_city_id;
	$customer_address=$body->customer_address;
	$array=array();
    foreach($body as $key=>$value){
    	$array[$key]=$value;
    }
 if($tenant_id!=""||$tenant_id!=null){
     $selectStatement = $database->select()
         ->from('tenant')
         ->where('exist',"=",0)
         ->where('tenant_id','=',$tenant_id);
     $stmt = $selectStatement->execute();
     $data2 = $stmt->fetch();
     if($data2!=null){
    if($customer_name!=""||$customer_name!=null){
        if($customer_phone>0||$customer_phone!=null){
            if(preg_match("/^1[34578]\d{9}$/", $customer_phone)) {
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('exist',"=",0)
                    ->where('customer_phone',"=",$customer_phone)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                if($data3==null){
                if ($customer_city_id != "" || $customer_city_id != null) {
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$customer_city_id);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    if($data4!=null){
                    if ($customer_address != "" || $customer_address != null) {
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('tenant_id', '=', $tenant_id);
                        $stmt = $selectStatement->execute();
                        $data = $stmt->fetchAll();
                        if ($data == null) {
                            $customer_id = 10000001;
                        } else {
                            $customer_id = count($data) + 10000001;
                        }
                        $array["customer_id"] = $customer_id;
                        $array["tenant_id"] = $tenant_id;
                        $array["exist"] = 0;
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('customer')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);

                        echo json_encode(array("result" => "0", "desc" => "success"));
                    } else {
                        echo json_encode(array("result" => "1", "desc" => "缺少客户地址"));
                    }
                    } else {
                        echo json_encode(array("result" => "2", "desc" => "客户城市不存在"));
                    }
                } else {
                    echo json_encode(array("result" => "3", "desc" => "缺少客户城市"));
                }
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"该公司该电话已经存在"));
                }
            }else {
                echo json_encode(array("result" => "5", "desc" => "电话不符和要求"));
            }

        }else{
            echo json_encode(array("result"=>"6","desc"=>"缺少客户电话"));
        }
    }else{
        echo json_encode(array("result"=>"7","desc"=>"缺少客户姓名"));
    }
     }else{
         echo json_encode(array("result"=>"8","desc"=>"租户不存在"));
     }
 }else{
     echo json_encode(array("result"=>"9","desc"=>"缺少租户id"));
 }
});



$app->get('/customers',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$page=$app->request->get('page');
	$per_page=$app->request->get("per_page");
    $database=localhost();
	if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
			if($page==null||$per_page==null){
			    $selectStatement = $database->select()
                                 ->from('customer')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo  json_encode(array("result"=>"0","desc"=>"success","customers"=>$data));
	        }else{
		        $selectStatement = $database->select()
                                 ->from('customer')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('exist',"=",0)
                                 ->limit((int)$per_page,(int)$per_page*(int)$page);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","customers"=>$data));
	        }
        }else{
            echo json_encode(array("result"=>"1","desc"=>"租户不存在"));
        }
	}else{
		echo json_encode(array("result"=>"2","desc"=>"缺少租户id","orders"=>""));
	}
});


$app->get("/customer",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
	$customer_id=$app->request->get("customerid");
    $database=localhost();
	if($tenant_id!=null||$tenant_id!=""){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist',"=",0)
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            if($data2!=null){
	    if($customer_id!=null||$customer_id!=""){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$customer_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                echo json_encode(array("result"=>"0","desc"=>"success","customer"=>$data));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"客户不存在","customer"=>''));
            }
            }else{
             echo json_encode(array("result"=>"2","desc"=>"缺少客户id","customer"=>""));
          }
            }else{
                echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
            }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"缺少租户id","customer"=>""));
    }
});


$app->put('/customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
	$body=$app->request->getBody();
    $body=json_decode($body);
	$customer_id=$body->customer_id;
	$customer_comment=$body->customer_comment;
	if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
         if($customer_id!=null||$customer_id!=""){
                 $selectStatement = $database->select()
                     ->from('customer')
                     ->where('tenant_id','=',$tenant_id)
                     ->where('customer_id','=',$customer_id)
                     ->where('exist',"=",0);
                 $stmt = $selectStatement->execute();
                 $data = $stmt->fetch();
                 if($data!=null){
                     if($customer_comment!=null||$customer_comment!=""){
                     $updateStatement = $database->update(array('customer_comment'=>$customer_comment))
                         ->table('customer')
                         ->where('tenant_id','=',$tenant_id)
                         ->where('customer_id','=',$customer_id)
                         ->where('exist',"=",0);
                     $affectedRows = $updateStatement->execute();
                     echo json_encode(array("result"=>"0","desc"=>"success"));
                 }else{
                     echo json_encode(array("result"=>"1","desc"=>"缺少客户备注信息"));
                 }
              }else{
                 echo json_encode(array("result"=>"2","desc"=>"客户不存在"));
              }
          }else{
             echo json_encode(array("result"=>"3","desc"=>"缺少客户id"));
          }
        }else{
            echo json_encode(array("result"=>"4","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"5","desc"=>"缺少租户id"));
    }
});

$app->delete('/customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $customer_id=$app->request->get('customerid');
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if($customer_id!=null||$customer_id!=""){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$customer_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $updateStatement = $database->update(array('exist'=>1))
                    ->table('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$customer_id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"客户不存在"));
            }
        }else{
            echo json_encode(array("result"=>"2",'desc'=>'缺少客户id'));
        }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});

//用户注册
$app->post('/wx_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=""||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            if($customer_name!=""||$customer_name!=null){
                if($customer_phone>0||$customer_phone!=null){
                        $selectStatement = $database->select()
                            ->from('customer')
                            ->where('exist',"=",0)
                            ->where('customer_phone',"=",$customer_phone)
                            ->where('tenant_id','=',$tenant_id);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        if($data3==null) {
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetchAll();
                            if ($data == null) {
                                $customer_id = 10000001;
                            } else {
                                $customer_id = count($data) + 10000001;
                            }
                            $array['customer_address']='-1';
                            $array['customer_city_id']='-1';
                            $array["customer_id"] = $customer_id;
                            $array["tenant_id"] = $tenant_id;
                            $array["exist"] = 0;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('customer')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);

                            echo json_encode(array("result" => "0", "desc" => "success"));

                        }else{
                            echo json_encode(array("result"=>"1","desc"=>"该公司该电话已经存在"));
                        }

                }else{
                    echo json_encode(array("result"=>"3","desc"=>"缺少客户电话"));
                }
            }else{
                echo json_encode(array("result"=>"4","desc"=>"缺少客户姓名"));
            }
        }else{
            echo json_encode(array("result"=>"5","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"6","desc"=>"缺少租户id"));
    }
});

//微信，进入每个页面查询是否注册
$app->get('/wx_openid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
//    $body = $app->request->getBody();
//    $body=json_decode($body);
    $wx_openid=$app->request->get("wx_openid");
    if($tenant_id!=""||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('exist',"=",0)
                ->where('customer_address','=',"-1")
                ->where('customer_city_id','=','-1')
                ->where('wx_openid','=',$wx_openid)
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            if($data3==null){
                echo json_encode(array("result"=>"0","desc"=>"去注册"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"用户已注册"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});


//微信获得所有地址
$app->post('/wxaddress',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $wx_openid=$app->request->get('wx_openid');
    if($tenant_id!=null||$tenant_id!=''){
           if($wx_openid!=null||$wx_openid!=''){
               $selectStatement = $database->select()
                   ->from('tenant')
                   ->where('exist',"=",0)
                   ->where('tenant_id','=',$tenant_id);
               $stmt = $selectStatement->execute();
               $data1 = $stmt->fetch();
               if($data1!=null){
                   $selectStatement = $database->select()
                       ->from('customer')
                       ->where('exist',"=",0)
                       ->where('customer_address','!=',-1)
                       ->where('customer_city_id','!=',-1)
                       ->where('wx_openid','=',$wx_openid)
                       ->where('tenant_id','=',$tenant_id);
                   $stmt = $selectStatement->execute();
                   $data2 = $stmt->fetchAll();
                   $num=count($data2);
                   for($i=0;$i<$num;$i++){
                       $selectStatement = $database->select()
                           ->from('city')
                           ->where('id',"=",$data2[$i]['customer_city_id']);
                       $stmt = $selectStatement->execute();
                       $data3 = $stmt->fetch();
                       $selectStatement = $database->select()
                           ->from('province')
                           ->where('id',"=",$data3['pid']);
                       $stmt = $selectStatement->execute();
                       $data4 = $stmt->fetch();
                       $data2[$i]['customer_city']=$data3['name'];
                       $data2[$i]['customer_province']=$data4['name'];
                   }
                   echo json_encode(array("result"=>"1","desc"=>"success","wxmessage"=>$data2));
               }else{
                   echo json_encode(array("result"=>"2","desc"=>"租户不存在"));
               }
           }else{
               echo json_encode(array("result"=>"3","desc"=>"openid为空"));
           }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"租户为空"));
    }
});


//微信添加寄件人、收件人的地址详情
$app->post('/plus_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $wx_openid=$body->wx_openid;
    $type=$body->type;
    $adress=$body->address;
    $city_id=$body->city_id;
    $customer_name=$body->customer_name;
    $phone=$body->customer_phone;
    if($tenant_id!=null||$tenant_id!=''){
       if($wx_openid!=null||$wx_openid!=''){
           $selectStatement = $database->select()
               ->from('customer')
               ->where('exist',"=",0)
               ->where('type',"=",$type)
               ->where('customer_address',"=",$adress)
               ->where('customer_city_id',"=",$city_id)
               ->where('customer_name','=',$customer_name)
               ->where('customer_phone','=',$phone)
               ->where('wx_openid','=',$wx_openid)
               ->where('tenant_id','=',$tenant_id);
           $stmt = $selectStatement->execute();
           $data1 = $stmt->fetch();
           if($data1==null){
               $selectStatement = $database->select()
                   ->from('customer')
                   ->where('tenant_id','=',$tenant_id);
               $stmt = $selectStatement->execute();
               $data2 = $stmt->fetchAll();
               $insertStatement = $database->insert(array('exist','tenant_id','wx_openid','type','customer_id','customer_address','customer_city_id','customer_name','customer_phone'))
                   ->into('customer')
                   ->values(array(0,$tenant_id,$wx_openid,$type,count($data2)+10000001,$adress,$city_id,$customer_name,$phone));
               $insertId = $insertStatement->execute(false);
               if($insertId!=null){
//                   $selectStatement = $database->select()
//                       ->from('customer')
//                       ->where('exist',"=",0)
//                       ->where('type',"=",$type)
//                       ->where('wx_openid','=',$wx_openid)
//                       ->where('tenant_id','=',$tenant_id);
//                   $stmt = $selectStatement->execute();
//                   $data2 = $stmt->fetchAll();
                   echo json_encode(array("result"=>"1","desc"=>"success"));
               }else{
                   echo json_encode(array("result"=>"2","desc"=>"添加未执行"));
               }
           }else{
               $selectStatement = $database->select()
                   ->from('customer')
                   ->where('exist',"=",0)
                   ->where('type',"=",$type)
                   ->where('wx_openid','=',$wx_openid)
                   ->where('tenant_id','=',$tenant_id);
               $stmt = $selectStatement->execute();
               $data2 = $stmt->fetchAll();
               echo json_encode(array("result"=>"1","desc"=>"success",'customers'=>$data2));
           }
       }else{
           echo json_encode(array("result"=>"4","desc"=>"缺少openid"));
       }
    }else{
        echo json_encode(array("result"=>"5","desc"=>"缺少租户id"));
    }
});

////批量上传，无则增加，有则修改
//$app->post('/customers_insert',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $database=localhost();
//    $tenant_id=$app->request->headers->get("tenant-id");
//    $body = $app->request->getBody();
//    $array=array();
//    $body=json_decode($body);
//    foreach($body as $key=>$value){
//        $array[$key]=$value;
//    }
//    $num=count($array);
////    $aa=$array[0];
////    $array1=array();
////    foreach($aa as $key=>$value){
////        $array1[$key]=$value;
////    }
//
////echo count($array1);
//    for($i=0;$i<$num;$i++){
//        $array1=array();
//        foreach($array[$i] as $key=>$value){
//            $array1[$key]=$value;
//        }
//        $selectStatement = $database->select()
//            ->from('customer')
//            ->where('customer_id','=',$array1['customer_id'])
//            ->where('exist','=',0)
//            ->where('tenant_id','=',$tenant_id);
//        $stmt = $selectStatement->execute();
//        $data1 = $stmt->fetch();
//        if($data1==null){
//            $array1['tenant_id']=$tenant_id;
//            $insertStatement = $database->insert(array_keys($array1))
//                ->into('customer')
//                ->values(array_values($array1));
//            $insertId = $insertStatement->execute(false);
//        }else{
//            $updateStatement = $database->update($array1)
//                ->table('customer')
//                ->where('tenant_id','=',$tenant_id)
//                ->where('customer_id','=',$array1['customer_id'])
//                ->where('exist',"=",0);
//            $affectedRows = $updateStatement->execute();
//        }
//    }
//    echo json_encode(array("result"=>"1","desc"=>"success"));
//});
//批量上传，有改无增
$app->post('/customer_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $customer_id=$body->customer_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $selectStatement = $database->select()
        ->from('customer')
        ->where('customer_id','=',$customer_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('customer')
            ->where('tenant_id','=',$tenant_id)
            ->where('customer_id','=',$customer_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $array['tenant_id']=$tenant_id;
        $insertStatement = $database->insert(array_keys($array))
            ->into('customer')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});

//客户端，根据tenant_id，查出tenant和customer
//$app->get('/tenant_customer',function()use($app){
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $tenant_id=$app->request->headers->get('tenant-id');
//    if($tenant_id!=null||$tenant_id!=''){
//           $array=array();
//           $selectStatement = $database->select()
//               ->from('tenant')
//               ->where('exist','=',0)
//               ->where('tenant_id','=',$tenant_id);
//           $stmt = $selectStatement->execute();
//           $data1 = $stmt->fetch();
//           if($data1!=null){
//               $selectStatement = $database->select()
//                   ->from('customer')
//                   ->where('exist','=',0)
//                   ->where('tenant_id','=',$tenant_id);
//               $stmt = $selectStatement->execute();
//               $data2 = $stmt->fetchAll();
//               if($data2!=null){
//                   $array['tenant']=$data1;
//                   $array['customer']=
//                   echo json_encode(array("result"=>"1","desc"=>"success",'customer'=>$array));
//               }else{
//                   echo json_encode(array("result"=>"2","desc"=>"客户不存在"));
//               }
//           }else{
//               echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
//           }
//    }else{
//        echo json_encode(array("result"=>"4","desc"=>"租户id为空"));
//    }
//});



//客户端添加customer
$app->post('/khd_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $customer_id=$body->customer_id;
    $customer_name=$body->customer_name;
    $customer_phone=$body->customer_phone;
    $customer_city_id=$body->customer_city_id;
    $customer_address=$body->customer_address;
    $contact_tenant_id=$body->contact_tenant_id;
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
                       if($contact_tenant_id!=null||$contact_tenant_id!=''){
                           $insertStatement= $database->insert(array_keys($array))
                               ->into('customer')
                               ->values(array_values($array));
                           $insertId = $insertStatement->execute(false);
                           if($insertId>0){
                               echo json_encode(array('result'=>'','desc'=>'添加成功'));
                           }else{
                               echo json_encode(array('result'=>'','desc'=>'添加失败'));
                           }
                       }else{
                           echo json_encode(array('result'=>'','desc'=>'合作公司id为空'));
                       }
                   }else{
                       echo json_encode(array('result'=>'','desc'=>'客户地址为空'));
                   }
                }else{
                    echo json_encode(array('result'=>'','desc'=>'客户城市id为空'));
                }
             }else{
                 echo json_encode(array('result'=>'','desc'=>'客户电话为空'));
             }
          }else{
              echo json_encode(array('result'=>'1','desc'=>'客户名字为空'));
          }
       }else{
           echo json_encode(array('result'=>'1','desc'=>'客户id为空'));
       }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户公司id为空'));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>