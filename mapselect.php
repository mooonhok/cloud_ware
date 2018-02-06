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
                  ->where('scheduling_id', '=', $data5[$i]['schedule_id']);

              $stmt = $selectStament->execute();
              $data = $stmt->fetch();
//             if($data['scheduling_status']==5){
//                 $selectStament = $database->select()
//                     ->from('map')
//                     ->where('scheduling_id', '=', $data['scheduling_id'])
//                     ->orderBy('accept_time')
//                     ->limit(1);
//                 $stmt = $selectStament->execute();
//                 $data3 = $stmt->fetch();
//                 $selectStament = $database->select()
//                     ->from('map')
//                     ->where('scheduling_id', '=', $data['scheduling_id'])
//                     ->orderBy('accept_time','desc')
//                     ->limit(1);
//                 $stmt = $selectStament->execute();
//                 $data4 = $stmt->fetch();
//                 $deleteStatement = $database->delete()
//                     ->from('map')
//                     ->where('scheduling_id','=',$data['scheduling_id'])
//                     ->where('accept_time','<',$data4['accept_time'])
//                     ->where('accept_time', '>',$data3['accept_time']);
//                 $affectedRows = $deleteStatement->execute();
//             }
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

//扫码添加
$app->post('/addmap',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $body = $app->request->getBody();
    $body = json_decode($body);
    $order_id = $body->order_id;
    if($order_id!=null||$order_id!=""){
            $selectStatement = $database->select()
            ->from('schedule_order')
            ->where('order_id', '=', $order_id)
            ->orderBy('id')
            ->limit(1);
           $stmt = $selectStatement->execute();
           $data2 = $stmt->fetch();
            $selectStatement = $database->select()
             ->from('tenant')
             ->where('tenant_id', '=', $tenant_id);
          $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
         $selectStatement = $database->select()
        ->from('map');
         $stmt = $selectStatement->execute();
       $data4 = $stmt->fetchAll();
       $insertStatement = $database->insert(array('scheduling_id','longitude','latitude','accept_time','id'))
        ->into('map')
       ->values(array($data2['schedule_id'],$data3['longitude'],$data3['latitude'],time(),count($data4)+1));
       $insertId = $insertStatement->execute(false);
        echo json_encode(array('result' => '0', 'desc' => '添加坐标成功'));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少运单号'));
    }
});


$app->get('/test',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $a="南通市";
    echo json_encode(array('result' => '1', 'desc' => mb_substr($a,0,-1,'utf-8')));
});


$app->run();

function localhost(){
    return connect();
}

?>