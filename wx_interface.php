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

        if ($this->checkSignature()) {
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
                                    <ToUserName><![CDATA[%s]]></ToUserName>  
                                    <FromUserName><![CDATA[%s]]></FromUserName>  
                                    <CreateTime>%s</CreateTime>  
                                    <MsgType><![CDATA[%s]]></MsgType>  
                                    <Image>  
                                    <MediaId><![CDATA[%s]]></MediaId>  
                                    </Image>  
                                    </xml>";


            if ($ev == "subscribe") {
                //       echo '123456';
                $resultStr = sprintf($newsTpl, $fromUsername, $toUsername, $time, 'news', $ArticleCount, "公司介绍", "万事鑫公司专业从事物流运输行业", "http://api.uminfo.cn/timg.jpg", "http://api.uminfo.cn/weixin/jiangsuyouming.html");
                echo $resultStr;
            }
            if (!empty($keyword)) {
                if($keyword==1){
//                    $msgType = "text";
//                    $contentStr = "请联系客服电话：15862790779";
//                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
//                    echo $resultStr;
                    $MsgType="image";
                    $MediaId="";
                    $resultStr = sprintf($imageTpl, $fromUsername, $toUsername, $time, $MsgType, $MediaId);
                    echo $resultStr;
                }else if($keyword==2){
                    $msgType = "text";
                    $contentStr = "请联系客服电话：15862790779";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }else{
                    $msgType = "text";
                    $contentStr = "您可以回复‘1’获取价格列表，回复‘2’获得联系电话";
                    $resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                    echo $resultStr;
                }
            } else {
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

//业务消息方法
    public function responseMsg2($openid,$order_id)
    {
        //get post data, May be due to the different environments
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

        //extract post data

        $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
        $fromUsername = $postObj->FromUserName;
        $toUsername = $postObj->ToUserName;
        $keyword = trim($postObj->Content);
        $ev = $postObj->Event;
        $time = time();
        $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";
        $msgType = "text";
        $contentStr = "您的订单已经生成，正在等待前台确认,订单号为".$order_id;
        $resultStr = sprintf($textTpl, $fromUsername, $openid, $time, $msgType, $contentStr);
        echo $resultStr;
    }
}
?>
