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
let ground_y = 550;
let ground_height = 5;
let brickArray = [];
let count = 0;
let life = 5;
let hasCollided = false; // 設定碰撞變數

document.getElementById("myLife").innerHTML = "剩餘生命: " + life;

function getRandomNumber(min, max) {
    // 最小值, 最大值, 排除的最小值, 排除的最大值
    // 生成隨機數字來當方塊的XY座標
    return min + Math.floor(Math.random() * (max - min));
}

class Brick {
    constructor(x, y) {
        this.x = x;
        this.y = y;
        this.width = 50;
        this.height = 50;
        brickArray.push(this);
        this.visible = true;
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
            new_y = getRandomNumber(0, 400);
            console.log(new_x, new_y);
            checkOverlap(new_x, new_y);
        } while (overlapping);

        this.x = new_x;
        this.y = new_y;
    }

    drawBrick() {
        ctx.fillStyle = "white";
        ctx.fillRect(this.x, this.y, this.width, this.height);
    }

    touchingBall(ballX, ballY) {
        return (
            ballX >= this.x - radius &&
            ballX <= this.x + this.width + radius &&
            ballY >= this.y - radius &&
            ballY <= this.y + this.height + radius
        );
    }
}

// 畫出10個方塊
for (let i = 0; i < 10; i++) {
    let newBrick = new Brick();
    newBrick.pickALocation();
}

window.addEventListener("mousemove", (e) => {
    //地板X座標等於滑鼠X座標 - 畫布居中左邊空白寬度
    ground_x = e.clientX - (window.innerWidth - c.width) / 2;

    // 確保地板不會超出畫布的左右邊界
    if (ground_x < 0) {
        ground_x = 0;
    } else if (ground_x > c.width - 200) {
        ground_x = c.width - 200;
    }
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

    // 確認是否撞到方塊
    brickArray.forEach((brick) => {
        if (brick.visible && brick.touchingBall(circle_x, circle_y)) {
            count++; // 若有撞到 count+1
            brick.visible = false; // 若有撞到方塊變不可見
            // 改變x,y方向的速度
            // 從下往上撞
            if (circle_y >= brick.y + brick.height) {
                ySpeed *= -1;
                // 從上往下撞
            } else if (circle_y <= brick.y) {
                ySpeed *= -1;
                // 從左往右撞
            } else if (circle_x <= brick.x) {
                xSpeed *= -1;
                // 從右往左撞
            } else if (circle_x >= brick.x + brick.width) {
                xSpeed *= -1;
            }

            if (count == 10) {
                //當撞擊10次遊戲結束
                alert("Congratulations");
                clearInterval(game);
            }
        }
    });

    // 確認是否撞到地板
    if (
        circle_x >= ground_x - radius &&
        circle_x <= ground_x + 200 + radius &&
        circle_y >= ground_y - radius &&
        circle_y <= ground_y + 5
    ) {
        if (ySpeed > 0) {
            circle_y = ground_y - radius; // 讓球的位置更接近地板，因為有步驟檢測，
            ySpeed *= -1; // 反轉速度
        }
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
    // 若hasCollided為不為false 則不會執行
    if (circle_y >= c.height - radius && !hasCollided) {
        hasCollided = true; // 每當碰撞一次 hasCollided 變成 true
        life--;
        document.getElementById("myLife").innerHTML = "剩餘生命: " + life;
        if (life == 0) {
            document.getElementById("myLife").innerHTML = "剩餘生命: " + life;
            setTimeout(() => {
                alert("Game Over");
                clearInterval(game);
            }, 100); // 設定延遲確保顯示0後再執行遊戲結束
        } else {
            setTimeout(() => {
                // 重置球的位置和速度
                circle_x = 160;
                circle_y = 60;
                xSpeed = 20;
                ySpeed = 20;
                hasCollided = false;
            }, 200);
        }
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
        if (brick.visible) {
            brick.drawBrick();
        }
    });

    // 畫出圓
    ctx.beginPath();
    ctx.arc(circle_x, circle_y, radius, 0, 2 * Math.PI); // x座標, y座標, 半徑, 初始角度, 結束角度
    ctx.stroke();
    ctx.fillStyle = "pink";
    ctx.fill();
}

let game = setInterval(drawCircle, 25);
