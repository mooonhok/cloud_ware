<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 10:27
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//微信添加
$app->post('/wxmessage',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $customer_name_s=$body->customer_name_s;
    $customer_city_s=$body->customer_city_s;
    $customer_address_s=$body->customer_address_s;
    $customer_phone_s=$body->customer_phone_s;
    $customer_name_a=$body->customer_name_a;
    $customer_city_a=$body->customer_city_a;
    $customer_address_a=$body->customer_address_a;
    $customer_phone_a=$body->customer_phone_a;
    $goods_name=$body->goods_name;
    $goods_weight=$body->goods_weight;
    $goods_capacity=$body->goods_capacity;
    $goods_package=$body->goods_package;
    $goods_count=$body->goods_count;
    $special_need=$body->special_need;
    $good_worth=$body->good_worth;
    $pay_method=$body->pay_method;
    $wx_openid=$body->openid;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $selectStatement = $database->select()
        ->from('customer')
        ->where('tenant_id','=',$tenant_id)
        ->where('exist',"=",0)
        ->where('customer_name','=',$customer_name_s)
        ->where('customer_city','=',$customer_city_s)
        ->where('customer_address','=',$customer_address_s)
        ->where('customer_phone','=',$customer_phone_s);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
//    if($data==null){
//        $selectStatement = $database->select()
//            ->from('customer')
//            ->where('tenant_id','=',$tenant_id)
//            ->where('exist',"=",0);
//        $stmt = $selectStatement->execute();
//        $data1 = $stmt->fetchAll();
//        $customer_id=count($data1)+10000001;
//        $insertStatement = $database->insert(array('customer_name', 'customer_city', 'customer_address','customer_phone'))
//            ->into('customer')
//            ->values(array($goods_id,$order_id, $goods_name,$goods_weight,$goods_capacity,$goods_package,$goods_count,$special_need,0,$tenant_id));
//        $insertId = $insertStatement->execute(false);
//    }else{
//
//    }

});



$app->post('/wxmessage',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $order_id=$body->order_id;
    $from_user=$body->from_user;
    $mobilephone=$body->mobilephone;
    $title=$body->title;
    $content=$body->content;
    $array=array();
    foreach($body as $key=>$value){
         $array[$key]=$value;
    }
    if($tenant_id!=''||$tenant_id!=null){
        if($order_id!=''||$order_id!=null){
            if($from_user!=''||$from_user!=null){
                if($mobilephone!=''||$mobilephone!=null){
                    if(preg_match("/^1[34578]\d{9}$/", $mobilephone)){
                   if($title!=''||$title!=null){
                       if($content!=''||$content!=null){
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
                           $insertStatement = $database->insert(array_keys($array))
                               ->into('wx_message')
                               ->values(array_values($array));
                           $insertId = $insertStatement->execute(false);
                           echo json_encode(array('result'=>'0','desc'=>'success'));
                       }else{
                           echo json_encode(array("result"=>"1","desc"=>"缺少消息内容"));
                       }
                   }else{
                       echo json_encode(array("result"=>"2","desc"=>"缺少消息标题"));
                   }
                    }else{
                        echo json_encode(array("result"=>"3","desc"=>"创建人电话不符合要求"));
                    }
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
                             ->where('tenant_id','=',$tenant_id)
                             ->where('exist',"=",0)
                             ->where('is_read','=',$is_read)
                             ->orderBy('ms_date');
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $num1=count($data);
            $array1=array();
            for($i=0;$i<$num1;$i++){
                $array=array();
                $array['wxmessage']=$data[$i];
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('order_source','=','1')
                    ->where('exist',"=",0)
                    ->where('order_id','=',$data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $array['orders']=$data1;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('customer_id','=',$data1['sender_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $array['sender']=$data2;
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $array['sender_city']=$data5['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('customer_id','=',$data1['receiver_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $array['receiver_city']=$data6['name'];
                $array['receiver']=$data3;
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('order_id','=',$data[$i]['order_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $array['goods']=$data4;
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
                $array['orders'] = $data1;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist', "=", 0)
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
                $array['sender'] = $data2;
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist', "=", 0)
                    ->where('customer_id', '=', $data1['receiver_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data6 = $stmt->fetch();
                $array['receiver_city']=$data6['name'];
                $array['receiver'] = $data3;
                $selectStatement = $database->select()
                    ->from('goods')
                    ->where('tenant_id', '=', $tenant_id)
                    ->where('exist', "=", 0)
                    ->where('order_id', '=', $data1['order_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $array['goods'] = $data7;
                array_push($array1, $array);
            }
            echo json_encode(array("result" => "0", "desc" => "success", "orders" => $array));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"信息不全","orders"=>""));
    }
});





$app->get('/wxmessage/isread',function()use($app){
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



//is_read的修改0至1
$app->put("/wxmessage_isread",function()use($app){
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


$app->run();

function localhost(){
    return connect();
}
?>