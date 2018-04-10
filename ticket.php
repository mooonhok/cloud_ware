<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 13:10
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/gettickets',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->leftJoin('ticket_lorry','ticket_lorry.company_id','=','ticket.id')
        ->orderBy('sign_img','desc');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>$data));
});

$app->get('/getticket',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>$data));
});

$app->post('/addticketlorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $app_lorry_id=$body->app_lorry_id;
    $pic=$body->pic;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1 = time();
        $new_file = "/files/ticket_sign/" . date('Ymd', $time1) . "/";
        if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file . $time1 . ".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            $lujing1 = $file_url."ticket_sign/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
        }
    }
    $arrays['sign_img']=$lujing1;
    if($id!=null||$id!=""){
        if($app_lorry_id!=null||$app_lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$app_lorry_id);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $arrays['company_id']=$id;
                $arrays['lorry_id']=$app_lorry_id;
                $insertStatement = $database->insert(array('company_id','lorry_id','sign_img'))
                    ->into('ticket_lorry')
                    ->values(array($id,$app_lorry_id,$arrays['sign_img']));
                $insertId = $insertStatement->execute(false);
                echo  json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"您未填写电话和密码"));
            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"您未填写身份证号"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"您未填写姓名"));
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