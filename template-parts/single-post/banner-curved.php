<?php
$opt = get_option( 'docy_opt' );
wp_enqueue_script( 'anchor' );
$creative_video = has_post_format( 'video' ) ? 'shadow-sm' : 'toc-creative-default';
$video_url      = docy_meta('video_url');
?>

<section class="tip_banner_area toc-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="toc-wrapper-banner">
                    <nav aria-label="breadcrumb">
						<?php docy_post_breadcrumbs(); ?>
                    </nav>
					<?php
					the_title( '<h1 class="banner_title">', '</h1>' );
					echo strip_shortcodes( Docy_helper()->excerpt( 'blog_excerpt', false ) );
					?>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="blog_classic_item toc-creative-media <?php echo esc_attr( $creative_video ); ?>">
                    <?php 
                    if ( has_post_format( 'video' ) && ! empty( $video_url ) ) :
                        // Thumbnail image in background CSS
                        $thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'full' );
                        $thumbnail_url = has_post_thumbnail() ? "style='background: url($thumbnail_url); background-size: cover;'" : '';
                        wp_enqueue_style( 'magnific-popup' );
                        wp_enqueue_script( 'magnific-popup' );
                        ?>
                        <div class="video_post" <?php echo $thumbnail_url; ?>>
                            <a class="popup-youtube video_icon" href="<?php echo esc_url( $video_url ) ?>">
                                <i class="arrow_triangle-right"></i>
                            </a>
                        </div>
                        <?php 
                    else :
                        if ( has_post_thumbnail() ){
                            the_post_thumbnail('docy_570x345');
                        }
                    endif;
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="toc-banner-overlay">
        <img src="<?php echo DOCY_DIR_IMG . '/banner-blog/toc-overlay.svg' ?>" alt="<?php esc_attr_e( 'Overlay Image', 'docy' ) ?>" class="overlay-shape-light"/>
        <img src="<?php echo DOCY_DIR_IMG . '/banner-blog/toc-overlay-dark.svg' ?>" alt="<?php esc_attr_e( 'Overlay Image', 'docy' ) ?>" class="overlay-shape-dark"/>
    </div>
</section>