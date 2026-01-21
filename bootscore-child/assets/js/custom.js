jQuery(function (e) {
    e(".pr_search_trigger").on("click", function () {
        e(this).toggleClass("show"), e(".product_search_form").toggleClass("show");
    }),
        e(".more_slide_open").slideUp(),
        e(".more_categories").on("click", function () {
            e(this).toggleClass("show"), e(".more_slide_open").slideToggle();
        }),
        e(".dc-social a.dc-link").click(function (t) {
            t.preventDefault();
            var o = (e(window).width() - 575) / 2,
                s = "status=1,width=575,height=520,top=" + (e(window).height() - 520) / 2 + ",left=" + o;
            window.open(e(this).attr("href"), "share", s);
        });
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach(function (e) {
        new bootstrap.Tooltip(e);
    });
    const t = new IntersectionObserver(
        function (e) {
            e.forEach((e) => {
                e.isIntersecting && ((e.target.style.opacity = "1"), (e.target.style.transform = "translateY(0)"));
            });
        },
        { threshold: 0.1, rootMargin: "0px 0px -50px 0px" }
    );

    document.addEventListener("DOMContentLoaded", function () {
        document.querySelectorAll(".shipping-card, .zone-card").forEach((e) => {
            (e.style.opacity = "0"),
                (e.style.transform = "translateY(30px)"),
                (e.style.transition = "opacity 0.6s ease, transform 0.6s ease"),
                t.observe(e);
        });
        const e = document.querySelector(".pr_search_trigger.sticky"),
            o = document.querySelector(".product_search_form.sticky");
        e.addEventListener("click", function () {
            e.classList.toggle("show"), o.classList.toggle("show");
        });
        const s = document.querySelector(".more_categories_sticky");
        document.querySelector(".more_slide_open_sticky");
        s.addEventListener("click", function () {
            s.classList.toggle("show");
        });
    });

    new Swiper(".productsCarruselSwiper", {
        slidesPerView: 5,
        spaceBetween: 20,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        loop: true,
        pagination: false,
        breakpoints: {
            0: { slidesPerView: 2, spaceBetween: 9 },
            575: { slidesPerView: 2, spaceBetween: 10 },
            768: { slidesPerView: 3 },
            991: { slidesPerView: 4, spaceBetween: 15, loop: true },
            1200: { slidesPerView: 5, spaceBetween: 15, loop: true },
        },
    });

    document.querySelectorAll(".carrusel-seccion").forEach((e, t) => {
        new Swiper(e.querySelector(".productsCarruselHome5"), {
            slidesPerView: 5,
            spaceBetween: 20,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            loop: true,
            pagination: false,
            breakpoints: {
                0: { slidesPerView: 2, spaceBetween: 9 },
                575: { slidesPerView: 2, spaceBetween: 10 },
                768: { slidesPerView: 3 },
                991: { slidesPerView: 4, spaceBetween: 15, loop: true },
                1200: { slidesPerView: 5, spaceBetween: 15, loop: true },
            },
        });
    }),

        document.querySelectorAll(".carrusel-seccion").forEach((e, t) => {
            new Swiper(e.querySelector(".productsCarruselHome3"), {
                slidesPerView: 3,
                spaceBetween: 10,
                navigation: {
                    nextEl: '.swiper-button-next',
                    prevEl: '.swiper-button-prev',
                },
                loop: true,
                pagination: false,
                breakpoints: {
                    0: { slidesPerView: 2, spaceBetween: 9 },
                    575: { slidesPerView: 2, spaceBetween: 10 },
                    768: { slidesPerView: 2 },
                    991: { slidesPerView: 2, spaceBetween: 15, loop: true },
                    1200: { slidesPerView: 3, spaceBetween: 10, loop: true },
                },
            });
        }),
        (window.onscroll = function () {
            window.pageYOffset >= s ? o.classList.add("Sticky") : o.classList.remove("Sticky");
        });
    var o = document.getElementById("masthead"),
        s = 100;

    document.querySelectorAll(".woocommerce .card .woocommerce-loop-product__title").forEach((e) => {
        e.textContent = e.textContent.toLowerCase().replace(/(^|\s)\S/g, (e) => e.toUpperCase());
    }),
        document.querySelectorAll(".product_title.entry-title").forEach((e) => {
            e.textContent = e.textContent.toLowerCase().replace(/(^|\s)\S/g, (e) => e.toUpperCase());
        }),
        e("div.BoxRedes a.Link").click(function (t) {
            t.preventDefault();
            var o = (e(window).width() - 575) / 2,
                s = "status=1,width=575,height=520,top=" + (e(window).height() - 520) / 2 + ",left=" + o;
            window.open(e(this).attr("href"), "share", s);
        });

    new Swiper(".slider-main", {
        effect: "fade",
        loop: true,
        autoplay: { delay: 5e3, disableOnInteraction: false },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        on: {
            init: () => i(),
            slideChangeTransitionStart: () => {
                document.querySelectorAll(".staggered-animation").forEach((e) => {
                    e.classList.remove("animate__animated", e.dataset.animation),
                        (e.style.opacity = 0),
                        (e.style.visibility = "hidden");
                });
            },
            slideChangeTransitionEnd: () => i(),
        },
    });

    function i() {
        document
            .querySelector(".swiper-slide-active")
            .querySelectorAll(".staggered-animation")
            .forEach((e) => {
                e.classList.add("animate__animated", e.dataset.animation),
                    (e.style.animationDelay = e.dataset.delay + "ms"),
                    (e.style.opacity = 1),
                    (e.style.visibility = "visible");
            });
    }
    
});
