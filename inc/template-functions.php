<?php
/**
 * Get theme option
 *
 * @param string $option The option key.
 * @param string $default The default value.
 * @param string $arr_item Access to the array item of a theme option.
 *
 * @return mixed|string
 */
function docy_opt( string $option = '', string $default = '', $arr_item = '' ) {
	$opt = get_option( 'docy_opt' );
	$single_opt = $opt[ $option ] ?? $default;
	// If $opt[ $option ] is an array
    $array_opt = is_array($single_opt) && !empty($arr_item) ? $single_opt[$arr_item] : '';
    $array_opt = $array_opt ?? $default;

	// If $opt[ $option ] is an array and $group_opt is not empty, return its value or default value.
	return ! empty( $array_opt ) ? $array_opt : $single_opt;
}

/**
 * Get post-meta
 *
 * @param string $meta_id
 * @param string $default
 *
 * @return mixed|string
 */
function docy_meta(string $meta_id = '', string $default = '' ): mixed {
    $meta_value = get_post_meta( get_the_ID(), $meta_id, true );
	return $meta_value ?? $default;
}


/**
 * Get post-meta value or theme option value.
 *
 * This function first attempts to retrieve a post-meta value. If the post meta
 * is not set or is empty, it falls back to the theme option value.
 *
 * @param string $option_id
 * @param string|null $default The default value to return if both meta and option are not set.
 * @return mixed The post meta value, theme option value, or default value.
 */

function docy_meta_apply(string $option_id, null|string $default = '' ): mixed {

    // Get post meta and theme option values
    $meta_value = get_post_meta( get_the_ID(), $option_id, true );
    $option_value = docy_opt($option_id, $default);

    // Check if meta value is an array and empty
    $is_meta_arr_empty = is_array($meta_value) && empty(array_filter($meta_value));

    if ( $meta_value == 'default' || $meta_value == '' || $meta_value == null || $is_meta_arr_empty ) {
        return $option_value;
    }

    // Return meta if it's a valid non-empty value
    return $meta_value;

}

/**
 * Get the homepage IDs by Title
 */
function docy_homepage_ids() {
    // Array of page titles you want to retrieve IDs for
    $page_titles = [
        'Focused Helpdesk',
        'Home Book Chapters / Tutorials',
        'Home Classic',
        'Home Cool',
        'Home Creative',
        'Home Help Desk',
        'Home Light',
        'Home Cool',
        'Home Multi Helpdesk',
        'User Manuals',
        'Support Forum',
        'Instructor',
        'Documentation'
    ];

    // Initialize an empty array to store page IDs
    $page_ids = [];

    // Query for each title to get the page ID
    foreach ($page_titles as $title) {
        $query = new WP_Query([
            'post_type' => 'page',
            'title' => $title,
            'fields' => 'ids', // Only get the IDs to optimize performance
            'posts_per_page' => 1
        ]);

        // If a page with this title exists, add its ID to the array
        if ($query->have_posts()) {
            $page_ids[] = $query->posts[0];
        }

        // Reset post data after each query
        wp_reset_postdata();
    }

    return $page_ids;
}

/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package docy
 */

// Search form
function docy_search_form( $is_button = true ) {
	?>
    <div class="docy-search">
        <form class="form-wrapper" action="<?php echo esc_url( home_url( '/' ) ); ?>" _lpchecked="1">
            <input type="text" id="search" placeholder="<?php esc_attr_e( 'Search ...', 'docy' ); ?>" name="s">
            <button type="submit" class="btn"><i class="fa fa-search"></i></button>
        </form>
		<?php if ( $is_button ) { ?>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="home_btn"> <?php esc_html_e( 'Back to home Page', 'docy' ); ?> </a>
		<?php } ?>
    </div>
	<?php
}

/**
 * Get comment count text
 *
 * @param $post_id
 *
 * @return void
 */
function docy_comment_count( $post_id ) {
	$comments_number = get_comments_number( $post_id );
	if ( $comments_number == 0 ) {
		$comment_text = esc_html__( 'No Comments', 'docy' );
	} elseif ( $comments_number == 1 ) {
		$comment_text = esc_html__( '1 Comment', 'docy' );
	} elseif ( $comments_number > 1 ) {
		$comment_text = $comments_number . esc_html__( ' Comments', 'docy' );
	}
	echo esc_html( $comment_text );
}

