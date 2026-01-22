<?php

// Function to show the notice only on themes.php page and if it hasn't been dismissed
function docy_show_congratulations_notice() {
	global $pagenow;

	// Show only on themes.php and if the user hasn't dismissed the notice
	if ( $pagenow == 'themes.php' && ! get_user_meta( get_current_user_id(), 'docy_congrats_notice_dismissed', true ) ) {
		?>
        <div class="nv-welcome-notice updated notice ti-about-notice">
            <div class="notice-dismiss theme-active-notice"></div>
            <div class="nv-notice-wrapper">
                <h2> <?php esc_html_e( 'Congratulations!', 'docy' ) ?> </h2>
                <p class="about-description"><?php esc_html_e( "Docy is now installed and ready to use. We've assembled some links to get you started.",
						'docy' ); ?></p>
                <hr>
                <div class="nv-notice-column-container">
                    <div class="nv-notice-column nv-notice-image">
                        <picture>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/docy-cong.jpg" alt="<?php esc_attr_e( 'Docy theme', 'docy' ); ?>">
                        </picture>
                    </div>
                    <div class="nv-notice-column nv-notice-starter-sites">
                        <div>
                            <h3><span class="dashicons dashicons-images-alt2"></span> <?php esc_html_e( 'Ready To GO', 'docy' ); ?> </h3>
                            <p> <?php esc_html_e( 'Now you need to configure the theme step by step to build your dream website. Follow the steps bellow to get started with Docy.', 'docy' ) ?> </p>
                        </div>
                        <div>
                            <div class="fat-arrow-rappa">
                                <a href="<?php echo admin_url( 'admin.php?page=docy_verify' ) ?>" class="fat-arrow">
                                    <div class="flo-arrow"><?php esc_html_e( '01', 'docy' ); ?></div>
                                    <span class="inner"> <?php esc_html_e( 'Register/Verify', 'docy' ); ?> </span>
                                </a>
                                <a href="<?php echo admin_url( 'themes.php?page=tgmpa-install-plugins&plugin_status=activate' ) ?>" class="fat-arrow">
                                    <div class="flo-arrow"><?php esc_html_e( '02', 'docy' ); ?></div>
                                    <span class="inner"><?php esc_html_e( 'Install Required Plugins', 'docy' ); ?></span>
                                </a>
                                <a href="<?php echo admin_url( 'admin.php?page=one-click-demo-import' ) ?>" class="fat-arrow">
                                    <div class="flo-arrow"><?php esc_html_e( '03', 'docy' ); ?></div>
                                    <span class="inner"><?php esc_html_e( 'Import Demo', 'docy' ); ?></span>
                                </a>
                            </div>
                            <p>
                                <a href="<?php echo admin_url( 'admin.php?page=docy-options' ) ?>" class="options-page-btn">
                                    <?php esc_html_e( 'then, go to the theme settings', 'docy' ); ?>
                                </a>
                            </p>
                        </div>
                    </div>
                    <div class="nv-notice-column nv-notice-documentation">
                        <div>
                            <h3><span class="dashicons dashicons-format-aside"></span><?php esc_html_e( 'Documentation', 'docy' ); ?></h3>
                            <p><?php esc_html_e( 'Need more details? Please check our full documentation for detailed information on how to use Docy.',
									'docy' ); ?></p>
                            <a target="_blank" rel="external noopener noreferrer" href="https://helpdesk.spider-themes.net/docs/docy-wordpress-theme/">
                                <span class="screen-reader-text"><?php esc_html_e( '(opens in a new tab)', 'docy' ); ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" focusable="false" role="img" viewBox="0 0 512 512" width="12" height="12"
                                     style="margin-right: 5px;">
                                    <path fill="currentColor" d="M432 320H400a16 16 0 0 0-16 16V448H64V128H208a16 16 0 0 0 16-16V80a16 16 0 0 0-16-16H48A48 48 0 0 0 0 112V464a48 48 0 0 0 48
                                    48H400a48 48 0 0 0 48-48V336A16 16 0 0 0 432 320ZM488 0h-128c-21.4 0-32 25.9-17 41l35.7 35.7L135 320.4a24 24 0 0 0 0 34L157.7 377a24 24 0 0 0 34 0L435.3
                                    133.3 471 169c15 15 41 4.5 41-17V24A24 24 0 0 0 488 0Z"></path>
                                </svg>
								<?php esc_html_e( 'Read full documentation', 'docy' ); ?>
                            </a>
                        </div>
                        <div>
                            <p class="secondary-btn-wrapper">
                                <a href="<?php echo admin_url( 'index.php' ) ?>" class="ti-return-dashboard button button-secondary button-hero install-now">
                                    <span><?php esc_html_e( 'Return to your dashboard', 'docy' ); ?></span>
                                </a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script type="text/javascript">
            (function ($) {
                $('.theme-active-notice').on('click', function () {
                    var data = {
                        action: 'dismiss_docy_notice',
                        security: '<?php echo wp_create_nonce( "dismiss_docy_notice" ); ?>'
                    };

                    $.post(ajaxurl, data, function (response) {
                        if ( response == 'success' ) {
                            $('.nv-welcome-notice').fadeOut();
                        }
                    });
                });
            })(jQuery);
        </script>
		<?php
	}
}

add_action( 'admin_notices', 'docy_show_congratulations_notice' );

// AJAX handler to dismiss the notice and store in user meta
function docy_dismiss_notice() {
	check_ajax_referer( 'dismiss_docy_notice', 'security' );
	update_user_meta( get_current_user_id(), 'docy_congrats_notice_dismissed', 1 );
	echo 'success';
	wp_die();
}

add_action( 'wp_ajax_dismiss_docy_notice', 'docy_dismiss_notice' );