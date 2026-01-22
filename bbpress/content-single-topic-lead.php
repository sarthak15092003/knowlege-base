<?php

/**
 * Single Topic Lead Content Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly

defined( 'ABSPATH' ) || exit;
do_action( 'bbp_template_before_lead_topic' );

$opt = get_option('docy_opt');
$topic_title_prefix = $opt['topic_title_prefix'] ?? 'Q:';
?>
    <div class="main-post">
        <!-- Forum post top area -->
        <div class="forum-post-top">
            <div class="d-flex bbp-author">
                <div class="author-avatar bbp-author-link">
                    <?php 
                    echo bbp_get_topic_author_link( 
                        array( 
                            'post_id' 	=> get_the_ID(), 
                            'size' 		=> 40, 
                            'type' 		=> 'avatar' 
                        )
                    );
                    ?>
                </div>
                <div class="forum-post-author">
                    <div class="topic-author d-flex mb-1">
                        <?php 
                        echo bbp_get_topic_author_link( 
                            array( 
                                'post_id' 	=> get_the_ID(), 
                                'type' 		=> 'name' 
                            )
                        );
                        ?>
                        <div class="author-badge badge <?php echo sanitize_title(docy_get_bbp_user_role()) ?>">
                            <?php docy_bbp_user_role_icon(); ?>
                            <span> <?php echo docy_get_bbp_user_role() ?> </span>
                        </div>
                    </div>
                    <div class="forum-author-meta meta">
                        <a href="<?php the_permalink(); ?>" title="<?php bbp_topic_post_date(); ?>">
                            <?php bbp_topic_post_date(get_the_ID(), true); ?>
                        </a>
                        <span class="dot"></span>
                        <a href="#topic-<?php bbp_topic_id(); ?>-replies" class="replies-count">
                            <?php docy_topic_reply_count(); esc_html_e(' Replies'); ?>
                        </a>
                    </div>
                </div>
            </div>
            <div class="action-button-container">
		        <?php
                if ( is_user_logged_in() ) :
                    bbp_topic_subscription_link( array(
                        'before'      => '',
                        'after'       => '',
                        'unsubscribe' => esc_html__( 'Subscribed', 'docy' ),
                    ));
                    else:
                        if ( get_option('_bbp_enable_subscriptions') == 1 && docy_opt('is_topic_subscription') == 1 ) :
                        
                            if (  docy_opt('login_url_type') == 'default' ) {
                                $login_url = wp_login_url( get_permalink() );
                            } else {
                                $pageId = docy_opt('login_page');
                                $login_url = get_permalink( $pageId );
                            }
                            ?>
                            <span id="subscription-toggle">
                                <a class="subscription-login-btn">
                                    <?php echo docy_opt('logout_subscription_btn'); ?>
                                </a>
                            </span>
                            <span class="subscription-login-modal">
                                <span class="subscription-login-modal-inner">
                                    <span class="subscription-login-modal-close">&times;</span>
                                    <h1><?php echo docy_opt('subscription_modal_title'); ?></h1>
                                    <p><?php echo docy_opt('subscription_modal_subtitle'); ?></p>
                                    <a href="<?php echo esc_url( $login_url ); ?>" class="btn btn-primary">
                                        <?php echo docy_opt('logout_login_btn' ); ?>
                                    </a>
                                </span>
                            </span>
                            <?php
                        endif;
                    endif;
                ?>
            </div>
        </div>

        <!-- Forum post content -->
        <div class="q-title">
            <span class="question-icon" title="Question">
                <?php echo esc_html($topic_title_prefix) ?>
            </span>
            <h1>
                <?php
                the_title();
                do_action( 'bbpc-resolved-topics' );
                ?>
            </h1>
        </div>

        <div class="forum-post-content">
            <div class="content">

                <?php do_action( 'bbp_theme_before_topic_content' ); ?>
                
				<?php bbp_topic_content(); ?>

				<?php do_action( 'bbp_theme_after_topic_content' ); ?>

            </div>
            <div class="forum-post-btm meta">
				<?php if ( bbp_get_topic_tag_list( get_the_ID() ) ) : ?>
                    <div class="taxonomy forum-post-tags">
						<?php
						bbp_topic_tag_list( '',
							array(
								'before' => '<i class="icon_tags_alt"></i> <strong>' . esc_html__( 'Tagged:', 'docy' ) . '</strong>&nbsp; <span class="tags">',
								'after'  => '</span>'
							)
						);
						?>
                    </div>
				<?php endif; ?>
                <div class="taxonomy forum-post-cat">
					<?php echo get_the_post_thumbnail( bbp_get_topic_forum_id(), 'docy_20x20' ); ?>
                    <a href="<?php bbp_forum_permalink( bbp_get_topic_forum_id() ); ?>">
						<?php echo bbp_get_topic_forum_title() ?>
                    </a>
                </div>
            </div>
            <div class="action-button-container action-btns">
				<?php if ( is_user_logged_in() ) : ?>
                    <ul class="list-unstyled d-flex bbp-admin-links">
						<?php
						$admin_link_args = array( 'before' => '<li>', 'after' => '</li>', 'sep' => '</li><li>' );
						bbp_topic_admin_links( $admin_link_args );
						?>
                    </ul>
				<?php endif; ?>
				<?php
				bbp_topic_favorite_link( array(
					'before' => '',
				));
				?>
            </div>
        </div>
    </div>

<?php
do_action( 'bbp_template_after_lead_topic' );