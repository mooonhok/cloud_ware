<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/1/24
 * Time: 17:50
 */

require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/getInsurances1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $selectStatement = $database->select()
        ->from('insurance')
        ->where('tenant_id','=',$tenant_id)
        ->orderBy('insurance.insurance_start_time',"DESC");
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','count'=>count($data1)));
});

$app->get('/limitInsurances1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $selectStatement = $database->select()
        ->from('insurance')
        ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
        ->where('insurance.tenant_id','=',$tenant_id)
        ->where('lorry.tenant_id','=',$tenant_id)
        ->orderBy('insurance.insurance_start_time','DESC')
        ->limit((int)$size,(int)$offset);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$data1));
});


$app->get('/getInsurances2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array=array();
    $array1=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $plate_number=$app->request->get("plate_number");
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$tenant_id)
        ->where('plate_number','=',$plate_number);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('insurance_scheduling','insurance_scheduling.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->where('insurance_scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id'])
            ->groupBy('insurance_scheduling.insurance_id');
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetchAll();
        if(!$array){
            $array=$data2;
        }else{
            $array=array_merge($array,$data2);
        }
    }
    for($j=0;$j<count($array);$j++){
        $selectStatement = $database->select()
            ->from('insurance')
            ->where('tenant_id','=',$tenant_id)
            ->where('insurance_id','=',$array[$j]['insurance_id']);
        $stmt = $selectStatement->execute();
        $data3= $stmt->fetch();
        array_push($array1,$data3);
    }
    echo json_encode(array('result'=>'1','desc'=>'success','count'=>count($array1)));
});




$app->get('/limitInsurances2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $array=array();
    $array1=array();
    $array2=array();
    $tenant_id=$app->request->headers->get("tenant-id");
    $plate_number=$app->request->get("plate_number");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $selectStatement = $database->select()
        ->from('lorry')
        ->where('tenant_id','=',$tenant_id)
        ->where('plate_number','=',$plate_number);
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('scheduling')
            ->join('insurance_scheduling','insurance_scheduling.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->where('insurance_scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('scheduling.lorry_id','=',$data1[$i]['lorry_id'])
            ->groupBy('insurance_scheduling.insurance_id');
        $stmt = $selectStatement->execute();
        $data2= $stmt->fetchAll();
        if(!$array){
            $array=$data2;
        }else{
            $array=array_merge($array,$data2);
        }
    }
    for($j=0;$j<count($array);$j++){
        $selectStatement = $database->select()
            ->from('insurance')
            ->join('lorry','lorry.lorry_id','=','insurance.insurance_lorry_id','INNER')
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('insurance.tenant_id','=',$tenant_id)
            ->where('insurance.insurance_id','=',$array[$j]['insurance_id']);
        $stmt = $selectStatement->execute();
        $data3= $stmt->fetch();
        array_push($array1,$data3);
    }
    $num=0;
    if((int)$offset<(count($array1)-$size)){
        $num=(int)$offset;
    }else{
        $num=count($array1);
    }
    for ($i =(int)$offset; $i <$num; $i++) {
        array_push($array2,$array1[$i]);
    }
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$array2));
});

$app->get('/getInsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $insurance_id=$app->request->get('insurance_id');
    $selectStatement = $database->select()
        ->from('insurance')
        ->join('insurance_scheduling','insurance_scheduling.insurance_id','=','insurance.insurance_id','INNER')
        ->join('scheduling','scheduling.scheduling_id','=','insurance_scheduling.scheduling_id','INNER')
        ->where('insurance_scheduling.tenant_id','=',$tenant_id)
        ->where('insurance.tenant_id','=',$tenant_id)
        ->where('scheduling.tenant_id','=',$tenant_id)
        ->where('insurance.insurance_id','=',$insurance_id)
        ->where('insurance_scheduling.insurance_id','=',$insurance_id)
        ->orderBy('scheduling.scheduling_id');
    $stmt = $selectStatement->execute();
    $data1= $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['receive_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id', '=', $data1[$i]['send_city_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $data1[$i]['receive_city']=$data2['name'];
        $data1[$i]['send_city']=$data3['name'];
    }
    echo json_encode(array('result'=>'1','desc'=>'success','insurances'=>$data1));
});


$app->run();

function localhost(){
    return connect();
}
?>
