<?php
/**
 * Search
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

if ( bbp_allow_search() ) : ?>

	<div class="bbp-search-form">
		<form role="search" class="search-form input-group icon-in" method="get" id="bbp-reply-search-form">
            <label class="screen-reader-text hidden" for="rs"><?php esc_html_e( 'Search replies:', 'docy' ); ?></label>
            <input type="search" value="<?php bbp_search_terms(); ?>" name="rs" id="rs" class="form-control" placeholder="<?php esc_attr_e( 'Search in Reply', 'docy' ); ?>" />
            <span class="input-group-addon">
                <button type="submit" id="bbp_search_submit"><i class="icon_search"></i></button>
            </span>
		</form>
	</div>

<?php endif;
