jQuery(function ($) {
    var para = {
        slidesToShow: 1,
        slidesToScroll: 1,
        dots: true,
        infinite: true,
        cssEase: 'linear',
        autoplay: true,
        autoplaySpeed: 2000,
    };
    $('.slider').slick(para);
});