/**
 * Get author role
 *
 * @return string
 */
function docy_get_author_role() {
	global $authordata;
	$author_roles = $authordata->roles;
	$author_role  = array_shift( $author_roles );

	return esc_html( $author_role );
}

/**
 * Check If the Page is Forum user profile page
 */
function docy_forum_user_profile() {
	if ( in_array( 'bbp-user-page', get_body_class() ) || in_array( 'bbp-user-edit', get_body_class() ) ) {
		return true;
	}
}


/**
 * Post title array
 *
 * @param $postType
 *
 * @return array
 */
function docy_get_postTitleArray( $postType = 'post' ) {
	$post_type_query = new WP_Query(
		array(
			'post_type'      => $postType,
			'posts_per_page' => - 1
		)
	);
	// we need the array of posts
	$posts_array = $post_type_query->posts;
	// the key equals the ID, the value is the post_title
	if ( is_array( $posts_array ) ) {
		$post_title_array = wp_list_pluck( $posts_array, 'post_title', 'ID' );
	} else {
		$post_title_array['default'] = esc_html__( 'Default', 'docy' );
	}

	return $post_title_array;
}

/**
 * Get a specific html tag from content
 *
 * @return a specific HTML tag from the loaded content
 */
function docy_get_html_tag( $tag = 'blockquote', $content = '' ) {
	$dom = new DOMDocument();
	$dom->loadHTML( $content );
	$divs = $dom->getElementsByTagName( $tag );
	$i    = 0;
	foreach ( $divs as $div ) {
		if ( $i == 1 ) {
			break;
		}
		echo "<h4 class='c_head'>{$div->nodeValue}</h4>";
		++ $i;
	}
}

/**
 * Get the page id by page template
 *
 * @param string $template
 *
 * @return int
 */
function docy_get_page_template_id( $template = 'page-job-apply-form.php' ) {
	$pages = get_pages( array(
		'meta_key'   => '_wp_page_template',
		'meta_value' => $template
	) );
	foreach ( $pages as $page ) {
		$page_id = $page->ID;
	}

	return $page_id;
}

/**
 * Arrow icon left right position
 */
function docy_arrow_left_right() {
	$arrow_icon = is_rtl() ? 'arrow_left' : 'arrow_right';
	echo esc_attr( $arrow_icon );
}

/**
 * Search results page's active tab
 */
function docy_is_search_tab_active( $post_type ): string
{

    // Check if the post_type is set in the query parameters
    if ( isset( $_GET['post_type'] ) && $_GET['post_type'] === $post_type ) {
        return 'active'; // Return active for matching post-types
    }

    // If post_type is not set and the current type is 'all', mark it as active
    if ( ! isset( $_GET['post_type'] ) && $post_type === 'all' ) {
        return 'active';
    }

    return ''; // Return an empty string if no conditions are meet

}


/**
 * Docy post breadcrumbs
 */
