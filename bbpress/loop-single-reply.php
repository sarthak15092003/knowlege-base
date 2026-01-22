<?php
/**
 * Replies Loop - Single Reply
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>

<div id="post-<?php bbp_reply_id(); ?>" <?php bbp_reply_class( get_the_ID(), array( 'forum-comment' ) ); ?>>
	<?php do_action( 'bbp_theme_before_reply_author_details' ); ?>
    <div class="forum-post-top">
		<div class="d-flex bbp-author">
			<?php bbp_reply_author_link( array( 'sep' => '', 'show_role' => false, 'type' => 'avatar', 'size' => 30 ) ); ?>
            <div class="forum-post-author topic-author">
				<?php bbp_reply_author_link( array( 'sep' => '', 'show_role' => false, 'type' => 'name' ) ); ?>
                <div class="author-badge badge <?php echo sanitize_title(docy_get_bbp_user_role()) ?>">
					<?php docy_bbp_user_role_icon(); ?>
                    <span> <?php echo docy_get_bbp_user_role() ?> </span>
                </div>
            </div>
        </div>
        <div class="forum-author-meta meta">
            <a class="date-meta" href="<?php the_permalink(); ?>" title="<?php bbp_topic_post_date(); ?>">
			    <?php bbp_reply_post_date(get_the_ID(), true); ?>
            </a>
        </div>
    </div>
    <div class="comment-content">
		<?php do_action( 'bbp_theme_before_reply_content' ); ?>
		<?php echo do_shortcode( bbp_get_reply_content( get_the_ID() ) ); ?>
		<?php do_action( 'bbp_theme_after_reply_content' ); ?>
		<?php if ( is_user_logged_in() ) : ?>
            <ul class="list-unstyled d-inline-flex bbp-admin-links">
				<?php
				$admin_link_args = array( 'before' => '<li>', 'after' => '</li>', 'sep' => '</li><li>' );
				bbp_reply_admin_links( $admin_link_args );
				?>
            </ul>
		<?php endif; ?>
    </div>
</div>