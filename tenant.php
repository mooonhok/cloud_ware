<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 9:10
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->post('/tenant',function()use($app){
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $body=$app->request->getBody();
//    $body=json_decode($body);
//    $company=$body->company;
//    $company_type=$body->company_type;
//    $from_city_id=$body->from_city_id;
//    $receive_city_id=$body->receive_city_id;
//    $contact_id=$body->contact_id;
//    $array=array();
//    foreach($body as $key=>$value){
//        $array[$key]=$value;
//    }
//    if($company!=null||$company!=''){
//        if($company_type!=null||$company_type!=''){
//            if($from_city_id!=null||$from_city_id!=''){
//                if($receive_city_id!=null||$receive_city_id!=''){
//                        if($contact_id!=null||$contact_id!=''){
//                                    $selectStatement = $database->select()
//                                        ->from('tenant')
//                                        ->where('company','=',$company);
//                                    $stmt = $selectStatement->execute();
//                                    $data = $stmt->fetch();
//                                    if($data!=null){
//                                        echo json_encode(array("result"=>"1","desc"=>"公司已存在"));
//                                    }else{
//                                        $selectStatement = $database->select()
//                                            ->from('city')
//                                            ->where('id','=',$from_city_id);
//                                        $stmt = $selectStatement->execute();
//                                        $data1 = $stmt->fetch();
//                                        if($data1==null){
//                                            echo json_encode(array("result"=>"2","desc"=>"发货人城市不存在"));
//                                        }else{
//                                            $selectStatement = $database->select()
//                                                ->from('city')
//                                                ->where('id','=',$receive_city_id);
//                                            $stmt = $selectStatement->execute();
//                                            $data2 = $stmt->fetch();
//                                            if($data2==null){
//                                                echo json_encode(array("result"=>"3","desc"=>"收货人城市不存在"));
//                                            }else{
//                                                $selectStatement = $database->select()
//                                                    ->from('customer')
//                                                    ->where('exist','=','0')
//                                                    ->where('customer_id','=',$contact_id);
//                                                $stmt = $selectStatement->execute();
//                                                $data3 = $stmt->fetch();
//                                                if($data3==null){
//                                                    echo json_encode(array("result"=>"4","desc"=>"公司联系人不存在"));
//                                                }else{
//                                                    $selectStatement = $database->select()
//                                                        ->from('tenant');
//                                                    $stmt = $selectStatement->execute();
//                                                    $data4 = $stmt->fetchAll();
//                                                    $tenant_id=10000001+count($data4);
//                                                    $array['tenant_id']=$tenant_id;
//                                                    $array['exist']=0;
//                                                    $insertStatement = $database->insert(array_keys($array))
//                                                        ->into('tenant')
//                                                        ->values(array_values($array));
//                                                    $insertId = $insertStatement->execute(false);
//                                                    echo json_encode(array("result"=>"0","desc"=>"success"));
//                                                }
//                                            }
//                                        }
//                                    }
//                        }else{
//                            echo json_encode(array('result'=>'5','desc'=>'缺少联系人id'));
//                        }
//                }else{
//                    echo json_encode(array('result'=>'6','desc'=>'缺少收货城市id'));
//                }
//            }else{
//                echo json_encode(array('result'=>'7','desc'=>'缺少发货城市id'));
//            }
//        }else{
//            echo json_encode(array('result'=>'8','desc'=>'缺少公司类型'));
//        }
//    }else{
//        echo json_encode(array('result'=>'9','desc'=>'缺少公司名字'));
//    }
//});

$app->put('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $company=$body->company;
    $company_type=$body->company_type;
    $from_city_id=$body->from_city_id;
    $receive_city_id=$body->receive_city_id;
    $contact_id=$body->contact_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($company!=null||$company!=''){
            if($company_type!=null||$company_type!=''){
                if($from_city_id!=null||$from_city_id!=''){
                    if($receive_city_id!=null||$receive_city_id!=''){
                        if($contact_id!=null||$contact_id!=''){
                            $selectStatement = $database->select()
                                ->from('tenant')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('exist','=','0');
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetch();
                            if($data!=null){
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('exist','=','0')
                                    ->where('tenant_id','=',$tenant_id)
                                    ->where('company','!=',$company);
                                $stmt = $selectStatement->execute();
                                $data1= $stmt->fetch();
                                if($data1==null){
                                    echo json_encode(array("result"=>"1","desc"=>"公司名字已存在"));
                                }else{
                                    $selectStatement = $database->select()
                                        ->from('city')
                                        ->where('id','=',$from_city_id);
                                    $stmt = $selectStatement->execute();
                                    $data2 = $stmt->fetch();
                                    if($data2==null){
                                        echo json_encode(array("result"=>"2","desc"=>"发货人城市不存在"));
                                    }else{
                                        $selectStatement = $database->select()
                                            ->from('city')
                                            ->where('id','=',$receive_city_id);
                                        $stmt = $selectStatement->execute();
                                        $data3 = $stmt->fetch();
                                        if($data3==null){
                                            echo json_encode(array("result"=>"3","desc"=>"收货人城市不存在"));
                                        }else{
                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->where('exist','=','0')
                                                ->where('customer_id','=',$contact_id);
                                            $stmt = $selectStatement->execute();
                                            $data4 = $stmt->fetch();
                                            if($data4==null){
                                                echo json_encode(array("result"=>"4","desc"=>"公司联系人不存在"));
                                            }else{
                                                $array['exist']="0";
                                                $updateStatement = $database->update($array)
                                                    ->table('tenant')
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                                echo json_encode(array("result"=>"0","desc"=>"success"));
                                            }
                                        }
                                    }
                                }

                            }else{
                                echo json_encode(array("result"=>"6","desc"=>"该公司不存在"));
                            }
                        }else{
                            echo json_encode(array('result'=>'7','desc'=>'缺少公司联系人id'));
                        }
                    }else{
                        echo json_encode(array('result'=>'8','desc'=>'缺少收货城市id'));
                    }
                }else{
                    echo json_encode(array('result'=>'9','desc'=>'缺少发货城市id'));
                }
            }else{
                echo json_encode(array('result'=>'10','desc'=>'缺少公司类型'));
            }
        }else{
            echo json_encode(array('result'=>'11','desc'=>'缺少公司名字'));
        }
    }else{
        echo json_encode(array('result'=>'12','desc'=>'缺少租户公司的id'));
    }

});