function docy_post_breadcrumbs(): void
{
	$opt = get_option( 'docy_opt' );
	$breadcrumb_home = $opt['breadcrumb_home'] ?? esc_html__( 'Home', 'docy' );

	if ( is_home() ) {
		$title = ! empty( $opt['blog_title'] ) ? $opt['blog_title'] : esc_html__( 'Blog', 'docy' );
	} else {
		$title = get_the_title();
	}

	if ( in_array( 'bbpress', get_body_class() ) ) {
		bbp_breadcrumb( array(
			'before'         => '<ol class="breadcrumb"> <li class="breadcrumb-item">',
			'sep_before'     => '',
			'sep'            => '</li><li class="breadcrumb-item">',
			'sep_after'      => '',
			'current_before' => '',
			'current_after'  => '',
			'after'          => '</li></ol>',
			'home_text'      => $breadcrumb_home
		) );
	} elseif ( is_singular('docs') || in_array( 'single-docs', get_post_class() ) ) {
		eazydocs_breadcrumbs();
	} elseif ( in_array( 'type-topic', get_post_class() ) ) {
		bbp_breadcrumb();
	} else {
		?>
        <ol class="breadcrumb <?php echo get_post_type( get_the_ID() ) ?>">

            <?php if ( ! is_singular('post') ) : ?>
            <li class="breadcrumb-item">
                <a href="<?php echo esc_url( home_url( '/' ) ) ?>"> <?php echo esc_html( $breadcrumb_home ) ?> </a>
            </li>
            <?php endif; ?>

            <?php
            // Is Search Result page
            global $post;
            if ( is_search() ) {
                ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo get_post_type_archive_link( get_post_type( get_the_ID() ) ) ?>">
                        <?php echo ucwords( get_post_type( get_the_ID() ) ); ?>
                    </a>
                </li>
                <?php
                if ( 'docs' == $post->post_type && $post->post_parent ) {

                    $ancestors = array_reverse( get_post_ancestors( $post->ID ) ); ;
                    foreach ( $ancestors as $ancestor_id ) {
                        $ancestor = get_post( $ancestor_id );
                        ?>
                        <li class="breadcrumb-item">
                            <a href="<?php echo get_the_permalink( $ancestor_id ); ?>">
                                <?php echo get_the_title( $ancestor ); ?>
                            </a>
                        </li>
                        <?php
                    }
                }
            }
            ?>

            <!-- wooCommerce Pages -->
	        <?php if ( in_array( 'woocommerce-cart', get_body_class() ) || in_array( 'woocommerce-checkout', get_body_class() ) ) : ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo get_permalink( wc_get_page_id('shop') ) ?>">
				        <?php echo get_the_title( wc_get_page_id('shop') ); ?>
                    </a>
                </li>
	        <?php endif; ?>

            <?php if ( in_array( 'woocommerce-checkout', get_body_class() ) ) : ?>
                <li class="breadcrumb-item">
                    <a href="<?php echo wc_get_cart_url() ?>">
			            <?php echo get_the_title( wc_get_page_id('cart') ); ?>
                    </a>
                </li>
            <?php endif; ?>

			<!-- Active page -->
			<?php if ( is_archive() && ! is_home() ) : ?>
				<li class="breadcrumb-item active">
					<?php
					// Show taxonomy/archive title instead of generic post type label
					if ( is_category() ) {
						echo single_cat_title( '', false );
					} else {
						echo esc_html( wp_strip_all_tags( get_the_archive_title() ) );
					}
					?>
				</li>
			<?php endif; ?>
			<?php if ( is_home() ) : ?>
                <li class="breadcrumb-item active">
					<?php esc_html_e( 'Blog', 'docy' ); ?>
                </li>
			<?php endif; ?>
            <?php
            // Insert category level for single blog posts (Category > Post Title)
            if ( is_singular('post') ) :
                $categories = get_the_category( get_the_ID() );
                if ( ! empty( $categories ) ) {
                    $primary_category = $categories[0];
                    echo '<li class="breadcrumb-item">'
                         . '<a href="' . esc_url( get_category_link( $primary_category->term_id ) ) . '">'
                         . esc_html( $primary_category->name )
                         . '</a>'
                         . '</li>';
                }
            endif;
            ?>

            <?php if ( is_single() || is_page() ) : ?>
                <li class="breadcrumb-item active" aria-current="page">
					<?php echo strip_tags(esc_html( $title )) ?>
                </li>
			<?php endif; ?>
        </ol>
		<?php
	}
}

/**
 * Has scrollspy
 */
function docy_has_scrollspy() {
	if ( docy_toc( 'post' ) == '1' || docy_toc( 'page' ) == '1' ) {
		echo 'data-bs-spy="scroll" data-bs-target="#docy-toc" data-bs-offset="150" data-bs-scroll-animation="true"';
	}
}

/**
 * No Titlebar Condition
 */
function docy_no_titlebar() {
	if ( class_exists( 'bbPress' ) ) {
		if ( is_post_type_archive( array(
				'forum',
				'topic'
			) )
		     || bbp_is_search_results()
		     || in_array( 'bbp-view-popular', get_body_class() )
		     || in_array( 'bbp-view-no-replies', get_body_class() )
		) {
			return true;
		}
	}

	if ( is_singular( 'docs' ) || is_404() || is_home() || is_single() || is_singular( 'topic' ) || is_search()
	     || in_array( 'woocommerce', get_body_class() )
	) {
		return true;
	}
}


