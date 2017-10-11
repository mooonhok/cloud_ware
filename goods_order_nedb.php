<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/11
 * Time: 13:51
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getGoodsOrders0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrders1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',0)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',1)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',2)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',3)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/getGoodsOrdersNum4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('orders')
            ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
            ->where('goods.tenant_id','=',$tenant_id)
            ->where('orders.tenant_id','=',$tenant_id)
            ->where('orders.order_status','=',1)
            ->where('orders.inventory_type','=',4)
            ->where('orders.exist','=',0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',0)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',1)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',2)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',3)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->get('/limitGoodsOrders4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get('tenant-id');
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=null||$tenant_id!=''){
        if($size!=null||$size!=''){
            if($offset!=null||$offset!=''){
                $selectStatement = $database->select()
                    ->from('orders')
                    ->join('goods', 'goods.order_id', '=', 'orders.order_id', 'INNER')
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('orders.order_status','=',1)
                    ->where('orders.inventory_type','=',4)
                    ->where('orders.exist','=',0)
                    ->orderBy('orders.order_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','goods_orders'=>$data1));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'偏移量为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'size为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户id为空'));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>