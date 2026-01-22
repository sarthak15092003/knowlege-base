<?php
$meta_btn_link    = docy_meta('action_btn_link');
$is_menu_btn      = docy_meta_apply('is_menu_btn');

// Button Title
$btn_title 			= '';
if ( ! empty( $meta_btn_link['text'] ) ) {
	$btn_title 		= $meta_btn_link['text'];
} else {
	$btn_title 		= docy_opt('menu_btn_label');
}

// Button URL
$btn_url 			= '';
if ( ! empty( $meta_btn_link['url'] ) ) {
	$btn_url 		= $meta_btn_link['url'];
} else {
	$btn_url 		= docy_opt('menu_btn_url');
}

// Button Target
$btn_target 		= '';
if ( ! empty( $meta_btn_link['target'] ) ) {
	$btn_target 	= "target='{$meta_btn_link['target']}'";
} else {
	$btn_target 	= docy_opt('menu_btn_target', '_self');
}

$two_button 		= $is_menu_btn == '1' && docy_opt('is_dark_switcher') == '1' ? ' two-button' : '';

if ( ( $is_menu_btn == '1' ) || ( docy_opt('is_dark_switcher') == '1' ) || ( docy_opt('is_search_form') == '1' ) ) :
	?>
    <div class="right-nav<?php echo esc_attr($two_button); ?>">
		<?php 

        if ( $is_menu_btn == '1' && ! empty( $btn_title ) ) {
			?>
            <a class="nav_btn tp_btn" href="<?php echo esc_url( $btn_url ) ?>" target="<?php echo esc_attr( $btn_target ) ?>">
				<?php echo esc_html( $btn_title ) ?>
            </a>
		    <?php
        }

		if ( docy_opt('is_dark_switcher') == '1' ) {
			wp_enqueue_style( 'docy-dark-mode' );
			wp_enqueue_script( 'docy-dark-mode' );
			?>
            <div class="px-2 darkmode-btn" title="<?php esc_attr_e( 'Toggle dark mode', 'docy' ); ?>">
                <label for="something" class="tab-btn tab-btns">
                    <i class="fa fa-moon-o"></i>
                </label>
                <label for="something" class="tab-btn">
                    <i class="fa fa-sun-o"></i>
                </label>
                <label id="ball" class=" ball" for="something"></label>
                <input type="checkbox" name="something" id="something" class="dark_mode_switcher something">
            </div>
			<?php
        }
		
		if ( docy_opt('is_search_form') == '1' && docy_opt('header_layout', 'default') == 'default' ) {
			?>
            <div class="search-icon">
                <i class="close-outline icon_close"></i>
                <i class="search-outline icon_search"></i>
            </div>
		    <?php
        }
		?>
    </div>
	<?php
endif;