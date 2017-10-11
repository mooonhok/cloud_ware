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

$app->run();
function localhost(){
    return connect();
}
?>