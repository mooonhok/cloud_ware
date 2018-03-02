<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/20
 * Time: 11:31
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('tenant');
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    if($data1!=null||$data1!=""){
        echo json_encode(array('result'=>'0','desc'=>'','tenant'=>$data1));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'尚未有数据','tenant'=>''));
    }
});


$app->get('/sontenants',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    if($data1['business_l']!=null||$data1['business_l']!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','!=',$tenant_id)
            ->where('business_l','=',$data1['business_l']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        for($x=0;$x<count($data2);$x++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id','=',$data2[$x]['contact_id'])
                ->where('tenant_id','=',$data2[$x]['tenant_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data2[$x]['telephone']=$data3['customer_name'].$data3['customer_phone'];
        }
        echo json_encode(array('result'=>'0','desc'=>'','tenants'=>$data2));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'尚未有数据','tenant'=>''));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>
