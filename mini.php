<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/8
 * Time: 17:31
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
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
                        ->from('city')
                        ->where('name', '=', $fcity);
                    $stmt = $selectStatement->execute();
                    $data2= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
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
    $fcity=$app->request->get('fcity');
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('mini_tenant')
            ->where('exist','=',0)
            ->where('id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('name', '=', $fcity);
        $stmt = $selectStatement->execute();
        $data6= $stmt->fetch();
        if($data!=null){
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('fcity_id','=',$data6['id'])
                ->where('tid', '=', $tenant_id);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetchAll();
            for($x=0;$x<count($data2);$x++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=',$data2[$x]['fcity_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
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
            ->from('city')
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
                    ->from('city')
                    ->where('name', '=', $fcity);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
               if($tcity!=null||$tcity!=""){
                $selectStatement = $database->select()
                    ->from('city')
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
                   $arrays3=array();
                   if($data!=null){
                       for($x=0;$x<count($data);$x++){
                           array_push($arrays2,$data[$x]['tid']);
                       }
                       $arrays2=array_unique($arrays2);
                       $arrays3=array_values($arrays2);
                       for($y=0;$y<count($arrays3);$y++){
                       $selectStatement = $database->select()
                           ->from('mini_tenant')
                           ->where('exist','=',0)
                           ->where('flag','=',$type)
                           ->where('id','=',$arrays3[$y]);
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
                    ->from('city')
                    ->where('id', '=',$data2[$x]['fcity_id']);
                $stmt = $selectStatement->execute();
                $data3= $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
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


$app->post('/getbytenantname',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $fcityname=$body->fcity;
    $tcityname=$body->tcity;
    $tenantname=$body->name;
    $lat1=$body->lat1;
    $lng1=$body->lng1;
    $arrays=array();
    if($tenantname!=null||$tenantname!=""){
        if($tcityname!=null||$tcityname!=""){
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $fcityname);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $tcityname);
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
                        ->where('id','=',$data[$x]['tid'])
                        ->whereLike('name','%'.$tenantname.'%');
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
                echo json_encode(array("result"=>"2","desc"=>"尚未有公司加盟"));
            }
        }else{
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $fcityname);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('fcity_id','=',$data2['id']);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetchAll();
            $arrays2=array();
            $arrays3=array();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    array_push($arrays2,$data[$x]['tid']);
                }
                $arrays2=array_unique($arrays2);
                $arrays3=array_values($arrays2);
                for($y=0;$y<count($arrays3);$y++){
                    $selectStatement = $database->select()
                        ->from('mini_tenant')
                        ->where('exist','=',0)
                        ->where('id','=',$arrays3[$y])
                        ->whereLike('name','%'.$tenantname.'%');
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
        echo json_encode(array("result"=>"1","desc"=>"尚未输入内容"));
    }
});

$app->post('/getbyperson',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $fcityname=$body->fcity;
    $tcityname=$body->tcity;
    $tenantname=$body->name;
    $lat1=$body->lat1;
    $lng1=$body->lng1;
    $arrays=array();
    if($tenantname!=null||$tenantname!=""){
        if($tcityname!=null||$tcityname!=""){
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $fcityname);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $tcityname);
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
                        ->where('id','=',$data[$x]['tid'])
                        ->whereLike('person','%'.$tenantname.'%');
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
                echo json_encode(array("result"=>"2","desc"=>"尚未有公司加盟"));
            }
        }else{
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $fcityname);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('fcity_id','=',$data2['id']);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetchAll();
            $arrays2=array();
            $arrays3=array();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    array_push($arrays2,$data[$x]['tid']);
                }
                $arrays2=array_unique($arrays2);
                $arrays3=array_values($arrays2);
                for($y=0;$y<count($arrays3);$y++){
                    $selectStatement = $database->select()
                        ->from('mini_tenant')
                        ->where('exist','=',0)
                        ->where('id','=',$arrays3[$y])
                        ->whereLike('person','%'.$tenantname.'%');
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
        echo json_encode(array("result"=>"1","desc"=>"尚未输入内容"));
    }
});


