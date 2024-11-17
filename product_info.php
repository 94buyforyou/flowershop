<?php
require_once("connMysql.php");
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
//購物車開始
require_once("mycart.php");
session_start();
$cart = &$_SESSION['cart']; // 將購物車的值設定為 Session
if (!is_object($cart))
    $cart = new myCart();

//繫結產品資料
$query_RecProduct = "SELECT * FROM product WHERE productid=?";
$stmt = $db_link->prepare($query_RecProduct);
$stmt->bind_param("i", $_GET["id"]);
$stmt->execute();
$RecProduct = $stmt->get_result();
$row_RecProduct = $RecProduct->fetch_assoc();
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();

// 從 $RecCategory 中獲取分類名稱和id
$categoryID = "";
$categoryName = "";
while ($row = $RecCategory->fetch_assoc()) {
    if ($row["categoryid"] == $row_RecProduct["categoryid"]) {
        $categoryID = $row["categoryid"];
        $categoryName = $row["categoryname"];
        break;
    }
}


// 重置 $RecCategory 的指標
$RecCategory->data_seek(0);
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
    <title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 商品詳情</title>
    <style>
        .success_message,
        .error_message {
            border-radius: 10px 10px 10px 10px;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            color: #155724;
            padding: 10px;
            border: 2px solid #000000;
            z-index: 9999;
            width: 65%;
            height: 170px;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            max-width: 700px;
            /* 限制最大寬度，這裡可以根據實際需求調整 */
            margin: 0 auto;
            /* 水平置中 */
            justify-content: center;
        }

        .success_message span,
        .error_message span {
            margin: 20px auto;
            /* 水平置中 */
        }

        .success_message_inner {
            display: flex;
            justify-content: space-around;
            /* 按鈕左右間距相等 */
            width: 100%;
            /* 充滿父元素的寬度 */
            box-sizing: border-box;
            /* 避免寬度溢出 */
        }

        .button4,
        .button5 {
            box-sizing: border-box;
            width: 20%;
            /* 按鈕寬度為父元素的一半，減去間隔寬度 */
            min-width: 100px;
            height: 40px;
            font-size: 14px;
            color: #fff;
            text-align: center;
        }

        .button4 {
            background-color: #525263;
            border: 0;
        }

        .button4:hover {
            background-color: #3b3b47;
            border-color: #363642;
        }

        .button5 {
            background-color: #DE5D50;
            border: 0;
        }

        .button5:hover {
            background-color: #d33828;
            border-color: #cb3526;
        }

        .message_close {
            width: 15px;
            height: 15px;
            position: absolute;
            right: 15px;
            top: 15px;
            cursor: pointer;
        }

        .catalog {
            margin-top: 30px;
            margin-bottom: 30px;
        }

        .catalog .col-10.offset-1 {
            padding: 0;
            font-size: 18px;
        }

        .catalog .col-10.offset-1 a {
            color: #000000;
            text-decoration: none;
            border-bottom: 1px solid #000000;
            padding-bottom: 1px;
        }

        .catalog .col-10.offset-1 a:hover {
            border: 0;
            transition: transform 0.2s;
        }

        #info1 {
            display: block;
        }

        #info2 {
            display: none;
        }

        #info1 .col-5,
        #info2 .col-10 {
            background-color: #fff;
        }

        #info1 .col-5.p_pic {
            padding-left: 50px;
            padding-top: 50px;
            padding-bottom: 50px;
            border-radius: 15px 0 0 15px;
            box-shadow: 0px 4px 0px rgba(0, 0, 0, 0.2);
        }

        #info1 .col-5.p_info {
            padding-top: 50px;
            padding-left: 50px;
            border-radius: 0 15px 15px 0;
            box-shadow: 0px 4px 0px rgba(0, 0, 0, 0.2);
        }

        #info2 .col-10.p_pic {
            padding: 10px;
            border-radius: 15px 15px 0 0;
        }

        #info2 .col-10.p_info {
            padding: 10px;
            border-radius: 0 0 15px 15px;
            box-shadow: 0px 4px 0px rgba(0, 0, 0, 0.2);
        }

        #info2 .product_image {
            width: 80%;
            margin: 30px auto;
        }

        #info2 .product_info {
            width: 80%;
            margin: 0 auto 30px auto;
        }

        .div_name {
            font-family: "微軟正黑體";
            font-size: 25px;
            margin: 0;
            padding: 0;
            color: #75293D;
            font-weight: bold;
        }

        .div_price {
            padding-top: 16px;
            padding-bottom: 16px;
            color: #75293D;
        }

        .label_count {
            vertical-align: middle;
            margin-right: 5px;
            color: #75293D;
        }

        .button1 {
            width: 50%;
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

        .describe {
            width: 80%;
        }

        #info2 .describe {
            margin: 30px auto;
        }

        .describe span {
            display: block;
            font-size: 20px;
            color: #75293D;
            font-weight: bold;
        }

        .description {
            font-size: 16px;
        }

        footer {
            margin-top: 80px;
        }

        @media only screen and (max-width: 991px) {
            #info1 {
                display: none;
            }

            #info2 {
                display: block;
            }
        }
    </style>
</head>

