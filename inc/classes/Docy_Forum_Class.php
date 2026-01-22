<?php
/**
 * Docy theme helper functions and resources
 */
if ( !class_exists( 'Docy_Forum_Class' ) ) {
    class Docy_Forum_Class {
        /**
         * Hold an instance of Docy_Forum_Class class.
         * @var Docy_Forum_Class
         */
        protected static $instance = null;

        /**
         * Main Docy_Forum_Class instance.
         * @return Docy_Forum_Class - Main instance.
         */
        public static function instance() {

            if ( null == self::$instance ) {
                self::$instance = new Docy_Forum_Class();
            }

            return self::$instance;
        }
        /**
         * Adds a reference of this object to $instance, populates default strings,
         * @see __construct
         */
        public function __construct() {

        }

        /**
         * Authors Name
         *
         * @return string
         */
        public static function forum_topics_authors() {
            global $wpdb;
            $d_users = [];
            $docyusers = get_users();

            if ( $docyusers ) {
                foreach ( $docyusers as $docyuser ) {
                    $post_count = count_user_posts( $docyuser->ID, 'topic' );
                    $d_users[$docyuser->ID] = $post_count;
                }
                arsort( $d_users );
                $i = 0;
                foreach ( $d_users as $key => $value ) {
                    $i++;

                    $user = get_userdata( $key );
                    $parent_id = get_queried_object_id();
                    //$users_post_count = count_user_posts( $user->ID, 'topic' );
                    $users_post_count = $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->posts WHERE post_author = '" . $user->ID . "' AND post_type = 'topic' AND post_status = 'publish' AND post_parent = '" . $parent_id . "' " );
                    $author_posts_url = get_author_posts_url( $key );
                    $post_count = $users_post_count;
                    if ( $post_count > 0 ) {
	                    echo '<div class="userlist">';
	                    echo '<a class="dropdown-item current-user docy-data data-auth" data-nonce="' . wp_create_nonce( 'docy-nonce' ) . '" data-rel="' . $user->display_name . '" data-type="author" data-count="' . $post_count . '" data-parent="' . get_queried_object_id() . '" data-id="' . intval( $user->ID ) . '" href="#' . $user->display_name . '">
                                <img src="' . get_avatar_url( $user->ID ) . '" alt="' . $user->display_name . '">' . $user->display_name . '<span class="count">' . $post_count . '</span>
                              </a>';
	                    echo '</div>';
                    }
                }
            }
        }

        /**
         * Tag Name
         *
         * @return string
         */
        public static function forum_topics_tags() {
            $args = [
                'post_type'           => 'topic',
                'post_parent'         => get_queried_object_id(),
                'post_status'         => 'publish',
                'order'               => 'ASC',
                'orderby'             => 'ID',
                'posts_per_page'      => -1,
            ];
            $docytags = new WP_Query( $args );

            if ( $docytags->have_posts() ):
	            $i=1;
                while ( $docytags->have_posts() ): $docytags->the_post();
                    global $post;
                    $get_tags = get_the_terms( get_the_ID(), 'topic-tag' );
	                if ( $get_tags ) {
		                foreach ( $get_tags as $tag ) {
                            $post_count = $tag->count;
                            echo '<div class="tagList" id="'.$tag->slug.'">';
                            echo '<a class="dropdown-item docy-data data-tag" data-parent="'.get_queried_object_id().'" data-nonce="'.wp_create_nonce('docy-nonce').'" data-rel="'.$tag->slug.'" data-type="tag" data-count="' . $post_count . '" data-id="' . intval( $tag->term_id ) . '" href="#' . esc_attr( $tag->slug ) . '"><span class="color-purple"></span>' . $tag->name . '</a>';
                            echo '</div>';
		                }
	                }
                    $i++;
                endwhile;

            endif;
        }

        /**
         * Tag Name
         *
         * @return void
         */
        public static function forum_topics_open_close(): void
        {
            $is_queried_obj = is_singular('forum') ? get_queried_object_id() : false;
            $user_id = is_singular('forum') ? false : bbp_get_displayed_user_field('ID');

            // Get the active tab from the URL or default to 'open'
            $active_tab = isset($_GET['tab']) && in_array($_GET['tab'], ['open', 'closed']) ? sanitize_text_field($_GET['tab']) : 'open';

            // Query for Open Topics Count
            $open_args = [
                'post_type'      => 'topic',
                'post_parent'    => $is_queried_obj,
                'post_status'    => 'publish',
                'posts_per_page' => -1, // No pagination for count
            ];
            $open_query = new WP_Query($open_args);
            $open_count = $open_query->found_posts;

            // Query for Closed Topics Count
            $closed_args = [
                'post_type'      => 'topic',
                'post_parent'    => $is_queried_obj,
                'post_status'    => 'closed',
                'posts_per_page' => -1,
            ];
            $closed_query = new WP_Query($closed_args);
            $closed_count = $closed_query->found_posts;

            if ( in_array('bbp-user-page', get_body_class()) ) :
                ?>
                <ul class="support-total-info">
                    <li class="open-ticket">
                        <?php esc_html_e( 'TOPIC', 'docy' ); ?>
                    </li>
                </ul>
                <ul class="category-menu">
                    <li> <?php esc_html_e( 'Comments', 'docy' ); ?> </li>
                    <li> <?php esc_html_e( 'Favorites', 'docy' ); ?> </li>
                </ul>
            <?php
            else :
                ?>
                <ul class="support-total-info">
                    <li class="open-ticket <?php echo $active_tab === 'open' ? 'active' : ''; ?>">
                        <i class="icon_info_alt"></i>
                        <a href="?tab=open"
                           class="open-data"
                           data-nonce="<?php echo wp_create_nonce( 'docy-nonce' );?>"
                           data-type="open"
                           data-userid="<?php echo esc_attr($user_id); ?>"
                           data-parent="<?php echo esc_attr($is_queried_obj); ?>">
                            <?php echo esc_html($open_count) . esc_html__(' Open', 'docy'); ?>
                        </a>
                    </li>
                    <li class="close-ticket <?php echo $active_tab === 'closed' ? 'active' : ''; ?>">
                        <i class="icon_check"></i>
                        <a href="?tab=closed"
                           class="open-data"
                           data-nonce="<?php echo wp_create_nonce( 'docy-nonce' );?>"
                           data-type="closed"
                           data-parent="<?php echo esc_attr($is_queried_obj); ?>"
                           data-userid="<?php echo esc_attr($user_id); ?>">
                            <?php echo esc_html($closed_count) . esc_html__(' Closed', 'docy'); ?>
                        </a>
                    </li>

                    <li class="close-ticket reset-btn reset-none <?php echo $active_tab === 'open' ? 'active' : ''; ?>">
                        <i class="icon_refresh"></i>
                        <a href="<?php echo esc_url( remove_query_arg( array('tab', 'paged'), get_permalink() ) ); ?>?tab=open"
                           class="loading open-data"
                           data-nonce="<?php echo wp_create_nonce( 'docy-nonce' );?>"
                           data-type="open"
                           data-parent="<?php echo esc_attr($is_queried_obj); ?>"
                           data-userid="<?php echo esc_attr($user_id); ?>">
                            <?php esc_html_e( 'Reset', 'docy' ); ?>
                        </a>
                    </li>
                </ul>
            <?php
            endif;
        }

        function topic_badges() {
            if( bbp_is_topic_sticky(get_the_ID()) ) { ?>
                <span class="badge badge-success">
            <?php esc_html_e( 'Sticky', 'docy' ); ?>
        </span>
            <?php }
            if (bbp_is_topic_closed(get_the_ID())) { ?>
                <span class="badge badge-danger">
            <?php esc_html_e( 'Closed', 'docy' ); ?>
        </span>
            <?php }
        }
    }
}
/**
 * Instance of Docy_Forum_Class class
 */

function Docy_Forum() {
    return Docy_Forum_Class::instance();
}