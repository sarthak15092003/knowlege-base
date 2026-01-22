<?php
/**
 * Topics Loop
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

do_action( 'bbp_template_before_topics_loop' );

bbp_get_template_part('loop-topics-head');
?>

<div class="load-forum">
<?php

$paged = get_query_var('paged') ? get_query_var('paged') : 1;
$is_queried_obj = is_singular('forum') ? get_queried_object_id() : false;
$user_id = is_singular('forum') ? false : bbp_get_displayed_user_field('ID');
$topic_status = isset($_GET['tab']) && $_GET['tab'] === 'closed' ? 'closed' : 'publish';

$args = [
    'post_type'      => 'topic',
    'post_parent'    => $is_queried_obj,
    'post_status'    => $topic_status,
    'posts_per_page' => get_option('_bbp_topics_per_page', 10),
    'order'          => 'DESC',
    'orderby'        => 'ID',
    'paged'          => $paged,
    'author'         => $user_id
];

// If is topic tag page
if ( is_tax('topic-tag') ) {
    $args['tax_query'] = [
        [
            'taxonomy' => 'topic-tag',
            'field'    => 'slug',
            'terms'    => get_queried_object()->slug,
        ]
    ];
}

$query = new WP_Query( $args );

if ( $query->have_posts() && !in_array('bbp-user-page', get_body_class()) ) :
    echo '<div class="community-posts-wrapper bb-radius">';
        while ( $query->have_posts() ) : $query->the_post();
            global $post;
            $parent_post_id = get_queried_object_id();
            $favorite = get_post_meta( get_the_ID(), '_bbp_favorite', true );
            $favorite_count = !empty( $favorite ) ? $favorite[0] : '0';
            ?>
            <div class="community-post style-two">
                <div class="post-content">
                    <div class="author-avatar">
                        <?php
                        echo bbp_get_topic_author_link(
                            array(
                                'post_id' 	=> get_the_ID(),
                                'type' 		=> 'avatar',
                                'size'      => 40
                            )
                        );
                        ?>
                    </div>
                    <div class="entry-content">
                        <?php
                        the_title( sprintf( '<a href="%s" rel="bookmark"> <h3 class="post-title">', get_permalink() ), '</h3></a>' );
                        do_action( 'bbpc-resolved-topics' );
                        ?>

                        <?php do_action( 'bbp_theme_after_topic_title' ); ?>
                        <ul class="meta">
                            <li>
                                <?php echo get_the_post_thumbnail(bbp_get_topic_forum_id(), array(40, 40)); ?>
                                <a href="<?php echo get_permalink( bbp_get_topic_forum_id() ); ?>">
                                    <?php echo get_the_title( bbp_get_topic_forum_id() ); ?>
                                </a>
                            </li>
                            <li><i class="icon_clock_alt"></i> <?php bbp_topic_post_date(get_the_ID(), true); ?> </li>
                        </ul>
                    </div>
                </div>
                <div class="post-meta-wrapper">
                    <ul class="post-meta-info">
                        <li><a href="<?php echo esc_url( get_permalink() ); ?>">
                            <i class="icon_chat_alt"></i> <?php docy_topic_reply_count(); ?> </a>
                        </li>
                        <li><a href="<?php echo esc_url( get_permalink() ); ?>">
                            <i class="icon_star"></i> <?php echo esc_html( $favorite_count ); ?> </a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
        endwhile;
        wp_reset_postdata();
    echo '</div>';

    ?>
    <div class="pagination-wrapper">
        <div class="view-post-of">
            <p><?php bbp_forum_pagination_count(); ?></p>
        </div>
        <div class="bbp-pagination">
            <?php
            $big = 999999999;
            $current_url = add_query_arg( 'paged', '', get_pagenum_link() );
            $current_url = remove_query_arg( 'tab', $current_url );

            echo paginate_links( array(
                'base' => str_replace( $big, '%#%', esc_url( add_query_arg( 'paged', $big, $current_url ) ) ),
                'format' => '?paged=%#%',
                'current' => max( 1, get_query_var('paged') ),
                'total' => $query->max_num_pages,
                'prev_text' => '<i class="arrow_carrot-left"></i>',
                'next_text' => '<i class="arrow_carrot-right"></i>',
                'add_args' => array('tab' => $topic_status === 'publish' ? '' : 'closed')
            ) );
            ?>
        </div>
    </div>
    <?php

elseif ( in_array('bbp-user-page', get_body_class()) ):
    ?>
    <div id="bbp-topic-<?php bbp_topic_id(); ?>" class="community-posts-wrapper bb-radius">
        <?php
        while ( bbp_topics() ) : bbp_the_topic();
            bbp_get_template_part( 'loop', 'single-topic' );
        endwhile;
        ?>
    </div>
    <?php
else:
    ?> <div class="community-posts-wrapper bb-radius"> <?php
    echo '<h3 class="error p-3 pt-4">' . esc_html__( 'Oops! No Post Found', 'docy' ) . '</h3>';
    echo '</div>';
endif;

echo '</div>';

