<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/10
 * Time: 9:10
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
//$app->post('/tenant',function()use($app){
//    $app->response->headers->set('Content-Type','application/json');
//    $database=localhost();
//    $body=$app->request->getBody();
//    $body=json_decode($body);
//    $company=$body->company;
//    $company_type=$body->company_type;
//    $from_city_id=$body->from_city_id;
//    $receive_city_id=$body->receive_city_id;
//    $contact_id=$body->contact_id;
//    $array=array();
//    foreach($body as $key=>$value){
//        $array[$key]=$value;
//    }
//    if($company!=null||$company!=''){
//        if($company_type!=null||$company_type!=''){
//            if($from_city_id!=null||$from_city_id!=''){
//                if($receive_city_id!=null||$receive_city_id!=''){
//                        if($contact_id!=null||$contact_id!=''){
//                                    $selectStatement = $database->select()
//                                        ->from('tenant')
//                                        ->where('company','=',$company);
//                                    $stmt = $selectStatement->execute();
//                                    $data = $stmt->fetch();
//                                    if($data!=null){
//                                        echo json_encode(array("result"=>"1","desc"=>"公司已存在"));
//                                    }else{
//                                        $selectStatement = $database->select()
//                                            ->from('city')
//                                            ->where('id','=',$from_city_id);
//                                        $stmt = $selectStatement->execute();
//                                        $data1 = $stmt->fetch();
//                                        if($data1==null){
//                                            echo json_encode(array("result"=>"2","desc"=>"发货人城市不存在"));
//                                        }else{
//                                            $selectStatement = $database->select()
//                                                ->from('city')
//                                                ->where('id','=',$receive_city_id);
//                                            $stmt = $selectStatement->execute();
//                                            $data2 = $stmt->fetch();
//                                            if($data2==null){
//                                                echo json_encode(array("result"=>"3","desc"=>"收货人城市不存在"));
//                                            }else{
//                                                $selectStatement = $database->select()
//                                                    ->from('customer')
//                                                    ->where('exist','=','0')
//                                                    ->where('customer_id','=',$contact_id);
//                                                $stmt = $selectStatement->execute();
//                                                $data3 = $stmt->fetch();
//                                                if($data3==null){
//                                                    echo json_encode(array("result"=>"4","desc"=>"公司联系人不存在"));
//                                                }else{
//                                                    $selectStatement = $database->select()
//                                                        ->from('tenant');
//                                                    $stmt = $selectStatement->execute();
//                                                    $data4 = $stmt->fetchAll();
//                                                    $tenant_id=10000001+count($data4);
//                                                    $array['tenant_id']=$tenant_id;
//                                                    $array['exist']=0;
//                                                    $insertStatement = $database->insert(array_keys($array))
//                                                        ->into('tenant')
//                                                        ->values(array_values($array));
//                                                    $insertId = $insertStatement->execute(false);
//                                                    echo json_encode(array("result"=>"0","desc"=>"success"));
//                                                }
//                                            }
//                                        }
//                                    }
//                        }else{
//                            echo json_encode(array('result'=>'5','desc'=>'缺少联系人id'));
//                        }
//                }else{
//                    echo json_encode(array('result'=>'6','desc'=>'缺少收货城市id'));
//                }
//            }else{
//                echo json_encode(array('result'=>'7','desc'=>'缺少发货城市id'));
//            }
//        }else{
//            echo json_encode(array('result'=>'8','desc'=>'缺少公司类型'));
//        }
//    }else{
//        echo json_encode(array('result'=>'9','desc'=>'缺少公司名字'));
//    }
//});

$app->put('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $tenant_id=$body->tenant_id;
    $company=$body->company;
    $company_type=$body->company_type;
    $from_city_id=$body->from_city_id;
//    $receive_city_id=$body->receive_city_id;
    $contact_id=$body->contact_id;
    $array=array();
    foreach($body as $key=>$value){
        $array[$key]=$value;
    }
    if($tenant_id!=null||$tenant_id!=''){
        if($company!=null||$company!=''){
            if($company_type!=null||$company_type!=''){
                if($from_city_id!=null||$from_city_id!=''){
//                    if($receive_city_id!=null||$receive_city_id!=''){
                        if($contact_id!=null||$contact_id!=''){
                            $selectStatement = $database->select()
                                ->from('tenant')
                                ->where('tenant_id','=',$tenant_id)
                                ->where('exist','=','0');
                            $stmt = $selectStatement->execute();
                            $data = $stmt->fetch();
                            if($data!=null){
                                $selectStatement = $database->select()
                                    ->from('tenant')
                                    ->where('exist','=','0')
                                    ->where('tenant_id','=',$tenant_id)
                                    ->where('company','!=',$company);
                                $stmt = $selectStatement->execute();
                                $data1= $stmt->fetch();
                                if($data1==null){
                                    echo json_encode(array("result"=>"1","desc"=>"公司名字已存在"));
                                }else{
                                    $selectStatement = $database->select()
                                        ->from('city')
                                        ->where('id','=',$from_city_id);
                                    $stmt = $selectStatement->execute();
                                    $data2 = $stmt->fetch();
                                    if($data2==null){
                                        echo json_encode(array("result"=>"2","desc"=>"发货人城市不存在"));
                                    }else{

                                            $selectStatement = $database->select()
                                                ->from('customer')
                                                ->where('exist','=','0')
                                                ->where('customer_id','=',$contact_id);
                                            $stmt = $selectStatement->execute();
                                            $data4 = $stmt->fetch();
                                            if($data4==null){
                                                echo json_encode(array("result"=>"4","desc"=>"公司联系人不存在"));
                                            }else{
                                                $array['exist']="0";
                                                $updateStatement = $database->update($array)
                                                    ->table('tenant')
                                                    ->where('tenant_id','=',$tenant_id);
                                                $affectedRows = $updateStatement->execute();
                                                echo json_encode(array("result"=>"0","desc"=>"success"));
                                            }

                                    }
                                }
                            }else{
                                echo json_encode(array("result"=>"6","desc"=>"该公司不存在"));
                            }
                        }else{
                            echo json_encode(array('result'=>'7','desc'=>'缺少公司联系人id'));
                        }
                }else{
                    echo json_encode(array('result'=>'9','desc'=>'缺少发货城市id'));
                }
            }else{
                echo json_encode(array('result'=>'10','desc'=>'缺少公司类型'));
            }
        }else{
            echo json_encode(array('result'=>'11','desc'=>'缺少公司名字'));
        }
    }else{
        echo json_encode(array('result'=>'12','desc'=>'缺少租户公司的id'));
    }

});


