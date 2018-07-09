<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 10:27
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//微信添加
$app->post('/wxmessage_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
//    $customer_name_s=$body->customer_name_s;
//    $customer_city_s=$body->customer_city_s;
//    $customer_adress_s=$body->customer_address_s;
//    $customer_phone_s=$body->customer_phone_s;
//    $customer_name_a=$body->customer_name_a;
//    $customer_city_a=$body->customer_city_a;
//    $customer_address_a=$body->customer_address_a;
//    $customer_phone_a=$body->customer_phone_a;
    $customer_send_id=$body->customer_send_id;
    $customer_accept_id=$body->customer_accept_id;
    $goods_name=$body->goods_name;
    $goods_weight=$body->goods_weight;
    $goods_capacity=$body->goods_capacity;
    $goods_package=$body->goods_package;
    $goods_count=$body->goods_count;
    $special_need=$body->special_need;
    $good_worth=$body->good_worth;
    $pay_method=$body->pay_method;
    $wx_openid=$body->openid;
    if($tenant_id!=''||$tenant_id!=null){
//        if($customer_name_s!=''||$customer_name_s!=null){
//            if($customer_city_s!=''||$customer_city_s!=null){
//                if($customer_adress_s!=''||$customer_adress_s!=null){
//                        if($customer_phone_s!=''||$customer_phone_s!=null){
//                            if($customer_name_a!=''||$customer_name_a!=null){
//                                if($customer_city_a!=''||$customer_city_a!=null){
//                                    if($customer_address_a!=''||$customer_address_a!=null){
//                                        if($customer_phone_a!=''||$customer_phone_a!=null){
                                      if($customer_send_id!=null||$customer_send_id!=''){
                                          if($customer_accept_id!=null||$customer_accept_id!=''){
                                            if($goods_name!=''||$goods_name!=null){
                                                if($goods_weight!=''||$goods_weight!=null){
//                                                    if($goods_capacity!=''||$goods_capacity!=null){
                                                        if($goods_package!=''||$goods_package!=null){
//                                                            if($goods_count!=''||$goods_count!=null){
                                                                    if($good_worth!=''||$good_worth!=null){
                                                                            if($wx_openid!=''||$wx_openid!=null){
                                                                                date_default_timezone_set("PRC");
                                                                                $shijian=date("Y-m-d H:i:s",time());
                                                                                $selectStatement = $database->select()
                                                                                    ->from('customer')
                                                                                    ->where('tenant_id','=',$tenant_id)
                                                                                    ->where('exist',"=",0)
//                                                                                    ->where('customer_address','=',$customer_adress_s)
//                                                                                    ->where('customer_name','=',$customer_name_s)
//                                                                                    ->where('customer_city_id','=',$customer_city_s)
//                                                                                    ->where('customer_phone','=',$customer_phone_s)
                                                                                    ->where('customer_id','=',$customer_send_id)
                                                                                    ->where('wx_openid','=',$wx_openid);
                                                                                $stmt = $selectStatement->execute();
                                                                                $data = $stmt->fetch();
                                                                                    if($data!=null){
                                                                                        $selectStatement = $database->select()
                                                                                            ->from('customer')
                                                                                            ->where('tenant_id','=',$tenant_id)
                                                                                            ->where('exist',"=",0)
//                                                                                            ->where('customer_address','=',$customer_adress_a)
//                                                                                            ->where('customer_name','=',$customer_name_a)
//                                                                                            ->where('customer_city_id','=',$customer_city_a)
//                                                                                            ->where('customer_phone','=',$customer_phone_a)
                                                                                            ->where('customer_id','=',$customer_accept_id)
                                                                                            ->where('wx_openid','=',$wx_openid);
                                                                                        $stmt = $selectStatement->execute();
                                                                                        $data1 = $stmt->fetch();
                                                                                            if($data1!=null) {
                                                                                                $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
                                                                                                $strr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                                do{
                                                                                                    $strr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                                }while(strlen($strr)<6);
                                                                                                $time=base_convert(time(), 10, 32);
                                                                                                $str=$time.$strr;
                                                                                    $insertStatement = $database->insert(array('wx_openid','tenant_id','order_id', 'pay_method','exist','order_status','sender_id','receiver_id','order_datetime0'))
                                                                                        ->into('orders')
                                                                                        ->values(array($wx_openid,$tenant_id,$str, $pay_method,0,-2,$data["customer_id"],$data1['customer_id'],$shijian));
                                                                                    $insertId = $insertStatement->execute(false);
                                                                                    if($insertId!=null){
//                                                                                        $selectStatement = $database->select()
//                                                                                            ->from('wx_message')
//                                                                                            ->where('tenant_id','=',$tenant_id);
//                                                                                        $stmt = $selectStatement->execute();
//                                                                                        $data5= $stmt->fetchAll();
//                                                                                        $wx_message_id=count($data5);
                                                                                        $strrr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                        do{
                                                                                            $strrr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                        }while(strlen($strrr)<6);
                                                                                        $time1=base_convert(time(), 10, 32);
                                                                                        $str1=$time1.$strrr;
                                                                                        $selectStatement = $database->select()
                                                                                            ->from('customer')
                                                                                            ->where('tenant_id','=',$tenant_id)
                                                                                            ->where('exist',"=",0)
                                                                                            ->where('customer_address','=','-1')
                                                                                            ->where('customer_city_id','=','-1')
                                                                                            ->where('wx_openid','=',$wx_openid);
                                                                                        $stmt = $selectStatement->execute();
                                                                                        $data6 = $stmt->fetch();
                                                                                        if($data6==null){
                                                                                            $data6=$data;
                                                                                        }

                                                                                            $insertStatement = $database->insert(array('order_id', 'tenant_id', 'message_id','exist','from_user','mobilephone','is_read','ms_date','title'))
                                                                                                ->into('wx_message')
                                                                                                ->values(array($str,$tenant_id, $str1,0,$data6['customer_name'],$data6["customer_phone"],0,$shijian,'微信受理'));
                                                                                            $insertId = $insertStatement->execute(false);
                                                                                            if($insertId!=null){
                                                                                                $strrrr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                                do{
                                                                                                    $strrrr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                                                                }while(strlen($strrrr)<4);
                                                                                                $time2=base_convert(time(), 10, 32);
                                                                                                $str2=$time2.$strrrr;
//                                                                                                $selectStatement = $database->select()
//                                                                                                    ->from('goods_package')
//                                                                                                    ->where('goods_package','=',$goods_package);
//                                                                                                $stmt = $selectStatement->execute();
//                                                                                                $data7= $stmt->fetch();
                                                                                                $insertStatement = $database->insert(array('order_id', 'tenant_id', 'goods_id','exist','goods_package_id','goods_name','goods_weight','goods_capacity','goods_count','special_need','goods_value'))
                                                                                                    ->into('goods')
                                                                                                    ->values(array($str,$tenant_id, $str2,0,$goods_package,$goods_name,$goods_weight,$goods_capacity,$goods_count,$special_need,$good_worth));
                                                                                                $insertId = $insertStatement->execute(false);
                                                                                                if($insertId!=null){
                                                                                                    $selectStament=$database->select()
                                                                                                        ->from('tenant')
                                                                                                        ->where('exist','=',0)
                                                                                                        ->where('tenant_id','=',$tenant_id);
                                                                                                    $stmt=$selectStament->execute();
                                                                                                    $data10=$stmt->fetch();
                                                                                                    $selectStament=$database->select()
                                                                                                        ->from('customer')
                                                                                                        ->where('customer_id','=',$data10['contact_id'])
                                                                                                        ->where('tenant_id','=',$tenant_id);
                                                                                                    $stmt=$selectStament->execute();
                                                                                                    $data11=$stmt->fetch();
                                                                                                    if($data10!=null){
                                                                                                        if($data10['appid']!=null&&$data10['secret']!=null&&$data11['wx_openid']!=null){
                                                                                                            $appid=$data10['appid'];
                                                                                                            $appsecret=$data10['secret'];
                                                                                                            $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
                                                                                                            $ch = curl_init();
                                                                                                            curl_setopt($ch, CURLOPT_URL, $url);
                                                                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                                                                                                            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                                                                                                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                                                                                                            $output = curl_exec($ch);
                                                                                                            curl_close($ch);
                                                                                                            $jsoninfo = json_decode($output, true);
                                                                                                            $access_token = $jsoninfo["access_token"];
                                                                                                            $template=array("touser"=>$data11['wx_openid'],"template_id"=>"0XdWHw-LDDWHgtrIbKq1F3JaGXQmQxE5SR2cb9iEf-c"
                                                                                                            ,"data"=>array("first"=>array("value"=>$data10['company'],"color"=>"#173177"),
                                                                                                                    "keyword1"=>array("value"=>$shijian,"color"=>"#173177"),
                                                                                                                    "keyword2"=>array("value"=>"您有新的订单，请打开客户端查看","color"=>"#173177")));
                                                                                                            $url="https://api.weixin.qq.com/cgi-bin/message/template/send?access_token=".$access_token;
                                                                                                            $postJson = urldecode( json_encode( $template));
                                                                                                            $ch1 = curl_init();
                                                                                                            curl_setopt($ch1, CURLOPT_URL, $url);
                                                                                                            curl_setopt($ch1, CURLOPT_RETURNTRANSFER, 1);
                                                                                                            curl_setopt($ch1, CURLOPT_SSL_VERIFYPEER, FALSE);
                                                                                                            curl_setopt($ch1, CURLOPT_SSL_VERIFYHOST, FALSE);
                                                                                                            // POST数据
                                                                                                            curl_setopt($ch1, CURLOPT_POST, 1);
                                                                                                            // 把post的变量加上
                                                                                                            curl_setopt($ch1, CURLOPT_POSTFIELDS, $postJson);
                                                                                                            $output2 = curl_exec($ch1);
                                                                                                            curl_close($ch1);
                                                                                                            $res2=json_decode($output2,true);
//                                                                                                            echo  json_encode(array("result"=>"0","desc"=>$res2));

                                                                                                        }
                                                                                                        echo json_encode(array("result"=>"1","desc"=>"success"));
                                                                                                    }else{
                                                                                                        echo  json_encode(array("result"=>"19","desc"=>"租户不存在"));
                                                                                                    }

                                                                                                }else{
                                                                                                    echo json_encode(array("result"=>"2","desc"=>"微信添加货物失败"));
                                                                                                }
                                                                                            }else{
                                                                                                echo json_encode(array("result"=>"3","desc"=>"微信添加微信消息失败"));
                                                                                            }
//                                                                                       }else{
//                                                                                            echo json_encode(array("result"=>"4","desc"=>"订单填写人信息不存在"));
//                                                                                        }

                                                                                    }else{
                                                                                        echo json_encode(array("result"=>"5","desc"=>"微信添加订单执行失败"));
                                                                                    }
                                                                                            }else {
                                                                                                echo json_encode(array("result"=>"6","desc"=>"收件人信息不存在"));
                                                                                            }
                                                                                }else{
                                                                                    echo json_encode(array("result"=>"7","desc"=>"寄件人信息不存在"));
                                                                                }
                                                                            }else{
                                                                                echo json_encode(array("result"=>"8","desc"=>"缺少openid"));
                                                                            }

                                                                    }else{
                                                                        echo json_encode(array("result"=>"10","desc"=>"缺少货物价值"));
                                                                    }

//                                                            }else{
//                                                                echo json_encode(array("result"=>"12","desc"=>"缺少货物总数"));
//                                                            }
                                                        }else{
                                                            echo json_encode(array("result"=>"13","desc"=>"缺少货物包装"));
                                                        }
//                                                    }else{
//                                                        echo json_encode(array("result"=>"14","desc"=>"缺少货物体积"));
//                                                    }
                                                }else{
                                                    echo json_encode(array("result"=>"15","desc"=>"缺少货物重量"));
                                                }
                                            }else{
                                                echo json_encode(array("result"=>"16","desc"=>"缺少货物名称"));
                                            }
                                          }else{
                                              echo json_encode(array("result"=>"17","desc"=>"缺少收件人id"));
                                          }
                                         }else{
                                                echo json_encode(array("result"=>"18","desc"=>"缺少寄件人id"));
                                         }
//                                        }else{
//                                            echo json_encode(array("result"=>"17","desc"=>"缺少订单创建人"));
//                                        }
//                                    }else{
//                                        echo json_encode(array("result"=>"18","desc"=>"缺少收货人的地址"));
//                                    }
//                                }else{
//                                    echo json_encode(array("result"=>"19","desc"=>"缺少收货人的城市"));
//                                }
//                            }else{
//                                echo json_encode(array("result"=>"20","desc"=>"缺少收货人的姓名"));
//                            }
//                        }else{
//                            echo json_encode(array("result"=>"21","desc"=>"缺少发货人的电话"));
//                        }
//                }else{
//                    echo json_encode(array("result"=>"22","desc"=>"缺少发货人的地址"));
//                }
//            }else{
//                echo json_encode(array("result"=>"23","desc"=>"缺少发货人的城市"));
//            }
//        }else{
//            echo json_encode(array("result"=>"24","desc"=>"缺少发货人的姓名"));
//        }
}else{
    echo json_encode(array("result"=>"25","desc"=>"缺少租户id"));
}

});



