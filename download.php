<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/3/13
 * Time: 9:59
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getDownload',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStament=$database->select()
        ->from('download');
    $stmt=$selectStament->execute();
    $data=$stmt->fetchAll();
    echo json_encode(array('result' => '0', 'desc' => 'success', 'downloads'=>$data));
});


$app->post('/addRecord',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $sales_id=$body->sales_id;
    $type=$body->type;
    $time=$body->time;
    $ip=$body->ip;
    $area=$body->area;
    if($sales_id!=null||$sales_id!=""){
        if($type!=null||$type!=""){
            if($time!=null||$time!=""){
                        $selectStatement = $database->select()
                            ->from('download_record');
                        $stmt = $selectStatement->execute();
                        $data = $stmt->fetchAll();
                        $insertStatement = $database->insert(array('id','sales_id','type','time','ip','area'))
                            ->into('download_record')
                            ->values(array(count($data)+1,$sales_id,$type,$time,$ip,$area));
                        $insertId = $insertStatement->execute(false);
                        echo json_encode(array("result" => "0", "desc" => "添加成功"));

            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少时间"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少下载类别"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少业务员id"));
    }
});






$app->run();
function localhost(){
    return connect();
}
?>