<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/9
 * Time: 18:02
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/test',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $pic2=$body->name;
    $trans_c_p=null;
    if($pic2!=null) {
        $base64_image_content = $pic2;
//        echo json_encode(array("result"=>"0","desc"=>"",'a'=>$base64_image_content));
//匹配出图片的格式
//        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
//        if (preg_match('/^(data:\s*application\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = 'doc';
//          echo json_encode(array("result"=>"0","desc"=>"",'a'=>$result));
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/trans_contract_p/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
//          $a="data:application/vnd.openxmlformats-officedocument.wordprocessingml.document;base64,";
             $arr=explode(",",$base64_image_content);
            $a=$arr[0];
            if (file_put_contents($new_file, base64_decode(str_replace($a, '', $base64_image_content)))) {
                $trans_c_p = "http://files.uminfo.cn:8000/trans_contract_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
//        }
    }
    echo json_encode(array("result"=>"0","desc"=>"",'a'=>$trans_c_p));
});

$app->run();

function localhost(){
    return connect();
}
?>