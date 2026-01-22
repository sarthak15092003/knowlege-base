<?php
// Color option
CSF::createSection($prefix, array(
    'title'     => esc_html__( 'Dark Mode', 'docy' ),
    'id'        => 'dark_mode_opt',
    'icon'      => 'dashicons dashicons-star-half',
    'fields'    => array(
        array(
            'title'   => esc_html__( 'Dark Mode', 'docy'),
            'id'      => 'dark_mode_option',
            'type'    => 'heading',
        ),
        array(
            'title'         => esc_html__( 'Dark Mode Switcher', 'docy' ),
            'subtitle'      => esc_html__( 'Show/Hide the Dark Mode Switcher on the Header navigation bar.', 'docy' ),
            'id'            => 'is_dark_switcher',
            'type'          => 'switcher',
            'text_on'       => esc_html__( 'SHow', 'docy' ),
			'text_off'      => esc_html__( 'Hide', 'docy' ),
			'text_width'    => 80,
            'default'       => true,
        ),
        array(
            'title'         => esc_html__( 'Active Dark Mode', 'docy' ),
            'subtitle'      => esc_html__( 'Activate the Dark Mode by default.', 'docy' ),
            'id'            => 'is_dark_default',
            'type'          => 'switcher',
            'text_width'    => 80,
            'default'       => '',
            'dependency'    => array('is_dark_switcher', '!=', '1')
        ),
        array(
            'id'            => 'brand_color_dark',
            'type'          => 'color',
            'title'         => esc_html__( 'Accent Color', 'docy' ),
            'subtitle'      => esc_html__( 'Accent Color for dark mode', 'docy' ),
            'output'        => ':root',
			'output_mode'   => '--brand_color_dark',
        ),
    )
));