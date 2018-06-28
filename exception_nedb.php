<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 16:49
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addException',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
//    $exception_id=$body->exception_id;
    $exception_source=$body->exception_source;
    $exception_person=$body->exception_person;
    $exception_comment=$body->exception_comment;
    date_default_timezone_set("PRC");
    $exception_time=date('Y-m-d H:i:s',time());
    $order_id=$body->order_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $array['tenant_id']=$tenant_id;
    $array['exist']=0;
    if($exception_source!=null||$exception_source!=''){
        if($exception_person!=null||$exception_person!=''){
            if($exception_comment!=null||$exception_comment!=''){
                if($exception_time!=null||$exception_time!=''){
                    if($order_id!=null||$order_id!=''){
                        $selectStatement = $database->select()
                            ->from('exception');
                        $stmt = $selectStatement->execute();
                        $data = $stmt->fetchAll();
                        $exception_id=count($data)+100000001;
                        date_default_timezone_set("PRC");
                        $array['exception_id']=$exception_id;
                        $array['exception_time']=date('Y-m-d H:i:s',time());
                        $insertStatement = $database->insert(array_keys($array))
                            ->into('exception')
                            ->values(array_values($array));
                        $insertId = $insertStatement->execute(false);
                        $array1=array();
                        $array1['order_status']=5;
                        $array1['exception_id']=$exception_id;
                        $updateStatement = $database->update($array1)
                            ->table('orders')
                            ->where('order_id','=',$order_id)
                            ->where('tenant_id','=',$tenant_id);
                        $affectedRows = $updateStatement->execute();
                        echo json_encode(array("result" => "0", "desc" => ""));
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "缺少运单id"));
                    }
                }else{
                    echo json_encode(array("result" => "2", "desc" => "缺少异常时间"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少异常备注"));
            }
        }else{
            echo json_encode(array("result" => "4", "desc" => "缺少异常人员"));
        }
    }else{
        echo json_encode(array("result" => "5", "desc" => "缺少异常来源"));
    }
});

$app->get('/getExceptions0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('exception')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'exception'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getExceptions1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('exception')
            ->where('exist','=',0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'exceptions'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getExceptions1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('exception')
            ->where('exist','=',0)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'exceptions'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getException',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $exception_id=$app->request->get('exception_id');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('exception')
            ->where('exception_id','=',$exception_id)
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        echo json_encode(array("result" => "0", "desc" => "success",'exception'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterException',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $exception_id=$body->exception_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        $updateStatement = $database->update($array)
            ->table('exception')
            ->where('tenant_id','=',$tenant_id)
            ->where('exception_id','=',$exception_id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->run();
function localhost(){
    return connect();
}
?>