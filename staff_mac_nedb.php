<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/30
 * Time: 14:17
 */

require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();



$app->post('/addStaffMac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $staff_id=$body->staff_id;
    $tenant_id=$body->tenant_id;
    date_default_timezone_set("PRC");
    $login_time=date('Y-m-d H:i:s',time());
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                if($login_time!=null||$login_time!=''){
                    $array['login_time']=date('Y-m-d H:i:s',time());
                    $insertStatement = $database->insert(array_keys($array))
                        ->into('staff_mac')
                        ->values(array_values($array));
                    $insertId = $insertStatement->execute(false);
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'缺少登陆时间'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'缺少员工id'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'缺少mac'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'缺少租户id'));
    }
});


$app->get('/getStaffMac0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $mac=$app->request->get('mac');
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($mac!=null||$mac!=""){
            if($staff_id!=null||$staff_id!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('mac',"=",$mac)
                    ->where('staff_id',"=",$staff_id)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少staff_id"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少mac"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->delete('/deleteStaffMac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get("id");
    $database=localhost();
    if($id!=null||$id!=""){
        $deleteStatement = $database->delete()
            ->from('staff_mac')
            ->where('id', '=', $id);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少id"));
    }
});

$app->get('/getStaffMac1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
            if($staff_id!=null||$staff_id!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('staff_id',"=",$staff_id)
                    ->where('is_login',"=",1)
                    ->where('tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少staff_id"));
            }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});





$app->put('/alterStaffMac2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $mac=$body->mac;
    $tenant_id=$body->tenant_id;
    $staff_id=$body->staff_id;
    $is_login=$body->is_login;

    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                    $updateStatement = $database->update(array('is_login'=>$is_login))
                        ->table('staff_mac')
                        ->where('mac','=',$mac)
                        ->where('staff_id','=',$staff_id)
                        ->where('tenant_id','=',$tenant_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));

            }else{
                echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
            }

        }else{
            echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
        }

    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});


$app->get('/alterStaffMac4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $staff_id=$app->request->get("staff_id");
    $mac=$app->request->get("mac");
    $tenant_id=$app->request->get("tenant_id");
    $is_login=$app->request->get("is_login");

    if($tenant_id!=null||$tenant_id!=''){
        if($mac!=null||$mac!=''){
            if($staff_id!=null||$staff_id!=''){
                $updateStatement = $database->update(array('is_login'=>$is_login))
                    ->table('staff_mac')
                    ->where('mac','=',$mac)
                    ->where('staff_id','=',$staff_id)
                    ->where('tenant_id','=',$tenant_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));

            }else{
                echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
            }

        }else{
            echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
        }

    }else{
        echo json_encode(array('result'=>'5','desc'=>'缺少租户id'));
    }
});

$app->get('/getStaffMacs0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $mac=$app->request->get('mac');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($mac!=null||$mac!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('mac',"=",$mac)
                    ->where('tenant_id','=',$tenant_id)
                    ->orderBy('login_time','DESC');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                for($i=0;$i<count($data1);$i++){
                    $selectStatement = $database->select()
                        ->from('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$data1[$i]['staff_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $data1[$i]['staff']=$data2;
                    $data1[$i]['staff']['password_decode']=decode($data2['password'],'cxphp');
                }
                echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少mac"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->get("/getStaffMac2",function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get("id");
    $database=localhost();
    if($id!=null||$id!=""){
//            $selectStatement = $database->select()
//                ->from('staff_mac')
//                ->leftJoin("staff","staff.staff_id","=","staff_mac.staff_id")
//                ->where('staff_mac.tenant_id',"=",'staff.tenant_id')
//                ->where('staff_mac.id',"=",$id);
//            $stmt = $selectStatement->execute();
//            $data1 = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('staff_mac')
            ->where('id',"=",$id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $selectStatement = $database->select()
                ->from('staff')
                ->where('staff_id',"=",$data1[$i]['staff_id'])
                ->where('tenant_id',"=",$data1[$i]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $data2['password']=decode($data2['password'] , 'cxphp');
            $data1[$i] = array_merge($data1[$i], $data2);
        }
            echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data1));
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少id"));
    }
});

$app->get('/getStaffMac',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get("tenant_id");
    $mac=$app->request->get('mac');
    $staff_id=$app->request->get("staff_id");
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        if($mac!=null||$mac!=""){
            if($staff_id!=null||$staff_id!=""){
                $selectStatement = $database->select()
                    ->from('staff_mac')
                    ->where('mac',"=",$mac)
                    ->where('staff_id',"=",$staff_id)
                    ->where('tenant_id','=',$tenant_id)
                    ->where("is_login",'=',0);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if($data1!=null){
                    $selectStatement = $database->select()
                        ->from('staff_mac')
                        ->where('staff_id',"=",$staff_id)
                        ->where('is_login',"=",1)
                        ->where('tenant_id','=',$tenant_id);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetchAll();
                    if($data2!=null){
                        echo json_encode(array("result"=>"0","desc"=>"success","staff_macs"=>$data2[0]["login_time"]));
                    }else{
                        echo json_encode(array("result"=>"4","desc"=>"记录不存在"));
                    }
                }else{
                    echo json_encode(array("result"=>"5","desc"=>"记录不存在"));
                }
            }else{
                echo json_encode(array("result"=>"1","desc"=>"缺少staff_id"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少mac"));
        }
    }else{
        echo json_encode(array("result"=>"3","desc"=>"缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}

//解密
function decode($string, $skey) {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}



?>