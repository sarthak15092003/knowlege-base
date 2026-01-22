<?php
CSF::createSection('docy_opt', array(
    'title' => esc_html__('Forums', 'docy'),
    'id' => 'forums_opt',
    'icon' => 'dashicons dashicons-buddicons-forums',
));

/**
 * Forum archive settings
 */
CSF::createSection('docy_opt', array(
    'parent' => 'forums_opt',
    'title' => esc_html__('Forum Archive', 'docy'),
    'id' => 'forum_archive_opt',
    'icon' => '',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'forum_details_header',
            'title' => esc_html__('Forum Archive', 'docy'),
            'type' => 'heading',
        ),
        array(
            'title' => esc_html__('Top Call to Action', 'docy'),
            'id' => 'is_forum_top_c2a',
            'type' => 'switcher',
            'text_on' => esc_html__('Show', 'docy'),
            'text_off' => esc_html__('Hide', 'docy'),
            'text_width' => 80,
            'default' => '1',
        ),

        /**
         * Top Call to Action
         */
        array(
            'title' => esc_html__('Top Call to Action Controls', 'docy'),
            'id' => 'forum_top_c2a-start',
            'type' => 'subheading',
            'indent' => true,
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Left Featured Image', 'docy'),
            'id' => 'forum_top_c2a_logo',
            'type' => 'media',
            'compiler' => true,
            'default' => array(
                'url' => DOCY_DIR_IMG . '/forum/answer.png'
            ),
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Title', 'docy'),
            'id' => 'forum_top_c2a_title',
            'type' => 'text',
            'default' => esc_html__("Can't find an answer?", 'docy'),
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Subtitle', 'docy'),
            'id' => 'forum_top_c2a_subtitle',
            'type' => 'text',
            'default' => esc_html__('Make use of a qualified tutor to get the answer', 'docy'),
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Button Title', 'docy'),
            'id' => 'forum_top_c2a_btn_title',
            'type' => 'text',
            'default' => esc_html__('Ask a Question', 'docy'),
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),
        array(
            'title' => esc_html__('Button URL', 'docy'),
            'id' => 'forum_top_c2a_btn_url',
            'type' => 'text',
            'default' => '#',
            'dependency' => array( 'is_forum_top_c2a', '==', '1' )
        ),

        array(
            'id' => 'forum_top_c2a-end',
            'type' => 'subheading',
            'indent' => false,
        ),

        /**
         * Bottom Call to Action
         */
        array(
            'title' => esc_html__('Bottom Call to Action', 'docy'),
            'id' => 'is_forum_btm_c2a',
            'type' => 'switcher',
            'text_on' => esc_html__('Show', 'docy'),
            'text_off' => esc_html__('Hide', 'docy'),
            'text_width' => 80,
            'default' => '1',
        ),
        array(
            'title' => esc_html__('Bottom Call to Action', 'docy'),
            'subtitle' => esc_html__('Control here the bottom Call to Action area of the Forum archive pages.', 'docy'),
            'id' => 'forum_btm_c2a-start',
            'type' => 'subheading',
            'indent' => true,
            'dependency' => array( 'is_forum_btm_c2a', '==', '1' )
        ),
        array(
            'title' => esc_html__('Left Featured Image', 'docy'),
            'id' => 'forum_btm_c2a_logo',
            'type' => 'media',
            'compiler' => true,
            'default' => array(
                'url' => DOCY_DIR_IMG . '/forum/chat-smile.png'
            ),
            'dependency' => array( 'is_forum_btm_c2a', '==', '1' )
        ),
        array(
            'title' => esc_html__('Background Image', 'docy'),
            'id' => 'forum_btm_c2a_bg',
            'type' => 'media',
            'compiler' => true,
            'default' => array(
                'url' => DOCY_DIR_IMG . '/forum/overlay_bg.png'
            ),
            'dependency' => array( 'is_forum_btm_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Title', 'docy'),
            'id' => 'forum_btm_c2a_title',
            'type' => 'text',
            'default' => esc_html__('New to Communities?', 'docy'),
            'dependency' => array( 'is_forum_btm_c2a', '==', '1' )
        ),

        array(
            'title' => esc_html__('Button Title', 'docy'),
            'id' => 'forum_btm_c2a_btn_title',
            'type' => 'text',
            'default' => esc_html__('Join the community ', 'docy'),
            'dependency' => array( 'is_forum_btm_c2a', '==', '1' )
        ),

        array(
            'id' => 'forum_btm_c2a-end',
            'type' => 'subheading',
            'indent' => false,
        ),
        
        array(
            'title' => esc_html__('Signup Button', 'docy'),
            'id' => 'forum_signup_enable',
            'type' => 'switcher',
            'text_on' => esc_html__('Enable', 'docy'),
            'text_off' => esc_html__('Disable', 'docy'),
            'default' => '0',
            'text_width' => 80            
        ),
        
        array(
            'title' => esc_html__('Select signup Page d', 'docy'),
            'id' => 'forum_signup_page',
            'type' => 'select',
            'options' => 'pages',
            'dependency' => array( 'forum_signup_enable', '==', '1' ),
            'chosen' => true,
            'ajax' => true,
            'close' => true,
            'subtitle' => sprintf( __( 'This setting will work if the Membership checkbox is checked from the general <a href="%s">settings</a>', 'docy' ), admin_url( 'options-general.php' )),
            'desc' => sprintf( __( 'Use this shortcode to show the bbpress default signup form <b>%s</b> Or skip the selection if need the wordprss register form', 'docy' ), '[bbp-register]' )
        )
    )
));


