<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/10/14
 * Time: 11:00
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();


$app->get('/getAgreementSchedulings0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement_schedule')
            ->join('agreement','agreement.agreement_id','=','agreement_schedule.agreement_id','INNER')
            ->join('scheduling','agreement_schedule.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
            ->where('agreement.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('agreement_schedule.tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data1['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['send_city']=$data1;
            $data[$i]['receive_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_province']=$data4;
        }
        echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/getAgreementSchedulings1',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    if($tenant_id!=''||$tenant_id!=null){
        $selectStatement = $database->select()
            ->from('agreement_schedule')
            ->join('agreement','agreement.agreement_id','=','agreement_schedule.agreement_id','INNER')
            ->join('scheduling','agreement_schedule.scheduling_id','=','scheduling.scheduling_id','INNER')
            ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
            ->where('agreement.tenant_id','=',$tenant_id)
            ->where('scheduling.tenant_id','=',$tenant_id)
            ->where('lorry.tenant_id','=',$tenant_id)
            ->where('agreement_schedule.tenant_id','=',$tenant_id)
            ->where('agreement_schedule.exist','=',0)
            ->where('agreement.exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['send_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data1['pid']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('province')
                ->where('id', '=', $data2['pid']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data[$i]['send_city']=$data1;
            $data[$i]['receive_city']=$data2;
            $data[$i]['send_province']=$data3;
            $data[$i]['receive_province']=$data4;
        }
        echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});




$app->get('/getAgreementScheduling0',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $agreement_id=$app->request->get("agreement_id");
    if($tenant_id!=''||$tenant_id!=null){
        if($agreement_id!=''||$agreement_id!=null){
            $selectStatement = $database->select()
                ->from('agreement_schedule')
                ->join('agreement','agreement.agreement_id','=','agreement_schedule.agreement_id','INNER')
                ->join('scheduling','agreement_schedule.scheduling_id','=','scheduling.scheduling_id','INNER')
                ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
//                ->join('ticket','agreement.company_id','=','ticket.id','INNER')
                ->where('agreement.tenant_id','=',$tenant_id)
                ->where('agreement.agreement_id','=',$agreement_id)
                ->where('scheduling.tenant_id','=',$tenant_id)
                ->where('lorry.tenant_id','=',$tenant_id)
                ->where('agreement_schedule.tenant_id','=',$tenant_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            for($i=0;$i<count($data);$i++){
                $selectStatement = $database->select()
                    ->from('ticket')
                    ->where('id','=',$data[$i]['company_id']);
                $stmt = $selectStatement->execute();
                $data5 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['send_city_id']);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data1['pid']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id', '=', $data[$i]['receive_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('province')
                    ->where('id', '=', $data2['pid']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data[$i]['send_city']=$data1;
                $data[$i]['receive_city']=$data2;
                $data[$i]['send_province']=$data3;
                $data[$i]['receive_province']=$data4;
                $data[$i]['company']=$data5;
            }
            echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少合同id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->get('/limitAgreementSchedulings',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database=localhost();
    $offset=$app->request->get('offset');
    $size=$app->request->get('size');
    if($tenant_id!=''||$tenant_id!=null){
        if($offset!=''||$offset!=null){
            if($size!=''||$size!=null){
                $selectStatement = $database->select()
                    ->from('agreement_schedule')
                    ->join('agreement','agreement.agreement_id','=','agreement_schedule.agreement_id','INNER')
                    ->join('scheduling','agreement_schedule.scheduling_id','=','scheduling.scheduling_id','INNER')
                    ->join('lorry','lorry.lorry_id','=','agreement.secondparty_id','INNER')
                    ->where('agreement.tenant_id','=',$tenant_id)
                    ->where('scheduling.tenant_id','=',$tenant_id)
                    ->where('lorry.tenant_id','=',$tenant_id)
                    ->where('agreement_schedule.tenant_id','=',$tenant_id)
                    ->where('agreement_schedule.exist','=',0)
                    ->where('agreement.exist','=',0)
                    ->orderBy('agreement.agreement_id')
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetchAll();
                for($i=0;$i<count($data);$i++){
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data[$i]['send_city_id']);
                    $stmt = $selectStatement->execute();
                    $data1 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data1['pid']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data[$i]['receive_city_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('province')
                        ->where('id', '=', $data2['pid']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data[$i]['send_city']=$data1;
                    $data[$i]['receive_city']=$data2;
                    $data[$i]['send_province']=$data3;
                    $data[$i]['receive_province']=$data4;
                }
                echo json_encode(array("result" => "1", "desc" => 'success','agreement_schedules'=>$data));
            }else{
                echo json_encode(array("result" => "2", "desc" => "缺少size"));
            }
        }else{
            echo json_encode(array("result" => "3", "desc" => "缺少偏移量"));
        }
    }else{
        echo json_encode(array("result" => "4", "desc" => "缺少租户id"));
    }
});



$app->post('/addAgreementScheduling',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $secondparty_id=$body->secondparty_id;
    $pay_method=$body->pay_method;
    $deadline=$body->deadline;
    $agreement_require=$body->agreement_require;
    $rcity=$body->rcity;
    $is_ticket=$body->is_ticket;
    $company_id=$body->company_id;
    $scheduling_ary=$body->scheduling_ary;
    $freight=$body->freight;
    $tenant_num=$body->tenant_num;
    $array1=null;
    foreach ($scheduling_ary as $key => $value) {
        $array1[$key] = $value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('agreement')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data9 = $stmt->fetchAll();
        $agreement_id=null;
        if((count($data9)+1)<10){
            $agreement_id='HT'.$tenant_num."00000".(count($data9)+1);
        }else if((count($data9)+1)<100&&(count($data9)+1)>9){
            $agreement_id='HT'.$tenant_num."0000".(count($data9)+1);
        }else if((count($data9)+1)<1000&&(count($data9)+1)>99){
            $agreement_id='HT'.$tenant_num."000".(count($data9)+1);
        }else if((count($data9)+1)<10000&&(count($data9)+1)>999){
            $agreement_id='HT'.$tenant_num."00".(count($data9)+1);
        }else if((count($data9)+1)<100000&&(count($data9)+1)>9999){
            $agreement_id='HT'.$tenant_num."0".(count($data9)+1);
        }else if((count($data9)+1)<1000000&&(count($data9)+1)>99999){
            $agreement_id='HT'.$tenant_num.(count($data9)+1);
        }
        for($x=0;$x<count($array1);$x++){
            $selectStatement = $database->select()
                ->from('agreement_schedule')
                ->where('tenant_id', '=', $tenant_id)
                ->where('agreement_id','=',$agreement_id)
                ->where('scheduling_id','=',$array1[$x])
                ->where("exist",'=',0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $updateStatement = $database->update(array('is_contract'=>2))
                ->table('scheduling')
                ->where('scheduling_id','=',$array1[$x])
                ->where('tenant_id','=',$tenant_id);
            $affectedRows = $updateStatement->execute();
            if($data2==null) {
                $insertStatement = $database->insert(array("tenant_id", "agreement_id", "scheduling_id", "exist"))
                    ->into('agreement_schedule')
                    ->values(array($tenant_id, $agreement_id, $array1[$x], 0));
                $insertId = $insertStatement->execute(false);
            }
        }
        $insertStatement = $database->insert(array("agreement_id","secondparty_id","pay_method","deadline","agreement_require","rcity","is_ticket","company_id","freight","tenant_id"))
            ->into('agreement')
            ->values(array($agreement_id,$secondparty_id,$pay_method,$deadline,$agreement_require,$rcity,$is_ticket,$company_id,$freight,$tenant_id));
        $insertId = $insertStatement->execute(false);
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
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