$app->get('/tenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $page=$app->request->get('page');
    $page=(int)$page-1;
    $from_city_id=$app->request->get('from_city_id');
    $company=$app->request->get('company');
    $per_page=10;
    $database=localhost();
    if(($from_city_id!=null||$from_city_id!='')&&($company!=null||$company!='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->whereLike('company','%'.$company.'%')
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->whereLike('company','%'.$company.'%');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id==null||$from_city_id=='')&&($company!=null||$company!='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->whereLike('company','%'.$company.'%')
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->whereLike('company','%'.$company.'%');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id!=null||$from_city_id!='')&&($company==null||$company=='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->where('from_city_id',"=",$from_city_id);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }else if(($from_city_id==null||$from_city_id=='')&&($company==null||$company=='')){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        $num=count($data2);
        for($i=0;$i<count($data);$i++){
            $selectStatement = $database->select()
                ->from('city')
                ->where('id', '=', $data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $data[$i]['from_city']=$data1['name'];
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$data,'count'=>$num));
    }
});

$app->get('/tenant_introduction',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database=localhost();
    $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id',"=",$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    $selectStatement = $database->select()
        ->from('city')
        ->where('id',"=",$data['from_city_id']);
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetch();
    $data['ad']=$data2['name'].$data['address'];
    $selectStatement = $database->select()
        ->from('customer')
        ->where('customer_id',"=",$data['contact_id']);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"success","tenant"=>$data,'contact'=>$data1));
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

