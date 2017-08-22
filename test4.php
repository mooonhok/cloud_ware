<?php  
require_once "test3.php";  
$jssdk = new JSSDK("wx15ef051f9f0bba92", "57ea0ee4abf4f4c6d6e38c88a289e687");  
$signPackage = $jssdk->GetSignPackage();  
?>  

<!DOCTYPE html>  
<html>  
<head>  
  <meta charset="utf-8">  
  <title>΢��JS-SDK Demo</title>  
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=0">  
  <link rel="stylesheet" href="style.css">  
</head>  
<body ontouchstart="">  
<h3 id="menu-scan">΢��ɨһɨ</h3>  
<span class="desc">����΢��ɨһɨ�ӿ�</span>  
<button class="btn btn_primary" id="scanQRCode0">scanQRCode(΢�Ŵ�����)</button>  
<button class="btn btn_primary" id="scanQRCode1">scanQRCode(ֱ�ӷ��ؽ��)</button>  
</body>  
<script src="http://res.wx.qq.com/open/js/jweixin-1.0.0.js"></script>  
<script>  
  /* 
   * ע�⣺ 
   * 1. ���е�JS�ӿ�ֻ���ڹ��ںŰ󶨵������µ��ã����ںſ�������Ҫ�ȵ�¼΢�Ź���ƽ̨���롰���ں����á��ġ��������á�����д��JS�ӿڰ�ȫ�������� 
   * 2. ��������� Android ���ܷ����Զ������ݣ��뵽�����������µİ����ǰ�װ��Android �Զ������ӿ��������� 6.0.2.58 �汾�����ϡ� 
   * 3. �������⼰���� JS-SDK �ĵ���ַ��http://mp.weixin.qq.com/wiki/7/aaa137b55fb2e0456bf8dd9148dd613f.html 
   * 
   * ������������������ĵ�����¼5-�������󼰽���취�����������δ�ܽ����ͨ���������������� 
   * �����ַ��weixin-open@qq.com 
   * �ʼ����⣺��΢��JS-SDK�������������� 
   * �ʼ�����˵�����ü��������������������ڣ��������������������ĳ������ɸ��Ͻ���ͼƬ��΢���Ŷӻᾡ�촦����ķ����� 
   */  
  wx.config({  
      debug: true,  
      appId: '<?php echo $signPackage["appId"];?>',  
      timestamp: <?php echo $signPackage["timestamp"];?>,  
      nonceStr: '<?php echo $signPackage["nonceStr"];?>',  
      signature: '<?php echo $signPackage["signature"];?>',  
      jsApiList: [  
        'checkJsApi',  
        'onMenuShareTimeline',  
        'onMenuShareAppMessage',  
        'onMenuShareQQ',  
        'onMenuShareWeibo',  
        'hideMenuItems',  
        'showMenuItems',  
        'hideAllNonBaseMenuItem',  
        'showAllNonBaseMenuItem',  
        'translateVoice',  
        'startRecord',  
        'stopRecord',  
        'onRecordEnd',  
        'playVoice',  
        'pauseVoice',  
        'stopVoice',  
        'uploadVoice',  
        'downloadVoice',  
        'chooseImage',  
        'previewImage',  
        'uploadImage',  
        'downloadImage',  
        'getNetworkType',  
        'openLocation',  
        'getLocation',  
        'hideOptionMenu',  
        'showOptionMenu',  
        'closeWindow',  
        'scanQRCode',  
        'chooseWXPay',  
        'openProductSpecificView',  
        'addCard',  
        'chooseCard',  
        'openCard'  
      ]  
  });  
</script>  
<script src="test5.js"> </script>  
</html>  