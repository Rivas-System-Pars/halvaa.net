$(document).ready(function () {
    const userData = JSON.parse(document.querySelector('meta[name="user-data"]').content);
    //  تنظیم نوع شمارش معکوس: تاریخ شمسی یا تایمر دلخواه
    let useJalali = true; // اگر true باشد، از تاریخ شمسی استفاده می‌شود

    let targetDate;



    if (useJalali) {
        //  تاریخ شمسی و ساعت (مثال: 1403/09/08 ساعت 15:30:00)
        let jalaliDate = userData['date_widget']; // تاریخ شمسی هدف
        let jalaliTime = userData['time_widget'] + ':00'; // ساعت هدف
        let dateParts = jalaliDate.split("/"); // جدا کردن سال، ماه، روز
        let timeParts = jalaliTime.split(":"); // جدا کردن ساعت، دقیقه، ثانیه

        // تبدیل به تاریخ میلادی
        let gregorianDate = jalaali.toGregorian(
            parseInt(dateParts[0]),
            parseInt(dateParts[1]),
            parseInt(dateParts[2])
        );

        // ایجاد تاریخ میلادی همراه با ساعت، دقیقه و ثانیه
        targetDate = new Date(
            gregorianDate.gy,
            gregorianDate.gm - 1, // ماه از 0 شروع می‌شود
            gregorianDate.gd,
            parseInt(timeParts[0]), // ساعت
            parseInt(timeParts[1]), // دقیقه
            parseInt(timeParts[2]) // ثانیه
        );
    } else {
        //  تایمر دلخواه (مثال: 5 دقیقه)
        let now = new Date().getTime();
        let timerDuration = 5 * 60 * 1000; // 5 دقیقه به میلی‌ثانیه
        targetDate = new Date(now + timerDuration);
    }

    //  شروع شمارش معکوس
    $("#counter").countdown(targetDate, function (event) {
        // به‌روزرسانی مقادیر
        $("#days").text(event.strftime("%D"));
        $("#hours").text(event.strftime("%H"));
        $("#minutes").text(event.strftime("%M"));
        $("#seconds").text(event.strftime("%S"));

        //  مدیریت نمایش باکس‌ها
        if (event.strftime("%D") === "00") {
            $("#days-box").hide();
        }
        // if (event.strftime('%D') !== '00' && event.strftime('%H') === '00') {
        //     $("#hours-box").hide();
        // }
        // if (event.strftime('%H') !== '00' && event.strftime('%M') === '00') {
        //     $("#minutes-box").hide();
        // }
        // if (event.strftime('%M') !== '00' && event.strftime('%S') === '00') {
        //     $("#seconds-box").hide();
        // }
    });
});

let offer_container = new Swiper("#offer-container", {
    spaceBetween: 10,
    centeredSlides: false,
    slidesPerView: 3,
    grabCursur:true,

    loop:false,
    autoplay: {
        delay: 6500,
         disableOnInteraction: false,
     },

    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },

    breakpoints: {
        100: {
            slidesPerView: 1,
            centeredSlides:true,
        },
        456: {
            slidesPerView: 2,
            centeredSlides:false,
        },
        768:{
            slidesPerView:3,
        },

        992: {
            slidesPerView: 4,
        },
        1200: {
            slidesPerView: 3,
        },

    },
});
