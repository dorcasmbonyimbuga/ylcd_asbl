$(document).ready(function () {
  // HERO SLIDER

  $("#hero-slider").owlCarousel({
    loop: true,
    margin: 0,
    nav: true,
    items: 1,
    dots: false,
    smartSpeed: 1000,
    autoplay: true,
    autoplayTimeout: 3000,
    autoplayHoverPause: false,

    // navText:['PREV','NEXT'],
    responsive: {
      0: {
        nav: false,
      },
      600: {
        nav: true,
      },
      1000: {},
    },
  });
  // PROJECT SLIDER

  $("#project-slider").owlCarousel({
    loop: true,
    margin: 0,
    nav: false,
    dots: true,
    autoplay: true,
    autoplayTimeout: 5000,
    smartSpeed: 1000,
    margin: 24,
    responsive: {
      0: {
        items: 1,
        margin: 0,
      },
      600: {
        items: 2,
        center: true,
      },
      1000: {
        items: 3,
        center: true,
      },
    },
  });
});

var yearEl = document.getElementById("year");
if (yearEl) yearEl.textContent = new Date().getFullYear();
