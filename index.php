	<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package docy
 */

get_header();

// Remove shadow and add stroke for cards on ?cat=1 page only
if ( isset( $_GET['cat'] ) && intval( $_GET['cat'] ) === 1 ) : ?>
    <style id="cat-1-no-shadow">
        /* Remove ALL shadows and add stroke only on cat=1 page */
        body * {
            -webkit-box-shadow: none !important;
            -moz-box-shadow: none !important;
            box-shadow: none !important;
        }
        .blog_grid_post,
        .card,
        .category-card,
        a.card {
            border: 2px solid #ccc !important;
            border-radius: 4px !important;
        }
        .blog_grid_post:hover,
        .card:hover,
        .category-card:hover,
        a.card:hover {
            border-color: #666 !important;
        }
    </style>
<?php endif;

// Remove borders for cards on ?cat=3 page only
if ( isset( $_GET['cat'] ) && intval( $_GET['cat'] ) === 3 ) : ?>
    <style id="cat-3-no-border">
        /* Remove borders for cat=3 page */
        .card {
            border: none !important;
        }
    </style>
<?php endif;

// Add custom container width for category pages
if ( isset( $_GET['cat'] ) && ! empty( $_GET['cat'] ) ) : ?>
    <style id="cat-page-container-width">
        @media (min-width: 1400px) {
            .container, .container-lg, .container-md, .container-sm, .container-xl, .container-xxl {
                max-width: 100% !important;
                padding-left: 30px;
                padding-right: 30px;
            }
        }

        /* Desktop Layout (768px and up) */
        @media (min-width: 768px) {
            .category-left-sidebar-col {
                -ms-flex: 0 0 20% !important;
                flex: 0 0 20% !important;
                max-width: 20% !important;
                width: 20% !important;
            }

            .category-main-col:not(.category-main-with-right) {
                -ms-flex: 0 0 80% !important;
                flex: 0 0 80% !important;
                max-width: 80% !important;
                width: 80% !important;
            }

            .category-main-col.category-main-with-right {
                -ms-flex: 0 0 55% !important;
                flex: 0 0 55% !important;
                max-width: 55% !important;
                width: 55% !important;
            }

            .category-right-sidebar-col {
                -ms-flex: 0 0 25% !important;
                flex: 0 0 25% !important;
                max-width: 25% !important;
                width: 25% !important;
            }
            
            /* Ensure columns float left for safety if flex fails */
            .category-left-sidebar-col,
            .category-main-col,
            .category-right-sidebar-col {
                float: left;
            }
        }

        /* Mobile Layout (767px and below) */
        @media (max-width: 767px) {
            .category-left-sidebar-col,
            .category-main-col,
            .category-right-sidebar-col {
                -ms-flex: 0 0 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
                width: 100% !important;
                float: none !important;
            }
            
            .category-left-sidebar-col.col-lg-3,
            .category-main-col.col-lg-6,
            .category-main-col.category-main-with-right,
            .category-right-sidebar-col.col-lg-3 {
                -ms-flex: 0 0 100% !important;
                flex: 0 0 100% !important;
                max-width: 100% !important;
                width: 100% !important;
                float: none !important;
            }

            .category-left-sidebar-col {
                margin-bottom: 1.5rem;
                border-right: none !important;
            }
        }

        /* Large screen adjustments (1440px and above) */
        @media (min-width: 1440px) {
            .category-main-col.category-main-with-right {
                -ms-flex: 0 0 55% !important;
                flex: 0 0 55% !important;
                max-width: 55% !important;
                width: 55% !important;
            }
        }
    </style>
<?php endif;

// Breadcrumb now moved to header card - no duplicate needed here

// Prepare category header data for ?cat pages or category archives
$cat_header_data = null;
$is_category_page = (isset( $_GET['cat'] ) && ! empty( $_GET['cat'] )) || is_category();

