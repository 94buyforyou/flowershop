<?php 
require_once("connMysql.php");
if(isset($_POST["customername"]) && ($_POST["customername"]!="")){
	//購物車開始
	require_once("mycart.php");
	session_start();
	$account = $_SESSION["member"]["account"];
	$cart =& $_SESSION['cart']; // 將購物車的值設定為 Session
	if(!is_object($cart)) $cart = new myCart();
	//購物車結束	
	//新增訂單資料
	$sql_query = "INSERT INTO orders (total, deliverfee, grandtotal, customername, customeraddress, customerphone, paytype, account) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
	$stmt = $db_link->prepare($sql_query);
	$stmt->bind_param("iiisssss", $cart->total, $cart->deliverfee, $cart->grandtotal, $_POST["customername"], $_POST["customeraddress"], $_POST["customerphone"], $_POST["paytype"], $account);
	$stmt->execute();
	//取得新增的訂單編號
	$o_pid = $stmt->insert_id;
	$stmt->close();
	//新增訂單內貨品資料
	if($cart->itemcount > 0) {
		foreach($cart->get_contents() as $item) {
			$sql_query="INSERT INTO orderdetail (orderid ,productid ,productname ,unitprice ,quantity, account) VALUES (?, ?, ?, ?, ?, ?)";
			$stmt = $db_link->prepare($sql_query);
			$stmt->bind_param("iisiis", $o_pid, $item['id'], $item['info'], $item['price'], $item['qty'], $account);
			$stmt->execute();
			$stmt->close();
		}
	}



	//清空購物車
	$cart->empty_cart();
}	
?>
<script language="javascript">
alert("感謝您的購買，我們將儘快進行處理。");
window.location.href="index.php";
</script>