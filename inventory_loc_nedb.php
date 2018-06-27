<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/27
 * Time: 15:17
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addInventoryLoc',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $database = localhost();
    $inventory_loc_name = $body->inventory_loc_name;
//    $inventory_loc_id = $body->inventory_loc_id;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
       if($inventory_loc_name!=null||$inventory_loc_name!=''){
           $selectStatement = $database->select()
               ->from('inventory_loc')
               ->where('tenant_id', '=', $tenant_id)
               ->where('inventory_loc_name','=',$inventory_loc_name);
           $stmt = $selectStatement->execute();
           $data2= $stmt->fetchAll();
           if($data2==null){
               $selectStatement = $database->select()
                   ->from('inventory_loc')
                   ->where('tenant_id', '=', $tenant_id);
               $stmt = $selectStatement->execute();
               $data = $stmt->fetchAll();
               $array['tenant_id']=$tenant_id;
               $array['inventory_loc_id']=count($data)+100001;
               $insertStatement = $database->insert(array_keys($array))
                   ->into('inventory_loc')
                   ->values(array_values($array));
               $insertId = $insertStatement->execute(false);
               echo json_encode(array("result" => "0", "desc" => "success"));
           }else{
               echo json_encode(array("result" => "1", "desc" => "该库位名字已经存在"));
           }
       }else{
           echo json_encode(array("result" => "2", "desc" => "缺少库位名字"));
       }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});


$app->get('/getInventoryLocs',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('inventory_loc')
            ->where('tenant_id', '=', $tenant_id)
            ->orderBy('inventory_loc_name');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'inventory_locs'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->delete('/deleteInventoryLoc',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $inventory_loc_id=$app->request->get('inventory_loc_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($inventory_loc_id!=null||$inventory_loc_id!=''){
            $deleteStatement = $database->delete()
                ->from('inventory_loc')
                ->where('tenant_id','=',$tenant_id)
                ->where('inventory_loc_id','=',$inventory_loc_id);
                 $affectedRows = $deleteStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "库位id为空"));
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