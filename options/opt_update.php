<?php
/**
 * Theme Options Update
 */
if (docy_opt('gradient_bg_color')[ 'from' ] ?? '' != '') {
    $group_color = [
        'gradient_bg_color-from' => docy_opt('gradient_bg_color')[ 'from' ] ?? '',
        'gradient_bg_color-to' => docy_opt('gradient_bg_color')[ 'to' ] ?? ''
    ];
} else {
    $group_color = docy_opt('gradient_bg_color');
}

$post_type_disable = docy_opt('post_type_disable');
$faq = $post_type_disable[ 'faq' ] ?? '';
$video = $post_type_disable[ 'video' ] ?? '';
$changelog = $post_type_disable[ 'changelog' ] ?? '';
$post_type = '';

if ($faq == '1' || $changelog == '1' || $video == '1') {

    if ($faq == '1') {
        $faq = 'faq';
    }
    if ($changelog == '1') {
        $changelog = 'changelog';
    }
    if ($video == '1') {
        $video = 'video';
    }

    $post_type = [
        'faq' => $faq,
        'video' => $video,
        'changelog' => $changelog
    ];
}

/**
 * Logo Width
 */
$logo_width_arr = [];
$logo_width = docy_opt('logo_dimensions')[ 'width' ] ?? '';
$logo_height = docy_opt('logo_dimensions')[ 'height' ] ?? '';
$logo_unit = docy_opt('logo_dimensions')[ 'units' ] ?? '';

$logo_width = docy_dimension_exclude([ $logo_width ])[ 0 ];
$logo_height = docy_dimension_exclude([ $logo_height ])[ 0 ];

$logo_width_arr = [
    'width' => $logo_width,
    'height' => $logo_height,
    'units' => $logo_unit
];

/**
 * Preloader Quotes
 */
$preQuotes = [];
if (!empty (docy_opt('preloader_quotes')) && empty (docy_opt('preloader_quotes')[ 0 ][ 'pre-quote' ])) {
    foreach ( docy_opt('preloader_quotes') as $quote ) {
        $preQuotes[] = array( 'pre-quote' => $quote );
    }
} else {
    $preQuotes = docy_opt('preloader_quotes');
}

/**
 * Search Keywords
 */
$searchKeys = [];
if (!empty (docy_opt('doc_keywords')) && empty (docy_opt('doc_keywords')[ 0 ][ 'doc_keyword' ])) {
    foreach ( docy_opt('doc_keywords') as $searchKey ) {
        $searchKeys[] = array( 'doc_keyword' => $searchKey );
    }
} else {
    $searchKeys = docy_opt('doc_keywords');
}

/**
 * Start update options
 */