<body>
    <?php
    // 檢查加入購物車成功訊息
    if (isset($_SESSION['success_message'])) {
        $successMessage = $_SESSION['success_message']; ?>

        <div class="success_message" id="success_message">
            <div>
                <img src="pic/delete.png" class="message_close" onclick="close_message()">
            </div>
            <span><?php echo $successMessage; ?></span>
            <div class="success_message_inner">
                <button class="button4" onclick="close_message()">繼續購物</button>
                <button class="button5" onclick="window.location.href='cart.php'">前往購物車</button>
            </div>
        </div>
    <?php
        unset($_SESSION['success_message']);
    }

    // 檢查加入購物車失敗訊息
    if (isset($_SESSION['error_message'])) {
        $errorMessage = $_SESSION['error_message']; ?>

        <div class="error_message" id="error_message">
            <div>
                <img src="pic/delete.png" class="message_close" onclick="close_message()">
            </div>
            <span><?php echo $errorMessage; ?></span>
            <div class="error_message_inner">
                <button class="button4" onclick="close_message()">繼續購物</button>
                <button class="button5" onclick="window.location.href='cart.php'">前往購物車</button>
            </div>
        </div>

    <?php unset($_SESSION['error_message']);
    }
    ?>



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

    <section class="catalog">
        <div class="container">
            <div class="row">
                <div class="col-10 offset-1">
                    <span>
                        <a href="index.php">首頁</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="product.php">所有商品</a> &nbsp;&nbsp;|&nbsp;&nbsp;<a href="product.php?cid=<?php echo $categoryID; ?>"><?php echo $categoryName ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
                        <?php echo $row_RecProduct["productname"]; ?>
                    </span>
                </div>
            </div>
        </div>
    </section>

    <section id="info1">
        <div class="container">
            <div class="row u-mb24 justify-content-center">
                <div class="col-5 p_pic">
                    <div class="product_image"> <!-- 左邊圖片 -->
                        <img src="productimg/<?php echo $row_RecProduct["productname"]; ?>.jpg">
                    </div>
                </div>
                <div class="col-5 p_info">
                    <div class="product_info"> <!-- 右邊資訊 -->
                        <div>
                            <div class="div_name">
                                <?php echo $row_RecProduct["productname"]; ?>
                            </div>
                            <?php if ($row_RecProduct["productid"] <= 5) { ?>
                                <div class="div_price">價格請聯繫我們</div>
                            <?php } else { ?>
                                <div class="div_price">NT$
                                    <?php echo $row_RecProduct["productprice"]; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <?php if ($row_RecProduct["productid"] > 5) { ?>
                            <form name="form3" method="post" action="add.php">
                                <input name="id" type="hidden" id="id" value="<?php echo $row_RecProduct["productid"]; ?>">
                                <input name="name" type="hidden" id="name" value="<?php echo $row_RecProduct["productname"]; ?>">
                                <input name="price" type="hidden" id="price" value="<?php echo $row_RecProduct["productprice"]; ?>">
                                <label for="qty" class="label_count">數量:</label>
                                <select name="qty" id="qty" style="width: 15%;">
                                    <?php for ($i = 1; $i <= 10; $i++) { ?> <!-- 下拉式選單 -->
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>

                                <input name="cartaction" type="hidden" id="cartaction" value="add">
                                <br>
                                <br>
                                <button type="submit" name="button1" id="submitButton" class="button1">加入購物車</button>
                            </form>
                            <br>
                        <?php } else ?>
                    </div>
                    <hr width="90%" size="1" />
                    <div class="describe">
                        <span>商品介紹</span>
                        <br>
                        <div class="description">
                            <?php echo nl2br($row_RecProduct["description"]); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="info2">
        <div class="container">
            <div class="row u-mb24 justify-content-center">
                <div class="col-10 p_pic">
                    <div class="product_image"> <!-- 左邊圖片 -->
                        <img src="productimg/<?php echo $row_RecProduct["productname"]; ?>.jpg">
                    </div>
                </div>
            </div>
            <div class="row u-mb24 justify-content-center">
                <div class="col-10 p_info">
                    <div class="product_info"> <!-- 右邊資訊 -->
                        <div>
                            <div class="div_name">
                                <?php echo $row_RecProduct["productname"]; ?>
                            </div>
                            <?php if ($row_RecProduct["productid"] <= 5) { ?>
                                <div class="div_price">價格請聯繫我們</div>
                            <?php } else { ?>
                                <div class="div_price">NT$
                                    <?php echo $row_RecProduct["productprice"]; ?>
                                </div>
                            <?php } ?>
                        </div>


                        <?php if ($row_RecProduct["productid"] > 5) { ?>
                            <form name="form3" method="post" action="add.php">
                                <input name="id" type="hidden" id="id" value="<?php echo $row_RecProduct["productid"]; ?>">
                                <input name="name" type="hidden" id="name" value="<?php echo $row_RecProduct["productname"]; ?>">
                                <input name="price" type="hidden" id="price" value="<?php echo $row_RecProduct["productprice"]; ?>">
                                <label for="qty" class="label_count">數量:</label>
                                <select name="qty" id="qty" style="width: 5rem;">
                                    <?php for ($i = 1; $i <= 10; $i++) { ?> <!-- 下拉式選單 -->
                                        <option value="<?php echo $i; ?>"><?php echo $i; ?></option>
                                    <?php } ?>
                                </select>

                                <input name="cartaction" type="hidden" id="cartaction" value="add">
                                <br>
                                <br>
                                <button type="submit" name="button1" id="submitButton" class="button1">加入購物車</button>
                            </form>
                            <br>
                        <?php } else ?>
                    </div>
                    <hr width="90%" style="margin:0 auto;" size="1" />
                    <div class="describe">
                        <span>商品介紹</span>
                        <br>
                        <div class="description">
                            <?php echo nl2br($row_RecProduct["description"]); ?>
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
        function close_message() {
            var close = document.getElementById("success_message") || document.getElementById("error_message");
            if (close) {
                close.style.display = "none";
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>
<?php
$stmt->close();
$db_link->close();
?>