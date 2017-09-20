<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/14
 * Time: 8:50
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

//批量上传，有改无增
$app->get('/insurance_fee',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('insurance_fee');
        $stmt = $selectStatement->execute();
        $data1= $stmt->fetchAll();
        echo json_encode(array("result" => "1", "desc" => "success","insurance_fee"=>$data1));
    }else{
        echo json_encode(array("result" => "2", "desc" => "租户公司id为空","insurance_fee"=>''));
    }

});

$app->run();
function localhost(){
    return connect();
}
?>