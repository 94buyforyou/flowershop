let hero = document.querySelector(".hero");
let slider = document.querySelector(".slider");
let animation = document.querySelector("section.animation-wrapper");

const time_line = new TimelineMax();

// 第一項是控制的對象
// 第二項是時間
// 第三項是對像的初始狀態
// 第四是對象的結束狀態
// 第五是動畫開始時間
time_line
    .fromTo(
        hero,
        1,
        { height: "0%" },
        { height: "100%", ease: Power2.easeInOut }
    )
    .fromTo(
        hero,
        1.2,
        { width: "80%" },
        { width: "100%", ease: Power2.easeInOut }
    )
    .fromTo(
        slider,
        1,
        { x: "-100%" },
        { x: "0%", ease: Power2.easeInOut },
        "-=1.2"
    )
    .fromTo(
        animation,
        0.3,
        { opacity: 1 },
        { opacity: 0, ease: Power2.easeInOut }
    );

setTimeout(() => {
    animation.style.pointerEvents = "none";
}, 2500);
