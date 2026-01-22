<?php
$thumb_size = is_active_sidebar( 'sidebar_widgets' ) ? 'docy_670x450' : 'full';
$opt = get_option('docy_opt');
$blog_continue_read = !empty($opt['blog_continue_read']) ? $opt['blog_continue_read'] : esc_html__( 'Continue Reading', 'docy' );
$is_post_meta = $opt['is_post_meta'] ?? '1';
$is_post_date = $opt['is_post_date'] ?? '1';
$is_post_reading_time = $opt['is_post_reading_time'] ?? '1';
$is_post_cat = $opt['is_post_cat'] ?? '1';
$is_post_author = $opt['is_post_author'] ?? '1';
$blog_column = !empty($opt['blog_column']) ? $opt['blog_column'] : '6';

$post_author_id = get_post_field( 'post_author', get_the_ID() );
?>

<?php

if ( is_sticky() ) {
    ?>
    <section class="blog_top_post_area sec_pad bg_color">
        <div class="container">
            <div class="row blog_top_post flex-row-reverse">
                <div class="col-lg-7 p_top_img">
                    <?php
                    the_post_thumbnail( $thumb_size, array( 'class' => 'p_img' ) );
                    ?>
                </div>
                <div class="col-lg-5 p-0">
                    <div class="b_top_post_content">
                        <div class="post_tag">
                            <?php
                            // Category display removed - no longer showing category names
                            if ( $is_post_date == '1' ) {
                                if ( $is_post_meta == '1' ) { ?>
                                    <a href="<?php Docy_helper()->day_link(); ?>" class="meta-item">
                                        <i class="fa fa-calendar"></i>
                                        <?php the_time(get_option('date_format')); ?>
                                    </a>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                        <a href="<?php the_permalink(); ?>">
                            <h3> <?php the_title() ?> </h3>
                        </a>
                        <?php 
                        // Single-line friendly excerpt without cutting words
                        $raw_excerpt = get_the_excerpt();
                        if ( empty( $raw_excerpt ) ) {
                            $raw_excerpt = wp_strip_all_tags( get_the_content() );
                        }
                        echo esc_html( wp_trim_words( $raw_excerpt, 12, '…' ) );
                        ?>
                        <a href="<?php the_permalink(); ?>" class="learn_btn"><?php echo esc_html($blog_continue_read) ?><i class="<?php docy_arrow_left_right() ?>"></i></a>
                        <?php
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
                                                <div class="date"><?php the_time(get_option('date_format')); ?></div>
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
        </div>
    </section>
    <?php
}