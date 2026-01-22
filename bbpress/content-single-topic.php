<?php
/**
 * Single Topic Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

$main_column = is_active_sidebar('forum_archive_sidebar') ? '8' : '12';
?>

<section <?php bbp_topic_class(get_the_ID(), array('forum-single-content')); ?>>
    <div class="row">
        <div class="col-lg-<?php echo esc_attr($main_column) ?>">

            <?php
            if ( post_password_required() ) :
                bbp_get_template_part( 'form', 'protected' );
            else :
                bbp_get_template_part( 'content', 'single-topic-lead' );
                ?>

                <?php if ( bbp_has_replies() ) : ?>

                    <div id="topic-<?php bbp_topic_id(); ?>-replies" class="all-answers">
                        <div class="top-bar d-flex justify-content-between mb-4">
                            <h5 class="title"> <?php esc_html_e('All Replies', 'docy'); ?> </h5>
                            <!--<div class="sort">
                                <select class="custom-select" id="sort-replies">
                                    <option selected>Sort By</option>
                                    <option value="1">ASC</option>
                                    <option value="2">Desc</option>
                                    <option value="3">Vote</option>
                                </select>
                            </div>-->
                        </div>

                        <?php bbp_get_template_part( 'pagination', 'replies' ); ?>

                        <?php bbp_get_template_part( 'loop', 'replies' ); ?>

                        <?php bbp_get_template_part( 'pagination', 'replies' ); ?>
                    </div>

                <?php endif; ?>

                <?php bbp_get_template_part( 'form', 'reply' ); ?>

                <?php bbp_get_template_part( 'alert', 'topic-lock' ); ?>

                <?php do_action( 'bbp_template_after_single_topic' ); ?>

                <?php
            endif;
            ?>
        </div>
        <!-- /.col-lg-8 -->

        <?php get_sidebar('forum'); ?>
        <!-- /.col-lg-4 -->
    </div>
</section>