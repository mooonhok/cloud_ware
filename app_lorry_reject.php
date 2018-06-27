<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/8
 * Time: 13:50
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addAppLorryReject',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $reason=$body->reason;
    $app_lorry_id = $body->app_lorry_id;
    if($tenant_id!=null||$tenant_id!=""){
        if($reason!=null||$reason!=""){
            if($app_lorry_id!=null||$app_lorry_id!=""){
                $array['tenant_id']=$tenant_id;
                $array['reason']=$reason;
                $array['app_lorry_id']=$app_lorry_id;
                date_default_timezone_set("PRC");
                $shijian=date("Y-m-d H:i:s",time());
                $array['time']=$shijian;
                $insertStatement = $database->insert(array_keys($array))
                    ->into('app_lorry_reject')
                    ->values(array_values($array));
                $insertId = $insertStatement->execute(false);
                $updateStatement = $database->update(array('lorry_status'=>1))
                    ->table('app_lorry')
                    ->where('app_lorry_id','=',$app_lorry_id);
                $affectedRows = $updateStatement->execute();
                if($insertId){
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"司机不存在"));
                }
            }else{
                echo json_encode(array("result"=>"3","desc"=>"缺少车辆id"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少原因"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少物流公司id"));
    }

});

$app->run();

function localhost(){
    return connect();
}
?>