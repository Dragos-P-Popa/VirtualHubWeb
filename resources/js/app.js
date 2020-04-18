var header_style_changed = false;

$(document).scroll(function () {
    navigationScrollingStyle();
});


$(document).resize(function () {
    navigationScrollingStyle();
});

$(document).ready(function() {
    var w = $(document);
    w.scroll();
    w.resize();

    checkCookie();
});

$(".cookie-consent__agree").click(function () {
    checkCookie();
});

function checkCookie() {
    var cookieDiv = $(".cookieDiv");
    if (document.cookie.indexOf("laravel_cookie_consent") >= 0) {
        cookieDiv.remove();
    }
    else {
        cookieDiv.css('display', 'flex');
    }
}


function navigationScrollingStyle() {
    var $nav = $("nav");
    var $s = $nav.attr("s");
    var w = $(document);

    if ($s === "header_style") {
        $nav.toggleClass('header_style', w.scrollTop() < $nav.height() * 2);
        $nav.toggleClass('shadow', w.scrollTop() > $nav.height() * 2);
    } else {
        $nav.toggleClass('shadow', w.scrollTop() > 1);
    }

}


function addShadowToElement(el) {
    if (!el.hasClass("shadow")) {
        el.addClass("shadow");
    }
}

function removeShadowFromElement(el) {
    if (el.hasClass("shadow")) {
        el.removeClass("shadow");
    }
}

import './date-time-picker.min';
