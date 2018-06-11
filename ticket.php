<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/10
 * Time: 13:10
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';

\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();

$app->get('/gettickets',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->leftJoin('ticket_lorry','ticket_lorry.company_id','=','ticket.id');
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    for($i=0;$i<count($data);$i++){
        $selectStatement = $database->select()
            ->from('ticket')
            ->where('company','=',$data[$i]['company']);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetch();
        $data[$i]['id']=$data1['id'];
        if($data[$i]['passwd']){
            $data[$i]['passwd_decode']=decode($data[$i]['passwd'], 'cxphp');
        }else{
            $data[$i]['passwd_decode']='';
        }
    }
        echo  json_encode(array("result"=>"1","desc"=>"",'ticket'=>$data));
});

$app->get('/getticket',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    if($data['passwd']){
        $data['passwd_decode']=decode($data['passwd'], 'cxphp');
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket'=>$data));
});

$app->get('/getticket_lorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $app_lorry_id=$app->request->get('app_lorry_id');
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket_lorry')
        ->where('lorry_id','=',$app_lorry_id)
        ->where('company_id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket'=>$data));
});

$app->post('/addticketlorry',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $database=localhost();
    $file_url=file_url();
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $app_lorry_id=$body->app_lorry_id;
    $pic=$body->pic;
    $base64_image_content = $pic;
//匹配出图片的格式
    if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)) {
        $type = $result[2];
        date_default_timezone_set("PRC");
        $time1 = time();
        $new_file = "/files/ticket_sign/" . date('Ymd', $time1) . "/";
        if (!file_exists($new_file)) {
//检查是否有该文件夹，如果没有就创建，并给予最高权限
            mkdir($new_file, 0700);
        }
        $new_file = $new_file . $time1 . ".{$type}";
        if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))) {
            $lujing1 = $file_url."ticket_sign/" . date('Ymd', $time1) . "/" . $time1 . ".{$type}";
        }
    }
    $arrays['sign_img']=$lujing1;
    if($id!=null||$id!=""){
        if($app_lorry_id!=null||$app_lorry_id!=""){
            $selectStament=$database->select()
                ->from('app_lorry')
                ->where('app_lorry_id','=',$app_lorry_id);
            $stmt=$selectStament->execute();
            $data1=$stmt->fetch();
            if($data1!=null){
                $arrays['company_id']=$id;
                $arrays['lorry_id']=$app_lorry_id;
                date_default_timezone_set("PRC");
                $shijian=date("Y-m-d H:i:s",time());
                $selectStament=$database->select()
                    ->from('ticket_lorry')
                    ->where('company_id','=',$id)
                    ->where('lorry_id','=',$app_lorry_id);
                $stmt=$selectStament->execute();
                $data2=$stmt->fetch();
                if($data2==null){
                    $insertStatement = $database->insert(array('company_id','lorry_id','sign_img','commit_time'))
                        ->into('ticket_lorry')
                        ->values(array($id,$app_lorry_id,$arrays['sign_img'],$shijian));
                    $insertId = $insertStatement->execute(false);
                }
                echo  json_encode(array("result"=>"0","desc"=>"添加成功"));
            }else{
                echo  json_encode(array("result"=>"3","desc"=>"您未填写电话和密码"));
            }
        }else{
            echo  json_encode(array("result"=>"2","desc"=>"您未填写身份证号"));
        }
    }else{
        echo  json_encode(array("result"=>"1","desc"=>"您未填写姓名"));
    }
});

$app->get('/getTickets',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id = $app->request->headers->get("tenant-id");
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('exist','=',0);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    for($x=0;$x<count($data);$x++){
        $selectStatement = $database->select()
            ->from('ticket_tenant')
            ->where('tenant_id','=',$tenant_id)
            ->where('company_id','=',$data[$x]['id']);
        $stmt = $selectStatement->execute();
        $data2 = $stmt->fetch();
        if($data[$x]['passwd']){
            $data[$x]['passwd_decode']=decode($data[$x]['passwd'], 'cxphp');
        }else{
            $data[$x]['passwd_decode']='';
        }

    if($data2!=null){
      $data[$x]['is_league']=1;
        $data[$x]['tenant']=$data2;
    }else{
        $data[$x]['is_league']=0;
        $data[$x]['tenant']=null;
    }
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'tickets'=>$data));
});



