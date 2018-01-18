<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8
 * Time: 17:31
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/getcityname',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $long=$body->long;
    $lat=$body->lat;
    if($long!=null||$long!=""){
         if($lat!=null||$lat!=""){
             $x = (double)$long ;
             $y = (double)$lat;
             $x_pi = 3.14159265358979324*3000/180;
             $z = sqrt($x * $x+$y * $y) + 0.00002 * sin($y * $x_pi);

             $theta = atan2($y,$x) + 0.000003 * cos($x*$x_pi);

             $gb = number_format($z * cos($theta) + 0.0065,6);
             $ga = number_format($z * sin($theta) + 0.006,6);
             echo json_encode(array("result"=>"0","desc"=>$ga.','.$gb));
         }else{
             echo json_encode(array("result"=>"1","desc"=>"缺少坐标"));
         }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少坐标"));
    }
});



$app->get('/mini_tenants',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $type=$app->request->get('flag');
    $fcity=$app->request->get('fcity');
    $tcity=$app->request->get('tcity');
    $arrays=array();
    if($type!=null||$type!=""){
        if($fcity!=null||$fcity!=""||$tcity!=null||$tcity!=""){

                    $selectStatement = $database->select()
                        ->from('mini_city')
                        ->where('name', '=', $fcity);
                    $stmt = $selectStatement->execute();
                    $data2= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('mini_city')
                        ->where('name', '=', $tcity);
                    $stmt = $selectStatement->execute();
                    $data3= $stmt->fetch();
                      $selectStatement = $database->select()
                          ->from('mini_route')
                          ->where('fcity_id','=',$data2['id'])
                          ->where('tcity_id', '=', $data3['id']);
                     $stmt = $selectStatement->execute();
                    $data= $stmt->fetchAll();
                   if($data!=null){
                       for($x=0;$x<count($data);$x++){
                           $selectStatement = $database->select()
                               ->from('mini_tenant')
                               ->where('exist','=',0)
                               ->where('flag','=',$type)
                               ->where('id','=',$data[$x]['tid']);
                           $stmt = $selectStatement->execute();
                           $data5= $stmt->fetch();
                           if($data5!=null) {
                               array_push($arrays, $data5);
                           }
                       }
                       echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays));
                   }else {
                       echo json_encode(array("result" => "5", "desc" => "该线路未有公司加盟"));
                   }
        }else{
            $selectStatement = $database->select()
                ->from('mini_tenant')
                ->where('exist','=',0)
                ->where('flag','=',$type);
            $stmt = $selectStatement->execute();
            $data5= $stmt->fetchAll();
            echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$data5));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少类型"));
    }
});

$app->get('/mini_tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('mini_tenant_id');
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('mini_tenant')
            ->where('exist','=',0)
            ->where('id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('tid', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            for($x=0;$x<count($data2);$x++){
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('id', '=',$data2[$x]['fcity_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('id', '=',$data2[$x]['tcity_id']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $data2[$x]['fcity']=$data3['name'];
                $data2[$x]['tcity']=$data4['name'];
            }
            $data['route']=$data2;
            echo json_encode(array("result"=>"0","desc"=>"","mini_tenant"=>$data));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"租户公司不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少租户公司"));
    }
});

$app->get('/province',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('province');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});

