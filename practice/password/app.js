let answer = Math.floor(Math.random() * 100);
let n1 = 0;
let n2 = 99;

while (true) {
    let guess = Number(
        prompt("終極密碼 " + "請輸入你的猜測 ( " + n1 + "~" + n2 + " ) 之間")
    );

    if (guess < n1 || guess > n2) {
        alert("無效數值,請輸入正確的數字");
        continue;
    }

    if (guess == answer) {
        alert("恭喜猜中,答案為" + answer);
        location.reload();
        break;
    } else if (guess > answer) {
        n2 = guess;
    } else if (guess < answer) {
        n1 = guess;
    }
}
