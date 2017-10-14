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
if($_GET['echostr']){
    $wechatObj->valid();//如果发来了echostr则进行验证
}else{
    $wechatObj->responseMsg(); //如果没有echostr，则返回消息
}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];

        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

//    public function responseMsg()
//    {
//        //get post data, May be due to the different environments
//        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
//
//        //extract post data
//        if (!empty($postStr)){
//
//            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
//            $fromUsername = $postObj->FromUserName;
//            $toUsername = $postObj->ToUserName;
//            $keyword = trim($postObj->Content);
//            $ev = $postObj->Event;
//            $time = time();
//            $textTpl = "<xml>
//							<ToUserName><![CDATA[%s]]></ToUserName>
//							<FromUserName><![CDATA[%s]]></FromUserName>
//							<CreateTime>%s</CreateTime>
//							<MsgType><![CDATA[%s]]></MsgType>
//							<Content><![CDATA[%s]]></Content>
//							<FuncFlag>0</FuncFlag>
//							</xml>";
//            if ($ev == "subscribe"){
////                $msgType = "text";  //image,text
////               $contentStr = "欢迎关注！万事鑫联微信公众号"; //http://api.uminfo.cn/timg.jpg
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                echo $resultStr;
//            }
//
//            if(!empty( $keyword ))
//            {
//                $msgType = "text";
//                $contentStr = "请联系qq客服：1026413232";
//                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                echo $resultStr;
//            }else{
//                echo "Input something...";
//            }
//        }else {
//            echo "";
//            exit;
//        }
//    }
    public function responseMsg()
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data
        if (!empty($postStr)){
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
            if ($ev == "subscribe"){
         //       echo '123456';
                $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, 'news',$ArticleCount,"公司介绍","万事鑫公司专业从事物流运输行业","http://api.uminfo.cn/timg.jpg","http://api.uminfo.cn/weixin/jiangsuyouming.html");
                echo $resultStr;
            }

            if(!empty( $keyword ))
            {
                $msgType = "text";
                $contentStr = "请联系客服电话：15862790779";
                $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                echo $resultStr;
            }else{
                echo "Input something...";
            }
        }else {
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
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}

?>
