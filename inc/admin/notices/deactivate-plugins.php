<?php
/**
 * Notice
 * Deactivate the ACF plugin
 *
 * @return void
 */
add_action( 'admin_notices', function () {
	if ( is_plugin_active( 'advanced-custom-fields-pro/acf.php' ) ) :
		?>
		<div class="notice notice-warning eaz-notice">
			<p>
				<?php esc_html_e( 'We replaced the ACF metaboxes with the Theme Settings options framework to make the theme more lightweight and dependency free.', 'docy' ); ?> <br>
				<?php esc_html_e( 'Deactivate ACF Pro plugin to avoid conflict with the new metabox fields.', 'docy' ); ?>
			</p>
			<p>
				<a href="?docy-deactivate-plugin=advanced-custom-fields-pro" class="button-primary button-large">
					<?php esc_html_e( 'Deactivate ACF Pro', 'docy' ); ?>
				</a>
			</p>
		</div>
	    <?php
	endif;
} );

/**
 * Deactivate plugins action
 */
if ( ! empty( $_GET['docy-deactivate-plugin'] ) ) {
	$plugin = sanitize_text_field( $_GET['docy-deactivate-plugin'] );
	add_action( 'admin_init', "docy_deactivate_other_plugin" );
	function docy_deactivate_other_plugin() {

		// Check if the current user has the capability to activate plugins
		if ( ! current_user_can( 'activate_plugins' ) ) {
			return;
		}

		$plugin = ! empty ( $_GET['docy-deactivate-plugin'] ) ? sanitize_text_field( $_GET['docy-deactivate-plugin'] ) : '';
		deactivate_plugins( "$plugin/acf.php" );
		$url = admin_url( 'plugins.php' );
		wp_safe_redirect( $url );
	}
}