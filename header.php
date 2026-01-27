<?php
/**
 * The header for our theme
 *
 * This is the template that displays all the <head> section
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package docy
 */

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <!-- Theme Version -->
        <!-- Charset Meta -->
        <meta charset="<?php bloginfo('charset' ); ?>">
        <!-- For IE -->
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <!-- For Responsive Device -->
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <?php wp_head(); ?>
        
        <?php
        // Remove shadow and add stroke for cards on ?cat=1 page only
        if ( isset( $_GET['cat'] ) && intval( $_GET['cat'] ) === 1 ) : ?>
        <style id="cat-1-no-shadow">
            /* NUCLEAR: Remove shadows from cards on cat=1 page */
            div.blog_grid_post,
            div.blog_grid_post div,
            div.blog_grid_post:hover,
            div.blog_grid_post:hover div,
            div.card,
            a.card,
            div.category-card,
            a.category-card,
            .grid_post_content {
                box-shadow: 0 0 0 0 transparent !important;
                -webkit-box-shadow: 0 0 0 0 transparent !important;
                -moz-box-shadow: 0 0 0 0 transparent !important;
            }
            div.blog_grid_post,
            div.card,
            a.card,
            div.category-card,
            a.category-card {
                border: 2px solid #ccc !important;
                border-radius: 4px !important;
            }
            div.blog_grid_post:hover,
            div.card:hover,
            a.card:hover,
            div.category-card:hover,
            a.category-card:hover {
                border-color: #666 !important;
            }
        </style>
        <?php endif; ?>
    </head>

    <body <?php body_class(); docy_has_scrollspy() ?> >
        <?php
        if ( function_exists('wp_body_open') ) {
            wp_body_open();
        }

        /**
         * Preloader
         */
        if ( docy_opt('is_preloader') == '1' ) {
            get_template_part('template-parts/header-elements/preloader');
        }
        ?>

        <div class="body_wrapper <?php docy_body_wrapper_classes() ?>">
            <div class="click_capture"></div>

            <?php
            if ( docy_opt('header_style') == 'elementor' && in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
                ?>
                <header id="docy-header" class="docy-header">
                    <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display(docy_opt('header_el_template')); ?>
                </header>
                <?php
            } else {
                ?>
                <header class="header">
                    <?php
                    if ( docy_opt('is_top_header') == '1' ) {
                        get_template_part( 'template-parts/header-elements/top-header' );
                    }
                    ?>
                    <nav <?php docy_navbar_class() ?> id="<?php docy_sticky_navbar('id') ?>">
                        <div class="<?php docy_nav_container() ?>">
                            <?php // Docy_helper()->logo(); ?>
                            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                                    aria-expanded="false" aria-label="<?php esc_attr_e('Toggle navigation', 'docy'); ?>">
                                <span class="menu_toggle">
                                    <span class="hamburger">
                                        <span></span>
                                        <span></span>
                                        <span></span>
                                    </span>
                                </span>
                            </button>
                            <?php get_template_part( 'template-parts/header-elements/layout', 'cmgalaxy' ); ?>
                        </div>
                    </nav>
                </header>

                <?php
                /**
                 * Mobile menu
                 */
                get_template_part( 'template-parts/header-elements/mobile-menu' );
            }

            // Search Banner
            echo '<!-- CMG_DEPLOY_CHECK_VER_101 -->';
            $meta_value   = docy_meta_apply( 'is_banner', '1');
            $is_cat_query = (isset( $_GET['cat'] ) && ! empty( $_GET['cat'] )) || is_category() || is_archive() || is_tag() || is_tax();

            if ( $meta_value == '1' && ! is_singular( 'post' ) && ! is_404() && ! $is_cat_query ) {
                $is_banner_meta = docy_meta('is_banner');
                $homepage_ids = docy_homepage_ids();
                if ( isset($is_banner_meta) && $is_banner_meta != '1' && in_array( get_the_ID(), $homepage_ids ) ) {
                    echo '<!-- BANNER_SKIPPED_BY_META -->';
                } else {
                    get_template_part( 'template-parts/header-elements/search-banner/sbnr', docy_search_banner() );
                }
            } else {
                echo "<!-- BANNER_HIDDEN: cat_query=" . ($is_cat_query?'Y':'N') . " -->";
            }