<?php
session_start();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
require_once("connMysql.php");
//預設每頁筆數
$pageRow_records = 15;
//預設頁數
$num_pages = 1;
//若已經有翻頁，將頁數更新
if (isset($_GET['page'])) {
    $num_pages = $_GET['page'];
}
//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
$startRow_records = ($num_pages - 1) * $pageRow_records;
//若有分類關鍵字時未加限制顯示筆數的SQL敘述句
if (isset($_GET["cid"]) && ($_GET["cid"] != "")) {
    $query_RecProduct = "SELECT * FROM product WHERE categoryid=? ORDER BY productid ASC";
    $stmt = $db_link->prepare($query_RecProduct);
    $stmt->bind_param("i", $_GET["cid"]);
    //若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
} elseif (isset($_GET["keyword"]) && ($_GET["keyword"] != "")) {
    $query_RecProduct = "SELECT * FROM product WHERE productname LIKE ? OR description LIKE ? ORDER BY productid ASC";
    $stmt = $db_link->prepare($query_RecProduct);
    $keyword = "%" . $_GET["keyword"] . "%";
    $stmt->bind_param("ss", $keyword, $keyword);
    //若有價格區間關鍵字時未加限制顯示筆數的SQL敘述句
} elseif (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] <= $_GET["price2"])) {
    $query_RecProduct = "SELECT * FROM product WHERE productprice BETWEEN ? AND ? ORDER BY productid ASC";
    $stmt = $db_link->prepare($query_RecProduct);
    $stmt->bind_param("ii", $_GET["price1"], $_GET["price1"]);
    //預設狀況下未加限制顯示筆數的SQL敘述句
} else {
    $query_RecProduct = "SELECT * FROM product ORDER BY productid ASC";
    $stmt = $db_link->prepare($query_RecProduct);
}
$stmt->execute();
//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_RecProduct 中
$all_RecProduct = $stmt->get_result();
//計算總筆數
$total_records = $all_RecProduct->num_rows;
//計算總頁數=(總筆數/每頁筆數)後無條件進位。
$total_pages = ceil($total_records / $pageRow_records);
//繫結產品目錄資料
$query_RecCategory = "SELECT category.categoryid, category.categoryname, category.categorysort, count(product.productid) as productNum FROM category LEFT JOIN product ON category.categoryid = product.categoryid GROUP BY category.categoryid, category.categoryname, category.categorysort ORDER BY category.categorysort ASC";
$RecCategory = $db_link->query($query_RecCategory);
//計算資料總筆數
$query_RecTotal = "SELECT count(productid) as totalNum FROM product";
$RecTotal = $db_link->query($query_RecTotal);
$row_RecTotal = $RecTotal->fetch_assoc();
//返回 URL 參數
function keepURL()
{
    $keepURL = "";
    if (isset($_GET["keyword"])) $keepURL .= "&keyword=" . urlencode($_GET["keyword"]);
    if (isset($_GET["price1"])) $keepURL .= "&price1=" . $_GET["price1"];
    if (isset($_GET["price2"])) $keepURL .= "&price2=" . $_GET["price2"];
    if (isset($_GET["cid"])) $keepURL .= "&cid=" . $_GET["cid"];
    return $keepURL;
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
    <title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店 | 所有商品</title>
    <style>
        a {
            text-decoration: none;
        }

        #list1 .col-2,
        #list2 .col-2 {
            flex: 0 0 20%;
            max-width: 20%;
        }

        #list1 .col-10,
        #list2 .col-10 {
            flex: 0 0 80%;
            max-width: 80%;
        }

        .container.container--lg>.row {
            margin: 0 auto;
            max-width: 1256px;
        }

        #list1 {
            margin-top: 50px;
            min-width: 100%;
        }

        .search {
            width: 100%;
        }

        #list1 .categorybox {
            border: 1px solid #656565;
            padding: 10px;
            background-color: #F0F0F0;
        }

        #list1 .categorybox p {
            margin-bottom: 10px;
        }

        .heading {
            color: #0066CC;
            line-height: 150%;
            font-weight: bold;
        }

        #list1 .categorybox ul {
            list-style-type: none;
            margin-left: -20px;
        }

        .categorybox li {
            font-size: 15px;
            background-repeat: no-repeat;
            padding-left: 20px;
        }

        #list1 .categorybox li a:hover {
            background-color: #CCCC00;
            color: #FFFFFF;
        }

        #list2 {
            margin-top: 30px;
            min-width: 100%;
            padding: 0;
        }

        #list2 .banner4 {
            padding: 10px;
        }

        #list2 .categorybox {
            background-color: white;
        }

        #list2 .categorybox.price {
            padding: 10px 10px 10px 30px;
            border: 2px #9D9D9D solid;
            border-bottom: 0;
        }

        #list2 .categorybox.price p {
            margin-bottom: 0;
            margin-right: 35px;
        }

        #list2 .categorybox.type {
            border: 2px #9D9D9D solid;
            border-top: 1px #9D9D9D solid;
            background-color: white;
            padding: 10px 30px 0 30px;
        }

        #list2 .categorybox ul {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            /* 自适应列布局 */
            grid-auto-rows: 1fr;
            /* 自适应行高 */
            grid-gap: 10px;
            /* 设置网格间距 */
            grid-auto-flow: dense;
            /* 自动填充剩余空间 */
            width: 100%;
            justify-content: center;
            list-style-type: none;
            padding: 0;
        }

        .product_list {
            border: 0;
            margin: 0 auto;
            /* 水平居中 */
            background-color: #FFFFFF;
        }

        .subjectDiv span {
            font-size: 25px;
            font-weight: bolder;
            color: #949449;
        }

        .item_list {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            /* 自适应列布局 */
            grid-auto-rows: 1fr;
            /* 自适应行高 */
            grid-gap: 25px;
            /* 设置网格间距 */
            grid-auto-flow: dense;
            /* 自动填充剩余空间 */
            padding: 28px 10px 8px;
            width: 100%;
            justify-content: center;
        }

        .albumDiv {
            text-align: center;
        }

        .picDiv img {
            display: inline-block;
            height: auto;
            vertical-align: middle;
            width: 100%;
        }

        .navDiv {
            clear: both;
            text-align: center;
            font-family: "Courier New", Courier, monospace;
            font-size: 9pt;
            padding: 5px;
        }

        footer {
            margin-top: 50px;
        }

        @media only screen and (max-width: 500px) {
            #list1 {
                display: none;
            }

            #list2 {
                display: block;
            }

            .item_list {
                grid-template-columns: repeat(auto-fill, minmax(170px, 1fr));
            }
        }

        @media only screen and (max-width: 991px) {
            #list1 {
                display: none;
            }

            #list2 {
                display: block;
            }
        }

        @media only screen and (min-width: 992px) {
            #list1 {
                display: block;
            }

            #list2 {
                display: none;
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
                                    <a href="./practice/snake/index.html" target="_blank">貪吃蛇</a>
                                    <a href="./practice/ball/index.html" target="_blank">彈跳球</a>
                                    <a href="./practice/password/index.html" target="_blank">終極密碼</a>
                                    <a href="./practice/score/index.html" target="_blank">成績計算網站</a>
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
                                    <a href="./practice/snake/index.html" target="_blank">貪吃蛇</a>
                                    <a href="./practice/ball/index.html" target="_blank">彈跳球</a>
                                    <a href="./practice/password/index.html" target="_blank">終極密碼</a>
                                    <a href="./practice/score/index.html" target="_blank">成績計算網站</a>
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

    <div class="text-center">
        <img src="pic/banner2.png">
    </div>

    <div class="container container--lg" id="list1">
        <div class="row u-mb24">
            <div class="col-2">
                <table class="search">
                    <tr>
                        <td>
                            <div class="categorybox">
                                <p class="heading">
                                    價格
                                    <span class="smalltext">Price</span>
                                </p>

                                <form action="product.php" method="get" name="form2" id="form2">
                                    <p style="text-align: center;">
                                        <input name="price1" type="text" id="price1" value="0" size="2">
                                        -
                                        <input name="price2" type="text" id="price2" value="0" size="2">
                                        <input type="submit" id="button2" value="查詢">
                                    </p>
                                </form>
                            </div>

                            <hr width="100%" size="1" />

                            <div class="categorybox">
                                <p class="heading"> 產品分類 <span class="smalltext">Category</span></p>
                                <ul class="product_type_ul">
                                    <li>
                                        <a href="product.php">所有產品
                                            <span class="categorycount">(<?php echo $row_RecTotal["totalNum"]; ?>)</span>
                                        </a>
                                    </li>

                                    <?php while ($row_RecCategory = $RecCategory->fetch_assoc()) { ?>
                                        <li>
                                            <a href="product.php?cid=<?php echo $row_RecCategory["categoryid"]; ?>"><?php echo $row_RecCategory["categoryname"]; ?>
                                                <span class="categorycount">(<?php echo $row_RecCategory["productNum"]; ?>)</span>
                                            </a>
                                        </li>
                                    <?php } ?>
                                </ul>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="col-10 col-md-12 col-sm-12">
                <div class="row u-mb24">
                    <div class="col-12 col-md-12 col-sm-12">
                        <table class="product_list">
                            <tr>
                                <td>
                                    <table border="0" cellspacing="0" cellpadding="10">
                                        <tr valign="top">
                                            <td>
                                                <div>
                                                    <img src="pic/banner4.png">
                                                </div>
                                                <br>
                                                <div class="subjectDiv text-center">
                                                    <?php if (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] <= $_GET["price2"])) { ?>
                                                        <span>查詢結果</span>
                                                    <?php } elseif (isset($_GET["keyword"]) && ($_GET["keyword"] != "")) { ?>
                                                        <span>查詢結果</span>
                                                    <?php } else if (!isset($_GET["cid"])) { ?>
                                                        <span>所有商品</span>
                                                    <?php } else if ($_GET["cid"] == 1) { ?>
                                                        <span>主打商品</span>
                                                    <?php } else if ($_GET["cid"] == 2) { ?>
                                                        <span>經典花束</span>
                                                    <?php } else if ($_GET["cid"] == 3) { ?>
                                                        <span>精緻桌花</span>
                                                    <?php } else if ($_GET["cid"] == 4) { ?>
                                                        <span>現代手提花盒</span>
                                                    <?php } else if ($_GET["cid"] == 5) { ?>
                                                        <span>其他商品</span>
                                                    <?php } ?>
                                                </div>
                                                <div class="item_list">
                                                    <?php
                                                    //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
                                                    $query_limit_RecProduct = $query_RecProduct . " LIMIT {$startRow_records}, {$pageRow_records}";
                                                    //以加上限制顯示筆數的SQL敘述句查詢資料到 $RecProduct 中
                                                    $stmt = $db_link->prepare($query_limit_RecProduct);
                                                    //若有分類關鍵字時未加限制顯示筆數的SQL敘述句
                                                    if (isset($_GET["cid"]) && ($_GET["cid"] != "")) {
                                                        $stmt->bind_param("i", $_GET["cid"]);
                                                        //若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
                                                    } elseif (isset($_GET["keyword"]) && ($_GET["keyword"] != "")) {
                                                        $keyword = "%" . $_GET["keyword"] . "%";
                                                        $stmt->bind_param("ss", $keyword, $keyword);
                                                        //若有價格區間關鍵字時未加限制顯示筆數的SQL敘述句
                                                    } elseif (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] > $_GET["price2"])) {
                                                        echo '<script>alert("您輸入的金額範圍有誤，請重新輸入");</script>';
                                                    } elseif (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] <= $_GET["price2"])) {
                                                        $stmt->bind_param("ii", $_GET["price1"], $_GET["price2"]);
                                                    }
                                                    $stmt->execute();
                                                    $RecProduct = $stmt->get_result();
                                                    while ($row_RecProduct = $RecProduct->fetch_assoc()) {
                                                    ?>

                                                        <div class="albumDiv">
                                                            <div class="picDiv">
                                                                <a href="product_info.php?id=<?php echo $row_RecProduct["productid"]; ?>" rel="noreferrer noopenner">
                                                                    <?php if ($row_RecProduct["productimages"] == "") { ?>
                                                                        <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                                                                    <?php } else { ?>
                                                                        <img src="productimg/<?php echo $row_RecProduct["productname"]; ?>.jpg" border="0">
                                                                    <?php } ?>
                                                                </a>
                                                            </div>
                                                            <div class="albuminfo">
                                                                <a href="product_info.php?id=<?php echo $row_RecProduct["productid"]; ?>"><?php echo $row_RecProduct["productname"]; ?>

                                                                </a>
                                                                <br>
                                                                <br>
                                                                <?php if ($row_RecProduct["productid"] <= 5) { ?>
                                                                    <span style="color: #FF0000;">價格請聯繫我們</span>
                                                                <?php } else { ?>
                                                                    <span class="smalltext">NT$ </span>
                                                                    <span class="redword">
                                                                        <?php echo $row_RecProduct["productprice"]; ?>
                                                                    </span>
                                                                    <span class="smalltext"> 元</span>
                                                                <?php } ?>
                                                            </div>
                                                        </div>

                                                    <?php } ?>
                                                </div>

                                                <br>
                                                <div class="navDiv">
                                                    <?php if ($num_pages > 1) { // 若不是第一頁則顯示 
                                                    ?>
                                                        <a href="?page=1<?php echo keepURL(); ?>">|&lt;</a> <a href="?page=<?php echo $num_pages - 1; ?><?php echo keepURL(); ?>">&lt;&lt;</a>
                                                    <?php } else { ?>
                                                        |&lt; &lt;&lt;
                                                    <?php } ?>
                                                    <?php
                                                    for ($i = 1; $i <= $total_pages; $i++) {
                                                        if ($i == $num_pages) {
                                                            echo $i . " ";
                                                        } else {
                                                            $urlstr = keepURL();
                                                            echo "<a href=\"?page=$i$urlstr\">$i</a> ";
                                                        }
                                                    }
                                                    ?>
                                                    <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 
                                                    ?>
                                                        <a href="?page=<?php echo $num_pages + 1; ?><?php echo keepURL(); ?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages; ?><?php echo keepURL(); ?>">&gt;|</a>
                                                    <?php } else { ?>
                                                        &gt;&gt; &gt;|
                                                    <?php } ?>
                                                </div>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container container--lg" id="list2">
        <div class="row">
            <div class="col-12">
                <div class="categorybox price">
                    <table>
                        <tr>
                            <th>
                                <p class="heading">價格</p>
                            </th>
                            <td>
                                <form action="product.php" method="get" name="form2" id="form2">
                                    <p>
                                        <input name="price1" type="text" id="price1" value="0" size="5">
                                        -
                                        <input name="price2" type="text" id="price2" value="0" size="5">
                                        <input type="submit" id="button2" value="查詢">
                                    </p>
                                </form>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="categorybox type">
                    <p class="heading">產品分類</p>
                    <ul class="product_type_ul">
                        <li>
                            <a href="product.php">
                                所有產品
                                <span class="categorycount">(<?php echo $row_RecTotal["totalNum"]; ?>)</span>
                            </a>
                        </li>
                        <?php mysqli_data_seek($RecCategory, 0); ?>
                        <?php while ($row_RecCategory = $RecCategory->fetch_assoc()) { ?>
                            <li>
                                <a href="product.php?cid=<?php echo $row_RecCategory["categoryid"]; ?>"><?php echo $row_RecCategory["categoryname"]; ?>
                                    <span class="categorycount">(<?php echo $row_RecCategory["productNum"]; ?>)</span>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
                </div>
            </div>
        </div>
        <br>
        <div class="row u-mb24">
            <div class="col-12 col-md-12 col-sm-12">
                <table class="product_list">
                    <tr>
                        <td>
                            <table border="0" cellspacing="0" cellpadding="0">
                                <tr valign="top">
                                    <td>
                                        <div class="banner4">
                                            <img src="pic/banner4.png">
                                        </div>
                                        <br>
                                        <div class="subjectDiv text-center">
                                            <?php if (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] <= $_GET["price2"])) { ?>
                                                <span>查詢結果</span>
                                            <?php } elseif (isset($_GET["keyword"]) && ($_GET["keyword"] != "")) { ?>
                                                <span>查詢結果</span>
                                            <?php } else if (!isset($_GET["cid"])) { ?>
                                                <span>所有商品</span>
                                            <?php } else if ($_GET["cid"] == 1) { ?>
                                                <span>主打商品</span>
                                            <?php } else if ($_GET["cid"] == 2) { ?>
                                                <span>經典花束</span>
                                            <?php } else if ($_GET["cid"] == 3) { ?>
                                                <span>精緻桌花</span>
                                            <?php } else if ($_GET["cid"] == 4) { ?>
                                                <span>現代手提花盒</span>
                                            <?php } else if ($_GET["cid"] == 5) { ?>
                                                <span>其他商品</span>
                                            <?php } ?>
                                        </div>
                                        <div class="item_list">
                                            <?php
                                            //加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
                                            $query_limit_RecProduct = $query_RecProduct . " LIMIT {$startRow_records}, {$pageRow_records}";
                                            //以加上限制顯示筆數的SQL敘述句查詢資料到 $RecProduct 中
                                            $stmt = $db_link->prepare($query_limit_RecProduct);
                                            //若有分類關鍵字時未加限制顯示筆數的SQL敘述句
                                            if (isset($_GET["cid"]) && ($_GET["cid"] != "")) {
                                                $stmt->bind_param("i", $_GET["cid"]);
                                                //若有搜尋關鍵字時未加限制顯示筆數的SQL敘述句
                                            } elseif (isset($_GET["keyword"]) && ($_GET["keyword"] != "")) {
                                                $keyword = "%" . $_GET["keyword"] . "%";
                                                $stmt->bind_param("ss", $keyword, $keyword);
                                                //若有價格區間關鍵字時未加限制顯示筆數的SQL敘述句
                                            } elseif (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] > $_GET["price2"])) {
                                                echo '<script>alert("您輸入的金額範圍有誤，請重新輸入");</script>';
                                            } elseif (isset($_GET["price1"]) && isset($_GET["price2"]) && ($_GET["price1"] <= $_GET["price2"])) {
                                                $stmt->bind_param("ii", $_GET["price1"], $_GET["price2"]);
                                            }
                                            $stmt->execute();
                                            $RecProduct = $stmt->get_result();
                                            while ($row_RecProduct = $RecProduct->fetch_assoc()) {
                                            ?>

                                                <div class="albumDiv">
                                                    <div class="picDiv">
                                                        <a href="product_info.php?id=<?php echo $row_RecProduct["productid"]; ?>" rel="noreferrer noopenner">
                                                            <?php if ($row_RecProduct["productimages"] == "") { ?>
                                                                <img src="images/nopic.png" alt="暫無圖片" width="120" height="120" border="0" />
                                                            <?php } else { ?>
                                                                <img src="productimg/<?php echo $row_RecProduct["productname"]; ?>.jpg" border="0">
                                                            <?php } ?>
                                                        </a>
                                                    </div>
                                                    <div class="albuminfo">
                                                        <a href="product_info.php?id=<?php echo $row_RecProduct["productid"]; ?>"><?php echo $row_RecProduct["productname"]; ?>

                                                        </a>
                                                        <br>
                                                        <br>
                                                        <?php if ($row_RecProduct["productid"] <= 5) { ?>
                                                            <span style="color: #FF0000;">價格請聯繫我們</span>
                                                        <?php } else { ?>
                                                            <span class="smalltext">NT$ </span>
                                                            <span class="redword">
                                                                <?php echo $row_RecProduct["productprice"]; ?>
                                                            </span>
                                                            <span class="smalltext"> 元</span>
                                                        <?php } ?>
                                                    </div>
                                                </div>

                                            <?php } ?>
                                        </div>

                                        <br>
                                        <div class="navDiv">
                                            <?php if ($num_pages > 1) { // 若不是第一頁則顯示 
                                            ?>
                                                <a href="?page=1<?php echo keepURL(); ?>">|&lt;</a> <a href="?page=<?php echo $num_pages - 1; ?><?php echo keepURL(); ?>">&lt;&lt;</a>
                                            <?php } else { ?>
                                                |&lt; &lt;&lt;
                                            <?php } ?>
                                            <?php
                                            for ($i = 1; $i <= $total_pages; $i++) {
                                                if ($i == $num_pages) {
                                                    echo $i . " ";
                                                } else {
                                                    $urlstr = keepURL();
                                                    echo "<a href=\"?page=$i$urlstr\">$i</a> ";
                                                }
                                            }
                                            ?>
                                            <?php if ($num_pages < $total_pages) { // 若不是最後一頁則顯示 
                                            ?>
                                                <a href="?page=<?php echo $num_pages + 1; ?><?php echo keepURL(); ?>">&gt;&gt;</a> <a href="?page=<?php echo $total_pages; ?><?php echo keepURL(); ?>">&gt;|</a>
                                            <?php } else { ?>
                                                &gt;&gt; &gt;|
                                            <?php } ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>