//$app->post('/tenant',function()use($app) {
//    $app->response->headers->set('Access-Control-Allow-Origin','*');
//    $app->response->headers->set('Content-Type','application/json');
//    $database = localhost();
//    $qq = $app->request->params('qq');
//    $address = $app->request->params('address');
//
//
//    $business_l = $app->request->params('business');
//    //$business_l_p = $app->request->params('business_l_p');
//    $company = $app->request->params('company');
//    $contact_name = $app->request->params('name');
//    $from_city_id = $app->request->params('city');
//    $c_introduction = $app->request->params('introduction');
//    $email = $app->request->params('email');
//    $loca = $app->request->params('location');
//    $jcompany = $app->request->params('jcompany');
//
//    //  $order_t_p = $app->request->params('order_t_p');
//
//    $sales_id = $app->request->params('sales_id');
//    $service_items = $app->request->params('service');
//    //   $trans_contract_p = $app->request->params('trans_contract_p');
//    $telephone=$app->request->params('phone');
//    $time1=time();
//    $name= $_FILES["order_file"]["name"];
//    $name1=iconv("UTF-8","gb2312", $name);
//    $name1=$time1.$name1;
//    move_uploaded_file($_FILES["order_file"]["tmp_name"], '/files/order_t_p/'.$name1);
//    $order_t_p= 'http://files.uminfo.cn:8000/order_t_p/'.$time1.$name.'';
//    $time2=time();
//    $name21=$_FILES["agreement_file"]["name"];
//    $name2=iconv("UTF-8","gb2312", $name21);
//    $name2=$time2.$name2;
//    move_uploaded_file($_FILES["agreement_file"]["tmp_name"],"/files/trans_contract_p/".$name2);
//    $trans_c_p='http://files.uminfo.cn:8000/trans_contract_p/'.$time2.$name21.'';
//
//    $time3=time();
//    $name31=$_FILES["logo_file"]["name"];
//    $name3=iconv("UTF-8","gb2312", $name31);
//    $name3=$time2.$name2;
//    move_uploaded_file($_FILES["logo_file"]["tmp_name"],"/files/tenant/".$name3);
//    $order_img='http://files.uminfo.cn:8000/tenant/'.$time3.$name31.'';
//    $time3=time();
//    $name31=$_FILES["business_file"]["name"];
//    $name3=iconv("UTF-8","gb2312", $name31);
//    $name3=$time3.$name3;
//    move_uploaded_file($_FILES["business_file"]["tmp_name"],"/files/business_l_p/".$name3);
//    $business_l_p='http://files.uminfo.cn:8000/business_l_p/'.$time3.$name31.'';
//    if($company!=null||$company!=""){
//        if($business_l!=""||$business_l!=null){
//             if($loca!=""||$loca!=null){
//                 $arr=explode(",",$loca);
//                 $longitude=$arr[0];
//                 $latitude=$arr[1];
//            if($contact_name!=null||$contact_name!=""){
//                if($telephone!=null||$telephone!=""){
//                    if($address!=""||$address!=null){
//                        if($from_city_id!=""||$from_city_id=null){
//
//                            date_default_timezone_set("PRC");
//                            $begin_time=date("Y-m-d H:i", time());
//
//                            if($sales_id!=null||$sales_id!=""){
//                                $selectStatement = $database->select()
//                                    ->from('tenant')
//                                    ->where('business_l','=',$business_l)
//                                    ->where('exist','=',0);
//                                $stmt = $selectStatement->execute();
//                                $data5 = $stmt->fetch();
//                                if($data5==null) {
//                                    $selectStatement = $database->select()
//                                        ->from('sales')
//                                        ->where('id','=',$sales_id)
//                                        ->where('exist',"=",0);
//                                    $stmt = $selectStatement->execute();
//                                    $data1 = $stmt->fetch();
//                                    if($data1!=null||$data1!=""){
//                                        $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
//                                        $str1 = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
//                                        do{
//                                            $str1.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
//                                        }while(strlen($str1)<4);
//                                        $time=base_convert(time(), 10, 32);
//                                        $num=$time.$str1;
//                                        $insertStatement = $database->insert(array('customer_id','customer_name','customer_phone','exist'
//                                        ,'customer_city_id','customer_address'))
//                                            ->into('customer')
//                                            ->values(array($num,$contact_name,$telephone,0,$from_city_id,$address));
//                                        $insertId = $insertStatement->execute(false);
//                                        if($insertId!=null||$insertId!=""){
//                                            $selectStatement = $database->select()
//                                                ->from('city')
//                                                ->where('id','=',$from_city_id);
//                                            $stmt = $selectStatement->execute();
//                                            $data01 = $stmt->fetch();
//                                            $selectStatement = $database->select()
//                                                ->from('tenant');
//                                            $stmt = $selectStatement->execute();
//                                            $data02 = $stmt->fetchAll();
//                                            $num01=0;
//                                            for($i=0;$i<count($data02);$i++){
//                                                if(substr($data02[$i]['tenant_num'],0,3)==$data01['area_code']){
//                                                    $num01++;
//                                                }
//                                            }
////                                                         $username='u'.$data01['area_code'].'0001';
//                                            $num01++;
//                                            while(strlen($num01)<4){
//                                                $num01='0'.$num01;
//                                            }
//                                            $tenant_num=$data01['area_code'].$num01;
//                                            $username='u'.$tenant_num;
//                                            $tenant_id=count($data02)+1000000501;
//                                            $ad_img1='http://files.uminfo.cn:8000/client/advertise/ad_img1.png';
//                                            $ad_img2='http://files.uminfo.cn:8000/client/advertise/ad_img2.png';
//                                            $ad_img3='http://files.uminfo.cn:8000/client/advertise/ad_img3.png';
//                                            $ad_img4='http://files.uminfo.cn:8000/client/advertise/ad_img4.png';
//                                            $ad_img5='http://files.uminfo.cn:8000/client/advertise/ad_img5.png';
//                                            $ad_img6='http://files.uminfo.cn:8000/client/advertise/ad_img6.png';
//                                            $ad_img7='http://files.uminfo.cn:8000/client/advertise/ad_img7.png';
////                                                         $order_img='http://files.uminfo.cn:8000/tenant/5230001_order.jpg';
//                                            $insertStatement = $database->insert(array('company','from_city_id','contact_id','exist','business_l','business_l_p'
//                                            ,'sales_id','address','order_t_p','trans_contract_p','service_items','c_introduction'
//                                            ,'begin_time','qq','email','insurance_balance','tenant_num','tenant_id','longitude','latitude','jcompany','ad_img1','ad_img2','ad_img3','ad_img4','ad_img5','ad_img6','ad_img7','order_img'))
//                                                ->into('tenant')
//                                                ->values(array($company,$from_city_id,$num,0,$business_l,$business_l_p
//                                                ,$sales_id,$address,$order_t_p, $trans_c_p
//                                                ,$service_items,$c_introduction,
//                                                    $begin_time,$qq,$email,0,$tenant_num,$tenant_id,$longitude,$latitude,$jcompany,$ad_img1,$ad_img2,$ad_img3,$ad_img4,$ad_img5,$ad_img6,$ad_img7,$order_img));
//                                            $insertId = $insertStatement->execute(false);
//                                            if($insertId!=""||$insertId!=null){
//                                                $selectStatement = $database->select()
//                                                    ->from('tenant')
//                                                    ->where('company','=',$company)
//                                                    ->where('business_l','=',$business_l)
//                                                    ->where('contact_id','=',$num);
//                                                $stmt = $selectStatement->execute();
//                                                $data4 = $stmt->fetch();
//                                                $array=array();
//                                                $key='tenant_id';
//                                                $array[$key]=$data4['tenant_id'];
//                                                $updateStatement = $database->update($array)
//                                                    ->table('customer')
//                                                    ->where('customer_id','=',$num);
//                                                $affectedRows = $updateStatement->execute();
//                                                $insertStatement = $database->insert(array('tenant_id','staff_id','username','password'
//                                                ,'name','telephone','position','status','permission','bg_img','head_img','exist'))
//                                                    ->into('staff')
//                                                    ->values(array($data4['tenant_id'],100001,$username,encode('888888','cxphp'),$contact_name,$telephone,'负责人',1,1111111,'http://files.uminfo.cn:8000/client/skin/bg1.jpg',"http://files.uminfo.cn:8000/staff/5230001_head.jpg",0));
//                                                $insertId = $insertStatement->execute(false);
//                                                //echo json_encode(array('result'=>'0','desc'=>'添加成功'));
//                                                $app->redirect('http://www.uminfo.cn/zhuce.html?desc=企业登记成功');
//                                            }else{
//                                                $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加租户信息失败');
////                                                                        echo json_encode(array("result"=>"1","desc"=>"添加租户信息失败"));
//                                            }
//                                        }else{
//                                            $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加负责人信息失败');
////                                                                    echo json_encode(array("result"=>"3","desc"=>"添加负责人信息失败"));
//                                        }
//                                    }else {
//                                        $app->redirect('http://www.uminfo.cn/zhuce.html?desc=该业务员不存在');
////                                                                echo json_encode(array("result"=>"4","desc"=>"该业务员不存在"));
//                                    }
//                                }else {
//                                    $app->redirect('http://www.uminfo.cn/zhuce.html?desc=该公司已存在');
//                                }
//                            }else{
//                                echo json_encode(array("result"=>"5","desc"=>"缺少sales_id"));
//                            }
//
//                        }else{
//                            echo json_encode(array("result"=>"9","desc"=>"缺少发货城市"));
//                        }
//                    }else {
//                        echo json_encode(array("result" => "10", "desc" => "缺少经营地址"));
//                    }
//                }else{
//                    echo json_encode(array("result"=>"11","desc"=>"缺少负责人电话"));
//                }
//            }else{
//                echo json_encode(array("result"=>"12","desc"=>"缺少负责人姓名"));
//            }
//             }else{
//                 echo json_encode(array("result"=>"13","desc"=>"地理坐标不能为空"));
//             }
//        }else{
//            echo json_encode(array("result"=>"14","desc"=>"缺少营业执照号码"));
//        }
//    }else{
//        echo json_encode(array("result"=>"15","desc"=>"缺少公司名称"));
//    }
//});
$app->post('/tenant',function()use($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $qq = $body->qq;
    $nature=$body->nature;
    $address=$body->address;
    $business_l=$body->business;
    $company = $body->company;
    $contact_name = $body->name;
    $from_city_id = $body->city;
    $c_introduction = $body->introduction;
    $email =$body->email;
    $loca =$body->location;
    $jcompany = $body->jcompany;
    $sales_id =  $body->sales_id;
    $service_items =  $body->service;
    $telephone= $body->phone;
     $pic1=$body->order_file;
    $order_t_p=null;
    $pic2=$body->agreement_file;
    $trans_c_p=null;
    $pic3=$body->logo_file;
    $order_img=null;
    $pic4=$body->business_file;
    $business_l_p=null;
    if($pic1!=null) {
        $base64_image_content = $pic1;
//匹配出图片的格式
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
            $type = $result[2];
            date_default_timezone_set("PRC");
            $time1 = time();
            $new_file = "/files/order_t_p/" . date('Ymd', $time1) . "/";
            if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                mkdir($new_file, 0700);
            }
            $new_file = $new_file . $time1 . ".{$type}";
            if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                $order_t_p = $file_url."order_t_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
            }
        }
    }
        if($pic2!=null) {
            $base64_image_content = $pic2;
//匹配出图片的格式
//            if (preg_match('/^(data:\s*application\/(\w+);base64,)/', $base64_image_content, $result)) {
                $type2 = "doc";
                date_default_timezone_set("PRC");
                $time1 = time();
                $new_file = "/files/trans_contract_p/" . date('Ymd', $time1) . "/";
                if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                    mkdir($new_file, 0700);
                }
                $new_file = $new_file . $time1 . ".{$type2}";
                $arr=explode(",",$base64_image_content);
                $a=$arr[0];
                if (file_put_contents($new_file, base64_decode(str_replace($a, '', $base64_image_content)))) {
                    $trans_c_p = $file_url."trans_contract_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type2}";
                }