$app->get('/person',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $fcityname=$app->request->get('fcity');
    $tcityname=$app->request->get('tcity');
    $name=$app->request->get('name');
    $arrays=array();
    $arrays1=array();
    if($name!=null||$name!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('name', '=', $fcityname);
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetch();
       if($tcityname!=null||$tcityname!=""){
           $selectStatement = $database->select()
               ->from('city')
               ->where('name', '=', $tcityname);
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
                       ->where('id','=',$data[$x]['tid'])
                       ->whereLike('person','%'.$name.'%');
                   $stmt = $selectStatement->execute();
                   $data5= $stmt->fetchAll();
                   for($i=0;$i<count($data5);$i++){
                   array_push($arrays,$data5[$i]['person']);
                   }
               }
               $arrays=array_unique($arrays);
               $arrays=array_filter($arrays);
               $arrays1=array_values($arrays);
//               $arrays=array_flip(array_flip($arrays));
               echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays1));
           }else{
               echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
           }
       }else{
           $selectStatement = $database->select()
               ->from('mini_route')
               ->where('fcity_id','=',$data2['id']);
           $stmt = $selectStatement->execute();
           $data= $stmt->fetchAll();
           if($data!=null){
               for($x=0;$x<count($data);$x++){
                   $selectStatement = $database->select()
                       ->from('mini_tenant')
                       ->where('exist','=',0)
                       ->where('id','=',$data[$x]['tid'])
                       ->whereLike('person','%'.$name.'%');
                   $stmt = $selectStatement->execute();
                   $data5= $stmt->fetchAll();
                   for($i=0;$i<count($data5);$i++){
                       array_push($arrays,$data5[$i]['person']);
                   }
               }
               $arrays=array_unique($arrays);
               $arrays=array_filter($arrays);
               $arrays1=array_values($arrays);
               echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays1));
           }else{
               echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
           }
       }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"尚未输入内容"));
    }
});

