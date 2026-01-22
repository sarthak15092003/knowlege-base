<?php
/**
 * The sidebar containing the main widget area
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package docy
 */

if ( ! is_active_sidebar( 'sidebar_widgets' ) ) {
	return;
}

?>

<div class="col-lg-<?php echo docy_toc('post') == 1 ? '3' : '4'; ?>">
    <?php 
    // Use modern sidebar template
    get_template_part( 'template-parts/sidebar-modern' );
    ?>
    
    <?php 
    // Old sidebar content removed
    /*
    <div class="blog_sidebar mt-4">
	    // Show category articles list on single post pages
	    if ( is_single() && get_post_type() == 'post' ) {
	        $categories = get_the_category();
	        if ( ! empty( $categories ) ) {
	            $category = $categories[0];
	            
	            <div class="widget sidebar_widget">
	                <h3 class="c_head">echo esc_html( $category->name );</h3>
	                <ul class="list-unstyled">
	                    $args = array(
	                        'post_type'      => 'post',
	                        'posts_per_page' => 20,
	                        'cat'            => $category->term_id,
	                        'orderby'        => 'date',
	                        'order'          => 'DESC',
	                    );
	                    $category_posts = new WP_Query( $args );
	                    
	                    if ( $category_posts->have_posts() ) {
	                        while ( $category_posts->have_posts() ) {
	                            $category_posts->the_post();
	                            $is_current = ( get_the_ID() == get_queried_object_id() );
	                            
	                            <li class="echo $is_current ? 'active font-weight-bold' : '';">
	                                <a href="the_permalink();" class="echo $is_current ? 'text-dark' : 'text-dark';">
	                                    the_title();
	                                </a>
	                            </li>
	                        }
	                        wp_reset_postdata();
	                    }
	                </ul>
	            </div>
	        }
	    }
	    
	    // Show regular widgets
	    dynamic_sidebar( 'sidebar_widgets' ); 
	</div>
    */
    ?>
</div>