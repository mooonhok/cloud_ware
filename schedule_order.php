<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 9:28
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//批量上传，有改无增
$app->post('/schedule_order_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $schedule_id=$body->schedule_id;
    $order_id=$body->order_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $array['tenant_id']=$tenant_id;
    $selectStatement = $database->select()
        ->from('schedule_order')
        ->where('schedule_id','=',$schedule_id)
        ->where('order_id','=',$order_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('schedule_order')
            ->where('tenant_id','=',$tenant_id)
            ->where('schedule_id','=',$schedule_id)
            ->where('order_id','=',$order_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $insertStatement = $database->insert(array_keys($array))
            ->into('schedule_order')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});




$app->run();
function localhost(){
    return connect();
}
?>