$app->post('/wxmessage',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $order_id=$body->order_id;
    $from_user=$body->from_user;
    $mobilephone=$body->mobilephone;
    $array=array();
    foreach($body as $key=>$value){
         $array[$key]=$value;
    }
    if($tenant_id!=''||$tenant_id!=null){
        if($order_id!=''||$order_id!=null){
            if($from_user!=''||$from_user!=null){
                if($mobilephone!=''||$mobilephone!=null){
//                    if(preg_match("/^1[34578]\d{9}$/", $mobilephone))
                           $array['exist']=0;
                           $array['tenant_id']=$tenant_id;
                           $selectStatement = $database->select()
                               ->from('wx_message')
                               ->where('tenant_id', '=',$tenant_id);
                           $stmt = $selectStatement->execute();
                           $data = $stmt->fetchAll();
                           if($data==null){
                               $messageid=100000001;
                           }else{
                               $messageid=count($data)+100000001;
                           }
                           $array['message_id']=$messageid;
                           date_default_timezone_set("PRC");
                           $array['ms_date']=date('Y-m-d H:i:s',time());
                           $insertStatement = $database->insert(array_keys($array))
                               ->into('wx_message')
                               ->values(array_values($array));
                           $insertId = $insertStatement->execute(false);
                           echo json_encode(array('result'=>'0','desc'=>'success'));

//                    }else{
//                        echo json_encode(array("result"=>"3","desc"=>"创建人电话不符合要求"));
//                    }
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"缺少订单创建人电话"));
                }
            }else{
                echo json_encode(array("result"=>"5","desc"=>"缺少订单创建人"));
            }
        }else{
            echo json_encode(array("result"=>"6","desc"=>"缺少运单id"));
        }
    }else{
        echo json_encode(array("result"=>"7","desc"=>"缺少租户id"));
    }
});


