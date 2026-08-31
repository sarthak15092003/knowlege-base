<?php
/**
 * CMGalaxy Direct API Authentication & User Data Storage
 * 
 * Authenticates users against the CMGalaxy API (https://api.cmgalaxy.com/api/v2/authentication/login/)
 * and stores Email, Name, Phone Number, Account Type, and Plan Status (Paid / Demo).
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Core Helper: Authenticate Credentials with CMGalaxy API and Return WP_User
 */
function cmg_authenticate_with_api($username, $password) {
    $username = trim((string)$username);
    $password = (string)$password;

    if (empty($username) || empty($password)) {
        return new WP_Error('empty_credentials', 'Email and password are required.');
    }

    $api_url = 'https://staging-api.cmgalaxy.com/api/v2/authentication/simple-login/';

    // Exact Payload required by CMGalaxy API
    $payload = array(
        'user_name' => $username,
        'password'  => $password,
        'website'   => 'platform.cmgalaxy.com'
    );

    $headers = array(
        'Content-Type' => 'application/json',
        'Accept'       => 'application/json',
        'Origin'       => 'https://platform.cmgalaxy.com',
        'Referer'      => 'https://platform.cmgalaxy.com/'
    );

    $response = wp_remote_post($api_url, array(
        'method'      => 'POST',
        'timeout'     => 15,
        'redirection' => 5,
        'httpversion' => '1.1',
        'blocking'    => true,
        'headers'     => $headers,
        'body'        => json_encode($payload),
        'sslverify'   => true
    ));

    // Fallback: try with email field if user_name alone didn't match
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) >= 400) {
        $payload_alt = array(
            'email'    => $username,
            'password' => $password,
            'website'  => 'platform.cmgalaxy.com'
        );
        $response_alt = wp_remote_post($api_url, array(
            'method'      => 'POST',
            'timeout'     => 15,
            'headers'     => $headers,
            'body'        => json_encode($payload_alt),
            'sslverify'   => true
        ));

        if (!is_wp_error($response_alt) && wp_remote_retrieve_response_code($response_alt) < 400) {
            $response = $response_alt;
        }
    }

    if (is_wp_error($response)) {
        return $response;
    }

    $status_code = wp_remote_retrieve_response_code($response);
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if ($status_code >= 400 || empty($data)) {
        $err_msg = 'Invalid email or password. Please try again.';
        if (!empty($data['message'])) {
            $err_msg = $data['message'];
        } elseif (!empty($data['detail'])) {
            $err_msg = $data['detail'];
        } elseif (!empty($data['error'])) {
            $err_msg = is_string($data['error']) ? $data['error'] : 'Invalid credentials.';
        }
        return new WP_Error('api_auth_failed', $err_msg, array('status' => $status_code, 'api_response' => $data, 'raw_body' => $body));
    }

    // Extract user item from "data": [ { "username": "...", "brand_name": "...", "first_name": "..." } ]
    $user_item = array();
    if (isset($data['data']) && is_array($data['data'])) {
        if (isset($data['data'][0]) && is_array($data['data'][0])) {
            $user_item = $data['data'][0];
        } else {
            $user_item = $data['data'];
        }
    } else {
        $user_item = is_array($data) ? $data : array();
    }

    // 1. Extract Username / Email (e.g. "ritik.mcc@maildrop.cc")
    $user_email = !empty($user_item['username']) ? trim($user_item['username']) : (!empty($user_item['email']) ? trim($user_item['email']) : '');
    if (empty($user_email) && is_email($username)) {
        $user_email = $username;
    }

    // 2. Extract Brand Name (e.g. "finserv")
    $brand_name = !empty($user_item['brand_name']) ? trim($user_item['brand_name']) : '';

    // 3. Extract First Name (e.g. "sbjbsubxisbxs")
    $first_name = !empty($user_item['first_name']) ? trim($user_item['first_name']) : '';

    if (empty($user_email) || !is_email($user_email)) {
        return new WP_Error('invalid_email', 'Invalid email address returned from API.');
    }

    // Determine Username to use (Prefer brand_name if available)
    $preferred_username = !empty($brand_name) ? sanitize_user($brand_name, true) : sanitize_user(strstr($user_email, '@', true), true);
    if (empty($preferred_username)) {
        $preferred_username = 'cmg_user';
    }

    // Find or create WordPress user
    $wp_user = get_user_by('email', $user_email);
    if (!$wp_user) {
        $wp_user = get_user_by('login', $user_email);
    }

    if (!$wp_user) {
        $uname = $preferred_username;
        $base_uname = $uname;
        $i = 1;
        while (username_exists($uname)) {
            $uname = $base_uname . '_' . $i;
            $i++;
        }

        $random_pass = wp_generate_password(24, true);
        $new_user_id = wp_create_user($uname, $random_pass, $user_email);

        if (is_wp_error($new_user_id)) {
            return $new_user_id;
        }

        $wp_user = get_user_by('id', $new_user_id);
        if ($wp_user) {
            $wp_user->set_role('subscriber');
        }
    }

    if ($wp_user && !is_wp_error($wp_user)) {
        $uid = $wp_user->ID;

        // Keep local password in sync
        wp_set_password($password, $uid);

        // Display Name priority: brand_name > first_name > username
        $display_title = !empty($brand_name) ? $brand_name : (!empty($first_name) ? $first_name : $preferred_username);

        wp_update_user(array(
            'ID'           => $uid,
            'display_name' => sanitize_text_field($display_title),
            'nickname'     => sanitize_text_field($display_title),
            'first_name'   => sanitize_text_field($first_name)
        ));

        // Store only the required fields: brand_name and first_name
        if (!empty($brand_name)) {
            update_user_meta($uid, 'brand_name', sanitize_text_field($brand_name));
        }
        if (!empty($first_name)) {
            update_user_meta($uid, 'first_name', sanitize_text_field($first_name));
        }

        // Set active plan status for site compatibility
        update_user_meta($uid, 'plan_status', 'paid');
        update_user_meta($uid, '_cmg_plan_status', 'paid');

        return $wp_user;
    }

    return new WP_Error('user_creation_failed', 'Failed to authenticate user.');
}

