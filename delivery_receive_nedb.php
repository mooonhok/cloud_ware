<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/19
 * Time: 14:52
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getDeliveryReceives0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('delivery_order')
            ->join('delivery','delivery_order.delivery_id','=','delivery.delivery_id','INNER')
            ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
            ->join('orders','orders.order_id','=','delivery_order.delivery_order_id','INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->leftJoin('goods_package','goods.goods_package_id','=','goods_package.goods_package_id')
            ->where('goods.tenant_id', '=', $tenant_id)
            ->where('delivery.tenant_id', '=', $tenant_id)
            ->where('customer.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('delivery_order.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'delivery_receivers'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getDeliveryReceives1',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('delivery_order')
            ->join('delivery','delivery_order.delivery_id','=','delivery.delivery_id','INNER')
            ->join('orders','orders.order_id','=','delivery_order.delivery_order_id','INNER')
            ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
            ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
            ->join('goods','goods.order_id','=','orders.order_id','INNER')
            ->leftJoin('goods_package','goods.goods_package_id','=','goods_package.goods_package_id')
            ->where('goods.tenant_id', '=', $tenant_id)
            ->where('lorry.tenant_id', '=', $tenant_id)
            ->where('delivery.tenant_id', '=', $tenant_id)
            ->where('customer.tenant_id', '=', $tenant_id)
            ->where('orders.tenant_id', '=', $tenant_id)
            ->where('delivery.exist', '=', 0)
            ->where('delivery_order.exist', '=', 0)
            ->where('delivery_order.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'delivery_receivers'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getDeliveryReceives2',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $delivery_id=$app->request->get('delivery_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($delivery_id!=null||$delivery_id!=''){
            $selectStatement = $database->select()
                ->from('delivery_order')
                ->join('delivery','delivery_order.delivery_id','=','delivery.delivery_id','INNER')
                ->join('orders','orders.order_id','=','delivery_order.delivery_order_id','INNER')
                ->join('customer','customer.customer_id','=','orders.receiver_id','INNER')
                ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
                ->join('goods','goods.order_id','=','orders.order_id','INNER')
                ->leftJoin('goods_package','goods.goods_package_id','=','goods_package.goods_package_id')
                ->where('goods.tenant_id', '=', $tenant_id)
                ->where('lorry.tenant_id', '=', $tenant_id)
                ->where('delivery.tenant_id', '=', $tenant_id)
                ->where('delivery.delivery_id', '=', $delivery_id)
                ->where('delivery_order.delivery_id', '=', $delivery_id)
                ->where('customer.tenant_id', '=', $tenant_id)
                ->where('orders.tenant_id', '=', $tenant_id)
                ->where('delivery.exist', '=', 0)
                ->where('delivery_order.exist', '=', 0)
                ->where('delivery_order.tenant_id', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'delivery_receivers'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少派送单id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->run();
function localhost(){
    return connect();
}
?>