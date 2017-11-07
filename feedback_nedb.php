<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/7
 * Time: 9:10
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addFeedback',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $staff_id=$body->staff_id;
    $tenant_id = $body->tenant_id;
    $content = $body->content;
    $time= $body->time;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($staff_id!=null||$staff_id!=''){
            if($content!=null||$content!=''){
                if($time!=null||$time!=''){
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('feedback')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success"));
                }else{
                    echo json_encode(array("result" => "1", "desc" => "缺少次数"));
                }
            }else{
                echo json_encode(array("result" => "2", "desc" => "缺少content"));
            }
        }else{
            echo json_encode(array("result" => "3", "desc" => "缺少员工id"));
        }
    }else{
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>