/**
 * Forum topics archive
 */
CSF::createSection('docy_opt', array(
    'title' => esc_html__('Topics Archive', 'docy'),
    'parent' => 'forums_opt',
    'id' => 'topics_archive_opt',
    'icon' => '',
    'subsection' => true,
    'fields' => array(
        array(
            'title' => esc_html__('Forums', 'docy'),
            'id' => 'is_forums_in_topics',
            'type' => 'switcher',
            'text_on' => esc_html__('Show', 'docy'),
            'text_off' => esc_html__('Hide', 'docy'),
            'default' => '1',
            'text_width' => 80,

        ),
        array(
            'title' => esc_html__('Forums Number', 'docy'),
            'desc' => esc_html__('Forums to show above the topics list.', 'docy'),
            'id' => 'forums_ppp_in_topics',
            'type' => 'slider',
            'default' => 4,
            'min' => 4,
            'step' => 1,
            'max' => 50,
            'display_value' => 'label',
        ),
    )
));

/**
 * Forum topics details
 */
CSF::createSection('docy_opt', array(
    'parent' => 'forums_opt',
    'title' => esc_html__('Topic Details', 'docy'),
    'id' => 'topic_details_opt',
    'icon' => '',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'topic_details_header',
            'title' => esc_html__('Topic Details', 'docy'),
            'type' => 'heading',
        ),
        array(
            'title' => esc_html__('Replies Order', 'docy'),
            'id' => 'reply_order',
            'type' => 'select',
            'default' => [ 'DESC' ],
            'options' => array(
                'ASC' => esc_html__('Ascending', 'docy'),
                'DESC' => esc_html__('Descending', 'docy'),
            )
        ),
        array(
            'title' => esc_html__('Title Prefix', 'docy'),
            'id' => 'topic_title_prefix',
            'type' => 'text',
            'default' => esc_html__('Q:', 'docy')
        ),

        array(
            'id' => 'topic_title_prefix_typo',
            'type' => 'typography',
            'title' => esc_html__('Title Prefix Typography', 'docy'),
            'font_family' => true,
            'font_style' => true,
            'font_size' => true,
            'text_align' => true,
            'text_transform' => true,
            'line_height' => true,
            'letter_spacing' => true,
            'output' => '.question-icon'
        ),

        // Subscription
        array(
            'id' => 'topic_subscription',
            'title' => esc_html__('Subscription', 'docy'),
            'subtitle'   => esc_html__('Subscription settings for logged out users.', 'docy'),
            'type' => 'subheading'
        ),
        
        array(
            'title' => esc_html__('Enable / Disable', 'docy'),
            'id' => 'is_topic_subscription',
            'type' => 'switcher',
            'text_on' => esc_html__('Show', 'docy'),
            'text_off' => esc_html__('Hide', 'docy'),
            'default' => '1',
            'text_width' => 80,
        ),

        array(
            'title'     => esc_html__('Subscribe Button', 'docy'),
            'id'        => 'logout_subscription_btn',
            'type'      => 'text',
            'default'   => esc_html__('Subscribe', 'docy'),
            'dependency' => array( 'is_topic_subscription', '==', '1' )
        ),

        array(
            'title' => esc_html__('Title', 'docy'),
            'id' => 'subscription_modal_title',
            'type' => 'text',
            'default' => esc_html__('Login to the community', 'docy'),
            'dependency' => array( 'is_topic_subscription', '==', '1' )
        ),

        array(
            'title' => esc_html__('Subtitle', 'docy'),
            'id' => 'subscription_modal_subtitle',
            'type' => 'text',
            'default' => esc_html__('You must be logged in to subscribe to this topic.', 'docy'),
            'dependency' => array( 'is_topic_subscription', '==', '1' )
        ),
        array(
            'title' => esc_html__('Login Button', 'docy'),
            'id' => 'logout_login_btn',
            'type' => 'text',
            'default' => esc_html__('Login', 'docy'),
            'dependency' => array( 'is_topic_subscription', '==', '1' )
        ),
        
        array(
            'title' => esc_html__('Login Type', 'docy'),
            'id' => 'login_url_type',
            'type' => 'select',
            'default' => 'default',
            'options' => array(
                'default' => esc_html__('Default Login', 'docy'),
                'custom' => esc_html__('Custom Page', 'docy'),
            ),
            'dependency' => array( 'is_topic_subscription', '==', '1' )
        ),
        
        array(
            'title' => esc_html__('Select Page', 'docy'),
            'id' => 'login_page',
            'type' => 'select',
            'options' => 'pages',
            'dependency' => array(
                array( 'login_url_type', '==', 'custom' ),
                array( 'is_topic_subscription', '==', '1' ),
            )  
        ),
    )
));

