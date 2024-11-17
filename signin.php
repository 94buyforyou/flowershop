<?php
session_start();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
require_once("connMysql.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// 獲取用戶提交的數據
	$account = $_POST['account'];
	$password = $_POST['password'];
	$name = $_POST['name'];
	$email = $_POST['email'];
	$phone = $_POST['phone'];
	$address = $_POST['address'];

	$sql = "SELECT * FROM member WHERE account=? OR email=? OR phone=?";
	$stmt = $db_link->prepare($sql);
	$stmt->bind_param("sss", $account, $email, $phone);
	$stmt->execute();

	$matchedaccount = null;
	$matchedemail = null;
	$matchedphone = null;

	$result = $stmt->get_result();

	while ($row = $result->fetch_assoc()) {
		if ($account === $row["account"]) {
			$matchedaccount = $row;
		}
		if ($email === $row["email"]) {
			$matchedemail = $row;
		}
		if ($phone === $row["phone"]) {
			$matchedphone = $row;
		}
	}

	echo "<script>";
	echo "window.onload = function() {";

	if ($matchedaccount !== null && !empty($account)) {
		echo "var accountDiv = document.querySelector('.account_div');";
		echo "if (accountDiv) {";
		echo "    var errorMessage_account = accountDiv.querySelector('.errormessage_account');";
		echo "    var accountInput = accountDiv.querySelector('.signin_input_account');";
		echo "    if (errorMessage_account) {";
		echo "        errorMessage_account.style.display = 'block';";
		echo "        accountDiv.style.marginBottom = '0px';";
		echo "        accountInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	} elseif (empty($account)) {
		echo "var accountDiv = document.querySelector('.account_div');";
		echo "if (accountDiv) {";
		echo "    var errorMessage_account_empty = accountDiv.querySelector('.errormessage_account_empty');";
		echo "    var accountInput = accountDiv.querySelector('.signin_input_account');";
		echo "    if (errorMessage_account_empty) {";
		echo "        errorMessage_account_empty.style.display = 'block';";
		echo "        accountDiv.style.marginBottom = '0px';";
		echo "        accountInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	if (empty($password)) {
		echo "var passwordDiv = document.querySelector('.password_div');";
		echo "if (passwordDiv) {";
		echo "    var errorMessage_password_empty = passwordDiv.querySelector('.errormessage_password_empty');";
		echo "    var passwordInput = passwordDiv.querySelector('.signin_input_password');";
		echo "    if (errorMessage_password_empty) {";
		echo "        errorMessage_password_empty.style.display = 'block';";
		echo "        passwordDiv.style.marginBottom = '0px';";
		echo "        passwordInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	if (empty($name)) {
		echo "var nameDiv = document.querySelector('.name_div');";
		echo "if (nameDiv) {";
		echo "    var errorMessage_name_empty = nameDiv.querySelector('.errormessage_name_empty');";
		echo "    var nameInput = nameDiv.querySelector('.signin_input_name');";
		echo "    if (errorMessage_name_empty) {";
		echo "        errorMessage_name_empty.style.display = 'block';";
		echo "        nameDiv.style.marginBottom = '0px';";
		echo "        nameInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	if ($matchedemail !== null && !empty($email)) {
		echo "var emailDiv = document.querySelector('.email_div');";
		echo "if (emailDiv) {";
		echo "    var errorMessage_email = emailDiv.querySelector('.errormessage_email');";
		echo "    var emailInput = emailDiv.querySelector('.signin_input_email');";
		echo "    if (errorMessage_email) {";
		echo "        errorMessage_email.style.display = 'block';";
		echo "        emailDiv.style.marginBottom = '0';";
		echo "        emailInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	} elseif (empty($email)) {
		echo "var emailDiv = document.querySelector('.email_div');";
		echo "if (emailDiv) {";
		echo "    var errorMessage_email_empty = emailDiv.querySelector('.errormessage_email_empty');";
		echo "    var emailInput = emailDiv.querySelector('.signin_input_email');";
		echo "    if (errorMessage_email_empty) {";
		echo "        errorMessage_email_empty.style.display = 'block';";
		echo "        emailDiv.style.marginBottom = '0';";
		echo "        emailInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	if ($matchedphone !== null && !empty($phone)) {
		echo "var phoneDiv = document.querySelector('.phone_div');";
		echo "if (phoneDiv) {";
		echo "    var errorMessage_phone = phoneDiv.querySelector('.errormessage_phone');";
		echo "    var phoneInput = phoneDiv.querySelector('.signin_input_phone');";
		echo "    if (errorMessage_phone) {";
		echo "        errorMessage_phone.style.display = 'block';";
		echo "        phoneDiv.style.marginBottom = '0px';";
		echo "        phoneInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	} elseif (empty($phone)) {
		echo "var phoneDiv = document.querySelector('.phone_div');";
		echo "if (phoneDiv) {";
		echo "    var errorMessage_phone_empty = phoneDiv.querySelector('.errormessage_phone_empty');";
		echo "    var phoneInput = phoneDiv.querySelector('.signin_input_phone');";
		echo "    if (errorMessage_phone_empty) {";
		echo "        errorMessage_phone_empty.style.display = 'block';";
		echo "        phoneDiv.style.marginBottom = '0px';";
		echo "        phoneInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	if (empty($address)) {
		echo "var addressDiv = document.querySelector('.address_div');";
		echo "if (addressDiv) {";
		echo "    var errorMessage_address_empty = addressDiv.querySelector('.errormessage_address_empty');";
		echo "    var addressInput = addressDiv.querySelector('.signin_input_address');";
		echo "    if (errorMessage_address_empty) {";
		echo "        errorMessage_address_empty.style.display = 'block';";
		echo "        addressInput.style.borderColor = 'red';";
		echo "    }";
		echo "}";
	}

	echo "}";
	echo "</script>";

	if ($matchedaccount === null && $matchedemail === null && $matchedphone === null) {
		// 執行注冊會員寫入數據庫的操作
		// 將相應的數據插入到數據庫中
		$insertSql = "INSERT INTO member (account, password, name, email, phone, address) VALUES (?, ?, ?, ?, ?, ?)";
		$insertStmt = $db_link->prepare($insertSql);
		$insertStmt->bind_param("ssssss", $account, $password, $name, $email, $phone, $address);
		$insertStmt->execute();

		echo "<script>alert('加入會員成功');</script>";
		echo "<script>setTimeout(function() { window.location.href = 'login.php'; }, 1);</script>";
		exit();
	}
}
?>




