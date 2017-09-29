<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/29
 * Time: 15:22
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/sign',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
   $name=$body->name;
    $password1=$body->password;
    $str1=str_split($password1,3);
    $password=null;
    for ($x=0;$x<count($str1);$x++){
        $password.=$str1[$x].$x;
    }
    if($name!=null||$name!=""){
        $selectStament=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=','1')
            ->where('username','=',$name);
        $stmt=$selectStament->execute();
        $data=$stmt->fetch();
        if($data!=null||$data!=""){
           if($data['password']==$password){
               echo json_encode(array('result' => '0', 'desc' => '登录成功'));
           }else{
               echo json_encode(array('result' => '3', 'desc' => '密码错误'));
           }
        }else{
            echo json_encode(array('result' => '2', 'desc' => '用户不存在'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '名字为空'));
    }

});

$app->get('/dbadmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $scheduling_id=$body->sch_id;
    $array=arrays();
    $selectStament=$database->select()
        ->from('scheduling')
        ->where('scheduling_id','=',$scheduling_id);
    $stmt=$selectStament->execute();
    $data=$stmt->fetch();
    $selectStament=$database->select()
        ->from('customer')
        ->where('customer_id','=',$data['receiver_id']);
    $stmt=$selectStament->execute();
    $data2=$stmt->fetch();
    $selectStament=$database->select()
        ->from('tenant')
        ->where('tenant_id','=',$data['tenant_id']);
    $stmt=$selectStament->execute();
    $data3=$stmt->fetch();
    $selectStament=$database->select()
        ->from('lorry')
        ->where('lorry_id','=',$data['lorry_id']);
    $stmt=$selectStament->execute();
    $data4=$stmt->fetch();
    if($data!=null||$data!=""){
        $selectStament=$database->select()
            ->from('schedule_order')
            ->where('schedule_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetchAll();
        if($data5!=null||$data5!=""){
            for($x=0;$x<count($data5);$x++){
                $selectStament=$database->select()
                    ->from('order')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data6=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('goods')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data7=$stmt->fetch();
                $arrays1['order']=$data6;
                $arrays1['goods']=$data7;
                array_push($array,$arrays1);
            }
            echo json_encode(array('result' => '0', 'desc' => '','scheduling_order'=>$array,'scheduling_receive'=>$data2,'scheduling_tenant_id'=>$data3,
                 'scheduling_lorry_id'=>$data4));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单没有关联运单'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单不存在'));
    }
});

$app->run();

function localhost(){
    return connect();
}

?>