// Users
CSF::createSection('docy_opt', array(
    'parent' => 'forums_opt',
    'title' => esc_html__('Users', 'docy'),
    'id' => 'forum_users',
    'icon' => '',
    'subsection' => true,
    'fields' => array(
        array(
            'id' => 'user_details_header',
            'title' => esc_html__('User Details', 'docy'),
            'type' => 'heading',
        ),
        array(
            'title' => esc_html__('User Menu Background', 'docy'),
            'subtitle' => esc_html__('Select a pre-designed banner background style.', 'docy'),
            'id' => 'user_bg',
            'type' => 'image_select',
            'default' => 'color',
            'options' => docy_banner_bg_style()
        ),
        array(
            'title' => esc_html__('Key master Icon', 'docy'),
            'id' => 'keymaster_icon',
            'type' => 'media',
            'compiler' => true,
            'default' => [
                'url' => DOCY_DIR_IMG . '/icons/keymaster.svg'
            ]
        ),
        array(
            'title' => esc_html__('Moderator Icon', 'docy'),
            'id' => 'moderator_icon',
            'type' => 'media',
            'compiler' => true,
            'default' => [
                'url' => DOCY_DIR_IMG . '/icons/moderator.svg'
            ]
        ),
        array(
            'title' => esc_html__('Participant Icon', 'docy'),
            'id' => 'participant_icon',
            'type' => 'media',
            'compiler' => true,
            'default' => [
                'url' => DOCY_DIR_IMG . '/icons/participants.svg'
            ]
        ),
        array(
            'title' => esc_html__('Blocked Icon', 'docy'),
            'id' => 'blocked_icon',
            'type' => 'media',
            'compiler' => true,
            'default' => [
                'url' => DOCY_DIR_IMG . '/icons/block-user.svg'
            ]
        )
    )
));
