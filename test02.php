<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/5
 * Time: 13:46
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/testone',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    date_default_timezone_set("PRC");
    $time1=time();

        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0)
            ->whereIn('scheduling_status',array(6,8));
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
    for($i=0;$i<count($data);$i++){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
            ->where('lorry.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->join('lorry_length','app_lorry.length','=','lorry_length.lorry_length_id','INNER')
            ->join('lorry_type','app_lorry.type','=','lorry_type.lorry_type_id','INNER')
            ->where('phone', '=', $data1['driver_phone'])
            ->where('name', '=', $data1['driver_name'])
            ->where('plate_number', '=', $data1['plate_number'])
            ->where('exist','=','0');
        $stmt = $selectStatement->execute();
        $data6= $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data[$i]['send_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('province')
            ->where('id', '=', $data2['pid']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data[$i]['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('province')
            ->where('id', '=', $data4['pid']);
        $stmt = $selectStatement->execute();
        $data5 = $stmt->fetch();
        $data[$i]['lorry']=$data1;
        $data[$i]['app_lorry']=$data6;
        $data[$i]['send_city']=$data2;
        $data[$i]['send_province']=$data3;
        $data[$i]['receive_city']=$data4;
        $data[$i]['receive_province']=$data5;
    }
        $time2=time();
        $num=$time2-$time1;
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$num));

});




$app->get('/testtwo',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $array=array();
    date_default_timezone_set("PRC");
    $time1=time();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
    for($i=0;$i<count($data);$i++){
        $selectStatement = $database->select()
            ->from('lorry')
            ->where('lorry.lorry_id','=',$data[$i]['lorry_id'])
            ->where('lorry.tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->join('lorry_length','app_lorry.length','=','lorry_length.lorry_length_id','INNER')
            ->join('lorry_type','app_lorry.type','=','lorry_type.lorry_type_id','INNER')
            ->where('phone', '=', $data1['driver_phone'])
            ->where('name', '=', $data1['driver_name'])
            ->where('plate_number', '=', $data1['plate_number'])
            ->where('exist','=','0');
        $stmt = $selectStatement->execute();
        $data6= $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data[$i]['send_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('province')
            ->where('id', '=', $data2['pid']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data[$i]['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('province')
            ->where('id', '=', $data4['pid']);
        $stmt = $selectStatement->execute();
        $data5 = $stmt->fetch();
        $data[$i]['lorry']=$data1;
        $data[$i]['app_lorry']=$data6;
        $data[$i]['send_city']=$data2;
        $data[$i]['send_province']=$data3;
        $data[$i]['receive_city']=$data4;
        $data[$i]['receive_province']=$data5;
    }
        for($x=0;$x<count($data);$x++){
            if($data[$x]['scheduling_status']==6||$data[$x]['scheduling_status']==6){
                array_push($array,$data[$x]);
            }
        }
        $time2=time();
        $num=$time2-$time1;
        echo json_encode(array("result" => "0", "desc" => "success",'schedulings'=>$num));
});


$app->run();

function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}

?>