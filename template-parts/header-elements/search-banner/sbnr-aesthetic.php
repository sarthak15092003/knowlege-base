<?php
$opt = get_option( 'docy_opt' );

$bg_image = ! empty( $opt['sbnr_bg_image2']['url'] ) ? "style='background: url(" . esc_url( $opt['sbnr_bg_image2']['url'] )
                                                       . ") no-repeat fixed center; background-size: cover;'" : '';

$placeholder     = ! empty( $opt['banner_search_placeholder'] ) ? $opt['banner_search_placeholder'] : '';
$is_focus_search = $opt['is_focus_search'] ?? '';
$is_focus_search = $is_focus_search == '1' ? 'focused-form' : '';

if ( class_exists( 'bbPress' ) ) {
	if ( is_singular( 'topic' ) || bbp_is_forum_archive() ) {
		$search_name = 'bbp_search';
	} else {
		$search_name = 's';
	}
} else {
	$search_name = 's';
}
?>

<section class="sbnr-aesthetic sbnr-global bg-<?php echo docy_opt( 'search_banner_bg' ) ?>" <?php echo $bg_image ?>>
		<?php
		Docy_helper()->image_from_settings( 'sbanner_left_image', 'p_absolute bl_left', 'leaf' );
		Docy_helper()->image_from_settings( 'sbanner_right_image', 'p_absolute bl_right', 'leaf' );
		Docy_helper()->image_from_settings( 'sbanner_bg_image', 'p_absolute star', 'leaf' );
		Docy_helper()->image_from_settings( 'sbanner_shape1', 'p_absolute wave_shap_one', 'Docy banner shape 01' );
		Docy_helper()->image_from_settings( 'sbanner_shape2', 'p_absolute wave_shap_two', 'Docy banner shape 02' );
		Docy_helper()->image_from_settings( 'sbanner_man_image', 'p_absolute one wow fadeInRight', 'Man illustration' );
		Docy_helper()->image_from_settings( 'sbanner_flower_image', 'p_absolute two wow fadeInUp', 'Flower illustration' );
		?>
        <div class="container custom_container">
            <div class="doc_banner_content">
                <?php
                // Title and subtitle for search banner
                include('title.php');

                if ( docy_opt( 'sbnr_search_fieldset', '1', 'is_sbnr_search' ) == '1' ) : ?>
                    <form action="<?php echo esc_url( home_url( '/' ) ) ?>" role="search" method="get"
                          class="banner_search_form <?php echo esc_attr( $is_focus_search ) ?>">
                        <div class="input-group">
                            <?php if ( class_exists( 'bbpress' ) ) : ?>
                                <?php if ( bbp_is_search_results() ) : ?>
                                    <input type="hidden" name="action" value="bbp-search-request"/>
                                <?php endif; ?>
                            <?php endif; ?>

                            <input type="search" id="searchInput" class="form-control" name="<?php echo esc_attr( $search_name ) ?>" placeholder="<?php echo esc_attr( $placeholder ) ?>">

                            <?php include( 'search-spinner.php' ); ?>

                            <?php if ( defined( 'ICL_LANGUAGE_CODE' ) ) : ?>
                                <input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>"/>
                            <?php endif; ?>

                            <?php if ( is_singular( 'docs' ) || is_post_type_archive( 'docs' ) ) : ?>
                                <input type="hidden" name="post_type" value="docs"/>
                            <?php endif; ?>

                            <div class="input-group-append">
                                <button type="submit"><i class="icon_search"></i></button>
                            </div>
                        </div>

                        <?php
                        include( 'ajax-search-results.php' );
                        include( 'keywords.php' );
                        ?>
                    </form>
                <?php endif;?>
            </div>
        </div>
    </section>

<?php
// Breadcrumb removed per request
// include( 'breadcrumb.php' );
include( 'featured-video.php' );