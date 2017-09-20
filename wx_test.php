<?php


require 'Slim/Slim.php';
require 'connect.php';


\Slim\Slim::registerAutoloader();
$app = new \Slim\Slim();
$app->get('/getappid',function()use($app){
    $app->response->headers->set('Content-Type','application/json');
    $tenant_id=$app->request->get('tenant_id');
  //  header('Content-type:text/html;charset=utf-8');
    $database=localhost();
    //  $tenant_id=$_SERVER['QUERY_STRING'];
 //   $str=$_SERVER['QUERY_STRING'];
  //  $num=strstr($str,'tenant_id=');
  //  $tenant_id=substr($str,$num+10,8);
    $selectStatement = $database->select()
        ->from('tenant')
        ->where('tenant_id','=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data1 = $stmt->fetch();
    if($data1!=null||$data1!=""){
        //if ($_COOKIE['openid'] == null) {
            if (!isset($_GET['code'])) {
                $appid=$data1['appid'];
                $redirect_uri = urlencode('http://mooonhok-cloudware.daoapp.io/wx_test.php/getappid?tenant_id='.$tenant_id);
                $scope = 'snsapi_base';
                $url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=$appid&redirect_uri=$redirect_uri&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
              //  header('Location:' . $url);
            } else {
                $appid=$data1['appid'];
                $secret=$data1['secret'];
                $code = $_GET["code"];
                $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$appid&secret=$secret&code=$code&grant_type=authorization_code";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $output = curl_exec($ch);
                curl_close($ch);
                $json_obj = json_decode($output, true);
              //  setcookie('openid', $json_obj['openid']);
                $url='http://mooonhok-cloudware.daoapp.io/weixin/build.html?tenant_id='.$tenant_id;
                echo json_encode(array('result'=>'0','url1'=>$url,'openid'=>$json_obj['openid']));
          //      header('location:http://mooonhok-cloudware.daoapp.io/weixin/build.html?tenant_id='.$tenant_id);
            }
      //  }else{
            //header('location:http://mooonhok-cloudware.daoapp.io/weixin/build.html?tenant_id='.$tenant_id);
         //   $url='http://mooonhok-cloudware.daoapp.io/weixin/build.html?tenant_id='.$tenant_id;
        //    echo json_encode(array('url'=>$url));
       // }
    }else{
        echo json_encode(array('result'=>'2','desc'=>'访问错误'));
    }
});
$app->run();
function localhost(){
    return connect();
}
?>