$app->get('/city',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $pid=$app->request->get('pid');
    if($pid!=null||$pid!=""){
        $selectStatement = $database->select()
            ->from('mini_city')
            ->where('pid','=',$pid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }
});
//计算距离
$app->post('/distance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $database=localhost();
    $body=json_decode($body);
    $type=$body->flag;
    $fcity=$body->fcity;
    $tcity=$body->tcity;
    $lat1=$body->lat1;
    $lng1=$body->lng1;
    $arrays=array();
    if($type!=null||$type!=""){
        if($fcity!=null||$fcity!=""){
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('name', '=', $fcity);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
               if($tcity!=null||$tcity!=""){
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('name', '=', $tcity);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('mini_route')
                    ->where('fcity_id','=',$data2['id'])
                    ->where('tcity_id', '=', $data3['id']);
                $stmt = $selectStatement->execute();
                $data= $stmt->fetchAll();
                if($data!=null){
                    for($x=0;$x<count($data);$x++){
                        $selectStatement = $database->select()
                            ->from('mini_tenant')
                            ->where('exist','=',0)
                            ->where('flag','=',$type)
                            ->where('id','=',$data[$x]['tid']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        if($data5!=null) {
                            $lng2 = $data5['longitude'];
                            $lat2 = $data5['latitude'];
                            $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
                            $radLat2 = deg2rad($lat2);
                            $radLng1 = deg2rad($lng1);
                            $radLng2 = deg2rad($lng2);
                            $a = $radLat1 - $radLat2;
                            $b = $radLng1 - $radLng2;
                            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
                            $data5['awaylong']=strval(number_format($s/1000,2));
                            array_push($arrays,$data5);
                        }
                    }
                    if($arrays!=null) {
                       foreach ( $arrays as $key => $row ){
                          $id[$key] = (int)$row ['awaylong'];
                           $name[$key]=$row['id'];
                       }
                     array_multisort($id, SORT_ASC, $name, SORT_ASC, $arrays);
                    }
                    echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays));
                }else{
                    echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
                }
                }else{
                   $selectStatement = $database->select()
                       ->from('mini_route')
                       ->where('fcity_id','=',$data2['id']);
                   $stmt = $selectStatement->execute();
                   $data= $stmt->fetchAll();
                   $arrays2=array();
                   if($data!=null){
                       for($x=0;$x<count($data);$x++){
                           array_push($arrays2,$data[$x]['tid']);
                       }
                       $arrays2=array_unique($arrays2);
                       for($y=0;$y<count($arrays2);$y++){
                       $selectStatement = $database->select()
                           ->from('mini_tenant')
                           ->where('exist','=',0)
                           ->where('flag','=',$type)
                           ->where('id','=',$arrays2[$y]);
                       $stmt = $selectStatement->execute();
                       $data5= $stmt->fetch();
                       if($data5!=null) {
                           $lng2 = $data5['longitude'];
                           $lat2 = $data5['latitude'];
                           $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
                           $radLat2 = deg2rad($lat2);
                           $radLng1 = deg2rad($lng1);
                           $radLng2 = deg2rad($lng2);
                           $a = $radLat1 - $radLat2;
                           $b = $radLng1 - $radLng2;
                           $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
                           $data5['awaylong']=strval(number_format($s/1000,2));
                           array_push($arrays,$data5);
                       }
                       }
                       if($arrays!=null) {
                           foreach ($arrays as $key => $row) {
                               $id[$key] = (int)$row ['awaylong'];
                               $name[$key] = $row['id'];
                           }
                           array_multisort($id, SORT_ASC, $name, SORT_ASC, $arrays);
                       }
                       echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays));
                   }else{
                       echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
                   }
               }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少出发城市"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少类型"));
    }
});


$app->post('/tenant_distance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->mini_tenant_id;
    $lat1=$body->lat1;
    $lng1=$body->lng1;
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('mini_tenant')
            ->where('exist','=',0)
            ->where('id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();
        if($data!=null){
            $lng2 = $data['longitude'];
            $lat2 = $data['latitude'];
            $radLat1 = deg2rad($lat1); //deg2rad()函数将角度转换为弧度
            $radLat2 = deg2rad($lat2);
            $radLng1 = deg2rad($lng1);
            $radLng2 = deg2rad($lng2);
            $a = $radLat1 - $radLat2;
            $b = $radLng1 - $radLng2;
            $s = 2 * asin(sqrt(pow(sin($a / 2), 2) + cos($radLat1) * cos($radLat2) * pow(sin($b / 2), 2))) * 6378.137 * 1000;
            $data['awaylong']=strval(number_format($s/1000,2));
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('tid', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            for($x=0;$x<count($data2);$x++){
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('id', '=',$data2[$x]['fcity_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('mini_city')
                    ->where('id', '=',$data2[$x]['tcity_id']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $data2[$x]['fcity']=$data3['name'];
                $data2[$x]['tcity']=$data4['name'];
            }
            $data['route']=$data2;
            echo json_encode(array("result"=>"0","desc"=>"","mini_tenant"=>$data));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"租户公司不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少租户公司"));
    }
});

$app->get('/getbyname',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenantname=$app->request->get('name');
    if($tenantname!=null||$tenantname!=""){
        $selectStatement = $database->select()
            ->from('mini_tenant')
            ->where('exist','=',0)
            ->whereLike('name','%'.$tenantname.'%');
        $stmt = $selectStatement->execute();
        $data5= $stmt->fetchAll();
        echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$data5));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"名字为空"));
    }
});

$app->run();

function localhost(){
    return connect();
}

?>

