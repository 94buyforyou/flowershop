const c = document.getElementById("myCanvas");
const canvasHeight = c.height;
const canvasWidth = c.width;
const ctx = c.getContext("2d");
let circle_x = 160;
let circle_y = 60;
let radius = 20;
let xSpeed = 20;
let ySpeed = 20;
let ground_x = 100;
let ground_y = 500;
let ground_height = 5;

c.addEventListener("mousemove", (e) => {
    ground_x = e.clientX;
});

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

    // 確認是否撞到地板
    if (
        circle_x >= ground_x - radius &&
        circle_x <= ground_x + 200 + radius &&
        circle_y >= ground_y - radius &&
        circle_y <= ground_y + radius
    ) {
        if (ySpeed > 0) {
            // 當球從上往下撞擊地板時，讓circle_y-40，避免球卡在地板中間造成來回彈跳
            circle_y -= 40;
        } else {
            circle_y += 40; // 當球從下往上撞擊地板時，讓circle_y+40，避免球卡在地板中間造成來回彈跳
        }
        ySpeed *= -1;
    }

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

    // 畫出地板
    ctx.fillStyle = "red";
    ctx.fillRect(ground_x, ground_y, 200, ground_height); // x座標, y座標, 長度, 寬度

    // 畫出圓
    ctx.beginPath();
    ctx.arc(circle_x, circle_y, radius, 0, 2 * Math.PI); // x座標, y座標, 半徑, 初始角度, 結束角度
    ctx.stroke();
    ctx.fillStyle = "pink";
    ctx.fill();
}

let game = setInterval(drawCircle, 25);
