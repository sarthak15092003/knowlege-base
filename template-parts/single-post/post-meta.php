<?php
$opt = get_option('docy_opt' );
$post_meta = $opt['is_single_post_meta'] ?? '1';
if ( false ) :
    ?>
    <div class="single_post_author d-flex justify-content-center">
        <div class="text post_tag">
            <?php
            $post_date = $opt['is_single_post_date'] ?? '';
            if ( $post_date == '1' ) :
                ?>
                <a href="<?php Docy_helper()->day_link(); ?>" class="meta-item date" title="<?php esc_attr_e( 'First published on ', 'docy'); the_time(get_option('date_format')); ?>">
                    <i class="fa fa-calendar"></i>
	                <?php esc_html_e( 'Updated on ', 'docy' ); the_modified_time( get_option( 'date_format' ) ); ?>
                </a>
                <?php
            endif;

            $reading_time = $opt['is_single_reading_time'] ?? '';
            if ( $reading_time == '1' ) :
                ?>
                <div class="meta-item read-time" title="<?php docy_reading_time(get_the_ID()); esc_html_e(' to read this post', 'docy'); ?>">
                    <i class="fa fa-clock-o"></i>
                    <?php docy_reading_time(get_the_ID()); ?>
                </div>
                <?php
            endif;

            $is_single_cats = $opt['is_single_cats'] ?? '';
            if ( $is_single_cats == '1' ) :
                ?>
                <div class="cats meta-item" title="<?php esc_attr_e( 'Categories', 'docy' ) ?>">
                    <i class="fa fa-tags"></i>
                    <?php the_category(','); ?>
                </div>
                <?php
            endif;
            
            if ( function_exists('docy_post_share') ) {
                docy_post_share();
            }
            
           docy_post_views(get_the_ID());
            ?>
            <div class="views meta-item">
                <i class="fa fa-eye"></i>
                <span> <?php echo get_post_meta( get_the_ID(), 'docy_post_views_count', true ) . esc_html__( ' Views', 'docy' ); ?> </span>
            </div>
                
        </div>
    </div>
    <div class="docy-link-copied-wrap"></div>
<?php
endif;