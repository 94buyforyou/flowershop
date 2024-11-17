<?php
session_start();
// 建立資料庫連線
require_once("connMysql.php");

// 檢查是否登入
if (!isset($_SESSION['member'])) {
    header("Location: login.php");
    exit();
}

// 取得會員帳號
$account = $_SESSION['member']['account'];

// 查詢該會員的訂單資料
$orders = array(); // 保存訂單數據的數組

// 第一個查詢
$sql1 = "SELECT * FROM orders WHERE account = ?";
$stmt1 = $db_link->prepare($sql1);
$stmt1->bind_param("s", $account);
$stmt1->execute();

$result1 = $stmt1->get_result();

// 將第一個查詢結果按orderid保存到數組中
while ($row = $result1->fetch_assoc()) {
    $orderid = $row['orderid'];

    // 檢查是否已經存在該orderid的訂單數據
    if (!isset($orders[$orderid])) {
        // 如果不存在，則創建一個新的訂單數據數組
        $orders[$orderid] = array(
            'customername' => $row['customername'],
            'customeraddress' => $row['customeraddress'],
            'paytype' => $row['paytype'],
            'total' => $row['total'],
            'deliverfee' => $row['deliverfee'],
            'grandtotal' => $row['grandtotal'],
            'orderdetails' => array() // 用於保存訂單詳情的數組
        );
    }
}

// 第二個查詢
$sql2 = "SELECT * FROM orderdetail WHERE account = ?";
$stmt2 = $db_link->prepare($sql2);
$stmt2->bind_param("s", $account);
$stmt2->execute();

$result2 = $stmt2->get_result();

// 將第二個查詢結果按orderid保存到數組中
while ($row = $result2->fetch_assoc()) {
    $orderid = $row['orderid'];

    // 檢查是否已經存在該orderid的訂單數據
    if (isset($orders[$orderid])) {
        // 將訂單詳情數據保存到對應的orderid的訂單數據中
        $orders[$orderid]['orderdetails'][] = array(
            'productname' => $row['productname'],
            'quantity' => $row['quantity'],
            'subtotal' => $row['quantity'] * $row['unitprice'],
            'productid' => $row['productid']
        );
    }
}

