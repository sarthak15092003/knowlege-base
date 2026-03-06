<?php
/**
 * CMGALAXY Custom Header Layout
 */
$s_value = get_search_query() ? get_search_query() : '';
?>

<div class="collapse navbar-collapse cmgalaxy-header" id="navbarSupportedContent">
    
    <!-- Close Button for Mobile -->
    <button class="navbar-close-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close navigation">
        <span class="close-icon">&times;</span>
    </button>
    
    <!-- Main Header Row -->
    <div class="cmgalaxy-header-main d-flex align-items-center w-100">
        <!-- Logo Section -->
        <div class="cmgalaxy-logo-section d-flex align-items-center">
            <div class="cmgalaxy-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="d-block">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="Knowledge Base Logo" class="cmgalaxy-logo-img">
                </a>
            </div>
        </div>

        <!-- Search Section -->
        <div class="cmgalaxy-search-section flex-grow-1" style="margin-left: 0; margin-right: 20px;">
            <div class="d-flex align-items-center gap-3">
                <form action="<?php echo esc_url(home_url("/")) ?>" class="cmgalaxy-search-form flex-grow-1" method="get">
                    <div class="search-input-wrapper">
                        <input type="search" 
                               placeholder="<?php esc_attr_e("Search ('/' to focus)", 'docy'); ?>" 
                               name="s" 
                               value="<?php echo esc_attr($s_value) ?>"
                               class="cmgalaxy-search-input">
                    </div>
                </form>
                
                <!-- Ask Lex Button -->
                <a href="#" class="cmgalaxy-ask-lex-btn" style="height: 40px;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/lexlogo.svg' ); ?>" alt="Lex Logo" class="cmgalaxy-ask-lex-logo me-2">
                    Ask Lex
                </a>
            </div>
        </div>

        <!-- Right Actions -->
        <div class="cmgalaxy-actions d-flex align-items-center">
            <!-- Community Link -->
            <a href="#" class="cmgalaxy-nav-link me-3">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/community.svg' ); ?>" alt="Community" class="cmgalaxy-community-icon me-2">
                Community
            </a>

            <!-- Sign In Link -->
            <a href="#" class="cmgalaxy-nav-link">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/signin.svg' ); ?>" alt="Sign In" class="cmgalaxy-signin-icon me-2">
                Sign In
            </a>
        </div>
    </div>

    <!-- Navigation Menu Row -->
    <div class="cmgalaxy-nav-menu w-100">
        <?php
        if ( has_nav_menu('main_menu') ) {
            wp_nav_menu( array (
                'menu' => 'main_menu',
                'theme_location' => 'main_menu',
                'container' => null,
                'menu_class' => "cmgalaxy-main-nav d-flex list-unstyled mb-0",
                'walker' => new Docy_Nav_Walker(),
                'depth' => 4
            ));
        } else {
            // Default menu items if no menu is set
            ?>
            <ul class="cmgalaxy-main-nav d-flex list-unstyled mb-0">
                <li class="nav-item me-4"><a href="<?php echo home_url('/'); ?>" class="nav-link">Home</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Get Started</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Docs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Guide</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">FAQs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">API Docs</a></li>
            </ul>
            <?php
        }

        ?>
    </div>
</div>

<button id="lex-side-trigger" class="lex-side-trigger" aria-label="Open Lex Assistant">
    <svg width="12" height="20" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2l-8 10 8 10" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<!-- Lex Drawer -->
