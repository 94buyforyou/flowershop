<?php
require_once("connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$cart = &$_SESSION['cart']; // 將購物車的值設定為 Session
if (!is_object($cart)) $cart = new myCart();
// 更新購物車內容
if (isset($_POST["cartaction"]) && ($_POST["cartaction"] == "update")) {
	if (isset($_POST["updateid"])) {
		$i = count($_POST["updateid"]);
		for ($j = 0; $j < $i; $j++) {
			$cart->edit_item($_POST['updateid'][$j], $_POST['qty'][$j]);
		}
	}
	header("Location: 123.php");
}
// 移除購物車內容
if (isset($_GET["cartaction"]) && ($_GET["cartaction"] == "remove")) {
	$rid = intval($_GET['delid']);
	$cart->del_item($rid);
	header("Location: cart.php");
}
// 清空購物車內容
if (isset($_GET["cartaction"]) && ($_GET["cartaction"] == "empty")) {
	$cart->empty_cart();
	header("Location: cart.php");
}
//購物車結束
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid)as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();

?>



<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="css.css">
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="script.js"></script>
	<title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 購物車</title>
	<style>
        .info_table {
            margin-top: 32px;
            margin-bottom: 50px;
            width: 100%;
            background-color: #E1DCD9;
        }

		.info_table th {
			width: 20%;
		}


	</style>
</head>

