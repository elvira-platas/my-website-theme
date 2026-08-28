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
        var $responsiveMenu = $(".kilka-responsive-menu");
        var $backToTop = $(".back-to-top");
        var $exhibition = $(".kilka-exhibition");
        var menuLabel = $responsiveMenu.data("menu-label") || "Menu";

        $primaryMenu.kilkaAccessibleDropDown();
        $primaryMenu.slicknav({
            allowParentLinks: true,
            label: menuLabel,
            prependTo: ".kilka-responsive-menu",
            nestedParentLinks: false,
            closeOnClick: true
        });

        $responsiveMenu.find(".slicknav_btn").attr("aria-label", menuLabel);

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

        if ($exhibition.length) {
            var $information = $exhibition.find(".kilka-exhibition__information");
            var $informationToggle = $exhibition.find(".kilka-exhibition__information-toggle");
            var $informationClose = $exhibition.find(".kilka-exhibition__information-close");
            var lastFocusedElement = null;

            var closeInformation = function () {
                $information.attr("hidden", "hidden");
                $informationToggle.attr("aria-expanded", "false");
                $("body").removeClass("kilka-exhibition-information-open");
                $(document).off("keydown.kilkaExhibitionInformation");

                if (lastFocusedElement) {
                    lastFocusedElement.focus();
                }
            };

            var openInformation = function () {
                lastFocusedElement = document.activeElement;
                $information.removeAttr("hidden");
                $informationToggle.attr("aria-expanded", "true");
                $("body").addClass("kilka-exhibition-information-open");
                $informationClose.focus();

                $(document).on("keydown.kilkaExhibitionInformation", function (event) {
                    var $focusableElements;

                    if (event.key === "Escape") {
                        event.preventDefault();
                        closeInformation();
                    } else if (event.key === "Tab") {
                        $focusableElements = $information.find("a[href], button:not([disabled])").filter(":visible");

                        if (event.shiftKey && document.activeElement === $focusableElements.first()[0]) {
                            event.preventDefault();
                            $focusableElements.last().focus();
                        } else if (!event.shiftKey && document.activeElement === $focusableElements.last()[0]) {
                            event.preventDefault();
                            $focusableElements.first().focus();
                        }
                    }
                });
            };

            $informationToggle.on("click", openInformation);
            $informationClose.on("click", closeInformation);
            $information.on("click", function (event) {
                if (event.target === this) {
                    closeInformation();
                }
            });
        }
    });
}(jQuery));
