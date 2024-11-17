<?php
session_start();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
require_once("connMysql.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 獲取用戶提交的數據
    $account = $_POST['account'];
    $password = $_POST['password'];

    $sql = $db_link->prepare("select * from member where account=? and password=?");
    $sql->bind_param("ss", $_POST['account'], $_POST['password']);
    $sql->execute();

    $result = $sql->get_result();

    $matchedRow = null; // 設定matchedRow為空值

    while ($row = $result->fetch_assoc()) {
        if ($account === $row["account"] && $password === $row["password"]) {
            $matchedRow = $row; // 把吻合的數據存到matchedRow
            break;
        }
    }

    if ($matchedRow !== null) {
        // 若matchedRow不為空值
        $_SESSION["member"] = [
            "id" => $matchedRow["id"],
            "name" => $matchedRow["name"],
            "account" => $matchedRow["account"],
            "password" => $matchedRow["password"]
        ];
        header("Location: index.php");
        exit();
    } else {
        echo "<script>";
        echo "window.onload = function() {";
        echo "    var errorMessage = document.getElementById('error_message');";
        echo "    if (errorMessage) {";
        echo "        errorMessage.style.display = 'block';";
        echo "    }";
        echo "}";
        echo "</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css.css">
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="script.js"></script>
    <script src="https://kit.fontawesome.com/2b15d4fecb.js" crossorigin="anonymous"></script>
    <title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 登入</title>
    <style>
        body {
            overflow-y: scroll;
            /* 顯示垂直捲動條 */
        }

        #login {
            max-width: 500px;
            margin: 80px auto;
        }

        #login .container {
            display: flex;
            justify-content: center;
        }

        .login {
            margin: 0;
            padding: 0;
            border: 0;
            font: inherit;
            vertical-align: baseline;
            text-align: center;
            border-bottom: none;
        }

        .login_signin {
            margin: 0;
            border: 0;
            display: flex;
            justify-content: space-between;
            /* 讓登入和註冊兩個字位於兩端 */
            align-items: center;
            padding: 8px;
        }

        .login_word {
            margin: 0;
            padding: 0;
            border: 0;
            flex: none;
            font-size: 24px;
            font-weight: 600;
            line-height: 36px;
            color: #1d1d1d;
            letter-spacing: 1px;
        }

        .signin_word {
            margin: 0;
            padding: 0;
            border: 0;
            color: #7d7d7d;
            font-size: 16px;
        }

        .login_div {
            padding: 36px;
            border-radius: 4px;
            box-shadow: 0 4px 10px 0 rgba(0, 0, 0, .2), 0 0 6px -1px rgba(0, 0, 0, .2);
            display: flex;
            flex-direction: column;
            background-color: #fff;
            margin-top: 10px;
        }

        form {
            margin: 0;
            padding: 0;
            border: 0;
            font: inherit;
            vertical-align: baseline;
        }

        .error_message {
            justify-content: center;
            background-color: #fef0ec;
            box-shadow: 0 2px 6px 0 rgba(0, 0, 0, .1);
            text-align: left;
            padding: 16px;
            margin-bottom: 24px;
            font-size: 14px;
            display: none;
            /* 默認隱藏 */
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

        .username_div {
            position: relative;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            text-align: left;
            font-size: 16px;
        }

        .password_div {
            position: relative;
            display: flex;
            flex-direction: column;
            box-sizing: border-box;
            margin-top: 25px;
            text-align: left;
            font-size: 16px;
        }

        .input_login {
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
            border: 1px solid #c3c3c3;
            border-radius: 4px;
            transition: .15s ease-in-out;
            transition-property: color, background-color, border;
            box-shadow: none;
        }

        .input_login:focus {
            border-color: #FF44FF;
            outline: none;
        }

        #password_eye {
            cursor: pointer;
            width: 35px;
            height: 35px;
            position: absolute;
            top: 70%;
            right: 10px;
            transform: translateY(-50%);
        }

        .checkbox {
            display: flex;
            justify-content: space-between;
            padding: 15px 0 15px 0;
            align-items: center;
        }

        .check {
            display: flex;
            align-items: center;
        }

        .check input {
            margin-right: 5px;
        }

        .remember {
            font-size: 14px;
            line-height: 1.5;
            text-decoration: none;
        }

        .forget_password {
            font-size: 14px;
            line-height: 1.5;
            color: #3679e1;
            text-decoration: none;
        }

        .login_button {
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

        .login_button:hover {
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

        .lgsi {
            text-decoration: none;
            color: #FF44FF;
        }

        .lgsi:hover {
            text-decoration: underline;
        }

        .login_middle_line {
            display: flex;
            text-align: center;
            align-items: center;
            margin-top: 20px;
            margin-bottom: 12px;
            color: #6e6e6e;
        }

        .login_middle_line::after,
        .login_middle_line::before {
            content: '';
            height: 1px;
            width: 100%;
            background-color: #a4a4a4;
        }

        .login_middle_line::before {
            margin-right: 8px;
        }

        .login_middle_line::after {
            margin-left: 8px;
        }

        .login_social_icon {
            margin: 0;
            display: flex;
            justify-content: space-between;
        }

        .login_social_icon .col-4 {
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

        .login_social_icon .col-4:hover {
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
            .login_social_icon .col-4 {
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

    <section id="login">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="login">
                        <div class="login_signin">
                            <span class="login_word">登入</span>
                            <span class="signin_word">新朋友？請先<a href="signin.php" class="lgsi">註冊</a></span>
                        </div>

                        <div class="login_div">
                            <form method="post" action="#">
                                <div>
                                    <div class="error_message" id="error_message">
                                        <img src="pic/warning.png" class="warning_pic">
                                        <span class="warning_word">帳號或密碼有誤，請重新輸入!</span>
                                    </div>
                                    <div class="username_div">
                                        <label for="account">帳號</label>
                                        <input required type="text" name="account" id="account" placeholder="請輸入帳號" autocomplete="username" class="input_login">
                                    </div>
                                    <div class="password_div">
                                        <label for="password">密碼</label>
                                        <input required type="password" name="password" id="password" placeholder="請輸入密碼" autocomplete="current-password" class="input_login">
                                        <img src="pic/password_eyeoff.png" id="password_eye" alt="切換密碼顯示">
                                    </div>
                                </div>
                                <div class="checkbox">
                                    <div class="check">
                                        <input type="checkbox" id="remember" name="remember">
                                        <label for="remember" class="remember">記住我</label>
                                    </div>
                                    <a href="#" class="lgsi forget_password">忘記密碼？</a>
                                </div>

                                <button type="submit" class="login_button">登入</button>
                            </form>
                            <div class="terms">
                                登入帳號，即表示您已閱讀並同意 花顏巧語 之
                                <a href="#" class="terms_link">會員條款 與 客戶隱私權條款</a>
                            </div>

                            <div class="login_middle_line">
                                <span class="rt-note" style="font-size: 12px;">或</span>
                            </div>
                            <div class="row login_social_icon">
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
                togglePasswordButton.src = "pic/password_eye.png";
                togglePasswordButton.alt = "顯示密碼";
            } else {
                passwordInput.type = "password";
                togglePasswordButton.src = "pic/password_eyeoff.png";
                togglePasswordButton.alt = "隱藏密碼";
            }
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>