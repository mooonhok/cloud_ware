<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/5
 * Time: 14:27
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/userlogin',function ()use($app){
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
    if($username!=""||$username!=null){
        $selectStaement=$database->select()
            ->from('admin')
            ->where('exist','=',0)
            ->where('type','=',0)
            ->where('username','=',$username);
        $stmt=$selectStaement->execute();
        $data=$stmt->fetch();
        if ($data!=null){
            $selectStaement=$database->select()
                ->from('admin')
                ->where('password','=',$password)
                ->where('exist','=',0)
                ->where('type','=',0)
                ->where('username','=',$username);
            $stmt=$selectStaement->execute();
            $data2=$stmt->fetch();
            if($data2!=null){
                echo json_encode(array('result'=>'0','desc'=>'登录成功','user'=>$data2));
            }else{
                echo json_encode(array('result'=>'3','desc'=>'密码错误','user'=>''));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'用户不存在','user'=>''));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'用户名为空','user'=>''));
    }

});

//客户端,生成去投保险
$app->get('/to_one_insurance',function ()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array2=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $array1=array();

        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('lorry','lorry.lorry_id','=','scheduling.lorry_id','INNER')
            ->where('scheduling.is_insurance', '=', 0)
            ->where('scheduling.scheduling_status','=',1)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data2!=null){
            for($j=0;$j<count($data2);$j++){
                $selectStatement = $database->select()
                    ->from('schedule_order')
                    ->join('orders','schedule_order.order_id','=','orders.order_id','INNER')
                    ->join('goods','goods.order_id','=','orders.order_id','INNER')
                    ->where('schedule_order.schedule_id', '=', $data2[$j]['scheduling_id'])
                    ->where('orders.tenant_id','=',$tenant_id)
                    ->where('goods.tenant_id','=',$tenant_id)
                    ->where('schedule_order.tenant_id','=',$tenant_id);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                array_push($array1,$data3);
                $data2[$j]['goods']=$array1;
            }
            array_push($array2,$data2);
        }

    }
    echo json_encode(array('result'=>'1','desc'=>'success','insurance'=>$array2));
});


//客户端，确认一个投保



//客户端，获取保险余额
$app->get('/insurance_balance',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    echo json_encode(array('result'=>'1','desc'=>'success','insurance'=>$data1));
});

$app->run();

function localhost(){
    return connect();
}
?>