// 關閉連接
$stmt1->close();
$stmt2->close();
$db_link->close();
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
    <title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 會員專區</title>
    <style>
        .line {
            height: 1px;
            width: 100%;
            background-color: #e7e2d2;
            color: #6e6e6e;
            margin: 0;
            /* 調整 margin 為 0 */
        }

        #productlist {
            margin-top: 80px;
        }

        .noproduct {
            background-color: transparent;
            border: 0;
        }

        .noproduct .col-8 {
            display: grid;
            justify-content: center;
            align-items: center;
        }

        .product .col-12.col-lg-10.col-xl-8 {
            background-color: #fff;
            border: 1px solid #cccccc;
            padding: 0;
        }

        .orderid {
            background-color: #eaeaea;
            display: flex;
            justify-content: space-between;
            padding: 18px 25px;
        }

        .orderid p {
            margin: 0;
            color: #262626;
            font-size: 15px;
        }

        .product_inner {
            padding: 30px 25px 0 25px;
        }

        .product_info {
            display: flex;
            grid-gap: 30px;
            margin-bottom: 30px;
        }

        .product_info_bottom p {
            font-size: 14px;
        }


        .product_info img {
            width: 150px;
        }

        .product_detail {
            padding-top: 20px;
        }

        .span_detail {
            display: block;
            margin-bottom: 20px;
            color: #262626;
        }

        .button_div {
            text-align: right;
            margin: 15px 25px;
        }

        .button1 {
            height: 30px;
            width: 175px;
            color: #333333;
            font-size: 12px;
            line-height: 30px;
            letter-spacing: 5px;
            text-align: center;
            text-decoration: none;
            border: 1px solid #333;
            border-radius: 2px;
            cursor: pointer;
            background-color: #fff;
        }

        .button1:hover {
            background-color: #f5f5f5;
        }

        .button2 {
            height: 26px;
            width: 125px;
            margin-top: 5px;
            color: #808080;
            font-size: 12px;
            line-height: 24px;
            letter-spacing: 2px;
            text-align: center;
            text-decoration: none;
            border: 1px solid #c5c5c5;
            border-radius: 2px;
            background-color: #fff;
            display: inline-block;
            cursor: pointer;
        }

        .button2:hover {
            background-color: #cccccc;
        }

        .detail {
            border-top: 1px solid #cccccc;
            padding: 20px 30px;
            background-color: #eaeaea;
        }


        .subtitle {
            margin-top: 0;
            font-weight: bold;
        }

        .grandtotal_right {
            color: #d55454;
        }

        .price_inner {
            display: flex;
            justify-content: space-between;
        }

        .price div:nth-of-type(4) {
            margin-top: 15px;
        }

        .price div:nth-of-type(4) p {
            font-weight: bold;
        }

        .price p {
            margin-top: 0;
            font-size: 14px;
        }

        @media only screen and (max-width: 575px) {
            #productlist {
                margin: 80px 10px 0 10px;
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


    <section id="productlist">
        <?php if (empty($orders)) { ?>
            <div class="noproduct">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-8">
                            <span>目前沒有訂單資料哦</span>
                            <br>
                            <button onclick="window.location.href = 'product.php'">繼續購物</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <?php
            if (!empty($orders)) {
                krsort($orders); // 按鍵名從大到小排序
                foreach ($orders as $orderid => $order) {
            ?>
                    <div class="product">
                        <div class="container">
                            <div class="row justify-content-center">
                                <div class="col-12 col-lg-10 col-xl-8">
                                    <div class="orderid">
                                        <p>訂單編號: <?php echo $orderid; ?></p>
                                        <p>狀態 : 等待出貨中</p>
                                    </div>
                                    <div>
                                        <?php foreach ($order['orderdetails'] as $detail) { ?>
                                            <div class="product_inner">
                                                <div class="product_info">
                                                    <div> <!-- 商品圖片 -->
                                                        <a href="product_info.php?id=<?php echo $detail['productid']; ?>">
                                                            <img src="productimg/<?php echo $detail['productname']; ?>.jpg">
                                                        </a>
                                                    </div>
                                                    <div class="product_detail">
                                                        <a href="product_info.php?id=<?php echo $detail['productid']; ?>" class="link">
                                                            <span class="span_detail"><?php echo $detail['productname']; ?></span> <!-- 商品名稱 -->
                                                        </a>
                                                        <span class="span_detail"><?php echo $detail['quantity']; ?> 個</span>
                                                        <span class="span_detail" style="margin-bottom: 0;"><?php echo $detail['subtotal']; ?> 元</span>
                                                    </div>
                                                </div>
                                                <div class="line"></div>
                                            </div>
                                        <?php } ?>
                                        <div class="button_div">
                                            <button class="button1">檢視訂單內容</button>
                                        </div>
                                    </div>

                                    <div class="detail">
                                        <div class="row">
                                            <div class="col-12 col-md-4">
                                                <div class="product_inner_bottom">
                                                    <p class="subtitle">寄送地址</p>
                                                    <p><?php echo $order['customername']; ?></p>
                                                    <p><?php echo $order['customeraddress']; ?></p>
                                                    <button class="button2">更改寄送地址</button>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="product_inner_bottom">
                                                    <p class="subtitle">付款方式</p>
                                                    <p><?php echo $order['paytype']; ?></p>
                                                </div>
                                            </div>
                                            <div class="col-12 col-md-4">
                                                <div class="price">
                                                    <p class="subtitle">訂單內容</p>
                                                    <div class="price_inner">
                                                        <p>商品小計</p>
                                                        <p><?php echo $order['total']; ?> 元</p>
                                                    </div>
                                                    <div class="price_inner">
                                                        <p>運費</p>
                                                        <p><?php echo $order['deliverfee']; ?> 元</p>
                                                    </div>
                                                    <div class="line" style="background-color: #bfbfbf;"></div>
                                                    <div class="price_inner">
                                                        <p>總計</p>
                                                        <p class="grandtotal_right"><?php echo $order['grandtotal']; ?> 元</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br>
                    <br>
                    <br>
            <?php }
            }
            ?>

        <?php } ?>
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
            // 隱藏所有的 detail 元素
            $('.detail').hide();

            // 使用事件委派，綁定 click 事件到共同的父元素 .product
            $('.product').on('click', '.button1', function() {
                // 找到被點擊的按鈕的對應 detail 元素
                var detail = $(this).closest('.product').find('.detail');

                // 切換 detail 元素的顯示和隱藏，以從上而下的方式
                detail.slideToggle();
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>



</body>

</html>