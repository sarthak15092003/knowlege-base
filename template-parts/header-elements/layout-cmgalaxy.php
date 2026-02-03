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
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/lexlogo.png' ); ?>" alt="Lex Logo" class="cmgalaxy-ask-lex-logo me-2">
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
    });
})(jQuery);
</script>

<style>
/* CSS Fixes for Mobile Menu Visibility and Interaction */
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
    padding-top: 120px !important; /* Space for fixed navbar */
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