/**
 * Standard WordPress Authenticate Filter Hook
 */
add_filter('authenticate', function($user, $username, $password) {
    if ($user instanceof WP_User || empty($username) || empty($password)) {
        return $user;
    }

    $auth_res = cmg_authenticate_with_api($username, $password);
    if ($auth_res instanceof WP_User) {
        return $auth_res;
    }

    return $user;
}, 20, 3);

/**
 * AJAX Login Handler (Keeps user on /signin/ page without redirecting to wp-login.php)
 */
function cmg_handle_ajax_login() {
    $email    = !empty($_POST['email']) ? sanitize_text_field($_POST['email']) : '';
    $password = !empty($_POST['password']) ? $_POST['password'] : '';
    $redirect = !empty($_POST['redirect_to']) ? esc_url_raw($_POST['redirect_to']) : home_url('/');

    if (empty($email) || empty($password)) {
        wp_send_json_error(array('message' => 'Please enter both email and password.'));
    }

    // 1. Try API Auth
    $user = cmg_authenticate_with_api($email, $password);

    // 2. Fallback to Local WordPress Authentication (Supports both Email and Username)
    if (is_wp_error($user) || !($user instanceof WP_User)) {
        // A. Check by email in WP DB
        if (is_email($email)) {
            $wp_user_by_email = get_user_by('email', $email);
            if ($wp_user_by_email && wp_check_password($password, $wp_user_by_email->user_pass, $wp_user_by_email->ID)) {
                $user = $wp_user_by_email;
            }
        }

        // B. Check by username in WP DB
        if (is_wp_error($user) || !($user instanceof WP_User)) {
            $wp_user_by_login = get_user_by('login', $email);
            if ($wp_user_by_login && wp_check_password($password, $wp_user_by_login->user_pass, $wp_user_by_login->ID)) {
                $user = $wp_user_by_login;
            }
        }
    }

    if ($user instanceof WP_User) {
        // Clear old cookies & set new authentication cookies
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);

        wp_send_json_success(array(
            'redirect' => $redirect,
            'message'  => 'Signed in successfully! Redirecting...'
        ));
    } else {
        $err_msg = 'Invalid email or password. Please try again.';
        $debug_info = array();
        if (is_wp_error($user)) {
            $err_msg = $user->get_error_message();
            $debug_info = $user->get_error_data();
        }
        wp_send_json_error(array(
            'message' => $err_msg,
            'debug'   => $debug_info
        ));
    }
}
add_action('wp_ajax_nopriv_cmg_ajax_login', 'cmg_handle_ajax_login');
add_action('wp_ajax_cmg_ajax_login', 'cmg_handle_ajax_login');

