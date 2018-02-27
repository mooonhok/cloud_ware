<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/19
 * Time: 10:08
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;


\Slim\Slim::registerAutoloader();
    $app = new \Slim\Slim();
$app->post('/lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $plate_number=$body->plate_number;
    $driver_name=$body->driver_name;
    $driver_phone=$body->driver_phone;
    $driver_identycard=$body->driver_identycard;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $array['tenant_id']=$tenant_id;
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if($plate_number!=null||$plate_number!=''){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('exist',"=",0)
                ->where('plate_number','=',$plate_number);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            if($data3!=null){
            if($driver_name!=null||$driver_name!=''){
                if($driver_phone!=''||$driver_phone!=null){
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->where('exist',"=",0)
                        ->where('','=',$driver_phone)
                        ->where('tenant_id','=',$tenant_id);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    if($data4!=null){
                    if(preg_match("/^1[34578]\d{9}$/", $driver_phone)) {
                        if ($driver_identycard != '' || $driver_identycard != null) {
                            $selectStatement = $database->select()
                                ->from('lorry')
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetchAll();
                            if ($data != null) {
                                $lorry_id = count($data) + 100000001;
                            } else {
                                $lorry_id = 100000001;
                            }
                            $array['lorry_id'] = $lorry_id;
                            $array['exist'] = 0;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('lorry')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success"));
                        } else {
                            echo json_encode(array("result" => "1", "desc" => "缺少司机身份证"));
                        }
                    }else{
                        echo json_encode(array("result"=>"2","desc"=>"电话号码不符合要求"));
                    }
                    }else{
                        echo json_encode(array("result"=>"3","desc"=>"该公司下该电话已经存在"));
                    }
                }else{
                    echo json_encode(array("result"=>"4","desc"=>"缺少司机电话"));
                }
            }else{
                echo json_encode(array("result"=>"5","desc"=>"缺少司机姓名"));
            }
            }else{
                echo json_encode(array("result"=>"6","desc"=>"车牌号已经存在"));
            }
        }else{
            echo json_encode(array("result"=>"7","desc"=>"缺少车牌号"));
        }
        }else{
            echo json_encode(array("result"=>"8","desc"=>"该租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"9","desc"=>"缺少租户id"));
    }
});

$app->put('/lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
    $database=localhost();
    $lorry_id=$body->lorry_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if($lorry_id!=''||$lorry_id!=null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            if($data!=null){
                $updateStatement = $database->update($array)
                    ->table('lorry')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist','=',0)
                    ->where('lorry_id','=',$lorry_id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"车辆不存在"));
            }
        }else{
            echo json_encode(array("result"=>"2","desc"=>"缺少车辆id"));
        }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"该租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"4","desc"=>"缺少租户id"));
    }
});