<div id="lex-drawer" class="lex-drawer" role="dialog" aria-modal="true" aria-label="Lex Assistant">
    <div class="lex-drawer-overlay"></div>
    <div class="lex-drawer-panel">
        <button class="lex-drawer-expand" id="lex-drawer-expand" aria-label="Toggle Lex Expansion">
            <svg class="lex-icon-expand" width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M8 4H4m0 0v4m0-4 5 5m7-5h4m0 0v4m0-4-5 5M8 20H4m0 0v-4m0 4 5-5m7 5h4m0 0v-4m0 4-5-5" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <svg class="lex-icon-collapse" width="12" height="20" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M2 2l8 10-8 10" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
        </button>
        <div class="lex-drawer-body">
            <div class="lex-drawer-header">
                <button class="lex-drawer-close" id="lex-drawer-close" aria-label="Close Lex">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M18 6L6 18M6 6L18 18" stroke="#292D32" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="lex-drawer-content">
                <iframe id="lex-assistant-frame" src="<?php echo esc_url( get_template_directory_uri() . '/assets/html/lex-assistant.html' ); ?>" style="width: 100%; height: 100%; border: none;" title="Lex Assistant"></iframe>
            </div>
        </div>
    </div>
</div>

</script>

<script>
// CMGALAXY Mobile Menu Toggle - Left Hamburger Only
(function($) {
    'use strict';
    
    $(document).ready(function() {
        // Only target LEFT hamburger (not the modern_sidebar_btn)
        $(document).on('click', '.mobile_menu_btn:not(.modern_sidebar_btn), .close_nav', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            var body = $('body');
            var sideMenu = $('.side_menu.dark_menu');
            var overlay = $('.click_capture');
            
            if (sideMenu.hasClass('menu-opened') || body.hasClass('menu-is-opened')) {
                sideMenu.removeClass('menu-opened');
                body.removeClass('menu-is-opened').addClass('menu-is-closed');
                overlay.fadeOut(300);
            } else {
                // Close right sidebar if open
                $('.modern_sidebar_drawer').removeClass('open');
                
                sideMenu.addClass('menu-opened');
                body.removeClass('menu-is-closed').addClass('menu-is-opened');
                overlay.fadeIn(300);
            }
        });
        
        // Close menu on overlay click
        $(document).on('click', '.click_capture', function() {
            $('.side_menu').removeClass('menu-opened');
            $('.modern_sidebar_drawer').removeClass('open');
            $('body').removeClass('menu-is-opened').addClass('menu-is-closed');
            $(this).fadeOut(300);
        });

        // Lex Side Trigger Click
        $(document).on('click', '#lex-side-trigger', function(e) {
            e.preventDefault();
            // Trigger the main Lex button click functionality
            $('.cmgalaxy-ask-lex-btn').first().click();
        });

        // Listen for messages from Lex Assistant iframe
        window.addEventListener('message', function(event) {
            if (event.data === 'close-lex') {
                $('#lex-drawer-close').click();
            }
        });
    });
})(jQuery);
</script>

<style>
/* =============================================
   LEX DRAWER STYLES
   ============================================= */

/* Drawer container — hidden by default, covers the full viewport when open */
.lex-drawer {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 999999;
    pointer-events: none;
}

.lex-drawer.open {
    display: block;
    pointer-events: auto;
}

/* Semi-transparent backdrop */
.lex-drawer-overlay {
    position: absolute;
    inset: 0;
    background: transparent;
    transition: background 0.3s ease, backdrop-filter 0.3s ease;
    animation: lex-fade-in 0.3s ease forwards;
}

.lex-drawer-panel.expanded ~ .lex-drawer-overlay {
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    pointer-events: auto;
}

.lex-drawer.closing .lex-drawer-overlay {
    animation: lex-fade-out 0.28s ease forwards;
}

/* Sliding panel */
.lex-drawer-panel {
    position: absolute;
    top: 0px;
    right: 0px;
    height: 100%;
    width: 375px;
    max-width: 95vw;
    overflow: visible;
    display: flex;
    flex-direction: column;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                top 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                right 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                border-radius 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: lex-slide-in 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    transform: translateX(100%);
    z-index: 999999;
}