if ( $is_category_page ) {
    if (isset( $_GET['cat'] ) && ! empty( $_GET['cat'] )) {
        $current_cat_id = intval( $_GET['cat'] );
    } else {
        $current_cat_id = get_queried_object_id();
    }
    $current_category = get_category( $current_cat_id );

    if ( $current_category && ! is_wp_error( $current_category ) ) {
        $cat_name = $current_category->name;

        $cat_desc        = '';
        $raw_description = category_description( $current_category );
        if ( $raw_description ) {
            $cat_desc = wp_trim_words( wp_strip_all_tags( $raw_description ), 24, '…' );
        }

        $cat_count = (int) $current_category->count;

        // Collect up to 3 author display names from recent posts in this category
        $author_names = [];
        $author_ids   = [];
        $author_query = new WP_Query(
            [
                'post_type'           => 'post',
                'posts_per_page'      => 20,
                'cat'                 => $current_cat_id,
                'ignore_sticky_posts' => true,
                'fields'              => 'ids',
            ]
        );

        if ( $author_query->have_posts() ) {
            foreach ( $author_query->posts as $post_id ) {
                $aid = (int) get_post_field( 'post_author', $post_id );
                if ( $aid && ! in_array( $aid, $author_ids, true ) ) {
                    $author_ids[]   = $aid;
                    $author_names[] = get_the_author_meta( 'display_name', $aid );
                }

                if ( count( $author_names ) >= 3 ) {
                    break;
                }
            }
        }

        wp_reset_postdata();

        $byline              = '';
        $author_count_total  = count( $author_ids );
        if ( $author_count_total === 1 ) {
            $byline = sprintf( __( 'By %s', 'docy' ), esc_html( $author_names[0] ) );
        } elseif ( $author_count_total === 2 ) {
            $byline = sprintf( __( 'By %1$s and %2$s', 'docy' ), esc_html( $author_names[0] ), esc_html( $author_names[1] ) );
        } elseif ( $author_count_total > 2 ) {
            $byline = sprintf( __( 'By %1$s and %2$s others', 'docy' ), esc_html( $author_names[0] ), number_format_i18n( $author_count_total - 1 ) );
        }

        $author_icon_relatives = [
            '/assets/img/author-icon.png',
            '/assets/images/author-icon.png',
            '/assets/extra/user.png',
        ];
        $author_icon_url       = '';
        foreach ( $author_icon_relatives as $rel ) {
            $path = get_template_directory() . $rel;
            if ( file_exists( $path ) ) {
                $author_icon_url = get_template_directory_uri() . $rel;
                break;
            }
        }

        $category_icon_url = '';
        $category_icon_rel = '/assets/img/knowledge.png';
        $category_icon_path = get_template_directory() . $category_icon_rel;
        if ( file_exists( $category_icon_path ) ) {
            $category_icon_url = get_template_directory_uri() . $category_icon_rel;
        }

        $cat_header_data = [
            'id'               => $current_cat_id,
            'name'             => $cat_name,
            'desc'             => $cat_desc,
            'count'            => $cat_count,
            'byline'           => $byline,
            'author_icon_url'  => $author_icon_url,
            'category_icon_url'=> $category_icon_url,
        ];

        $GLOBALS['cat_header_data'] = $cat_header_data;
    }
}

