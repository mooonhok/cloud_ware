<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/6/30
 * Time: 14:11
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();



$app->post('/addInsuranceSchedule',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->headers->get("tenant-id");
    $body=$app->request->getBody();
    $body=json_decode($body);
//    $plate_number=$body->plate_number;
//    $driver_name=$body->driver_name;
//    $driver_phone=$body->driver_phone;
    $insurance_price=$body->insurance_price;
    $insurance_amount=$body->insurance_amount;
    $scheduling_ary=$body->scheduling_ary;
    $insurance_lorry_id=$body->insurance_lorry_id;
    $array1=array();
    foreach($scheduling_ary as $key=>$value){
        $array1[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('insurance')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $insurance_id=1000000001+count($data);
        date_default_timezone_set("PRC");
        $insurance_start_time=date('Y-m-d H:i:s',time());
        $insertStatement = $database->insert(array("insurance_id","tenant_id","insurance_lorry_id","insurance_price","insurance_amount","insurance_start_time","exist"))
            ->into('insurance')
            ->values(array($insurance_id,$tenant_id,$insurance_lorry_id,$insurance_price,$insurance_amount,$insurance_start_time,0));
        $insertId = $insertStatement->execute(false);
        for($x=0;$x<count($array1);$x++){
            $selectStatement = $database->select()
                ->from('insurance_scheduling')
                ->where('tenant_id', '=', $tenant_id)
                ->where("insurance_id",'=',$insurance_id)
                ->where("scheduling_id","=",$array1[$x])
                ->where("exist","=",0);
            $stmt = $selectStatement->execute();
            $data2= $stmt->fetch();
            $updateStatement = $database->update(array('is_insurance'=>2))
                ->table('scheduling')
                ->where('scheduling_id','=',$array1[$x])
                ->where('tenant_id','=',$tenant_id)
                ->where('exist','=',0);
            $affectedRows = $updateStatement->execute();
            if($data2==null){
                $insertStatement = $database->insert(array("insurance_id","tenant_id","scheduling_id","exist"))
                    ->into('insurance_scheduling')
                    ->values(array($insurance_id,$tenant_id,$array1[$x],0));
                $insertId = $insertStatement->execute(false);
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success"));
    }else{
        echo json_encode(array("result" => "1", "desc" => "租户公司id为空","insurance_fee"=>''));
    }
});



$app->run();

function localhost(){
    return connect();
}
?>