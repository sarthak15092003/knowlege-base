<?php
/**
 * docy functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package docy
 */


if (!function_exists('docy_setup')) :
    /**
     * Sets up theme defaults and registers support for various WordPress features.
     *
     * Note that this function is hooked into the after_setup_theme hook, which
     * runs before the init hook. The init hook is too late for some features, such
     * as indicating support for post thumbnails.
     */
    function docy_setup () {
        /*
         * Make theme available for translation.
         * Translations can be filed in the /languages/ directory.
         * If you're building a theme based on gull, use a find and replace
         * to change 'gull' to the name of your theme in all the template files.
         */
        load_theme_textdomain('docy', get_template_directory() . '/languages');

        // Add default posts and comments RSS feed links to head.
        add_theme_support('automatic-feed-links');

        // Enable excerpt support for page
        add_post_type_support('page', 'excerpt');

        /*
         * Let WordPress manage the document title.
         * By adding theme support, we declare that this theme does not use a
         * hard-coded <title> tag in the document head, and expect WordPress to
         * provide it for us.
         */
        add_theme_support('title-tag');
        add_theme_support('woocommerce');

        /*
         * Enable support for Post Thumbnails on posts and pages.
         *
         * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
         */
        add_theme_support('post-thumbnails');
        add_post_type_support('forum', 'thumbnail');
        add_post_type_support('topic', 'thumbnail');
        add_theme_support('post-formats', array( 'video', 'quote', 'link' ));

        // This theme uses wp_nav_menu() in one location.
        register_nav_menus(array(
            'main_menu' => esc_html__('Main Menu', 'docy'),
        ));

        /*
         * Switch default core markup for search form, comment form, and comments
         * to output valid HTML5.
         */
        add_theme_support('html5', array(
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
        ));

        add_theme_support('align-wide');
        add_theme_support('editor-styles');
        add_editor_style('style-editor.css');
        add_theme_support('responsive-embeds');

        // Disable Sidebar widgets block editor
        if ( docy_opt('is_sidebar_editor') == 'classic' ) {
            add_filter('use_widgets_block_editor', '__return_false');
        }
    }
endif;
add_action('after_setup_theme', 'docy_setup');


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function docy_content_width () {
    $GLOBALS[ 'content_width' ] = apply_filters('docy_content_width', 1270);
}

add_action('after_setup_theme', 'docy_content_width', 0);


/**
 * Constants
 * Defining default asset paths
 */
define('DOCY_DIR_CSS', get_template_directory_uri() . '/assets/css');
define('DOCY_DIR_JS', get_template_directory_uri() . '/assets/js');
define('DOCY_DIR_VEND', get_template_directory_uri() . '/assets/vendors');
define('DOCY_DIR_IMG', get_template_directory_uri() . '/assets/img');
define('DOCY_DIR_FONT', get_template_directory_uri() . '/assets/fonts');

$my_theme = wp_get_theme('docy');
define('DOCY_VERSION', $my_theme->Version);

/**
 * Required plugins activation
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Enqueue scripts and styles.
 */
require get_template_directory() . '/inc/enqueue.php';

/**
 * Theme's helper functions
 */
require get_template_directory() . '/inc/classes/Docy_helper.php';

/**
 * Theme settings
 */
// Include CSF
require_once get_template_directory() . '/inc/csf/classes/setup.class.php';
if ( class_exists('CSF') ) {

    // Theme settings
	require_once get_template_directory() . '/inc/admin/settings-options.php';

    // Metaboxes
	require get_template_directory() . '/inc/meta/all-meta-boxes.php';
	require get_template_directory() . '/inc/meta/meta-register-login.php';
	require get_template_directory() . '/inc/meta/meta-post-format.php';
	require get_template_directory() . '/inc/meta/remove-meta.php';

}

// ACF widgets abd blocks
require get_template_directory() . '/inc/acf/widgets-blocks.php';

/**
 * Theme's filters and actions
 */
