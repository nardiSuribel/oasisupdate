$(".slider-home").slick({
    infinite: false,
    autoplay: true,
    fade: true,
    speed: 1500,
    slidesToShow: 1,
    slidesToScroll: 1,
    arrows: false,
    dots: true,
    dotsClass: "custom-dots",
    swipe: false,
    slide: "article",
});

$('.fade').slick({
    dots: true,
    infinite: true,
    speed: 500,
    fade: true,
    cssEase: 'linear',
    dotsClass: "custom-dots",
  });
console.log("javascript");