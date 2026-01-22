<?php

/**
 * bbPress Replies
 */
$reply_order = !empty($opt['reply_order']) ? $opt['reply_order'] : 'DESC';
add_filter('bbp_before_has_replies_parse_args', function () use ($reply_order) {
    $args['order'] = $reply_order;
    return $args;
});


add_filter('bbp_show_lead_topic', function () {
	$show_lead[] = 'true';
	return $show_lead;
});

/**
 * Include bbPress 'topic' custom post type in WordPress' search results
 */
function ntwb_bbp_topic_cpt_search( $topic_search ) {
	$topic_search['exclude_from_search'] = false;
	return $topic_search;
}
add_filter( 'bbp_register_topic_post_type', 'ntwb_bbp_topic_cpt_search' );

/**
 * Include bbPress 'forum' custom post type in WordPress' search results
 * @param $forum_search
 * @return mixed
 */
function ntwb_bbp_forum_cpt_search( $forum_search ) {
	$forum_search['exclude_from_search'] = false;
	return $forum_search;
}
add_filter( 'bbp_register_forum_post_type', 'ntwb_bbp_forum_cpt_search' );

/**
 * Include bbPress 'reply' custom post type in WordPress' search results
 * @param $reply_search
 * @return mixed
 */
function ntwb_bbp_reply_cpt_search( $reply_search ) {
	$reply_search['exclude_from_search'] = false;
	return $reply_search;
}
add_filter( 'bbp_register_reply_post_type', 'ntwb_bbp_reply_cpt_search' );

/**
 * bbPress User Role
 */
function docy_bbp_user_role_icon() {
	$author_role = bbp_get_user_display_role( bbp_get_reply_author_id() );
	switch ( $author_role ) {
		case 'Keymaster':
			Docy_helper()->image_from_settings( 'keymaster_icon' );
			break;
		case 'Moderator':
			Docy_helper()->image_from_settings( 'moderator_icon' );
			break;
		case 'Participant':
			Docy_helper()->image_from_settings( 'participant_icon' );
			break;
		case 'Spectator':
			Docy_helper()->image_from_settings( 'spectator_icon' );
			break;
		case 'Blocked':
			Docy_helper()->image_from_settings( 'blocked_icon' );
			break;
	}
}

/**
 * Get author role
 */
function docy_get_bbp_user_role() {
	$role = bbp_get_topic_author_role( array(
		'topic_id' => get_the_ID(),
		'before'   => '',
		'after'    => '',
	));
	return $role;
}

/**
 * Get topic reply count
 */
function docy_topic_reply_count() {
	$get_reply = get_post_meta( get_the_ID(), '_bbp_reply_count', true );
	$_reply_count = isset( $get_reply ) && !empty( $get_reply ) ? $get_reply : 0;
	echo esc_html( $_reply_count );
}

/**
 * Remove character "|" showing on click before to the subscription button
 * @param $args
 * @return array|mixed
 */
add_filter ('bbp_before_get_user_subscribe_link_parse_args', function ($args = array() ) {
    $args['before'] = '';
    return $args;
});