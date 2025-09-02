document.addEventListener("DOMContentLoaded", function () {
    const carousel = document.querySelector("#serviceCarousel");
    const menuChoser = document.querySelector(".service-Menu-Choser");
    const buttons = menuChoser ? Array.from(menuChoser.children) : [];
    let currentIndex = 0;
    let autoplayInterval;

    if (carousel && buttons.length > 0) {
        // ایجاد یک نمونه از Bootstrap Carousel
        const bsCarousel = new bootstrap.Carousel(carousel, { interval: false });

        // اضافه کردن کلاس به دکمه پیش‌فرض
        buttons[currentIndex].classList.add("service-btn-coloredBG");

        buttons.forEach((button, index) => {
            button.addEventListener("click", function () {
                if (currentIndex !== index) {
                    buttons[currentIndex].classList.remove("service-btn-coloredBG");
                    currentIndex = index;
                    button.classList.add("service-btn-coloredBG");

                    // تغییر اسلاید در کاروسل
                    bsCarousel.to(index);
                }
            });
        });

        // گوش دادن به رویداد تغییر اسلاید
        carousel.addEventListener("slid.bs.carousel", function (event) {
            buttons[currentIndex].classList.remove("service-btn-coloredBG");
            currentIndex = event.to;
            buttons[currentIndex].classList.add("service-btn-coloredBG");
        });

        // تابع برای شروع Autoplay
        function startAutoplay() {
            if (!autoplayInterval) {
                autoplayInterval = setInterval(() => {
                    buttons[currentIndex].classList.remove("service-btn-coloredBG");
                    currentIndex = (currentIndex + 1) % buttons.length;
                    buttons[currentIndex].classList.add("service-btn-coloredBG");

                    bsCarousel.to(currentIndex);
                }, 5000);
            }
        }

        // تابع برای توقف Autoplay
        function stopAutoplay() {
            if (autoplayInterval) {
                clearInterval(autoplayInterval);
                autoplayInterval = null;
            }
        }

        // استفاده از Intersection Observer برای تشخیص نمایش کاروسل
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        startAutoplay();
                    } else {
                        stopAutoplay();
                    }
                });
            },
            { threshold: 1.0 }
        );

        observer.observe(carousel);
    }
});