/**
 * =========================================================================
 * WordPress Admin Users Table Columns: Brand Name, First Name, Plan
 * =========================================================================
 */
add_filter('manage_users_columns', function($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'email') {
            $new_columns['cmg_brand_name'] = 'Brand Name';
            $new_columns['cmg_first_name'] = 'First Name';
            $new_columns['cmg_plan_status'] = 'Plan Status';
        }
    }
    if (!isset($new_columns['cmg_plan_status'])) {
        $new_columns['cmg_brand_name'] = 'Brand Name';
        $new_columns['cmg_first_name'] = 'First Name';
        $new_columns['cmg_plan_status'] = 'Plan Status';
    }
    return $new_columns;
});

add_filter('manage_users_custom_column', function($value, $column_name, $user_id) {
    if ($column_name === 'cmg_brand_name') {
        $brand = get_user_meta($user_id, 'brand_name', true);
        return !empty($brand) ? '<strong style="color:#0f172a;">' . esc_html($brand) . '</strong>' : '<span style="color:#94a3b8;">—</span>';
    }

    if ($column_name === 'cmg_first_name') {
        $fname = get_user_meta($user_id, 'first_name', true);
        if (empty($fname)) {
            $user = get_userdata($user_id);
            $fname = $user ? $user->first_name : '';
        }
        return !empty($fname) ? esc_html($fname) : '<span style="color:#94a3b8;">—</span>';
    }

    if ($column_name === 'cmg_plan_status') {
        $plan = get_user_meta($user_id, 'plan_status', true);
        if (empty($plan)) {
            $plan = 'paid';
        }

        if (strtolower($plan) === 'paid') {
            return '<span style="background:#dcfce7; color:#15803d; border:1px solid #86efac; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">✓ PAID</span>';
        } elseif (strtolower($plan) === 'demo') {
            return '<span style="background:#dbeafe; color:#1d4ed8; border:1px solid #93c5fd; padding:3px 10px; border-radius:999px; font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">DEMO</span>';
        } else {
            return '<span style="background:#f3f4f6; color:#4b5563; padding:3px 8px; border-radius:6px; font-size:11px; font-weight:600;">' . esc_html(strtoupper($plan)) . '</span>';
        }
    }

    return $value;
}, 10, 3);

add_action('show_user_profile', 'cmg_render_user_profile_fields');
add_action('edit_user_profile', 'cmg_render_user_profile_fields');

function cmg_render_user_profile_fields($user) {
    $brand_name  = get_user_meta($user->ID, 'brand_name', true);
    $plan_status = get_user_meta($user->ID, 'plan_status', true);
    if (empty($plan_status)) $plan_status = 'paid';
    ?>
    <h3 style="margin-top:25px;">CMGalaxy Account Details</h3>
    <table class="form-table">
        <tr>
            <th><label for="cmg_brand_name">Brand Name</label></th>
            <td>
                <input type="text" name="cmg_brand_name" id="cmg_brand_name" value="<?php echo esc_attr($brand_name); ?>" class="regular-text" placeholder="Brand Name" /><br />
                <span class="description">User's Brand Name from CMGalaxy API.</span>
            </td>
        </tr>
        <tr>
            <th><label for="cmg_plan_status">Plan Status</label></th>
            <td>
                <select name="cmg_plan_status" id="cmg_plan_status">
                    <option value="paid" <?php selected($plan_status, 'paid'); ?>>Paid (Full Access)</option>
                    <option value="demo" <?php selected($plan_status, 'demo'); ?>>Demo (Limited Access)</option>
                </select><br />
                <span class="description">Select whether this user has Paid access or Demo access.</span>
            </td>
        </tr>
    </table>
    <?php
}

add_action('personal_options_update', 'cmg_save_user_profile_fields');
add_action('edit_user_profile_update', 'cmg_save_user_profile_fields');

function cmg_save_user_profile_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['cmg_brand_name'])) {
        update_user_meta($user_id, 'brand_name', sanitize_text_field($_POST['cmg_brand_name']));
    }
    if (isset($_POST['cmg_plan_status'])) {
        $plan = sanitize_text_field($_POST['cmg_plan_status']);
        update_user_meta($user_id, 'plan_status', $plan);
        update_user_meta($user_id, '_cmg_plan_status', $plan);
    }
}