$app->get('/lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $page=$app->request->get("page");
    $per_page=$app->request->get("per_page");
    if(($tenant_id!=''||$tenant_id!=null)){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","lorries"=>$data));
        }else{
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->limit((int)$per_page,(int)$per_page*(int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo json_encode(array("result"=>"0","desc"=>"success","lorries"=>$data));
        }
        }else{
            echo json_encode(array("result"=>"1","desc"=>"该租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"信息不全","lorries"=>""));
    }
});

$app->delete('/lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $lorry_id=$app->request->get('lorryid');
    if(($tenant_id!=''||$tenant_id!=null)){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
        if($lorry_id!=""||$lorry_id!=null){
            $selectStatement = $database->select()
                ->from('lorry')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0)
                ->where('lorry_id','=',$lorry_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $selectStatement = $database->select()
                    ->from('scheduling')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('lorry_id','=',$lorry_id);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('schedule_id','=',$data1['schedule_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('orders')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0)
                    ->where('order_id','=',$data2['order_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                if($data3['order_status']==0||$data3['order_status']==5){
                    $updateStatement = $database->update(array('exist' => 1))
                        ->table('lorry')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('exist',"=",0)
                        ->where('lorry_id','=',$lorry_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"1","desc"=>"车辆在送货途中"));
                }

            }else{
                echo json_encode(array("result"=>"2","desc"=>'车辆不存在'));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"缺少车辆id"));
        }
        }else{
            echo json_encode(array("result"=>"4","desc"=>"该租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"5","desc"=>"缺少租户id"));
    }
});


//批量上传，有改无增
$app->post('/lorry_insert',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body=json_decode($body);
    $array=array();
    $lorry_id=$body->lorry_id;
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('lorry_id','=',$lorry_id)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    if($data2!=null){
        $updateStatement = $database->update($array)
            ->table('lorry')
            ->where('tenant_id','=',$tenant_id)
            ->where('lorry_id','=',$lorry_id);
        $affectedRows = $updateStatement->execute();
    }else{
        $array['tenant_id']=$tenant_id;
        $insertStatement = $database->insert(array_keys($array))
            ->into('lorry')
            ->values(array_values($array));
        $insertId = $insertStatement->execute(false);
    }
});

//控制后台，通过plate_number获得车辆信息
$app->get('/lorrys_plate_number',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $page=$app->request->get("page");
    $page=$page-1;
    $per_page=$app->request->get("per_page");
    $plate_number=$app->request->get('plate_number');
    $selectStatement = $database->select()
        ->from('app_lorry')
        ->whereLike('plate_number','%'.$plate_number.'%')
        ->orWhereLike('phone','%'.$plate_number.'%');
    $stmt = $selectStatement->execute();
    $data0 = $stmt->fetchAll();
    $selectStatement = $database->select()
        ->from('app_lorry')
        ->whereLike('plate_number','%'.$plate_number.'%')
        ->orWhereLike('phone','%'.$plate_number.'%')
        ->orderBy('id','DESC')
        ->limit((int)$per_page,(int)$per_page * (int)$page);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('lorry_length')
            ->where('lorry_length_id','=',$data1[$i]['length']);
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetch();
        $data1[$i]['lorry_length_name']=$data2['lorry_length'];
        $selectStatement = $database->select()
            ->from('lorry_type')
            ->where('lorry_type_id','=',$data1[$i]['type']);
        $stmt = $selectStatement->execute();
        $data3= $stmt->fetch();
        $data1[$i]['lorry_type_name']=$data3['lorry_type_name'];
    }
    echo json_encode(array("result"=>"0","desc"=>"success",'lorrys'=>$data1,'count'=>count($data0)));
});


$app->get('/getAppLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $applorry=$app->request->get("app_lorry_id");
    if($applorry!=null||$applorry!=null){
        $selectStatement = $database->select()
            ->from('app_lorry')
            ->where('app_lorry_id','=',$applorry);
        $stmt = $selectStatement->execute();
        $data= $stmt->fetch();

                $selectStatement = $database->select()
                    ->from('lorry_length')
                    ->where('lorry_length_id','=',$data['length']);
                $stmt = $selectStatement->execute();
                $data2= $stmt->fetch();
                $data['lorry_length_name']=$data2['lorry_length'];
//                $selectStatement = $database->select()
//                    ->from('lorry_load')
//                    ->where('lorry_load_id','=',$data[$i]['deadweight']);
//                $stmt = $selectStatement->execute();
//                $data3= $stmt->fetch();
                $data['lorry_load_name']=$data['deadweight'];
                $selectStatement = $database->select()
                    ->from('lorry_type')
                    ->where('lorry_type_id','=',$data['type']);
                $stmt = $selectStatement->execute();
                $data4= $stmt->fetch();
                $data['lorry_type_name']=$data4['lorry_type_name'];

            echo json_encode(array("result"=>"0","desc"=>"","lorrys"=>$data));

    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少司机ID"));
    }
});

