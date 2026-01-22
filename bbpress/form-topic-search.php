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
		<form role="search" method="get" class="search-form input-group icon-in" id="bbp-topic-search-form">
            <label class="screen-reader-text hidden" for="ts"><?php esc_html_e( 'Search topics:', 'docy' ); ?></label>
            <input type="text" class="form-control" value="<?php bbp_search_terms(); ?>" name="ts" id="ts" placeholder="<?php esc_attr_e( 'Search in Topics', 'docy' ); ?>" />
            <span class="input-group-addon">
                <button type="submit" id="bbp_search_submit"><i class="icon_search"></i></button>
            </span>
		</form>
	</div>

<?php endif;