//获得所有微信下的单
$app->post('/wxmessages',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $is_read=$body->is_read;
    $page=$app->request->get("page");
    $per_page=$app->request->get("per_page");
    if(($tenant_id!=''||$tenant_id!=null)){
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->join('orders','orders.order_id','=','wx_message.order_id','INNER')
                ->where('wx_message.tenant_id','=',$tenant_id)
//              ->where('wx_message.title','=','微信受理')
                ->whereIn('wx_message.title',array('微信受理','网页受理'))
                ->where('orders.exist',"=",0)
                ->where('wx_message.exist','=',0)
                ->where('wx_message.is_read','=',$is_read)
                ->where('orders.order_status','!=',-1)
                ->orderBy('orders.order_status')
//              ->orderBy('orders.order_status',filed('-2','0','1','2','3','4','5','6','7','-1'))
                ->orderBy('wx_message.ms_date','DESC');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $selectStatement = $database->select()
                ->from('wx_message')
                ->join('orders','orders.order_id','=','wx_message.order_id','INNER')
                ->where('wx_message.tenant_id','=',$tenant_id)
                ->whereIn('wx_message.title',array('微信受理','网页受理'))
//                ->where('wx_message.title','=','微信受理')
                ->where('orders.exist',"=",0)
                ->where('wx_message.exist','=',0)
                ->where('wx_message.is_read','=',$is_read)
                ->where('orders.order_status','=',-1)
                ->orderBy('wx_message.ms_date','DESC');
            $stmt = $selectStatement->execute();
            $dataa = $stmt->fetchAll();
            $data=array_merge($data,$dataa);
            $num1=count($data);
            $array1=array();
            for($i=0;$i<$num1;$i++){
                $array=array();
                $array['wxmessage']=$data[$i];
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('exist',"=",0)
                    ->where('order_id','=',$data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
//                $array['orders']=$data1;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1['sender_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
//                $array['sender']=$data2;
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $array['sender_city']=$data5['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$data1['receiver_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $array['receiver_city']=$data6['name'];
//                $array['receiver']=$data3;
//                $selectStatement = $database->select()
//                    ->from('goods')
//                    ->where('tenant_id','=',$tenant_id)
//                    ->where('exist',"=",0)
//                    ->where('order_id','=',$data[$i]['order_id']);
//                $stmt = $selectStatement->execute();
//                $data4 = $stmt->fetch();
//                $array['goods']=$data4;
                array_push($array1,$array);
            }
            echo  json_encode(array("result"=>"0","desc"=>"success","wxmessage"=>$array1));
        }else {
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', "=", 0)
                ->orderBy('ms_date')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $num1 = count($data);
            $array1 = array();
            for ($i = 0; $i < $num1; $i++) {
                $array = array();
                $array['wxmessage']=$data[$i];
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist', "=", 0)
                    ->where('order_id', '=', $data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
//                $array['orders'] = $data1;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data1['sender_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $array['sender']=$data2;
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $array['sender_city']=$data5['name'];
//                $array['sender'] = $data2;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('customer_id', '=', $data1['receiver_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $array['receiver_city']=$data6['name'];
//                $array['receiver'] = $data3;
//                $selectStatement = $database->select()
//                    ->from('goods')
//                    ->where('tenant_id', '=', $tenant_id)
//                    ->where('exist', "=", 0)
//                    ->where('order_id', '=', $data1['order_id']);
//                $stmt = $selectStatement->execute();
//                $data7 = $stmt->fetch();
//                $array['goods'] = $data7;
                array_push($array1, $array);
            }
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $array1));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全","orders"=>""));
    }
});





