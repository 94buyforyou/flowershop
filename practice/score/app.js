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
    if (e.key == "Enter") {
        e.preventDefault();
    }
});

// 防止FORM內部的BUTTON交出表單
let allButtons = document.querySelectorAll("button");
allButtons.forEach((button) => {
    button.addEventListener("click", (e) => {
        e.preventDefault();
    });
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

let addButton = document.querySelector(".plus-btn");
addButton.addEventListener("click", () => {
    let newForm = document.createElement("form");
    let newDiv = document.createElement("div");
    newDiv.classList.add("grader");

    //製作3個input 1個select 1個button
    let newInput1 = document.createElement("input");
    newInput1.setAttribute("type", "text");
    newInput1.setAttribute("list", "opt");
    newInput1.setAttribute("placeholder", "class category");
    newInput1.classList.add("class-type");

    let newInput2 = document.createElement("input");
    newInput2.setAttribute("type", "text");
    newInput2.setAttribute("placeholder", "class number");
    newInput2.classList.add("class-number");

    let newInput3 = document.createElement("input");
    newInput3.setAttribute("type", "number");
    newInput3.setAttribute("min", "0");
    newInput3.setAttribute("max", "6");
    newInput3.setAttribute("placeholder", "credits");
    newInput3.classList.add("class-credit");
    newInput3.addEventListener("change", () => {
        setGPA();
    });

    // here is the select tag
    let newSelect = document.createElement("select");
    newSelect.classList.add("select");

    var opt1 = document.createElement("option");
    opt1.setAttribute("value", "");
    let textNode1 = document.createTextNode("");
    opt1.appendChild(textNode1);

    var opt2 = document.createElement("option");
    opt2.setAttribute("value", "A");
    let textNode2 = document.createTextNode("A");
    opt2.appendChild(textNode2);

    var opt3 = document.createElement("option");
    opt3.setAttribute("value", "A-");
    let textNode3 = document.createTextNode("A-");
    opt3.appendChild(textNode3);

    var opt4 = document.createElement("option");
    opt4.setAttribute("value", "B+");
    let textNode4 = document.createTextNode("B+");
    opt4.appendChild(textNode4);

    var opt5 = document.createElement("option");
    opt5.setAttribute("value", "B");
    let textNode5 = document.createTextNode("B");
    opt5.appendChild(textNode5);

    var opt6 = document.createElement("option");
    opt6.setAttribute("value", "B-");
    let textNode6 = document.createTextNode("B-");
    opt6.appendChild(textNode6);

    var opt7 = document.createElement("option");
    opt7.setAttribute("value", "C+");
    let textNode7 = document.createTextNode("C+");
    opt7.appendChild(textNode7);

    var opt8 = document.createElement("option");
    opt8.setAttribute("value", "C");
    let textNode8 = document.createTextNode("C");
    opt8.appendChild(textNode8);

    var opt9 = document.createElement("option");
    opt9.setAttribute("value", "C-");
    let textNode9 = document.createTextNode("C-");
    opt9.appendChild(textNode9);

    var opt10 = document.createElement("option");
    opt10.setAttribute("value", "D+");
    let textNode10 = document.createTextNode("D+");
    opt10.appendChild(textNode10);

    var opt11 = document.createElement("option");
    opt11.setAttribute("value", "D");
    let textNode11 = document.createTextNode("D");
    opt11.appendChild(textNode11);

    var opt12 = document.createElement("option");
    opt12.setAttribute("value", "D-");
    let textNode12 = document.createTextNode("D-");
    opt12.appendChild(textNode12);

    var opt13 = document.createElement("option");
    opt13.setAttribute("value", "F");
    let textNode13 = document.createTextNode("F");
    opt13.appendChild(textNode13);

    newSelect.appendChild(opt1);
    newSelect.appendChild(opt2);
    newSelect.appendChild(opt3);
    newSelect.appendChild(opt4);
    newSelect.appendChild(opt5);
    newSelect.appendChild(opt6);
    newSelect.appendChild(opt7);
    newSelect.appendChild(opt8);
    newSelect.appendChild(opt9);
    newSelect.appendChild(opt10);
    newSelect.appendChild(opt11);
    newSelect.appendChild(opt12);
    newSelect.appendChild(opt13);

    newSelect.addEventListener("change", (e) => {
        setGPA();
        changeColor(e.target);
    });

    let newButton = document.createElement("button");
    newButton.classList.add("trash");
    let newItag = document.createElement("i");
    newItag.classList.add("fas");
    newItag.classList.add("fa-trash");
    newButton.appendChild(newItag);

    newButton.addEventListener("click", (e) => {
        e.preventDefault();
        e.target.parentElement.parentElement.style.animation =
            "scaleDown 0.5s ease forwards";
        e.target.parentElement.parentElement.addEventListener(
            "animationend",
            (e) => {
                e.target.remove();
                setGPA();
            }
        );
    });

    newDiv.appendChild(newInput1);
    newDiv.appendChild(newInput2);
    newDiv.appendChild(newInput3);
    newDiv.appendChild(newSelect);
    newDiv.appendChild(newButton);

    newForm.appendChild(newDiv);
    document.querySelector(".all-inputs").appendChild(newForm);
    newForm.style.animation = "scaleUP 0.5s ease forwards";
});

let allTrash = document.querySelectorAll(".trash-button");
allTrash.forEach((trash) => {
    trash.addEventListener("click", (e) => {
        e.target.parentElement.parentElement.classList.add("remove");
    });
});

allTrash.forEach((trash) => {
    let form = trash.parentElement.parentElement;
    form.addEventListener("transitionend", (e) => {
        e.target.remove();
        setGPA();
    });
});

//分數排序
let btn1 = document.querySelector(".btndown");
let btn2 = document.querySelector(".btnup");
btn1.addEventListener("click", () => {
    reline("down");
});
btn2.addEventListener("click", () => {
    reline("up");
});

function reline(direction) {
    let graders = document.querySelectorAll("div.grader");
    let objectArray = [];

    for (let i = 0; i < graders.length; i++) {
        let class_name = graders[i].children[0].value;
        let class_number = graders[i].children[1].value;
        let class_credit = graders[i].children[2].value;
        let class_grade = graders[i].children[3].value;

        if (
            !(
                class_name == "" &&
                class_number == "" &&
                class_credit == "" &&
                class_grade == ""
            )
        ) {
            let class_object = {
                class_name,
                class_number,
                class_credit,
                class_grade,
            };
            objectArray.push(class_object);
        }
    }

    //取得objectarray後 把成績的ABCD(string)轉換成數字
    for (let i = 0; i < objectArray.length; i++) {
        objectArray[i].class_grade_number = convertor(
            objectArray[i].class_grade
        );
    }

    objectArray = mergeSort(objectArray);
    if (direction == "down") {
        objectArray = objectArray.reverse();
    }

    //根據objectArray來更新網頁
    let allInputs = document.querySelector(".all-inputs");
    allInputs.innerHTML = "";

    for (let i = 0; i < objectArray.length; i++) {
        allInputs.innerHTML += `<form>
            <div class="grader">
                <input
                    type="text"
                    placeholder="class category"
                    class="class-type"
                    list="opt"
                    value=${objectArray[i].class_name}
                /><!--
                --><input
                    type="text"
                    placeholder="class number"
                    class="class-number"
                    value=${objectArray[i].class_number}
                /><!--
                --><input
                    type="number"
                    placeholder="credits"
                    min="0"
                    max="6"
                    class="class-credit"
                    value=${objectArray[i].class_credit}
                /><!--
                --><select name="select" class="select">
                    <option value=""></option>
                    <option value="A">A</option>
                    <option value="A-">A-</option>
                    <option value="B+">B+</option>
                    <option value="B">B</option>
                    <option value="B-">B-</option>
                    <option value="C+">C+</option>
                    <option value="C">C</option>
                    <option value="C-">C-</option>
                    <option value="D+">D+</option>
                    <option value="D">D</option>
                    <option value="D-">D-</option>
                    <option value="F">F</option></select
                ><!--
                --><button class="trash-button">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
            </form>`;
    }

    graders = document.querySelectorAll("div.grader");
    for (let i = 0; i < graders.length; i++) {
        graders[i].children[3].value = objectArray[i].class_grade;
    }

    //添加排序後 select的顏色監聽事件
    allSelects = document.querySelectorAll("select");
    allSelects.forEach((select) => {
        changeColor(select);
        select.addEventListener("change", (e) => {
            setGPA();
            changeColor(e.target);
        });
    });

    //添加排序後 credits的GPA事件監聽
    let allCredits = document.querySelectorAll(".class-credit");
    allCredits.forEach((credit) => {
        credit.addEventListener("change", () => {
            setGPA();
        });
    });

    //添加排序後 垃圾筒的功能
    let allTrash = document.querySelectorAll(".trash-button");
    allTrash.forEach((trash) => {
        trash.addEventListener("click", (e) => {
            e.preventDefault();
            e.target.parentElement.parentElement.classList.add("remove");
        });
    });

    allTrash.forEach((trash) => {
        let form = trash.parentElement.parentElement;
        form.addEventListener("transitionend", (e) => {
            e.target.remove();
            setGPA();
        });
    });
}

function merge(a1, a2) {
    let result = [];
    let i = 0;
    let j = 0;

    while (i < a1.length && j < a2.length) {
        if (a1[i].class_grade_number > a2[j].class_grade_number) {
            result.push(a2[j]);
            j++;
        } else {
            result.push(a1[i]);
            i++;
        }
    }

    while (i < a1.length) {
        result.push(a1[i]);
        i++;
    }
    while (j < a2.length) {
        result.push(a2[j]);
        j++;
    }

    return result;
}

function mergeSort(arr) {
    if (arr.length == 0) {
        return;
    }

    if (arr.length == 1) {
        return arr;
    } else {
        let middle = Math.floor(arr.length / 2);
        let left = arr.slice(0, middle);
        let right = arr.slice(middle, arr.length);
        return merge(mergeSort(left), mergeSort(right));
    }
}