//            }
        }
            if($pic3!=null) {
                $base64_image_content = $pic3;
//匹配出图片的格式
                if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                    $type = $result[2];
                    date_default_timezone_set("PRC");
                    $time1 = time();
                    $new_file = "/files/tenant/" . date('Ymd', $time1) . "/";
                    if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                        mkdir($new_file, 0700);
                    }
                    $new_file = $new_file . $time1 . ".{$type}";
                    if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                        $order_img = $file_url."tenant/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                    }
                }
            }else{
                $order_img=$file_url."tenant/5130001_order_logo.png";
            }
                if($pic4!=null) {
                    $base64_image_content = $pic4;
//匹配出图片的格式
                    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
                        $type = $result[2];
                        date_default_timezone_set("PRC");
                        $time1 = time();
                        $new_file = "/files/business_l_p/" . date('Ymd', $time1) . "/";
                        if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
                            mkdir($new_file, 0700);
                        }
                        $new_file = $new_file . $time1 . ".{$type}";
                        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
                            $business_l_p = $file_url."business_l_p/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
                        }
                    }
                }
    if($company!=null||$company!=""){
        if($business_l!=""||$business_l!=null){
            if($loca!=""||$loca!=null){
                $arr=explode(",",$loca);
                $longitude=$arr[0];
                $latitude=$arr[1];
                if($contact_name!=null||$contact_name!=""){
                    if($telephone!=null||$telephone!=""){
                        if($address!=""||$address!=null){
                            if($from_city_id!=""||$from_city_id=null){
                                date_default_timezone_set("PRC");
                                $begin_time=date("Y-m-d H:i", time());

                                if($sales_id!=null||$sales_id!=""){
                                    $selectStatement = $database->select()
                                        ->from('tenant')
                                        ->where('company','=',$company)
                                        ->where('exist','=',0);
                                    $stmt = $selectStatement->execute();
                                    $data5 = $stmt->fetch();
                                    if($data5==null) {
                                        $selectStatement = $database->select()
                                            ->from('sales')
                                            ->where('id','=',$sales_id)
                                            ->where('exist',"=",0);
                                        $stmt = $selectStatement->execute();
                                        $data1 = $stmt->fetch();
                                        if($data1!=null||$data1!=""){
                                            $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
                                            $str1 = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            do{
                                                $str1.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
                                            }while(strlen($str1)<4);
                                            $time=base_convert(time(), 10, 32);
                                            $num=$time.$str1;
                                            $insertStatement = $database->insert(array('customer_id','customer_name','customer_phone','exist'
                                            ,'customer_city_id','customer_address'))
                                                ->into('customer')
                                                ->values(array($num,$contact_name,$telephone,0,$from_city_id,$address));
                                            $insertId = $insertStatement->execute(false);
                                            if($insertId!=null||$insertId!=""){
                                                $selectStatement = $database->select()
                                                    ->from('city')
                                                    ->where('id','=',$from_city_id);
                                                $stmt = $selectStatement->execute();
                                                $data01 = $stmt->fetch();
                                                $selectStatement = $database->select()
                                                    ->from('tenant')
                                                    ->whereNotLike('tenant_id','9'.'%');
                                                $stmt = $selectStatement->execute();
                                                $data02 = $stmt->fetchAll();
                                                $num01=0;
                                                for($i=0;$i<count($data02);$i++){
                                                    if(substr($data02[$i]['tenant_num'],0,3)==$data01['area_code']){
                                                        $num01++;
                                                    }
                                                }
//                                                         $username='u'.$data01['area_code'].'0001';
                                                $num01++;
                                                while(strlen($num01)<4){
                                                    $num01='0'.$num01;
                                                }
                                                $tenant_num=$data01['area_code'].$num01;
                                                $username='u'.$tenant_num;
                                                $tenant_id=count($data02)+1000000501;
                                                $ad_img1=$file_url.'client/advertise/ad_img1.png';
                                                $ad_img2=$file_url.'client/advertise/ad_img2.png';
                                                $ad_img3=$file_url.'client/advertise/ad_img3.png';
                                                $ad_img4=$file_url.'client/advertise/ad_img4.png';
                                                $ad_img5=$file_url.'client/advertise/ad_img5.png';
                                                $ad_img6=$file_url.'client/advertise/ad_img6.png';
                                                $ad_img7=$file_url.'client/advertise/ad_img7.png';
//                                                         $order_img='http://files.uminfo.cn:8000/tenant/5230001_order.jpg';
                                                $insertStatement = $database->insert(array('company','from_city_id','contact_id','exist','business_l','business_l_p'
                                                ,'sales_id','address','order_t_p','trans_contract_p','service_items','c_introduction'
                                                ,'begin_time','qq','email','tenant_num','tenant_id','longitude','latitude','jcompany','ad_img1',
                                                    'ad_img2','ad_img3','ad_img4','ad_img5','ad_img6','ad_img7','order_img','nature'))
                                                    ->into('tenant')
                                                    ->values(array($company,$from_city_id,$num,0,$business_l,$business_l_p
                                                    ,$sales_id,$address,$order_t_p, $trans_c_p
                                                    ,$service_items,$c_introduction,
                                                        $begin_time,$qq,$email,$tenant_num,$tenant_id,$longitude,$latitude,$jcompany,$ad_img1
                                                    ,$ad_img2,$ad_img3,$ad_img4,$ad_img5,$ad_img6,$ad_img7,$order_img,$nature));
                                                $insertId = $insertStatement->execute(false);
                                                if($insertId!=""||$insertId!=null){
                                                    $selectStatement = $database->select()
                                                        ->from('tenant')
                                                        ->where('company','=',$company)
                                                        ->where('business_l','=',$business_l)
                                                        ->where('contact_id','=',$num);
                                                    $stmt = $selectStatement->execute();
                                                    $data4 = $stmt->fetch();
                                                    $array=array();
                                                    $key='tenant_id';
                                                    $array[$key]=$data4['tenant_id'];
                                                    $updateStatement = $database->update($array)
                                                        ->table('customer')
                                                        ->where('customer_id','=',$num);
                                                    $affectedRows = $updateStatement->execute();
                                                    $insertStatement = $database->insert(array('tenant_id','staff_id','username','password'
                                                    ,'name','telephone','position','status','permission','bg_img','head_img','exist'))
                                                        ->into('staff')
                                                        ->values(array($data4['tenant_id'],100001,$username,encode('888888','cxphp'),$contact_name,$telephone,'负责人',1,11111111,$file_url.'client/skin/bg1.jpg',$file_url."staff/5230001_head.jpg",0));
                                                    $insertId = $insertStatement->execute(false);
                                                    echo json_encode(array('result'=>'0','desc'=>'添加成功'));
//                                                    $app->redirect('http://www.uminfo.cn/zhuce.html?desc=企业登记成功');
                                                }else{
//                                                    $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加租户信息失败');
                                                                        echo json_encode(array("result"=>"1","desc"=>"添加租户信息失败"));
                                                }
                                            }else{
//                                                $app->redirect('http://www.uminfo.cn/zhuce.html?desc=添加负责人信息失败');
                                                                    echo json_encode(array("result"=>"2","desc"=>"添加负责人信息失败"));
                                            }
                                        }else {
//                                            $app->redirect('http://www.uminfo.cn/zhuce.html?desc=该业务员不存在');
                                                                echo json_encode(array("result"=>"3","desc"=>"该业务员不存在"));
                                        }
                                    }else {
//
                                        echo json_encode(array("result"=>"4","desc"=>"该公司名已存在"));
                                    }
                                }else{
                                    echo json_encode(array("result"=>"5","desc"=>"缺少sales_id"));
                                }
                            }else{
                                echo json_encode(array("result"=>"9","desc"=>"缺少发货城市"));
                            }
                        }else {
                            echo json_encode(array("result" => "10", "desc" => "缺少经营地址"));
                        }
                    }else{
                        echo json_encode(array("result"=>"11","desc"=>"缺少负责人电话"));
                    }
                }else{
                    echo json_encode(array("result"=>"12","desc"=>"缺少负责人姓名"));
                }
            }else{
                echo json_encode(array("result"=>"13","desc"=>"地理坐标不能为空"));
            }
        }else{
            echo json_encode(array("result"=>"14","desc"=>"缺少营业执照号码"));
        }
    }else{
        echo json_encode(array("result"=>"15","desc"=>"缺少公司名称"));
    }
});

