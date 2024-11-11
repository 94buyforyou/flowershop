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
let brickArray = [];

function getRandomNumber(min, max, excludeMin, excludeMax) {
    // 最小值, 最大值, 排除的最小值, 排除的最大值
    // 生成隨機數字來當方塊的XY座標
    let num;
    do {
        num = min + Math.floor(Math.random() * (max - min));
    } while (num >= excludeMin && num <= excludeMax); // 如果生成的數字在排除區間內，則重新生成
    return num;
}

class Brick {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.width = 50;
        this.height = 50;
        brickArray.push(this);
    }

    drawBrick() {
        ctx.fillStyle = "white";
        ctx.fillRect(this.x, this.y, this.width, this.height);
    }

    pickALocation() {
        //取得方塊座標
        let overlapping = false;
        let new_x;
        let new_y;

        function checkOverlap(new_x, new_y) {
            //判斷方塊是否重疊
            for (let i = 0; i < brickArray.length; i++) {
                if (
                    new_x >= brickArray[i].x - 50 &&
                    new_x <= brickArray[i].x + 50 &&
                    new_y >= brickArray[i].y - 50 &&
                    new_y <= brickArray[i].y + 50
                ) {
                    overlapping = true;
                    return;
                } else {
                    overlapping = false;
                }
            }
        }

        do {
            new_x = getRandomNumber(0, 950);
            new_y = getRandomNumber(0, 550, 450, 505); // 避免方塊Y座標 在 450 到 505 之間，避免和地板重疊
            console.log(new_x, new_y);
            checkOverlap(new_x, new_y);
        } while (overlapping);

        this.x = new_x;
        this.y = new_y;
    }
}

for (let i = 0; i < 10; i++) {
    let newBrick = new Brick();
    newBrick.pickALocation();
}

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

    // 畫出方塊
    brickArray.forEach((brick) => {
        brick.drawBrick();
    });

    // 畫出圓
    ctx.beginPath();
    ctx.arc(circle_x, circle_y, radius, 0, 2 * Math.PI); // x座標, y座標, 半徑, 初始角度, 結束角度
    ctx.stroke();
    ctx.fillStyle = "pink";
    ctx.fill();
}

let game = setInterval(drawCircle, 25);
