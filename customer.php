<?php
require 'Slim/Slim.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
$app->post('/customer',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	$tenant_id=$app->request->headers->get("tenant-id");
	$body = $app->request->getBody();
	$body=json_decode($body);
	$customer_name=$body->customer_name;
	$customer_phone=$body->customer_phone;
	$customer_city=$body->customer_city;
	$customer_address=$body->customer_address;
	$array=array();
    foreach($body as $key=>$value){
    	$array[$key]=$value;
    }
 
	if(($tenant_id!=""||$tenant_id!=null)&&($customer_name!=""||$customer_name!=null)&&($customer_phone>0||$customer_phone!=null)&&($customer_city!=""||$customer_city!=null)&&($customer_address!=""||$customer_address!=null)){
			$selectStatement = $database->select()
                            ->from('customer')
                            ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            $customer_id;
            if($data==null){
            	$customer_id=10000001;
            }else{
            	$customer_id=count($data)+10000001;
            }
            $array["customer_id"]=$customer_id;
            $array["tenant_id"]=$tenant_id;
            $array["exist"]=0;
            $insertStatement = $database->insert(array_keys($array))
                       ->into('customer')
                       ->values(array_values($array));
            $insertId = $insertStatement->execute(false);
            
            echo json_encode(array("result"=>"0","desc"=>"success"));
	}else{
		    echo json_encode(array("result"=>"1","desc"=>"信息不全"));
	}
});



$app->get('/customers',function()use($app){
	$app->response->headers->set('Content-Type', 'application/json');
	$tenant_id=$app->request->headers->get("tenant-id");
	$page=$app->request->get('page');
	$per_page=$app->request->get("per_page");
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	if($tenant_id!=null||$tenant_id!=""){
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
		echo json_encode(array("result"=>"1","desc"=>"信息不全","orders"=>""));
	}
});


$app->get("/customer",function()use($app){
	$app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
	$customer_id=$app->request->get("customerid");
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	if(($tenant_id!=null||$tenant_id!="")&&($customer_id!=null||$customer_id!="")){
				$selectStatement = $database->select()
                                 ->from('customer')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('customer_id','=',$customer_id)
                                 ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                echo json_encode(array("result"=>"0","desc"=>"success","customer"=>$data));
	}else{
		        echo json_encode(array("result"=>"1","desc"=>"信息不全","customer"=>""));
	}
});


$app->put('/customer',function()use($app){
	$app->response->headers->set('Content-type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
	$database=new database("mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8","root","");
	$body=$app->request->getBody();
    $body=json_decode($body);
	$customer_id=$body->customer_id;
	$customer_comment=$body->customer_comment;
	if(($tenant_id!=null||$tenant_id!="")&&($customer_id!=null||$customer_id!="")&&($customer_comment!=null||$customer_comment!="")){
				$selectStatement = $database->select()
                                 ->from('customer')
                                 ->where('tenant_id','=',$tenant_id)
                                 ->where('customer_id','=',$customer_id)
                                 ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data!=null){
                	$updateStatement = $database->update(array('customer_comment'=>$customer_comment))
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
		echo json_encode(array("result"=>"2","desc"=>"信息不全"));
	}
});

$app->delete('/customer',function()use($app){
	$app->response->headers->set('Content-type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
	$database=new database('mysql:host=127.0.0.1;dbname=cloud_ware;charset=utf8','root','');
    $customer_id=$app->request->get('customerid');
    if(($tenant_id!=null||$tenant_id!="")&&($customer_id!=null||$customer_id!="")){
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
    	echo json_encode(array("result"=>"2",'desc'=>'信息不全'));
    }
});


$app->run();
?>