.lex-drawer-body {
    position: relative;
    width: 100%;
    height: 100%;
    background: #ffffff;
    box-shadow: -12px 0 48px rgba(58, 125, 255, 0.18);
    border: 1.5px solid #e0e9f9;
    border-radius: 24px;
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.lex-drawer-panel.expanded {
    width: 85vw;
    height: 90vh;
    max-width: 90vw;
    max-height: 95vh;
    top: 50%;
    right: 50%;
    transform: translate(50%, -50%) !important;
}

.lex-drawer-panel.expanded .lex-drawer-body {
    border-radius: 20px;
}

.lex-drawer-header {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: flex-end;
    padding: 0 14px;
    background: transparent;
    z-index: 100;
    pointer-events: none;
}

.lex-drawer-header .lex-drawer-close {
    pointer-events: auto;
}

.lex-drawer-expand {
    position: absolute;
    top: 50%;
    left: -18px; /* Half of width to sit on the line */
    transform: translateY(-50%);
    background: #ffffff;
    border: 1.5px solid #e0e9f9;
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.05);
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 101;
    transition: background 0.2s ease, transform 0.25s ease, left 0.3s ease;
    flex-shrink: 0;
}

.lex-drawer-expand svg {
    transition: transform 0.3s ease;
}

.lex-icon-collapse {
    display: none;
}

.lex-drawer-panel.expanded .lex-icon-expand {
    display: none;
}

.lex-drawer-panel.expanded .lex-icon-collapse {
    display: flex;
}

.lex-drawer-expand:hover {
    background: #f8fafc;
    transform: translateY(-50%) scale(1.1);
}

.lex-drawer.closing .lex-drawer-panel {
    animation: lex-slide-out 0.28s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Side Trigger Button - Minimalist Circular */
.lex-side-trigger {
    position: fixed;
    right: -18px; /* Sit halfway off the screen edge */
    top: 50%;
    transform: translateY(-50%);
    z-index: 9999;
    background: #ffffff;
    border: 1.5px solid #f1f5f9;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 6px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.lex-side-trigger:hover {
    right: -10px;
    background: #ffffff;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

/* Vertical line behind the button */
.lex-side-trigger::before {
    content: '';
    position: absolute;
    right: 17px;
    top: -100vh;
    height: 200vh;
    width: 2px;
    background: #e2e8f0;
    z-index: -1;
    pointer-events: none;
}

.lex-side-trigger svg {
    transition: transform 0.3s ease;
}

.lex-side-trigger:hover svg {
    transform: translateX(-2px);
}

/* Hide side trigger when drawer is open */
body.lex-drawer-open .lex-side-trigger {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-50%) translateX(100%);
}

/* Keyframe animations */
@keyframes lex-slide-in {
    from { transform: translateX(100%); }
    to   { transform: translateX(0); }
}

@keyframes lex-slide-out {
    from { transform: translateX(0); }
    to   { transform: translateX(100%); }
}

@keyframes lex-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

@keyframes lex-fade-out {
    from { opacity: 1; }
    to   { opacity: 0; }
}

/* Close button */
.lex-drawer-close {
    position: relative;
    background: #f3f4f6;
    border: none;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 10;
    transition: background 0.2s ease, transform 0.25s ease;
    flex-shrink: 0;
}

.lex-drawer-close:hover {
    background: #dbeafe;
    transform: rotate(90deg);
}

.lex-drawer-content {
    flex: 1;
    display: block;
    overflow: hidden;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}

.lex-drawer-content iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: none;
    margin: 0;
    padding: 0;
}

.lex-drawer-img {
    width: 100%;
    height: auto;
    display: block;
    user-select: none;
    pointer-events: none;
}

/* Prevent body scroll while drawer is open */
body.lex-drawer-open {
    overflow: hidden !important;
}

/* =============================================
   CSS Fixes for Mobile Menu Visibility and Interaction
   ============================================= */
