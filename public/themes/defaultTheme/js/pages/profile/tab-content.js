$(document).ready(function () {
    const $tabs = $(".tabs li");
    const $contents = $(".tab-content");

    $tabs.on("click", function (e) {
        e.preventDefault();
        console.log('object');

        // حذف active از همه تب‌ها و محتواها
        $tabs.removeClass("active");
        $contents.removeClass("active");

        // افزودن active به تب انتخاب‌شده و محتوای مرتبط
        const index = $(this).index();
        $(this).addClass("active");
        $contents.eq(index).addClass("active");
    });
});
