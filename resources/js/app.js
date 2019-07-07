$(window).scroll(function () {
    var scroll = $(window).scrollTop();
    var nav = $("nav");
    var ts = nav.attr("transparant_nav");

    if (ts === "false") {
        if (scroll > 1) {
            if (!nav.hasClass("shadow")) {
                nav.addClass("shadow");
            }
        } else {
            if (nav.hasClass("shadow")) {
                nav.removeClass("shadow");
            }
        }
    }

    if (scroll > 50) {
        if (ts === "true") {
            if (nav.attr("ts_changed") === "false") {
                nav.attr("transparant_nav", "false");
                nav.attr("ts_changed", "true")
            }
        }
    } else {
        if (ts === "false") {
            if (nav.attr("ts_changed") === "true") {
                nav.attr("transparant_nav", "true");
                nav.attr("ts_changed", "false")
            }
        }
    }
});