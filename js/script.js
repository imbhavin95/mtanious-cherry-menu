!(function (a) {
    ('use strict');
    console.log(123);
    a(window).on('load', function () {
        a('.loader-inner').fadeOut(), a('.loader').delay(200).fadeOut('slow');
    });
    var b = a('.header'),
        c = b.offset();
    a(window).on('scroll', function () {
        a(this).scrollTop() > c.top + 500 && b.hasClass('default')
            ? b.fadeOut('fast', function () {
                  a(this).removeClass('default').addClass('switched-header').fadeIn(200);
              })
            : a(this).scrollTop() <= c.top + 500 &&
              b.hasClass('switched-header') &&
              b.fadeOut('fast', function () {
                  a(this).removeClass('switched-header').addClass('default').fadeIn(100);
              });
    }),
        // a('a.scroll').smoothScroll({ speed: 800, offset: -66 }),
        a('.venobox').venobox({ titleattr: 'data-title', numeratio: !0 }),
        a('#carousel').owlCarousel({
            navigation: 1,
            slideSpeed: 300,
            paginationSpeed: 400,
            responsiveRefreshRate: 200,
            responsiveBaseWidth: window,
            pagination: !0,
            autoPlay: !0,
            singleItem: !0,
        });
    var d = a('.mobile-but'),
        e = a('.main-nav ul');
    e.height();
    a(d).on('click', function () {
        return a('.toggle-mobile-but').toggleClass('active'), e.slideToggle(), a('.main-nav li a').addClass('mobile'), !1;
    }),
        a(window).on('resize', function () {
            var b = a(window).width();
            b > 320 && e.is(':hidden') && (e.removeAttr('style'), a('.main-nav li a').removeClass('mobile'));
        }),
        a('.main-nav li a').on('click', function () {
            a(this).hasClass('mobile') && (e.slideToggle(), a('.toggle-mobile-but').toggleClass('active'));
        });
})(jQuery);
