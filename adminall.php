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
//清单模块
$app->get('/dbadmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $scheduling_id= $app->request->get("sch_id");
    $database=localhost();
    $array=array();
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
    $arrays2['customer_id']=$data2['customer_id'];
    $arrays2['customer_name']=$data2['customer_name'];
    $arrays2['customer_phone']=$data2['customer_phone'];
    $selectStament=$database->select()
        ->from('tenant')
        ->where('tenant_id','=',$data['tenant_id']);
    $stmt=$selectStament->execute();
    $data3=$stmt->fetch();
    $arrays3['tenant_id']=$data3['tenant_id'];
    $arrays3['company']=$data3['company'];
    $arrays3['address']=$data3['address'];
    $selectStament=$database->select()
        ->from('customer')
        ->where('customer_id','=',$data3['contact_id']);
    $stmt=$selectStament->execute();
    $data32=$stmt->fetch();
    $arrays3['contact_id']=$data3['contact_id'];
    $arrays3['contant_name']=$data32['customer_name'];
    $arrays3['contant_tel']=$data32['customer_phone'];
    $selectStament=$database->select()
        ->from('lorry')
        ->where('lorry_id','=',$data['lorry_id']);
    $stmt=$selectStament->execute();
    $data4=$stmt->fetch();
    $arrays4['lorry_id']=$data4['lorry_id'];
    $arrays4['plate_number']=$data4['plate_number'];
    $arrays4['driver_name']=$data4['driver_name'];
    $arrays4['driver_phone']=$data4['driver_phone'];
    if($data!=null){
        $selectStament=$database->select()
            ->from('schedule_order')
            ->where('schedule_id','=',$scheduling_id);
        $stmt=$selectStament->execute();
        $data5=$stmt->fetchAll();
        if($data5!=null){
            for($x=0;$x<count($data5);$x++){
                $selectStament=$database->select()
                    ->from('orders')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data6=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['sender_id']);
                $stmt=$selectStament->execute();
                $data62=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data62['customer_city_id']);
                $stmt=$selectStament->execute();
                $data622=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data6['receiver_id']);
                $stmt=$selectStament->execute();
                $data63=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('city')
                    ->where('id','=',$data63['customer_city_id']);
                $stmt=$selectStament->execute();
                $data632=$stmt->fetch();
                $selectStament=$database->select()
                    ->from('goods')
                    ->where('order_id','=',$data5[$x]['order_id']);
                $stmt=$selectStament->execute();
                $data7=$stmt->fetch();
                $arrays1['order_id']=$data6['order_id'];
               $arrays1['sender_id']=$data62['customer_id'];
               $arrays1['sender_phone']=$data62['customer_phone'];
               $arrays1['sender_name']=$data62['customer_name'];
               $arrays1['sendcity']=$data622['name'];
               $arrays1['receiver_id']=$data63['customer_id'];
               $arrays1['receiver_name']=$data63['customer_name'];
               $arrays1['receiver_phone']=$data63['customer_phone'];
                $arrays1['receiver_city']=$data632['name'];
                $arrays1['goods_id']=$data7['goods_id'];
                $arrays1['goods_name']=$data7['goods_name'];
                array_push($array,$arrays1);
            }
            echo json_encode(array('result' => '0', 'desc' => '','scheduling_order'=>$array,'scheduling_receive'=>$arrays2,'scheduling_tenant_id'=>$arrays3,
                 'scheduling_lorry_id'=>$arrays4));
        }else{
            echo json_encode(array('result' => '2', 'desc' => '清单没有关联运单'));
        }
    }else{
        echo json_encode(array('result' => '1', 'desc' => '清单不存在'));
    }
});
//公司信息
$app->get('/tenants',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
   // $arrayinsurance=array();
    $selectStament=$database->select()
        ->from('tenant');
    $stmt=$selectStament->execute();
    $data=$stmt->fetchAll();
    if($data!=null){
        for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$data[$x]['tenant_id'])
                    ->where('customer_id','=',$data[$x]['contact_id'])
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data2['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $data2['customer_city']=$data3['name'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['from_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$x]['from_city']=$data4['name'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $data[$x]['receive_city']=$data5['name'];
                $data[$x]['customer']=$data2;
             //   array_push($arrayt,$array1);
            $selectStatement = $database->select()
                ->from('insurance')
                ->where('tenant_id','=',$data[$x]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetchAll();
            for($y=0;$y<count($data6);$y++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['from_c_id']);
                $stmt = $selectStatement->execute();
                $data7 = $stmt->fetch();
                $data6[$y]['from_city']=$data7['name'];
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data6[$y]['receive_c_id']);
                $stmt = $selectStatement->execute();
                $data8 = $stmt->fetch();
                $data6[$y]['receive_city']=$data8['name'];
                $selectStatement = $database->select()
                    ->from('lorry')
                    ->where('lorry_id','=',$data6[$y]['insurance_lorry_id']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $data6[$y]['plate_number']=$data9['plate_number'];
                $data6[$y]['driver_name']=$data9['driver_number'];
                $data6[$y]['driver_phone']=$data9['driver_phone'];
            }
            $data[$x]['insurance']=$data6;
            $selectStatement = $database->select()
                ->from('rechanges_insurance')
                ->where('tenant_id','=',$data[$x]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data10 = $stmt->fetchAll();
            $data[$x]['rechanges']=$data10;
        }
        echo json_encode(array('result' => '0', 'desc' => '','tenants'=>$data));
    }else{
        echo json_encode(array('result' => '1', 'desc' => '尚未有公司'));
    }

});


$app->run();

function localhost(){
    return connect();
}

?>