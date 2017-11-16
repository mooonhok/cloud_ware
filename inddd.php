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
$conn = new mysqli("{$servername}:{$port}",$servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("连接失败: " . $conn->connect_error);
}

$sql = "SELECT * FROM staff";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 输出数据
    while($row = $result->fetch_assoc()) {
        echo "id: " . $row["id"]. " - Name: " . $row["name"]. " " . "<br>";
    }
} else {
    echo "0 结果";
}
$conn->close();
?>