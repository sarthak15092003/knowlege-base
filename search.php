<?php
/**
 * The template for displaying search results pages.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package docy
 */

get_header();
$blog_column        = is_active_sidebar( 'sidebar_widgets' ) ? '8' : '12';
$blog_layout        = ! empty ( $opt['blog_layout'] ) ? $opt['blog_layout'] : 'list';
$sbnr_post_types    = ! empty ( $opt['sbnr_post_types'] ) ?  $opt['sbnr_post_types'] : ['post', 'page'];
?>
<section class="doc_blog_classic_area sec_pad">
    <div class="container">
        <div class="row">
            <div class="col-lg-<?php echo esc_attr($blog_column) ?> pe-4">
                <div class="search-main">
                    <div class="searchbar-tabs mb-5">

                        <!-- 'All' Tab -->
                        <a href="?s=<?php the_search_query(); ?>" class="tab-item <?php echo docy_is_search_tab_active('all') ?>" value="all">
                            <?php esc_html_e('All', 'docy'); ?>
                        </a>

                        <!-- Dynamic Tabs for Post Types -->
                        <?php  
                        foreach ( $sbnr_post_types as $sbnr_post_type ) :
                            // Clean and format the post type name
                            $cleaned_type = preg_replace( '/[-_]+/', ' ', $sbnr_post_type ); // Replace dashes/underscores with spaces
                            $post_type_label = ucwords( trim( preg_replace( '/[^a-zA-Z0-9\s]/', '', $cleaned_type ) ) ); // Capitalize words and remove unwanted characters
                            ?>
                            <a href="?s=<?php echo esc_attr( get_search_query() ); ?>&post_type=<?php echo esc_attr( $sbnr_post_type ); ?>"
                               class="tab-item <?php echo docy_is_search_tab_active($sbnr_post_type) ? 'active' : ''; ?>"
                               value="<?php echo esc_attr( $sbnr_post_type ); ?>">
                                <?php echo esc_html( $post_type_label ); ?>
                            </a>
                            <?php
                        endforeach;
                        ?>
                    </div>
                    <?php
                    $search_term = get_search_query();
                    $post_type   = isset( $_GET['post_type'] ) ? sanitize_text_field( $_GET['post_type'] ) : 'all';
                    $paged = get_query_var('paged') ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
                    $args = [
                        's'         => $search_term,
                        'post_type' => ( $post_type === 'all' ) ? $sbnr_post_types : $post_type,
                        'posts_per_page' => get_option('posts_per_page'),
                        'paged'          => $paged,
                    ];

                    // Run the search query
                    $search_query = new WP_Query( $args );
                    if ( $search_query->have_posts() ) {
                        while ( $search_query->have_posts() ) : $search_query->the_post();
                            get_template_part( 'template-parts/contents/content', 'search' );
                        endwhile;
                        wp_reset_postdata();
                    } else {
                        get_template_part( 'template-parts/contents/content', 'none' );
                    }
                    Docy_helper()->pagination();
                    ?>
                </div>
            </div>
            <?php get_sidebar(); ?>
        </div>
    </div>
</section>

<?php
get_footer();