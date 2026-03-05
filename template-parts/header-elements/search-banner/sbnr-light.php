<?php
$opt        = get_option( 'docy_opt' );
// Force indexbg.svg as background
$fallback_bg = esc_url( get_template_directory_uri() . '/assets/img/indexbg.svg' );
$bg_style   = "style='background-image: url(" . $fallback_bg . "); background-size: cover; background-position: center; background-color: #ffffff00;'";

$breadcrumb_container = is_singular( 'docs' ) || is_post_type_archive( 'docs' ) ? 'custom_container' : '';
$placeholder          = ! empty( $opt['banner_search_placeholder'] ) ? $opt['banner_search_placeholder'] : '';
$is_focus_search      = $opt['is_focus_search'] ?? '';
$is_focus_search      = $is_focus_search == '1' ? 'focused-form' : '';
?>

<section class="banner-bg">
    <div class="doc_banner_area search-banner-light banner_creative1 sbnr-global bg-<?php echo docy_opt( 'search_banner_bg' ) ?>" <?php echo $bg_style ?>>
		<?php if ( docy_opt( 'is_banner_overlay' ) == '1' ) :
			$overlay_styles = array();

			if ( docy_opt( 'is_sbnr_blur' ) == '1' ) {
				$overlay_styles[] = 'backdrop-filter: blur(' . intval( docy_opt( 'sbnr_blur_density', '10' ) ) . 'px)';
			}

			$overlay_colors = docy_opt( 'sbnr_overlay_color' );
			$gradient_from  = $overlay_colors['gradient_bg_color-from'] ?? '';
			$gradient_to    = $overlay_colors['gradient_bg_color-to'] ?? '';

			// Use very light overlay to show background image
			if ( empty( $gradient_from ) || empty( $gradient_to ) ) {
				$gradient_from = 'rgba(255, 255, 255, 0.1)';
				$gradient_to   = 'rgba(255, 255, 255, 0.2)';
			}

			$overlay_styles[] = 'background: linear-gradient(to bottom, ' . esc_attr( $gradient_from ) . ', ' . esc_attr( $gradient_to ) . ')';
			?>
            <div class="overlay-bg" style="<?php echo esc_attr( implode( '; ', $overlay_styles ) ); ?>;">
            </div>
		<?php endif; ?>

        <div class="container">
            <div class="doc_banner_content">
				<?php
				// Title and subtitle
				include('title.php');

                echo '<p class="banner-support-title" style="color:#161C52;font-weight:600;font-size:40px;line-height:125%;">The Ultimate Space for<br> CMGalaxy Learning and Support</p>';

                if ( docy_opt( 'sbnr_search_fieldset', '1', 'is_sbnr_search' ) == '1' ) : ?>
                    <form id="ajax-search-form" action="<?php echo esc_url( site_url() ); ?>" class="header_search_form <?php echo esc_attr( $is_focus_search ) ?>">

                        <div class="header_search_form_info">
                            <div class="stylish-search stylish-search--banner">
                                <div class="stylish-search__shell">
                                    <div class="stylish-search__body" style="height:57px;">
                                        <span class="stylish-search__sparkle" aria-hidden="true">
                                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/lexlogo.png' ); ?>" alt="" width="24" height="24" loading="lazy" />
                                        </span>
                                        <input type="search" name="s" id="searchInput" class="stylish-search__input" placeholder="<?php echo esc_attr( $placeholder ) ?>" autocomplete="off" value="<?php echo get_search_query() ?>"style="
    border: 0px;
" />
                                        <?php include( 'search-spinner.php' ); ?>
                                        <button type="submit" class="stylish-search__submit" aria-label="<?php esc_attr_e( 'Search', 'docy' ); ?>">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14.707 13.293a1 1 0 0 1 1.32-.083l.094.083 2.5 2.5a1 1 0 0 1-1.32 1.497l-.094-.083-2.5-2.5a1 1 0 0 1 0-1.414z" fill="currentColor" />
                                                <path d="M9 2a7 7 0 1 1 0 14A7 7 0 0 1 9 2zm0 2a5 5 0 1 0 0 10A5 5 0 0 0 9 4z" fill="currentColor" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php 
                            if ( defined( 'ICL_LANGUAGE_CODE' ) ) : ?>
                                <input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
                                <?php 
                            endif; 
                            ?>
                        </div>

					<?php
					// include( 'ajax-search-results.php' );
					include( 'keywords.php' );
						include( 'ajax-search-results.php' );
						?>
                    </form>
				    <?php 
                endif; 
                ?>

            </div>
        </div>
    </div>
</section>

<?php
// Breadcrumb removed per request
// include( 'breadcrumb.php' );
include( 'featured-video.php' );