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
        if($fcity!=null||$fcity!=""){
            if($tcity!=null||$tcity!=""){
                 $selectStatement = $database->select()
                     ->from('mini_tenant')
                     ->where('exist','=',0)
                     ->where('type', '=', $type);
                  $stmt = $selectStatement->execute();
                  $data= $stmt->fetchAll();
                if($data!=null){
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
                    for($x=0;$x<count($data);$x++){
                        $selectStatement = $database->select()
                            ->from('mini_route')
                            ->where('tid','=',$data[$x]['id'])
                            ->where('fcity_id','=',$data2['id'])
                            ->where('tcity_id', '=', $data3['id']);
                        $stmt = $selectStatement->execute();
                        $data5= $stmt->fetch();
                        if($data5!=null){
                            $arrays1['name']=$data[$x]['name'];
                            $arrays1['img']=$data[$x]['img'];
                            $arrays1['intro']=$data[$x]['intro'];
                            $arrays1['person']=$data[$x]['person'];
                            $arrays1['phone']=$data[$x]['phone'];
                            $arrays1['address']=$data[$x]['address'];
                            $arrays1['latitude']=$data[$x]['latitude'];
                            $arrays1['longitude']=$data[$x]['longitude'];
                            $arrays1['public_img']=$data[$x]['public_img'];
                            $arrays1['exist']=$data[$x]['exist'];
                            $arrays1['fcityname']=$fcity;
                            $arrays1['tcityname']=$tcity;
                            $arrays1['flag']=$type;
                            array_push($arrays1,$arrays);
                        }
                    }
                    echo json_encode(array("result"=>"0","desc"=>"",'mini_tenants'=>$arrays));
                 }else{
                 echo json_encode(array("result"=>"2","desc"=>"尚未有数据"));
                }
            }else{
                echo json_encode(array("result"=>"4","desc"=>"缺少到达城市"));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"缺少出发城市"));
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
            $data['rounte']=$data2;
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
$app->run();

function localhost(){
    return connect();
}

?>

