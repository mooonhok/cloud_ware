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
                     date_default_timezone_set("PRC");
                     $time=date("Y-m-d H",$data2[$x]['accept_time']);
                   $arrays1['longitude']=$data2[$x]['longitude'];
                   $arrays1['latitude']=$data2[$x]['latitude'];
                     $arrays1['time']=$time;
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
    $time2=$body->time1;
    $time=time();
    if($longitude!=null||$longitude!=""||$latitude!=null||$latitude!=""){
    if($lorry_id!=null||$lorry_id!=""){
        $selectStament=$database->select()
            ->from('lorry')
            ->where('exist','=',0)
            ->where('lorry_id','=',$lorry_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['signtime']==$time2){
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
                    ->where('scheduling_status', '!=', 1)
                    ->where('scheduling_status', '!=', 5)
                    ->where('scheduling_status', '!=', 6)
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
                            if ($data4 != null) {
                                if($data4[count($data4)-1]['longitude']==$longitude&&$data4[count($data4)-1]['latitude']==$latitude) {
                                    $arrays['accept_time'] = $time;
                                    $updateStatement = $database->update($arrays)
                                        ->table('map')
                                        ->where('id', '=', $data4[count($data4)-1]['id']);
                                    $affectedRows = $updateStatement->execute();
                                } else {
                                    if ($time - $data4[count($data4) - 1]['accept_time'] > 1200) {
                                        $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                            ->into('map')
                                            ->values(array($data3[$y]['scheduling_id'], $longitude, $latitude, $time));
                                        $insertId = $insertStatement->execute(false);
                                    }
                                }
                            }else {
                                $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                                    ->into('map')
                                    ->values(array($data3[$y]['scheduling_id'], $longitude, $latitude, $time));
                                $insertId = $insertStatement->execute(false);
                            }
                    }
                }
            }
            echo json_encode(array('result' => '0', 'desc' => '上传地理位置成功'));
            }else{
                echo json_encode(array('result' => '9', 'desc' => '您的帐号已经在其他地方登录，请重新登录'));
            }
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
        $data5=$stmt->fetchAll();
      if($data5!=null){
          for($i=0;$i<count($data5);$i++){
              $selectStament = $database->select()
                  ->from('scheduling')
                  ->where('scheduling_id', '=', $data5[$i]['schedule_id'])
                  ->orderBy('accept_time');
              $stmt = $selectStament->execute();
              $data = $stmt->fetch();
             if($data['scheduling_status']==5){
                 $selectStament = $database->select()
                     ->from('map')
                     ->where('scheduling_id', '=', $data['scheduling_id'])
                     ->orderBy('accept_time')
                     ->limit(1);
                 $stmt = $selectStament->execute();
                 $data3 = $stmt->fetch();
                 $selectStament = $database->select()
                     ->from('map')
                     ->where('scheduling_id', '=', $data['scheduling_id'])
                     ->orderBy('accept_time','desc')
                     ->limit(1);
                 $stmt = $selectStament->execute();
                 $data4 = $stmt->fetch();
                 $deleteStatement = $database->delete()
                     ->from('chathall')
                     ->where('scheduling_id','=',$data['scheduling_id'])
                     ->where('accept_time','<',$data4['accept_time'])
                     ->where('accept_time', '>',$data3['accept_time']);
                 $affectedRows = $deleteStatement->execute();

             }
              $selectStament = $database->select()
                  ->from('map')
                  ->where('scheduling_id', '=', $data5[$i]['schedule_id'])
                  ->orderBy('accept_time');
              $stmt = $selectStament->execute();
              $data2 = $stmt->fetchAll();
              if($data2!=null) {
                  for ($x = 0; $x < count($data2); $x++) {
                      date_default_timezone_set("PRC");
                      $time = date("Y-m-d H:i", $data2[$x]['accept_time']);
                      $arrays1['longitude'] = $data2[$x]['longitude'];
                      $arrays1['latitude'] = $data2[$x]['latitude'];
                      $arrays1['time'] = $time;
                      array_push($arrays, $arrays1);
                  }
              }
          }
          echo json_encode(array('result' => '0', 'desc' => '', 'map' => $arrays));
      }else{
          echo json_encode(array('result' => '4', 'desc' => '运单未出发'));
      }
    }else {
        echo json_encode(array('result' => '1', 'desc' => '运单号为空'));
    }
});

//交付上传地理坐标
$app->post('/getmap',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $scheduling_id=$body->scheduling_id;
    $longitude=$body->longitude;
    $latitude=$body->latitude;
    $time=time();
    if($scheduling_id){
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('scheduling_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($longitude!=null||$longitude!=""||$latitude!=null||$latitude!=""){
            $insertStatement = $database->insert(array('scheduling_id', 'longitude', 'latitude', 'accept_time'))
                ->into('map')
                ->values(array($scheduling_id, $longitude, $latitude, $time));
            $insertId = $insertStatement->execute(false);
                echo json_encode(array('result' => '0', 'desc' => '上传成功'));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '缺少地理位置'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单号为空'));
    }
});


$app->get('/allmap',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $arrays=array();
      $arrays2=array();
        $selectStament=$database->select()
            ->from('scheduling')
            ->where('scheduling_status','=',4);
        $stmt=$selectStament->execute();
        $data=$stmt->fetchAll();

              for($x=0;$x<count($data);$x++){
              	 $selectStament=$database->select()
                 ->from('map')
                 ->where('scheduling_id','=',$data[$x]['scheduling_id'])
                 ->orderBy('accept_time')
                 ->limit(1);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                    date_default_timezone_set("PRC");
                    $arrays1['scheduling_id']=$data2['scheduling_id'];
                    $time = date("Y-m-d H", $data2['accept_time']);
                    $arrays1['longitude'] = $data2['longitude'];
                    $arrays1['latitude'] = $data2['latitude'];
                    $arrays1['time'] = $time;
                  
                     $selectStament=$database->select()
                 ->from('lorry')
                 ->where('lorry_id','=',$data[$x]['lorry_id']);
                   $stmt=$selectStament->execute();
                $data6=$stmt->fetch();
                    $arrays1['telephone']=$data6['driver_phone'];
                    $arrays1['driver_name']=$data6['driver_name'];
                      array_push($arrays, $arrays1);
          }
           $selectStament=$database->select()
            ->from('tenant');
           $stmt=$selectStament->execute();
           $data3=$stmt->fetchAll();
           for($i=0;$i<count($data3);$i++){
           	   $arrays5['longitude']=$data3[$i]['longitude'];
           	   $arrays5['latitude']=$data3[$i]['latitude'];
           	   $arrays5['company']=$data3[$i]['company'];
           	    date_default_timezone_set("PRC");
           	   $a=strtotime($data3[$i]["end_date"])-time();
           	    $arrays5['level']=$a;
           	   
           	     $selectStament=$database->select()
                 ->from('customer')
                 ->where('customer_id','=',$data3[$i]['contact_id']);
                  $stmt=$selectStament->execute();
                 $data4=$stmt->fetch();
           	    $arrays5['customer_name']=$data4['customer_name'];
           	    $arrays5['telephone']=$data4['customer_phone'];
           	     $selectStament=$database->select()
                ->from('city')
                ->where('id','=',$data3[$i]['from_city_id']);
                $stmt=$selectStament->execute();
                 $data5=$stmt->fetch();
                 $arrays5['address']=$data5['name'].$data3[$i]['address'];
                  array_push($arrays2, $arrays5);
           }
            echo json_encode(array('result' => '0', 'desc' => '', 'map' => $arrays,'teant'=>$arrays2));
});


$app->run();

function localhost(){
    return connect();
}

?>