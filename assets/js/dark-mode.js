;(function ($) {
    "use strict";
    $(document).ready(function() {

        /*------------ Cookie functions and color js ------------*/
        function createCookie(name, value, days) {
            var expires = ""
            if (days) {
                var date = new Date()
                date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000))
                expires = "; expires=" + date.toUTCString()
            }
            document.cookie = name + "=" + value + expires + "; path=/"
        }

        function readCookie(name) {
            var nameEQ = name + "="
            var ca = document.cookie.split(';')
            for (var i = 0; i < ca.length; i++) {
                var c = ca[i];
                while (c.charAt(0) == ' ') c = c.substring(1, c.length);
                if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
            }
            return null;
        }

        function eraseCookie(name) {
            createCookie(name, "", -1)
        }

        let prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
        let selectedNightTheme = readCookie("body_dark")

        if ( selectedNightTheme === "true" || (selectedNightTheme === null && prefersDark) ) {
            applyNight();
            $('#something').prop('checked', true)
        } else {
            applyDay();
            $('#something').prop('checked', false)
        }

        function applyNight() {
            if ( $('#ball').length ) {
                const ball = document.getElementById('ball')
                ball.style.left = "30px"
                ball.style.transition = ".3s linear"
                document.body.style.transition = ".3s linear"
            }
            $("body").addClass("body_dark")
        }

        function applyDay() {
            if ( $('#ball').length ) {
                const ball = document.getElementById('ball')
                ball.style.left = "4px"
                ball.style.transition = ".3s linear"
                document.body.style.transition = ".3s linear"
            }
            $("body").removeClass("body_dark")
        }

        $('#something').change(function () {
            if ($(this).is(':checked')) {
                applyNight()
                createCookie("body_dark", true, 999)
            } else {
                applyDay();
                createCookie("body_dark", false, 999)
            }
        })
    })
})(jQuery)