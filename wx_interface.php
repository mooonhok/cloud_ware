<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/8/9
 * Time: 8:50
 */
/**
 * wechat php test
 */

//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$a=$_GET['tenant_id'];
$url=$_SERVER["HTTP_HOST"];
if(isset($_GET['echostr'])){
    $wechatObj->valid();//如果发来了echostr则进行验证
}else{
    $wechatObj->responseMsg($a,$url); //如果没有echostr，则返回消息
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        if ($this->checkSignature()) {
            echo $echoStr;
            exit;
        }
    }

    public function responseMsg($a,$url)
    {
        //get post data, May be due to the different environments
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        $postStr = isset($GLOBALS['HTTP_RAW_POST_DATA']) ? $GLOBALS['HTTP_RAW_POST_DATA'] : file_get_contents("php://input");
        $data=$this->getcompany($a);
        //extract post data
       if (!empty($postStr)) {
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $fromUsername = $postObj->FromUserName;
            $toUsername = $postObj->ToUserName;
            $keyword = trim($postObj->Content);
            $ev = $postObj->Event;
            $time = time();
            $ArticleCount = 1;
            $newsTpl = "<xml>  
                                <ToUserName><![CDATA[%s]]></ToUserName>  
                                <FromUserName><![CDATA[%s]]></FromUserName>  
                                <CreateTime>%s</CreateTime>  
                                <MsgType><![CDATA[%s]]></MsgType>  
                                <ArticleCount>%s</ArticleCount>  
                                <Articles>  
                                <item>  
                                <Title><![CDATA[%s]]></Title>   
                                <Description><![CDATA[%s]]></Description>  
                                <PicUrl><![CDATA[%s]]></PicUrl>  
                                <Url><![CDATA[%s]]></Url>  
                                </item>   
                                </Articles>  
                                </xml>";
            $textTpl = "<xml>
						<ToUserName><![CDATA[%s]]></ToUserName>
						<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content> 
							<FuncFlag>0</FuncFlag>
							</xml>";

           if ($ev == "subscribe") {
               $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, 'news',
                   $ArticleCount,'公司简介',$a,$data['tenant_img_url'],
                   "http://".$url.'/weixin/sguangao.html?tenant_id='.$data['tenant_id']);
               echo $resultStr;
           }
           if (!empty($keyword)) {
               $msgType = "text";
               $contentStr = "请在菜单中选择电话询价，拨打电话咨询需要的信息";
               $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               echo $resultStr;
           } else {
               echo "Input something...";
           }
       } else {
           echo "";
           exit;
       }
    }
     private function getcompany($a)
     {
         $dbhost = "172.17.16.2";
         $dbuser = "root";
         $dbpass = "jsym_20170607";
         $dbname = "cloud_ware";
         $port='60026';
         $dbh = new PDO("mysql:host=$dbhost;dbname=$dbname;port=$port;charset:utf8;", $dbuser, $dbpass,array(PDO::MYSQL_ATTR_INIT_COMMAND => "set names utf8"));
         $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
         $tablename="tenant";
         $sql = "SELECT * FROM   ".$tablename."   WHERE  tenant_id=".$a;
         try {
             $stmt = $dbh->query($sql);
             $tenant = $stmt->fetch(PDO::FETCH_ASSOC);
             $dbh = null;
             return $tenant;
         } catch (PDOException $e) {
             return $e->getMessage();
         }
     }
    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode($tmpArr);
        $tmpStr = sha1($tmpStr);

        if ($tmpStr == $signature) {
            return true;
        } else {
            return false;
        }
    }
}
?>