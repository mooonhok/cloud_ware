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
define("TOKEN", "youming");
$wechatObj = new wechatCallbackapiTest();

if(isset($_GET['echostr'])){
    $wechatObj->valid();//如果发来了echostr则进行验证
}else{
    $wechatObj->responseMsg(); //如果没有echostr，则返回消息
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

    public function responseMsg()
    {
        //get post data, May be due to the different environments
       $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];


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
                    $ArticleCount,'公司简介','江苏酉铭信息技术有限公司','http://files.uminfo.cn:8000/weixinguanggao/uminfo.jpg',
                    'http://www.uminfo.cn');
                echo $resultStr;
            }else if($ev=="CLICK"){
                $msgType = "text";
                $contentStr = "客服电话：15365580443";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }
           if (!empty($keyword)) {
               $msgType = "text";
               $contentStr = "客服电话：15365580443";
               $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
               echo $resultStr;
           }else {
                echo "Input something...";
            }
       } else {
           echo "";
           exit;
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