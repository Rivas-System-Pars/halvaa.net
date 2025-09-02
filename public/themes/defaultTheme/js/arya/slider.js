arya = document.getElementById('newSwiper')




var swiper2 = new Swiper(arya, {
    spaceBetween: 30,
    loop: true,
    centeredSlides: true,
    autoplay: {
      delay: 5000,
      disableOnInteraction: false,
    },
    pagination: {
      el: ".swiper-pagination",
      clickable: true,
    },
    navigation: {
      nextEl: ".swiper-button-next",
      prevEl: ".swiper-button-prev",
    },
  });


var faqSwiper = new Swiper(".faq_swiper", {
    slidesPerView: 1,
    spaceBetween: 20,
    breakpoints: {
        320: {
            slidesPerView: 1,
            spaceBetween: 20,
        },
        768: {
            slidesPerView: 2,
            spaceBetween: 30,
        },
       992: {
            slidesPerView: 2,
            spaceBetween: 40,
        },
    },
    // autoplay: {
    //     delay: 2500, // Time between slide changes
    //     disableOnInteraction: false, // Ensure autoplay continues even after user interaction
    // },
    pagination: {
        el: ".swiper-pagination",
        type: "fraction",
    },
    navigation: {
        nextEl: ".swiper-button-next",
        prevEl: ".swiper-button-prev",
    },
});

const accordionHeaders = document.querySelectorAll(".the_accordion_header");
const accordionCollapse = document.querySelectorAll(".the_accordion_collapse");
let autoplayTimeout;

accordionHeaders.forEach((header) => {
    header.addEventListener("click", () => {
        const button = header.querySelector(".accordion-button");

        if (button && button.getAttribute("aria-expanded") === "true" && !button.classList.contains("collapsed")) {
            header.style.borderRadius = "0";
            header.style.height = "160px";

            // Force align-items using setProperty to override CSS
            button.style.setProperty("align-items", "flex-start", "important");
            button.style.setProperty("padding-top", "1.3rem", "important");
        } else {
            header.style.borderRadius = "";
            header.style.height = "";

            // Reset align-items
            if (button) {
                button.style.setProperty("align-items", "");
            }
        }
    });
});

// بررسی اینکه کدام پنل در ابتدا باز است و استایل را تنظیم کنید
accordionCollapse.forEach((collapse, index) => {
    if (collapse.classList.contains("show")) {
        const header = accordionHeaders[index];
        const button = header?.querySelector(".accordion-button");
        
        if (header) {
            header.style.borderRadius = "0";
            header.style.height = "160px";
        }
        
        if (button) {
            button.style.setProperty("align-items", "flex-start", "important");
            button.style.setProperty("padding-top", "1.3rem", "important");
        }
    }
});

