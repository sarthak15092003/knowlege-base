<?php
/**
 * Single Reply Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$main_column = is_active_sidebar('forum_archive_sidebar') ? '8' : '12';
?>

<div class="row">
    <div class="col-lg-<?php echo esc_attr($main_column) ?>">
        <div id="bbpress-forums" class="bbpress-wrapper main-post">
            <?php do_action( 'bbp_template_before_single_reply' ); ?>

            <?php if ( post_password_required() ) : ?>

                <?php bbp_get_template_part( 'form', 'protected' ); ?>

            <?php else : ?>

                <?php bbp_get_template_part( 'loop', 'single-reply' ); ?>

            <?php endif; ?>

            <?php do_action( 'bbp_template_after_single_reply' ); ?>

        </div>
    </div>
    <?php get_sidebar('forum'); ?>
</div>
