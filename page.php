<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package docy
 */

get_header();

$opt = get_option('docy_opt');

if ( docy_toc('page') == '1' ) {
	wp_enqueue_script( 'anchor' );
	wp_enqueue_script( 'bootstrap-toc' );
}

$padding = "";

$wrap_class = 'page_wrapper';
if ( class_exists('bbPress') ) {
    $wrap_class .= is_post_type_archive('forum') || is_post_type_archive('topic') || is_singular('forum') ? ' forum-page-content' : '';
} elseif ( in_array('woocommerce', get_body_class()) || in_array('woocommerce-page', get_body_class() ) ) {
    $wrap_class = '';
}

while ( have_posts() ) : the_post();
    ?>
    <div class="sec_pad <?php echo esc_attr($wrap_class) ?>">
        <div class="container">

	        <?php
	        if ( docy_toc('page') == '1' ) :
            ?>
            <div id="toc_stick" class="row">
                <div class="col-lg-2 doc_mobile_menu doc-sidebar display_none">
                    <aside class="left_sidebarlist">
                        <nav data-toggle="toc" class="nav-sidebar doc-nav" id="docy-toc"> </nav>
                    </aside>
                </div>

                <div class="sc-jtXEFf jrBzsJ">
                    <div class="sc-eldieg eYVFtH">
                        <div class="overlay" id="toc-overlay"></div>
                        <!-- Button to toggle the Table of Contents -->
                        <button class="sc-kiIyQV fqmceZ table_content" aria-expanded="false" aria-controls="docy-toc">
                            <?php esc_html_e('Table of Contents', 'docy'); ?>
                        </button>

                        <!-- Hidden Table of Contents, will appear above the button -->
                        <aside class="bottom_table_content" id="docy-tocs" aria-hidden="true">
                            <button class="close-toc">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24"
                                     viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707
                                     4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12
                                     13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision">
                                    </path>
                                </svg>
                            </button>
                            <nav data-toggle="toc" class="nav-sidebar doc-nav">
                                <!-- You can dynamically generate your TOC items here -->
                            </nav>
                        </aside>

                        <!-- Button to show Share modal -->
                        <button class="sc-kiIyQV fqmceZ table_share_btn">
                            <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_ sc-cLpAjG cfZGuc"
                                 data-ui-name="Share" width="16" height="16" viewBox="0 0 16 16" data-name="Share" data-group="m">
                                <path d="M11.707 1.293a1 1 0 1 0-1.414 1.414L12.586 5H7a6 6 0 0 0-6 6v3a1 1 0 1 0 2
                                0v-3a4 4 0 0 1 4-4h5.586l-2.293 2.293a1 1 0 1 0 1.414 1.414l4-4a1 1 0 0 0 0-1.414l-4-4Z"
                                      shape-rendering="geometricPrecision"></path>
                            </svg>
                            <?php esc_html_e('Share', 'docy'); ?>
                        </button>

                        <!-- Hidden Share Modal -->
                        <div class="docy-modal-content" id="share-modal" aria-hidden="true">

                            <button class="close docy-close" aria-label="Close Share Modal">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24"
                                     viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707
                                     4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12
                                     13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision">
                                    </path>
                                </svg>
                            </button>

                            <div class="docy-share-wrap">
                                <div class="social-links">
                                    <a href="mailto:?subject=<?php the_title(); ?>&amp;body= <?php esc_html_e( 'Check out this doc', 'docy' );
                                    the_permalink(); ?>" target="_blank">
                                        <i class="icon_mail"></i>
                                    </a>
                                    <a href="https://www.facebook.com/share.php?u=<?php the_permalink(); ?>">
                                        <i class="social_facebook_circle"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>">
                                        <i class="social_linkedin_square"></i>
                                    </a>
                                    <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?> &amp;hashtags=<?php echo esc_url(site_url()); ?>">
                                        <i class="social_twitter"></i>
                                    </a>
                                </div>
                                <p>Copy link</p>
                                <div class="docy-copy-url-wrap">
                                    <div class="share-this-docs">
                                        <input readonly type="text" value="<?php the_permalink(); ?>" class="word-wrap">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/clone.svg" alt="<?php esc_attr_e( 'Docy theme', 'docy' ); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-10 anchor-enabled">
                <?php endif; ?>
                    <?php
                    the_content();
                    wp_link_pages(array(
                        'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'docy' ) . '</span>',
                        'after'       => '</div>',
                        'link_before' => '<span>',
                        'link_after'  => '</span>',
                        'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'docy' ) . ' </span>%',
                        'separator'   => '<span class="screen-reader-text">, </span>',
                    ));

                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;

                    echo docy_toc('page') == '1' ? '
                </div> 
            </div>' : ''; // Close the row
            ?>
        </div>
    </div>

<?php
endwhile; // End of the loop.

if ( is_post_type_archive( array('forum', 'topic') ) ) {
    if ( docy_opt('is_forum_btm_c2a') == '1' ) {
        get_template_part('template-parts/forum/c2a-bottom');
    }
}

get_footer();