require get_template_directory() . '/inc/filter_actions.php';
require get_template_directory() . '/inc/woo_config.php';
require get_template_directory() . '/inc/ajax_actions.php';
require get_template_directory() . '/inc/reg_process.php';
require get_template_directory() . '/inc/cmgalaxy-api-auth.php';
require get_template_directory() . '/inc/cmgalaxy-sso.php';

/**
 * Classes
 */
require get_template_directory() . '/inc/classes/Docy_Mobile_Nav_Walker.php';
require get_template_directory() . '/inc/classes/Docy_Walker_Comment.php';
require get_template_directory() . '/inc/classes/Docy_Forum_Class.php';

//updater
require get_template_directory() . '/inc/classes/Docy_base.php';
require get_template_directory() . '/inc/classes/Docy_register_theme.php';
require get_template_directory() . '/inc/classes/Docy_update_checker.php';

/**
 * Admin notices
 */
if ( is_admin() ) {
    require_once get_template_directory() . '/inc/admin/notices/welcome-notice.php';
}

/**
 * Configure one click demo
 */
require get_template_directory() . '/inc/demo_config.php';

/**
 * Required plugins activation
 */
require get_template_directory() . '/inc/tgm/plugin_activation.php';

/**
 * Bootstrap Nav Walker
 */
require get_template_directory() . '/inc/classes/Docy_Nav_Walker.php';
require get_template_directory() . '/inc/classes/Docy_Walker_Docs.php';

/**
 * Register Sidebar Areas
 */
require get_template_directory() . '/inc/sidebars.php';

/**
 * Admin Page
 */
require get_template_directory() . '/inc/Admin.php';

/**
 * Dynamically fix broken localhost image paths in blog content
 * This is useful if posts were created in a local environment and moved to production
 */
function docy_fix_localhost_paths($content) {
    if (is_admin()) return $content;
    
    // Get current site URL
    $site_url = get_site_url();
    
    // Define the local path to search for
    $local_path = 'http://localhost/knowlege'; 
    
    // Replace hardcoded localhost paths with dynamic site URL
    $content = str_replace($local_path, $site_url, $content);
    
    return $content;
}
add_filter('the_content', 'docy_fix_localhost_paths', 99);
add_filter('the_excerpt', 'docy_fix_localhost_paths', 99);
add_filter('post_thumbnail_html', 'docy_fix_localhost_paths', 99);

/**
 * Helper: Check if post is restricted to logged-in users
 */
function cmg_is_post_restricted_to_logged_in($post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    if (!$post_id) {
        return false;
    }

    // 1. Exact match for your plugin (_rbcr_ keys)
    $rbcr_logged_in_only = get_post_meta($post_id, '_rbcr_logged_in_only', true);
    if ($rbcr_logged_in_only === '1' || $rbcr_logged_in_only === 1 || $rbcr_logged_in_only === true) {
        return true;
    }

    $rbcr_login_status = get_post_meta($post_id, '_rbcr_login_status', true);
    if (!empty($rbcr_login_status) && (strpos($rbcr_login_status, 'logged_in') !== false || $rbcr_login_status === 'logged_in_only')) {
        return true;
    }

    // 2. Content Control Plugin function checks
    if (function_exists('content_control_user_can_view_post')) {
        if (!content_control_user_can_view_post($post_id)) {
            return true;
        }
    }
    if (class_exists('\ContentControl\Models\Post')) {
        try {
            $cc_post = new \ContentControl\Models\Post($post_id);
            if (method_exists($cc_post, 'is_restricted') && $cc_post->is_restricted()) {
                return true;
            }
        } catch (\Exception $e) {}
    }

    // 3. Check all other common restriction meta keys
    $meta_keys = array(
        '_rbcr_logged_in_only',
        '_rbcr_login_status',
        '_content_control_restriction_type',
        '_content_control_user_status',
        '_content_control_who',
        'content_control_restriction_type',
        '_ca_content_control_settings',
        '_content_control_settings',
        'content_control_settings',
        '_content_control_post_restricted',
        'content_control',
        '_content_control',
        'restrict_access_type',
        '_restrict_access_type',
        '_restriction_type',
        'restriction_type'
    );

    foreach ($meta_keys as $key) {
        $val = get_post_meta($post_id, $key, true);
        if (!empty($val)) {
            if (is_string($val)) {
                $val_lower = strtolower($val);
                if (strpos($val_lower, 'logged_in') !== false || strpos($val_lower, 'logged-in') !== false || $val_lower === 'users' || $val_lower === 'loggedin') {
                    return true;
                }
            } elseif (is_array($val)) {
                $serialized = strtolower(serialize($val));
                if (strpos($serialized, 'logged_in') !== false || strpos($serialized, 'logged-in') !== false || strpos($serialized, 'logged_in_only') !== false) {
                    return true;
                }
            }
        }
    }

    // 3. Check all meta of post for Content Control settings
    $all_meta = get_post_meta($post_id);
    if (!empty($all_meta) && is_array($all_meta)) {
        foreach ($all_meta as $m_key => $m_values) {
            if (strpos($m_key, 'content_control') !== false || strpos($m_key, 'restrict') !== false) {
                foreach ((array)$m_values as $m_val) {
                    $m_val_lower = strtolower(is_string($m_val) ? $m_val : serialize($m_val));
                    if (strpos($m_val_lower, 'logged_in') !== false || strpos($m_val_lower, 'logged-in') !== false) {
                        return true;
                    }
                }
            }
        }
    }

    return false;
}

