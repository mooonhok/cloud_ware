<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/12
 * Time: 11:38
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/province',function ()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('province');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","province"=>$data));
});

$app->get('/city',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $pid=$app->request->get('pid');
    if($pid!=null||$pid!=""){
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid','=',$pid);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }
});

$app->get('/city_p_name',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $p_name=$app->request->get('p_name');
    if($p_name!=null||$p_name!=""){
        $selectStatement = $database->select()
            ->from('province')
            ->where('name','=',$p_name);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid','=',$data1['id']);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
    }
});

//获得所有city
$app->get('/citys',function()use($app){
      $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $selectStatement = $database->select()
                ->from('city');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo  json_encode(array("result"=>"0","desc"=>"success","city"=>$data));
});

$app->get('/all',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $table=$app->request->get('table_name');
    $database=localhost();
    $selectStatement = $database->select()
        ->count('id','aaa')
        ->from($table.'');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tables"=>$data));
});

$app->post('/addpackage',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $package=$body->name;
    if($package!=null||$package!=""){
        $selectStatement = $database->select()
            ->from('goods_package')
            ->where('goods_package','=',$package);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data==null){
            $selectStatement = $database->select()
                ->from('goods_package');
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $insertStatement = $database->insert(array('goods_package_id','goods_package'))
                    ->into('goods_package')
                     ->values(array(count($data1)+1,$package));
            $insertId = $insertStatement->execute(false);
            if($insertId!=""||$insertId!=null){
                echo  json_encode(array("result"=>"0","desc"=>"添加成功"));
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"添加失败"));
            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"包装的方式已经存在"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"包装的方式为空"));
    }
});


$app->get('/datetime',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    date_default_timezone_set("PRC");
    $time=date('Y-m-d H:i:s');
    echo  json_encode(array("result"=>"0","desc"=>"success","time"=>$time));
});



$app->run();

function localhost(){
    return connect();
}
?>
