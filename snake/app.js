const canvas = document.getElementById("myCanvas");
const ctx = canvas.getContext("2d");
// getContext() method會回傳一個canvas的drawing context，
// drawing context可以用來在canvas內畫圖
const unit = 20;
const row = canvas.height / unit; // 320 / 20 = 16
const column = canvas.width / unit; // 320 / 20 = 16

let snake = []; // array中的每個元素，都是一個物件
function creakSnake() {
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
}

class Fruit {
    constructor() {
        this.x = Math.floor(Math.random() * column) * unit;
        this.y = Math.floor(Math.random() * row) * unit;
    }

    drawFruit() {
        //果實的樣式及位置
        ctx.fillStyle = "pink";
        ctx.fillRect(this.x, this.y, unit, unit);
    }

    pickALocation() {
        let overlapping = false; //設定變數重疊(初始為False = 沒有)
        let new_x; //新果實的XY座標
        let new_y;

        function checkOverlap(new_x, new_y) {
            //檢查新果實的隨機位置是否和蛇的身體重疊
            for (let i = 0; i < snake.length; i++) {
                if (new_x == snake[i].x && new_y == snake[i].y) {
                    overlapping = true;
                    return;
                } else {
                    overlapping = false;
                }
            }
        }

        do {
            new_x = Math.floor(Math.random() * column) * unit;
            new_y = Math.floor(Math.random() * row) * unit;
            checkOverlap(new_x, new_y);
        } while (overlapping); //若沒有重疊 則取得新果實的XY座標

        this.x = new_x; //將新果實的XY座標代入
        this.y = new_y;
    }
}

//初始設定
creakSnake();
let myFruit = new Fruit();
window.addEventListener("keydown", changeDirection);
let d = "Right"; //設定初始移動方向

function changeDirection(e) {
    //改變方向
    if (e.key == "ArrowLeft" && d != "Right") {
        d = "Left";
    } else if (e.key == "ArrowRight" && d != "Left") {
        d = "Right";
    } else if (e.key == "ArrowUp" && d != "Down") {
        d = "Up";
    } else if (e.key == "ArrowDown" && d != "Up") {
        d = "Down";
    }

    //每次按上下左右之後，下一幀被畫出來之前，不接受任何keydown事件(不能再改變方向)，防止蛇在重新畫出來之前自殺
    window.removeEventListener("keydown", changeDirection);
}

let score = 0;
let highestScore;
loadHighestScore();
document.getElementById("myScore").innerHTML = "遊戲分數: " + score;
document.getElementById("myScore2").innerHTML = "最高分數: " + highestScore;

function draw() {
    //每次畫圖之前 確認蛇有沒有咬到自己
    for (let i = 1; i < snake.length; i++) {
        if (snake[i].x == snake[0].x && snake[i].y == snake[0].y) {
            clearInterval(myGame);
            alert("Game Over");
            return;
        }
    }

    ctx.fillStyle = "black";
    ctx.fillRect(0, 0, canvas.width, canvas.height); //( X座標 , Y座標 , 長 , 寬 )

    myFruit.drawFruit();

    for (let i = 0; i < snake.length; i++) {
        //畫出蛇
        if (i == 0) {
            ctx.fillStyle = "red"; //頭的顏色
        } else {
            ctx.fillStyle = "lightgreen"; //其他的顏色
        }
        ctx.strokeStyle = "white"; //每一格的邊框顏色

        //穿牆功能
        if (snake[i].x >= canvas.width) {
            //如果蛇頭的X座標或Y座標 = 0 或 = 遊戲寬度/高度 (碰到牆壁),要讓其出現在另一端
            snake[i].x = 0;
        }

        if (snake[i].x < 0) {
            snake[i].x = canvas.width - unit;
        }

        if (snake[i].y >= canvas.height) {
            snake[i].y = 0;
        }

        if (snake[i].y < 0) {
            snake[i].y = canvas.height - unit;
        }

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
    if (snake[0].x == myFruit.x && snake[0].y == myFruit.y) {
        myFruit.pickALocation();
        score++;
        setHighestScore(score);
        document.getElementById("myScore").innerHTML = "遊戲分數: " + score;
        document.getElementById("myScore2").innerHTML =
            "最高分數: " + highestScore;
    } else {
        snake.pop();
    }

    snake.unshift(newHead);
    window.addEventListener("keydown", changeDirection);
}

let myGame = setInterval(draw, 75);

function loadHighestScore() {
    //進網頁時．取得歷史最高分
    if (localStorage.getItem("highestScore") == null) {
        highestScore = 0;
    } else {
        highestScore = Number(localStorage.getItem("highestScore"));
    }
}

function setHighestScore(score) {
    if (score > highestScore) {
        localStorage.setItem("highestScore", score);
        highestScore = score;
    }
}
