<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/25
 * Time: 15:09
 */

require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addstaff',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $username=$body->username;
    $name=$body->name;
    $telephone=$body->telephone;
    $position=$body->position;
    $status=$body->status;
    $permission=$body->permission;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($username!=null||$username!=''){
            if($name!=null||$name!=''){
                if($telephone!=null||$telephone!=''){
                    if($position!=null||$position!=''){
                        if($status!=null||$status!=''){
                            if($permission!=null||$permission!=''){
                                $selectStatement = $database->select()
                                    ->from('staff')
                                    ->where('tenant_id',"=",$tenant_id)
                                    ->where('username','=',$username);
                                $stmt = $selectStatement->execute();
                                $data3 = $stmt->fetch();
                                if($data3==null){
                                    $selectStatement = $database->select()
                                        ->from('staff')
                                        ->where('tenant_id', '=', $tenant_id)
                                        ->where('name','=',$name)
                                        ->where('telephone','=',$telephone);
                                    $stmt = $selectStatement->execute();
                                    $data4 = $stmt->fetch();
                                    if($data4==null){
                                $array['head_img']="http://files.uminfo.cn:8000/staff/5230001_head.jpg";
                                $array['exist']=0;
                                $array['password']=encode('123456' , 'cxphp');
                                $array['tenant_id']=$tenant_id;
                                $array['bg_img']="http://files.uminfo.cn:8000/client/skin/bg1.jpg";
                                $selectStatement = $database->select()
                                    ->from('staff')
                                    ->where('tenant_id', '=', $tenant_id);
                                $stmt = $selectStatement->execute();
                                $data2 = $stmt->fetchAll();
                                $array['staff_id']=count($data2)+100001;

                                $insertStatement = $database->insert(array_keys($array))
                                    ->into('staff')
                                    ->values(array_values($array));
                                $insertId = $insertStatement->execute(false);
                                echo json_encode(array("result"=>"0","desc"=>"success"));
                                    }else{
                                        echo json_encode(array('result'=>'9','desc'=>'该员工已存在'));
                                    }
                                }else{
                                    echo json_encode(array('result'=>'8','desc'=>'用户名已存在'));
                                }
                            }else{
                                echo json_encode(array('result'=>'7','desc'=>'缺少状态'));
                            }
                        }else{
                            echo json_encode(array('result'=>'6','desc'=>'缺少状态'));
                        }
                    }else{
                        echo json_encode(array('result'=>'5','desc'=>'缺少职位'));
                    }
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'缺少电话号码'));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'缺少昵称'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'缺少员工名字'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'缺少租户'));
    }
});


