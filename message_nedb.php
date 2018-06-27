<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/10
 * Time: 16:07
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();



$app->post('/upload',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
//    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $file_url=file_url();
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
    $shijian=date("Y-m-d",time());
        if ($tenant_id != null || $tenant_id != '') {
            $insertStatement = $database->insert(array('tenant_id', 'url','datetime','from_user','content','is_read'))
                ->into('message')
                ->values(array($tenant_id,$file_url."insurance_policy/".$name3,$shijian,'保险公司','您有一条新的保险单','1'));
            $insertId = $insertStatement->execute(false);
            echo json_encode(array("result" => "0", "desc" => "success"));
        } else {
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
});

$app->get('/getMessages0',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->get('tenant_id');
    if ($tenant_id != null || $tenant_id != '') {
        $selectStatement = $database->select()
            ->from('message')
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id','DESC');
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
    $file_url=file_url();
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
    $shijian=date("Y-m-d",time());
    $selectStatement = $database->select()
        ->from('tenant');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $insertStatement = $database->insert(array( 'tenant_id','url','datetime','from_user','content','is_read'))
            ->into('message')
            ->values(array($data1[$i]['tenant_id'],$file_url."insurance_policy/".$name3,$shijian,'江苏酉铭','您有一条新的公告','1'));
        $insertId = $insertStatement->execute(false);
    }

    echo json_encode(array("result" => "0", "desc" => "success"));
});

$app->get('/limitMessages0',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->get('tenant_id');
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    if ($tenant_id != null || $tenant_id != '') {
        $selectStatement = $database->select()
            ->from('message')
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id','DESC')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success","messages"=>$data1));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getMessages1',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->get('tenant_id');
    if ($tenant_id != null || $tenant_id != '') {
        $selectStatement = $database->select()
            ->from('message')
            ->where('tenant_id','=',$tenant_id)
            ->where('is_read', '=', 0)
            ->orderBy('id','DESC');
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success","messages"=>$data1));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterMessage0',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $id = $body->id;
    $is_read= $body->is_read;
    if ($id != null || $id != '') {
        $updateStatement = $database->update(array('is_read'=>$is_read))
            ->table('message')
            ->where('id', '=', $id);
        $affectedRows = $updateStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少id"));
    }
});

$app->delete('/deleteMessage',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin', '*');
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $id = $app->request->get('id');
    if ($id != null || $id != '') {
        $deleteStatement = $database->delete()
            ->from('message')
            ->where('id', '=', $id);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    } else {
        echo json_encode(array("result" => "1", "desc" => "缺少id"));
    }
});

$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>