<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 17:31
 */
require 'Slim/Slim.php';
require 'connect.php';
use Slim\PDO\Database;

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->post('/addCustomer',function()use($app) {
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
//    $customer_id=$body->customer_id;
    $customer_name = $body->customer_name;
    $customer_phone = $body->customer_phone;
    $customer_city_id = $body->customer_city_id;
    $customer_address = $body->customer_address;
    $type=$body->type;
    $contact_tenant_id=$body->contact_tenant_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
            if($customer_name!=null||$customer_name!=''){
                if($customer_phone!=null||$customer_phone!=''){
                    if($customer_city_id!=null||$customer_city_id!=''){
                        if($customer_address!=null||$customer_address!=''){
                            if( $contact_tenant_id==null|| $contact_tenant_id==""){
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->whereNull('wx_openid')
                                ->where('customer_name','=',$customer_name)
                                ->where('customer_phone','=',$customer_phone)
                                ->where('customer_city_id','=',$customer_city_id)
                                ->where('customer_address','=',$customer_address)
                                ->where('type','=',$type)
//                                ->where('contact_tenant_id','=',$contact_tenant_id)
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data2 = $stmt->fetch();
                            if($data2==null){
                            $array['tenant_id']=$tenant_id;
                            $array['exist']=0;
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('tenant_id', '=', $tenant_id);
                                $stmt = $selectStatement->execute();
                                $data1 = $stmt->fetch();
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->whereNull('wx_openid')
                                ->where('customer_id', '!=', $data1['contact_id'])
                                ->where('tenant_id', '=', $tenant_id);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetchAll();
                            $array['customer_id']=count($data)+10000000001;
                            $insertStatement = $database->insert(array_keys($array))
                                ->into('customer')
                                ->values(array_values($array));
                            $insertId = $insertStatement->execute(false);
                            echo json_encode(array("result" => "0", "desc" => "success",'customer'=>"".$array['customer_id']));
                            }else{
                                echo json_encode(array("result" => "6", "desc" => "用户已经存在",'customer'=>$data2['customer_id']));
                            }
                            }else{
                                $selectStatement = $database->select()
                                    ->from('customer')
                                    ->whereNull('wx_openid')
                                    ->where('customer_name','=',$customer_name)
                                    ->where('customer_phone','=',$customer_phone)
                                    ->where('customer_city_id','=',$customer_city_id)
                                    ->where('customer_address','=',$customer_address)
                                    ->where('type','=',$type)
                                    ->where('contact_tenant_id','=',$contact_tenant_id)
                                    ->where('tenant_id', '=', $tenant_id);
                                $stmt = $selectStatement->execute();
                                $data2 = $stmt->fetch();
                                if($data2==null){
                                    $array['tenant_id']=$tenant_id;
                                    $array['exist']=0;
                                    $selectStatement = $database->select()
                                        ->from('tenant')
                                        ->where('tenant_id', '=', $tenant_id);
                                    $stmt = $selectStatement->execute();
                                    $data1 = $stmt->fetch();
                                    $selectStatement = $database->select()
                                        ->from('customer')
                                        ->whereNull('wx_openid')
                                        ->where('customer_id', '!=', $data1['contact_id'])
                                        ->where('tenant_id', '=', $tenant_id);
                                    $stmt = $selectStatement->execute();
                                    $data = $stmt->fetchAll();
                                    $array['customer_id']=count($data)+10000000001;
                                    $insertStatement = $database->insert(array_keys($array))
                                        ->into('customer')
                                        ->values(array_values($array));
                                    $insertId = $insertStatement->execute(false);
                                    echo json_encode(array("result" => "0", "desc" => "success",'customer'=>"".$array['customer_id']));
                                }else{
                                    echo json_encode(array("result" => "6", "desc" => "用户已经存在",'customer'=>$data2['customer_id']));
                                }

                            }
                        }else{
                            echo json_encode(array("result" => "2", "desc" => "缺少用户城市的地址"));
                        }
                    }else{
                        echo json_encode(array("result" => "3", "desc" => "缺少用户的城市id"));
                    }
                }else{
                    echo json_encode(array("result" => "4", "desc" => "缺少用户手机"));
                }
            }else{
                echo json_encode(array("result" => "5", "desc" => "缺少用户名字"));
            }
    }else{
        echo json_encode(array("result" => "7", "desc" => "缺少租户id"));
    }
});

