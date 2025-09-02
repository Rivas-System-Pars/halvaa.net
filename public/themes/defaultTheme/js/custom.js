var whyUsSwiper = new Swiper(".why_us_swiper", {
    slidesPerView: 1, // Default for smallest screen
    spaceBetween: 30,
    breakpoints: {
        // For small screens (mobile)
        320: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        // For tablets
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
        // For large screens
        1200: {
            slidesPerView: 3,
            spaceBetween: 40,
        },
    },
    pagination: {
        el: ".swiper-pagination",
        clickable: true,
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
    // loop: true, // Ensures infinite scrolling
    grabCursor: true, // Better user experience
});