$app->get('/tenant_customer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $page=$app->request->get('page');
    $per_page=$app->request->get("per_page");
    $database=localhost();
    if($page==null||$per_page==null){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $num=count($data);
        $array=array();
        for($i=0;$i<$num;$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id','=',$data[$i]['contact_id'])
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['from_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data[$i]['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data1['customer_city']=$data2['name'];
            $data[$i]['from_city']=$data3['name'];
            $data[$i]['receive_city']=$data4['name'];
            $array1=array();
            $array1['customer']=$data1;
            $array1['tenant']=$data[$i];
            array_push($array,$array1);
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$array));
    }else{
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('exist',"=",0)
            ->limit((int)$per_page,(int)$per_page*(int)$page);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        $num=count($data);
        $array=array();
        for($i=0;$i<$num;$i++){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('customer_id','=',$data[$i]['contact_id'])
                ->where('tenant_id','=',$data[$i]['tenant_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data1 = $stmt->fetchAll();
            $array1=array();
            $array1['customer']=$data1;
            $array1['tenant']=$data[$i];
            array_push($array,$array1);
        }
        echo  json_encode(array("result"=>"0","desc"=>"success","tenants"=>$array));
    }
});

$app->get('/one_tenant_customer',function()use($app){
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->get('tenant_id');
    $database=localhost();
    $array=array();
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id)
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        if($data1!=null){
            $selectStatement = $database->select()
                ->from('customer')
                ->where('tenant_id','=',$tenant_id)
                ->where('customer_id','=',$data1['contact_id'])
                ->where('exist',"=",0);
            $stmt = $selectStatement->execute();
            $data2 = $stmt->fetch();
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data2['customer_city_id']);
            $stmt = $selectStatement->execute();
            $data3 = $stmt->fetch();
            $data2['customer_city']=$data3['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['from_city_id']);
            $stmt = $selectStatement->execute();
            $data4 = $stmt->fetch();
            $data1['from_city']=$data4['name'];
            $selectStatement = $database->select()
                ->from('city')
                ->where('id','=',$data1['receive_city_id']);
            $stmt = $selectStatement->execute();
            $data5 = $stmt->fetch();
            $data1['receive_city']=$data5['name'];
            $array['tenant']=$data1;
            $array['customer']=$data2;
            echo  json_encode(array("result"=>"1","desc"=>"success","tenant"=>$array));
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"租户公司不存在","tenant"=>''));
        }
    }else{
        echo  json_encode(array("result"=>"3","desc"=>"租户公司id为空","tenant"=>''));
    }
});

$app->get('/company_name',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $tenant_id=$app->request->get('company');
    if($tenant_id!=null||$tenant_id!=''){
        $selectStatement = $database->select()
            ->from('tenant')
            ->whereLike('tenant_id','%'.$tenant_id.'%')
            ->where('exist',"=",0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        echo  json_encode(array("result"=>"0","desc"=>"success","company_name"=>$data['company']));
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"租户公司不存在"));
    }
});

$app->delete('/emptyStaff',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('staff')
        ->where('tenant_id', '=', $tenant_id)
        ->where('staff_id', '!=', 100001);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyCustomer',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('customer')
        ->where('tenant_id','=',$tenant_id)
        ->orderBy('id');
    $stmt = $selectStatement->execute();
    $data2 = $stmt->fetchAll();
    if($data2[2]!=null){
        $deleteStatement = $database->delete()
            ->from('customer')
            ->where('id','>',$data2[2]['id'])
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
    }
    $updateStatement = $database->update(array('type'=>1,'times'=>1))
        ->table('customer')
        ->where('tenant_id', '=', $tenant_id)
        ->where('customer_id','=',10000000001);
    $affectedRows = $updateStatement->execute();
    $updateStatement = $database->update(array('type'=>0,'times'=>0))
        ->table('customer')
        ->where('tenant_id', '=', $tenant_id)
        ->where('customer_id','=',10000000002);
    $affectedRows = $updateStatement->execute();

    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('orders')
        ->where('tenant_id','=',$tenant_id)
        ->orderBy('id');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $deleteStatement = $database->delete()
            ->from('orders')
            ->where('id','>',$data[0]['id'])
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $updateStatement = $database->update(array('sender_id'=>'10000000001','receiver_id'=>'10000000002','order_status'=>1,'order_datetime0'=>'2018-01-01 12:12:12','order_datetime1'=>'2018-01-01 12:12:12','order_datetime2'=>null,'order_datetime3'=>null,'order_datetime4'=>null,'order_datetime5'=>null,'inventory_loc_id'=>null,'inventory_type'=>0,'is_schedule'=>0,'is_transfer'=>0,'order_comment'=>null,'exception_id'=>null,'reach_city'=>null,'pickup_id'=>null,'transfer_cost'=>null,'sure_img'=>null,'is_back'=>0))
            ->table('orders')
            ->where('tenant_id', '=', $tenant_id)
            ->where('id','=',$data[0]['id']);
        $affectedRows = $updateStatement->execute();
    }
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyGoods',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $selectStatement = $database->select()
        ->from('goods')
        ->where('tenant_id','=',$tenant_id)
        ->orderBy('id');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    if($data!=null){
        $deleteStatement = $database->delete()
            ->from('goods')
            ->where('id','>',$data[0]['id'])
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $updateStatement = $database->update(array('goods_name'=>'电脑设备','goods_weight'=>16,'goods_capacity'=>32,'goods_package_id'=>1,'goods_count'=>68,'goods_value'=>24))
            ->table('goods')
            ->where('tenant_id', '=', $tenant_id)
            ->where('id','=',$data[0]['id']);
        $affectedRows = $updateStatement->execute();
    }
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyAgreement',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('agreement')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyAgreementSchedule',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('agreement_schedule')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyException',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('exception')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyInsurance',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('insurance')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyInsuranceScheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('insurance_scheduling')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyInventoryLoc',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('inventory_loc')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyScheduling',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('scheduling')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    $selectStatement = $database->select()
        ->from('scheduling')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetchAll();
    for($i=0;$i<count($data1);$i++){
        $deleteStatement = $database->delete()
            ->from('map')
            ->where('scheduling_id', '=', $data1[$i]['scheduling_id']);
        $affectedRows = $deleteStatement->execute();
    }
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyScheduleOrder',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $deleteStatement = $database->delete()
        ->from('schedule_order')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyLorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $updateStatement = $database->update(array('exist'=>'0'))
        ->table('lorry')
        ->where('tenant_id', '=', $tenant_id)
        ->where('lorry_id','=','100000001');
    $affectedRows = $updateStatement->execute();
    $deleteStatement = $database->delete()
        ->from('lorry')
        ->where('lorry_id','!=','100000001')
        ->where('tenant_id', '=', $tenant_id);
    $affectedRows = $deleteStatement->execute();
    echo  json_encode(array("result"=>"0","desc"=>"success"));
});