$app->post('/app_lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $array=array();
    $database=localhost();
    $file_url=file_url();
    $admin_id = $app->request->params('admin_id');
    $app_lorry_id = $app->request->params('app_lorry_id');
    $phone = $app->request->params('phone');
    $name = $app->request->params('name');
    $id_number = $app->request->params('id_number');
    $plate_number = $app->request->params('plate_number');
    $lorry_length_name = $app->request->params('lorry_length_name');
    $lorry_type_name = $app->request->params('lorry_type_name');
    $lorry_load_name = $app->request->params('lorry_load_name');
    $array['app_lorry_id']=$app_lorry_id;
    $array['phone']=$phone;
    $array['name']=$name;
    $array['id_number']=$id_number;
    $array['plate_number']=$plate_number;
    $array['length']=$lorry_length_name;
    $array['type']=$lorry_type_name;
    $array['deadweight']=$lorry_load_name;

    $time2=time();
    if(isset($_FILES["identity_card_z"]["name"])){
        $name21=$_FILES["identity_card_z"]["name"];
        $name2=iconv("UTF-8","gb2312", $name21);
        $name2=$time2.$name2;
        move_uploaded_file($_FILES["identity_card_z"]["tmp_name"],"/files/idcard5/".$name2);
        $identity_card_z=$file_url.'idcard5/'.$time2.$name21.'';
        $array['identity_card_z']=$identity_card_z;
    }

    $time2=time();
    if(isset($_FILES["identity_card_f"]["name"])){
        $name21=$_FILES["identity_card_f"]["name"];
        $name2=iconv("UTF-8","gb2312", $name21);
        $name2=$time2.$name2;
        move_uploaded_file($_FILES["identity_card_f"]["tmp_name"],"/files/idcard6/".$name2);
        $identity_card_f=$file_url.'idcard6/'.$time2.$name21.'';
        $array['identity_card_f']=$identity_card_f;
    }

    $time2=time();
    if(isset($_FILES["driver_license_fp"]["name"])){
        $name21=$_FILES["driver_license_fp"]["name"];
        $name2 = iconv("UTF-8", "gb2312", $name21);
        $name2 = $time2 . $name2;
        move_uploaded_file($_FILES["driver_license_fp"]["tmp_name"], "/files/lorry/" . $name2);
        $driver_license_fp = $file_url.'lorry/' . $time2 . $name21 . '';
        $array['driver_license_fp']=$driver_license_fp;
    }
    $time2=time();
    if(isset($_FILES["driver_license_tp"]["name"])){
        $name21=$_FILES["driver_license_tp"]["name"];
        $name2=iconv("UTF-8","gb2312", $name21);
        $name2=$time2.$name2;
        move_uploaded_file($_FILES["driver_license_tp"]["tmp_name"],"/files/lorry2/".$name2);
        $driver_license_tp=$file_url.'lorry2/'.$time2.$name21.'';
        $array['driver_license_tp']=$driver_license_tp;
    }

    $time2=time();
    if(isset($_FILES["driving_license_fp"]["name"])){
        $name21=$_FILES["driving_license_fp"]["name"];
        $name2=iconv("UTF-8","gb2312", $name21);
        $name2=$time2.$name2;
        move_uploaded_file($_FILES["driving_license_fp"]["tmp_name"],"/files/lorry3/".$name2);
        $driving_license_fp=$file_url.'lorry3/'.$time2.$name21.'';
        $array['driving_license_fp']=$driving_license_fp;
    }

    $time2=time();
    if(isset($_FILES["driving_license_tp"]["name"])){
        $name21=$_FILES["driving_license_tp"]["name"];
        $name2=iconv("UTF-8","gb2312", $name21);
        $name2=$time2.$name2;
        move_uploaded_file($_FILES["driving_license_tp"]["tmp_name"],"/files/lorry4/".$name2);
        $driving_license_tp=$file_url.'lorry4/'.$time2.$name21.'';
        $array['driving_license_tp']=$driving_license_tp;
    }



    if($app_lorry_id!=''||$app_lorry_id!=null){
        $updateStatement = $database->update($array)
            ->table('app_lorry')
            ->where('exist','=',0)
            ->where('app_lorry_id','=',$app_lorry_id);
        $affectedRows = $updateStatement->execute();

        date_default_timezone_set("PRC");
        $shijian=date("Y-m-d H:i:s",time());
        $insertStatement = $database->insert(array('service_id','tab_name','tab_id','tenant_id','time'))
            ->into('operate_admin')
            ->values(array($admin_id,'app_lorry',$app_lorry_id,-1,$shijian));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result"=>"0","desc"=>"success"));
    }else{
        echo json_encode(array("result"=>"1","desc"=>"缺少车辆id"));
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