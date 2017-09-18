<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/18
 * Time: 14:09
 */
require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/insurances',function ()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $company=$app->request->get('company');
    $city_id=$app->request->get('city_id');
    $page = $app->request->get('page');
    $per_page=$app->request->get('per_page');
    $database=localhost();

    if($page==null||$per_page==null){
        //   $page=(int)$page-1;
        if ($city_id != null || $city_id != "") {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                $num=count($data1);
                //            $sum=ceil($num/(int)$per_page);
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['duration'] = $data1[$x]['duration'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['from_c_id']);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $arrays1['from_city'] = $data2['name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['receive_c_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('code' => '0', 'msg' => '', 'data' => $arrays,'count'=>$num));
            } else {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                //  ->limit((int)10, (int)10 * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                $num=count($data1);
                //   $sum=ceil($num/(int)$per_page);
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['duration'] = $data1[$x]['duration'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['from_c_id']);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $arrays1['from_city'] = $data2['name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['receive_c_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('code' => '0', 'msg' => '', 'data' => $arrays,'count'=>$num));
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->where('insurance.sure_insurance', '=', '1')
                ->orderBy('insurance.insurance_start_time', 'desc');
            //         ->limit((int)10, (int)10 * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $num=count($data1);
            //  $sum=ceil($num/(int)$per_page);
            if ($data1 != "" || $data1 != null) {
                for ($x = 0; $x < count($data1); $x++) {
                    $arrays1['company'] = $data1[$x]['company'];
                    $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                    $arrays1['plate_number'] = $data1[$x]['plate_number'];
                    $arrays1['driver_name'] = $data1[$x]['driver_name'];
                    $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                    $arrays1['duration'] = $data1[$x]['duration'];
                    $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                    $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                    $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['from_c_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $arrays1['from_city'] = $data2['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['receive_c_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $arrays1['receive_city'] = $data3['name'];
                    array_push($arrays, $arrays1);
                }
            }
            echo json_encode(array('code' => '0', 'msg' => '', 'data' => $arrays,'count'=>$num));
        }
    }else{
        $page=(int)$page-1;
        if ($city_id != null || $city_id != "") {
            if ($company != null || $company != '') {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
                //   $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.company', '=', $company)
                    ->orderBy('insurance.insurance_start_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['duration'] = $data1[$x]['duration'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['from_c_id']);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $arrays1['from_city'] = $data2['name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['receive_c_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('code' => '0', 'msg' =>'', 'data' => $arrays,'count'=>$num));
            } else {
                $arrays=array();
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc');
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetchAll();
                $num=count($data4);
                //   $sum=ceil($num/(int)$per_page);
                $selectStatement = $database->select()
                    ->from('insurance')
                    ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                    ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                    ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                    ->where('insurance.sure_insurance', '=', '1')
                    ->where('tenant.from_city_id', '=', $city_id)
                    ->orderBy('insurance.insurance_start_time', 'desc')
                    ->limit((int)$per_page, (int)$per_page * (int)$page);
                $stmt = $selectStatement->execute();
                $data1 = $stmt->fetchAll();
                if ($data1 != null || $data1 != "") {
                    for ($x = 0; $x < count($data1); $x++) {
                        $arrays1['company'] = $data1[$x]['company'];
                        $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                        $arrays1['plate_number'] = $data1[$x]['plate_number'];
                        $arrays1['driver_name'] = $data1[$x]['driver_name'];
                        $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                        $arrays1['duration'] = $data1[$x]['duration'];
                        $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                        $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                        $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['from_c_id']);
                        $stmt = $selectStatement->execute();
                        $data2 = $stmt->fetch();
                        $arrays1['from_city'] = $data2['name'];
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data1[$x]['receive_c_id']);
                        $stmt = $selectStatement->execute();
                        $data3 = $stmt->fetch();
                        $arrays1['receive_city'] = $data3['name'];
                        array_push($arrays, $arrays1);
                    }
                }
                echo json_encode(array('code' => '0', 'msg' => '', 'data' => $arrays,'count'=>$num));
            }
        } else {
            $arrays=array();
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->where('insurance.sure_insurance', '=', '1')
                ->orderBy('insurance.insurance_start_time', 'desc');
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetchAll();
            $num=count($data4);
            // $sum=ceil($num/(int)$per_page);
            $selectStatement = $database->select()
                ->from('insurance')
                ->join('tenant', 'insurance.tenant_id', '=', 'tenant.tenant_id', 'INNER')
                ->join('lorry', 'lorry.lorry_id', '=', 'insurance.insurance_lorry_id', 'INNER')
                ->join('customer', 'tenant.contact_id', '=', 'customer.customer_id', 'INNER')
                ->where('insurance.sure_insurance', '=', '1')
                ->orderBy('insurance.insurance_start_time', 'desc')
                ->limit((int)$per_page, (int)$per_page * (int)$page);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            if ($data1 != "" || $data1 != null) {
                for ($x = 0; $x < count($data1); $x++) {
                    $arrays1['company'] = $data1[$x]['company'];
                    $arrays1['customer_phone'] = $data1[$x]['customer_phone'];
                    $arrays1['plate_number'] = $data1[$x]['plate_number'];
                    $arrays1['driver_name'] = $data1[$x]['driver_name'];
                    $arrays1['insurance_start_time'] = $data1[$x]['insurance_start_time'];
                    $arrays1['duration'] = $data1[$x]['duration'];
                    $arrays1['insurance_amount'] = $data1[$x]['insurance_amount'];
                    $arrays1['insurance_price'] = $data1[$x]['insurance_price'];
                    $arrays1['insurance_id'] = $data1[$x]['insurance_id'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['from_c_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $arrays1['from_city'] = $data2['name'];
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id', '=', $data1[$x]['receive_c_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $arrays1['receive_city'] = $data3['name'];
                    array_push($arrays, $arrays1);
                }
            }
            echo json_encode(array('code' => '0', 'msg' =>'', 'data' => $arrays,'count'=>$num));
        }
    }
});
$app->run();

function localhost(){
    return connect();
}
?>
