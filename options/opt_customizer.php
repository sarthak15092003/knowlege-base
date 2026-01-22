<?php

$customizer_url = admin_url( 'customize.php?url=' ) . site_url( '/' ) . '?autofocus[panel]=docy_opt&autofocus[section]=color';

CSF::createSection( $prefix, array(
	'id'     => 'design_fields',
	'title'  => esc_html__( 'Customizer', 'docy' ),
	'icon'   => 'fas fa-plus-circle',
	'fields' => [
		
		array(
			'id'         => 'customizer_visibility',
			'type'       => 'switcher',
			'title'      => esc_html__( 'Options Visibility on Customizer', 'docy' ),
			'text_on'    => esc_html__( 'Enabled', 'docy' ),
			'text_off'   => esc_html__( 'Disabled', 'docy' ),
			'text_width' => 100,
		),

		array(
			'type'       => 'content',
			'content'    => sprintf( '<a href="' . $customizer_url . '" target="_blank" id="docy_customizer_opt">' . esc_html__( 'Customizer', 'docy' ) . '</a>' ),
			'dependency' => array(
				array( 'customizer_visibility', '==', true ),
			),
		)

	]
) );