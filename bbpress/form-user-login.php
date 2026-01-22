<?php

/**
 * User Login Form
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;
?>

<form method="post" action="<?php bbp_wp_login_action( array( 'context' => 'login_post' ) ); ?>" class="bbp-login-form">
	<fieldset class="bbp-form">
		<legend><?php esc_html_e( 'Log In', 'docy' ); ?></legend>

		<div class="bbp-username">
			<label for="user_login"><?php esc_html_e( 'Username', 'docy' ); ?>: </label>
			<input type="text" name="log" value="<?php bbp_sanitize_val( 'user_login', 'text' ); ?>" size="20" maxlength="100" id="user_login" autocomplete="off" />
		</div>

		<div class="bbp-password">
			<label for="user_pass"><?php esc_html_e( 'Password', 'docy' ); ?>: </label>
			<input type="password" name="pwd" value="<?php bbp_sanitize_val( 'user_pass', 'password' ); ?>" size="20" id="user_pass" autocomplete="off" />
		</div>

		<div class="bbp-remember-me">
			<input type="checkbox" class="checkbox-tik" name="rememberme" value="forever" <?php checked( bbp_get_sanitize_val( 'rememberme', 'checkbox' ) ); ?> id="rememberme" />
			<label for="rememberme"><?php esc_html_e( 'Keep me signed in', 'docy' ); ?></label>
		</div>

		<?php do_action( 'login_form' ); ?>

		<div class="bbp-submit-wrapper has-bbp-register">

			<button type="submit" name="user-submit" id="user-submit" class="button submit user-submit fill-brand"><?php esc_html_e( 'Log In', 'docy' ); ?></button>

			<?php 
			bbp_user_login_fields();

			// Signup Button
			if ( ! is_user_logged_in() && docy_opt( 'forum_signup_enable' ) == '1' && get_option('users_can_register') == 1 ){
				$register_url = docy_opt('forum_signup_page' ) ? get_permalink( docy_opt( 'forum_signup_page' ) ) : wp_registration_url();
				echo '<a href="' . esc_url( $register_url ) . '" class="button">Sign Up</a>';
			}
			?>

		</div>
	</fieldset>
</form>
