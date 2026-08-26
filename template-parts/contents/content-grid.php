<?php
// Check if we're on a category page with sidebar
$is_ajax_cat = isset($_POST['cat_slug']) || isset($_GET['cat_slug']);
$has_cat_sidebar = (isset($_GET['cat']) && !empty($_GET['cat'])) || is_category() || $is_ajax_cat;

if ($has_cat_sidebar) {
    $is_restricted = !is_user_logged_in() && function_exists('cmg_is_post_restricted_to_logged_in') && cmg_is_post_restricted_to_logged_in(get_the_ID());
    // Simple card layout for category pages with sidebar
    ?>
    <div class="col-12 mb-4">
        <div class="simple-post-card <?php echo $is_restricted ? 'is-restricted-card' : ''; ?>" <?php echo $is_restricted ? 'data-restricted="true"' : ''; ?>>
            <div class="post-card-content">
                <div class="post-card-meta">
                    <?php 
                    $categories = get_the_category();
                    if (!empty($categories)) {
                        $category = reset($categories);
                        ?>
                        <span class="post-category">
                            <span class="category-dot" aria-hidden="true"></span>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>">
                                <?php echo esc_html($category->name); ?>
                            </a>
                        </span>
                        <?php
                    }
                    ?>
                    <span class="post-date">
                        <i class="fa fa-calendar"></i> <?php the_time(get_option('date_format')); ?>
                    </span>
                    <?php if ($is_restricted): ?>
                    <span class="badge" style="background: #eff6ff; color: #2563eb; font-size: 11px; padding: 4px 8px; border-radius: 6px; font-weight: 600; margin-left: 6px;">
                        <i class="fa fa-lock me-1"></i> Members Only
                    </span>
                    <?php endif; ?>
                </div>
                <h4 class="post-card-title">
                    <a href="<?php the_permalink(); ?>" <?php echo $is_restricted ? 'data-restricted="true" class="is-restricted-link"' : ''; ?>><?php the_title(); ?></a>
                </h4>
                <p class="post-card-excerpt">
                    <?php 
                    if ($is_restricted) {
                        echo 'Unlock full access to this premium guide and resource by upgrading to a paid account.';
                    } else {
                        $excerpt = get_the_excerpt();
                        if (empty($excerpt)) {
                            $excerpt = wp_strip_all_tags(get_post_field('post_content', get_the_ID()));
                        }
                        echo esc_html(wp_trim_words($excerpt, 20, '…'));
                    }
                    ?>
                </p>
                <a href="<?php the_permalink(); ?>" class="post-card-link <?php echo $is_restricted ? 'is-restricted-link' : ''; ?>" <?php echo $is_restricted ? 'data-restricted="true"' : ''; ?>>
                    <?php echo $is_restricted ? 'Unlock Access →' : 'Continue Reading →'; ?>
                </a>
            </div>
        </div>
    </div>
    <?php
} else {
    // Original grid layout for other pages
    $opt = get_option('docy_opt');
    $thumb_size = is_active_sidebar( 'sidebar_widgets' ) ? 'docy_410x220' : 'full';
    $is_post_meta           = $opt['is_post_meta'] ?? '1';
    $is_post_date           = $opt['is_post_date'] ?? '1';
    $is_post_reading_time   = $opt['is_post_reading_time'] ?? '1';
    $is_post_cat            = $opt['is_post_cat'] ?? '1';
    $is_post_author         = $opt['is_post_author'] ?? '1';
    $post_author_id         = get_post_field( 'post_author', get_the_ID() );

    $blog_column = docy_opt('blog_column', 3);

    if ( !empty($_GET['blog_layout']) && $_GET['blog_layout'] == 'blog_category' ) {
        $blog_column = '6';
    }
    ?>
    <div class="col-lg-<?php echo esc_attr( $blog_column ) ?> col-sm-6">
        <div class="blog_grid_post wow fadeInUp">
            <?php
            if ( !is_search() ) {
                the_post_thumbnail($thumb_size);
            }
            ?>
            <div class="grid_post_content">
                <?php if ( $is_post_meta == '1' ) : ?>
                    <div class="post_tag">
                        <?php
                        // Add category
                        $categories = get_the_category();
                        if (!empty($categories)) {
                            $category = reset($categories);
                            ?>
                            <a href="<?php echo esc_url(get_category_link($category->term_id)); ?>" class="meta-item">
                                <span class="category-dot" aria-hidden="true"></span>
                                <?php echo esc_html($category->name); ?>
                            </a>
                            <?php
                        }
                        
                        if ( $is_post_date == '1' ) {
                            ?>
                            <a href="<?php Docy_helper()->day_link(); ?>" class="meta-item">
                                <i class="fa fa-calendar"></i>
                                <?php the_time(get_option('date_format')); ?>
                            </a>
                            <?php
                        }
                        ?>
                    </div>
                <?php endif; ?>
                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute() ?>">
                    <h4 class="b_title"> <?php Docy_helper()->limit_latter(get_the_title(), docy_opt('post_title_length', 45), ''); ?> </h4>
                </a>
                <?php
                $raw_excerpt = get_the_excerpt();
                if ( empty( $raw_excerpt ) ) {
                    $raw_excerpt = wp_strip_all_tags( get_the_content() );
                }
                echo esc_html( wp_trim_words( $raw_excerpt, 12, '…' ) );
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
    <?php
}
?>