<?php

// Color option
CSF::createSection( $prefix, array(
	'title'  => esc_html__( 'Appearance', 'docy' ),
	'id'     => 'color',
	'icon'   => 'dashicons dashicons-admin-appearance',
	'fields' => array(
		array(
            'title'   => esc_html__( 'Color Scheme', 'docy'),
            'id'      => 'color_scheme_option',
            'type'    => 'heading',
        ),
		array(
			'id'     		=> 'accent_solid_color_opt',
			'type'       	=> 'color',
			'title'    	  	=> esc_html__( 'Accent Color', 'docy' ),
			'default'    	=> '#0866ff',
			'output'      	=> ':root',
			'output_mode'	=> '--brand_color',
		),
		array(
			'id'            => 'secondary_color_opt',
			'type'          => 'color',
			'title'    		=> esc_html__( 'Secondary Color', 'docy' ),
			'subtitle'  	=> esc_html__( 'Normally used in Titles, Gradient Colors', 'docy' ),
			'default'   	=> '#1d2746',
			'output'      	=> ':root',
			'output_mode'	=> '--secondary_color',
		),
		array(
			'id'          	=> 'paragraph_color_opt',
			'type'         	=> 'color',
			'title'        	=> esc_html__( 'Paragraph Color', 'docy' ),
			'subtitle'    	=> esc_html__( 'Normally used in meta content, paragraph, doc lists, subtitles, icon', 'docy' ),
			'default'     	=> '#425466',
			'output'      	=> ':root',
			'output_mode'	=> '--p_color',
		),
		array(
			'id'               => 'gradient_bg_color',
			'type'     	       => 'color_group',
			'title'            => esc_html__( 'Background Gradient Color', 'docy' ),
			'subtitle'         => esc_html__( 'This color applied to Post Single, Forum pages, Search Results page', 'docy' ),
			'options'          => array(
				'gradient_bg_color-from'	=> 'From',
				'gradient_bg_color-to' 		=> 'To',
			),
			'default'   	   			=> array(
				'gradient_bg_color-from'	=> '#FFFBF2',
				'gradient_bg_color-to'     	=> '#EDFFFD',
			),
			'output'      				=> ':root'
		),
		
		array(
            'id'          => 'is_box_shadow',
            'type'        => 'switcher',
            'title'       => esc_html__( 'Container Box Shadow', 'docy' ),
            'subtitle'    => esc_html__( 'This color applied to Post Single, Forum pages, Search Results page', 'docy' ),
			'text_on'     => esc_html__( 'SHow', 'docy' ),
			'text_off'    => esc_html__( 'Hide', 'docy' ),
			'text_width'  => 80,
            'default'     => true,
        ),
	)
) );