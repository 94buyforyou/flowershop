let hero = document.querySelector(".hero");
let slider = document.querySelector(".slider");
let animation = document.querySelector("section.animation-wrapper");

const time_line = new TimelineMax();

// 第一項是控制的對象
// 第二項是時間
// 第三項是對像的初始狀態
// 第四是對象的結束狀態
time_line.fromTo(
    hero,
    1,
    { height: "0%" },
    { height: "100%", ease: Power2.easeInOut }
);
