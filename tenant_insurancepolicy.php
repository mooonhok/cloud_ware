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
        $name31 = $_FILES["file1"]["name"];
    $name3=substr(strrchr($name31, '.'), 1);
//        $name3 = iconv("UTF-8", "gb2312", $name31);
        $shijian = time();
        $name3 = $shijian .'.'. $name3;
//        move_uploaded_file($_FILES["file1"]["tmp_name"], "tenant/insurance/" . $name3);
    $url="/files/insurance_policy/";
    move_uploaded_file($_FILES["file1"]["tmp_name"], $url . $name3);
        if ($tenant_id != null || $tenant_id != '') {
            $insertStatement = $database->insert(array('tenant_id', 'tenant_insurancepolicy'))
                ->into('tenant_insurancepolicy')
                ->values(array($tenant_id,"http://files.uminfo.cn:8000/insurance_policy/".$name3));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result" => "0", "desc" => "success"));
        } else {
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
});

$app->run();
function localhost(){
    return connect();
}
?>