$app->get('/getStaff1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $username=$app->request->get('username');
    $password=$app->request->get('password');
    $mac=$app->request->get('mac');
    $is_remember=$app->request->get("is_remember");
    date_default_timezone_set("PRC");
    $time=date('Y-m-d H:i:s',time());
    if($username!=null||$username!=''){
        if($password!=null||$password!=''){
            $selectStatement = $database->select()
                ->from('staff')
                ->where('tenant_id',"=",$tenant_id)
                ->where('username','=',$username)
                ->where('password','=',encode($password , 'cxphp'));
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                if($data['status']==2){
                    echo json_encode(array('result'=>'4','desc'=>'已经离职'));
                }else{
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->leftJoin('customer','customer.customer_id','=','tenant.contact_id')
                        ->where('tenant.tenant_id','=',$tenant_id)
                        ->where('customer.tenant_id','=',$tenant_id)
                        ->where('tenant.exist',"=",0);
                    $stmt = $selectStatement->execute();
                    $data1= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data2['pid']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $data1['city']=$data2;
                    $data1['province']=$data3;
                    $selectStatement = $database->select()
                        ->from('staff_mac')
                        ->where('staff_id',"=",$data['staff_id'])
                        ->where('tenant_id','=',$tenant_id)
                        ->where('is_login','=',1);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetchAll();
                    for($x=0;$x<count($data5);$x++){
                        if($data5[$x]["mac"]!=$mac){
                            $updateStatement = $database->update(array("is_login"=>0))
                                ->table('staff_mac')
                                ->where('id','=',$data5[$x]['id']);
                            $affectedRows = $updateStatement->execute();
                        }
                    }
                    $selectStatement = $database->select()
                        ->from('staff_mac')
                        ->where('staff_id',"=",$data['staff_id'])
                        ->where('tenant_id','=',$tenant_id)
                        ->where('mac','=',$mac);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    if($data4==null){
                        $insertStatement = $database->insert(array("mac","tenant_id","staff_id","is_login","is_remember","login_time"))
                            ->into('staff_mac')
                            ->values(array($mac,$tenant_id,$data['staff_id'],1,$is_remember,$time));
                        $insertId = $insertStatement->execute(false);
                    }else{
                        $updateStatement = $database->update(array("is_login"=>1,"login_time"=>$time,"is_remember"=>$is_remember))
                            ->table('staff_mac')
                            ->where('id','=',$data4['id']);
                        $affectedRows = $updateStatement->execute();
                    }
                    echo json_encode(array('result'=>'0','desc'=>'success','staff'=>$data,"tenant"=>$data1));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'账号不存在'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少密码'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'缺少员工名字'));
    }
});

$app->get('/getStaff2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $telephone=$app->request->get('telephone');
    $password=$app->request->get('password');
    $mac=$app->request->get('mac');
    $is_remember=$app->request->get("is_remember");
    date_default_timezone_set("PRC");
    $time=date('Y-m-d H:i:s',time());
    if($telephone!=null||$telephone!=''){
        if($password!=null||$password!=''){
            $selectStatement = $database->select()
                ->from('staff')
                ->where('tenant_id',"=",$tenant_id)
                ->where('telephone','=',$telephone)
                ->where('password','=',encode($password , 'cxphp'));
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                if($data['status']==2){
                    echo json_encode(array('result'=>'4','desc'=>'已经离职'));
                }else{
                    $selectStatement = $database->select()
                        ->from('tenant')
                        ->leftJoin('customer','customer.customer_id','=','tenant.contact_id')
                        ->where('tenant.tenant_id','=',$tenant_id)
                        ->where('customer.tenant_id','=',$tenant_id)
                        ->where('tenant.exist',"=",0);
                    $stmt = $selectStatement->execute();
                    $data1= $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data2['pid']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $data1['city']=$data2;
                    $data1['province']=$data3;
                    $selectStatement = $database->select()
                        ->from('staff_mac')
                        ->where('staff_id',"=",$data['staff_id'])
                        ->where('tenant_id','=',$tenant_id)
                        ->where('is_login','=',1);
                    $stmt = $selectStatement->execute();
                    $data5 = $stmt->fetchAll();
                    for($x=0;$x<count($data5);$x++){
                        if($data5[$x]["mac"]!=$mac){
                            $updateStatement = $database->update(array("is_login"=>0))
                                ->table('staff_mac')
                                ->where('id','=',$data5[$x]['id']);
                            $affectedRows = $updateStatement->execute();
                        }
                    }
                    $selectStatement = $database->select()
                        ->from('staff_mac')
                        ->where('staff_id',"=",$data['staff_id'])
                        ->where('tenant_id','=',$tenant_id)
                        ->where('mac','=',$mac);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    if($data4==null){
                        $insertStatement = $database->insert(array("mac","tenant_id","staff_id","is_login","is_remember","login_time"))
                            ->into('staff_mac')
                            ->values(array($mac,$tenant_id,$data['staff_id'],1,$is_remember,$time));
                        $insertId = $insertStatement->execute(false);
                    }else{
                        $updateStatement = $database->update(array("is_login"=>1,"login_time"=>$time,"is_remember"=>$is_remember))
                            ->table('staff_mac')
                            ->where('id','=',$data4['id']);
                        $affectedRows = $updateStatement->execute();
                    }
                    echo json_encode(array('result'=>'0','desc'=>'success','staff'=>$data,"tenant"=>$data1));
                }
            }else{
                echo json_encode(array('result'=>'3','desc'=>'账号不存在'));
            }
        }else{
            echo json_encode(array('result'=>'1','desc'=>'缺少密码'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'缺少员工名字'));
    }
});