$app->get('/getCustomer',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $customer_name=$app->request->get('customer_name');
    $customer_phone = $app->request->get('customer_phone');
    $customer_city_id = $app->request->get('customer_city_id');
    $customer_address = $app->request->get('customer_address');
    $contact_tenant_id=$app->request->get('contact_tenant_id');
    $type=$app->request->get('type');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_name!=null||$customer_name!=''){
            if($customer_phone!=null||$customer_phone!=''){
                if($customer_city_id!=null||$customer_city_id!=''){
                    if($customer_address!=null||$customer_address!=''){
                        if($contact_tenant_id!=null||$contact_tenant_id!=''){
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('customer_name', '=', $customer_name)
                                ->where('customer_phone', '=', $customer_phone)
                                ->where('customer_city_id', '=', $customer_city_id)
                                ->where('customer_address', '=', $customer_address)
                                ->where('contact_tenant_id', "=", $contact_tenant_id)
                                ->where('type', "=", $type);
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetch();
                            echo json_encode(array("result" => "0", "desc" => "success",'customer'=>$data));
                        }else{
                            $selectStatement = $database->select()
                                ->from('customer')
                                ->where('tenant_id', '=', $tenant_id)
                                ->where('customer_name', '=', $customer_name)
                                ->where('customer_phone', '=', $customer_phone)
                                ->where('customer_city_id', '=', $customer_city_id)
                                ->where('customer_address', '=', $customer_address)
                                ->where('type', "=", $type)
                                ->whereNull('contact_tenant_id');
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetch();
                            echo json_encode(array("result" => "0", "desc" => "success",'customer'=>$data));
                        }
                    }else{
                        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
                    }
                }else{
                    echo json_encode(array("result" => "2", "desc" => "缺少客户城市id"));
                }
            }else{
                echo json_encode(array("result" => "3", "desc" => "缺少客户电话"));
            }
        }else{
            echo json_encode(array("result" => "4", "desc" => "缺少客户名字"));
        }
    }else{
        echo json_encode(array("result" => "5", "desc" => "缺少租户id"));
    }
});

$app->get('/getCustomer1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $customer_id=$app->request->get('customer_id');
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_id!=null||$customer_id!=''){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id', '=', $tenant_id)
                ->where('customer_id', '=', $customer_id);
            $stmt = $selectStatement->execute();
            $data = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data6 = $stmt->fetch();
//            $selectStatement = $database->select()
//                ->from('province')
//                ->where('id', '=', $data6['pid']);
//            $stmt = $selectStatement->execute();
//            $data8 = $stmt->fetch();
            if($data['contact_tenant_id']!=null){
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('exist', '=', 0)
                    ->where('tenant_id', '=', $data['contact_tenant_id']);
                $stmt = $selectStatement->execute();
                $data9 = $stmt->fetch();
                $data['contact_company']=$data9['company'];
                $data['contact_jcompany']=$data9['jcompany'];
            }else{
                $data['contact_company']='';
                $data['contact_jcompany']='';
            }
//            $data['customer_city']=$data6['name'];
//            $data['province']=$data8['name'];
            $data['province_id']=$data6['pid'];
            echo json_encode(array("result" => "0", "desc" => "success",'customer'=>$data));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少客户id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

//
$app->get("/getCustomers0",function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->whereNull('wx_openid')
            ->where('customer_id', '!=', $data1['contact_id'])
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        echo json_encode(array("result" => "0", "desc" => "success",'customers'=>$data));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});


