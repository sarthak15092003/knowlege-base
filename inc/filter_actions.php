<?php
// Theme settings options
$opt = get_option('docy_opt' );

// Image sizes
add_image_size('docy_60x60', 60, 60, true); // Forum Thumbnail
add_image_size('docy_270x320', 270, 320, true); // Product Thumbnail
add_image_size('docy_300x320', 300, 320, true); // Product Thumbnail
add_image_size('docy_370x360', 370, 360, true); // Posts carousel thumbnail
add_image_size('docy_844x400', 844, 400, true); // Blog post list format
add_image_size('docy_410x220', 410, 220, true); // Blog post grid format
add_image_size('docy_370x200', 370, 200, true); // Related post-thumbnail
add_image_size('docy_670x450', 670, 450, true); // Blog Category Page Sticky post thumbnail
add_image_size('docy_18x18', 18, 18, true); // Forum post topic category thumbnail
add_image_size('docy_20x20', 20, 20, true); // Forum post topic category thumbnail
add_image_size('docy_570x345', 570, 345, true); // Blog single colorful thumbnail

// add category nicknames in body and post class
function docy_post_class( $classes ) {
    global $post;
    if ( !has_post_thumbnail() ) {
        $classes[] = 'no-post-thumbnail';
    }
    if ( is_sticky() && !in_array('sticky', $classes) ) {
        $classes[] = 'sticky';
    }
    return $classes;
}
add_filter( 'post_class', 'docy_post_class' );

/**
 * Body classes
 */
add_filter( 'body_class', function($classes) {

    $page_doc_width = docy_meta('doc_width', 'default');

    if ( $page_doc_width == 'default' || $page_doc_width == '' ) {
        $header_width   = docy_opt('header_width', 'boxed');
    } else {
        $header_width   = $page_doc_width;
    }

    $is_doc_sticky = docy_meta('is_sticky_header');
    $has_menu = has_nav_menu('main_menu') ? '' : 'has_not_menu';



    $classes[] = $has_menu;

    if ( Docy_helper()->doc_layout() == 'left_sidebar' && is_singular('docs') ) {
        $classes[] = 'no_right_sidebar';
    }

    if ( is_singular('docs') ) {
        $classes[] = 'doc';
        if ( $is_doc_sticky == '1' ) {
            $classes[] = 'sticky-nav-doc';
        }
    }

    if ( docy_opt('is_dark_default') =='1' ) {
        wp_enqueue_style( 'docy-dark-mode' );
        $classes[] = 'body_dark';
    }

    if ( docy_navbar_position() == 'static' ) {
        $classes[] = 'static-navbar';
    }

    $classes[] = $header_width;
    $classes[] = docy_search_banner();

    return $classes;
});

/**
 * Removes admin notices on the docy's pages.
 *
 * @return void
 */
add_action( 'admin_head', function () {
	$page = !empty( $_GET['page'] ) ? $_GET['page'] : '';
	// Check if the current screen is for your plugin page
	if ( in_array( $page, ['docy_verify', 'docy-options', 'docy_template'] ) ) {
		// Remove admin notices
		remove_all_actions( 'admin_notices' );
		remove_all_actions( 'all_admin_notices' );
	}
});

/**
 * Show post excerpt by default
 * @param $user_login
 * @param $user
 */
function docy_show_post_excerpt( $user_login, $user ) {
    $unchecked = get_user_meta( $user->ID, 'metaboxhidden_post', true );
    $key = is_array($unchecked) ? array_search( 'postexcerpt', $unchecked ) : FALSE;
    if ( FALSE !== $key ) {
        array_splice( $unchecked, $key, 1 );
        update_user_meta( $user->ID, 'metaboxhidden_post', $unchecked );
    }
}
add_action( 'wp_login', 'docy_show_post_excerpt', 10, 2 );

// filter to replace class on reply link
add_filter('comment_reply_link', function($class){
    $class = str_replace("class='comment-reply-link", "class='comment_reply", $class);
    return $class;
});

/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function docy_pingback_header() {
    if ( is_singular() && pings_open() ) {
        echo '<link rel="pingback" href="', esc_url( get_bloginfo( 'pingback_url' ) ), '">';
    }
}
add_action( 'wp_head', 'docy_pingback_header' );

// Move the comment field to bottom
add_filter( 'comment_form_fields', function ( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
});

// Remove WordPress admin bar default CSS
add_action('get_header', function() {
    remove_action('wp_head', '_admin_bar_bump_cb' );
});

// Elementor post type support
function docy_add_cpt_support() {
    //if exists, assign to $cpt_support var
    $cpt_support = get_option( 'elementor_cpt_support' );

    //check if option DOESN'T exist in db
    if ( ! $cpt_support ) {
        $cpt_support = [ 'page', 'post', 'docs' ]; //create array of our default supported post types
        update_option( 'elementor_cpt_support', $cpt_support ); //write it to the database
    }

    //if it DOES exist, but header is NOT defined
    elseif( !in_array( 'docs', $cpt_support ) ) {
        $cpt_support[] = 'docs'; //append to array
        update_option( 'elementor_cpt_support', $cpt_support ); //update database
    }
}
add_action( 'after_switch_theme', 'docy_add_cpt_support' );

// Color Picker Issue solution
if( is_admin() ){
	add_action( 'wp_default_scripts', 'docy_default_custom_scripts' );
	function docy_default_custom_scripts( $scripts ) {
		$scripts->add( 'wp-color-picker', "/wp-admin/js/color-picker.js", array( 'iris' ), false, 1 );
		did_action( 'init' ) && $scripts->localize(
			'wp-color-picker',
			'wpColorPickerL10n',
			array(
				'clear'            => esc_html__( 'Clear', 'docy' ),
				'clearAriaLabel'   => esc_html__( 'Clear color', 'docy' ),
				'defaultString'    => esc_html__( 'Default', 'docy' ),
				'defaultAriaLabel' => esc_html__( 'Select default color', 'docy' ),
				'pick'             => esc_html__( 'Select Color', 'docy' ),
				'defaultLabel'     => esc_html__( 'Color value', 'docy' ),
			)
		);
	}
}

/**
 * Turn on the WordPress visual editor for bbPress
 * @param array $args
 *
 * @return array|mixed
 */
function docy_bbp_enable_visual_editor( $args = array() ) {
	$args['tinymce'] = true;
	return $args;
}
add_filter( 'bbp_after_get_the_content_parse_args', 'docy_bbp_enable_visual_editor' );

if( function_exists('acf_add_options_sub_page') ) {
    acf_add_options_sub_page(array(
        'page_title' 	=> esc_html__( 'Changelogs Settings', 'docy' ),
        'menu_title'	=> esc_html__( 'Settings', 'docy'),
        'parent_slug'	=> 'edit.php?post_type=changelog',
    ));
}

/**
 * bbPress forum configurations
 */
if ( class_exists('bbPress') ) {
	require get_template_directory() . '/inc/bbpress-config.php';
}