@media (max-width: 1024px) {
    /* Ensure the mobile bar is ALWAYS on top */
    .mobile_main_menu {
        z-index: 100000 !important;
        background: #ffffff !important;
    }
    
    /* Ensure the hamburger button is clickable */
    .mobile_menu_btn {
        position: relative !important;
        z-index: 100001 !important;
        cursor: pointer !important;
        background: transparent !important;
        border: none !important;
        padding: 10px !important;
    }
    
    /* Hide desktop items absolutely */
    #sticky, .header, .cmgalaxy-header {
        display: none !important;
    }
    
    /* Show the mobile menu bar if theme hid it */
    .mobile_main_menu.display_none {
        display: block !important;
    }
}

/* Side Menu and Overlay z-index */
.side_menu {
    z-index: 100002 !important;
}

.click_capture {
    z-index: 100001 !important;
    background: rgba(0,0,0,0.5) !important;
}

/* Ensure body doesn't scroll when menu is open */
body.menu-is-opened {
    overflow: hidden !important;
}

<style>
/* CMGALAXY Header Styles */
.cmgalaxy-header {
    flex-direction: column !important;
    padding: 1rem 0;
}



.cmgalaxy-logo-section {
    min-width: 200px;
}

.cmgalaxy-logo-img {
    max-height: 50px;
    height: auto;
    width: auto;
    max-width: 300px;
    object-fit: contain;
}

.cmgalaxy-brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    text-decoration: none;
    line-height: 1.2;
}

.cmgalaxy-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-top: -2px;
}

.cmgalaxy-search-section {
    max-width: 500px;
}

.cmgalaxy-search-form {
    width: 100%;
}

.search-input-wrapper {
    position: relative;
    width: 100%;
}

.cmgalaxy-search-input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background-color: #f9fafb;
    transition: all 0.2s ease;
}

.cmgalaxy-search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.search-icon-left {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 0.875rem;
}

.cmgalaxy-actions {
    min-width: 250px;
    justify-content: flex-end;
}

.cmgalaxy-ask-lex-btn {
    background: white;
    color: #484A61;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cmgalaxy-ask-lex-logo {
    width: 20px;
    height: auto;
    display: inline-block;
}

.cmgalaxy-ask-lex-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.cmgalaxy-nav-link {
    color: #4b5563;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.cmgalaxy-nav-link:hover {
    color: #1f2937;
    text-decoration: none;
}

.cmgalaxy-community-icon {
    width: 24px;
    height: auto;
    display: inline-block;
}

.cmgalaxy-signin-icon {
    width: 24px;
    height: auto;
    display: inline-block;
}

.cmgalaxy-nav-menu {
    padding-top: 0px;
    margin-top: 0px !important;
    padding-bottom: 0rem !important;
}

.menu-is-opened .navbar-collapse.cmgalaxy-header,
.menu-is-opened .cmgalaxy-nav-menu {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
}

.cmgalaxy-main-nav {
    gap: 2rem;
}

.cmgalaxy-main-nav .nav-item {
    margin: 0;
}

.cmgalaxy-main-nav .nav-link {
    color: #4b5563;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 0;
    text-decoration: none;
    transition: color 0.2s ease;
    border-bottom: 2px solid transparent;
}

.cmgalaxy-main-nav .nav-link:hover,
.cmgalaxy-main-nav .nav-item.active .nav-link {
    color: #1f2937;
    border-bottom-color: #3b82f6;
}

/* Desktop Header Main Padding */
@media (min-width: 1025px) {
    #sticky .container {
        padding-left: 30px !important;
        padding-right: 30px !important;
        max-width: 100% !important;
    }

    /* Target single post page specifically for 40px header padding */
    body.single-post #sticky .container {
        padding-left: 30px !important;
        padding-right: 30px !important;
    }

    .cmgalaxy-header {
        padding-bottom: 0 !important;
    }

    .cmgalaxy-header-main {
        padding-left: 0px;
        padding-right: 0px; /* Removed individual padding */
    }
    
    .cmgalaxy-nav-menu {
        padding-left: 0px;
        padding-right: 0px; /* Removed individual padding */
    }
}

