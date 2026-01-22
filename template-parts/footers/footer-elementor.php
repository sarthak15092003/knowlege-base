<?php
$opt = get_option('docy_opt');
$footer_el_template = $opt['footer_el_template'] ?? '';

if ( in_array( 'elementor/elementor.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
    <footer id="docy-footer" class="docy-footer">
        <?php echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($footer_el_template); ?>
    </footer>
    <?php
}