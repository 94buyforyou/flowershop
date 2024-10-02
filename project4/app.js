// let hero = document.querySelector(".hero");
// let slider = document.querySelector(".slider");
// let animation = document.querySelector("section.animation-wrapper");

// const time_line = new TimelineMax();

// // 第一項是控制的對象
// // 第二項是時間
// // 第三項是對像的初始狀態
// // 第四是對象的結束狀態
// // 第五是動畫開始時間
// time_line
//     .fromTo(
//         hero,
//         1,
//         { height: "0%" },
//         { height: "100%", ease: Power2.easeInOut }
//     )
//     .fromTo(
//         hero,
//         1.2,
//         { width: "80%" },
//         { width: "100%", ease: Power2.easeInOut }
//     )
//     .fromTo(
//         slider,
//         1,
//         { x: "-100%" },
//         { x: "0%", ease: Power2.easeInOut },
//         "-=1.2"
//     )
//     .fromTo(
//         animation,
//         0.3,
//         { opacity: 1 },
//         { opacity: 0, ease: Power2.easeInOut }
//     );

// setTimeout(() => {
//     animation.style.pointerEvents = "none";
// }, 2500);

// 禁止整個網站使用Enter
window.addEventListener("keypress", (e) => {
    if (e.ket == "Enter") {
        e.preventDefault();
    }
});

function convertor(grade) {
    switch (grade) {
        case "A":
            return 4.0;
        case "A-":
            return 3.7;
        case "B+":
            return 3.4;
        case "B":
            return 3.0;
        case "B-":
            return 2.7;
        case "C+":
            return 2.4;
        case "C":
            return 2.0;
        case "C-":
            return 1.7;
        case "D+":
            return 1.4;
        case "D":
            return 1.0;
        case "D-":
            return 0.7;
        case "F":
            return 0.0;
        default:
            return 0;
    }
}

// 當select加權改變時 GPA也會改變
let allSelects = document.querySelectorAll("select");
allSelects.forEach((select) => {
    select.addEventListener("change", (e) => {
        setGPA();
        changeColor(e.target);
    });
});

// 當credit學分改變時 GPA也會改變
let credits = document.querySelectorAll(".class-credit");
credits.forEach((credit) => {
    credit.addEventListener("change", () => {
        setGPA();
    });
});

function changeColor(target) {
    if (target.value == "A" || target.value == "A-") {
        target.style.backgroundColor = "lightgreen";
        target.style.color = "black";
    } else if (
        target.value == "B+" ||
        target.value == "B" ||
        target.value == "B-"
    ) {
        target.style.backgroundColor = "yellow";
        target.style.color = "black";
    } else if (
        target.value == "C+" ||
        target.value == "C" ||
        target.value == "C-"
    ) {
        target.style.backgroundColor = "orange";
        target.style.color = "black";
    } else if (
        target.value == "D+" ||
        target.value == "D" ||
        target.value == "D-"
    ) {
        target.style.backgroundColor = "red";
        target.style.color = "black";
    } else if (target.value == "F") {
        target.style.backgroundColor = "grey";
        target.style.color = "white";
    } else {
        target.style.backgroundColor = "white";
    }
}

function setGPA() {
    let formLength = document.querySelectorAll("form").length;
    let credits = document.querySelectorAll(".class-credit"); //學分
    let selects = document.querySelectorAll("select"); // 加權
    let sum = 0;
    let creditSum = 0;

    for (let i = 0; i < credits.length; i++) {
        if (!isNaN(creditSum + credits[i].valueAsNumber)) {
            creditSum = creditSum + credits[i].valueAsNumber;
        }
    }

    for (let i = 0; i < formLength; i++) {
        if (!isNaN(creditSum + credits[i].valueAsNumber)) {
            sum = sum + credits[i].valueAsNumber * convertor(selects[i].value);
        }
    }

    if (creditSum == 0) {
        result = (0.0).toFixed(2);
    } else {
        result = (sum / creditSum).toFixed(2);
    }

    document.getElementById("result-gpa").innerText = result;
}
