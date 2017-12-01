<?php
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;
use Slim\PDO\Statement;
use Slim\PDO\Statement\SelectStatement;

\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
$app->post('/goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
	$body=$app->request->getBody();
	$body=json_decode($body);
	$order_id=$body->order_id;
	$goods_name=$body->goods_name;
	$goods_weight=$body->goods_weight;
	$goods_capacity=$body->goods_capacity;
	$goods_package=$body->goods_package;
	$goods_count=$body->goods_count;
	$special_need=$body->special_need;
    if(($tenant_id!=null||$tenant_id!="")) {
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if ($order_id != null || $order_id != "") {
            $selectStatement = $database->select()
                ->from('orders')
                ->where('exist',"=",0)
                ->where('order_id','=',$order_id);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            if($data3!=null){
            if ($goods_name != null || $goods_name != "") {
                if ($goods_weight != null || $goods_weight != "") {
                    if ($goods_capacity != null || $goods_capacity != "") {
                        if ($goods_package != null || $goods_package != "") {
                            if ($goods_count != null || $goods_count != ""){
                                if ($special_need != null || $special_need != "") {
                                    $selectStatement = $database->select()
                                        ->from('orders')
                                        ->where('order_id', '=',$order_id)
                                        ->where('tenant_id','=',$tenant_id)
                                        ->where('exist',"=",0)
                                        ->where('order_status',"=",0);
                                    $stmt = $selectStatement->execute();
                                    $data = $stmt->fetch();
                                    if($data!=null){
                                        $selectStatement = $database->select()
                                            ->from('goods')
                                            ->where('tenant_id','=',$tenant_id);

                                        $stmt = $selectStatement->execute();
                                        $data = $stmt->fetchAll();
                                        if($data==null){
                                            $goods_id=100000001;
                                        }else{
                                            $goods_id=count($data)+100000001;
                                        }
                                        $insertStatement = $database->insert(array('goods_id', 'order_id', 'goods_name','goods_weight','goods_capacity','goods_package','goods_count','special_need','exist','tenant_id'))
                                            ->into('goods')
                                            ->values(array($goods_id,$order_id, $goods_name,$goods_weight,$goods_capacity,$goods_package,$goods_count,$special_need,0,$tenant_id));
                                        $insertId = $insertStatement->execute(false);
                                        echo json_encode(array("result"=>"0",'desc'=>'success'));
                                    }else{
                                        echo json_encode(array("result"=>"1",'desc'=>'订单有误'));
                                    }
                                } else {
                                        echo json_encode(array("result" => "2", 'desc' => '货物特殊需求不能为空'));
                                     }
                                } else {
                                    echo json_encode(array("result" => "3", 'desc' => '货物总数不能为0'));
                                }
                            } else {
                                echo json_encode(array("result" => "4", 'desc' => '货物包装不能为空'));
                            }
                        } else {
                            echo json_encode(array("result" => "5", 'desc' => '货物体积不能为0'));
                        }
                    } else {
                        echo json_encode(array("result" => "6", 'desc' => '货物重量不能为0'));
                    }
                } else {
                    echo json_encode(array("result" => "7", 'desc' => '货物名称不能为空'));
                }
            } else {
                echo json_encode(array("result" => "8", 'desc' => '运单号不存在'));
            }
            } else {
                echo json_encode(array("result" => "9", 'desc' => '运单号不能为空'));
            }
        } else {
            echo json_encode(array("result" => "10", 'desc' => '租户不存在'));
        }
        } else {
            echo json_encode(array("result" => "11", 'desc' => '租户id不能为空'));
        }
});


