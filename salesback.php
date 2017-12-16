<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/16
 * Time: 10:22
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//业务员管理后台登录
$app->post('/sadmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $username=$body->username;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($username!=null||$username!=""){
        if($password!=null||$password!=""){
            $selectStament=$database->select()
                ->from('admin')
                ->where('exist','=',0)
                ->where('password','=',$password)
                ->where('username','=',$username);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                echo json_encode(array('result' => '0', 'desc' => '登录成功',"admin"=>$data['id']));
            }else{
                echo json_encode(array('result' => '3', 'desc' => '账号不存在或账号密码错误'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '缺少密码'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少用户名'));
    }
});

//查询业务员所有信息
$app->get('/sales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id = $app->request->get("adminid");
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $num=0;
    if($page==null||$page==""){
        if($admin_id!=""||$admin_id!=null){
            $selectStament=$database->select()
                ->from('admin')
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['type']==2||$data['type']==1){
                    $selectStament=$database->select()
                        ->from('sales');
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    $num=count($data2);
                    if($data2!=null){
                        echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
        }
    }else{
        $page=(int)$page-1;
        if($admin_id!=""||$admin_id!=null){
            $selectStament=$database->select()
                ->from('admin')
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['type']==1||$data['type']==2){
                    $selectStament=$database->select()
                        ->from('sales');
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    $num=count($data3);
                    $selectStament=$database->select()
                        ->from('sales')
                        ->limit((int)$per_page, (int)$per_page * (int)$page);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    if($data2!=null){
                        echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
        }
    }
});
//修改业务员状态
$app->post('/upsales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $admin_id=$body->admin_id;
    $arrays['exist']=$body->change;
    $arrays['teamid']=$body->change;
    if($admin_id!=null||$admin_id!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['type']==1){
                $selectStament=$database->select()
                    ->from('sales')
                    ->where('id','=',$sales_id);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                if($data2!=null){
                    $updateStatement = $database->update($arrays)
                        ->table('sales')
                        ->where('id', '=', $sales_id);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array('result' => '0', 'desc' => '修改成功'));
                }else{
                    echo json_encode(array('result' => '4', 'desc' => '业务员不存在'));
                }
            }else{
                echo json_encode(array('result' => '3', 'desc' => '您没有权限进行操作'));
            }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
    }
});

//添加业务员
$app->post('/addsales',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_name=$body->sales_name;
    $sex=$body->sex;
    $card_id=$body->card_id;
    $telephone=$body->telephone;
    $address=$body->address;
    $zip_code=$body->zip_code;
    $qq=$body->qq;
    $weixin=$body->weixin;
    $admin_id=$body->admin_id;
    if($admin_id!=""||$admin_id!=null){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('admin_id','=',$admin_id);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null){
            if($data['type']==1){
                if($sales_name!=null||$sales_name!=""){
                    if($sex!=null||$sex!=""){
                      if($card_id!=null||$card_id!=""){
                      $selectStatement = $database->select()
                    ->from('sales')
                    ->where('exist','=',0)
                    ->where('card_id', '=', $card_id);
                       $stmt = $selectStatement->execute();
                      $data1 = $stmt->fetch();
                 if($data1==null){
                        if($telephone!=null||$telephone!=""){
                         if($address!=null||$address!=""){
                             if($zip_code!=null||$zip_code!=""){
                                    $password1=substr($card_id,-6);
                                    // $num2=rand(1000000,10000000000);
                                    $str1=str_split($password1,3);
                                    $password=null;
                                    for ($x=0;$x<count($str1);$x++){
                                        $password.=$str1[$x].$x;
                                    }
                                    $insertStatement = $database->insert(array('exist','sales_name','sex','card_id','telephone','address'
                                    ,'zip_code','qq','weixin','password','higher_id'))
                                        ->into('sales')
                                        ->values(array(0,$sales_name,$sex,$card_id,$telephone,$address,$zip_code,$qq,$weixin,$password
                                        ,0));
                                    $insertId = $insertStatement->execute(false);
                                    $arrays['password']=$password1;
                                    echo json_encode(array('result' => '0', 'desc' => '添加成功','sales'=>$arrays));
                                  }else{
                                      echo json_encode(array('result' => '7', 'desc' => '邮箱不能为空','sales'=>''));
                                    }
                                 }else{
                                      echo json_encode(array('result' => '6', 'desc' => '地址不能为空','sales'=>''));
                                  }
                            }else{
                             echo json_encode(array('result' => '5', 'desc' => '电话不能为空','sales'=>''));
                         }
                       }else{
                           echo json_encode(array('result' => '4', 'desc' => '业务员已经存在','sales'=>''));
                       }
                       }else{
                       echo json_encode(array('result' => '3', 'desc' => '业务员身份证编号为空','sales'=>''));
                 }
                   }else{
                   echo json_encode(array('result' => '2', 'desc' => '没有选择性别','sales'=>''));
               }
                  }else{
                 echo json_encode(array('result' => '1', 'desc' => '业务员姓名为空','sales'=>''));
            }
            }else{
                echo json_encode(array('result' => '10', 'desc' => '您没有权限进行操作','sales'=>''));
            }
     }else{
         echo json_encode(array('result' => '9', 'desc' => '后台管理员不存在','sales'=>''));
     }
    }else{
        echo json_encode(array('result' => '8', 'desc' => '后台管理员id不存在','sales'=>''));
    }
});

//依据团队查询业务员
$app->get('/sbysalesid',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $admin_id=$app->request->get('adminid');
    $teamid=$app->request->get('teamid');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $num=0;
    if($page==null||$page==""){
        if($admin_id!=""||$admin_id!=null){
            $selectStament=$database->select()
                ->from('admin')
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['type']==2||$data['type']==1){
                    $selectStament=$database->select()
                        ->from('sales')
                        ->where('teamid','=',$teamid);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    $num=count($data2);
                    if($data2!=null){
                        echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
        }
    }else{
        $page=(int)$page-1;
        if($admin_id!=""||$admin_id!=null){
            $selectStament=$database->select()
                ->from('admin')
                ->where('admin_id','=',$admin_id);
            $stmt=$selectStament->execute();
            $data=$stmt->fetch();
            if($data!=null){
                if($data['type']==2||$data['type']==1){
                    $selectStament=$database->select()
                        ->from('sales')
                        ->where('teamid','=',$teamid);
                    $stmt=$selectStament->execute();
                    $data3=$stmt->fetchAll();
                    $num=count($data3);
                    $selectStament=$database->select()
                        ->from('sales')
                        ->where('teamid','=',$teamid)
                        ->limit((int)$per_page, (int)$per_page * (int)$page);
                    $stmt=$selectStament->execute();
                    $data2=$stmt->fetchAll();
                    if($data2!=null){
                        echo json_encode(array('result' => '0', 'desc' => '','sales'=>$data2,'count'=>$num));
                    }else{
                        echo json_encode(array('result' => '4', 'desc' => '尚未有业务员'));
                    }
                }else{
                    echo json_encode(array('result' => '3', 'desc' => '您没有足够权限'));
                }
            }else{
                echo json_encode(array('result' => '2', 'desc' => '管理员不存在'));
            }
        }else{
            echo json_encode(array('result' => '1', 'desc' => '缺少管理员id'));
        }
    }
});

$app->run();

function localhost(){
    return connect();
}

?>