<?php

//��ȡ����ҳ��Ȩ��access_token
$appid = "wx15ef051f9f0bba92";//��д���ںŻ����š����Ժŵ�appid
$secret = "57ea0ee4abf4f4c6d6e38c88a289e687";//��д��Ӧ��secriet

if(isset($_SESSION['openId'])){
    $openid = $_SESSION['openId'];
}else{
    $code = $_GET['code'];
    $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code ";

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);
    $json_obj = json_decode($res,true);
    $openid = $json_obj['openid'];
    $_SESSION['openId'] = $openid;
}
    echo '΢���û���'.$openid.'<br/>';
    echo '$json_obj ������Ϣ��';
    var_dump($json_obj);

    getUserInfo($json_obj);
/**
 * @param $json_obj
 * �����û�openid ��ȡ��������Ϣ
 */
function getUserInfo($json_obj){
    $access_token = $json_obj['access_token'];
    $openid = $json_obj['openid'];
    $get_user_info_url = 'https://api.weixin.qq.com/sns/userinfo?access_token='.$access_token.'&openid='.$openid.'&lang=zh_CN';

    $ch = curl_init();
    curl_setopt($ch,CURLOPT_URL,$get_user_info_url);
    curl_setopt($ch,CURLOPT_HEADER,0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1 );
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $res = curl_exec($ch);
    curl_close($ch);

//����json
    $user_obj = json_decode($res,true);
    var_dump($user_obj);
    echo 'Name:'.$user_obj['nickname'];
}


?>
