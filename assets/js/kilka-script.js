(function ($) {
    "use strict";

    $.fn.kilkaAccessibleDropDown = function () {
        var el = $(this);

        $("a", el).focus(function () {
            $(this).parents("li").addClass("hover");
        }).blur(function () {
            $(this).parents("li").removeClass("hover");
        });
    };

    $(".menu-close").on("click", function () {
        $("a.slicknav_btn").removeClass("slicknav_open");
        $(".slicknav_nav").css("display", "none");
    });

    $(document).ready(function () {
        var $primaryMenu = $("#primary-menu");
        var $backToTop = $(".back-to-top");

        $primaryMenu.kilkaAccessibleDropDown();
        $primaryMenu.slicknav({
            allowParentLinks: true,
            prependTo: ".kilka-responsive-menu",
            nestedParentLinks: false,
            closeOnClick: true
        });

        if ($backToTop.length) {
            var getScrollTop = function () {
                return window.pageYOffset || document.documentElement.scrollTop || document.body.scrollTop || 0;
            };

            var getBackToTopThreshold = function () {
                return window.matchMedia && window.matchMedia("(max-width: 767px)").matches ? 200 : 400;
            };

            var updateBackToTop = function () {
                $backToTop.toggleClass("is-visible", getScrollTop() > getBackToTopThreshold());
            };

            updateBackToTop();
            $(window).on("scroll.kilkaBackToTop resize.kilkaBackToTop pageshow.kilkaBackToTop", updateBackToTop);
            $(document).on("scroll.kilkaBackToTop touchmove.kilkaBackToTop", updateBackToTop);

            $backToTop.on("click", function () {
                var reduceMotion = window.matchMedia && window.matchMedia("(prefers-reduced-motion: reduce)").matches;

                if (!reduceMotion && "scrollBehavior" in document.documentElement.style) {
                    window.scrollTo({
                        top: 0,
                        behavior: "smooth"
                    });
                } else {
                    window.scrollTo(0, 0);
                }
            });
        }
    });
}(jQuery));
