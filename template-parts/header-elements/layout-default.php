<?php
$s_value = get_search_query() ? get_search_query() : '';
$opt = get_option('docy_opt');

$menu_align = $opt['menu_align'] ?? '';
$is_menu_center = '';
switch ( $menu_align ) {
    case 'left':
        $menu_class = 'justify-content-lg-between ms-5';
        break;
    case 'center':
        $menu_class = '';
        $is_menu_center = 'm-auto';
        break;
    default:
        $menu_class = '';
}
?>

<div class="collapse navbar-collapse <?php echo esc_attr($menu_class) ?>" id="navbarSupportedContent">
    <form action="<?php echo esc_url(home_url("/")) ?>" class="search-input toggle" method="get">
        <input type="search" placeholder="<?php esc_attr_e('Search...', 'docy'); ?>" name="s" value="<?php echo esc_attr($s_value) ?>">
        <button type="submit" class="search-icon">
            <i class="icon_search"></i>
        </button>
    </form>
    <?php
    if ( has_nav_menu('main_menu') ) {
        wp_nav_menu( array (
            'menu' => 'main_menu',
            'theme_location' => 'main_menu',
            'container' => null,
            'menu_class' => "navbar-nav menu ml-auto $is_menu_center",
            'walker' => new Docy_Nav_Walker(),
            'depth' => 4
        ));
    }
    ?>
    <?php get_template_part('template-parts/header-elements/action-button' ); ?>
</div>