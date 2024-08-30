<?php
session_start();
$_SESSION['prev_page'] = $_SERVER['REQUEST_URI'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="css.css">
    <script src="http://code.jquery.com/jquery.min.js"></script>
    <script src="script.js"></script>
    <title>花顏巧語 Capturing the Essence of Beauty | 花顏巧語、乾燥花店、推薦花店、花藝教學、網路花店</title>
    <style>
        /* 箭頭的的大小 */
        .carousel-control-next-icon,
        .carousel-control-prev-icon {
            width: 3rem;
            height: 3rem;
        }

        /* 箭頭的的透明度 */
        .carousel-control-next,
        .carousel-control-prev {
            opacity: 0.8;
            width: 10%;
        }

        .carousel-control-next:hover,
        .carousel-control-prev:hover {
            opacity: 1.0;
        }

        .carousel-indicators [data-bs-target] {
            width: 50px;
            opacity: 0.7;
        }

        .carousel-indicators .active {
            opacity: 1.0;
        }

        .i1 {
            background-color: #fff;
        }

        .information {
            position: relative;
            background: linear-gradient(#b844b4a8, #ff6d9ac4);
            padding: 4rem 0 5rem;
        }

        .information::before {
            content: "";
            display: block;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url(pic/bg_kirakira.png);
            animation: infobg 40s infinite linear;
            z-index: 1;
            pointer-events: none;
            /* pointer-events: auto;
            transform: translate3d(0, 0, 0);
            backface-visibility: hidden;
            perspective: 1000px;
            display: flex;
            flex-direction: column;
            align-items: center; */
        }

        .i2 {
            content: "";
            /*display: block;*/
            position: relative;
            top: 0;
            left: 0;
        }

        @keyframes infobg {
            0% {
                background-position: 0 0;
            }

            100% {
                background-position: 0px -1676px;
            }
        }

        .col-md-12.text-center.i2 h2 {
            color: white;
            display: flex;
            justify-content: center;
            font-size: 60px;
            font-weight: 400;
            text-shadow: 2px 2px 3px #571d2c66, 2px 2px 6px #571d2c66, 2px 2px 9px #571d2c66;
            margin: 0rem 0 4rem;
            text-align: center;
        }

        .col-md-12.text-center.i2 h2 span {
            font-family: "Century Gothic", CenturyGothic, AppleGothic, Futura, "Noto Sans JP", "ヒラギノ角ゴ ProN W3", Meiryo, sans-serif;
        }

        .col-md-12.text-center.i2 h2 b {
            color: #E3007F;
            font-weight: 200;
        }

        .col-md-12.text-center.i2 h2::before {
            background-image: url(pic/kirakira_left.png);
            background-position: right center;
        }

        .col-md-12.text-center.i2 h2::before,
        .col-md-12.text-center.i2 h2::after {
            content: "";
            display: inline-block;
            width: 1.3em;
            background-size: contain;
            background-repeat: no-repeat;
        }

        ::before,
        ::after {
            text-decoration: inherit;
            vertical-align: inherit;
            box-sizing: border-box;
        }

        .col-md-12.text-center.i2 h2::after {
            background-image: url(pic/kirakira_right.png);
            background-position: left center;
        }

        .information h3 {
            color: white;
            display: flex;
            justify-content: center;
            font-size: 40px;
            font-weight: 400;
            margin: 0;
            line-height: 1.2;
            box-sizing: border-box;
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            text-shadow: 2px 2px 3px #571d2c66, 2px 2px 6px #571d2c66, 2px 2px 9px #571d2c66;
            font-family: "Century Gothic", CenturyGothic, AppleGothic, Futura, "Noto Sans JP", "ヒラギノ角ゴ ProN W3", Meiryo, sans-serif;
            margin: 0rem 0 4rem;
            text-align: center;
        }

        .i1 .col-md-3 {
            width: 31.5em;
        }

        .news_table {
            background-color: #fff;
            border-radius: 15px 15px 15px 15px;
            padding: 32px 32px 26px 32px;
        }

        .table1 {
            text-indent: initial;
            border-spacing: 2px;
        }

        tr td {
            border-bottom: dotted 1px #bc3e84;
            /*虛線*/
            padding: 8px 0;
        }

        td p {
            margin: 0;
            padding-bottom: 5px;
            border: 0;
            font-weight: bold;
            color: #13007F;
            font-size: 14px;
        }

        td a {
            text-decoration: none;
            color: #4d4d4d;
            font-weight: bold;
            font-size: 16px;
            letter-spacing: 1px
        }

        .more {
            text-decoration: none;
            font-size: 16px;
            color: #D9007E;

        }

        .fb-page {
            z-index: 0;
        }

        @media only screen and (max-width: 500px) {
            .col-md-12.text-center.i2 h2 {
                font-size: 48px;
            }

            .carousel-indicators [data-bs-target] {
                width: 30px;
            }

            .carousel-control-next-icon,
            .carousel-control-prev-icon {
                width: 2rem;
                height: 2rem;
            }
        }

        @media only screen and (min-width: 501px) and (max-width: 575px) {
            .col-md-12.text-center.i2 h2 {
                font-size: 48px;
            }
        }

        @media only screen and (min-width: 576px) and (max-width: 767px) {
            .col-md-12.text-center.i2 h2 {
                font-size: 56px;
            }
        }

        @media only screen and (max-width: 1199px) {
            .col-md-12.text-center.i2 h2 {
                margin: 0rem 0 2rem;
            }

            .information h3 {
                margin: 3rem 0 1rem;
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
                                    <a href="./password/index.html" target="_blank">終極密碼</a>
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
                                    <a href="./password/index.html" target="_blank">終極密碼</a>
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

    <section>
        <div id="myCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
            <!-- 輪播指示 -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="0" class="active"
                    aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
                <button type="button" data-bs-target="#myCarousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
            </div>
            <!-- 輪播內容 -->
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="pic/notice.png" class="d-block w-100" alt="公告">
                </div>
                <div class="carousel-item">
                    <img src="pic/banner2.png" class="d-block w-100" alt="花顏巧語 為你的禮物賦予特別的意義">
                </div>
                <div class="carousel-item">
                    <img src="pic/banner5.png" class="d-block w-100" alt="畢業季">
                </div>
                <div class="carousel-item">
                    <img src="pic/banner3.png" class="d-block w-100" alt="你的故事獨一無二">
                </div>
                <div class="carousel-item">
                    <img src="pic/banner4.png" class="d-block w-100" alt="主打客製化商品">
                </div>
            </div>
            <!-- 輪播控制 -->
            <a class="carousel-control-prev" href="#myCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
                <span class="visually-hidden">Previous</span>
            </a>
            <a class="carousel-control-next" href="#myCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
                <span class="visually-hidden">Next</span>
            </a>
        </div>
    </section>

    <section class="i1">
        <div class="information">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 text-center i2">
                        <h2><span>INFOR<b>M</b>ATION</span></h2>
                    </div>
                </div>
                <div class="row justify-content-center">
                    <div class="col-md-3">
                        <div>
                            <h3>NEWS</h3>
                        </div>
                        <div class="news_table">
                            <table class="table1">
                                <tr>
                                    <td>
                                        <p>2023/10/16</p>
                                        <a href="#">會員登錄系統完成，購物車系統建置中。</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/09/01</p>
                                        <a href="#">開啟新旅程！為新學期獻上美好祝福!</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/08/25</p>
                                        <a href="#">花好花滿！期間限定8月滿額折扣，買越多省越多。</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/08/15</p>
                                        <a href="#">夏日狂歡！多款當季花卉，為您打造一個充滿綠意和生機的夏日花園。</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/08/09</p>
                                        <a href="#">08/14 網站例行維護公告。</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/08/05</p>
                                        <a href="#">浪漫求婚指南！讓花顏巧語教你打造完美求婚場景。</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <p>2023/08/01</p>
                                        <a href="#">花顏巧語熱銷TOP 10！最受歡迎花卉一次看。</a>
                                    </td>
                                </tr>

                            </table>
                            <br>
                            <div style="text-align: center;"><a href="#" class="more">MORE</a></div>
                        </div>
                    </div>
                    <div class="col-md-3 text-center">
                        <div>
                            <h3>OFFICIAL Facebook</h3>
                        </div>
                        <div class="fb-page" data-href="https://www.facebook.com/CapturingtheEssenceofBeauty"
                            data-tabs="timeline" data-width="470px" data-height="608px"
                            style="border:none;overflow:hidden; border-radius: 15px;" data-small-header="false"
                            data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true">
                            <blockquote cite="https://www.facebook.com/CapturingtheEssenceofBeauty"
                                class="fb-xfbml-parse-ignore">
                                <a href="https://www.facebook.com/CapturingtheEssenceofBeauty">花顏巧語</a>
                            </blockquote>
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


    <!-- 以下是FB粉專用script -->
    <div id="fb-root"></div>
    <script async defer crossorigin="anonymous" src="https://connect.facebook.net/zh_TW/sdk.js#xfbml=1&version=v18.0"
        nonce="F6VFf1vd"></script>

    <script>
        const imagePaths = ['pic/banner2.png', 'pic/banner3.png', 'pic/banner4.png', 'pic/banner5.png'];

        imagePaths.forEach(path => {
            const img = new Image();
            img.src = path;
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</body>

</html>