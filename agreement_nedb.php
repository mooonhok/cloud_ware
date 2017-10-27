<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/8
 * Time: 10:53
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/addAgreement',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $agreement_id=$body->agreement_id;
    $secondparty_id = $body->secondparty_id;
    $freight = $body->freight;
	$agreement_time=$body->agreement_time;
    $array = array();
    foreach ($body as $key => $value) {
        $array[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($agreement_id!=null||$agreement_id!=''){
            if($secondparty_id!=null||$secondparty_id!=''){
                if($freight!=null||$freight!=''){
                                if($agreement_time!=null||$agreement_time!=''){
                                    $array['tenant_id']=$tenant_id;
                                    $array['exist']=0;
                                    $insertStatement = $database->insert(array_keys($array))
                                        ->into('agreement')
                                        ->values(array_values($array));
                                    $insertId = $insertStatement->execute(false);
                                    echo json_encode(array("result" => "0", "desc" => "success"));
                                }else{
                                    echo json_encode(array("result" => "1", "desc" => "缺少合同生成时间"));
                                }
                }else{
                    echo json_encode(array("result" => "5", "desc" => "缺少运费"));
                }
            }else{
                echo json_encode(array("result" => "6", "desc" => "缺少乙方id"));
            }
        }else{
            echo json_encode(array("result" => "7", "desc" => "缺少合同id"));
        }
    }else{
        echo json_encode(array("result" => "8", "desc" => "缺少租户id"));
    }
});


$app->get('/getAgreements0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "1", "desc" => 'success','agreements'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});


$app->get('/getAgreements1',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "1", "desc" => 'success','agreements'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getAgreement',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $agreement_id=$app->request->get('agreement_id');
    if($tenant_id!=''||$tenant_id!=null){
        if($agreement_id!=null||$agreement_id!=''){
            $selectStatement = $database->select()
                ->from('agreement')
                ->where('tenant_id','=',$tenant_id)
                ->where('agreement_id',"=",$agreement_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            echo json_encode(array("result" => "0", "desc" => 'success','agreement'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少合同id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->put('/alterAgreement0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $agreement_id=$body->agreement_id;
    $agreement_comment=$body->agreement_comment;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=''||$tenant_id!=null){
        if($agreement_id!=null||$agreement_id!=''){
            if($agreement_comment!=null||$agreement_comment!=''){
                $updateStatement = $database->update(array('agreement_comment'=>$agreement_comment))
                    ->table('agreement')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('agreement_id','=',$agreement_id)
                    ->where('exist',"=","0");
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result" => "0", "desc" => "success"));
            }else{
                echo json_encode(array("result" => "1", "desc" => "缺少合同备注"));
            }
        }else{
            echo json_encode(array("result" => "2", "desc" => "缺少合同id"));
        }
    }else{
        echo json_encode(array("result" => "3", "desc" => "缺少租户id"));
    }
});


$app->run();

function localhost(){
    return connect();
}
?>