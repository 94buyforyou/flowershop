<?php
require_once("connMysql.php");
//購物車開始
include("mycart.php");
session_start();
$cart = &$_SESSION['cart']; // 將購物車的值設定為 Session
if (!is_object($cart)) $cart = new myCart();
//購物車結束
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
?>


<!DOCTYPE html>
<html lang="en">

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

        select {
            min-width: 15%;
        }

        #info {
            margin: 80px auto;
            display: grid;
            grid-auto-flow: column;
            /* 水平排列 */
            grid-gap: 50px;
            /* 設定間距 */
            display: block;
        }

        #info .row.justify-content-center {
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

        .info_inner {
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

        .info_table {
            margin-top: 32px;
            margin-bottom: 50px;
            width: 100%;
            background-color: #E1DCD9;
        }

        .info_table th {
            width: 25%;
            min-width: 25%;
        }

        .info_table th p {
            color: #303447;
            margin: 0;
        }

        .info_table tr th {
            border-bottom: dotted 1px #8F8681;
            /*虛線*/
            padding: 20px 0;
            text-align: center;
        }

        .info_table tr td {
            border-bottom: dotted 1px #8F8681;
            /*虛線*/
            padding: 20px 0;
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

        .info_table input {
            height: 40px;
            width: 60%;
            padding: 3px 12px 3px 12px;
            border: 0;
            border-color: #FFF;

        }

        .info_table input:focus {
            outline-width: 0;
            background: #fff;
            box-shadow: 0 0 0 3.5px #ADA8A6;
            transition: box-shadow 0.2s ease-in-out;
            /* 增加延遲時間為0.2秒 */
        }

        .warm {
            margin-top: 32px;
            text-align: center;
            border: 1px solid black;
            padding: 20px 20px;
        }

        .warm p {
            margin: 0;
        }

        .date_info {
            font-size: 14px;
            color: grey;
            margin-top: 5px;
            margin-bottom: 0;
        }

        .button2 {
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

        .button1 {
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

        @media only screen and (max-width: 767px) {
            .info_table input {
                width: 80%;
            }
        }

        @media only screen and (min-width: 992px) {
            .col-12.col-lg-8 {
                margin-right: 30px;
            }
        }

        @media only screen and (min-width: 768px) and (max-width: 1199px) {
            .info_table input {
                width: 70%;
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

    <section id="info">
        <div class="container">
            <div class="row justify-content-center">
                <?php if ($cart->itemcount > 0) { ?>
                    <div class="col-12 col-lg-8">
                        <form action="cartreport.php" method="post" name="cartform" id="cartform" onSubmit="return checkForm();" class="info_form">
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

                                <tr>
                                    <th style="border: none;">
                                        <p>付款方式</p>
                                    </th>
                                    <td style="border: none;">
                                        <select name="paytype" id="paytype" required>
                                            <option></option>
                                            <option value="線上刷卡">線上刷卡</option>
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
                                        <p class="date_info">如遇週日、國定(連)假日，因貨運業者規定，將於(連)假日隔日送達。</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th style="border: none;">
                                        <p>希望送達時間</p>
                                    </th>
                                    <td style="border: none;">
                                        <select required>
                                            <option></option>
                                            <option>不指定</option>
                                            <option>12時前</option>
                                            <option>12時~18時</option>
                                        </select>
                                        <p class="date_info">實際送達時間受配送路線及當日貨量、交通、天氣等情況影響，可能提早或延後，請多見諒。</p>
                                    </td>
                                </tr>
                            </table>
                        </form>
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

</body>

<script language="javascript">
    function checkForm() {
        if (document.cartform.customername.value == "") {
            alert("請填寫姓名!");
            document.cartform.customername.focus();
            return false;
        }
        if (document.cartform.customerphone.value == "") {
            alert("請填寫電話!");
            document.cartform.customerphone.focus();
            return false;
        }
        if (document.cartform.customeraddress.value == "") {
            alert("請填寫地址!");
            document.cartform.customeraddress.focus();
            return false;
        }
        return confirm('確定送出嗎？');
    }

    $(document).ready(function() {
        $('.d1').click(function() {
            $(this).toggleClass('active');
        });
    });

    var select = document.getElementById("date");
    var currentDate = new Date();
    currentDate.setDate(currentDate.getDate() + 1); // 從明天開始

    // 循環添加當前日期後的7天日期作為選項
    for (var i = 0; i < 7; i++) {
        var option = document.createElement("option");
        var date = new Date(currentDate);
        date.setDate(currentDate.getDate() + i);

        // 創建日期字符串，只包含月份和日期，並使用斜杠分隔
        var dateStr = (date.getMonth() + 1) + '/' + date.getDate();

        option.value = dateStr;
        option.text = dateStr;
        select.appendChild(option);
    }
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</html>
<?php $db_link->close(); ?>