/**
 * Decode Docy
 */
function docy_decode_du( $str ) {
	$str = str_replace( 'cZ5^9o#!', 'wordpress-theme.spider-themes.net', $str );
	$str = str_replace( 'aI7!8B4H', 'resources', $str );
	$str = str_replace( '^93|3d@', 'https', $str );
	$str = str_replace( 't7Cg*^n0', 'docy', $str );
	$str = str_replace( '3O7%jfGc', '.zip', $str );

	return urldecode( $str );
}

/**
 * Navbar Position
 */
function docy_navbar_position() {
	$opt           = get_option( 'docy_opt' );
	$position_page = docy_meta('navbar_position');
	$position_opt  = $opt['navbar_position'] ?? 'absolute';

	return ! empty( $position_page ) & $position_page != 'default' ? $position_page : $position_opt;
}

/**
 * Navbar class
 **/
function docy_navbar_class() {
	$is_static = docy_navbar_position() == 'static' && ! is_singular( 'post' ) ? ' position-static' : '';
	?>
    class="navbar navbar-expand-lg menu_one sticky-nav display_none <?php Docy_helper()->navbar_type();
	echo esc_attr( $is_static ) . '"';
}

/**
 * Navbar container
 **/
function docy_nav_container( $class = '' ) {
	echo Docy_helper()->page_width() == 'full-width' ? "container-fluid pl-60 pr-60 $class" : "container $class";
}

/**
 *
 * Is Navbar Sticky
 */
function docy_sticky_navbar( $class = 'wrapper', $stick_on = 'desktop' ) {
	$is_sticky_nav     = docy_opt( 'is_sticky_header', '' );
	$sticky_appearance = docy_opt( 'sticky_appearance', 'stick_up' );
	if ( $is_sticky_nav == '1' ) {
		$get_nav = $_GET['navbar'] ?? '';
		if ( $stick_on == 'desktop' ) {
			if ( $class == 'wrapper' ) {
				echo $sticky_appearance == 'stick_all' || $get_nav == 'stick-all' ? 'sticky_menu' : '';
			} else {
				echo $sticky_appearance == 'stick_all' || $get_nav == 'stick-all' ? 'stickyTwo' : 'sticky';
			}
		} elseif ( $stick_on == 'mobile' ) {
			echo $sticky_appearance == 'stick_all' || $get_nav == 'stick-all' ? 'mobile-stickyTwo' : 'mobile-sticky';
		}
	}
}

/**
 * Page TOC
 */
function docy_toc( $post_type ) {

	$is_toc = docy_meta('is_toc');

	// Check if 'is_toc' exists and if its value is 'default'
	if ( isset( $is_toc ) && $is_toc == 'default' ) {
		$is_toc = docy_opt( "is_" . $post_type . "_toc" );
	} else {
		$is_toc = ! empty( $is_toc ) ? $is_toc : '';
	}

	return $is_toc;
}

/**
 * Body wrapper css classes
 */
function docy_body_wrapper_classes() {
	$class = '';
	if ( docy_opt('search_banner_bg') != 'color' ) {
		$class .= ' sbnr-gradient';
	}
	if ( docy_opt('is_top_header') == '1' ) {
		$class .= ' has_top_header';
	}
	if ( docy_toc('post') == '1' || docy_toc( 'page' ) == '1' ) {
		$class .= ' no-overflow';
	}

	echo docy_sticky_navbar() . $class;
}

/**
 * Search banner
 */
function docy_search_banner() {
	$opt = get_option( 'docy_opt' );
	if ( docy_opt( 'search_banner_layout', 'default' ) == 'default' ) {
		$search_banner = ! empty( $opt['select_search_banner'] ) ? $opt['select_search_banner'] : 'light';
	} else {
		$search_banner = 'el-template';
	}

	return $search_banner;
}

/**
 * Is aesthetic banner default
 */
function docy_is_aesthetic_default() {
	$opt               = get_option( 'docy_opt' );
	$header_type       = docy_meta('docy_header_type');
	$banner_preset     = docy_meta('banner_preset');
	$search_banner_opt = $opt['select_search_banner'] ?? 'light';
	$search_banner     = ! empty( $banner_preset ) && $banner_preset != 'default' ? $banner_preset : $search_banner_opt;
	if ( $search_banner == 'aesthetic' ) {
		if ( $header_type == 'default' || ! isset( $header_type ) ) {
			return true;
		}
	}
}