$app->put('/goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
	$body=json_decode($body);
	$goods_id=$body->goods_id;
	$array=array();
	foreach($body as $key=>$value){
		if($key!="goods_id"){
			$array[$key]=$value;
		}
	}
	  if($tenant_id!=null||$tenant_id!=""){
          $selectStatement = $database->select()
              ->from('tenant')
              ->where('exist',"=",0)
              ->where('tenant_id','=',$tenant_id);
          $stmt = $selectStatement->execute();
          $data2 = $stmt->fetch();
          if($data2!=null){
           if($goods_id!=null||$goods_id!=""){
               $selectStatement=$database->select()
                   ->from('goods')
                   ->where('goods_id','=',$goods_id)
                   ->where('tenant_id','=',$tenant_id)
                   ->where('exist','=',0);
               $stmt = $selectStatement->execute();
               $data = $stmt->fetch();
               if($data!=null){
                   $selectStatement=$database->select()
                       ->from('orders')
                       ->where('order_id','=',$data['order_id'])
                       ->where('tenant_id','=',$tenant_id)
                       ->where('exist','=',0);
                   $stmt = $selectStatement->execute();
                   $data1 = $stmt->fetch();
                   if($data1['order_status']==0){
                       $updateStatement = $database->update($array)
                           ->table('goods')
                           ->where('tenant_id','=',$tenant_id)
                           ->where('goods_id','=',$goods_id)
                           ->where('exist','=',0);
                       $affectedRows = $updateStatement->execute();
                       echo json_encode(array('result'=>'0','desc'=>'success'));
                   }else{
                       echo json_encode(array('result'=>'1','desc'=>'货物运送中，不可改'));
                   }
               }else{
                   echo json_encode(array('result'=>'2','desc'=>'货物不存在'));
               }
           }else{
               echo json_encode(array('result'=>'3','desc'=>'缺少货物id'));
           }
          }else{
              echo json_encode(array('result'=>'4','desc'=>'租户不存在'));
          }
      }else{
          echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
      }
});


$app->delete('/goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
	$goods_id=$app->request->get('goodsid');
	 if($goods_id!=null||$goods_id!=""){
	     if ($tenant_id!=''||$tenant_id!=null){
             $selectStatement = $database->select()
                 ->from('tenant')
                 ->where('exist',"=",0)
                 ->where('tenant_id','=',$tenant_id);
             $stmt = $selectStatement->execute();
             $data2 = $stmt->fetch();
             if($data2!=null){
             $selectStatement=$database->select()
                 ->from('goods')
                 ->where('goods_id','=',$goods_id)
                 ->where('tenant_id','=',$tenant_id)
                 ->where('exist','=',0);
             $stmt=$selectStatement->execute();
             $data=$stmt->fetch();
             $selectStatement=$database->select()
                 ->from('orders')
                 ->where('order_id','=',$data['order_id'])
                 ->where('tenant_id','=',$tenant_id)
                 ->where('exist','=',0);
             $stmt=$selectStatement->execute();
             $data1=$stmt->fetch();
             if($data1['order_status']==0){
                 $updateStatement = $database->update(array('exist'=>'1'))
                     ->table('goods')
                     ->where('tenant_id','=',$tenant_id)
                     ->where('goods_id','=',$goods_id);
                 $affectedRows = $updateStatement->execute();
                 echo json_encode(array('result'=>'0','desc'=>'success'));
             }else{
                 echo json_encode(array('result'=>"1",'desc'=>'货物已运送或不存在，不可更改'));
             }
             }else{
                 echo json_encode(array('result'=>"2",'desc'=>'租户不存在'));
             }
         }else{
             echo json_encode(array('result'=>"3",'desc'=>'缺少租户id'));
         }
     }else{
         echo json_encode(array('result'=>"4",'desc'=>'缺少货物id'));
     }
});