$app->delete('/emptyTenant',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $deleteStatement = $database->delete()
            ->from('staff')
            ->where('tenant_id', '=', $tenant_id)
            ->where('staff_id', '!=', 100001);
        $affectedRows = $deleteStatement->execute();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id');
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetchAll();
        if($data2[2]!=null){
            $deleteStatement = $database->delete()
                ->from('customer')
                ->where('id','>',$data2[2]['id'])
                ->where('tenant_id', '=', $tenant_id);
            $affectedRows = $deleteStatement->execute();
        }
        $updateStatement = $database->update(array('type'=>1,'times'=>1))
            ->table('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('customer_id','=',10000000001);
        $affectedRows = $updateStatement->execute();
        $updateStatement = $database->update(array('type'=>0,'times'=>0))
            ->table('customer')
            ->where('tenant_id', '=', $tenant_id)
            ->where('customer_id','=',10000000002);
        $affectedRows = $updateStatement->execute();
        $selectStatement = $database->select()
            ->from('orders')
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            $deleteStatement = $database->delete()
                ->from('orders')
                ->where('id','>',$data[0]['id'])
                ->where('tenant_id', '=', $tenant_id);
            $affectedRows = $deleteStatement->execute();
            $updateStatement = $database->update(array('sender_id'=>'10000000001','receiver_id'=>'10000000002','order_status'=>1,'order_datetime0'=>'2018-01-01 12:12:12','order_datetime1'=>'2018-01-01 12:12:12','order_datetime2'=>null,'order_datetime3'=>null,'order_datetime4'=>null,'order_datetime5'=>null,'inventory_loc_id'=>null,'inventory_type'=>0,'is_schedule'=>0,'is_transfer'=>0,'order_comment'=>null,'exception_id'=>null,'reach_city'=>null,'pickup_id'=>null,'transfer_cost'=>null,'sure_img'=>null,'is_back'=>0))
                ->table('orders')
                ->where('tenant_id', '=', $tenant_id)
                ->where('id','=',$data[0]['id']);
            $affectedRows = $updateStatement->execute();
        }
        $selectStatement = $database->select()
            ->from('goods')
            ->where('tenant_id','=',$tenant_id)
            ->orderBy('id');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            $deleteStatement = $database->delete()
                ->from('goods')
                ->where('id','>',$data[0]['id'])
                ->where('tenant_id', '=', $tenant_id);
            $affectedRows = $deleteStatement->execute();
            $updateStatement = $database->update(array('goods_name'=>'电脑设备','goods_weight'=>16,'goods_capacity'=>32,'goods_package_id'=>1,'goods_count'=>68,'goods_value'=>24))
                ->table('goods')
                ->where('tenant_id', '=', $tenant_id)
                ->where('id','=',$data[0]['id']);
            $affectedRows = $updateStatement->execute();
        }
        $deleteStatement = $database->delete()
            ->from('agreement')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('agreement_schedule')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('exception')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('insurance')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('insurance_scheduling')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('inventory_loc')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();

//        $deleteStatement = $database->delete()
//            ->from('pickup')
//            ->where('tenant_id', '=', $tenant_id);
//        $affectedRows = $deleteStatement->execute();
        $deleteStatement = $database->delete()
            ->from('scheduling')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $selectStatement = $database->select()
            ->from('scheduling')
            ->where('tenant_id','=',$tenant_id);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        for($i=0;$i<count($data1);$i++){
            $deleteStatement = $database->delete()
                ->from('map')
                ->where('scheduling_id', '=', $data1[$i]['scheduling_id']);
            $affectedRows = $deleteStatement->execute();
        }
        $deleteStatement = $database->delete()
            ->from('schedule_order')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        $updateStatement = $database->update(array('exist'=>'0'))
            ->table('lorry')
            ->where('tenant_id', '=', $tenant_id)
            ->where('lorry_id','=','100000001');
        $affectedRows = $updateStatement->execute();
        $deleteStatement = $database->delete()
            ->from('lorry')
            ->where('lorry_id','!=','100000001')
            ->where('tenant_id', '=', $tenant_id);
        $affectedRows = $deleteStatement->execute();
        echo json_encode(array("result"=>"0",'desc'=>'success'));
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});