$app->get('/getStaffs1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('staff')
//        ->where('exist',"=",0)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array('result'=>'0','desc'=>'success','staff'=>$data));
});


$app->get('/limitStaffs',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($size!=null||$size!=''){
        if($offset!=null||$offset!=''){
            if($tenant_id!=null||$tenant_id!=''){
                $selectStatement = $database->select()
                    ->from('staff')
//                    ->where('exist',"=",0)
                    ->where('tenant_id','=',$tenant_id)
                    ->orderBy('staff_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                echo json_encode(array('result'=>'0','desc'=>'success','staffs'=>$data));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'租户为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'偏移量为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'size为空'));
    }
});



$app->get('/searchStaff0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $staff_id=$app->request->get('staff_id');
    if($staff_id!=null||$staff_id!=''){
        if($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('staff')
//                ->where('exist',"=",0)
                ->where('staff_id','=',$staff_id)
                ->where('tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                 $data[$i]['password']=decode($data[$i]['password'],'cxphp');
            }
            echo json_encode(array('result'=>'0','desc'=>'success','staff'=>$data));
        }else{
            echo json_encode(array('result'=>'1','desc'=>'租户为空'));
        }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'员工id为空'));
    }
});

$app->put('/alterStaff',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $staff_id=$body->staff_id;
    $name=$body->name;
    $telephone=$body->telephone;
    $position=$body->position;
    $status=$body->status;
    $permission=$body->permission;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($staff_id!=null||$staff_id!=''){
        if($tenant_id!=null||$tenant_id!=''){
            if($name!=null||$name!=''){
                if($telephone!=null||$telephone!=''){
                   if($position!=null||$position!=''){
                       if($status!=null||$status!=''){
                           if($permission!=null||$permission!=''){
                               $updateStatement = $database->update($array)
                                   ->table('staff')
                                   ->where('tenant_id','=',$tenant_id)
//                                   ->where('exist',"=",0)
                                   ->where('staff_id','=',$staff_id);
                               $affectedRows = $updateStatement->execute();
                               echo json_encode(array('result'=>'0','desc'=>'success'));
                           }else{
                               echo json_encode(array('result'=>'1','desc'=>'权限为空'));
                           }
                       }else{
                           echo json_encode(array('result'=>'2','desc'=>'状态为空'));
                       }
                    }else{
                       echo json_encode(array('result'=>'3','desc'=>'职位为空'));
                    }
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'电话为空'));
                }
            }else{
                echo json_encode(array('result'=>'5','desc'=>'姓名为空'));
            }
        }else{
            echo json_encode(array('result'=>'6','desc'=>'租户为空'));
        }
    }else{
        echo json_encode(array('result'=>'7','desc'=>'员工id为空'));
    }
});

$app->get('/limitStaffs0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $staff_id=$app->request->get('staff_id');
    if($size!=null||$size!=''){
        if($offset!=null||$offset!=''){
            if($tenant_id!=null||$tenant_id!=''){
                if($staff_id!=null||$staff_id!=''){
                    $selectStatement = $database->select()
                        ->from('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$staff_id)
                        ->orderBy('staff_id')
                        ->limit((int)$size,(int)$offset);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetchAll();
                    echo json_encode(array('result'=>'0','desc'=>'success','staffs'=>$data));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'租户为空'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'偏移量为空'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'size为空'));
    }
});

$app->get('/searchStaff1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $name=$app->request->get('name');
            if($tenant_id!=null||$tenant_id!=''){
                if($name!=null||$name!=''){
                    $selectStatement = $database->select()
                        ->from('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('name','=',$name);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetchAll();
                    echo json_encode(array('result'=>'0','desc'=>'success','staffs'=>$data));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'员工姓名为空'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'租户为空'));
            }
});

