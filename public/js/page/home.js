$(document).ready(function () {
    $(".owl-carousel").owlCarousel({
        center: true,
        items: 2,
        loop: true,
        margin: 150,
        nav: true,
        autoplay: false,
        autoplayTimeout: 8000,
        autoplaySpeed: 800,
        navSpeed: 800,
        dots: false,
        responsive: {
            0: {
                margin: 20,
                items: 1,
                center: false,
            },
            720: {
                items: 2
            }
        }
    });
});