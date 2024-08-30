<?php
  require_once("connMysql.php");
  //購物車開始
  require_once("mycart.php");
  session_start();
  $cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
  if(!is_object($cart)) $cart = new myCart();

  if(isset($_POST["cartaction"]) && ($_POST["cartaction"]=="add")){
  $cart->add_item($_POST['id'], $_POST['qty'], $_POST['price'], $_POST['name']);


  ob_start(); // 開始緩衝輸出
  // 進行其他的 PHP 程式碼和處理

  // 假設你要重定向到 product_info.php?id=xxx
  $id = $_POST['id'];
  $redirectUrl = "product_info.php?id=$id";

  ob_end_clean(); // 清除緩衝區內容
  header("Location: $redirectUrl");
  exit(); // 確保在進行重定向後結束腳本的執行
}
?>