if ( ! function_exists( 'docy_render_category_header_card' ) ) {
    function docy_render_category_header_card( $data ) {
        if ( empty( $data ) || ! is_array( $data ) ) {
            return;
        }

        $count_text = sprintf( _n( '%s article', '%s articles', $data['count'], 'docy' ), number_format_i18n( $data['count'] ) );
        ?>
        <div class="card border shadow-sm p-4 mb-4 category-header-card">
            <?php
            // Add breadcrumb inside header card for category pages with ?cat= parameter
            if ( isset( $_GET['cat'] ) && ! empty( $_GET['cat'] ) ) {
                $is_breadcrumb = docy_opt('is_breadcrumb', '1');
                if ( $is_breadcrumb == '1' ) {
                    $cat_id = intval( $_GET['cat'] );
                    $category = get_category( $cat_id );
                    if ( $category && ! is_wp_error( $category ) ) {
                        ?>
                        <nav aria-label="breadcrumb" class="mb-3">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item">
                                    <a href="<?php echo esc_url( home_url( '/' ) ) ?>">Home</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    <?php echo esc_html( $category->name ); ?>
                                </li>
                            </ol>
                        </nav>
                        <?php
                    }
                }
            }
            ?>
            <h1 class="mb-2"><?php echo esc_html( $data['name'] ); ?></h1>
            <?php if ( ! empty( $data['desc'] ) ) : ?>
                <p class="text-muted mb-3"><?php echo esc_html( $data['desc'] ); ?></p>
            <?php endif; ?>
            <div class="small text-muted d-flex align-items-center gap-2">
                <?php if ( ! empty( $data['byline'] ) ) : ?>
                    <span class="d-flex align-items-center gap-1">
                        <?php if ( ! empty( $data['author_icon_url'] ) ) : ?>
                            <img src="<?php echo esc_url( $data['author_icon_url'] ); ?>" alt="<?php esc_attr_e( 'Author icon', 'docy' ); ?>" width="14" height="14" />
                        <?php else : ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v2h16v-2c0-2.761-3.582-5-8-5z" fill="currentColor" />
                            </svg>
                        <?php endif; ?>
                        <?php echo esc_html( $data['byline'] ); ?>
                    </span>
                    <span>•</span>
                <?php endif; ?>
                <span><?php echo esc_html( $count_text ); ?></span>
            </div>
        </div>
        <?php
    }
}

$opt             = get_option( 'docy_opt' );
$has_sidebar     = false; // Hide sidebar for entire site
$blog_column     = $has_sidebar ? '8' : '12';
$blog_layout_opt = ! empty( $opt['blog_layout'] ) ? $opt['blog_layout'] : 'list';
$blog_layout     = ! empty( $_GET['blog_layout'] ) ? $_GET['blog_layout'] : $blog_layout_opt;

// Force cards layout on the blog home so items can sit side-by-side
if ( is_home() && $blog_layout === 'list' ) {
    $blog_layout = 'blog_category';
}

// Force blog_category layout for category pages with ?cat parameter or category archives
if ( $is_category_page ) {
    $blog_layout = 'blog_category';
}


if ( $blog_layout == 'list' ) {
	$sec_class = 'doc_blog_classic_area sec_pad';
	$is_row    = '';
} elseif ( $blog_layout == 'grid' ) {
	$sec_class = 'doc_blog_grid_area sec_pad';
	$is_row    = '';
} elseif ( $blog_layout == 'blog_category' ) {
	$sec_class = 'doc_blog_grid_area';
	$is_row    = ' blog_grid_tab';
} else {
	$sec_class = 'doc_blog_classic_area sec_pad';
	$is_row    = '';
}

