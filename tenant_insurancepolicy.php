<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 16:07
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->post('/upload',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
//    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->get('tenant_id');
    $name3 = $_FILES["file1"];
//        $name3 = $_FILES["file1"]["name"];
//        $name3 = iconv("UTF-8", "UTF-8", $name3);
//        date_default_timezone_set("PRC");
//        $shijian = time();
//        $name3 = $shijian . $name3;
//        move_uploaded_file($_FILES["file"]["tmp_name"], "tenant/insurance/" . $name3);
//        if ($tenant_id != null || $tenant_id != '') {
//            $insertStatement = $database->insert(array('tenant_id', 'tenant_insurancepolicy'))
//                ->into('tenant_insurancepolicy')
//                ->values(array($tenant_id, $name3));
//            $insertId = $insertStatement->execute(false);
//            echo json_encode(array("result" => "0", "desc" => "success"));
//        } else {
//            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
//        }
    echo $name3;
});

$app->run();
function localhost(){
    return connect();
}
?>