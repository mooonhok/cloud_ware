<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/17
 * Time: 13:58
 */


require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/staff_login',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $name=$body->name;
    $password=$body->password;
    if($tenant_id!=null||$tenant_id!=''){
        if($name!=null||$name!=''){
            if($password!=null||$password!=''){
                $selectStatement = $database->select()
                    ->from('staff')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('username','=',$name)
                    ->where('password','=',encode($password,'cxphp'));
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data){
                    echo json_encode(array('result'=>'0','desc'=>'success'));
                }else{
                    echo json_encode(array('result'=>'4','desc'=>'暂无该用户'));
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


$app->run();

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