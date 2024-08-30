$(function () {
  $(".member1, .member2").hover(
    function () {
      $(this).attr("src", "pic/member_black.png");
    },
    function () {
      $(this).attr("src", "pic/member.png");
    }
  );

  $(".cart1, .cart2").hover(
    function () {
      $(this).attr("src", "pic/shopping_cart_black.png");
    },
    function () {
      $(this).attr("src", "pic/shopping_cart.png");
    }
  );

  $(".member1").click(function () {
    $(".memberlist").slideToggle(200); // slideToggle 滾動效果
  });

  $(document).on("click", function (event) {
    // 檢查點擊事件是否發生在 .member1 或 #memberlist 元素內
    if (!$(event.target).closest(".member1, .memberlist").length) {
      // 如果不在目標區域內，滾動隱藏 #memberlist
      $(".memberlist").slideUp(200);
    }
  });
});

// 這段是jquery
$(document).ready(function () {
  // 為帶有 href="#" 的鏈接添加點擊事件處理程序
  $('a[href="#"]').click(function (event) {
    // 阻止默認行為，防止頁面跳轉
    event.preventDefault();
    // 顯示警告消息
    alert("施工中");
  });
});