$app->get('/goods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
	$tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
	$goods_id=$app->request->get('goodsid');
      if($tenant_id!=''||$tenant_id!=null){
          $selectStatement = $database->select()
              ->from('tenant')
              ->where('exist',"=",0)
              ->where('tenant_id','=',$tenant_id);
          $stmt = $selectStatement->execute();
          $data2 = $stmt->fetch();
          if($data2!=null){
              if($goods_id!=null||$goods_id!=""){
              $selectStatement=$database->select()
              ->from('goods')
              ->where('goods_id','=',$goods_id)
              ->where('tenant_id','=',$tenant_id)
              ->where('exist','=',0);
          $stmt=$selectStatement->execute();
          $data=$stmt->fetch();
          if($data!=null||$data!=""){
              echo json_encode(array('result'=>"0",'desc'=>'success','goods'=>$data));
          }else{
              echo json_encode(array('result'=>"1",'desc'=>'货物不存在','goods'=>""));
          }
          }else{
              echo json_encode(array('result'=>'2','desc'=>'缺少货物id','goods'=>''));
          }
          }else{
              echo json_encode(array('result'=>'3','desc'=>'租户不存在','goods'=>''));
          }
      }else{
          echo json_encode(array('result'=>'4','desc'=>'缺少租户id','goods'=>''));
      }

});

//控制后台，根据order_id查询货物
$app->get("/goods_order_id",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $order_id=$app->request->get('order_id');
    $selectStatement=$database->select()
        ->from('orders')
        ->where('order_id','=',$order_id)
        ->where('exist','=',0);
    $stmt=$selectStatement->execute();
    $data1=$stmt->fetch();
    $selectStatement=$database->select()
        ->from('goods')
        ->where('order_id','=',$order_id)
        ->where('tenant_id','=',$data1['tenant_id'])
        ->where('exist','=',0);
    $stmt=$selectStatement->execute();
    $data2=$stmt->fetch();
    $selectStament=$database->select()
        ->from('goods_package')
        ->where('goods_package_id','=',$data2['goods_package_id']);
    $stmt=$selectStament->execute();
    $data3=$stmt->fetch();
    $data2['goods_package']=$data3['goods_package'];
    echo json_encode(array('result'=>'1','desc'=>'success','goods'=>$data2));
});

//客户端根据order_id修改微信的goods
$app->put('/goods_order_id',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $goods_id=$body->goods_id;
    $order_id1=$body->order_id_o;
    $order_id2=$body->order_id_n;
    $goods_count=$body->goods_count;
    $goods_capacity=$body->goods_capacity;
    $goods_weight=$body->goods_weight;
    $goods_package_id=$body->goods_package_id;
    $goods_value=$body->goods_value;
    if($tenant_id!=null||$tenant_id!=""){
        if($goods_id!=null||$goods_id!=""){
            if($order_id1!=null||$order_id1!=""){
                if($order_id2!=null||$order_id2!=""){
                    $updateStatement = $database->update(array('goods_id'=>$goods_id,'order_id'=>$order_id2,'goods_count'=>$goods_count,'goods_capacity'=>$goods_capacity,'goods_weight'=>$goods_weight,'goods_package_id'=>$goods_package_id,'goods_value'=>$goods_value))
                        ->table('goods')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('order_id','=',$order_id1);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'缺少新运单id'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'缺少旧运单id'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'缺少新货物id'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'缺少租户id'));
    }
});

//批量上传，有改无增
$app->post('/goods_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $goods_id=$body->goods_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $selectStatement = $database->select()
        ->from('goods')
        ->where('goods_id','=',$goods_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('goods')
            ->where('tenant_id','=',$tenant_id)
            ->where('goods_id','=',$goods_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $array['tenant_id']=$tenant_id;
        $insertStatement = $database->insert(array_keys($array))
            ->into('goods')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});

//用户发的最多的货的排序,限制10个
$app->get('/goods_old',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->count('goods_name','cou')
        ->from('goods')
        ->where('tenant_id','=',$tenant_id)
        ->groupBy('goods_name')
        ->orderBy('cou','DESC')
        ->orderBy('id','DESC')
        ->limit(10);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'0','desc'=>'success','goods'=>$data1));
});

$app->run();

function localhost(){
    return connect();
}
?>