$app->get('/wxmessage/isread',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $message_id=$app->request->get('messageid');
    if($tenant_id!=''||$tenant_id!=null){
        if ($message_id!=''||$message_id!=null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('tenant_id','=',$tenant_id)
                ->where('message_id','=',$message_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                echo json_encode(array('code'=>0,'is_read'=>$data['is_read']));
            }else{
                echo json_encode(array('code'=>1,'is_read'=>''));
            }
        }else{
            echo json_encode(array('code'=>3,'is_read'=>'缺少消息id'));
        }
    }else{
        echo json_encode(array('code'=>4,'is_read'=>'缺少用户id'));
    }
});

$app->get('/wxmessage/set-read',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $message_id=$app->request->get('messageid');
    if($tenant_id!=''||$tenant_id!=null){
        if ($message_id!=''||$message_id!=null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('tenant_id','=',$tenant_id)
                ->where('message_id','=',$message_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $updateStatement = $database->update(array('is_read' => 1))
                    ->table('wx_message')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('message_id','=',$message_id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"不存在"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少消息id"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->delete("/wxmessage",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $database=localhost();
    $message_id=$app->request->get('messageid');
    if($tenant_id!=''||$tenant_id!=null){
        if ($message_id!=''||$message_id!=null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('tenant_id','=',$tenant_id)
                ->where('message_id','=',$message_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id','=',$data['order_id'])
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1['order_status']==0||$data1['order_status']==5){
                    $updateStatement = $database->update(array('exist' => 1))
                        ->table('wx_message')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('message_id','=',$message_id)
                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"1","desc"=>"订单已发出"));
                }
            }else{
                echo json_encode(array("result"=>"2","desc"=>"不存在"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"缺少消息id"));
        }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"缺少租户id"));
    }
});