$app->get('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $page=$app->request->get('page');
    $page=(int)$page-1;
    $from_city_id=$app->request->get('from_city_id');
    $company=$app->request->get('company');
    $per_page=10;
    $database=localhost();
    if(($from_city_id!=null||$from_city_id!='')&&($company!=null||$company!='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->whereLike('company','%'.$company.'%')
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->whereLike('company','%'.$company.'%');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id==null||$from_city_id=='')&&($company!=null||$company!='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->whereLike('company','%'.$company.'%')
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->whereLike('company','%'.$company.'%');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id!=null||$from_city_id!='')&&($company==null||$company=='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id==null||$from_city_id=='')&&($company==null||$company=='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }
});

$app->get('/tenant_introduction',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database=localhost();
    $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id',"=",$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('customer')
        ->where('customer_id',"=",$data['contact_id']);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data,'contact'=>$data1));
});

$app->delete('/tenant',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenantid');
    if ($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $updateStatement = $database->update(array('exist'=>1))
                    ->table('tenant')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"该公司不存在"));
            }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->post('/tenant',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $qq = $app->request->params('qq');
    $address = $app->request->params('address');
    $begin_time = $app->request->params('begin_time');
    $end_time = $app->request->params('end_time');
    $business_l = $app->request->params('business_l');
    //$business_l_p = $app->request->params('business_l_p');
    $company = $app->request->params('company');
    $contact_name = $app->request->params('contact_name');
    $from_city_id = $app->request->params('from_city_id');
    $c_introduction = $app->request->params('c_introduction');
    $email = $app->request->params('email');
  //  $order_t_p = $app->request->params('order_t_p');
    $receive_city_id = $app->request->params('receive_city_id');
    $sales_id = $app->request->params('sales_id');
    $service_items = $app->request->params('service_items');
 //   $trans_contract_p = $app->request->params('trans_contract_p');
    $telephone=$app->request->params('telephone');
    $name= $_FILES["order_t_p"]["name"];
    $name=iconv("UTF-8","gb2312", $name);
    $name=rand(1,100000).$name;
   move_uploaded_file($_FILES["order_t_p"]["tmp_name"], '/files/order_t_p/'.$name);
   $order_t_p= 'http://files.uminfo.cn:8000/order_t_p/'.$name.'';
    $name2=$_FILES["trans_contract_p"]["name"];
    $name2=iconv("UTF-8","gb2312", $name2);
    $name2=rand(1,100000).$name2;
   move_uploaded_file($_FILES["trans_contract_p"]["tmp_name"],"/files/trans_contract_p/".$name2);
   $trans_c_p='http://files.uminfo.cn:8000/trans_contract_p/'.$name2.'';
    $name3=$_FILES["file1"]["name"];
    $name3=iconv("UTF-8","gb2312", $name3);
    $name3=rand(1,100000).$name3;
    move_uploaded_file($_FILES["file1"]["tmp_name"],"/files/business_l_p/".$name3);
    $business_l_p='http://files.uminfo.cn:8000/business_l_p/'.$name3.'';
    if($company!=null||$company!=""){
        if($business_l!=""||$business_l!=null){
             if($business_l_p!=""||$business_l_p!=null){
                 if($contact_name!=null||$contact_name!=""){
                     if($telephone!=null||$telephone!=""){
                         if($address!=""||$address!=null){
                             if($from_city_id!=""||$from_city_id=null){
                                 if($receive_city_id!=null||$receive_city_id!=""){
                                     if($begin_time!=null||$begin_time!=""){
                                         if($end_time!=null||$end_time!=""){
                                             if($sales_id!=null||$sales_id!=""){
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
                                                                    $insertStatement = $database->insert(array('company','from_city_id','receive_city_id','contact_id','exist','business_l'
                                                                    ,'sales_id','address','business_l_p','order_t_p','trans_contract_p','service_items','c_introduction','end_date'
                                                                    ,'begin_time','qq','email','insurance_balance'))
                                                                        ->into('tenant')
                                                                        ->values(array($company,$from_city_id,$receive_city_id,$num,0,$business_l
                                                                        ,$sales_id,$address,$business_l_p,$order_t_p, $trans_c_p
                                                                        ,$service_items,$c_introduction,$end_time,
                                                                            $begin_time,$qq,$email,0));
                                                                    $insertId = $insertStatement->execute(false);
                                                                    if($insertId!=""||$insertId!=null){
                                                                        $selectStatement = $database->select()
                                                                            ->from('tenant')
                                                                            ->where('company','=',$company)
                                                                            ->where('business_l','=',$business_l);
                                                                        $stmt = $selectStatement->execute();
                                                                        $data4 = $stmt->fetch();
                                                                        $array=array();
                                                                        $key='tenant_id';
                                                                        $array[$key]=$data4['tenant_id'];
                                                                        $updateStatement = $database->update($array)
                                                                            ->table('customer')
                                                                            ->where('customer_id','=',$num);
                                                                        $affectedRows = $updateStatement->execute();
                                                                        //echo json_encode(array('result'=>'0','desc'=>'添加成功'));
                                                                        $app->redirect('http://www.uminfo.cn/yonhu.html');
                                                                    }else{
                                                                        echo json_encode(array("result"=>"1","desc"=>"添加租户信息失败"));
                                                                    }
                                                                }else{
                                                                    echo json_encode(array("result"=>"3","desc"=>"添加负责人信息失败"));
                                                                }
                                                            }else {
                                                                echo json_encode(array("result"=>"4","desc"=>"该业务员不存在"));
                                                            }
                                                        }else{
                                                            echo json_encode(array("result"=>"5","desc"=>"缺少sales_id"));
                                                        }
                                                    }else{
                                                        echo json_encode(array("result"=>"6","desc"=>"结束时间不能为空"));
                                                    }
                                              }else{
                                                  echo json_encode(array("result"=>"7","desc"=>"开始时间不能为空"));
                                              }
                                          }else{
                                              echo json_encode(array("result"=>"8","desc"=>"缺少收货城市"));
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
                 echo json_encode(array("result"=>"13","desc"=>"缺少营业执照照片"));
             }
        }else{
            echo json_encode(array("result"=>"14","desc"=>"缺少营业执照号码"));
        }
    }else{
        echo json_encode(array("result"=>"15","desc"=>"缺少公司名称"));
    }
});


$app->get('/tenant_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=localhost();
    if($page==null||$per_page==null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $num=count($data);
        $array=array();
        for($i=0;$i<$num;$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id','=',$data[$i]['contact_id'])
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data1['customer_city']=$data2['name'];
            $data[$i]['from_city']=$data3['name'];
            $data[$i]['receive_city']=$data4['name'];
            $array1=array();
            $array1['customer']=$data1;
            $array1['tenant']=$data[$i];
            array_push($array,$array1);
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$array));
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $num=count($data);
        $array=array();
        for($i=0;$i<$num;$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id','=',$data[$i]['contact_id'])
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $array1=array();
            $array1['customer']=$data1;
            $array1['tenant']=$data[$i];
            array_push($array,$array1);
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$array));
    }
});

$app->get('/one_tenant_customer',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database=localhost();
    $array=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1['contact_id'])
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
                ->where('id','=',$data1['from_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data1['from_city']=$data4['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data1['receive_city']=$data5['name'];
            $array['tenant']=$data1;
            $array['customer']=$data2;
            echo  json_encode(array("result"=>"1","desc"=>"success","tenant"=>$array));
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"租户公司不存在","tenant"=>''));
        }
    }else{
        echo  json_encode(array("result"=>"3","desc"=>"租户公司id为空","tenant"=>''));
    }
});

$app->run();

function localhost(){
    return connect();
}


?>