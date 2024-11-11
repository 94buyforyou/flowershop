const c = document.getElementById("myCanvas");
const canvasHeight = c.height;
const canvasWidth = c.width;
const ctx = c.getContext("2d");
let circle_x = 160;
let circle_y = 60;
let radius = 20;
let xSpeed = 20;
let ySpeed = 20;
// 獲取設備的 DPI
let dpi = window.devicePixelRatio;

function fix_dpi() {
    // 獲取 CSS 樣式中的高度和寬度，並將其轉為數字
    let style_height = +getComputedStyle(c)
        .getPropertyValue("height")
        .slice(0, -2);
    let style_width = +getComputedStyle(c)
        .getPropertyValue("width")
        .slice(0, -2);

    // 調整 <canvas> 的實際解析度
    c.setAttribute("height", style_height * dpi);
    c.setAttribute("width", style_width * dpi);
}

function drawCircle() {
    // 修正 DPI 確保畫布解析度適應高 DPI 設備
    fix_dpi();

    // 確認是否撞到右邊牆壁
    if (circle_x >= c.width - radius) {
        xSpeed *= -1;
    }
    // 確認是否撞到左邊牆壁
    if (circle_x <= radius) {
        xSpeed *= -1;
    }
    // 確認是否撞到上邊牆壁
    if (circle_y <= radius) {
        ySpeed *= -1;
    }
    // 確認是否撞到下邊牆壁
    if (circle_y >= c.height - radius) {
        ySpeed *= -1;
    }

    circle_x += xSpeed;
    circle_y += ySpeed;

    // 畫出黑色背景
    ctx.fillStyle = "black";
    ctx.fillRect(0, 0, c.width, c.height);

    //畫出圓
    ctx.beginPath();
    ctx.arc(circle_x, circle_y, radius, 0, 2 * Math.PI);
    ctx.stroke();
    ctx.fillStyle = "yellow";
    ctx.fill();
}

let game = setInterval(drawCircle, 25);
