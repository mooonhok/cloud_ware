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

$app->post('/addScheduleOrder',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $scheduling_id = $body->schedule_id;
    $order_id= $body->order_id;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($scheduling_id!=null||$scheduling_id!=''){
        if($order_id!=null||$order_id!=''){
            $array['tenant_id']=$tenant_id;
            $array['exist']=0;
            $insertStatement = $database->insert(array_keys($array))
                ->into('schedule_order')
                ->values(array_values($array));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getScheduleOrders0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getScheduleOrders1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id)
            ->where('exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getScheduleOrders2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $schedule_id= $app->request->get('schedule_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($schedule_id!=null||$schedule_id!=''){
            $selectStatement = $database->select()
                ->from('schedule_order')
                ->where('schedule_id', '=', $schedule_id)
                ->where('tenant_id', '=', $tenant_id)
                ->where('exist', '=', 0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少调度id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/deleteScheduleOrders',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $schedule_id = $body->schedule_id;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>1))
            ->table('schedule_order')
            ->where('tenant_id','=',$tenant_id)
            ->where('schedule_id','=',$schedule_id)
            ->where('exist','=',0);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduleOrders0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $schedule_id=$body->schedule_id;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>0))
            ->table('schedule_order')
            ->where('tenant_id','=',$tenant_id)
            ->where('schedule_id','=',$schedule_id)
            ->where('exist','=',1);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterScheduleOrder0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $schedule_id=$body->schedule_id;
    $order_id=$body->order_id;
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update(array('exist'=>0))
            ->table('schedule_order')
            ->where('tenant_id','=',$tenant_id)
            ->where('schedule_id','=',$schedule_id)
            ->where('order_id','=',$order_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//$app->delete('/deleteScheduleOrders',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $database = localhost();
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $schedule_id= $app->request->get('schedule_id');
//    if($tenant_id!=null||$tenant_id!=''){
//        $deleteStatement = $database->delete()
//            ->from('schedule_order')
//            ->where('tenant_id','=',$tenant_id)
//            ->where('exist','=',0)
//            ->where('schedule_id','=',$schedule_id);
//        echo json_encode(array("result" => "0", "desc" => "success"));
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});

$app->delete('/deleteScheduleOrders1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $schedule_id= $app->request->get('schedule_id');
    if($tenant_id!=null||$tenant_id!=''){
        $deleteStatement = $database->delete()
            ->from('schedule_order')
            ->where('schedule_id', '=', $schedule_id)
            ->where('tenant_id','=',$tenant_id)
            ->where('exist','=',1);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

//$app->get('/getScheduleOrder',function()use($app){
//    $app->response->headers->set('Content-Type', 'application/json');
//    $database = localhost();
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $schedule_id= $app->request->get('schedule_id');
//    $order_id= $app->request->get('order_id');
//    if($tenant_id!=null||$tenant_id!=''){
//        if($schedule_id!=null||$schedule_id!=''){
//            if($order_id!=null||$order_id!=''){
//                $selectStatement = $database->select()
//                    ->from('schedule_order')
//                    ->where('schedule_id', '=', $schedule_id)
//                    ->where('order_id', '=', $order_id)
//                    ->where('tenant_id', '=', $tenant_id);
//                $stmt = $selectStatement->execute();
//                $data = $stmt->fetchAll();
//                echo json_encode(array("result" => "0", "desc" => "success",'schedule_orders'=>$data));
//            }else{
//                echo json_encode(array("result" => "3", "desc" => "缺少运单id"));
//            }
//        }else{
//            echo json_encode(array("result" => "2", "desc" => "缺少调度id"));
//        }
//    }else{
//        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//    }
//});



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