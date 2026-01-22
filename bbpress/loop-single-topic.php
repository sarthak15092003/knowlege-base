<?php
/**
 * Topics Loop - Single
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
$favoriters = bbp_get_topic_favoriters();
$favorite_count = !empty($favoriters) ? $favoriters[0] : '0';
?>

<div class="community-post style-two">
    <div class="post-content">
        <div class="entry-content">
            <a href="<?php bbp_topic_permalink(); ?>">
                <h3 class="post-title"> <?php bbp_topic_title(); ?> </h3>
            </a>
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
            <li>
                <a href="<?php bbp_topic_permalink(); ?>">
                    <i class="icon_chat_alt"></i>
                    <?php bbp_show_lead_topic() ? bbp_topic_reply_count(get_the_ID()) : bbp_topic_post_count(get_the_ID()); ?>
                </a>
            </li>
            <li>
                <a href="<?php bbp_topic_permalink(); ?>">
                    <i class="icon_star_alt"></i> <?php echo esc_html($favorite_count) ?>
                </a>
            </li>
        </ul>
    </div>
</div>