<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/7/28
 * Time: 16:27
 */
use Slim\PDO\Database;
function connect(){
    $mysql_server_name='localhost'; //改成自己的mysql数据库服务器
    $mysql_username='root'; //改成自己的mysql数据库用户名
    $mysql_password='123456'; //改成自己的mysql数据库密码
    $mysql_database='Mydb'; //改成自己的mysql数据库名
    $database=new database("mysql:host=tcp://db2.daocloudinternal.io:60212;dbname=temp_db;charset=utf8","root","Gd6lhOSsY");
    return  $database;
}


?>