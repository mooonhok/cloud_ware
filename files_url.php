<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/22
 * Time: 10:07
 */

function files_url(){
    $url=explode(".",$_SERVER["HTTP_HOST"]);
    return  'http://files.'.$url[1].'.cn:8000/';
//    return  'http://files.uminfo.cn:8000/';
}


function apiurl(){
    $url=explode(".",$_SERVER["HTTP_HOST"]);
    return $url[1];
}
?>