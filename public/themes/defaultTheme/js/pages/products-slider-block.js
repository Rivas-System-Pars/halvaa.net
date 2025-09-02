/* #######################################   Slider  ################################################ */
/*=============== SHOW MENU ===============*/

/*=============== REMOVE MENU MOBILE ===============*/

/*=============== SWIPER CAR ===============*/
const productSwiper = new Swiper(".product_swiper", {
    pagination: {
        el: ".product_pagination",
        clickable: true,
        renderBullet: (index, className) => {
            return (
                '<span class="' +
                className +
                '">' +
                "</span>"
            );
        },
    },
    direction: "horizontal",
    speed: 1200,
    effect: "fade",
});

/*=============== GSAP ANIMATION ===============*/
gsap.from(".product_panel-1", { y: -1000, duration: 2 });
gsap.from(".product_panel-2", { y: 1000, duration: 2 });
gsap.from(".product_image", { x: -1000, duration: 2 });
gsap.from(".product_image", { y: 1000, duration: 2 });
gsap.from(".product_titles", { x: 100, opacity: 0, duration: 2 });
gsap.from(".product_subtitle", { x: 100, opacity: 0, duration: 2 });
gsap.from(".product_info", { y: 1000, opacity: 0, duration: 2 });
/*=============== ADD BLUR HEADER ===============*/
