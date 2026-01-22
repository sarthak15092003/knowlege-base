<?php
$thumb_size = is_active_sidebar('sidebar_widgets') ? 'docy_844x400' : 'full';
$opt = get_option('docy_opt');
$blog_continue_read = !empty($opt[ 'blog_continue_read' ]) ? $opt[ 'blog_continue_read' ] : esc_html__('Continue Reading', 'docy');
$is_post_meta = $opt[ 'is_post_meta' ] ?? '1';
$is_post_author = $opt[ 'is_post_author' ] ?? '1';
$post_author_id = get_post_field('post_author', get_the_ID());
$video_url = docy_meta('video_url');
?>
<div <?php post_class('bs-sm h:bs-lg blog_classic_item'); ?>>
    <div class="video_post">
        <?php
        the_post_thumbnail($thumb_size);
        if ( !empty($video_url) ) :
            wp_enqueue_style('magnific-popup');
            wp_enqueue_script('magnific-popup');
            ?>
            <a class="popup-youtube video_icon" href="<?php echo esc_url($video_url) ?>"><i class="arrow_triangle-right"></i></a>
            <?php
        endif;
        ?>
    </div>
    <div class="b_top_post_content">

        <?php include('post-tag.php'); ?>

        <div class="post_icon">
            <?php if ( docy_opt('is_post_format_icon') == '1' ) : ?>
                <i class="<?php echo esc_attr($opt['b_video_icon' ]) ?>"></i>
            <?php endif; ?>
            <a href="<?php the_permalink(); ?>">
                <h3 class="title">
                    <?php the_title() ?>
                </h3>
            </a>
        </div>

        <?php echo strip_shortcodes(Docy_helper()->excerpt('blog_excerpt', false)); ?>

        <div class="d-flex justify-content-between p_bottom">
            <a href="<?php the_permalink(); ?>" class="learn_btn">
                <?php echo esc_html($blog_continue_read) ?>
                <i class="<?php docy_arrow_left_right() ?>"></i>
            </a>
            <?php
            if ($is_post_author == '1') {
                if ($is_post_meta == '1') {
                    ?>
                    <div class="media d-flex post_author">
                        <div class="round_img">
                            <?php Docy_helper()->post_author_avatar(); ?>
                        </div>
                        <div class="media-body author_text">
                            <a href="<?php echo get_author_posts_url($post_author_id) ?>">
                                <?php echo get_the_author_meta('display_name') ?>
                            </a>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
