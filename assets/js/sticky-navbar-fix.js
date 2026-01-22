/**
 * Minimal Sticky Navbar Fix
 * Simply ensures navbar always stays at top with inline styles
 */
(function () {
    'use strict';

    // Force navbar to top on load
    window.addEventListener('DOMContentLoaded', function () {
        var nav = document.getElementById('sticky');
        if (nav) {
            nav.style.cssText = 'position:fixed!important;top:0!important;left:0!important;right:0!important;width:100%!important;z-index:9999!important;display:block!important;';
        }
    });

    // Also force on window load as backup
    window.addEventListener('load', function () {
        var nav = document.getElementById('sticky');
        if (nav) {
            nav.style.cssText = 'position:fixed!important;top:0!important;left:0!important;right:0!important;width:100%!important;z-index:9999!important;display:block!important;';
        }
    });
})();
