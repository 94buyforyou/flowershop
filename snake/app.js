const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");
// getContext() method會回傳一個canvas的drawing context，
// drawing context可以用來在canvas內畫圖
const unit = 20;
const row = canvas.height / unit; // 320 / 20 = 16
const column = canvas.width / unit; // 320 / 20 = 16

let snake = []; // array中的每個元素，都是一個物件
// 物件的工作是，儲存身體的x, y座標
snake[0] = {
    x: 80,
    y: 0,
};

snake[1] = {
    x: 60,
    y: 0,
};

snake[2] = {
    x: 40,
    y: 0,
};

snake[3] = {
    x: 20,
    y: 0,
};

window.addEventListener("keydown", changeDirection);
let d = "Right"; //設定初始移動方向
function changeDirection(e) {
    if (e.key == "ArrowLeft" && d != "Right") {
        d = "Left";
    } else if (e.key == "ArrowRight" && d != "Left") {
        d = "Right";
    } else if (e.key == "ArrowUp" && d != "Down") {
        d = "Up";
    } else if (e.key == "ArrowDown" && d != "Up") {
        d = "Down";
    }
}

function draw() {
    ctx.fillStyle = "black";
    ctx.fillRect(0, 0, canvas.width, canvas.height);

    for (let i = 0; i < snake.length; i++) {
        if (i == 0) {
            ctx.fillStyle = "lightgreen"; //頭的顏色
        } else {
            ctx.fillStyle = "lightblue"; //其他的顏色
        }
        ctx.strokeStyle = "white"; //每一格的邊框顏色
        ctx.fillRect(snake[i].x, snake[i].y, unit, unit); //畫出所有格子
        ctx.strokeRect(snake[i].x, snake[i].y, unit, unit); //所有格子的邊框
    }

    //以目前d的方向來決定蛇的下一幀在哪個座標
    let snakeX = snake[0].x; //取得目前蛇頭的X座標
    let snakeY = snake[0].y; //取得目前蛇頭的Y座標

    if (d == "Left") {
        //判斷方向改變座標
        snakeX -= unit;
    } else if (d == "Up") {
        snakeY -= unit;
    } else if (d == "Right") {
        snakeX += unit;
    } else if (d == "Down") {
        snakeY += unit;
    }

    let newHead = {
        x: snakeX,
        y: snakeY,
    };

    //判斷蛇是否有吃到果實
    snake.pop();
    snake.unshift(newHead);
}

let myGame = setInterval(draw, 100);