/**
 * Check if page title-bar show
 */
function docy_is_titlebar_default() {
	$header_type = docy_meta('docy_header_type');
	if ( ( is_home() && docy_search_banner() == 'light' ) || is_tag() || is_category() || ( is_page() && ! isset( $header_type ) )
	     || docy_forum_user_profile()
	) {
		return true;
	}
}

/**
 * Get titlebar excerpt
 */
function docy_titlebar_excerpt() {
	if ( ! is_search() ) {
		if ( is_tag() ) {
			echo wpautop( tag_description( get_queried_object()->term_id ) );
		} elseif ( is_category() ) {
			echo wpautop( category_description( get_queried_object()->term_id ) );
		} else {
			echo has_excerpt() ? wpautop( get_the_excerpt() ) : '';
		}
	}
}

/**
 * Modified Date
 */
function docy_modified_date() {
	$modified_date = '';
	$recent_posts  = wp_get_recent_posts( array(
		'numberposts' => 1, // Number of recent posts thumbnails to display
		'post_status' => 'publish' // Show only the published posts
	) );
	foreach ( $recent_posts as $recent_post ) {
		$modified_date = get_the_time( get_option( 'date_format' ), $recent_post['ID'] );
	}
	if ( is_home() ) {
		echo esc_html( $modified_date );
	} else {
		the_modified_date( get_option( 'date_format' ) );
	}
}

/**
 * Estimated reading time
 **/
function docy_reading_time( $post ) {
	$content     = get_post_field( 'post_content', $post );
	$word_count  = str_word_count( strip_tags( $content ) );
	$readingtime = ceil( $word_count / 200 );
	if ( $readingtime == 1 ) {
		$timer = esc_html__( " minute", 'docy' );
	} else {
		$timer = esc_html__( " minutes", 'docy' );
	}
	$totalreadingtime = $readingtime . $timer;
	echo esc_html( $totalreadingtime );
}

/**
 * Allowed HTML for wp_kses function
 *
 * @return array
 */
function docy_allowed_html() {
	return array(
		'a' => array(
			'class'  => array(),
			'href'   => true,
			'rel'    => true,
			'rev'    => true,
			'name'   => true,
			'target' => true,
		),

		'br' => array(),

		'p' => array(
			'class' => array(),
		),

		'strong' => array(),
		'div'    => array(
			'style' => array(),
			'class' => array()
		),

		'img' => array(
			'class'  => array(),
			'src'    => array(),
			'srcset' => array(),
			'alt'    => array(),
		),
	);
}

/**
 * Banner preset background styles
 *
 * @return array[]|string[]
 */
function docy_banner_bg_style() {
	return array(
		'color'           => DOCY_DIR_IMG . '/options/color.png',
		'faded-sun'       => DOCY_DIR_IMG . '/options/faded-sun.jpg',
		'happy-journey'   => DOCY_DIR_IMG . '/options/happy-journey.jpg',
		'apparent-circle' => DOCY_DIR_IMG . '/options/apparent-circle.jpg',
		'soft-weather'    => DOCY_DIR_IMG . '/options/soft-weather.jpg',
		'romantic-sun'    => DOCY_DIR_IMG . '/options/romantic-sun.jpg',
		'teal-eclipse'    => DOCY_DIR_IMG . '/options/teal-eclipse.jpg',
	);
}

/**
 * Get elementor templates
 *
 * @return array[]
 */
function docy_elementor_template() {
	$elementor_templates = get_posts( array(
		'post_type'      => 'elementor_library',
		'posts_per_page' => - 1,
		'status'         => 'publish'
	) );

	$elementor_templates_array = array();
	if ( ! empty( $elementor_templates ) ) {
		foreach ( $elementor_templates as $elementor_template ) {
			$elementor_templates_array[ $elementor_template->ID ] = $elementor_template->post_title;
		}
	}

	return $elementor_templates_array;
}