$app->get('/getCustomers1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $array=array();
    $array1=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('customer_id', '!=', $data1['contact_id'])
            ->where('times','!=',0)
            ->whereNotNull('times')
            ->where('type', '=', 1)
            ->where('exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();

        for($i=0;$i<count($data);$i++){
            for($g=(count($data)-1);$g>$i;$g--){
                if($data[$i]['customer_name']==$data[$g]['customer_name']&&$data[$i]['customer_phone']==$data[$g]['customer_phone']){
                    $data[$i]['times']+=$data[$g]['times'];
                    array_push($array,$g);
                }
            }
        }
        for($i=0;$i<count($data);$i++){
            if($array){
                for($x=0;$x<count($array);$x++){
                    if($i==$array[$x]){
                        break;
                    }
                    if($x==(count($array)-1)){
                        array_push($array1,$data[$i]);
                    }
                }
            }else{
                $array1=$data;
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'customers'=>$array1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/getCustomers2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $array=array();
    $array1=array();
    $tenant_id = $app->request->headers->get("tenant-id");
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id', '=', $tenant_id)
//            ->where('times','!=',0)
            ->whereNotNull('times')
            ->where('type', '=', 3)
            ->where('exist', '=', 0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            for($g=(count($data)-1);$g>$i;$g--){
                if($data[$i]['contact_tenant_id']==$data[$g]['contact_tenant_id']){
                    $data[$i]['times']+=$data[$g]['times'];
                    array_push($array,$g);
                }
            }
        }
        for($i=0;$i<count($data);$i++){
            if($array){
                for($x=0;$x<count($array);$x++){
                    if($i==$array[$x]){
                        break;
                    }
                    if($x==(count($array)-1)){
                        array_push($array1,$data[$i]);
                    }
                }
            }else{
                $array1=$data;
            }
        }
        echo json_encode(array("result" => "0", "desc" => "success",'customers'=>$array1));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitCustomers0',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $array=array();
    $array1=array();
    $array2=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('customer_id', '!=', $data1['contact_id'])
            ->where('times','!=',0)
            ->whereNotNull('times')
            ->where('type', '=', 1)
            ->where('exist', '=', 0)
            ->orderBy('id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            for($g=(count($data)-1);$g>$i;$g--){
                if($data[$i]['customer_name']==$data[$g]['customer_name']&&$data[$i]['customer_phone']==$data[$g]['customer_phone']){
                    $data[$i]['times']+=$data[$g]['times'];
                    array_push($array,$g);
                }
            }
        }
        for($i=0;$i<count($data);$i++){
            if($array){
                for($x=0;$x<count($array);$x++){
                    if($i==$array[$x]){
                        break;
                    }
                    if($x==(count($array)-1)){
                        array_push($array1,$data[$i]);
                    }
                }
            }else{
                $array1=$data;
            }
        }
        $num=0;
        if($offset<count($array1)&&$offset<(count($array1)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($array1);
        }
        for($g=$offset;$g<$num;$g++){
            $array1[$g]['number']=$g+1;
            array_push($array2,$array1[$g]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'customers'=>$array2));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->get('/limitCustomers1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $array=array();
    $array1=array();
    $array2=array();
    $tenant_id = $app->request->headers->get("tenant-id");
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id', '=', $tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('type', '=', 3)
            ->whereNotNull('times')
//            ->where('times','!=',0)
            ->where('exist', '=', 0)
            ->orderBy('customer_id')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        for($i=0;$i<count($data);$i++){
            for($g=(count($data)-1);$g>$i;$g--){
                if($data[$i]['contact_tenant_id']==$data[$g]['contact_tenant_id']){
                    $data[$i]['times']+=$data[$g]['times'];
                    array_push($array,$g);
                }
            }
        }

        for($i=0;$i<count($data);$i++){
            if($array){
                for($x=0;$x<count($array);$x++){
                    if($i==$array[$x]){
                        break;
                    }
                    if($x==(count($array)-1)){
                        array_push($array1,$data[$i]);
                    }
                }
            }else{
                $array1=$data;
            }
        }
        $num=0;
        if($offset<count($array1)&&$offset<(count($array1)-$size)){
            $num=$offset+$size;
        }else{
            $num=count($array1);
        }
        $data= array_values(array_unset_tt($data,'contact_tenant_id'));
        for($i=$offset;$i<$num;$i++){
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('tenant_id', '=', $data[$i]['contact_tenant_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $array1[$i]['contact_tenant']=$data2;
            array_push($array2,$array1[$i]);
        }
        echo json_encode(array("result" => "0", "desc" => "success",'customers'=>$array2));
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/alterCustomer1',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $customer_id=$body->customer_id;
    $times=$body->times;
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_id!=null||$customer_id!=''){
            $updateStatement = $database->update(array('times'=>$times))
                ->table('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$customer_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少客户id"));
        }
    }else{
        echo json_encode(array("result" => "2", "desc" => "缺少租户id"));
    }
});

$app->put('/alterCustomer2',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $database = localhost();
    $tenant_id = $app->request->headers->get("tenant-id");
    $body = $app->request->getBody();
    $body = json_decode($body);
    $customer_id=$body->customer_id;
    $customer_comment=$body->customer_comment;
    if($tenant_id!=null||$tenant_id!=''){
        if($customer_id!=null||$customer_id!=''){
            $updateStatement = $database->update(array('customer_comment'=>$customer_comment))
                ->table('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$customer_id);
            $affectedRows = $updateStatement->execute();
            echo json_encode(array("result" => "0", "desc" => "success"));
        }else{
            echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
        }
    }else{
        echo json_encode(array("result" => "1", "desc" => "缺少租户id"));
    }
});

$app->put('/deleteCustomer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
//    $customer_id=$app->request->get('customer_id');
    $body = $app->request->getBody();
    $body = json_decode($body);
    $customer_id=$body->customer_id;
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            if($customer_id!=null||$customer_id!=""){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$customer_id)
                    ->where('exist',"=",0);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data!=null){
                    $updateStatement = $database->update(array('exist'=>1))
                        ->table('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$customer_id)
                        ->where('exist',"=",0);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"1","desc"=>"客户不存在"));
                }
            }else{
                echo json_encode(array("result"=>"2",'desc'=>'缺少客户id'));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});

$app->put('/recoverCustomer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
//    $customer_id=$app->request->get('customer_id');
    $body = $app->request->getBody();
    $body = json_decode($body);
    $customer_id=$body->customer_id;
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data2!=null){
            if($customer_id!=null||$customer_id!=""){
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('tenant_id','=',$tenant_id)
                    ->where('customer_id','=',$customer_id)
                    ->where('exist',"=",1);
                $stmt = $selectStatement->execute();
                $data = $stmt->fetch();
                if($data!=null){
                    $updateStatement = $database->update(array('exist'=>0))
                        ->table('customer')
                        ->where('tenant_id','=',$tenant_id)
                        ->where('customer_id','=',$customer_id)
                        ->where('exist',"=",1);
                    $affectedRows = $updateStatement->execute();
                    echo json_encode(array("result"=>"0","desc"=>"success"));
                }else{
                    echo json_encode(array("result"=>"1","desc"=>"客户不存在"));
                }
            }else{
                echo json_encode(array("result"=>"2",'desc'=>'缺少客户id'));
            }
        }else{
            echo json_encode(array("result"=>"3","desc"=>"租户不存在"));
        }
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});

$app->run();


function array_unset_tt($arr,$key){
    //建立一个目标数组
    $res = array();
    foreach ($arr as $value) {
        //查看有没有重复项

        if(isset($res[$value[$key]])){
            //有：销毁

            unset($value[$key]);

        }
        else{

            $res[$value[$key]] = $value;
        }
    }
    return $res;
}
function localhost(){
    return connect();
}
?>