<?php
$opt = get_option( 'docy_opt' );
?>
<div class="mobile_main_menu <?php Docy_helper()->navbar_type(); ?>" id="<?php docy_sticky_navbar('id', 'mobile') ?>">
    <div class="container">
        <div class="mobile_menu_left">
            <button type="button" class="navbar-toggler mobile_menu_btn">
                <span class="menu_toggle ">
                    <span class="hamburger">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </span>
            </button>
            <div class="cmgalaxy-mobile-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="CMGALAXY Knowledge Base" style="height: 32px; width: auto;">
                </a>
            </div>
        </div>
        <div class="mobile_menu_right">
            <!-- Right Hamburger for Modern Sidebar -->
            <button type="button" class="navbar-toggler modern_sidebar_btn">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 12H21M3 6H21M3 18H21" stroke="#333" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</div>

<!-- Left Side Menu (Main Navigation) -->
<div class="side_menu dark_menu">
    <div class="mobile_menu_header">
        <div class="mobile_logo">
            <h3 class="cmgalaxy-brand mb-0" style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0;">CMGALAXY</h3>
        </div>
        <div class="close_nav">
            <i class="icon_close"></i>
        </div>
    </div>

    <div class="mobile_nav_wrapper">
        <nav class="mobile_nav_bottom">
            <?php
            if ( has_nav_menu('main_menu') ) {
                wp_nav_menu( array (
                    'menu' => 'main_menu',
                    'theme_location' => 'main_menu',
                    'container' => null,
                    'menu_class' => "navbar-nav menu ml-auto",
                    'walker' => new Docy_Mobile_Nav_Walker(),
                    'depth' => 4
                ));
            }
            ?>
        </nav>
    </div>
</div>

<!-- Right Side Menu (Modern Sidebar with Expandable Categories) -->
<div class="modern_sidebar_drawer">
    <div class="mobile_menu_header">
        <div class="mobile_logo">
            <h3 class="cmgalaxy-brand mb-0" style="font-size: 1.5rem; font-weight: 700; color: #1f2937; margin: 0;">Navigate</h3>
        </div>
        <div class="close_modern_sidebar">
            <i class="icon_close"></i>
        </div>
    </div>
    <div class="modern_sidebar_drawer_content">
        <?php
        // Get categories
        $categories = get_categories(array(
            'orderby' => 'name',
            'order'   => 'ASC',
            'hide_empty' => false,
        ));
        
        if (!empty($categories)) :
            foreach ($categories as $category) :
                // Get posts in this category
                $cat_posts = get_posts(array(
                    'category' => $category->term_id,
                    'posts_per_page' => -1,
                    'orderby' => 'date',
                    'order' => 'DESC'
                ));
        ?>
            <div class="mobile-category-accordion">
                <div class="mobile-category-header" data-category="<?php echo esc_attr($category->slug); ?>">
                    <span class="category-name"><?php echo esc_html($category->name); ?></span>
                    <span class="category-toggle">
                        <span class="category-count"><?php echo esc_html($category->count); ?></span>
                        <svg class="toggle-arrow" width="12" height="12" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="mobile-category-posts" id="cat-<?php echo esc_attr($category->slug); ?>">
                    <?php if (!empty($cat_posts)) : ?>
                        <?php foreach ($cat_posts as $post) : ?>
                            <a href="<?php echo esc_url(get_permalink($post->ID)); ?>" class="mobile-post-item">
                                <?php echo esc_html($post->post_title); ?>
                            </a>
                        <?php endforeach; ?>
                    <?php else : ?>
                        <span class="no-posts">No articles yet</span>
                    <?php endif; ?>
                </div>
            </div>
        <?php
            endforeach;
        endif;
        ?>
    </div>
</div>

<!-- Overlay -->
<div class="click_capture"></div>

<style>
/* LEFT SIDE MENU - Slide from left */
.side_menu.dark_menu {
    position: fixed !important;
    top: 0 !important;
    left: -320px !important;
    width: 300px !important;
    height: 100% !important;
    background: #ffffff !important;
    z-index: 100002 !important;
    transition: left 0.3s ease !important;
    box-shadow: 2px 0 10px rgba(0,0,0,0.1) !important;
    overflow-y: auto !important;
}

.side_menu.dark_menu.menu-opened {
    left: 0 !important;
    box-shadow: 5px 0 25px rgba(0,0,0,0.15) !important;
}