$app->get('/getTenants1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $city_id=$app->request->get('city_id');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    if($city_id!=null||$city_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('from_city_id','=',$city_id)
            ->where('tenant_id','!=',$tenant_id)
            ->where('exist','=','0');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        if($data!=null){
//         for($x=0;$x<count($data);$x++){
//             $selectStatement = $database->select()
//                 ->from('city')
//                 ->where('id','=',$data[$x]['from_city_id']);
//             $stmt = $selectStatement->execute();
//             $data2 = $stmt->fetch();
//             $data[$x]['from_city_name']=$data2['name'];
//             $selectStatement = $database->select()
//                 ->from('customer')
//                 ->where('customer_id','=',$data[$x]['contact_id'])
//                 ->where('tenant_id','=',$data[$x]['tenant_id']);
//             $stmt = $selectStatement->execute();
//             $data3 = $stmt->fetch();
//             $selectStatement = $database->select()
//                 ->from('city')
//                 ->where('id','=',$data3['customer_city_id']);
//             $stmt = $selectStatement->execute();
//             $data4 = $stmt->fetch();
//             $data3['customer_city_name']=$data4['name'];
//             $data[$x]['customer']=$data3;
//         }
//        }
        echo json_encode(array("result"=>"0",'desc'=>'','count'=>count($data)));
    }else{
        echo json_encode(array("result"=>"1",'desc'=>'缺少城市id'));
    }
});

$app->get('/limitTenants1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $city_id=$app->request->get('city_id');
    $tenant_id=$app->request->headers->get('tenant-id');
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    if($city_id!=null||$city_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('from_city_id','=',$city_id)
            ->where('tenant_id','!=',$tenant_id)
            ->where('exist','=','0')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['from_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['from_city_name']=$data2['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['contact_id'])
                    ->where('tenant_id','=',$data[$x]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data3['customer_city_name']=$data4['name'];
                $data[$x]['customer']=$data3;
            }
        }
        echo json_encode(array("result"=>"0",'desc'=>'','tenants'=>$data));
    }else{
        echo json_encode(array("result"=>"1",'desc'=>'缺少城市id'));
    }
});

$app->get('/getTenants2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->get('tenant_id');
    $tenant=$app->request->headers->get('tenant-id');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id)
            ->where('tenant_id','!=',$tenant)
            ->where('exist','=','0');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        if($data!=null){
//            for($x=0;$x<count($data);$x++){
//                $selectStatement = $database->select()
//                    ->from('city')
//                    ->where('id','=',$data[$x]['from_city_id']);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetch();
//                $data[$x]['from_city_name']=$data2['name'];
//                $selectStatement = $database->select()
//                    ->from('customer')
//                    ->where('customer_id','=',$data[$x]['contact_id'])
//                    ->where('tenant_id','=',$data[$x]['tenant_id']);
//                $stmt = $selectStatement->execute();
//                $data3 = $stmt->fetch();
//                $selectStatement = $database->select()
//                    ->from('city')
//                    ->where('id','=',$data3['customer_city_id']);
//                $stmt = $selectStatement->execute();
//                $data4 = $stmt->fetch();
//                $data3['customer_city_name']=$data4['name'];
//                $data[$x]['customer']=$data3;
//            }
//        }
        echo json_encode(array("result"=>"0",'desc'=>'','count'=>count($data)));
    }else{
        echo json_encode(array("result"=>"1",'desc'=>'缺少租户id'));
    }
});

$app->get('/limitTenants2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->get('tenant_id');
    $tenant=$app->request->headers->get('tenant-id');
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$tenant_id)
            ->where('tenant_id','!=',$tenant)
            ->where('exist','=','0')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['from_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['from_city_name']=$data2['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['contact_id'])
                    ->where('tenant_id','=',$data[$x]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data3['customer_city_name']=$data4['name'];
                $data[$x]['customer']=$data3;
            }
        }
        echo json_encode(array("result"=>"0",'desc'=>'','tenants'=>$data));
    }else{
        echo json_encode(array("result"=>"1",'desc'=>'缺少租户id'));
    }
});

$app->get('/getTenants3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $company=$app->request->get('company');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    if($company!=null||$company!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('jcompany','=',$company)
            ->where('tenant_id','!=',$tenant_id)
            ->where('exist','=','0');
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        if($data!=null){
//            for($x=0;$x<count($data);$x++){
//                $selectStatement = $database->select()
//                    ->from('city')
//                    ->where('id','=',$data[$x]['from_city_id']);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetch();
//                $data[$x]['from_city_name']=$data2['name'];
//                $selectStatement = $database->select()
//                    ->from('customer')
//                    ->where('customer_id','=',$data[$x]['contact_id'])
//                    ->where('tenant_id','=',$data[$x]['tenant_id']);
//                $stmt = $selectStatement->execute();
//                $data3 = $stmt->fetch();
//                $selectStatement = $database->select()
//                    ->from('city')
//                    ->where('id','=',$data3['customer_city_id']);
//                $stmt = $selectStatement->execute();
//                $data4 = $stmt->fetch();
//                $data3['customer_city_name']=$data4['name'];
//                $data[$x]['customer']=$data3;
//            }
//        }else{
//            $selectStatement = $database->select()
//                ->from('tenant')
//                ->where('company','=',$company)
//                ->where('tenant_id','!=',$tenant_id)
//                ->where('exist','=','0');
//            $stmt = $selectStatement->execute();
//            $data = $stmt->fetchAll();
//            if($data!=null){
//                for($x=0;$x<count($data);$x++){
//                    $selectStatement = $database->select()
//                        ->from('city')
//                        ->where('id','=',$data[$x]['from_city_id']);
//                    $stmt = $selectStatement->execute();
//                    $data2 = $stmt->fetch();
//                    $data[$x]['from_city_name']=$data2['name'];
//                    $selectStatement = $database->select()
//                        ->from('customer')
//                        ->where('customer_id','=',$data[$x]['contact_id'])
//                        ->where('tenant_id','=',$data[$x]['tenant_id']);
//                    $stmt = $selectStatement->execute();
//                    $data3 = $stmt->fetch();
//                    $selectStatement = $database->select()
//                        ->from('city')
//                        ->where('id','=',$data3['customer_city_id']);
//                    $stmt = $selectStatement->execute();
//                    $data4 = $stmt->fetch();
//                    $data3['customer_city_name']=$data4['name'];
//                    $data[$x]['customer']=$data3;
//                }
//            }
//        }
        echo json_encode(array("result"=>"0",'desc'=>'','count'=>count($data)));
    }else{
        echo json_encode(array("result"=>"1",'desc'=>'缺少公司名称'));
    }
});

