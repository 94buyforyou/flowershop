<?php
session_start();
require_once("connMysql.php");
//檢查是否經過登入，若有登入則重新導向
if(isset($_SESSION["member"]["account"]) && ($_SESSION["member"]["account"]!="")){
	//若存在 $_SESSION["member"]["account"]
		header("Location: checkout.php");
	//否則則導向登入夜面
	}else{
		header("Location: login.php");	
	}

?>
