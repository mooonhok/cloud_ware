<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/27
 * Time: 14:37
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//根据清单号获取
$app->get('/maps',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $scheduling_id= $app->request->get("sch_id");
    $arrays=array();
    if($scheduling_id!=null||$scheduling_id!=""){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('scheduling_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('map')
                ->where('scheduling_id','=',$scheduling_id)
                ->orderBy('accept_time');
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            if($data2!=null){
                 for($x=0;$x<count($data2);$x++){
                   $arrays1['longitude']=$data2[$x]['longitude'];
                   $arrays1['latitude']=$data2[$x]['latitude'];
                   array_push($arrays,$arrays1);
                 }
                echo json_encode(array('result' => '0', 'desc' => '','map'=>$arrays));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '清单还未出发或清单已到达'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else {
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }
});
//添加地理坐标
$app->post('/addmap',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $lorry_id=$body->lorry_id;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    $time=time();
    if($longitude!=null||$longitude!=""||$latitude!=null||$latitude!=""){
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('flag','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            $selectStament=$database->select()
                ->from('lorry')
                ->where('tenant_id','!=',0)
                ->where('flag','=',0)
                ->where('driver_phone','=',$data['driver_phone'])
                ->where('driver_name','=',$data['driver_name']);
            $stmt=$selectStament->execute();
            $data2=$stmt->fetchAll();
            for($x=0;$x<count($data2);$x++) {
                $selectStament = $database->select()
                    ->from('scheduling')
                    ->where('scheduling_status', '=', 4)
                    ->where('lorry_id', '=', $data2[$x]['lorry_id'])
                    ->orderBy('change_datetime', 'desc');
                $stmt = $selectStament->execute();
                $data3 = $stmt->fetchAll();
                if ($data3 != null) {
                    for ($y = 0; $y < count($data3); $y++) {
                        $selectStament=$database->select()
                            ->from('map')
                            ->where('scheduling_id','=',$data3[$y]['scheduling_id'])
                            ->orderBy('accept_time');
                        $stmt=$selectStament->execute();
                        $data4=$stmt->fetchAll();
                        if($time-$data4[count($data4)-1]['accept_time']>1800){
                            $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                ->into('map')
                                ->values(array($data3[$x]['scheduling_id'], $longitude, $latitude,$time));
                            $insertId = $insertStatement->execute(false);
                        }
                    }
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '上传地理位置成功'));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '司机不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '司机信息为空'));
    }
    }else{
        echo json_encode(array('result' => '3', 'desc' => '坐标缺少'));
    }
});

//根据orderid获取地理位置
$app->get('/mapsbyor',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $order_id= $app->request->get("order_id");
    $arrays=array();
    if($order_id!=null||$order_id!=""){
        $selectStament=$database->select()
            ->from('schedule_order')
            ->where('order_id','=',$order_id);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetch();
       if($data5!=null){
           $selectStament=$database->select()
               ->from('scheduling')
               ->where('scheduling_id','=',$data5['schedule_id']);
           $stmt=$selectStament->execute();
           $data=$stmt->fetch();
           if($data!=null){
               $selectStament=$database->select()
                   ->from('map')
                   ->where('scheduling_id','=',$data5['schedule_id'])
                   ->orderBy('accept_time');
               $stmt=$selectStament->execute();
               $data2=$stmt->fetchAll();
               if($data2!=null){
                   for($x=0;$x<count($data2);$x++){
                       $arrays1['longitude']=$data2[$x]['longitude'];
                       $arrays1['latitude']=$data2[$x]['latitude'];
                       array_push($arrays,$arrays1);
                   }
                   echo json_encode(array('result' => '0', 'desc' => '','map'=>$arrays));
               }else{
                   echo json_encode(array('result' => '3', 'desc' => '清单还未出发或清单已到达'));
               }
           }else{
               echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
           }
       }else{
           echo json_encode(array('result' => '2', 'desc' => '运单未出发'));
       }
    }else {
        echo json_encode(array('result' => '1', 'desc' => '运单号为空'));
    }
});

$app->run();

function localhost(){
    return connect();
}

?>