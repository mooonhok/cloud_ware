<?php
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->delete('/order', function() use ($app) {
   $app->response->headers->set('Content-Type', 'application/json');
   $tenant_id=$app->request->headers->get("tenant-id");
   $orderid=$app->request->get("orderid");
    $database=localhost();
//云端客户的id和订单id都不为空
if ($tenant_id!=null||$tenant_id!=""){
    if($orderid!=null||$orderid!=""){
        $selectStatement = $database->select()
            ->from('orders')
            ->where('tenant_id','=',$tenant_id)
            ->where('order_id', '=', $orderid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data["order_status"]==0||$data["order_status"]==5){
            $updateStatement = $database->update(array('exist' => '1'))
                ->table('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('id', '=', $orderid);
            $affectedRows = $updateStatement->execute();
            if($affectedRows>0){
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"记录不存在"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"记录不可以删除"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少订单id"));
    }
}else{
    echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
}
});


$app->post('/order',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
    $database=localhost();
	$body = $app->request->getBody();
	$body=json_decode($body);
	$sender_id=$body->sender_id;
	$receiver_id=$body->receiver_id;
	$order_cost=$body->order_cost;
	$pay_method=$body->pay_method;
	$order_status=$body->order_status;
	$order_datetime=$body->order_datetime;
	$selectStatement = $database->select()
                      ->from('orders')
                      ->where('tenant_id', '=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    
    if($data==null){
    	 $order_id=100000001;
    }else{
    	$order_id=count($data)+100000001;
    }
    if($sender_id!=null||$sender_id!=""){
         if($receiver_id!=null||$receiver_id>0){
              if($order_cost!=null||$order_cost!=""){
                   if($pay_method!=null||$pay_method!=""){
                       if($order_status!=null||$order_status!=""){
                           if($order_datetime!=null||$order_datetime!=""){
                               if($tenant_id!=null||$tenant_id!=""){
                                   $selectStatement = $database->select()
                                       ->from('customer')
                                       ->where('customer_id', '=',$sender_id)
                                       ->where('tenant_id','=',$tenant_id)
                                       ->where('exist',"=",0);
                                   $stmt = $selectStatement->execute();
                                   $data1 = $stmt->fetch();
                                   $selectStatement = $database->select()
                                       ->from('customer')
                                       ->where('customer_id', '=',$receiver_id)
                                       ->where('tenant_id','=',$tenant_id)
                                       ->where('exist',"=",0);
                                   $stmt = $selectStatement->execute();
                                   $data2 = $stmt->fetch();
                                   if($data1!=null&&$data2!=null){
                                       $insertStatement = $database->insert(array('order_id', 'sender_id', 'receiver_id','pay_method','order_cost','order_status','order_datetime','tenant_id','exist'))
                                           ->into('orders')
                                           ->values(array($order_id, $sender_id,$receiver_id,$pay_method,$order_cost,$order_status,$order_datetime,$tenant_id,0));

                                       $insertId = $insertStatement->execute(false);
                                       echo json_encode(array("result"=>"0","desc"=>"success"));
                                   }else{
                                       echo json_encode(array("result"=>"1","desc"=>"发货人或者收货人不存在"));
                                   }
                               }else{
                                   echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
                               }
                           }else{
                               echo json_encode(array("result"=>"3","desc"=>"缺少运单生成时间"));
                           }
                       }else{
                           echo json_encode(array("result"=>"4","desc"=>"缺少订单状态"));
                       }
                   }else{
                       echo json_encode(array("result"=>"5","desc"=>"缺少付款方式"));
                   }
              }else{
                  echo json_encode(array("result"=>"6","desc"=>"缺少订单金额"));
              }
         }else{
             echo json_encode(array("result"=>"7","desc"=>"缺少收货人"));
         }
    }else{
        echo json_encode(array("result"=>"8","desc"=>"缺少发货人"));
    }
});


$app->put("/order",function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
    $database=localhost();
	$body = $app->request->getBody();
	$body=json_decode($body);
	$order_id=$body->order_id;
	$array=array();
    foreach($body as $key=>$value){
    	if($key!="order_id"&&($value!=null||$value!=""||$value>0)){
    		$array[$key]=$value;
    	}
    	if($key!="sender_id"){
    		$selectStatement = $database->select()
                             ->from('customer')
                             ->where('customer_id', '=',$value)
                             ->where('tenant_id','=',$tenant_id)
                             ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
    	}
    	if($key!="receiver_id"){
    	    $selectStatement = $database->select()
                             ->from('customer')
                             ->where('customer_id', '=',$value)
                             ->where('tenant_id','=',$tenant_id)
                             ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
    	}
    }
    if(count($array)>0&&$data1!=null&&$data2!=null&&($tenant_id!=null||$tenant_id!="")){
    	    $updateStatement = $database->update($array)
                       ->table('orders')
                       ->where('tenant_id','=',$tenant_id)
                       ->where('order_id','=',$order_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
    	echo json_encode(array("result"=>"1","desc"=>"修改信息有误"));
    }

});


$app->get('/orders',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$page=$app->request->get('page');
	$per_page=$app->request->get("per_page");
    $database=localhost();
	if($tenant_id!=null||$tenant_id!=""){
			if($page==null||$per_page==null){
			    $selectStatement = $database->select()
                                 ->from('orders')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo  json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
	        }else{
		        $selectStatement = $database->select()
                                 ->from('orders')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('exist',"=",0)
                                 ->limit((int)$per_page,(int)$per_page*(int)$page);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","orders"=>$data));
	        }
	}else{
		echo json_encode(array("result"=>"1","desc"=>"信息不全","orders"=>""));
	}
  
});


$app->get('/order',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$order_id=$app->request->get('orderid');
    $database=localhost();
	if($tenant_id!=null||$tenant_id!=""){
	    if($order_id!=null||$order_id!=""){
            $selectStatement = $database->select()
                ->from('orders')
                ->where('tenant_id','=',$tenant_id)
                ->where('order_id','=',$order_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            echo json_encode(array("result"=>"0","desc"=>"success","order"=>$data));
        }else{
            echo json_encode(array("result"=>"1","desc"=>"缺少运单id","order"=>""));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id","order"=>""));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>