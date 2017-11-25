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

require 'Slim/Slim.php';
require 'connect.php';
//define your token
define("TOKEN", "weixin");
$wechatObj = new wechatCallbackapiTest();
$a=$_GET['tenant_id'];
if($_GET['echostr']){
    $wechatObj->valid();//如果发来了echostr则进行验证
}else{
    $wechatObj->responseMsg($a); //如果没有echostr，则返回消息
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

    public function responseMsg($a)
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
       $data2=$this->getcompany($a);
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
            $imageTpl = "<xml>
            <ToUserName><![CDATA[toUser]]></ToUserName>
            <FromUserName><![CDATA[fromUser]]></FromUserName>
            <CreateTime>%s</CreateTime>
            <MsgType><![CDATA[image]]></MsgType>
            <Image>
            <MediaId><![CDATA[media_id]]></MediaId>
            </Image>
            </xml>";
            
            if ($ev == "subscribe") {
                $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, 'news',
                    $ArticleCount,'公司简介',$data2['company'],'http://files.uminfo.cn:8000/weixinguanggao/wsx.png',
                    '');
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
    function getcompany($a){
        $database=localhost();
        $selectStatement = $database->select()
            ->from('tenant')
            ->where('tenant_id','=',$a);
        $stmt = $selectStatement->execute();
        $data1 = $stmt->fetchAll();
        return $data1;
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