// Is Sticky - but not for category pages
if ( $blog_layout == 'blog_category' && ! $is_category_page ) {
	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/contents/content-sticky' );
	endwhile;
}
?>

    <section class="<?php echo esc_attr( $sec_class ) ?>" style="<?php echo $is_category_page ? 'margin-top: 50px;' : ''; ?>">
		<?php
		// Debug: Show current layout and category
		echo '<!-- Debug: blog_layout = ' . $blog_layout . ', is_category_page = ' . ($is_category_page ? 'yes' : 'no') . ' -->';
		
		if ( $blog_layout == 'blog_category' && ! $is_category_page ) {
			// Dynamic Category Grid (top-level categories) - only show on home page, not category pages
			$docy_categories = get_categories([
				'hide_empty' => true,
				'parent'     => 0,
			]);
			if ( ! empty( $docy_categories ) ) {
                echo '<div class="container mb-4">
                <div class="row">
                <div class="col-12">
                <p class="category-intro lead mb-10" style="font-size: 18px;
    font-weight: 400;
    line-height: 170%;
    text-align: center;margin-top:30px;">Welcome to the HubSpot Developer Documentation. Build custom CRM and data-driven website experiences on HubSpot. Follow the steps below to get started, or jump directly into a quickstart that fits your build.</p>
                </div>
                </div>
                </div>';
			echo '<div class="container">
			<div class="row g-4 mb-4">';
			foreach ( $docy_categories as $cat ) {
					$cat_link = get_category_link( $cat->term_id );
					$cat_name = esc_html( $cat->name );
					$cat_desc = esc_html( wp_trim_words( category_description( $cat ), 18, '…' ) );
					$cat_count = intval( $cat->count );
                    // Build simple author byline from latest posts in this category
                    $author_names = [];
                    $author_ids   = [];
                    $author_query = new WP_Query([
                        'post_type'           => 'post',
                        'posts_per_page'      => 10,
                        'cat'                 => $cat->term_id,
                        'ignore_sticky_posts' => true,
                        'fields'              => 'ids',
                    ]);
                    if ( $author_query->have_posts() ) {
                        foreach ( $author_query->posts as $post_id ) {
                            $aid = (int) get_post_field( 'post_author', $post_id );
                            if ( $aid && ! in_array( $aid, $author_ids, true ) ) {
                                $author_ids[]   = $aid;
                                $author_names[] = get_the_author_meta( 'display_name', $aid );
                            }
                            if ( count( $author_names ) >= 3 ) {
                                break;
                            }
                        }
                    }
                    wp_reset_postdata();
                    $byline = '';
                    $author_count_total = count( $author_ids );
                    if ( $author_count_total === 1 ) {
                        $byline = sprintf( __( 'By %s', 'docy' ), esc_html( $author_names[0] ) );
                    } elseif ( $author_count_total === 2 ) {
                        $byline = sprintf( __( 'By %1$s and %2$s', 'docy' ), esc_html( $author_names[0] ), esc_html( $author_names[1] ) );
                    } elseif ( $author_count_total > 2 ) {
                        $byline = sprintf( __( 'By %1$s and %2$s others', 'docy' ), esc_html( $author_names[0] ), number_format_i18n( $author_count_total - 1 ) );
                    }
                    echo '<div class="col-md-6 col-lg-6">';
                    echo '<a class="card h-100 category-card border text-reset text-decoration-none" href="' . esc_url( $cat_link ) . '">';
                    echo '<div class="card-body">';
                    echo '<div class="category-card__icon custom-icon" aria-hidden="true">'
                        . '<svg width="36" height="36" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
                        . '<path d="M6 4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v16l-6-3-6 3V4z" stroke="currentColor" stroke-width="1.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>'
                        . '</svg>'
                        . '</div>';
                    echo '<h5 class="card-title mb-2">' . $cat_name . '</h5>';
                    echo '<p class="card-text text-muted mb-3">' . $cat_desc . '</p>';
                    echo '<div class="category-card__meta small text-muted">';
                    $author_icon_relatives = [
                        '/assets/img/author-icon.png',
                        '/assets/images/author-icon.png',
                        '/assets/extra/user.png',
                    ];
                    $author_icon_path = '';
                    $author_icon_url  = '';
                    foreach ( $author_icon_relatives as $rel ) {
                        $path = get_template_directory() . $rel;
                        if ( file_exists( $path ) ) {
                            $author_icon_path = $path;
                            $author_icon_url  = get_template_directory_uri() . $rel;
                            break;
                        }
                    }
                    $author_markup = '';
					if ( $author_icon_path ) {
						$author_markup .= '<span class="category-card__author-icon">'
						   . '<img class="category-card__author-icon-img" src="' . esc_url( $author_icon_url ) . '" alt="" />'
						   . '</span>';
					} else {
						$author_markup .= '<span class="category-card__author-icon" aria-hidden="true">'
						   . '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">'
						   . '<path d="M12 12c2.761 0 5-2.239 5-5s-2.239-5-5-5-5 2.239-5 5 2.239 5 5 5zm0 2c-4.418 0-8 2.239-8 5v2h16v-2c0-2.761-3.582-5-8-5z" fill="currentColor"/>'
						   . '</svg>'
						   . '</span>';
					}
					if ( $byline ) {
						$author_markup .= '<span class="category-card__byline">' . esc_html( $byline ) . '</span>';
					}
					if ( $author_markup ) {
						echo '<div class="category-card__author d-flex align-items-center gap-2">' . $author_markup . '</div>';
					}
                    echo '<span>' . sprintf( _n( '%s article', '%s articles', $cat_count, 'docy' ), number_format_i18n( $cat_count ) ) . '</span>';
                    echo '</div>';
                    echo '</div>';
                    echo '</a>';
                    echo '</div>';
				}
				echo '</div></div>';
				?>
				<section class="container highest-rated-section">
					<div class="text-center mb-5">
						<h2 class="highest-rated-title" style="font-weight:600;">Highest rated articles</h2>
					</div>
					<div class="row g-4">
						<div class="col-md-4">
							<div class="highest-card h-100">
								<div class="highest-card__icon" aria-hidden="true">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/act.png' ); ?>" alt="" width="380" height="225" loading="lazy" />
								</div>
								<h3 class="highest-card__title">Act on Data in CMGalaxy</h3>
								<p class="highest-card__desc">Automate actions, trigger workflows, and extend HubSpot logic to fit your business.</p>
								<ul class="highest-card__links list-unstyled">
									<li><a href="#">Custom Coded Workflows</a></li>
									<li><a href="#">Custom Action Builders</a></li>
									<li><a href="#">Gated Content</a></li>
									<li><a href="#">Conversations API</a></li>
								</ul>
							</div>
						</div>
						<div class="col-md-4">
							<div class="highest-card h-100">
								<div class="highest-card__icon" aria-hidden="true">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/vizulize.png' ); ?>" alt="" width="380" height="225" loading="lazy" />
								</div>
								<h3 class="highest-card__title">Visualize Data in CMGalaxy</h3>
								<p class="highest-card__desc">Add custom UI elements (like CRM cards) to display your data right inside of CMGalaxy.</p>
								<ul class="highest-card__links list-unstyled">
									<li><a href="#">Custom Cards</a></li>
									<li><a href="#">App settings page</a></li>
									<li><a href="#">Custom Quote Templates</a></li>
									<li><a href="#">Custom Calling Extensions</a></li>
								</ul>
							</div>
						</div>
						<div class="col-md-4">
							<div class="highest-card h-100">
								<div class="highest-card__icon" aria-hidden="true">
									<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/get.png' ); ?>" alt="" width="380" height="225" loading="lazy" />
								</div>
								<h3 class="highest-card__title">Get Data In and Out of CMGalaxy</h3>
								<p class="highest-card__desc">Automate actions, trigger workflows, and extend HubSpot logic to fit your business.</p>
								<ul class="highest-card__links list-unstyled">
									<li><a href="#">Import/Export Data</a></li>
									<li><a href="#">Custom Channels</a></li>
									<li><a href="#">CRM Object Sync</a></li>
								</ul>
							</div>
						</div>
					</div>
				</section>
				<?php
			}
			?>
			<section class="home-cta-banner">
					<div class="container">
						<div class="home-cta-banner__inner">
							<div class="home-cta-banner__copy">
								<h2 class="home-cta-banner__title">See How We Helped Businesses Like Yours <span>Grow 3x </span>Faster.</h2>
								<p class="home-cta-banner__subtitle">Let’s build a performance-driven ad strategy that works for your business.</p>
							</div>
							<div class="home-cta-banner__actions">
								<a class="home-cta-banner__primary" href="#">Request a Demo</a>
								<a class="home-cta-banner__secondary" href="#">
									<span class="home-cta-banner__icon" aria-hidden="true">
										<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/message.svg' ); ?>" alt="" width="24" height="24">
									</span>
									Let’s Talk Growth
								</a>
							</div>
						</div>
					</div>
				</section>
			<?php
		}
		?>
        <?php 
        // Show container for category pages or non-home pages
        $show_container = ! ( is_home() && $blog_layout == 'blog_category' && ! $is_category_page );
        if ( $show_container ) : ?>
        <div class="container">
            <div class="row <?php echo esc_attr( $is_row ) ?>">
				<?php
				/**
				 * Render posts based on the selected category or default query.
				 * 
				 * @return void
				 */
                function extracted(): void {
					// Sanitize category input from URL parameters.
					$category = isset( $_GET['category'] ) ? sanitize_text_field( $_GET['category'] ) : '';

					if ( $category ) {
						// Create a custom query if a specific category is selected.
						$cat_posts = new WP_Query([
							'post_type'           => 'post',
							'posts_per_page'      => -1,
							'category_name'       => $category,
							'ignore_sticky_posts' => true,
						]);

						// Output posts from the custom query.
                        if ( $cat_posts->have_posts() ) {
                            echo '<div class="col-lg-12">';
                            echo '<div class="row">';
                            while ( $cat_posts->have_posts() ) : $cat_posts->the_post();
                                get_template_part('template-parts/contents/content-grid');
                            endwhile;
                            echo '</div>';
                            echo '</div>';
                        }
						wp_reset_postdata(); // Reset global post data after custom query.
						
					} else {
						// Default query loop if no category is selected.
                        echo '<div class="col-lg-12">';
                        echo '<div class="row">';
                        while ( have_posts() ) : the_post();
                            get_template_part('template-parts/contents/content-grid');
                        endwhile;
                        echo '</div>';
                        echo '</div>';
					}
				}

				if ( $blog_layout == 'list' ) {
					// Check if this is a category page with ?cat parameter
					$show_cat_sidebar = $is_category_page;
					
					if ( $show_cat_sidebar ) {
						// Reset the query to start from the beginning
						rewind_posts();
						?>
						<!-- Sidebar Column for Category Page -->
					<div class="col-lg-3 mb-4 category-left-sidebar-col">
							<style>
								/* Ensure sidebar is visible */
								.modern-sidebar {
									background: #ffffff;
									border: 1px solid #e5e7eb;
									border-radius: 8px;
									padding: 0;
									margin-bottom: 2rem;
									position: sticky;
									top: 100px;
									max-height: calc(100vh - 120px);
									overflow-y: auto;
								}
							</style>
							<?php get_template_part( 'template-parts/sidebar-modern' ); ?>
						</div>
						
						<!-- Content Column -->
						<div class="col-lg-9 category-main-col">
							<?php
							if ( isset( $GLOBALS['cat_header_data'] ) && ! empty( $GLOBALS['cat_header_data'] ) ) {
								docy_render_category_header_card( $GLOBALS['cat_header_data'] );
							}
							?>
							<div class="row">
								<?php
								while ( have_posts() ) : the_post();
									get_template_part( 'template-parts/contents/content-grid' );
								endwhile;
								?>
							</div>
							<?php Docy_helper()->pagination(); ?>
						</div>
						<?php
					} else {
						// Normal list layout without sidebar
						if ( $has_sidebar ) {
							get_sidebar();
						}
						?>
						<div class="col-lg-<?php echo esc_attr( $blog_column ) ?> pe-4">
							<?php
							while ( have_posts() ) : the_post();
								get_template_part( 'template-parts/contents/content', get_post_format() );
							endwhile;
							Docy_helper()->pagination();
							?>
						</div>
						<?php
					}
				} elseif ( $blog_layout == 'grid' ) {
					if ( $has_sidebar ) {
						get_sidebar();
					}
					// Grid layout content removed - not needed for category pages
				} elseif ( $blog_layout == 'blog_category' ) {
					// Add sidebar for category pages with ?cat parameter OR pretty permalink category archives
					$show_sidebar = (isset( $_GET['cat'] ) && ! empty( $_GET['cat'] )) || is_category();
					$current_cat_id = 0;
					
					if (isset( $_GET['cat'] ) && ! empty( $_GET['cat'] )) {
						$current_cat_id = intval( $_GET['cat'] );
					} elseif (is_category()) {
						$current_cat_id = get_queried_object_id();
					}
					
					if ( $show_sidebar ) {
						// Debug: Add comment to check if this section is reached
						echo '<!-- Category sidebar section reached for cat: ' . $current_cat_id . ' -->';
						
						// Apply 3-column layout with both sidebars to all category pages
					if ( $show_sidebar ) {
						echo '<!-- Category ' . $current_cat_id . ' special layout with left modern sidebar and right image sidebar -->';
						?>
							<!-- Left Modern Sidebar for Cat 3 -->
					<div class="mb-4 category-left-sidebar-col" style="border-right: 1px solid #e5e7eb; flex: 0 0 20% !important; max-width: 20% !important;">
				<style>
								/* Sticky left sidebar for cat=2 and cat=3 */
		.category-left-sidebar-col {
			position: sticky;
			 /* Stick below the navbar */
			align-self: flex-start;
			max-height: calc(100vh - 170px);
			overflow-y: auto;
		}	
			
			/* Extend the border upward to connect with navbar */
			.category-left-sidebar-col::before {
				content: '';
				position: absolute;
				right: 0;
				top: -118px; /* Adjusted for 38px margin */
				width: 1px;
				height: 118px;
				background-color: #e5e7eb;
				z-index: 1;
			}
			
			/* Remove Bootstrap row padding */
			.category-left-sidebar-col {
				padding-left: 0 !important;
				padding-right: 0 !important;
			}
			
			/* Remove margin-top from all category columns */
			.category-main-col,
			.category-right-sidebar-col {
				/* margin-top removed */
			}
			
			/* Remove margin-top from category header card */
			.category-header-card,
			.category-main-col .card:first-child,
			.category-main-col > .card,
			.category-main-col > .row {
				margin-top: 0 !important;
			}
				
				.modern-sidebar {
					background: #ffffff;
					/* border removed */
					border-radius: 8px;
					padding: 0;
					margin-bottom: 0;
					width: 100%;
					max-width: 100%; /* Changed from 240px to reduce gap */
				}
				
				/* Large screen adjustments */
				@media (min-width: 1440px) {
					.category-left-sidebar-col.col-lg-3 {
						max-width: 300px !important;
						width: 300px !important;
						flex: 0 0 300px !important;
					}
				}
				
				/* Smooth scrollbar for sidebar */
				.category-left-sidebar-col::-webkit-scrollbar {
					width: 6px;
				}
				
				.category-left-sidebar-col::-webkit-scrollbar-track {
					background: #f1f1f1;
					border-radius: 3px;
				}
				
				.category-left-sidebar-col::-webkit-scrollbar-thumb {
					background: #888;
					border-radius: 3px;
				}
				
				.category-left-sidebar-col::-webkit-scrollbar-thumb:hover {
					background: #555;
				}
				
				/* Mobile Responsive Styles for Left Sidebar */
				@media (max-width: 767px) {
					.category-left-sidebar-col {
						position: static !important; /* Disable sticky on mobile */
						top: auto !important;
						max-height: none !important;
						overflow-y: visible !important;
						padding-top: 0 !important;
						border-right: none !important; /* Remove border on mobile */
						margin-bottom: 2rem;
					}
					
					.category-left-sidebar-col::before {
						display: none; /* Hide the extending border line */
					}
					
					.modern-sidebar {
						max-width: 100% !important;
					}
				}
				
				@media (max-width: 576px) {
					.category-left-sidebar-col {
						padding-left: 0;
						padding-right: 0;
						margin-bottom: 1.5rem;
					}
				}
			</style>
				<?php get_template_part( 'template-parts/sidebar-modern' ); ?>
			</div>
							
							<!-- Content Column for Cat 3 -->
				<div class="category-main-col category-main-with-right" style="flex: 0 0 55% !important; max-width: 55% !important;">
								<?php
								if ( isset( $GLOBALS['cat_header_data'] ) && ! empty( $GLOBALS['cat_header_data'] ) ) {
									docy_render_category_header_card( $GLOBALS['cat_header_data'] );
								}
								?>
								<div class="row">
									<?php
									// Render posts for category page
									while ( have_posts() ) : the_post();
										get_template_part('template-parts/contents/content-grid');
									endwhile;
									?>
								</div>
								<?php Docy_helper()->pagination(); ?>
							</div>
							
							<!-- Right Image Sidebar for Cat 3 -->
							<div class="mb-4 category-right-sidebar-col" style="flex: 0 0 25% !important; max-width: 25% !important;">
								<div class="cat-3-right-sidebar">
									<div class="sidebar-widget">
										<!-- <h5 class="widget-title">Featured Image</h5> -->
										<?php
										// Array of random images from assets folder
										$random_images = array(
											
											get_template_directory_uri() . '/assets/img/sidebarimg.png',
											
										);
										
										// Select a random image
										$random_image = $random_images[array_rand($random_images)];
										?>
										<div class="random-image-container">
											<img src="<?php echo esc_url($random_image); ?>" alt="Random Featured Image" class="img-fluid rounded">
										</div>
									</div>

									<div class="sidebar-cta-card">
										<div class="sidebar-cta-icon" aria-hidden="true">
											<svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
												<rect x="4" y="8" width="36" height="32" rx="8" stroke="#3B82F6" stroke-width="2" />
												<path d="M12 18H16" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
												<path d="M12 23H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
												<path d="M12 28H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
												<path d="M28 18H32" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
												<path d="M28 23H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
												<path d="M28 28H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
											</svg>
										</div>
										<div class="sidebar-cta-text">
											<p class="sidebar-cta-eyebrow">New to CMGalaxy?</p>
											<p class="sidebar-cta-body">Check our <a href="#" class="sidebar-cta-link">Get Started</a> guides.</p>
										</div>
									</div>
									
									<!-- <div class="sidebar-widget mt-4">
										<h5 class="widget-title">Quick Info</h5>
										<div class="quick-info-content">
											<p>Welcome to our knowledge base! Explore our articles and find the information you need.</p>
											<ul class="info-list">
												<li><i class="fa fa-check"></i> Comprehensive guides</li>
												<li><i class="fa fa-check"></i> Regular updates</li>
												<li><i class="fa fa-check"></i> Expert insights</li>
											</ul>
										</div>
									</div> -->
								</div>
								
								<style>
			/* Sticky right sidebar for all category pages */
		.category-right-sidebar-col {
			position: sticky;
			top: 70px;
			align-self: flex-start;
			max-height: calc(100vh - 170px);
			overflow-y: auto;
			margin-top: 50px; /* Align with left sidebar and main content */
		}	
			
			.cat-3-right-sidebar {
				background: #ffffff;
				border-radius: 8px;
				padding: 0;
				max-width: 240px;
			}
			
			/* Smooth scrollbar for right sidebar */
			.category-right-sidebar-col::-webkit-scrollbar {
				width: 6px;
			}
			
			.category-right-sidebar-col::-webkit-scrollbar-track {
				background: #f1f1f1;
				border-radius: 3px;
			}
			
			.category-right-sidebar-col::-webkit-scrollbar-thumb {
				background: #888;
				border-radius: 3px;
			}
			
			.category-right-sidebar-col::-webkit-scrollbar-thumb:hover {
				background: #555;
			}
								
								.sidebar-widget {
                                    margin-top:20px;
									padding: 0px;
									border-bottom: 1px solid #f1f5f9;
								}
								
								.sidebar-widget:last-child {
									border-bottom: none;
								}
								
								.widget-title {
									font-size: 1.125rem;
									font-weight: 600;
									color: #1e293b;
									margin-bottom: 1rem;
								}
								
								.random-image-container {
									text-align: center;
								}
								
								.random-image-container img {
									max-width: 100%;
									height: auto;
									border-radius: 8px;
									
								}
								
								.quick-info-content p {
									color: #64748b;
									font-size: 0.9375rem;
									line-height: 1.6;
									margin-bottom: 1rem;
								}
								
								.info-list {
									list-style: none;
									padding: 0;
									margin: 0;
								}
								
								.info-list li {
									display: flex;
									align-items: center;
									gap: 0.5rem;
									padding: 0.5rem 0;
									color: #475569;
									font-size: 0.875rem;
								}
								
								.info-list li i {
									color: #10b981;
									font-size: 0.75rem;
								}
								
								@media (max-width: 992px) {
				.category-left-sidebar-col,
				.category-right-sidebar-col {
					position: static;
					max-height: none;
					overflow-y: visible;
				}
				
				.cat-3-right-sidebar {
					margin-top: 2rem;
				}
			}
								</style>
							</div>
							<?php
						} // This closes the `if ( $category_id == 3 )` block
					} else {
						// No sidebar - use full width
						extracted();
						Docy_helper()->pagination();
					}
					
					echo '</div>';
					wp_reset_postdata();
				}
				?>
            </div>
        </div>
        <?php endif; ?>
    </section>

<?php
get_footer();