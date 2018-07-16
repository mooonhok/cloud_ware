<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 14:39
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getScheduleOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $scheduling_id=$app->request->get('scheduling_id');
    $selectStatement = $database->select()
        ->from('schedule_order')
        ->join('orders','orders.order_id','=','schedule_order.order_id','INNER')
        ->join('goods','goods.order_id','=','schedule_order.order_id','INNER')
        ->join('goods_package','goods.goods_package_id','=','goods_package.goods_package_id','INNER')
        ->where('schedule_order.schedule_id','=',$scheduling_id)
        ->where('goods.tenant_id','=',$tenant_id)
        ->where('orders.tenant_id','=',$tenant_id)
        ->where('schedule_order.tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','schedule_orders'=>$data1));
});






$app->run();
function localhost(){
    return connect();
}
?>