$app->delete("/wxmessage_message_id",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $database=localhost();
    $message_id=$app->request->get('messageid');
    if($tenant_id!=''||$tenant_id!=null){
        if ($message_id!=''||$message_id!=null){
            $selectStatement = $database->select()
                ->from('wx_message')
                ->where('tenant_id','=',$tenant_id)
                ->where('message_id','=',$message_id)
                ->where('is_show',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id','=',$data['order_id'])
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                if($data1['order_status']==-1||$data1['order_status']==7){
                    $updateStatement = $database->update(array('is_show' => 1))
                        ->table('wx_message')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('message_id','=',$message_id)
                        ->where('is_show',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"1","desc"=>"订单受理中，无法删除"));
                }
            }else{
                echo json_encode(array("result"=>"2","desc"=>"不存在"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"缺少消息id"));
        }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"缺少租户id"));
    }
});


//is_read的修改0至1
$app->put("/wxmessage_isread",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $message_id=$body->message_id;
    if($tenant_id!=''||$tenant_id!=null){
        if ($message_id!=''||$message_id!=null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist', "=", 0)
                ->where('tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            if($data1!=null){
                $selectStatement = $database->select()
                    ->from('wx_message')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('message_id','=',$message_id)
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                if($data2!=null){
                    $updateStatement = $database->update(array('is_read' => 1))
                        ->table('wx_message')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('message_id','=',$message_id)
                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    if($affectedRows!=null){
                        echo json_encode(array("result"=>"1","desc"=>"successs"));
                    }else{
                        echo json_encode(array("result"=>"2","desc"=>"未执行"));
                    }
                }else{
                    echo json_encode(array("result"=>"3","desc"=>"信息不存在"));
                }
            }else{
                echo json_encode(array("result"=>"4","desc"=>"租户不存在"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"缺少消息id"));
        }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"缺少租户id"));
    }
});




