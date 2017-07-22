<?php
require 'Slim/Slim.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->delete('/order', function() use ($app) {
   $app->response->headers->set('Content-Type', 'application/json');
   $tenant_id=$app->request->headers->get("tenant-id");
   $orderid=$app->request->get("orderid");
   $database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
//云端客户的id和订单id都不为空
   if(($tenant_id!=null||$tenant_id!="")&&($orderid!=null||$orderid!="")){
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
   	echo json_encode(array("result"=>"3","desc"=>"订单id不存在"));
   }
 
});



$app->post('/order',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
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
    if(($sender_id!=null||$sender_id!="")&&($receiver_id!=null||$receiver_id>0)&&($order_cost!=null||$order_cost!="")&&($pay_method!=null||$pay_method!="")&&($order_status!=null||$order_status!="")&&($order_datetime!=null||$order_datetime!="")&&($tenant_id!=null||$tenant_id!="")){
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
    	echo json_encode(array("result"=>"2","desc"=>"订单信息不全"));
    }

});


$app->put("/order",function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	$body = $app->request->getBody();
	$body=json_decode($body);
	$order_id=$body->order_id;
	$array=array();
	$data1;
	$data2;
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
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
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
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	if(($tenant_id!=null||$tenant_id!="")&&($order_id!=null||$order_id!="")){
				$selectStatement = $database->select()
                                 ->from('orders')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('order_id','=',$order_id)
                                 ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                echo json_encode(array("result"=>"0","desc"=>"success","order"=>$data));
	}else{
		        echo json_encode(array("result"=>"1","desc"=>"信息不全","order"=>""));
	}
});

$app->run();


?>