/**
 * Filter the_content: If single post is restricted to logged-in users and user is not logged in, show upgrade modal
 */
function cmg_render_restricted_post_modal($content) {
    if (is_admin()) {
        return $content;
    }

    // Only replace content on singular single post views, not archives or feeds
    if (!is_singular()) {
        return $content;
    }

    $post_id = get_the_ID();
    if (!$post_id) {
        return $content;
    }

    // If user is already logged in, show normal content (do not show modal)
    if (is_user_logged_in()) {
        return $content;
    }

    // If post is restricted to logged-in users only
    if (cmg_is_post_restricted_to_logged_in($post_id)) {
        ob_start();
        get_template_part('template-parts/modal-upgrade');
        return ob_get_clean();
    }

    return $content;
}
add_filter('the_content', 'cmg_render_restricted_post_modal', 999);

/**
 * Filter the_excerpt: Prevent modal markup from polluting archive/category excerpts
 */
function cmg_filter_restricted_excerpt($excerpt) {
    $post_id = get_the_ID();
    if ($post_id && !is_user_logged_in() && cmg_is_post_restricted_to_logged_in($post_id)) {
        return 'Unlock full access to this premium guide and resource by upgrading to a paid account.';
    }
    return $excerpt;
}
add_filter('the_excerpt', 'cmg_filter_restricted_excerpt', 1);

/**
 * Also hook into Content Control plugin filter hooks if active
 */
add_filter('content_control/content/replacement_content', function($replacement, $post_id) {
    if (!is_user_logged_in()) {
        ob_start();
        get_template_part('template-parts/modal-upgrade');
        return ob_get_clean();
    }
    return $replacement;
}, 999, 2);

add_filter('content_control_restriction_message', function($message) {
    if (!is_user_logged_in()) {
        ob_start();
        get_template_part('template-parts/modal-upgrade');
        return ob_get_clean();
    }
    return $message;
}, 999);

/**
 * Prevent Content Control / RBCR / Restriction plugins from redirecting single posts,
 * allowing single.php to render the teaser + upgrade modal directly.
 */
add_filter('content_control/prevent_redirect', '__return_true', 999);
add_filter('content_control/do_redirect', '__return_false', 999);
add_filter('rbcr_do_redirect', '__return_false', 999);
add_filter('rbcr_redirect_url', '__return_false', 999);
add_filter('rbcr_allow_redirect', '__return_false', 999);