update_option('docy_opt', [

    // RGBA color
    'back_to_top_btn_bg_color' => is_array(docy_opt('back_to_top_btn_bg_color')) ? docy_opt('back_to_top_btn_bg_color')[ 'color' ] : docy_opt('back_to_top_btn_bg_color'),
    'back_to_top_btn_bg_hover_color' => is_array(docy_opt('back_to_top_btn_bg_hover_color')) ? docy_opt('back_to_top_btn_bg_hover_color')[ 'color' ] : docy_opt('back_to_top_btn_bg_hover_color'),
    'footer_top_normal_font_color' => is_array(docy_opt('footer_top_normal_font_color')) ? docy_opt('footer_top_normal_font_color')[ 'color' ] : docy_opt('footer_top_normal_font_color'),
    'footer_top_hover_font_color' => is_array(docy_opt('footer_top_hover_font_color')) ? docy_opt('footer_top_hover_font_color')[ 'color' ] : docy_opt('footer_top_hover_font_color'),

    // 404 page
    'error_heading' => docy_opt('error_heading'),
    'error_subtitle' => docy_opt('error_subtitle'),
    'error_home_btn_label' => docy_opt('error_home_btn_label'),
    'btn_font_color' => docy_opt('btn_font_color'),
    'btn_bg_color' => docy_opt('btn_bg_color'),
    'error_bg_shape_image' => docy_opt('error_bg_shape_image'),

    // Blog page
    'blog_title' => docy_opt('blog_title'),
    'blog_subtitle' => docy_opt('blog_subtitle'),
    'blog_layout' => docy_opt('blog_layout', 'list'),
    'blog_column' => docy_opt('blog_column', '6'),
    'post_title_length' => docy_opt('post_title_length', '50'),
    'blog_excerpt' => docy_opt('blog_excerpt', '6'),
    'blog_continue_read' => docy_opt('blog_continue_read', 'Continue Reading'),
    'is_post_meta' => docy_opt('is_post_meta'),
    'is_post_date' => docy_opt('is_post_date'),
    'is_post_reading_time' => docy_opt('is_post_reading_time'),

    'is_post_cat' => docy_opt('is_post_cat'),
    'is_post_author' => docy_opt('is_post_author'),
    'blog_single_banner_bg_color' => docy_opt('blog_single_banner_bg_color'),
    'blog_single_banner_title_color' => docy_opt('blog_single_banner_title_color'),
    'is_single_post_meta' => docy_opt('is_single_post_meta'),
    'is_single_post_date' => docy_opt('is_single_post_date'),
    'is_single_reading_time' => docy_opt('is_single_reading_time'),
    'is_single_cats' => docy_opt('is_single_cats'),
    'is_single_post_tag' => docy_opt('is_single_post_tag'),
    'is_related_posts' => docy_opt('is_related_posts'),
    'related_posts_title' => docy_opt('related_posts_title'),
    'related_posts_count' => docy_opt('related_posts_count', 3),

    // color scheme
    'accent_solid_color_opt' => docy_opt('accent_solid_color_opt', '#4c4cf1'),
    'secondary_color_opt' => docy_opt('secondary_color_opt', '#1d2746'),
    'paragraph_color_opt' => docy_opt('paragraph_color_opt', '#425466'),
    'gradient_bg_color' => $group_color ?? docy_opt('gradient_bg_color'),
    'is_box_shadow' => docy_opt('is_box_shadow'),

    // Custom code
    'custom_css' => docy_opt('custom_css'),
    'custom_js' => docy_opt('custom_js'),

    // Dark mode
    'is_dark_switcher' => docy_opt('is_dark_switcher'),
    'is_dark_default' => docy_opt('is_dark_default', ''),
    'brand_color_dark' => docy_opt('brand_color_dark'),

    // Footer
    'footer_style' => docy_opt('footer_style', 'normal'),
    'is_footer_columns_preset' => docy_opt('is_footer_columns_preset'),
    'footer_column' => docy_opt('footer_column', '3'),
    'footer_column_padding' => docy_theme_option_dimension('footer_column_padding', 'padding') ?? docy_opt('footer_column_padding'),
    'fs_illustration' => docy_opt('fs_illustration'),
    'widget_title_color' => docy_opt('widget_title_color'),
    'footer_top_bg_color' => docy_opt('footer_top_bg_color'),
    'footer_btm_bg_color' => docy_opt('footer_btm_bg_color'),
    'copyright_txt' => docy_opt('copyright_txt'),
    'footer_btm_links' => docy_opt('footer_btm_links'),

    // Forum
    'is_forum_top_c2a' => docy_opt('is_forum_top_c2a'),
    'forum_top_c2a-start' => docy_opt('forum_top_c2a-start'),
    'forum_top_c2a_logo' => docy_opt('forum_top_c2a_logo'),
    'forum_top_c2a_title' => docy_opt('forum_top_c2a_title'),
    'forum_top_c2a_subtitle' => docy_opt('forum_top_c2a_subtitle'),
    'forum_top_c2a_btn_title' => docy_opt('forum_top_c2a_btn_title'),
    'forum_top_c2a_btn_url' => docy_opt('forum_top_c2a_btn_url'),
    'is_forum_btm_c2a' => docy_opt('is_forum_btm_c2a'),
    'forum_btm_c2a-start' => docy_opt('forum_btm_c2a-start'),
    'forum_btm_c2a_logo' => docy_opt('forum_btm_c2a_logo'),
    'forum_btm_c2a_bg' => docy_opt('forum_btm_c2a_bg'),
    'forum_btm_c2a_title' => docy_opt('forum_btm_c2a_title'),
    'forum_btm_c2a_btn_title' => docy_opt('forum_btm_c2a_btn_title'),
    'is_forums_in_topics' => docy_opt('is_forums_in_topics'),
    'forums_ppp_in_topics' => docy_opt('forums_ppp_in_topics'),
    'reply_order' => docy_opt('reply_order'),
    'topic_title_prefix' => docy_opt('topic_title_prefix'),
    'topic_title_prefix_typo' => docy_opt('topic_title_prefix_typo'),
    'user_bg' => docy_opt('user_bg'),
    'keymaster_icon' => docy_opt('keymaster_icon'),
    'moderator_icon' => docy_opt('moderator_icon'),
    'participant_icon' => docy_opt('participant_icon'),
    'blocked_icon' => docy_opt('blocked_icon'),
    'is_forum_sidebar' => docy_opt('is_forum_sidebar'),
    'forum_signup_page' => docy_opt('forum_signup_page'),

    // General
    'is_preloader' => docy_opt('is_preloader'),
    'preloader_pages' => docy_opt('preloader_pages'),
    'preloader_page_ids' => docy_opt('preloader_page_ids'),
    'preloader_logo' => docy_opt('preloader_logo'),
    'logo_title' => docy_opt('logo_title'),
    'preloader_logo_title_color' => docy_opt('preloader_logo_title_color'),
    'preloader_title' => docy_opt('preloader_title'),
    'preloader_title_color' => docy_opt('preloader_title_color'),
    'preloader_quotes' => $preQuotes ?? docy_opt('preloader_quotes'),
    'preloader_quotes_color' => docy_opt('preloader_quotes_color'),
    'is_back_to_top_btn_switcher' => docy_opt('is_back_to_top_btn_switcher'),
    'back_to_top_btn_icon_color' => docy_opt('back_to_top_btn_icon_color'),
    'back_to_top_btn_icon_hover_color' => docy_opt('back_to_top_btn_icon_hover_color'),
    'is_ajax_search_tab' => docy_opt('is_ajax_search_tab'),
    'doc_result_limit' => docy_opt('doc_result_limit'),
    'post_type_disable' => is_array($post_type) ? $post_type : docy_opt('post_type_disable'),
    'bt_position' => docy_opt('bt_position'),

    // Header
    'header_width' => docy_opt('header_width'),
    'is_sticky_header' => docy_opt('is_sticky_header'),
    'sticky_appearance' => docy_opt('sticky_appearance'),
    'header_layout' => docy_opt('header_layout'),
    'navbar_position' => docy_opt('navbar_position'),
    'is_search_form' => docy_opt('is_search_form'),
    'main_logo' => docy_opt('main_logo'),
    'sticky_logo' => docy_opt('sticky_logo'),
    'retina_logo' => docy_opt('retina_logo'),
    'retina_sticky_logo' => docy_opt('retina_sticky_logo'),


    'logo_dimensions' => $logo_width_arr ?? docy_opt('logo_dimensions'),
    'logo_padding' => docy_theme_option_dimension('logo_padding', 'padding') ?? docy_opt('logo_padding'),

    'is_menu_btn' => docy_opt('is_menu_btn'),
    'menu_btn_label' => docy_opt('menu_btn_label'),
    'menu_btn_url' => docy_opt('menu_btn_url'),
    'menu_btn_target' => docy_opt('menu_btn_target'),
    'menu_btn_padding' => docy_theme_option_dimension('menu_btn_padding', 'padding') ?? docy_opt('menu_btn_padding'),
    'button_colors' => docy_opt('button_colors'),
    'menu_btn_font_color' => docy_opt('menu_btn_font_color'),

    'menu_btn_hover_font_color' => docy_opt('menu_btn_hover_font_color'),
    'menu_btn_hover_border_color' => docy_opt('menu_btn_hover_border_color'),
    'menu_btn_hover_bg_color' => docy_opt('menu_btn_hover_bg_color'),
    'menu_btn_border_color' => docy_opt('menu_btn_border_color'),
    'menu_btn_bg_color' => docy_opt('menu_btn_bg_color'),
    'button_colors_sticky' => docy_opt('button_colors_sticky'),
    'menu_btn_font_color_sticky' => docy_opt('menu_btn_font_color_sticky'),

    'menu_btn_hover_border_color_sticky' => docy_opt('menu_btn_hover_border_color_sticky'),
    'menu_btn_hover_bg_color_sticky' => docy_opt('menu_btn_hover_bg_color_sticky'),
    'menu_btn_border_color_sticky' => docy_opt('menu_btn_border_color_sticky'),
    'menu_btn_bg_color_sticky' => docy_opt('menu_btn_bg_color_sticky'),
    'menu_btn_hover_font_color_sticky' => docy_opt('menu_btn_hover_font_color_sticky'),
    'title_bar_header' => docy_opt('title_bar_header'),
    'is_banner_ornaments' => docy_opt('is_banner_ornaments'),
    'banner_left_ornament' => docy_opt('banner_left_ornament'),
    'banner_bg' => docy_opt('banner_bg'),
    'banner_right_ornament' => docy_opt('banner_right_ornament'),
    'title_bar_padding' => docy_theme_option_dimension('title_bar_padding', 'padding') ?? docy_opt('title_bar_padding'),
    'titlebar_align' => docy_opt('titlebar_align'),
    'is_search_banner' => docy_opt('is_search_banner'),
    'search_banner_note' => docy_opt('search_banner_note'),
    'is_breadcrumb' => docy_opt('is_breadcrumb'),
    'breadcrumb_home' => docy_opt('breadcrumb_home'),
    'breadcrumb_update_text' => docy_opt('breadcrumb_update_text'),
    'breadcrumbs_note' => docy_opt('breadcrumbs_note'),

    'is_page_title' => docy_opt('is_page_title'),
    'search_banner_layout' => docy_opt('search_banner_layout'),
    'search_banner_el_layout' => docy_opt('search_banner_el_layout'),
    'select_search_banner' => docy_opt('select_search_banner'),
    'search_banner_bg' => docy_opt('search_banner_bg'),
    'is_focus_search' => docy_opt('is_focus_search'),
    'is_focus_by_slash' => docy_opt('is_focus_by_slash'),
    'is_keywords' => docy_opt('is_keywords'),
    'doc_keywords' => $searchKeys ?? docy_opt('doc_keywords'),
    'banner_search_placeholder' => docy_opt('banner_search_placeholder'),
    'sbnr_padding' => docy_theme_option_dimension('sbnr_padding', 'padding') ?? docy_opt('sbnr_padding'),
    'sbnr_light_bg' => docy_opt('sbnr_light_bg'),
    'sbnr_bg_image2' => docy_opt('sbnr_bg_image2'),
    'sbnr_bg_image' => docy_opt('sbnr_bg_image'),
    'sbanner_left_image' => docy_opt('sbanner_left_image'),
    'sbanner_right_image' => docy_opt('sbanner_right_image'),
    'sbanner_man_image' => docy_opt('sbanner_man_image'),
    'sbanner_flower_image' => docy_opt('sbanner_flower_image'),
    'sbanner_bg_image' => docy_opt('sbanner_bg_image'),
    'sbanner_shape1' => docy_opt('sbanner_shape1'),
    'sbanner_shape2' => docy_opt('sbanner_shape2'),
    'is_banner_overlay' => docy_opt('is_banner_overlay'),
    'is_sbnr_blur' => docy_opt('is_sbnr_blur'),
    'sbnr_overlay_color' => docy_opt('sbnr_overlay_color'),

    // Menu
    'menu_align' => docy_opt('menu_align'),
    'normal_menu_start' => docy_opt('normal_menu_start'),
    'menu_normal_font_color' => docy_opt('menu_normal_font_color'),
    'menu_normal_hover_font_color' => docy_opt('menu_normal_hover_font_color'),
    'menu_normal_item_active_color' => docy_opt('menu_normal_item_active_color'),
    'sticky_menu_start' => docy_opt('sticky_menu_start'),
    'sticky_menu_font_color' => docy_opt('sticky_menu_font_color'),
    'menu_sticky_hover_font_color' => docy_opt('menu_sticky_hover_font_color'),
    'menu_sticky_active_font_color' => docy_opt('menu_sticky_active_font_color'),
    'dropdown_menu_start' => docy_opt('dropdown_menu_start'),
    'dropdown_menu_font_color' => docy_opt('dropdown_menu_font_color'),
    'dropdown_menu_hover_font_color' => docy_opt('dropdown_menu_hover_font_color'),
    'dropdown_menu_bg_color' => docy_opt('dropdown_menu_bg_color'),
    'dropdown_menu_border_color' => docy_opt('dropdown_menu_border_color'),
    'menu_item_padding' => docy_theme_option_dimension('menu_item_padding', 'padding') ?? docy_opt('menu_item_padding'),
    'menu_item_margin' => docy_theme_option_dimension('menu_item_margin', 'margin') ?? docy_opt('menu_item_margin'),

    // Privacy
    'is_privacy_bar' => docy_opt('is_privacy_bar'),
    'privacy_bar_txt' => docy_opt('privacy_bar_txt'),
    'privacy_bar_btn_txt' => docy_opt('privacy_bar_btn_txt'),
    'privacy_bar_padding' => docy_theme_option_dimension('privacy_bar_padding', 'padding') ?? docy_opt('privacy_bar_padding'),
    'privacy_bar_bg' => docy_opt('privacy_bar_bg'),

    // Shop
    'shop_title' => docy_opt('shop_title'),
    'shop_subtitle' => docy_opt('shop_subtitle'),
    'shop_sidebar' => docy_opt('shop_sidebar'),
    'shop-single' => docy_opt('shop-single'),
    'related_products_title' => docy_opt('related_products_title'),
    'related_products_subtitle' => docy_opt('related_products_subtitle'),
    'is_wc_block' => docy_opt('is_wc_block'),

    // Social Link
    'facebook' => docy_opt('facebook'),
    'twitter' => docy_opt('twitter'),
    'instagram' => docy_opt('instagram'),
    'linkedin' => docy_opt('linkedin'),
    'youtube' => docy_opt('youtube'),
    'dribbble' => docy_opt('dribbble'),
    'github' => docy_opt('github'),

    // Typography
    'typo_note' => docy_opt('typo_note'),
    'body_typo' => docy_theme_option_typo('body_typo') ?? docy_opt('body_typo'),
    'typo_note_headers' => docy_opt('typo_note_headers'),
    'h1_typo' => docy_theme_option_typo('h1_typo') ?? docy_opt('h1_typo'),
    'h2_typo' => docy_theme_option_typo('h2_typo') ?? docy_opt('h2_typo'),
    'h3_typo' => docy_theme_option_typo('h3_typo') ?? docy_opt('h3_typo'),
    'h4_typo' => docy_theme_option_typo('h4_typo') ?? docy_opt('h4_typo'),
    'h5_typo' => docy_theme_option_typo('h5_typo') ?? docy_opt('h5_typo'),
    'h6_typo' => docy_theme_option_typo('h6_typo') ?? docy_opt('h6_typo'),

    // Customizer
    'customizer_visibility' => docy_opt('customizer_visibility')

], true);