$app->get('/name',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $fcityname=$app->request->get('fcity');
    $tcityname=$app->request->get('tcity');
    $name=$app->request->get('name');
    $arrays=array();
    $arrays1=array();
    if($name!=null||$name!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('name', '=', $fcityname);
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetch();
        if($tcityname!=null||$tcityname!=""){
            $selectStatement = $database->select()
                ->from('city')
                ->where('name', '=', $tcityname);
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
                        ->where('id','=',$data[$x]['tid'])
                        ->whereLike('name','%'.$name.'%');
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetchAll();
                    for($i=0;$i<count($data5);$i++){
                        array_push($arrays,$data5[$i]['name']);
                    }
                }
                $arrays=array_unique($arrays);
                $arrays=array_filter($arrays);
                $arrays1=array_values($arrays);
                echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays1));
            }else{
                echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
            }
        }else{
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('fcity_id','=',$data2['id']);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetchAll();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    $selectStatement = $database->select()
                        ->from('mini_tenant')
                        ->where('exist','=',0)
                        ->where('id','=',$data[$x]['tid'])
                        ->whereLike('name','%'.$name.'%');
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetchAll();
                    for($i=0;$i<count($data5);$i++){
                        array_push($arrays,$data5[$i]['name']);
                    }
                }
                $arrays=array_unique($arrays);
                $arrays=array_filter($arrays);
                $arrays1=array_values($arrays);
                echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays1));
            }else{
                echo json_encode(array("result"=>"3","desc"=>"尚未有城市加盟"));
            }
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"尚未输入内容"));
    }
});
//添加路线全省
$app->post('/addbypro',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $pid=$body->pid;
    $mtid=$body->tid;
    $fcity=$body->fid;
    if($pid!=null||$pid!=""){
        if($mtid!=null||$mtid!=""){
            $selectStatement = $database->select()
                ->from('province')
                ->where('id','=',$pid);
            $stmt = $selectStatement->execute();
            $data= $stmt->fetch();
            if($data!=null){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('pid','=',$pid);
                $stmt= $selectStatement->execute();
                $data2= $stmt->fetchAll();
                 for($x=0;$x<count($data2);$x++){
                     $selectStatement = $database->select()
                         ->from('mini_route');
                     $stmt = $selectStatement->execute();
                     $data3= $stmt->fetchAll();
                     $insertStatement = $database->insert(array('id','fcity_id','tcity_id','tid'))
                         ->into('mini_route')
                         ->values(array(count($data3)+1,$fcity,$data2[$x]['id'],$mtid));
                     $insertId = $insertStatement->execute(false);
                 }
                echo json_encode(array("result"=>"0","desc"=>"添加成功"));
            }else{
                echo json_encode(array("result"=>"3","desc"=>"省份不存在"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"尚未小程序id"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"尚未输入省份id"));
    }
});
//单个线路添加
$app->post('/addroute',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tcity=$body->tcityid;
    $mtid=$body->tid;
    $fcity=$body->fcityid;
    if($tcity!=null||$tcity!=""){
        if($mtid!=null||$mtid!=""){
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('tid','=',$mtid)
                ->where('tcity_id','=',$tcity)
                ->where('fcity_id','=',$fcity);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data==null) {
                $selectStatement = $database->select()
                    ->from('mini_route');
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                $insertStatement = $database->insert(array('id', 'fcity_id', 'tcity_id', 'tid'))
                    ->into('mini_route')
                    ->values(array(count($data3) + 1, $fcity, $tcity, $mtid));
                $insertId = $insertStatement->execute(false);
                echo json_encode(array("result" => "0", "desc" => "添加成功"));
            }else{
                echo json_encode(array("result"=>"3","desc"=>"该公司该线路已经存在"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"尚未小程序id"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"尚未输入省份id"));
    }
});
//添加小程序租户
$app->post('/addmini',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $intro=$body->intro;
    $line=$body->line;
    $person=$body->person;
    $phone=$body->phone;
    $telephone=$body->telephone;
    $address=$body->address;
    $lat=$body->lat;
    $lng=$body->lng;
    $publicname=$body->pubname;
    $public_img=$body->pubimg;
    $adminid=$body->adminid;
    $flag=$body->flag;
    $fcity=$body->fcity;
    $tcity=$body->tcity;
    $pic1=$body->pic1;
    $pic2=$body->pic2;
    $pic3=$body->pic3;
    $pic4=$body->pic4;
    $pic5=$body->pic5;
    $lujing1=null;
    $lujing2=null;
    $lujing3=null;
    $lujing4=null;
    $lujing5=null;
    $lujing6=null;

    if($name!=null||$name!=""){
        if($flag!=null||$flag!=""){
            if($person!=null||$person!=""){
                if($line!=null||$line!=""){
                    $selectStatement = $database->select()
                        ->from('mini_tenant');
                    $stmt = $selectStatement->execute();
                    $data5= $stmt->fetchAll();
                    $time1=time();
                        if($pic1!=null){
                            $base64_image_content = $pic1;
                            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                                $type = $result[2];
                                date_default_timezone_set("PRC");
                                $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                                if (!file_exists($new_file)) {
                                  //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                    mkdir($new_file, 0700);
                                }
                                $new_file = $new_file . $time1 . ".{$type}";
                                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                    $lujing1 = $file_url."mini/" . count($data5) . ".{$type}";
                                }
                            }
                        }
                        if($pic2!=null){
                            $base64_image_content = $pic2;
                            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                                $type = $result[2];
                                date_default_timezone_set("PRC");
                                $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                                if (!file_exists($new_file)) {
                                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                    mkdir($new_file, 0700);
                                }
                                $new_file = $new_file .$time1 . ".{$type}";
                                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                    $lujing2 = $file_url."mini/" . count($data5) . "-1.{$type}";
                                }
                            }
                        }
                        if($pic3!=null){
                            $base64_image_content = $pic3;
                            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                                $type = $result[2];
                                date_default_timezone_set("PRC");
                                $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                                if (!file_exists($new_file)) {
                                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                    mkdir($new_file, 0700);
                                }
                                $new_file = $new_file . $time1 . ".{$type}";
                                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                    $lujing3 = $file_url."mini/" . count($data5) . "-2.{$type}";
                                }
                            }
                        }
                        if($pic4!=null){
                            $base64_image_content = $pic4;
                            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                                $type = $result[2];
                                date_default_timezone_set("PRC");
                                $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                                if (!file_exists($new_file)) {
                                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                    mkdir($new_file, 0700);
                                }
                                $new_file = $new_file . $time1 . ".{$type}";
                                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                    $lujing4 = $file_url."mini/" . count($data5) . "-3.{$type}";
                                }
                            }

                        }
                        if($pic5!=null){
                            $base64_image_content = $pic5;
                            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                                $type = $result[2];
                                date_default_timezone_set("PRC");
                                $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                                if (!file_exists($new_file)) {
                                    //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                    mkdir($new_file, 0700);
                                }
                                $new_file = $new_file . $time1 . ".{$type}";
                                if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                    $lujing5 = $file_url."mini/" . count($data5) . "-4.{$type}";
                                }
                            }
                        }
                    if($public_img!=null){
                        $base64_image_content = $public_img;
                        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                            $type = $result[2];
                            date_default_timezone_set("PRC");
                            $new_file = "/files/mini/" . date('Ymd', $time1) . "/";
                            if (!file_exists($new_file)) {
                                //检查是否有该文件夹，如果没有就创建，并给予最高权限
                                mkdir($new_file, 0700);
                            }
                            $new_file = $new_file . $time1 . ".{$type}";
                            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                                $lujing6 = $file_url."mini/" . count($data5) . ".{$type}";
                            }
                        }
                    }
                        $insertStatement = $database->insert(array('id','name','img','intro','line','person','phone'
                        ,'telephone','address','latitude','longitude','public_name','public_img','flag','swiper_img1'
                        ,'swiper_img2','swiper_img3','swiper_img4','exist'))
                            ->into('mini_tenant')
                            ->values(array(count($data5)+1,$name,$lujing1,$intro,$line,$person,$phone
                            ,$telephone,$address,$lat,$lng,$publicname,$lujing6,$flag,$lujing2
                            ,$lujing3,$lujing4,$lujing5,0));
                        $insertId = $insertStatement->execute(false);
                       $selectStatement = $database->select()
                          ->from('mini_route');
                       $stmt = $selectStatement->execute();
                       $data3= $stmt->fetchAll();
                    $insertStatement = $database->insert(array('id','fcity_id','tcity_id','tid'))
                        ->into('mini_route')
                        ->values(array(count($data3)+1,$fcity,$tcity,count($data5)+1));
                    $insertId = $insertStatement->execute(false);

                    date_default_timezone_set("PRC");
                    $shijian=date("Y-m-d H:i:s",time());
                    $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                        ->into('operate_admin')
                        ->values(array($adminid,'mini_tenant',count($data5)+1,-1,$shijian));
                    $insertId = $insertStatement->execute(false);
                    $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
                        ->into('operate_admin')
                        ->values(array($adminid,'mini_route',count($data3)+1,-1,$shijian));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result"=>"0","desc"=>"添加成功"));
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"缺少线路介绍"));
                }
            }else{
                echo json_encode(array("result"=>"3","desc"=>"缺少联系人"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"尚未选择公司是否是精品物流专线"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"尚未输入公司名称"));
    }
});
//获取所有的小程序用户
$app->get('/allmini',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $page=(int)$page-1;
    $selectStatement = $database->select()
        ->from('mini_tenant')
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('mini_tenant')
        ->where('exist','=',0)
        ->limit((int)$per_page, (int)$per_page * (int)$page);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    echo json_encode(array("result"=>"0","desc"=>"",'mini_tenant'=>$data2,'count'=>count($data1)));
});

//获取租户详细信息
$app->get('/minibyid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tid=$app->request->get('tid');
    if($tid!=null||$tid!=""){
        $selectStatement = $database->select()
            ->from('mini_tenant')
            ->where('exist','=',0)
           ->where('id','=',$tid);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('mini_route')
                ->where('tid','=',$tid);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetchAll();
            for($j=0;$j<count($data2);$j++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2[$j]['fcity_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2[$j]['tcity_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data2[$j]['line']='从'.$data3['name'].'到'.$data4['name'];
            }
            $data1['route']=$data2;
            $data1['routecount']=count($data2);
            echo json_encode(array("result"=>"0","desc"=>"",'routes'=>$data1));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"1","desc"=>"没有租户id"));
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