$app->get('/limitStaffs1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    $name=$app->request->get('name');
    if($size!=null||$size!=''){
        if($offset!=null||$offset!=''){
            if($tenant_id!=null||$tenant_id!=''){
                if($name!=null||$name!=''){
                    $selectStatement = $database->select()
                        ->from('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('name','=',$name)
                        ->orderBy('staff_id')
                        ->limit((int)$size,(int)$offset);
                    $stmt = $selectStatement->execute();
                    $data = $stmt->fetchAll();
                    echo json_encode(array('result'=>'0','desc'=>'success','staffs'=>$data));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'租户为空'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'偏移量为空'));
        }
    }else{
        echo json_encode(array('result'=>'4','desc'=>'size为空'));
    }
});

$app->put('/alterStaff1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $bg_img=$body->bg_img;
    $staff_id=$body->staff_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
        if($tenant_id!=null||$tenant_id!=''){
            if($bg_img!=null||$bg_img!=''){
                if($staff_id!=null||$staff_id!=''){
                    $updateStatement = $database->update($array)
                        ->table('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$staff_id);
//                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'背景图为空'));
            }
        }else{
            echo json_encode(array('result'=>'3','desc'=>'租户为空'));
        }
});

$app->put('/alterStaff2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $staff_id=$body->staff_id;
    $password=$body->password;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
            if($staff_id!=null||$staff_id!=''){
                if($password!=null||$password!=''){
                    $password=encode($password , 'cxphp');
                    $array['password']=$password;
                    $updateStatement = $database->update($array)
                        ->table('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$staff_id);
//                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'1','desc'=>'密码为空'));
                }
            }else{
                echo json_encode(array('result'=>'2','desc'=>'员工id为空'));
            }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户为空'));
    }
});

$app->put('/alterStaff3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $bg_img=$body->head_img;
    $staff_id=$body->staff_id;
    $array['head_img']=$bg_img;
    if($tenant_id!=null||$tenant_id!=''){
        if($bg_img!=null||$bg_img!=''){
            if($staff_id!=null||$staff_id!=''){
                $updateStatement = $database->update($array)
                    ->table('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('staff_id','=',$staff_id);
//                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success'));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'头像图为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户为空'));
    }
});

$app->post('/uploadStaff',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->params('tenant_id');
    $staff_id=$app->request->params('staff_id');
    $database=localhost();
    $file_url=file_url();
    $array=array();
    if(isset($_FILES["head_img"])){
        $name11 = $_FILES["head_img"]["name"];
        if($name11){
            $name1=substr(strrchr($name11, '.'), 1);
            $shijian = time();
            $name1 = $shijian .".". $name1;
            move_uploaded_file($_FILES["head_img"]["tmp_name"], "/files/staff/" . $name1);
            $array['head_img']=$file_url."staff/".$name1;
        }
        if($tenant_id!=null||$tenant_id!=''){
            if($staff_id!=null||$staff_id!=''){
                $updateStatement = $database->update($array)
                    ->table('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('staff_id','=',$staff_id);
//                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success','url'=>$array['head_img']));
            }else{
                echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
            }
       }else{
            echo json_encode(array('result'=>'2','desc'=>'头像图为空'));
      }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户为空'));
    }
});

$app->put('/alterStaff4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $old_passwd=$body->old_passwd;
    $staff_id=$body->staff_id;
    $new_passwd=$body->new_passwd;
    if($tenant_id!=null||$tenant_id!=''){
        if($old_passwd!=null||$old_passwd!=''){
            if($staff_id!=null||$staff_id!=''){
                $selectStatement = $database->select()
                    ->from('staff')
                    ->where('staff_id','=',$staff_id)
                    ->where('tenant_id','=',$tenant_id)
                    ->where('password','=',encode($old_passwd,'cxphp'));
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data!=null){
                    $updateStatement = $database->update(array("password"=>encode($new_passwd,'cxphp')))
                        ->table('staff')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('staff_id','=',$staff_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'旧密码错误'));
                }
            }else{
                echo json_encode(array('result'=>'1','desc'=>'员工id为空'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'旧密码为空'));
        }
    }else{
        echo json_encode(array('result'=>'3','desc'=>'租户为空'));
    }
});

$app->run();
function file_url(){
    return files_url();
}
function localhost(){
    return connect();
}
//加密
function encode($string , $skey ) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
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