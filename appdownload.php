﻿<php
require 'files_url.php';
$file_url=file_url();
echo $file_url;
function file_url(){
return files_url();
}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no" />
		<title>交付帮手下载</title>
		<style>
				* {
				margin: 0;
				padding: 0;
			}
				html,
			body {
				height: 100%;
				width: 100%;
				overflow: hidden;
				margin: 0;
				padding: 0;
			
			}
			.center{
				width:100%;
				height:100%;
				background:
				background-size:100%,100%;
	            background-repeat: no-repeat;			
			}
			.center1{
				width:100%;
				height:200px;
				color:white;
				text-align: center;
				line-height:100px;
				font-size:30px;
				position:absolute;
				top:80px;
			}
			.center2{
				width:40%;
				margin-left:30%;
				/*height:100px;*/
				line-height:70px;
				text-align: 200px;
				background-color: #00E3E3;
				color:white;
				font-size: 30px;
				text-align: center;
				border-radius: 10px;
				position:absolute;
				top:200px;
			}
			
			.center2 a{
				width:40%;
				height:70px;
				line-height:70px;
				/*margin-top:300px;*/
				text-align: 200px;
				background-color: #00E3E3;
				border-radius: 10px;
				color:white;
			}
		
		</style>
	</head>
	<body>
		<div class="center"  style="background:url(<php  echo  $file_url.'app/appdown.jpg';?>);">
		<div class="center1">交付帮手</div>
		<div class="center2"><a   href="<php  echo  $file_url.'/app/jiaofu.apk';?>">点击下载</a></div>
	    </div>
	</body>
</html>



