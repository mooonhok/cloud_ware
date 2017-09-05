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
    $app->response->headers->set('Content-Type', 'application/json');
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=localhost();
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data));
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist',"=",0)
                ->limit((int)$per_page,(int)$per_page*(int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data));
        }
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




$app->post('/tenant',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $qq=$app->request->params('qq');
    $address=$app->request->params('address');
    $begin_time=$app->request->params('begin_time');
    $end_time=$app->request->params('end_time');
    $business_l=$app->request->params('business_l');
    $business_l_p=$app->request->params('business_l_p');
    $company=$app->request->params('company');
    $contact_name=$app->request->params('contact_name');
    $from_city_id=$app->request->params('from_city_id');
    $c_introduction=$app->request->params('c_introduction');
    $email=$app->request->params('email');
    $order_t_p=$app->request->params('order_t_p');
    $qq=$app->request->params('qq');
    $receive_city_id=$app->request->params('receive_city_id');
    $sales_id=$app->request->params('sales_id');
    $service_items=$app->request->params('service_items');
    $trans_contract_p=$app->request->params('trans_contract_p');

//    $body=$app->request->getBody();
//    $body=json_decode($body);
//    $address=$body->address;
//    $begin_time=$body->begin_time;
//    $end_time=$body->end_time;
//    $business_l=$body->business_l;
//    $business_l_p=$body->business_l_p;
//    $company=$body->company;
//    $contact_name=$body->contact_name;
//    $c_introduction=$body->c_introduction;
//    $email=$body->email;
//    $from_city_id=$body->from_city_id;
//    $order_t_p=$body->order_t_p;
//    $qq=$body->qq;
//    $receive_city_id=$body->receive_city_id;
//    $sales_id=$body->sales_id;
//    $service_items=$body->service_items;
//    $trans_contract_p=$body->trans_contract_p;
//    $array=array();
//    foreach($body as $key=>$value){
//        $array[$key]=$value;
//    }

//    $name=$_FILES["myfile"]["name"];
//    $name=iconv("UTF-8","gb2312", $name);
//    move_uploaded_file($_FILES["myfile"]["tmp_name"],"upload/". $name."");
//    $fwendan="upload/".$_FILES["myfile"]["name"]."";
//    if($address!=null||$address!=''){
//        if($begin_time!=null||$begin_time!=''){
//            if($end_time!=null||$end_time!=''){
//                if($business_l!=null||$business_l!=''){
//                     if($business_l_p!=null||$business_l_p!=''){
//                        if($company!=null||$company!=''){
//                           if($contact_name!=null||$contact_name!=''){
//                               if($c_introduction!=''||$c_introduction!=null){
//                                   if($email!=null||$email!=''){
//                                       if($from_city_id!=null||$from_city_id!=''){
//                                           if($order_t_p!=null||$order_t_p!=''){
//                                              if($qq!=null||$qq!=''){
//                                                 if($receive_city_id!=null||$receive_city_id!=''){
//                                                    if($sales_id!=null||$sales_id!=''){
//                                                        if($service_items!=null||$service_items!=''){
//
//                                                        }else{
//                                                            echo json_encode(array("result"=>"2","desc"=>"缺少服务项目"));
//                                                        }
//                                                    }else{
//                                                        echo json_encode(array("result"=>"2","desc"=>"缺少销售人员id"));
//                                                    }
//                                                 }else{
//                                                     echo json_encode(array("result"=>"2","desc"=>"缺少收货城市id"));
//                                                 }
//                                              }else{
//                                                  echo json_encode(array("result"=>"2","desc"=>"缺少qq"));
//                                              }
//                                           }else{
//                                               echo json_encode(array("result"=>"2","desc"=>"缺少订单运输协议"));
//                                           }
//                                       }else{
//                                           echo json_encode(array("result"=>"2","desc"=>"缺少发货城市id"));
//                                       }
//                                   }else{
//                                       echo json_encode(array("result"=>"2","desc"=>"缺少公司邮箱"));
//                                   }
//                               }else{
//                                   echo json_encode(array("result"=>"2","desc"=>"缺少公司介绍"));
//                               }
//                           }else{
//                               echo json_encode(array("result"=>"2","desc"=>"缺少公司负责人姓名"));
//                           }
//                        }else{
//                            echo json_encode(array("result"=>"2","desc"=>"缺少公司名字"));
//                        }
//                     }else{
//                         echo json_encode(array("result"=>"2","desc"=>"缺少营业执照片"));
//                     }
//                }else{
//                    echo json_encode(array("result"=>"2","desc"=>"缺少营业执照号码"));
//                }
//            }else{
//                echo json_encode(array("result"=>"2","desc"=>"缺少结束时间"));
//            }
//        }else{
//            echo json_encode(array("result"=>"2","desc"=>"缺少开始时间"));
//        }
//    }else{
//        echo json_encode(array("result"=>"2","desc"=>"缺少办公地址"));
//    }
//                                                    $insertStatement = $database->insert(array('order_t_p','trans_contract_p'))
//                                                        ->into('tenantceshi')
//                                                        ->values(array($order_t_p,$trans_contract_p));
//                                                    $insertId = $insertStatement->execute(false);
//                                                    echo json_encode(array("result"=>"0","desc"=>"success"));
});

$app->get('/tenant_customer',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
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


$app->run();

function localhost(){
    return connect();
}
?>