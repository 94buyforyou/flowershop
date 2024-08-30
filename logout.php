<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
if(isset($_SESSION["member"]["account"])){
	unset($_SESSION["member"]["account"]);
}
// 檢查是否有上一頁 URL 存儲在會話中
if(isset($_SESSION['prev_page'])){
    // 獲取上一頁 URL
    $prevPage = $_SESSION['prev_page'];
    
    // 清除上一頁 URL，以防止未來的重定向
    unset($_SESSION['prev_page']);
    
    // 執行重定向
    header("Location: $prevPage");
} else {
    // 如果沒有上一頁 URL，則回到默認頁面或其他頁面
    header("location:index.php");
	exit();
}

?>