function docy_get_page_title( $page_title_name = '' ) {


	$args = array(
		'post_type'      => 'page',
		'posts_per_page' => - 1,
		'post_status'    => 'publish'
	);

	$pages = get_posts( $args );

	if ( ! empty( $pages ) ) {
		$title_name_id = '';
		foreach ( $pages as $page ) {
			if ( $page->post_title == $page_title_name ) {
				$title_name_id = $page->ID;
			}
		}

		return $title_name_id;
	}

}

/**
 * Remove px || em || % from array
 *
 * @param $array
 *
 */
function docy_dimension_exclude( $array ) {
	$result = array();

	foreach ( $array as $value ) {
		$valueWithoutPx = str_replace( 'px', '', $value );
		$valueWithoutPx = str_replace( 'em', '', $valueWithoutPx );
		$valueWithoutPx = str_replace( '%', '', $valueWithoutPx );
		$result[]       = $valueWithoutPx;
	}

	return $result;
}

function docy_theme_option_dimension( $key, $mode ) {

	$top    = docy_opt( $key )[ $mode . '-top' ] ?? '';
	$right  = docy_opt( $key )[ $mode . '-right' ] ?? '';
	$left   = docy_opt( $key )[ $mode . '-left' ] ?? '';
	$bottom = docy_opt( $key )[ $mode . '-bottom' ] ?? '';
	$modePx = $top . $right . $left . $bottom;

	if ( preg_match( '/px|em|%/', $modePx ) ) {

		$top          = docy_opt( $key )[ $mode . '-top' ] ?? '';
		$right        = docy_opt( $key )[ $mode . '-right' ] ?? '';
		$left         = docy_opt( $key )[ $mode . '-left' ] ?? '';
		$bottom       = docy_opt( $key )[ $mode . '-bottom' ] ?? '';
		$padding_unit = docy_opt( $key )['units'] ?? '';

		$top    = docy_dimension_exclude( [ $top ] )[0];
		$right  = docy_dimension_exclude( [ $right ] )[0];
		$left   = docy_dimension_exclude( [ $left ] )[0];
		$bottom = docy_dimension_exclude( [ $bottom ] )[0];

		$key = [
			'top'    => $top,
			'right'  => $right,
			'left'   => $left,
			'bottom' => $bottom,
			'units'  => $padding_unit
		];
	} else {
		$key = docy_opt( $key );
	}

	return $key;
}

function docy_theme_option_typo( $key = '' ) {

	$font_size   = docy_opt( $key )['font-size'] ?? '';
	$line_height = docy_opt( $key )['line-height'] ?? '';

	$typoPx = $font_size . $line_height;

	if ( preg_match( '/px|em|%/', $typoPx ) ) {

		$font_size   = docy_dimension_exclude( [ $font_size ] )[0];
		$line_height = docy_dimension_exclude( [ $line_height ] )[0];

		$typo = [
			'font-family' => docy_opt( $key )['font-family'] ?? '',
			'font-size'   => $font_size,
			'font-weight' => docy_opt( $key )['font-weight'] ?? '',
			'subset'      => docy_opt( $key )['subsets'] ?? '',
			'line-height' => $line_height,
			'color'       => docy_opt( $key )['color'] ?? '',
			'text-align'  => docy_opt( $key )['text-align'] ?? '',
		];

	} else {
		$typo = docy_opt( $key );
	}

	return $typo;
}


/*
 * Set post views count using post meta
 */
function docy_post_views( $post_ID ) {
	$countKey = 'docy_post_views_count';
	$count    = get_post_meta( $post_ID, $countKey, true );
	if ( $count == '' ) {
		delete_post_meta( $post_ID, $countKey );
		add_post_meta( $post_ID, $countKey, '1' );
	} else {
		$count ++;
		update_post_meta( $post_ID, $countKey, $count );
	}
}

/**
 * Limit latter
 *
 * @param        $string
 * @param        $limit_length
 * @param string $suffix
 */
function docy_limit_letter( $string, $limit_length, $suffix = '...' ) {
	if ( strlen( $string ) > $limit_length ) {
		echo strip_shortcodes( substr( $string, 0, $limit_length ) . $suffix );
	} else {
		echo strip_shortcodes( esc_html( $string ) );
	}
}

/**
 * Get custom icon [Elegant Icons]
 */