<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
	<link rel="stylesheet" href="css.css">
	<script src="http://code.jquery.com/jquery.min.js"></script>
	<script src="script.js"></script>
	<script src="https://kit.fontawesome.com/2b15d4fecb.js" crossorigin="anonymous"></script>
	<title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 註冊會員</title>
	<style>
		.errormessage_account,
		.errormessage_account_empty,
		.errormessage_password_empty,
		.errormessage_name_empty,
		.errormessage_email,
		.errormessage_email_empty,
		.errormessage_phone,
		.errormessage_phone_empty,
		.errormessage_address_empty {
			display: none;
			/* 默認隱藏 */
			height: 30px;
			/* 設置高度 */
			line-height: 30px;
			/* 設置行高，使文本垂直居中 */
			text-align: center;
			/* 使文本水平居中 */
			margin: 0 auto;
			/* 將左右外邊距均分 達到置中效果 */
		}

		.warning_pic {
			width: 14px;
			height: 14px;
			margin-right: 4px;
			vertical-align: middle;
		}

		.warning_word {
			vertical-align: middle;
			font-size: 14px;
			color: #DC2900;
			letter-spacing: 1.5px;
			vertical-align: baseline;
		}

		#signin {
			max-width: 500px;
			margin: 80px auto;
		}

		#signin .container {
			display: flex;
			justify-content: center;
		}

		.signin {
			margin: 0;
			padding: 0;
			border: 0;
			font: inherit;
			vertical-align: baseline;
			text-align: center;
			margin-bottom: 8px;
		}

		.login_signin {
			display: flex;
			justify-content: space-between;
			align-items: center;
			padding: 8px;
		}

		.signin_word_left {
			margin: 0;
			padding: 0;
			border: 0;
			flex: none;
			font-size: 24px;
			font-weight: 600;
			line-height: 36px;
			color: #1d1d1d;
			letter-spacing: .9px;
		}

		.signin_word_right {
			margin: 0;
			padding: 0;
			border: 0;
			color: #7d7d7d;
			font-size: 16px;
		}

		.login_link {
			text-decoration: none;
			color: #FF44FF;
		}

		.login_link:hover {
			text-decoration: underline;
		}

		.signin_div {
			margin-top: 10px;
			padding: 36px;
			border-radius: 4px;
			box-shadow: 0 4px 10px 0 rgba(0, 0, 0, .2), 0 0 6px -1px rgba(0, 0, 0, .2);
			display: flex;
			flex-direction: column;
			background-color: #fff;
		}

		form {
			margin: 0;
			padding: 0;
			border: 0;
			font: inherit;
			vertical-align: baseline;
		}

		.account_div,
		.password_div,
		.name_div,
		.email_div,
		.phone_div,
		.address_div {
			display: flex;
			flex-direction: column;
			box-sizing: border-box;
			text-align: left;
			font-size: 16px;
			margin-bottom: 25px;
		}

		.password_div {
			position: relative;
		}

		.password_input {
			position: relative;
		}

		#password_eye {
			width: 35px;
			height: 35px;
			position: absolute;
			top: 60%;
			right: 5px;
			/* 距離input的右側5px */
			transform: translateY(-50%);
			cursor: pointer;
			font-size: 16px;
			background-image: url("pic/password_eyeoff.png");
			background-size: contain;
			background-repeat: no-repeat;
			background-position: center;
			background-color: transparent;
			border: 0;
		}

		.signin_input_account,
		.signin_input_password,
		.signin_input_name,
		.signin_input_email,
		.signin_input_phone,
		.signin_input_address {
			height: 46px;
			margin-top: 8px;
			max-width: 100%;
			padding: 12px 16px;
			box-sizing: border-box;
			font-style: normal;
			font-weight: 400;
			font-size: 14px;
			line-height: 22px;
			color: #1d1d1d;
			letter-spacing: .6px;
			text-align: left;
			background: #fff;
			border: 1px solid;
			border-color: #c3c3c3;
			border-radius: 4px;
			transition: .15s ease-in-out;
			transition-property: color, background-color, border;
			box-shadow: none;
		}

		.signin_input_password {
			padding-right: 40px;
			/* 給輸入框右側預留足夠的空間以容納按鈕 */
			width: 100%;
			/* 讓輸入框填充其容器的寬度 */
		}

		.signin_input_account:focus,
		.signin_input_password:focus,
		.signin_input_name:focus,
		.signin_input_email:focus,
		.signin_input_phone:focus,
		.signin_input_address:focus {
			border-color: #FF44FF;
			outline: none;
		}

		.signin_button {
			border: 0;
			padding: 16px 24px;
			font-size: 16px;
			line-height: 24px;
			width: 100%;
			background-color: #ff8a07;
			color: #fff;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			box-sizing: border-box;
			border-radius: 4px;
			/* 邊框的圓角 */
			font-weight: 600;
			letter-spacing: .6px;
			text-transform: none;
			text-decoration: none;
			text-align: center;
			vertical-align: middle;
			box-shadow: none;
			user-select: none;
		}

		.signin_button:hover {
			filter: brightness(0.9);
		}

		.terms {
			padding: 20px 20px 0 20px;
		}

		.terms_link {
			display: inline-block;
			text-decoration: none;
			font-weight: 600;
			margin: 0 4px;
		}

		.signin_middle_line {
			display: flex;
			text-align: center;
			align-items: center;
			margin-top: 20px;
			margin-bottom: 12px;
			color: #6e6e6e;
		}

		.signin_middle_line::after,
		.signin_middle_line::before {
			content: '';
			height: 1px;
			width: 100%;
			background-color: #6e6e6e;
		}

		.signin_middle_line::before {
			margin-right: 20px;
		}

		.signin_middle_line::after {
			margin-left: 20px;
		}

		.signin_middle_line .rt-note {
			display: inline-block;
			vertical-align: middle;
		}

		.signin_social_icon {
			margin: 0;
			display: flex;
			justify-content: space-between;
		}

		.signin_social_icon .col-4 {
			padding: 9px 15px;
			width: 31%;
			border: 1px solid #c3c3c3;
			border-radius: 4px;
			cursor: pointer;
		}

		.social_button {
			background-color: #fff;
			color: #6e6e6e;
			box-shadow: none;
			display: inline-flex;
			justify-content: center;
			align-items: center;
			overflow: visible;
			font-style: normal;
			font-weight: 600;
			font-size: 15px;
			line-height: 22px;
			letter-spacing: .6px;
			text-transform: none;
			text-decoration: none;
			text-align: center;
			vertical-align: middle;
			user-select: none;
			-webkit-tap-highlight-color: transparent;
		}

		.signin_social_icon .col-4:hover {
			border-color: #8d8d8d;
			outline: none;
		}

		.social_icon {
			margin: 0;
			padding: 0;
			border: 0;
			height: 24px;
			width: 24px;
			vertical-align: -8.62px;
			display: inline-block;
			background-size: contain;
			background-position: center center;
			background-repeat: no-repeat;
			margin-right: 4px !important;
			font: inherit;
		}

		@media only screen and (max-width: 500px) {
			.signin_social_icon .col-4 {
				width: 100%;
				margin-top: 10px;
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

	<section id="signin">
		<div class="container">
			<div class="row">
				<div class="col-12">
					<div class="signin">
						<div class="login_signin">
							<span class="signin_word_left">註冊會員</span>
							<span class="signin_word_right">已經有帳號？請<a href="login.php" class="login_link">登入</a></span>
						</div>


						<div class="signin_div">
							<form method="post" action="#">
								<div class="account_div">
									<label for="account">帳號 *</label>
									<input type="text" name="account" id="account" placeholder="4~24字元的英數字組合" autocomplete="username" class="signin_input_account" value="<?php echo isset($_POST['account']) ? htmlspecialchars($_POST['account']) : ''; ?>">
									<div class="errormessage_account" id="errormessage_account">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此帳號已被使用!</span>
									</div>
									<div class="errormessage_account_empty" id="errormessage_account_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>

								<div class="password_div">
									<label for="password">密碼 *</label>
									<div class="password_input">
										<input type="password" name="password" id="password" placeholder="6~15字元的英數字組合，必須包含大寫英文字母" autocomplete="new-password" class="signin_input_password">
										<button id="password_eye" type="button" aria-label="切換密碼顯示"></button>
									</div>
									<div class="errormessage_password_empty" id="errormessage_password_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>

								<div class="name_div">
									<label for="name">姓名 *</label>
									<input type="text" name="name" id="name" placeholder="請輸入您的姓名" class="signin_input_name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
									<div class="errormessage_name_empty" id="errormessage_name_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>

								<div class="email_div">
									<label for="email">Email *</label>
									<input type="email" name="email" id="email" placeholder="請輸入您的Email" class="signin_input_email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
									<div class="errormessage_email" id="errormessage_email">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此Email已被使用!</span>
									</div>
									<div class="errormessage_email_empty" id="errormessage_email_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>

								<div class="phone_div">
									<label for="phone">電話 *</label>
									<input type="tel" name="phone" id="phone" placeholder="請輸入您的電話號碼" class="signin_input_phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>">
									<div class="errormessage_phone" id="errormessage_phone">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此電話已被使用!</span>
									</div>
									<div class="errormessage_phone_empty" id="errormessage_phone_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>

								<div class="address_div">
									<label for="address">地址 *</label>
									<input type="text" name="address" id="address" placeholder="請輸入您的地址" class="signin_input_address" value="<?php echo isset($_POST['address']) ? htmlspecialchars($_POST['address']) : ''; ?>">
									<div class="errormessage_address_empty" id="errormessage_address_empty">
										<img src="pic/warning.png" class="warning_pic">
										<span class="warning_word">此欄位不能為空!</span>
									</div>
								</div>


								<button type="submit" class="signin_button">註冊</button>
							</form>
							<div class="terms">
								點擊註冊，即表示您已閱讀並同意 花顏巧語 之
								<a href="#" class="terms_link">會員條款 與 客戶隱私權條款</a>
							</div>

							<div class="signin_middle_line">
								<span class="rt-note" style="font-size: 12px;">快</span>
								<span class="rt-note" style="font-size: 12px;">速</span>
								<span class="rt-note" style="font-size: 12px;">註</span>
								<span class="rt-note" style="font-size: 12px;">冊</span>
							</div>

							<div class="row signin_social_icon">
								<div class="col-4">
									<a href="#" class="social_button"><img src="pic/fb.png" class="social_icon">Facebook</a>
								</div>
								<div class="col-4">
									<a href="#" class="social_button"><img src="pic/google.png" class="social_icon">Google</a>
								</div>
								<div class="col-4">
									<a href="#" class="social_button"><img src="pic/X.png" class="social_icon">X(twitter)</a>
								</div>
							</div>
						</div>

					</div>
				</div>
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
		const passwordInput = document.getElementById("password");
		const togglePasswordButton = document.getElementById("password_eye");

		togglePasswordButton.addEventListener("click", function() {
			if (passwordInput.type === "password") {
				passwordInput.type = "text";
				togglePasswordButton.style.backgroundImage = 'url("pic/password_eye.png")'; // 切換圖片
				togglePasswordButton.setAttribute("aria-label", "隱藏密碼"); // 更新aria-label
			} else {
				passwordInput.type = "password";
				togglePasswordButton.style.backgroundImage = 'url("pic/password_eyeoff.png")'; // 切換圖片
				togglePasswordButton.setAttribute("aria-label", "顯示密碼"); // 更新aria-label
			}
		});
	</script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>