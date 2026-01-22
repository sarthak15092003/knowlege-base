<?php
$opt = get_option('docy_opt');
$thumb_size = is_active_sidebar( 'sidebar_widgets' ) ? 'docy_410x220' : 'full';
$is_post_meta           = $opt['is_post_meta'] ?? '1';
$is_post_date           = $opt['is_post_date'] ?? '1';
$is_post_reading_time   = $opt['is_post_reading_time'] ?? '1';
$is_post_cat            = $opt['is_post_cat'] ?? '1';
$is_post_author         = $opt['is_post_author'] ?? '1';
$post_author_id         = get_post_field( 'post_author', get_the_ID() );
$video_url = docy_meta('video_url');
$blog_column = docy_opt('blog_column', 3);

if ( !empty($_GET['blog_layout']) && $_GET['blog_layout'] == 'blog_category' ) {
    $blog_column = '4';
}
?>

<div class="col-lg-<?php echo esc_attr( $blog_column ) ?> col-sm-6">
    <div class="blog_grid_post wow fadeInUp">
        <div class="video_post">
            <?php
            the_post_thumbnail($thumb_size);
            if ( !empty($video_url) ) :
                wp_enqueue_style('magnific-popup');
                wp_enqueue_script('magnific-popup');
                <a class="popup-youtube video_icon" href="<?php echo esc_url($video_url) ?>"><i class="arrow_triangle-right"></i></a>
            <?php
            endif;
            ?>
        </div>
        <div class="grid_post_content">
            <?php if ( $is_post_meta == '1' ) : ?>
                <div class="post_tag">
                    <?php
                    if ( $is_post_reading_time == '1' ) {
                        ?>
                        <span class="meta"> <?php docy_reading_time(get_the_ID()); ?> </span>
                        <?php
                    }
                    ?>
                </div>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>">
                <h4 class="b_title"> <?php Docy_helper()->limit_latter(get_the_title(), docy_opt('post_title_length', 45), ''); ?> </h4>
            <?php
            echo strip_shortcodes( Docy_helper()->excerpt( 'blog_excerpt', false ) );
            if ( $is_post_author == '1' ) {
                if ( $is_post_meta == '1' ) {
                    ?>
                    <div class="media d-flex post_author">
                        <div class="round_img">
                            <?php Docy_helper()->post_author_avatar(); ?>
                        </div>
                        <div class="media-body author_text">
                            <a href="<?php echo get_author_posts_url($post_author_id) ?>">
                                <?php echo get_the_author_meta('display_name') ?>
                            </a>
                            <?php
                            if ( $is_post_date == '1' ) {
                                if ( $is_post_meta == '1' ) { ?>
                                    <div class="date"> <?php the_time(get_option('date_format')); ?> </div>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>