if ( ! function_exists( 'docy_cs_elegant_icons' ) ) {

	function docy_cs_elegant_icons( $icons = [] ) {
		// Adding new icons
		$icons[] = array(
			'title' => 'Elegant Icons',
			'icons' => array(
				'icon_chat_alt',
				'icon_images',
				'social_youtube',
			)
		);

		// Move custom icons to top of the list.
		$icons = array_reverse( $icons );

		return $icons;
	}

	add_filter( 'csf_field_icon_add_icons', 'docy_cs_elegant_icons' );
}


/**
 * Enqueue fontawesome for codes tart framework
 */
/*if ( ! function_exists( 'docy_codestar_fontawesome' ) ) {
	function docy_codestar_fontawesome(): void
    {
		wp_enqueue_style( 'fa5', 'https://use.fontawesome.com/releases/v5.13.0/css/all.css', array(), '5.13.0', 'all' );
	}

	add_action( 'wp_enqueue_scripts', 'docy_codestar_fontawesome' );
}*/


/**
 * Retrieve an associative array of post IDs and titles for a given post type.
 *
 * @param string $post_type The post type to retrieve posts from.
 *
 * @return array Associative array of post IDs and titles.
 */
function docy_get_post_options( string $post_type ): array {

	// Initialize the options array with a default value.
	$options = [ '' => esc_html__( 'Default', 'docy' ) ];

	// Retrieve all posts of the given post type, ordered by name.
	$posts = get_posts(
		[
			'post_type'      => $post_type,
			'posts_per_page' => - 1,
			'orderby'        => 'name',
			'order'          => 'ASC',
		]
	);

	// Add each post's ID and title to the options array.
	foreach ( $posts as $post ) {
		$options[ $post->ID ] = $post->post_title;
	}

	return $options;
}


/**
 * Page title
 *
 * @return string
 */
function docy_page_title() {
	$opt = get_option( 'docy_opt' );

	if ( is_home() ) {
		$blog_title = ! empty( $opt['blog_title'] ) ? $opt['blog_title'] : esc_html__( 'Blog', 'docy' );
		echo esc_html( $blog_title );
	} elseif ( class_exists('WooCommerce') && is_shop() ) {
		$shop_title = ! empty( $opt['shop_title'] ) ? $opt['shop_title'] : esc_html__( 'Shop', 'docy' );
		echo esc_html( $shop_title );
	} elseif ( class_exists('bbPress') && in_array( 'bbpress', get_body_class() ) ) {
		the_title();
	} elseif ( is_page() || is_single() ) {
		the_title();
	} elseif ( is_category() ) {
		single_cat_title();
	} elseif ( is_archive() ) {
		the_archive_title();
	} elseif ( is_search() ) {
		esc_html_e( 'Search result for: “', 'docy' );
		echo get_search_query() . '”';
	} else {
		the_title();
	}
}

/**
 * Page subtitle
 *
 * @return string
 */
function docy_page_subtitle() {
	$opt      = get_option( 'docy_opt' );
	$subtitle = ''; // Initialize an empty variable for the subtitle.

	if ( docy_opt( 'sbnr_subtitle_fieldset', '', 'is_page_subtitle' ) == '1' ) {
		if ( is_home() ) {
			$subtitle = ! empty( $opt['blog_subtitle'] ) ? esc_html( $opt['blog_subtitle'] ) : '';
		} elseif ( is_page() || is_single() ) {
			$subtitle = has_excerpt() && !is_singular('product') ? get_the_excerpt() : '';
		} elseif ( is_category() ) {
			$subtitle = category_description();
		} elseif ( is_tag() ) {
			$subtitle = tag_description();
		} elseif ( is_tax() ) {
			$subtitle = term_description();
		}

		// Echo the subtitle wrapped with wpautop.
		echo wpautop( $subtitle );
	}
}

/**
 * Extracts the YouTube video ID from a given URL.
 *
 * This function uses a regular expression to find the 'v' parameter
 * in a YouTube URL, which contains the video ID.
 *
 * @param string $url The YouTube video URL to extract the ID from.
 *
 * @return string The extracted YouTube video ID, or an empty string if not found.
 */
function docy_get_youtube_video_id( $url ): string {
	preg_match('/[\\?\\&]v=([^\\?\\&]+)/', $url, $matches);
	return $matches[1] ?? '';
}