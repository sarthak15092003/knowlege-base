<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package docy
 */

/**
 * Theme Options
 */
$opt                = get_option('docy_opt' );
$is_back2top_btn    = $opt['is_back_to_top_btn_switcher'] ?? '1';
$bt_position        = $opt['bt_position'] ?? '';

/**
 * Page Options
 */
$footer_visibility     = docy_meta( 'footer_visibility' );

if ( !isset( $footer_visibility ) || ( isset($footer_visibility) & $footer_visibility != '0' ) ) {
    $footer_visibility = '1';
}

if ( $footer_visibility == '1' ) {
    $footer_style  = !empty($opt['footer_style']) ? $opt['footer_style'] : 'normal';
    $copyright_text = !empty($opt['copyright_txt']) ? $opt['copyright_txt'] : esc_html__('©2025 Spider Themes. All rights reserved', 'docy');

    get_template_part('template-parts/footers/footer', $footer_style);
}
?>

</div> <!-- Body Wrapper -->

<?php
if ( $is_back2top_btn == '1' ) :
    ?>
    <a id="back-to-top" title="<?php esc_attr_e('Back to Top', 'docy') ?>" class="<?php echo esc_attr( $bt_position ); ?>"></a>
    <?php 
endif;

if ( is_singular('docs') || is_singular('post') ) :
    ?>
    <div id="reading-progress"><div id="reading-progress-fill"></div></div>
    <?php 
endif;

wp_footer();
?>
</body>
</html>