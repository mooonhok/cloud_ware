<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/31
 * Time: 9:35
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addAdmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
         $insertStatement = $database->insert(array_keys($array))
                   ->into('lisence_admin')
                   ->values(array_values($array));
         $insertId = $insertStatement->execute(false);
         echo json_encode(array("result"=>"0","desc"=>"success"));

});

//$app->get('/getAdmin0',function()use($app){
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database = localhost();
//    $username=$app->request->get('username');
//    $passwd=$app->request->get('passwd');
//    $selectStatement = $database->select()
//        ->from('lisence_admin')
//        ->where('exist','=',0)
//        ->where('username','=',$username)
//        ->where('passwd','=',$passwd);
//    $stmt = $selectStatement->execute();
//    $data = $stmt->fetch();
//    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data));
//});

$app->get('/getAdmin0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $username=$app->request->get('username');
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0)
        ->where('username','=',$username);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","lisence_admin"=>$data));
});

$app->get('/getTenant0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $tenant_id=$app->request->get('tenant_id');
    $company=$app->request->get('company');
    $tenant_num=$app->request->get('tenant_num');
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('company','=',$company)
        ->where('tenant_num','=',$tenant_num)
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data));
});


$app->get('/getAdmins',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","lisence_admin"=>$data));
});

$app->get('/limitAdmins',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0)
        ->orderBy('id')
        ->limit((int)$size,(int)$offset);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","lisence_admin"=>$data));
});

$app->get('/getAdmin1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $id=$app->request->get('id');
    $selectStatement = $database->select()
        ->from('lisence_admin')
        ->where('exist','=',0)
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","lisence_admin"=>$data));
});


$app->put('/alterAdmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $array['username']=$body->username;
    $array['passwd']=$body->passwd;
    $array['permission']=$body->permission;
    $array['exist']=$body->exist;
    $updateStatement = $database->update($array)
        ->table('lisence_admin')
        ->where('id', '=', $id);
    $affectedRows = $updateStatement->execute();
    if($affectedRows!=null){
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "未执行"));
    }
});

$app->put('/removeAdmin',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $updateStatement = $database->delete()
        ->from('lisence_admin')
        ->where('id', '=', $id);
    $affectedRows = $updateStatement->execute();
    if($affectedRows!=null){
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "未执行"));
    }
});

$app->get('/getRecords',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $id=$app->request->get('admin_id');
    $selectStatement = $database->select()
        ->from('lisence_record')
        ->where('admin_id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "","lisence_record"=>$data));
});


$app->get('/limitRecords',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $id=$app->request->get('admin_id');
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $selectStatement = $database->select()
        ->from('lisence_record')
        ->where('admin_id','=',$id)
        ->limit((int)$size,(int)$offset);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "","lisence_record"=>$data));
});



$app->post('/addRecord',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $company=$body->company;
    $tenant_num=$body->tenant_num;
    $mac=$body->mac;
    $create_type=$body->create_type;
    $create_time=$body->create_time;
    $admin_id=$body->admin_id;
    if($tenant_id!=null||$tenant_id!=""){
      if($company!=null||$company!=""){
          if($tenant_num!=null||$tenant_num!=""){
              if($mac!=null||$mac!=""){
                  if($create_time!=null||$create_time!=""){
                      if($create_type!=null||$create_type!=""){
                          if($admin_id!=null||$admin_id!=""){
                              $selectStatement = $database->select()
                                  ->from('lisence_record');
                              $stmt = $selectStatement->execute();
                              $data = $stmt->fetchAll();
                              $insertStatement = $database->insert(array('id','tenant_id','company','tenant_num','mac','create_type','create_time','admin_id'))
                                  ->into('lisence_record')
                                  ->values(array(count($data)+1,$tenant_id,$company,$tenant_num,$mac,$create_type,$create_time,$admin_id));
                              $insertId = $insertStatement->execute(false);
                              echo json_encode(array("result" => "0", "desc" => "添加成功"));
                          }else{
                              echo json_encode(array("result" => "7", "desc" => "缺少管理员id"));
                          }
                      }else{
                          echo json_encode(array("result" => "6", "desc" => "缺少创建类型"));
                      }
                  }else{
                      echo json_encode(array("result" => "5", "desc" => "缺少创建时间"));
                  }
              }else{
                  echo json_encode(array("result" => "4", "desc" => "缺少物理地址"));
              }
          }else{
              echo json_encode(array("result" => "3", "desc" => "缺少单号开头"));
          }
      }else{
          echo json_encode(array("result" => "2", "desc" => "缺少公司名称"));
      }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少tenantid"));
    }
});

$app->get('/getRecords1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $selectStatement = $database->select()
        ->from('lisence_record')
        ->where('tenant_id','=',$tenant_id)
        ->where('admin_id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "","lisence_record"=>$data));
});

$app->get('/limitRecords1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $id=$app->request->get('admin_id');
    $tenant_id=$app->request->get('tenant_id');
    $size= $app->request->get('size');
    $offset= $app->request->get('offset');
    $selectStatement = $database->select()
        ->from('lisence_record')
        ->where('tenant_id','=',$tenant_id)
        ->where('admin_id','=',$id)
        ->limit((int)$size,(int)$offset);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode(array("result" => "0", "desc" => "","lisence_record"=>$data));
});


$app->run();
function localhost(){
    return connect();
}
?>