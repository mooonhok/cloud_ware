<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/13
 * Time: 16:47
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//批量上传，有改无增
$app->post('/delivery_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $delivery_id=$body->delivery_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $array['tenant_id']=$tenant_id;
    $selectStatement = $database->select()
        ->from('delivery')
        ->where('delivery_id','=',$delivery_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('delivery')
            ->where('tenant_id','=',$tenant_id)
            ->where('delivery_id','=',$delivery_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $insertStatement = $database->insert(array_keys($array))
            ->into('delivery')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});




$app->run();
function localhost(){
    return connect();
}
?>