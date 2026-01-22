<?php

/**
 * bbPress User Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<form id="bbp-your-profile" class="bbp-your-profile" method="post" enctype="multipart/form-data">

	<?php do_action( 'bbp_user_edit_before' ); ?>

	<fieldset class="bbp-form">
		<legend><?php esc_html_e( 'Name', 'docy' ) ?></legend>



		<div class="row g-3">

            <?php do_action( 'bbp_user_edit_before_name' ); ?>

                <div class="col-lg-6 position-relative">
                    <input type="text" name="first_name" id="first_name" value="<?php bbp_displayed_user_field( 'first_name', 'edit' ); ?>" placeholder="<?php esc_attr_e('First Name', 'docy'); ?>" class="regular-text" />
                    <label for="first_name" class="floating-label"><?php esc_html_e( 'First Name', 'docy' ) ?></label>
                </div>

                <div class="col-lg-6 position-relative">
                    <input type="text" name="last_name" id="last_name" value="<?php bbp_displayed_user_field( 'last_name', 'edit' ); ?>" placeholder="<?php esc_attr_e('Last Name', 'docy'); ?>" class="regular-text" />
                    <label for="last_name" class="floating-label"><?php esc_html_e( 'Last Name', 'docy' ) ?></label>
                </div>

                <div class="col-lg-6">
                    <input type="text" name="nickname" id="nickname" value="<?php bbp_displayed_user_field( 'nickname', 'edit' ); ?>" placeholder="<?php esc_attr_e('Nickname', 'docy'); ?>" class="regular-text" />
                </div>

                <div class="col-lg-6">
                    <?php bbp_edit_user_display_name(); ?>
                </div>

            <?php do_action( 'bbp_user_edit_after_name' ); ?>


            <?php do_action( 'bbp_user_edit_before_contact' ); ?>

                <div class="col-lg-12">
                    <input type="text" name="url" id="url" value="<?php bbp_displayed_user_field( 'user_url', 'edit' ); ?>"
                           placeholder="<?php esc_attr_e('Website', 'docy'); ?>"
                           maxlength="200" class="regular-text code" />
                </div>

            <?php foreach ( bbp_edit_user_contact_methods() as $name => $desc ) : ?>

                <div>
                    <label for="<?php echo esc_attr( $name ); ?>"><?php echo apply_filters( 'user_' . $name . '_label',
                            $desc ); ?></label>
                    <input type="text" name="<?php echo esc_attr( $name ); ?>" id="<?php echo esc_attr( $name ); ?>" value="<?php bbp_displayed_user_field( $name, 'edit' ); ?>" class="regular-text" />
                </div>

            <?php endforeach; ?>

            <?php do_action( 'bbp_user_edit_after_contact' ); ?>

            <?php do_action( 'bbp_user_edit_before_about' ); ?>
                <div class="col-lg-12 position-relative">
                    <textarea name="description" id="description" rows="5" cols="30" class="regular-text" value="<?php bbp_displayed_user_field( 'description', 'edit' ); ?>" placeholder="<?php esc_attr_e('Write about yourself', 'docy'); ?>"></textarea>
                    <label for="description" class="floating-label"><?php esc_html_e( 'Bio', 'docy' ) ?></label>
                </div>

            <?php do_action( 'bbp_user_edit_after_about' ); ?>

        </div>

	</fieldset>

	<fieldset class="bbp-form">
        <legend><?php esc_html_e( 'Account', 'docy' ) ?></legend>

		<?php do_action( 'bbp_user_edit_before_account' ); ?>

		<div class="row g-3">
            <div class="col-lg-6">
                <input type="text" name="user_login" id="user_login" value="<?php bbp_displayed_user_field( 'user_login',
                    'edit' ); ?>" maxlength="100" class="regular-text" autocomplete="off" />
            </div>

            <div class="col-lg-6">
                <input type="text" name="email" id="email" value="<?php bbp_displayed_user_field( 'user_email', 'edit' ); ?>" maxlength="100" class="regular-text" autocomplete="off" />
            </div>
        </div>

		<?php bbp_get_template_part( 'form', 'user-passwords' ); ?>

		<div>
			<?php bbp_edit_user_language(); ?>
		</div>

		<?php do_action( 'bbp_user_edit_after_account' ); ?>

	</fieldset>

	<?php if ( ! bbp_is_user_home_edit() && current_user_can( 'promote_user', bbp_get_displayed_user_id() ) ) : ?>

		<fieldset class="bbp-form">
			<legend><?php esc_html_e( 'User Role', 'docy' ); ?></legend>

			<?php do_action( 'bbp_user_edit_before_role' ); ?>

			<?php if ( is_multisite() && is_super_admin() && current_user_can( 'manage_network_options' ) ) : ?>

				<div>
					<label for="super_admin"><?php esc_html_e( 'Network Role', 'docy' ); ?></label>
					<label>
						<input class="checkbox" type="checkbox" id="super_admin" name="super_admin"<?php checked( is_super_admin( bbp_get_displayed_user_id() ) ); ?> />
						<?php esc_html_e( 'Grant this user super admin privileges for the Network.', 'docy' ); ?>
					</label>
				</div>

			<?php endif; ?>

			<?php bbp_get_template_part( 'form', 'user-roles' ); ?>

			<?php do_action( 'bbp_user_edit_after_role' ); ?>

		</fieldset>

	<?php endif; ?>

	<?php do_action( 'bbp_user_edit_after' ); ?>

	<fieldset class="submit">
		<div>

			<?php bbp_edit_user_form_fields(); ?>

			<button type="submit" id="bbp_user_edit_submit" name="bbp_user_edit_submit" class="button submit user-submit fill-brand"><?php bbp_is_user_home_edit()
				? esc_html_e( 'Update Profile', 'docy' )
				: esc_html_e( 'Update User',    'docy' );
			?></button>
		</div>
	</fieldset>
</form>
