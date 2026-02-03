<?php
/**
 * Register Google fonts.
 *
 * @return string Google fonts URL for the theme.
 */
function docy_fonts_url(): string
{
	$fonts_url = '';
	$fonts     = array();
	$subsets   = '';

	/* Body font */
	if ( 'off' !== 'on' ) {
		$fonts[] = "Poppins:300,400,500,600,700";
	}

	$is_ssl = is_ssl() ? 'https' : 'http';

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), "$is_ssl://fonts.googleapis.com/css" );
	}

	return $fonts_url;
}

/**
 * Enqueue site scripts and styles
 */
function docy_scripts() {
	$opt = get_option( 'docy_opt' );

	/**
	 * Registering site's scripts and styles
	 */
	wp_register_style( 'docy-fonts', docy_fonts_url(), array(), null );
	wp_register_style( 'nice-select', DOCY_DIR_VEND . '/niceselectpicker/nice-select.css' );
	wp_register_style( 'docy-font-size', DOCY_DIR_VEND . '/font-size/css/rvfs.css' );
	wp_register_style( 'bootstrap-select', DOCY_DIR_VEND . '/bootstrap/css/bootstrap-select.min.css' );
	wp_register_style( 'magnific-popup', DOCY_DIR_VEND . '/magnify-pop/magnific-popup.css' );
	wp_register_style( 'docy-forum', DOCY_DIR_CSS . '/forum.css' );
	wp_register_style( 'docy-blog-single', DOCY_DIR_CSS . '/blog-single.css' );
	wp_register_style( 'docy-dark-mode', DOCY_DIR_CSS . '/dark-mode.css' );

    if ( function_exists('bbp_is_search_results')) {
        if ( !bbp_is_search_results() ) {
            wp_dequeue_style( 'bbp-default' );
        }
    }

	wp_enqueue_style( 'docy-fonts' );
	wp_enqueue_style( 'bootstrap', DOCY_DIR_VEND . '/bootstrap/css/bootstrap.min.css' );
	wp_enqueue_style( 'elegant-icon', DOCY_DIR_VEND . '/elegant-icon/style.css' );
	wp_enqueue_style( 'font-awesome', DOCY_DIR_VEND . '/font-awesome/css/all.css' );
	wp_enqueue_style( 'animate', DOCY_DIR_VEND . '/animation/animate.css' );
	wp_enqueue_style( 'docy-main', DOCY_DIR_CSS . '/style-main.css', array(), DOCY_VERSION . '.1' );
	
	// CMGALAXY Custom Header Styles
	wp_enqueue_style( 'cmgalaxy-header', DOCY_DIR_CSS . '/cmgalaxy-header.css', array(), DOCY_VERSION );
	
	// Modern Sidebar Styles
	wp_enqueue_style( 'modern-sidebar', DOCY_DIR_CSS . '/modern-sidebar.css', array(), DOCY_VERSION );
	
	// Simple Post Card Styles
	wp_enqueue_style( 'simple-post-card', DOCY_DIR_CSS . '/simple-post-card.css', array(), DOCY_VERSION );
	
	// Social Icons Styles
	wp_enqueue_style( 'social-icons', DOCY_DIR_CSS . '/social-icons.css', array(), DOCY_VERSION );
	
	// Mobile Menu Custom Styles
	wp_enqueue_style( 'mobile-menu-custom', DOCY_DIR_CSS . '/mobile-menu-custom.css', array(), DOCY_VERSION );
	
	// Typography Schema - Global typography system
	wp_enqueue_style( 'typography-schema', DOCY_DIR_CSS . '/typography-schema.css', array(), DOCY_VERSION );

	// bbPress forum plugin styles
	if ( in_array( 'bbpress', get_body_class() ) && class_exists( 'bbPress' ) ) {
		wp_enqueue_style( 'docy-forum' );
	}

	// wooCommerce stylesheets
	if ( class_exists( 'WooCommerce' ) ) {
		if ( is_shop() || is_singular('product') || is_cart() || is_product_taxonomy() ) {
			wp_enqueue_style( 'docy-shop', DOCY_DIR_CSS . '/shop.css' );
			wp_enqueue_style( 'nice-select' );
			wp_enqueue_script( 'nice-select' );
		}
		if ( is_checkout() ) {
			wp_enqueue_style( 'docy-checkout', DOCY_DIR_CSS . '/checkout.css' );
		}
		if ( is_account_page() ) {
			wp_enqueue_style( 'docy-shop-my-account', DOCY_DIR_CSS . '/shop-my_account.css' );
		}
	}

	wp_enqueue_style( 'docy-root', get_stylesheet_uri() );

	if ( is_singular( 'post' ) ) {
		wp_enqueue_style( 'docy-responsive', DOCY_DIR_CSS . '/responsive.css', array( 'docy-blog-single' ) );
	} else {
		wp_enqueue_style( 'docy-responsive', DOCY_DIR_CSS . '/responsive.css' );
	}

	if ( is_rtl() ) {
		wp_enqueue_style( 'docy-rtl', DOCY_DIR_CSS . '/rtl.css' );
	}

	$css_output = array(
		'menu_item_color'            => array(
			'color' => '.navbar .menu > .nav-item > .nav-link',
		),
		'banner_background_color'    => array(
			'background-color' => '.titlebar',
		),
		'banner_text_color'          => array(
			'color' => '.breadcrumb_text h1, .breadcrumb_text p',
		),
		'footer_pt__px'              => array(
			'padding-top' => '.footer_area'
		),
		'footer_pr__px'              => array(
			'padding-right' => '.footer_area'
		),
		'footer_pb__px'              => array(
			'padding-bottom' => '.footer_area'
		),
		'footer_pl__px'              => array(
			'padding-left' => '.footer_area'
		),
		// Footer background color
		'footer_background_color'    => array(
			'background-color' => '.doc_footer_top'
		),

		/**
		 * Action Button
		 */
		'btn_background_color'       => array(
			'background' => '.right-nav .nav_btn.tp_btn'
		),
		'btn_border_radius'          => array(
			'border-radius' => '.right-nav .nav_btn.tp_btn'
		),
		'btn_text_color'             => array(
			'color' => '.right-nav .nav_btn.tp_btn, .dark_menu .right-nav .nav_btn.tp_btn'
		),
		'btn_border_color'           => array(
			'border-color' => '.right-nav .nav_btn.tp_btn'
		),
		'hover_btn_background_color' => array(
			'background' => '.right-nav .nav_btn.tp_btn:hover'
		),
		'hover_btn_text_color'       => array(
			'color' => '.right-nav .nav_btn.tp_btn:hover'
		),
		'hover_btn_border_color'     => array(
			'border-color' => '.right-nav .nav_btn.tp_btn:hover'
		),

		/**
		 * Page Settings
		 */
		'page_padding_top__px'       => array(
			'padding-top' => '.page_wrapper'
		),
		'page_padding_right__px'     => array(
			'padding-right' => '.page_wrapper'
		),
		'page_padding_bottom__px'    => array(
			'padding-bottom' => '.page_wrapper'
		),
		'page_padding_left__px'      => array(
			'padding-left' => '.page_wrapper'
		),
	);

	Docy_helper()->dynamic_css_render( 'docy-root', $css_output );

	/**
	 * Register and enqueue theme script files
	 */
	wp_register_script( 'preloader', DOCY_DIR_JS . '/pre-loader.js', array( 'jquery' ), '1.0', true );
	wp_register_script( 'nice-select', DOCY_DIR_VEND . '/niceselectpicker/jquery.nice-select.min.js', array( 'jquery' ), '1.0', true );
	wp_register_script( 'docy-font-size', DOCY_DIR_VEND . '/font-size/js/rv-jquery-fontsize-2.0.3.js', array( 'jquery' ), '2.0.3', true );
	wp_register_script( 'bootstrap-select', DOCY_DIR_VEND . '/bootstrap/js/bootstrap-select.min.js', array( 'jquery', 'bootstrap' ), '2.0.3', true );
	wp_register_script( 'bootstrap-toc', DOCY_DIR_VEND . '/bootstrap/js/bootstrap-toc.min.js', array( 'jquery', 'bootstrap' ), '1.0.1', true );
	wp_register_script( 'magnific-popup', DOCY_DIR_VEND . '/magnify-pop/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
	wp_register_script( 'printThis', DOCY_DIR_JS . '/printThis.js', array( 'jquery' ), '1.0.0', true );
	wp_register_script( 'docy-dark-mode', DOCY_DIR_JS . '/dark-mode.js', array( 'jquery' ), '1.0.0', true );

	if ( is_page_template( 'page-onepage.php' ) ) {
		wp_enqueue_style( 'eazydocs-frontend' );
	}

	if ( is_singular( 'topic' ) ) {
		wp_enqueue_style( 'nice-select' );
		wp_enqueue_script( 'nice-select' );
	}

	/**
	 * JavaScripts
	 */
	wp_enqueue_script( 'bootstrap', DOCY_DIR_VEND . '/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.1.3', true );
	wp_enqueue_script( 'wow', DOCY_DIR_VEND . '/wow/wow.min.js', array( 'jquery' ), '1.1.3', true );
	
	// CMGALAXY Header JavaScript - renamed file to force cache refresh
	// Force reload: v2 file loaded
	wp_enqueue_script( 'cmgalaxy-header', DOCY_DIR_JS . '/cmgalaxy-header-v2.js', array( 'jquery' ), '1.0.2', true );

	$banner_type = docy_meta('banner_type');

	// Re-enabled main.js for mobile menu functionality
	wp_enqueue_script( 'docy-main', DOCY_DIR_JS . '/main.js', array( 'jquery' ), '1.0.1', true );
	
	// Enqueue sticky navbar fix script - loads independently now
	wp_enqueue_script( 'sticky-navbar-fix', DOCY_DIR_JS . '/sticky-navbar-fix.js', array( 'jquery' ), '1.0.0', true );

	if ( class_exists( 'bbPress' ) ) {
		wp_enqueue_script( 'prism' );

		if ( ! is_singular( 'docs' ) ) {
			wp_enqueue_script( 'docy-forum', DOCY_DIR_JS . '/forum.js', array( 'jquery' ), '1.0.0', true );
		}
	}
	
	// Enqueue arrow removal script
	wp_enqueue_script( 'docy-remove-arrows', DOCY_DIR_JS . '/remove-arrows.js', array( 'jquery' ), '1.0.0', true );
	
	// Enqueue TOC click fix script
	wp_enqueue_script( 'docy-toc-click-fix', DOCY_DIR_JS . '/toc-click-fix.js', array( 'jquery' ), '1.0.2', true );

	// Localize the script with new data
	$ajax_url              = admin_url( 'admin-ajax.php' );
	$wpml_current_language = apply_filters( 'wpml_current_language', null );
	if ( ! empty( $wpml_current_language ) ) {
		$ajax_url = add_query_arg( 'wpml_lang', $wpml_current_language, $ajax_url );
	}

	$is_doc_ajax         = $opt['is_doc_ajax'] ?? '1';
	$is_focus_by_slash   = $opt['is_focus_by_slash'] ?? '';
	$sbnr_post_types   	 = ! empty ( $opt['sbnr_post_types'] ) ?  $opt['sbnr_post_types'] : ['post', 'page', 'docs'];
	    
    wp_enqueue_script( 'docy-ajax-search-form', DOCY_DIR_JS . '/ajax-search-form.js', array( 'jquery' ), '1.0.0', true );
    wp_localize_script('docy-ajax-search-form', 'docy_local_object',
        array(
            'ajaxurl'           => $ajax_url,
            'DOCY_DIR_CSS'      => DOCY_DIR_CSS,
            'is_doc_ajax'       => $is_doc_ajax,
            'is_focus_by_slash' => $is_focus_by_slash,
			'post_types' 		=> $sbnr_post_types,
			'ajax_nonce' 		=> wp_create_nonce('ajax_search_nonce')
        )
    );

	global $wp_query;
	$localized_settings = [
		'ajax_url'     => admin_url( 'admin-ajax.php' ),
		'docy_nonce'   => wp_create_nonce( 'docy-nonce' ),
		'docy_parent'  => get_queried_object_id(),
		'posts'        => json_encode( $wp_query->query_vars ), // everything about your loop is here
		'current_page' => get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1,
		'max_page'     => $wp_query->max_num_pages,
		'first_page'   => get_pagenum_link( 1 )
	];

	wp_localize_script( 'jquery', 'DocyForum', $localized_settings );

	/**
	 * Inline Scripts
	 */
	$dynamic_js = '';

	if ( ! empty( $opt['custom_js'] ) ) {
		$dynamic_js .= $opt['custom_js'];
	}

	if ( ! empty( $opt['os_options'][0]['title'] ) && is_singular( 'docs' ) ) {
		foreach ( $opt['os_options'] as $option ) {
			$dynamic_js .= '
            if( jQuery("#mySelect").val() == "' . esc_js( sanitize_title( $option['title'] ) ) . '" ) {
                jQuery(".' . esc_js( sanitize_title( $option['title'] ) ) . '").show();
            } else {
                jQuery(".' . esc_js( sanitize_title( $option['title'] ) ) . '").hide();
            }
            jQuery("#mySelect").change(function() {
                if( jQuery("#mySelect").val() == "' . esc_js( sanitize_title( $option['title'] ) ) . '" ) {
                    jQuery(".' . esc_js( sanitize_title( $option['title'] ) ) . '").show();
                } else {
                    jQuery(".' . esc_js( sanitize_title( $option['title'] ) ) . '").hide();
                }
            })';
		}
	}

	wp_add_inline_script( 'docy-main', $dynamic_js );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}

add_action( 'wp_enqueue_scripts', 'docy_scripts' );

// Admin dashboard style and scripts
add_action( 'admin_enqueue_scripts', function () {
	global $pagenow;

    // Admin styles
    wp_enqueue_style( 'docy-admin', DOCY_DIR_CSS . '/admin.css' );
	if ( $pagenow == 'admin.php' ) {
		wp_enqueue_style( 'elegant-icon', DOCY_DIR_VEND . '/elegant-icon/style.css' );
	}

    // Admin scripts
	wp_enqueue_script( 'docy-admin', DOCY_DIR_JS . '/admin.js', array( 'jquery' ), '1.0.0', true );
} );

function docy_block_editor_styles() {
    wp_enqueue_style( 'docy-block-editor-styles', get_template_directory_uri() . '/style-editor.css' );
    wp_enqueue_style( 'docy-typography-schema-editor', DOCY_DIR_CSS . '/typography-schema.css', array(), DOCY_VERSION );
}

add_action('enqueue_block_editor_assets', 'docy_block_editor_styles');
