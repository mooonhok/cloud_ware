<?php
/**
 * Step 1: Require the Slim Framework
 *
 * If you are not using Composer, you need to require the
 * Slim Framework and register its PSR-0 autoloader.
 *
 * If you are using Composer, you can skip this step.
 */
require 'Slim/Slim.php';
require 'connect.php';
require 'files_url.php';
use Slim\PDO\Database;




\Slim\Slim::registerAutoloader();

/**
 * Step 2: Instantiate a Slim application
 *
 * This example instantiates a Slim application using
 * its default settings. However, you will usually configure
 * your Slim application now by passing an associative array
 * of setting names and values into the application constructor.
 */
$app = new \Slim\Slim();

/**
 * Step 3: Define the Slim application routes
 *
 * Here we define several Slim application routes that respond
 * to appropriate HTTP request methods. In this example, the second
 * argument for `Slim::get`, `Slim::post`, `Slim::put`, `Slim::patch`, and `Slim::delete`
 * is an anonymous function.
 */




// GET route

$app->get('/testPage', function() use ($app) {
    echo $app->request->get('name');
});

$app->post("/testqianduan",function() use ($app) {
    $app->response->headers->set('Access-Control-Allow-Origin','*');
    $app->response->headers->set('Content-Type','application/json');
//    $app->redirect('http://www.uminfo.cn/yonhu.html');
    echo json_encode(array("result" => "1", "desc" => "http://www.uminfo.cn/yonhu.html", "orders" => ""));
});

$app->get("/name",function() use ($app) {
//    $tablename=$app->request->get('name');
    getAllBook();
});

$app->get("/gettable",function(){
	$database=new database("mysql:host=127.0.0.1;dbname=its_youming;charset=utf8","root","");
    $selectStatement = $database->select()
                       ->from('goods')
                       ->where('goods_id', '=', "1");
    $stmt = $selectStatement->execute();
    $data = $stmt->fetchAll();
    echo json_encode($data);
});

$app->delete('/order', function() use ($app) {
   $tenant_id=$app->request->headers->get("tenant-id");
   $orderid=$app->request->get("orderid");
   $database=new database("mysql:host=127.0.0.1;dbname=its_youming;charset=utf8","root","");
   $selectStatement = $database->select()
                      ->from('tenant')
                      ->where('tenant_id', '=',$tenant_id);
    $stmt = $selectStatement->execute();
    $data = $stmt->fetch();
    echo $data["tenant_id"];
   
});

$app->put('/order',"getAllBook");

$app->get('/weixin',function(){
$url='http://mooonhok-cloudware.daoapp.io/test7.php';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $output = curl_exec($ch);
    curl_close($ch);
    echo $output;
});

$app->get('/getfunc',function()use($app){
    $database=localhost();
    $database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $data=$database->query('select * from orders');
    $data = $data->fetchAll(PDO::FETCH_OBJ);
    echo json_encode(array('result' => '1', 'desc' => $data));
});


$app->get('/getOrdersTime',function()use($app){
    $database=localhost();
    $i=100;
    while($i>1){
        $selectStament=$database->select()
            ->from('orders')
            ->where('exist','=',0);
        $stmt=$selectStament->execute();
        $data1=$stmt->fetch();
        echo json_encode($data1);
        echo '<br>';
        sleep(1);
        $i=100-mt_rand(1,100);
    }
});
$app->get('/getOrdersTime1',function()use($app) {
    $urls = array(
        'http://www.jswsx56.cn/index.html',
        'http://www.jswsx56.cn/about_wsx.html',
        'http://www.jswsx56.cn/member.html',
        'http://www.jswsx56.cn/operate.html',
        'http://www.jswsx56.cn/product_service.html',
    );
    $api = 'http://data.zz.baidu.com/urls?site=www.jswsx56.cn&token=oogvqUOZXwcxjIeg';
    $ch = curl_init();
    $options = array(
        CURLOPT_URL => $api,
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => implode("\n", $urls),
        CURLOPT_HTTPHEADER => array('Content-Type: text/plain'),
    );
    curl_setopt_array($ch, $options);
    $result = curl_exec($ch);
    echo $result;
});

$app->get("/getTest1",function(){
    echo "1";
});

$app->get("/getTest2",function(){
    ob_get_contents();
    set_time_limit(1000);
    echo json_encode(array("test2"=>1));
    ob_end_clean();

    echo str_pad('',1024);
    $i=1;
    while($i>0){
        echo json_encode(array("test3"=>2));
        flush();
        sleep(1);
    }
});

$app->run();

function getConnection()
{
    $dbhost = "172.17.16.2";
    $dbuser = "root";
    $dbpass = "jsym_20170607";
    $dbname = "cloud_ware";
    $port='60026';
    $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;port=$port;", $dbuser, $dbpass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $dbh;
}
function getAllBook()
{
    $tablename="goods";
    $sql = "SELECT * FROM ".$tablename."";
    try {
        $db =getConnection();
        $stmt = $db->query($sql);
        $book = $stmt->fetchAll(PDO::FETCH_OBJ);
        $db = null;
        echo '{"book": ' . json_encode($book) . '}';
    } catch (PDOException $e) {
        echo "fff";
    }
}



function file_url(){
    return files_url();
}

function localhost(){
    return connect();
}
?>