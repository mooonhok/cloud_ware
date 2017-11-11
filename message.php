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
    $name3='';
    if(isset($_FILES['file1'])){
        $name31 = $_FILES["file1"]["name"];
        $name3=substr(strrchr($name31, '.'), 1);
//        $name3 = iconv("UTF-8", "gb2312", $name31);
        $shijian = time();
        $name3 = $shijian .'.'. $name3;
//        move_uploaded_file($_FILES["file1"]["tmp_name"], "tenant/insurance/" . $name3);
        $url="/files/insurance_policy/";
        move_uploaded_file($_FILES["file1"]["tmp_name"], $url . $name3);
    }
    date_default_timezone_set("PRC");
    $shijian=date("Y-m-d H:i:s",time());
        if ($tenant_id != null || $tenant_id != '') {
            $insertStatement = $database->insert(array('tenant_id', 'url','datetime','from_user','content','is_read'))
                ->into('message')
                ->values(array($tenant_id,"http://files.uminfo.cn:8000/insurance_policy/".$name3,$shijian,'保险公司','您有一条新的保险单','1'));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result" => "0", "desc" => "success"));
        } else {
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
});

$app->get('/news',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->get('tenant_id');
    if ($tenant_id != null || $tenant_id != '') {
        $selectStatement = $database->select()
            ->from('message')
            ->whereIn('tenant_id', array( 0, $tenant_id ))
            ->orderBy('datetime','DESC');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success","messages"=>$data1));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->post('/upnotice',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $name3='';
    if(isset($_FILES['file1'])){
        $name31 = $_FILES["file1"]["name"];
        $name3=substr(strrchr($name31, '.'), 1);
        $shijian = time();
        $name3 = $shijian .'.'. $name3;
        $url="/files/insurance_policy/";
        move_uploaded_file($_FILES["file1"]["tmp_name"], $url . $name3);
    }
    date_default_timezone_set("PRC");
    $shijian=date("Y-m-d H:i:s",time());
    $insertStatement = $database->insert(array( 'url','datetime','from_user','content','is_read'))
            ->into('message')
            ->values(array("http://files.uminfo.cn:8000/insurance_policy/".$name3,$shijian,'江苏酉铭','您有一条新的公告','1'));
    $insertId = $insertStatement->execute(false);
    echo json_encode(array("result" => "0", "desc" => "success"));
});

$app->run();
function localhost(){
    return connect();
}
?>