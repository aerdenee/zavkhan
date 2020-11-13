$(function () {

    $('#weather').vTicker({
        speed: 800,
        pause: 5000,
        animation: 'fade',
        mousePause: false,
        showItems: 1
    });

    $('#currencyRate').vTicker({
        speed: 700,
        pause: 4000,
        animation: 'fade',
        mousePause: false,
        showItems: 1
    });
    var owlSlider = $('.owl-banner');
    owlSlider.owlCarousel({
        loop: true,
        margin: 10,
        dots: true,
        nav: false,
        mouseDrag: false,
        autoplay: true,
        animateOut: 'fadeOut',
        items: 1,
        transitionStyle: "fade",
        singleItem: true,
        pagination: false

    });

    var owlMediaNews = $('.owl-media-news');
    owlMediaNews.owlCarousel({
        loop: true,
        margin: 10,
        dots: true,
        nav: false,
        mouseDrag: false,
        autoplay: true,
        animateOut: 'fadeOut',
        items: 1,
        transitionStyle: "fade",
        singleItem: true,
        pagination: false

    });
    owlMediaNews.owlCarousel({
        loop: true,
        margin: 10,
        responsiveClass: true,
        responsive: {
            0: {
                items: 1,
                nav: true
            },
            600: {
                items: 5,
                nav: false
            },
            1000: {
                items: 6,
                nav: true,
                loop: false
            }
        }
    });


    $('._theme-tab-toperdenet-content').slimScroll({
        height: '690px'
    });
    $('._theme-live-toperdenet').slimScroll({
        height: '1000px'
    });

});

document.addEventListener('DOMContentLoaded', function () {
    if (!$().stick_in_parent) {
        console.warn('Warning - sticky.min.js is not loaded.');
        return;
    }

    $('.navbar-sticky').stick_in_parent();
    _themeShareButtons();
});

function _twitter(param) {

    if ((param.url + " " + param.text).length < 120 && isWebkitMobile()) {
        1 == confirm("Twitter App \u043d\u044d\u044d\u0445 \u04af\u04af?") ? document.location = "twitter://post?message=" + param.text + "%20" + e + ("" == param.author ? "" : "%20via @" + param.author) : window.open("http://twitter.com/share?url=" + param.url + "&text=" + param.text + ("" == param.author ? "" : "&via=" + param.author), "twitterwindow", "height=450, width=550, top=" + ($(window).height() / 2 - 225) + ", left=" + ($(window).width() / 2 - 275) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0").focus();
    } else
        window.open("http://twitter.com/share?url=" + param.url + "&text=" + param.text + ("" == param.author ? "" : "&via=" + param.author), "twitterwindow", "height=450, width=550, top=" + ($(window).height() / 2 - 225) + ", left=" + ($(window).width() / 2 - 275) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0").focus();
}

function _facebook(param) {
    window.open("http://www.facebook.com/sharer/sharer.php?u=" + param.url, "facebook-share-dialog", "width=626,height=436, top=" + ($(window).height() / 2 - 225) + ", left=" + ($(window).width() / 2 - 275) + ", toolbar=0, location=0, menubar=0, directories=0, scrollbars=0").focus()
}
function isWebkitMobile() {
    return window.navigator.userAgent.match(/iPad/i) || window.navigator.userAgent.match(/iPhone/i) || window.navigator.userAgent.match(/Android/i)
}
function _themeShareButtons() {
    var _this = "";
    var _text = "";
    var _url = document.location.href;
    $("._theme-share-twitter").each(function () {
        _this = $(this);
        var _urlEncode = encodeURIComponent(_this.attr("data-url"));
        var _textEncode = encodeURIComponent((_this.attr("data-text")));
        _text = _this.attr("data-text");
        _url = _this.attr("data-url");
        _this.click(function () {
            _twitter({urlEncode: _urlEncode, textEncode: _textEncode, url: _url, text: _text, author: 'toperdenet'});
        });
    });
    $("._theme-share-facebook").each(function () {
        _this = $(this);
        _url = encodeURIComponent(_this.attr("data-url") || document.location.href);
        encodeURIComponent(_this.attr("data-text") || document.title);
        $(_this).click(function () {
            _facebook({url: _url});
        });
    });

//    var i = $("#iksmsc");
//    s = parseInt(i.attr("fbcount")) || 0;
//    r = i.attr("nid");
//    o = parseInt(i.attr("ldate"));
//    a = parseInt($("#ikon-datetime").attr("cdate"));
//    $("._theme-share-facebook").each(function () {
//        $(this).find(".count").html(s);
//    });
//    $.getJSON("https://graph.facebook.com/?id=" + t + "&fields=" + encodeURIComponent("og_object{engagement}"), function (e) {
//        if (e.og_object && e.og_object.engagement) {
//            var t = !0;
//            $("._theme-share-facebook").each(function () {
//                var i = $(this),
//                        l = e.og_object.engagement.count,
//                        d = l || 0;
//                s < d && (i.find(".count").html(d), ($(".inews").length > 0 || $("#longread").length > 0) && n == document.location.href && t && a - o > 120 && ($.post("/nsc/" + r, {
//                    authenticity_token: $("#wrapper").attr("at"),
//                    fb: d,
//                    tw: 0
//                }).done(function () {
//                }), t = !1))
//            })
//        }
//    });
}
