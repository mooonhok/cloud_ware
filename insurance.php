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
    $password=$body->password;
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

$app->run();

function localhost(){
    return connect();
}
?>