<body>
	<header class="header1">
		<div class="container">
			<nav class="navbar navbar-expand-lg fixed-top">
				<div class="container-fluid">
					<div class="logo-container">
						<a class="navbar-brand" href="index.php">
							<img src="pic/logo.png" class="logo">
						</a>
					</div>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
						<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarSupportedContent">
						<ul class="navbar-nav me-auto mb-2 mb-lg-0">
							<li class="nav-item">
								<a class="nav-link" href="#">
									<p class="c">品牌故事</p>
									<p class="e">BRAND</p>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="product.php">
									<p class="c">花藝商品</p>
									<p class="e">PRODUCT</p>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<p class="c">新品上市</p>
									<p class="e">NEW</p>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<p class="c">常見問題</p>
									<p class="e">FAQ</p>
								</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">
									<p class="c">聯絡我們</p>
									<p class="e">CONTACT</p>
								</a>
							</li>
						</ul>

						<form class="d-flex align-items-center" role="search" name="form1" method="get" action="product.php" id="form1">
							<input class="form-control me-2" placeholder="搜尋商品..." aria-label="Search" name="keyword" type="text">
							<button class="btn btn-outline-success" id="search" type="submit"></button>
						</form>
						<div class="header_toolbar1">
							<?php if (isset($_SESSION["member"]["account"]) && ($_SESSION["member"]["account"] != "")) { ?>
								<div class="header_toolbar1_member">
									<img src="pic/member.png" class="member1 toolbar_icon" id="m1">
									<div id="memberlist">
										<a href="member.php">會員中心</a>
										<a href="logout.php">登出</a>
									</div>
								</div>
							<?php } else { ?>
								<div class="header_toolbar1_member">
									<a href="logincheck.php" class="link">
										<img src="pic/member.png" class="member1 toolbar_icon">
									</a>
								</div>
							<?php } ?>
							<div class="header_toolbar1_cart">
								<a href="cart.php" class="link">
									<img src="pic/shopping_cart.png" class="cart1 toolbar_icon">
								</a>
							</div>
						</div>
					</div>
				</div>
			</nav>
		</div>
	</header>

	<header class="header2">
		<nav class="navbar fixed-top">
			<div class="container-fluid">
				<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>
				<a class="navbar-brand" href="index.php"><img src="pic/logo.png" class="logo"></a>
				<div class="header_toolbar2">
					<?php if (isset($_SESSION["member"]["account"]) && ($_SESSION["member"]["account"] != "")) { ?>
						<div class="header_toolbar1_member">
							<img src="pic/member.png" class="member1 toolbar_icon">
							<div class="memberlist">
								<a href="member.php">會員中心</a>
								<a href="logout.php">登出</a>
							</div>
						</div>
					<?php } else { ?>
						<div class="header_toolbar1_member">
							<a href="logincheck.php" class="link">
								<img src="pic/member.png" class="member1 toolbar_icon">
							</a>
						</div>
					<?php } ?>
					<div>
						<a href="cart.php" class="link">
							<img src="pic/shopping_cart.png" class="cart2 toolbar_icon">
						</a>
					</div>
				</div>

				<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
					<br>
					<div class="offcanvas-header">
						<h5 class="offcanvas-title" id="offcanvasNavbarLabel">花顏巧語 Capturing the Essence of Beauty</h5>
						<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
					</div>
					<div class="offcanvas-body">
						<ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
							<li class="nav-item">
								<a class="nav-link" href="index.php">首頁</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">品牌故事</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="product.php">花藝商品</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">新品上市</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">常見問題</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="#">聯絡我們</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="cart.php">購物車</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" href="logincheck.php">會員中心</a>
							</li>
							<?php if (isset($_SESSION["member"]["account"]) && ($_SESSION["member"]["account"] != "")) { ?>
								<li class="nav-item">
									<a class="nav-link" href="logout.php">登出</a>
								</li>
							<?php } ?>
						</ul>
						<div>
							<form class="d-flex align-items-center text-center" role="search" name="form2" method="get" action="#" id="form2">
								<input class="form-control me-2" placeholder="搜尋商品..." aria-label="Search" name="keyword" type="text">
								<button class="btn btn-outline-success" id="search" type="submit"></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>

	<section id="info">
		<div class="container">
			<div class="row justify-content-center">
				<?php if ($cart->itemcount > 0) { ?>
					<div class="col-12 col-lg-8">
						<div class="info_inner">
							<p class="subtitle">收件人資訊</p>
						</div>
						<div class="line"></div>
						<table class="info_table">
							<tr>
								<th>
									<p>姓名</p>
								</th>
								<td>
									<div class="d1">
										<input type="text" name="customername" id="customername">
									</div>
								</td>
							</tr>
							<tr>
								<th>
									<p>電話</p>
								</th>
								<td>
									<div class="d1">
										<input type="phone" name="customerphone" id="customerphone">
									</div>
								</td>
							</tr>
							<tr>
								<th>
									<p>地址</p>
								</th>
								<td>
									<div class="d1">
										<input name="customeraddress" type="text" id="customeraddress">
									</div>
								</td>
							</tr>

							<tr style="display: none;">
								<th>
									<p>電子郵件</p>
								</th>
								<td>
									<div class="d1">
										<input type="text" name="customeremail" id="customeremail" value="123@gmail.com">
									</div>
								</td>
							</tr>

							<tr>
								<th style="border: none;">
									<p>付款方式</p>
								</th>
								<td style="border: none;">
									<select name="paytype" id="paytype">
										<option value="線上刷卡" selected>線上刷卡</option>
										<option value="ATM匯款">ATM匯款</option>
									</select>
								</td>
							</tr>
						</table>
						<div class="info_inner">
							<p class="subtitle">指定配送方式及日期</p>
						</div>
						<div class="line"></div>
						<div class="warm">
							<p>如果您訂購的商品具有不同的預計出貨日期，商品將會在最晚的預計出貨日一起發貨。</p>
							<br>
							<p>如果您急需某項商品，請單獨購買。</p>
						</div>
						<table class="info_table">
							<tr>
								<th>
									<p>配送方式</p>
								</th>
								<td>
									<select>
										<option value="宅配" selected>宅配</option>
									</select>
								</td>
							</tr>
							<tr>
								<th>
									<p>希望送達日期</p>
								</th>
								<td>
									<select id="date" name="date"></select>
									<p class="date_info1">1.需最慢三日前提早預訂，小編會安排司機外送，如有問題會電話連絡。</p>
									<p class="date_info2">2.遇國定連休假日，訂單有可能隔日處理唷(母親節、情人節、新年除外)。</p>
								</td>
							</tr>
							<tr>
								<th style="border: none;">
									<p>希望送達時間</p>
								</th>
								<td style="border: none;">
									<select>
										<option selected>不指定</option>
										<option>12時前</option>
										<option>12時~18時</option>
									</select>

									<p class="date_info1">1."當日急單"是沒辦法完全在指定時段配送達喔!!</p>
									<p class="date_info1">2.如送酒店、夜店者，請先提早來電連絡送花時間!!</p>
									<p class="date_info1">3.大節日(畢業季、情人節、新年)送花時段可能會延誤塞車，請顧客見諒唷!!</p>
									<p class="date_info2">4.實際送達時間受配送路線及當日貨量、交通狀況、特殊天氣狀況影響，可能提早或延後，請多見諒。</p>
								</td>
							</tr>
						</table>
					</div>

					<div class="col-12 col-lg-3">
						<div class="info_inner">
							<p class="subtitle">訂單摘要</p>
						</div>
						<div class="line"></div>
						<div> <!-- 訂單摘要 小計 + 運費 -->
							<div>
								<div class="price_info">
									<p>小計：</p>
									<p style="margin-left: auto;">NT$ <?php echo number_format($cart->total); ?></p>
								</div>
								<div class="price_info">
									<p>運費：</p>
									<p style="margin-left: auto;">NT$ <?php echo number_format($cart->deliverfee); ?></p>
								</div>
							</div>
						</div>
						<div class="line"></div>
						<div class="total">
							<p>總計</p>
							<p style="margin-left: auto;">NT$ <?php echo number_format($cart->grandtotal); ?></p>
						</div>
						<input type="button" name="button" id="button2" class="button2" value="返回上一頁" onClick="window.location.href='cart.php';">
						<button type="submit" form="cartform" class="button1">前往付款</button>
					</div>

				<?php } else { ?>
					<br>
					<div class="infoDiv">
						<span>購物車是空的哦~</span>
						<br>
						<button onclick="window.location.href = 'product.php'">繼續購物</button>
					</div>
				<?php } ?>
			</div>
		</div>
	</section>

	<footer>
		<div class="container">
			<div class="row justify-content-center text-center">
				<div class="col-md-3">
					<img src="pic/logo.png">
				</div>
				<div class="col-md-3" id="time">
					<div>
						<span>營業時間 : </span>
						<ul>
							<li>週一至週五 AM:09:00 ~ PM:17:00</li>
							<br>
							<li>週六及週日 AM:09:00 ~ PM:16:00</li>
						</ul>
						<span>電話 : </span>
						<ul>
							<li>0912 - 345678</li>
						</ul>
					</div>
				</div>
				<div class="col-md-12">
					<ul>
						<li><a href="#" class="link">隱私權聲明</a></li>
						<li><a href="#" class="link">服務條款</a></li>
						<li><a href="#" class="link">付款方式</a></li>
						<li><a href="#" class="link">徵才消息</a></li>
					</ul>
				</div>
			</div>

		</div>
	</footer>

	<script>
		$(document).ready(function() {
			$(".member1, .member2").hover(
				function() {
					$(this).attr("src", "pic/member_black.png");
				},
				function() {
					$(this).attr("src", "pic/member.png");
				}
			);

			$(".cart1, .cart2").hover(
				function() {
					$(this).attr("src", "pic/shopping_cart_black.png");
				},
				function() {
					$(this).attr("src", "pic/shopping_cart.png");
				}
			);

			$(".plus").click(function() {
				var p = parseInt($(this).siblings(".input_count").val());
				p = p + 1;
				$(this).siblings(".input_count").val(p);
				if (p != 1) {
					$(this).siblings(".minus").attr("src", "pic/minus.png");
				}
				// 在此處執行更新購物車的程式碼
				updateCart();
			});


			$(".minus").click(function() {
				var m = parseInt($(this).siblings(".input_count").val());
				if (m > 1) {
					m = m - 1;
					$(this).siblings(".input_count").val(m);
				}
				if (m === 1) {
					$(this).attr("src", "pic/minus_grey.png");
				}
				// 在此處執行更新購物車的程式碼
				updateCart();
			});

			// 更新購物車的函式
			$(".input_count").change(function() {
				updateCart();
			});

			function updateCart() {
				$("#button3").click(); // 在此處執行更新購物車的程式碼
			}
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>



</body>



</html>