add_action('template_redirect', function() {
    if (is_singular() && !is_user_logged_in() && cmg_is_post_restricted_to_logged_in(get_the_ID())) {
        global $wp_filter;
        if (isset($wp_filter['template_redirect']) && isset($wp_filter['template_redirect']->callbacks)) {
            foreach ($wp_filter['template_redirect']->callbacks as $priority => $callbacks) {
                foreach ($callbacks as $idx => $callback) {
                    $func_name = '';
                    if (is_array($callback['function'])) {
                        $class = is_object($callback['function'][0]) ? get_class($callback['function'][0]) : $callback['function'][0];
                        $method = $callback['function'][1];
                        $func_name = $class . '::' . $method;
                    } elseif (is_string($callback['function'])) {
                        $func_name = $callback['function'];
                    }

                    if (stripos($func_name, 'rbcr') !== false || stripos($func_name, 'content_control') !== false || stripos($func_name, 'restrict') !== false) {
                        unset($wp_filter['template_redirect']->callbacks[$priority][$idx]);
                    }
                }
            }
        }
    }
}, 0);


/**
 * Shortcode [cmg_upgrade_modal]
 */
add_shortcode('cmg_upgrade_modal', function($atts) {
    if (is_user_logged_in()) {
        return '';
    }
    ob_start();
    get_template_part('template-parts/modal-upgrade');
    return ob_get_clean();
});

/**
 * Debugger in wp_footer (Only on Single Article Pages)
 */
add_action('wp_footer', function() {
    // Only check and log on single article pages
    if (!is_singular()) {
        return;
    }

    $is_logged_in = is_user_logged_in();
    $post_id = get_the_ID();
    $all_meta = $post_id ? get_post_meta($post_id) : array();
    $is_restricted = $post_id ? cmg_is_post_restricted_to_logged_in($post_id) : false;
    $post_title = $post_id ? get_the_title($post_id) : '';
    ?>
    <script>
        console.group('%c🔐 CMGalaxy Single Post Restriction Debugger', 'color: #2563eb; font-size: 14px; font-weight: bold;');
        console.log('📌 Post ID:', <?php echo json_encode($post_id); ?>);
        console.log('📄 Post Title:', <?php echo json_encode($post_title); ?>);
        console.log('👤 User Logged In:', <?php echo $is_logged_in ? 'true' : 'false'; ?>);
        console.log('🚫 Is Restricted to Logged-in:', <?php echo $is_restricted ? 'true' : 'false'; ?>);
        console.log('📦 All Meta Keys for this Post:', <?php echo json_encode($all_meta); ?>);
        <?php if ($is_restricted && !$is_logged_in): ?>
        console.log('%c✅ Status: Restricted Post - Paywall Rendered on Single Page!', 'color: green; font-weight: bold; font-size: 12px;');
        <?php elseif ($is_logged_in): ?>
        console.log('%cℹ️ Status: User is logged in, full content displayed.', 'color: #64748b;');
        <?php else: ?>
        console.log('%cℹ️ Status: This post is not restricted (public article).', 'color: #64748b;');
        <?php endif; ?>
        console.groupEnd();
    </script>
    <?php
}, 9999);

/**
 * Hide WordPress Admin Bar for all regular users / subscribers (only show for Administrator)
 */
add_filter('show_admin_bar', function($show) {
    if (!current_user_can('administrator')) {
        return false;
    }
    return $show;
});

/**
 * Block /wp-admin/ access for non-admin users and redirect to home
 */
add_action('admin_init', function() {
    if (is_user_logged_in() && !current_user_can('administrator') && !wp_doing_ajax()) {
        wp_safe_redirect(home_url('/'));
        exit;
    }
});

/**
 * Redirect user to home or article after login instead of wp-admin
 */
add_filter('login_redirect', function($redirect_to, $request, $user) {
    if (isset($user->roles) && is_array($user->roles)) {
        if (!in_array('administrator', $user->roles)) {
            return !empty($request) ? $request : home_url('/');
        }
    }
    return $redirect_to;
}, 10, 3);

/**
 * Automatically load page-signin.php on /signin/ or /login/ URL (No 404 error)
 */
add_filter('template_include', function($template) {
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $path = trim(parse_url($request_uri, PHP_URL_PATH), '/');

    if ($path === 'signin' || $path === 'login') {
        $signin_template = get_template_directory() . '/page-signin.php';
        if (file_exists($signin_template)) {
            global $wp_query;
            $wp_query->is_404 = false;
            status_header(200);
            return $signin_template;
        }
    }

    return $template;
}, 99);