/* Modern Sidebar Drawer Styles */
.modern_sidebar_btn {
    display: flex !important;
    align-items: center;
    justify-content: center;
    background: transparent !important;
    border: none !important;
    padding: 8px !important;
    cursor: pointer !important;
}

.modern_sidebar_drawer {
    position: fixed;
    top: 0;
    right: -320px;
    width: 300px;
    height: 100%;
    background: #ffffff;
    z-index: 100002;
    transition: right 0.3s ease;
    box-shadow: -2px 0 10px rgba(0,0,0,0.1);
    overflow-y: auto;
}

.modern_sidebar_drawer.open {
    right: 0;
}

.modern_sidebar_drawer .mobile_menu_header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 15px 20px;
    border-bottom: 1px solid #e5e7eb;
    position: sticky;
    top: 0;
    background: #fff;
    z-index: 10;
}

.close_modern_sidebar {
    font-size: 20px;
    cursor: pointer;
    color: #374151;
}

.modern_sidebar_drawer_content {
    padding: 10px;
}

/* Accordion Styles */
.mobile-category-accordion {
    margin-bottom: 5px;
    border-radius: 8px;
    overflow: hidden;
}

.mobile-category-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 14px 15px;
    background: #f8fafc;
    cursor: pointer;
    transition: all 0.2s ease;
    border-radius: 8px;
}

.mobile-category-header:hover {
    background: #e5e7eb;
}

.mobile-category-header.active {
    background: #3b82f6;
    color: white;
    border-radius: 8px 8px 0 0;
}

.mobile-category-header.active .category-count {
    background: white;
    color: #3b82f6;
}

.mobile-category-header.active .toggle-arrow {
    transform: rotate(90deg);
}

.category-name {
    font-weight: 500;
    font-size: 14px;
}

.category-toggle {
    display: flex;
    align-items: center;
    gap: 8px;
}

.category-count {
    background: #3b82f6;
    color: white;
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
    font-weight: 600;
}

.toggle-arrow {
    transition: transform 0.2s ease;
}

/* Posts List */
.mobile-category-posts {
    display: none;
    background: #f8fafc;
    border-radius: 0 0 8px 8px;
    padding: 0;
}

.mobile-category-posts.open {
    display: block;
}

.mobile-post-item {
    display: block;
    padding: 12px 15px 12px 25px;
    color: #374151;
    text-decoration: none;
    font-size: 13px;
    border-bottom: 1px solid #e5e7eb;
    transition: all 0.2s ease;
}

.mobile-post-item:last-child {
    border-bottom: none;
}

.mobile-post-item:hover {
    background: #e5e7eb;
    color: #1f2937;
    padding-left: 30px;
}

.no-posts {
    display: block;
    padding: 12px 15px;
    color: #9ca3af;
    font-size: 13px;
    font-style: italic;
}
</style>

<script>
(function($) {
    'use strict';
    $(document).ready(function() {
        // Toggle Modern Sidebar (Right)
        $(document).on('click', '.modern_sidebar_btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            // Close left menu first
            $('.side_menu.dark_menu').removeClass('menu-opened');
            
            $('.modern_sidebar_drawer').addClass('open');
            $('.click_capture').fadeIn(300);
            $('body').addClass('menu-is-opened');
        });
        
        // Close Modern Sidebar
        $(document).on('click', '.close_modern_sidebar', function(e) {
            e.preventDefault();
            $('.modern_sidebar_drawer').removeClass('open');
            $('.click_capture').fadeOut(300);
            $('body').removeClass('menu-is-opened');
        });
        
        // Toggle Category Accordion
        $(document).on('click', '.mobile-category-header', function(e) {
            e.preventDefault();
            var $this = $(this);
            var catSlug = $this.data('category');
            var $posts = $('#cat-' + catSlug);
            var isOpen = $this.hasClass('active');
            
            // Close all other accordions first
            $('.mobile-category-header').not($this).removeClass('active');
            $('.mobile-category-posts').not($posts).slideUp(200);
            
            // Toggle current based on state
            if (isOpen) {
                $this.removeClass('active');
                $posts.slideUp(200);
            } else {
                $this.addClass('active');
                $posts.slideDown(200);
            }
        });
        
        // Close on overlay click
        $(document).on('click', '.click_capture', function() {
            $('.modern_sidebar_drawer').removeClass('open');
        });
    });
})(jQuery);
</script>