//获得微信端的所有订单数
$app->get("/wx_message_source",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $database=localhost();
    if($tenant_id!=null){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('wx_message','orders.order_id','=','wx_message.order_id','INNER')
            ->where('wx_message.tenant_id','=',$tenant_id)
            ->whereIn('wx_message.title',array('微信受理','网页受理'))
//            ->where('wx_message.title','=','微信受理')
            ->where('orders.exist',"=",0)
            ->where('wx_message.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array("result"=>"1","desc"=>"success",'count'=>count($data1)));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

//根据客户端order_id更改微信message的数据
$app->put("/wxmessage_order_id",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $order_id1=$body->order_id_o;
    $order_id2=$body->order_id_n;
    if($tenant_id!=''||$tenant_id!=null){
        if($order_id1!=''||$order_id1!=null){
            if($order_id2!=''||$order_id2!=null){
                $updateStatement = $database->update(array('order_id' => $order_id2))
                    ->table('wx_message')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id','=',$order_id1)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少新运单id"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少旧运单id"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

//根据orderid和tenant_id更改exist为1
$app->put("/wxmessage_exist",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant_id');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $order_id=$body->order_id;
    if($tenant_id!=''||$tenant_id!=null){
        if($order_id!=''||$order_id!=null){
                $updateStatement = $database->update(array('exist' => 1))
                    ->table('wx_message')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_id','=',$order_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"缺少运单id"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->options('/gwmessage_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "POST");
    $app->response->headers->set("Access-Control-Allow-Headers", "Origin, No-Cache, X-Requested-With, If-Modified-Since, Pragma, Last-Modified, Cache-Control, Expires, Content-Type, X-E4M-With,tenant-id");
});

$app->post('/gwmessage_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $customer_send_id=$body->customer_send_id;
    $customer_accept_id=$body->customer_accept_id;
    $goods_name=$body->goods_name;
    $goods_weight=$body->goods_weight;
    $goods_capacity=$body->goods_capacity;
    $goods_package=$body->goods_package;
    $goods_count=$body->goods_count;
    $special_need=$body->special_need;
    $good_worth=$body->good_worth;
    $pay_method=$body->pay_method;
    $wx_openid=$body->openid;
    if($tenant_id!=''||$tenant_id!=null){
        if($customer_send_id!=null||$customer_send_id!=''){
            if($customer_accept_id!=null||$customer_accept_id!=''){
                if($goods_name!=''||$goods_name!=null){
                    if($goods_weight!=''||$goods_weight!=null){
                        if($goods_package!=''||$goods_package!=null){
                            if($good_worth!=''||$good_worth!=null){
                                if($wx_openid!=''||$wx_openid!=null){
                                    date_default_timezone_set("PRC");
                                    $shijian=date("Y-m-d H:i:s",time());
                                    $selectStatement = $database->select()
                                        ->from('customer')
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('exist',"=",0)
                                        ->where('customer_id','=',$customer_send_id)
                                        ->where('wx_openid','=',$wx_openid);
                                    $stmt = $selectStatement->execute();
                                    $data = $stmt->fetch();
                                    if($data!=null){
                                        $selectStatement = $database->select()
                                            ->from('customer')
                                            ->where('tenant_id','=',$tenant_id)
                                            ->where('exist',"=",0)
                                            ->where('customer_id','=',$customer_accept_id)
                                            ->where('wx_openid','=',$wx_openid);
                                        $stmt = $selectStatement->execute();
                                        $data1 = $stmt->fetch();
                                        if($data1!=null) {
                                            $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
                                            $strr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            do{
                                                $strr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            }while(strlen($strr)<6);
                                            $time=base_convert(time(), 10, 32);
                                            $str=$time.$strr;
                                            $insertStatement = $database->insert(array('wx_openid','tenant_id','order_id', 'pay_method','exist','order_status','sender_id','receiver_id','order_datetime0'))
                                                ->into('orders')
                                                ->values(array($wx_openid,$tenant_id,$str, $pay_method,0,-2,$data["customer_id"],$data1['customer_id'],$shijian));
                                            $insertId = $insertStatement->execute(false);
                                            if($insertId!=null){
                                                $strrr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                do{
                                                    $strrr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                }while(strlen($strrr)<6);
                                                $time1=base_convert(time(), 10, 32);
                                                $str1=$time1.$strrr;
                                                $selectStatement = $database->select()
                                                    ->from('customer')
                                                    ->where('tenant_id','=',$tenant_id)
                                                    ->where('exist',"=",0)
                                                    ->where('customer_address','=','-1')
                                                    ->where('customer_city_id','=','-1')
                                                    ->where('wx_openid','=',$wx_openid);
                                                $stmt = $selectStatement->execute();
                                                $data6 = $stmt->fetch();
                                                if($data6==null){
                                                    $data6=$data;
                                                }
                                                $insertStatement = $database->insert(array('order_id', 'tenant_id', 'message_id','exist','from_user','mobilephone','is_read','ms_date','title'))
                                                    ->into('wx_message')
                                                    ->values(array($str,$tenant_id, $str1,0,$data6['customer_name'],$data6["customer_phone"],0,$shijian,'网页受理'));
                                                $insertId = $insertStatement->execute(false);
                                                if($insertId!=null){
                                                    $strrrr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                    do{
                                                        $strrrr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                                    }while(strlen($strrrr)<4);
                                                    $time2=base_convert(time(), 10, 32);
                                                    $str2=$time2.$strrrr;
                                                    $insertStatement = $database->insert(array('order_id', 'tenant_id', 'goods_id','exist','goods_package_id','goods_name','goods_weight','goods_capacity','goods_count','special_need','goods_value'))
                                                        ->into('goods')
                                                        ->values(array($str,$tenant_id, $str2,0,$goods_package,$goods_name,$goods_weight,$goods_capacity,$goods_count,$special_need,$good_worth));
                                                    $insertId = $insertStatement->execute(false);
                                                    if($insertId!=null){
                                                            echo json_encode(array("result"=>"1","desc"=>"success"));
                                                    }else{
                                                        echo json_encode(array("result"=>"2","desc"=>"微信添加货物失败"));
                                                    }
                                                }else{
                                                    echo json_encode(array("result"=>"3","desc"=>"微信添加微信消息失败"));
                                                }
                                            }else{
                                                echo json_encode(array("result"=>"5","desc"=>"微信添加订单执行失败"));
                                            }
                                        }else {
                                            echo json_encode(array("result"=>"6","desc"=>"收件人信息不存在"));
                                        }
                                    }else{
                                        echo json_encode(array("result"=>"7","desc"=>"寄件人信息不存在"));
                                    }
                                }else{
                                    echo json_encode(array("result"=>"8","desc"=>"缺少openid"));
                                }

                            }else{
                                echo json_encode(array("result"=>"10","desc"=>"缺少货物价值"));
                            }
                        }else{
                            echo json_encode(array("result"=>"13","desc"=>"缺少货物包装"));
                        }

                    }else{
                        echo json_encode(array("result"=>"15","desc"=>"缺少货物重量"));
                    }
                }else{
                    echo json_encode(array("result"=>"16","desc"=>"缺少货物名称"));
                }
            }else{
                echo json_encode(array("result"=>"17","desc"=>"缺少收件人id"));
            }
        }else{
            echo json_encode(array("result"=>"18","desc"=>"缺少寄件人id"));
        }
    }else{
        echo json_encode(array("result"=>"20","desc"=>"缺少租户id"));
    }

});






$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>