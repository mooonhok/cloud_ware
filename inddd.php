<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/16
 * Time: 19:11
 */

$servername = "172.17.16.2";
$username = "root";
$password = "jsym_20170607";
$dbname = "cloud_ware";
$port=60026;

// 创建连接
$db_connect =@new mysqli($servername,$username,$password,$dbname,3306);
if ($db_connect->connect_error) {
    die("连接失败: " . $db_connect->connect_error);
}

$sql = "SELECT * FROM staff";
$result = $db_connect->query($sql);

$db_connect->close();
?>