<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 9:10
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->post('/tenant',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $company=$body->company;
    $company_type=$body->company_type;
    $from_city=$body->from_city;
    $receive_city=$body->receive_city;
    $contact_id=$body->contact_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($company!=null||$company!=''){
        if($company_type!=null||$company_type!=''){
            if($from_city!=null||$from_city!=''){
                if($receive_city!=null||$receive_city!=''){
                        if($contact_id!=null||$contact_id!=''){
                                    $selectStatement = $database->select()
                                        ->from('tenant')
                                        ->where('company','=',$company);
                                    $stmt = $selectStatement->execute();
                                    $data = $stmt->fetchAll();
                                    if($data!=null){
                                        echo json_encode(array("result"=>"7","desc"=>"公司已存在"));
                                    }else{
                                        $selectStatement = $database->select()
                                            ->from('tenant');
                                        $stmt = $selectStatement->execute();
                                        $data1 = $stmt->fetchAll();
                                        $tenant_id=10000001+count($data1);
                                        $array['tenant_id']=$tenant_id;
                                        $array['exist']=0;
                                        $insertStatement = $database->insert(array_keys($array))
                                            ->into('tenant')
                                            ->values(array_values($array));
                                        $insertId = $insertStatement->execute(false);
                                        echo json_encode(array("result"=>"0","desc"=>"success"));
                                    }
                        }else{
                            echo json_encode(array('result'=>'1','desc'=>''));
                        }
                }else{
                    echo json_encode(array('result'=>'3','desc'=>'缺少收货地'));
                }
            }else{
                echo json_encode(array('result'=>'4','desc'=>'缺少发货地'));
            }
        }else{
            echo json_encode(array('result'=>'5','desc'=>'缺少公司类型'));
        }
    }else{
        echo json_encode(array('result'=>'6','desc'=>'缺少公司名字'));
    }
});

$app->put('/tenant',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $company=$body->company;
    $company_type=$body->company_type;
    $from_city=$body->from_city;
    $receive_city=$body->receive_city;
    $contact_id=$body->contact_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($company!=null||$company!=''){
        if($company_type!=null||$company_type!=''){
            if($from_city!=null||$from_city!=''){
                if($receive_city!=null||$receive_city!=''){
                    if($contact_id!=null||$contact_id!=''){
                        $selectStatement = $database->select()
                            ->from('tenant')
                            ->where('company','=',$company)
                            ->where('exist','=','0');
                        $stmt = $selectStatement->execute();
                        $data = $stmt->fetchAll();
                        if($data!=null){
                            $updateStatement = $database->update($array)
                                ->table('tenant')
                                ->where('company','=',$company);
                            $affectedRows = $updateStatement->execute();
                            echo json_encode(array("result"=>"0","desc"=>"success"));
                        }else{
                            echo json_encode(array("result"=>"1","desc"=>"该公司不存在"));
                        }
                    }else{
                        echo json_encode(array('result'=>'2','desc'=>''));
                    }
                }else{
                    echo json_encode(array('result'=>'3','desc'=>'缺少收货地'));
                }
            }else{
                echo json_encode(array('result'=>'4','desc'=>'缺少发货地'));
            }
        }else{
            echo json_encode(array('result'=>'5','desc'=>'缺少公司类型'));
        }
    }else{
        echo json_encode(array('result'=>'6','desc'=>'缺少公司名字'));
    }
});


$app->get('/tenant',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=localhost();
        if($page==null||$per_page==null){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data));
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('exist',"=",0)
                ->limit((int)$per_page,(int)$per_page*(int)$page);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data));
        }
});

$app->delete('/tenant',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database=localhost();
    $tenant_id=$app->request->get('tenantid');
    if ($tenant_id!=null||$tenant_id!=''){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id','=',$tenant_id)
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            if($data!=null){
                $updateStatement = $database->update(array('exist'=>1))
                    ->table('tenant')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array("result"=>"0","desc"=>"success"));
            }else{
                echo json_encode(array("result"=>"1","desc"=>"该公司不存在"));
            }
    }else{
        echo json_encode(array("result"=>"2","desc"=>"缺少租户id"));
    }
});

$app->run();

function localhost(){
    return connect();
}
?>