$app->get('/getTicket',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id = $app->request->get("id");
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    if($data['passwd']){
        $data['passwd_decode']=decode($data['passwd'], 'cxphp');
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket'=>$data));
});

$app->get('/getTicket1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id = $app->request->get("id");
    $mac = $app->request->get("mac");
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('mac','=',$mac)
        ->where('id','=',$id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    if($data['passwd']){
        $data['passwd_decode']=decode($data['passwd'], 'cxphp');
    }
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket'=>$data));
});

$app->get('/getTicket2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $business = $app->request->get("business");
    $passwd = $app->request->get("passwd");
    $database = localhost();
    $selectStatement = $database->select()
        ->from('ticket')
        ->where('business','=',$business)
        ->where('passwd','=',encode($passwd, 'cxphp') );
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo  json_encode(array("result"=>"0","desc"=>"",'ticket'=>$data));
});

$app->put('/alterTicket0',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $id=$app->request->get('id');
    $database=localhost();
    if($id!=null||$id!=''){
        date_default_timezone_set("PRC");
        $shijian=date("Y-m-d H:i:s",time());
                $updateStatement = $database->update(array('is_first'=>1,'login_time'=>$shijian))
                    ->table('ticket')
                    ->where('id','=',$id)
                    ->where('exist',"=",0);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success'));
    }else{
        echo json_encode(array('result'=>'1','desc'=>'id为空'));
    }
});

$app->get('/password',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $chars = "0123456789abcdefghijklmnopqrstuvwxyz";
    $strr = substr($chars, mt_rand(0, strlen($chars) - 2), 1);
    do{
        $strr.= substr($chars, mt_rand(0, strlen($chars) - 2), 1);
    }while(strlen($strr)<10);
    echo json_encode(array("password"=>$strr));
});

$app->post('/en_password',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $password=$body->passwd;
    $password=encode($password , 'cxphp' );
    echo json_encode(array("password"=>$password));
});

$app->post('/de_password',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $password=$body->passwd;
    $password=decode($password , 'cxphp' );
    echo json_encode(array("password"=>$password));
});

$app->options('/alterTicket1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});


$app->put('/alterTicket1',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $pre_passwd=$body->pre_passwd;
    $new_passwd=$body->new_passwd;
    $database = localhost();
    $arrays=array();
    $arrays['passwd']=encode($new_passwd, 'cxphp' );
    if($id!=null||$id!=''){
        $selectStatement = $database->select()
            ->from('ticket')
            ->where('id','=',$id );
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
            if($pre_passwd==decode($data['passwd'] , 'cxphp' )){
                $updateStatement = $database->update($arrays)
                    ->table('ticket')
                    ->where('id','=',$id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success'));
            }else{
                echo json_encode(array('result'=>'3','desc'=>'旧密码错误'));
            }
        }else{
            echo json_encode(array('result'=>'2','desc'=>'该记录不存在'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'id为空'));
    }
});

$app->options('/alterTicket2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $app->response->headers->set("Access-Control-Allow-Methods", "PUT");
});

$app->put('/alterTicket2',function()use($app){
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
    $body=$app->request->getBody();
    $body=json_decode($body);
    $id=$body->id;
    $bg_img=$body->bg_img;
    $database = localhost();
    $arrays=array();
    $arrays['bg_img']=$bg_img;
    if($id!=null||$id!=''){
        $selectStatement = $database->select()
            ->from('ticket')
            ->where('id','=',$id );
        $stmt = $selectStatement->execute();
        $data = $stmt->fetch();
        if($data!=null){
                $updateStatement = $database->update($arrays)
                    ->table('ticket')
                    ->where('id','=',$id);
                $affectedRows = $updateStatement->execute();
                echo json_encode(array('result'=>'0','desc'=>'success'));
        }else{
            echo json_encode(array('result'=>'2','desc'=>'该记录不存在'));
        }
    }else{
        echo json_encode(array('result'=>'1','desc'=>'id为空'));
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

//解密
function decode($string, $skey) {
    $strArr = str_split(str_replace(array('O0O0O', 'o000o', 'oo00o'), array('=', '+', '/'), $string), 2);
    $strCount = count($strArr);
    foreach (str_split($skey) as $key => $value)
        $key <= $strCount  && isset($strArr[$key]) && $strArr[$key][1] === $value && $strArr[$key] = $strArr[$key][0];
    return base64_decode(join('', $strArr));
}
?>