/* Mobile & Tablet Responsive Styles */
@media (max-width: 768px) {
    /* Navbar adjustments for mobile */
    .header .navbar,
    .navbar.sticky-nav,
    nav.menu_one,
    #sticky {
        padding: 0.5rem 0 !important;
    }

    /* Removed conflicting styles - now controlled by cmgalaxy-header.css */

    .cmgalaxy-header-container {
        flex-direction: column;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
    }

    .cmgalaxy-logo-section {
        width: 100%;
        min-width: auto;
        justify-content: center;
    }

    .cmgalaxy-logo-img {
        max-height: 40px;
    }

    .cmgalaxy-search-section {
        width: 100%;
        max-width: 100%;
    }

    .cmgalaxy-actions {
        width: 100%;
        min-width: auto;
        justify-content: center;
        gap: 1rem;
    }

    /* Removed conflicting nav-menu styles */

    .cmgalaxy-main-nav {
        flex-wrap: nowrap;
        gap: 1rem;
        padding: 0 1rem;
    }

    .cmgalaxy-main-nav .nav-item {
        white-space: nowrap;
    }
}

@media (max-width: 576px) {
    /* Mobile-specific navbar adjustments */
    .header .navbar,
    .navbar.sticky-nav,
    nav.menu_one,
    #sticky {
        padding: 0.25rem 0 !important;
    }

    .cmgalaxy-header-container {
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
    }

    .cmgalaxy-logo-img {
        max-height: 35px;
    }

    .cmgalaxy-search-input {
        padding: 0.5rem 0.75rem 0.5rem 2rem;
        font-size: 0.8125rem;
    }

    .search-icon-left {
        left: 0.5rem;
        font-size: 0.75rem;
    }

    .cmgalaxy-ask-lex-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .cmgalaxy-ask-lex-logo {
        width: 16px;
    }

    .cmgalaxy-nav-link {
        font-size: 0.8125rem;
    }

    .cmgalaxy-main-nav {
        gap: 0.75rem;
        padding: 0 0.75rem;
    }

    .cmgalaxy-main-nav .nav-link {
        font-size: 0.8125rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 480px) {
    /* Extra small mobile devices */
    .cmgalaxy-header-container {
        padding: 0.5rem;
    }

    .cmgalaxy-logo-img {
        max-height: 30px;
    }

    .cmgalaxy-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    .cmgalaxy-ask-lex-btn,
    .cmgalaxy-nav-link {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 1024px) {
    /* NUCLEAR HIDE: Ensure desktop header layout NEVER shows on mobile */
    #sticky,
    .header,
    .navbar-collapse.cmgalaxy-header,
    .cmgalaxy-header-main,
    .cmgalaxy-nav-menu,
    #navbarSupportedContent {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
    }
}

/* Desktop Header Visibility */
@media (min-width: 1025px) {
    .navbar-collapse.cmgalaxy-header {
        display: flex !important;
    }
}

/* Sticky/Fixed Navbar - Using position: fixed for better browser support */
.header {
    position: relative;
}

.header .navbar,
.navbar.sticky-nav,
nav.menu_one,
#sticky {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    z-index: 9999 !important;
    background-color: #ffffff !important;
    border-bottom: 1px solid #e5e7eb !important;
    padding: 0px !important;
    margin: 0 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    transform: none !important;
}

/* Super aggressive override for inline styles */
nav#sticky[style] {
    top: 0 !important;
    position: fixed !important;
    display: block !important;
}

.header .navbar.scrolled {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
}

/* Add padding to body to prevent content from hiding under fixed navbar */
body {
    padding-top: 114px !important; /* Space for fixed navbar */
}

/* Adjust for mobile */
@media (max-width: 1024px) {
    body {
        padding-top: 70px !important; /* Smaller space for mobile header */
    }
    
    .body_wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
}

/* Logo is now visible in navbar */

/* Keyboard shortcut styling */
.cmgalaxy-search-input::placeholder {
    color: #9ca3af;
}
</style>

