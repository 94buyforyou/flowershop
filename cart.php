<?php
require_once("connMysql.php");
//購物車開始
require_once("mycart.php");
session_start();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
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
	header("Location: cart.php");
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
		body {
			background-image: none;
			background-color: #f4f2ec;
		}

		#cart {
			margin: 80px auto;
			display: grid;
			grid-auto-flow: column;
			/* 水平排列 */
			grid-gap: 50px;
			/* 設定間距 */
			display: block;
		}

		#cart .row.justify-content-center {
			padding: 45px 20px 40px;
		}

		.subtitle {
			font-size: 20px;
			color: #7F5757;
			font-weight: bold;
			margin: 0;
			padding: 0;
			border: 0;
		}

		.cart_inner {
			display: grid;
			grid-template-columns: auto auto;
			/* 兩個元素並排顯示 */
			justify-content: space-between;
			align-items: end;
			margin-bottom: 16px;
		}

		.line {
			height: 1px;
			width: 100%;
			background-color: #e7e2d2;
			color: #6e6e6e;
			margin: 0;
			/* 調整 margin 為 0 */
		}

		#cart li {
			list-style-type: none;
		}

		#cart .img_money {
			display: flex;
			/*將商品圖片和名稱價格並列顯示*/
			padding-top: 32px;
			padding-bottom: 32px;
		}

		.item_image {
			width: 150px;
			border: 0;
		}

		.item_info {
			flex: 1;
			margin-left: 20px;
		}

		.item_link {
			text-decoration: none;
			color: black;
		}

		.item_link:hover {
			color: #6495ED;
		}

		.subtotal {
			display: flex;
			gap: 30px;
		}

		.subtotal img {
			margin-top: -4px;
		}

		.p_price {
			margin-top: 25px;
			margin-bottom: 25px;
			font-size: 14px;
		}

		.price_info {
			display: flex;
		}

		div>.price_info:nth-child(1) {
			margin-top: 32px;
		}

		div>.price_info:nth-child(2) {
			margin-top: 20px;
			margin-bottom: 20px;
		}

		.total {
			display: flex;
			font-size: 20px;
			margin-top: 20px;
			margin-bottom: 10px;
		}

		.button_count {
			display: flex;
			align-items: center;
			justify-content: center;
			border: 1px black solid;
			padding: 4px;
			width: 80px;
		}

		.input_count::-webkit-outer-spin-button,
		.input_count::-webkit-inner-spin-button {
			/*讓輸入框右邊按鈕消失*/
			-webkit-appearance: none;
			margin: 0;
		}

		.input_count {
			width: 38px;
			border: 0;
			text-align: center;
			background-color: #f4f2ec;
		}

		.minus,
		.plus {
			cursor: pointer;
		}

		.button6 {
			width: 100%;
			height: 41px;
			border: 0;
			/* 使用 #DE5D50 作為基礎顏色，通過調整透明度來創建漸變效果 */
			background: linear-gradient(to bottom right, #DE5D50, #DE5D50 35%, rgba(222, 93, 80, 0.9) 67%, rgba(222, 93, 80, 0.7));
			background-position: center;
			background-size: cover;
			color: white;
			font-size: 15px;
			cursor: pointer;
		}

		.button7 {
			margin-top: 15px;
			width: 100%;
			height: 41px;
			border: 0;
			/*以下是漸層按鈕*/
			background: linear-gradient(to bottom right, #fd4536, #ec546e 35%, #c65f91 67%, #705fae);
			background-position: center;
			background-size: cover;
			/*以上*/
			color: white;
			font-size: 15px;
			cursor: pointer;
		}

		.infoDiv {
			display: grid;
			justify-content: center;
			align-items: center;
			margin-top: 100px;
			margin-bottom: 100px;
		}

		@media only screen and (max-width: 500px) {
			.item_info {
				margin-left: 10px;
			}

			.subtotal {
				gap: 10px;
			}
		}

		@media only screen and (max-width: 991px) {
			.col-12.col-lg-3 {
				margin-top: 40px;
			}
		}

		@media only screen and (min-width: 992px) {
			.col-12.col-lg-8 {
				margin-right: 30px;
			}
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
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse"
						data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
						aria-expanded="false" aria-label="Toggle navigation">
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
								<div class="nav-link" href="">
									<p class="c">練習作品</p>
									<p class="e">PRACTICE</p>
								</div>
								<div class="practice_works">
									<a href="index.php">花顏巧語</a>
									<a href="./snake/index.html" target="_blank">貪吃蛇</a>
									<a href="./ball/index.html" target="_blank">彈跳球</a>
									<a href="./password/index.html" target="_blank">終極密碼</a>
									<a href="./project4/index.html" target="_blank">成績計算網站</a>
								</div>
							</li>
						</ul>




						<form class="d-flex align-items-center" role="search" name="form1" method="get"
							action="product.php" id="form1">
							<input class="form-control me-2" placeholder="搜尋商品..." aria-label="Search" name="keyword"
								type="text">
							<button class="btn btn-outline-success" id="search" type="submit"></button>
						</form>
						<div class="header_toolbar1">
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
				<button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
					data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
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

				<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
					aria-labelledby="offcanvasNavbarLabel">
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
							<li class="nav-item practice">
								<p class="nav-link p_practice">練習作品</p>
								<div class="practice_works2">
									<a href="index.php">花顏巧語</a>
									<a href="./snake/index.html" target="_blank">貪吃蛇</a>
									<a href="./ball/index.html" target="_blank">彈跳球</a>
									<a href="./password/index.html" target="_blank">終極密碼</a>
									<a href="./project4/index.html" target="_blank">成績計算網站</a>
								</div>
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
							<form class="d-flex align-items-center text-center" role="search" name="form2" method="get"
								action="#" id="form2">
								<input class="form-control me-2" placeholder="搜尋商品..." aria-label="Search"
									name="keyword" type="text">
								<button class="btn btn-outline-success" id="search" type="submit"></button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</nav>
	</header>




	<section id="cart">
		<div class="container">
			<div class="row justify-content-center">
				<?php if ($cart->itemcount > 0) { ?>
					<div class="col-12 col-lg-8">
						<div class="cart_inner">
							<p class="subtitle">我的購物車</p>
							<a href="?cartaction=empty" class="link">清空購物車</a>
						</div>
						<div class="line"></div>
						<form action="" method="post" name="cartform" id="cartform">
							<?php foreach ($cart->get_contents() as $item) { ?> <!-- 購物車項目的迴圈 -->
								<li>
									<div class="img_money"> <!-- 商品圖案名稱單價數量 -->
										<div>
											<!-- 商品圖片連結 -->
											<a href="product_info.php?id=<?php echo $item['id']; ?>">
												<img src="productimg/<?php echo $item['info']; ?>.jpg" class="item_image">
											</a>
										</div>
										<div class="item_info">
											<div> <!-- 商品名 -->
												<a href="product_info.php?id=<?php echo $item['id']; ?>" class="item_link">
													<?php echo $item['info']; ?>
												</a>
											</div>

											<div class="p_price"> <!-- 商品單價 -->
												<p>NT$ <?php echo number_format($item['price']); ?></p>
											</div>

											<div> <!-- 商品數量 -->
												<div class="button_count">
													<?php if ($item['qty'] == 1) { ?>
														<img src="pic/minus_grey.png" width="10" height="10" class="minus">
													<?php } else { ?>
														<img src="pic/minus.png" width="10" height="10" class="minus">
													<?php } ?>
													<input name="updateid[]" type="hidden" id="updateid[]" value="<?php echo $item['id']; ?>">
													<input name="qty[]" type="text" id="qty[]" class="input_count" value="<?php echo $item['qty']; ?>" size="1">
													<img src="pic/plus.png" width="10" height="10" class="plus">
												</div>
											</div>
										</div>


										<div class="subtotal"> <!-- 右邊的單項總金額 -->
											<div>
												<p class="top_p">NT$ <?php echo number_format($item['subtotal']); ?></p>
											</div>
											<div>
												<a href="?cartaction=remove&delid=<?php echo $item['id']; ?>">
													<img src="pic/delete.png" width="12px" height="12px">
												</a>
											</div>
										</div>
									</div>
								</li>
								<div class="line"></div>
							<?php } ?>

					</div>
					<div class="col-12 col-lg-3">
						<div class="cart_inner">
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
						<div style="float: right;">
							<input name="cartaction" type="hidden" id="cartaction" value="update">
							<input type="submit" name="updatebtn" id="button3" style="display: none;" value="更新購物車">
						</div>
						<input type="button" name="button" id="button6" class="button6" value="繼續購物" onClick="window.location.href='product.php';">
						<input type="button" name="button" id="button7" class="button7" value="下一步 : 填寫收件地址" onClick="window.location.href='buycheck.php';">
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