$app->get('/limitTenants3',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $company=$app->request->get('company');
    $tenant_id=$app->request->headers->get('tenant-id');
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    if($company!=null||$company!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('jcompany','=',$company)
            ->where('tenant_id','!=',$tenant_id)
            ->where('exist','=','0')
            ->limit((int)$size,(int)$offset);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['from_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['from_city_name']=$data2['name'];
                $selectStatement = $database->select()
                    ->from('customer')
                    ->where('customer_id','=',$data[$x]['contact_id'])
                    ->where('tenant_id','=',$data[$x]['tenant_id']);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetch();
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data3['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data4 = $stmt->fetch();
                $data3['customer_city_name']=$data4['name'];
                $data[$x]['customer']=$data3;
            }
        }else{
            $selectStatement = $database->select()
                ->from('tenant')
                ->where('company','=',$company)
                ->where('tenant_id','!=',$tenant_id)
                ->where('exist','=','0')
                ->limit((int)$size,(int)$offset);;
            $stmt = $selectStatement->execute();
            $data = $stmt->fetchAll();
            if($data!=null){
                for($x=0;$x<count($data);$x++){
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data[$x]['from_city_id']);
                    $stmt = $selectStatement->execute();
                    $data2 = $stmt->fetch();
                    $data[$x]['from_city_name']=$data2['name'];
                    $selectStatement = $database->select()
                        ->from('customer')
                        ->where('customer_id','=',$data[$x]['contact_id'])
                        ->where('tenant_id','=',$data[$x]['tenant_id']);
                    $stmt = $selectStatement->execute();
                    $data3 = $stmt->fetch();
                    $selectStatement = $database->select()
                        ->from('city')
                        ->where('id','=',$data3['customer_city_id']);
                    $stmt = $selectStatement->execute();
                    $data4 = $stmt->fetch();
                    $data3['customer_city_name']=$data4['name'];
                    $data[$x]['customer']=$data3;
                }
            }
        }
        echo json_encode(array("result"=>"0",'desc'=>'','tenants'=>$data));
        }else{
            echo json_encode(array("result"=>"1",'desc'=>'缺少公司名称'));
        }
});

$app->get('/getTenants4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $customer_name=$app->request->get('customer_name');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $arrays1=array();
    if($customer_name!=null||$customer_name!=""){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id','!=',$tenant_id)
            ->where('customer_name','=',$customer_name);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
//        if($data!=null){
//            for($x=0;$x<count($data);$x++){
//                $selectStatement = $database->select()
//                    ->from('city')
//                    ->where('id','=',$data[$x]['customer_city_id']);
//                $stmt = $selectStatement->execute();
//                $data2 = $stmt->fetch();
//                $data[$x]['customer_city_name']=$data2['name'];
//                $selectStatement = $database->select()
//                    ->from('tenant')
//                    ->where('contact_id','=',$data[$x]['customer_id'])
//                    ->where('tenant_id','=',$data[$x]['tenant_id'])
//                    ->where('exist','=',0);
//                $stmt = $selectStatement->execute();
//                $data3 = $stmt->fetchAll();
//                if($data3!=null) {
//                    for($j=0;$j<count($data3);$j++) {
//                        $selectStatement = $database->select()
//                            ->from('city')
//                            ->where('id', '=', $data3[$j]['from_city_id']);
//                        $stmt = $selectStatement->execute();
//                        $data4 = $stmt->fetch();
//                        $data3[$j]['from_city_name'] = $data4['name'];
//                        $data3[$j]['customer'] = $data[$x];
//                        array_push($arrays1,$data3[$j]);
//                    }
//                }
//            }
//        }
        echo json_encode(array("result"=>"0",'desc'=>'','count'=>count($arrays1)));
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少客户名称'));
    }
});

$app->get('/limitTenants4',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $customer_name=$app->request->get('customer_name');
    $tenant_id=$app->request->headers->get('tenant-id');
    $size=$app->request->get('size');
    $offset=$app->request->get('offset');
    $database=localhost();
    $arrays1=array();
    if($customer_name!=null||$customer_name!=""){
        $selectStatement = $database->select()
            ->from('customer')
            ->where('tenant_id','!=',$tenant_id)
            ->where('customer_name','=',$customer_name);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetchAll();
        if($data!=null){
            for($x=0;$x<count($data);$x++){
                $selectStatement = $database->select()
                    ->from('city')
                    ->where('id','=',$data[$x]['customer_city_id']);
                $stmt = $selectStatement->execute();
                $data2 = $stmt->fetch();
                $data[$x]['customer_city_name']=$data2['name'];
                $selectStatement = $database->select()
                    ->from('tenant')
                    ->where('contact_id','=',$data[$x]['customer_id'])
                    ->where('tenant_id','=',$data[$x]['tenant_id'])
                    ->where('exist','=',0)
                    ->limit((int)$size,(int)$offset);
                $stmt = $selectStatement->execute();
                $data3 = $stmt->fetchAll();
                if($data3!=null) {
                    for($j=0;$j<count($data3);$j++) {
                        $selectStatement = $database->select()
                            ->from('city')
                            ->where('id', '=', $data3[$j]['from_city_id']);
                        $stmt = $selectStatement->execute();
                        $data4 = $stmt->fetch();
                        $data3[$j]['from_city_name'] = $data4['name'];
                        $data3[$j]['customer'] = $data[$x];
                        array_push($arrays1,$data3[$j]);
                    }
                }
            }
        }
        echo json_encode(array("result"=>"0",'desc'=>'','tenants'=>$arrays1));
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少客户名称'));
    }
});

$app->get('/getTenant5',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $business_l=$app->request->get('business_l');
    $database=localhost();
    if($business_l!=null||$business_l!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('business_l','=',$business_l)
            ->where('nature','=',0)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        echo json_encode(array("result"=>"0",'desc'=>'','tenant'=>$data));
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少营业执照编号'));
    }
});

$app->get('/getPC',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type', 'application/json');
    $tenant_id=$app->request->headers->get('tenant-id');
    $database=localhost();
    $array1=array();
    if($tenant_id!=null||$tenant_id!=""){
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id',"=",$tenant_id)
            ->where('exist','=',0);
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('customer')
            ->where('customer_id',"=",$data['contact_id']);
        $stmt = $selectStatement->execute();
        $data3 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('id',"=",$data['from_city_id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        $selectStatement = $database->select()
            ->from('city')
            ->where('pid',"=",$data2['pid'])
            ->where('id',"<",$data["from_city_id"]);
        $stmt = $selectStatement->execute();
        $data4 = $stmt->fetchAll();
        echo json_encode(array("result"=>"0",'desc'=>'','province_id'=>$data2['pid'],"city_id"=>$data['from_city_id'],"number"=>count($data4)+1,"customer_name"=>$data3['customer_name'],
            'customer_address'=>$data3['customer_address'],'customer_phone'=>$data3['customer_phone'],'company'=>$data['company'],'tenant_num'=>$data['tenant_num']));
    }else{
        echo json_encode(array("result"=>"4",'desc'=>'缺少租户id'));
    }
});




$app->run();

function file_url(){
    return files_url();
}
function localhost(){
    return connect();
}
//加密
function encode($string , $skey ) {
    $strArr = str_split(base64_encode($string));
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key < $strCount && $strArr[$key].=$value;
    return str_replace(array('=', '+', '/'), array('O0O0O', 'o000o', 'oo00o'), join('', $strArr));
}

?>