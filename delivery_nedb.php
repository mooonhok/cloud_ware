<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/9
 * Time: 8:48
 */
require 'Slim/Slim.php';
require 'connect.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->post('/addDelivery',function()use($app) {
//    $app->response->headers->set('Content-Type', 'application/json');
//    $database = localhost();
//    $tenant_id = $app->request->headers->get("tenant-id");
//    $body = $app->request->getBody();
//    $body = json_decode($body);
//    $delivery_id = $body->delivery_id;
//    $delivery_name = $body->delivery_name;
//    $delivery_phone = $body->delivery_phone;
//    $delivery_cost = $body->delivery_cost;
//    $array = array();
//    foreach ($body as $key => $value) {
//        if($key!="delivery_name"||$key!="delivery_phone"){
//            $array[$key]=$value;
//        }
//    }
//    if($tenant_id!=null||$tenant_id){
//        if($delivery_id!=null||$delivery_id!=''){
//            if($delivery_name!=null||$delivery_name!=''){
//                if($delivery_phone!=null||$delivery_phone!=''){
//                    if($delivery_cost!=null||$delivery_cost!=''){
//                        $selectStatement = $database->select()
//                            ->from('courier')
//                            ->where('courier_name', '=', $delivery_name)
//                            ->where('courier_phone', '=', $delivery_phone)
//                            ->where('tenant_id', '=', $tenant_id);
//                        $stmt = $selectStatement->execute();
//                        $data = $stmt->fetch();
//                        if($data!=null){
//                            $array['tenant_id']=$tenant_id;
//                            $array['exist']=0;
//                            $array['courier_id']=$data['courier_id'];
//                            $insertStatement = $database->insert(array_keys($array))
//                                ->into('delivery')
//                                ->values(array_values($array));
//                            $insertId = $insertStatement->execute(false);
//                        }else{
//                            $selectStatement = $database->select()
//                                ->from('courier')
//                                ->where('tenant_id', '=', 0);
//                            $stmt = $selectStatement->execute();
//                            $data1 = $stmt->fetchAll();
//                            $selectStatement = $database->select()
//                                ->from('courier')
//                                ->where('tenant_id', '=', $tenant_id);
//                            $stmt = $selectStatement->execute();
//                            $data2 = $stmt->fetchAll();
//                            $password1=123456;
//                            $str1=str_split($password1,3);
//                            $password=null;
//                            for($x=0;$x<count($str1);$x++){
//                                $password.=$str1[$x].$x;
//                            }
//                            $insertStatement = $database->insert(array('courier_id','courier_name','courier_phone','password'))
//                                ->into('courier')
//                                ->values(array(count($data1)+10000001,$delivery_name,$delivery_phone,$password));
//                            $insertId = $insertStatement->execute(false);
//                            $insertStatement = $database->insert(array('courier_id','courier_name','courier_phone','tenant_id'))
//                                ->into('courier')
//                                ->values(array(count($data2)+10000001,$delivery_name,$delivery_phone,$tenant_id));
//                            $insertId = $insertStatement->execute(false);
//                        }
//                        echo json_encode(array("result" => "0", "desc" => "success"));
//                    }else{
//                        echo json_encode(array("result" => "1", "desc" => "缺少派送费"));
//                    }
//                }else{
//                    echo json_encode(array("result" => "2", "desc" => "缺少派送员电话"));
//                }
//            }else{
//                echo json_encode(array("result" => "3", "desc" => "缺少派送员姓名"));
//            }
//        }else{
//            echo json_encode(array("result" => "4", "desc" => "缺少派送单id"));
//        }
//    }else{
//        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
//    }
//});

$app->post('/addDelivery',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $delivery_id = $body->delivery_id;
    $lorry_id = $body->lorry_id;
    $delivery_cost = $body->delivery_cost;
    $array = array();
    foreach ($body as $key => $value) {
            $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id){
        if($delivery_id!=null||$delivery_id!=''){
            if($lorry_id!=null||$lorry_id!=''){
                if($delivery_cost!=null||$delivery_cost!=''){
                    $array['tenant_id']=$tenant_id;
                    $array['exist']=0;
                    $insertStatement = $database->insert(array_keys($array))
                        ->into('delivery')
                        ->values(array_values($array));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result" => "0", "desc" => "success"));
                }else{
                    echo json_encode(array("result" => "1", "desc" => "缺少派送费"));
                }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少车辆id"));
        }
        }else{
            echo json_encode(array("result" => "3", "desc" => "缺少派送单id"));
        }
    }else{
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
    }
});

$app->get('/getDeliverys0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('delivery')
            ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('delivery.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'deliverys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getDeliverys1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('delivery')
            ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('delivery.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'deliverys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitDeliverys',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('delivery')
            ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('delivery.tenant_id', '=', $tenant_id)
            ->orderBy('delivery.delivery_id','DESC')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'deliverys'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getDelivery',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $delivery_id=$app->request->get('delivery_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($delivery_id!=null||$delivery_id!=''){
            $selectStatement = $database->select()
                ->from('delivery')
                ->join('lorry','lorry.lorry_id','=','delivery.lorry_id','INNER')
                ->where('lorry.tenant_id','=',$tenant_id)
                ->where('delivery.tenant_id', '=', $tenant_id)
                ->where('delivery.exist', '=', 0)
                ->where('delivery_id','=',$delivery_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result" => "0", "desc" => "success",'deliverys'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少派送单id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->put('/alterDelivery0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $delivery_id = $body->delivery_id;
    $delivery_comment = $body->delivery_comment;
    if($tenant_id!=null||$tenant_id!=''){
        if($delivery_id!=null||$delivery_id!=''){
            $updateStatement = $database->update(array('delivery_comment'=>$delivery_comment))
                ->table('delivery')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0)
                ->where('delivery_id','=',$delivery_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
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
