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

    $api_url = 'https://api.cmgalaxy.com/api/v2/authentication/login/';

    // 1. Try with email payload
    $payload = array(
        'email'    => $username,
        'password' => $password
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

    // 2. If email failed, try with username payload
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) >= 400) {
        $payload_alt = array(
            'username' => $username,
            'password' => $password
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

    // Helper to find data inside response
    $find_val = function($keys) use ($data) {
        foreach ((array)$keys as $k) {
            if (!empty($data[$k])) return $data[$k];
            if (!empty($data['user'][$k])) return $data['user'][$k];
            if (!empty($data['data'][$k])) return $data['data'][$k];
        }
        return '';
    };

    // Extract user details
    $user_email = $find_val(array('email', 'user_email', 'username'));
    if (empty($user_email) && is_email($username)) {
        $user_email = $username;
    }

    $first_name = $find_val(array('first_name', 'firstName', 'fname'));
    $last_name  = $find_val(array('last_name', 'lastName', 'lname'));
    $full_name  = $find_val(array('name', 'full_name', 'fullName', 'display_name'));
    if (empty($first_name) && !empty($full_name)) {
        $name_parts = explode(' ', trim($full_name), 2);
        $first_name = $name_parts[0];
        $last_name  = isset($name_parts[1]) ? $name_parts[1] : '';
    }

    $phone_number = $find_val(array('phone', 'phone_number', 'phoneNumber', 'mobile', 'mobile_number', 'contact', 'contact_number', 'number'));
    $account_type = $find_val(array('account_type', 'accountType', 'user_type', 'userType', 'role', 'type'));
    if (empty($account_type)) {
        $account_type = 'Client';
    }

    // Default Plan Status: Paid
    $plan_status = $find_val(array('plan_status', 'planStatus', 'plan', 'subscription_status', 'status'));
    if (empty($plan_status) || strtolower($plan_status) !== 'demo') {
        $plan_status = 'paid';
    } else {
        $plan_status = strtolower($plan_status);
    }

    if (empty($user_email) || !is_email($user_email)) {
        return new WP_Error('invalid_email', 'Invalid email address returned from API.');
    }

    // Find or create WordPress user
    $wp_user = get_user_by('email', $user_email);
    if (!$wp_user) {
        $uname = sanitize_user(strstr($user_email, '@', true));
        $base_uname = $uname;
        $i = 1;
        while (username_exists($uname)) {
            $uname = $base_uname . $i;
            $i++;
        }

        $random_pass = wp_generate_password(24, true);
        $new_user_id = wp_create_user($uname, $random_pass, $user_email);

        if (is_wp_error($new_user_id)) {
            return $new_user_id;
        }

        $wp_user = get_user_by('id', $new_user_id);
    }

    if ($wp_user && !is_wp_error($wp_user)) {
        $uid = $wp_user->ID;

        // Keep local password in sync
        wp_set_password($password, $uid);

        // Update User Meta
        if (!empty($first_name)) update_user_meta($uid, 'first_name', sanitize_text_field($first_name));
        if (!empty($last_name)) update_user_meta($uid, 'last_name', sanitize_text_field($last_name));
        if (!empty($full_name)) {
            wp_update_user(array('ID' => $uid, 'display_name' => sanitize_text_field($full_name)));
        }

        if (!empty($phone_number)) {
            update_user_meta($uid, 'phone_number', sanitize_text_field($phone_number));
        }

        if (!empty($account_type)) {
            update_user_meta($uid, 'account_type', sanitize_text_field($account_type));
        }

        update_user_meta($uid, 'plan_status', sanitize_text_field($plan_status));
        update_user_meta($uid, '_cmg_plan_status', sanitize_text_field($plan_status));

        $token = $find_val(array('token', 'access_token', 'accessToken', 'jwt', 'jwt_token'));
        if (!empty($token)) {
            update_user_meta($uid, '_cmg_api_token', sanitize_text_field($token));
        }

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
 * WordPress Admin Users Table Columns: Name, Email, Phone, Account Type, Plan
 * =========================================================================
 */
add_filter('manage_users_columns', function($columns) {
    $new_columns = array();
    foreach ($columns as $key => $title) {
        $new_columns[$key] = $title;
        if ($key === 'email') {
            $new_columns['cmg_phone']        = 'Phone Number';
            $new_columns['cmg_account_type'] = 'Account Type';
            $new_columns['cmg_plan_status']  = 'Plan Status';
        }
    }
    if (!isset($new_columns['cmg_plan_status'])) {
        $new_columns['cmg_phone']        = 'Phone Number';
        $new_columns['cmg_account_type'] = 'Account Type';
        $new_columns['cmg_plan_status']  = 'Plan Status';
    }
    return $new_columns;
});

add_filter('manage_users_custom_column', function($value, $column_name, $user_id) {
    if ($column_name === 'cmg_phone') {
        $phone = get_user_meta($user_id, 'phone_number', true);
        return !empty($phone) ? esc_html($phone) : '<span style="color:#94a3b8;">—</span>';
    }

    if ($column_name === 'cmg_account_type') {
        $type = get_user_meta($user_id, 'account_type', true);
        if (empty($type)) {
            $type = 'Client';
        }
        return '<span style="background:#f1f5f9; color:#334155; padding:3px 8px; border-radius:6px; font-size:12px; font-weight:600;">' . esc_html($type) . '</span>';
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
    $phone        = get_user_meta($user->ID, 'phone_number', true);
    $account_type = get_user_meta($user->ID, 'account_type', true);
    $plan_status  = get_user_meta($user->ID, 'plan_status', true);
    if (empty($plan_status)) $plan_status = 'paid';
    if (empty($account_type)) $account_type = 'Client';
    ?>
    <h3 style="margin-top:25px;">CMGalaxy Account & Subscription Details</h3>
    <table class="form-table">
        <tr>
            <th><label for="cmg_phone">Phone Number</label></th>
            <td>
                <input type="text" name="cmg_phone" id="cmg_phone" value="<?php echo esc_attr($phone); ?>" class="regular-text" placeholder="+1 234 567 8900" /><br />
                <span class="description">User's contact phone number from CMGalaxy API.</span>
            </td>
        </tr>
        <tr>
            <th><label for="cmg_account_type">Account Type</label></th>
            <td>
                <input type="text" name="cmg_account_type" id="cmg_account_type" value="<?php echo esc_attr($account_type); ?>" class="regular-text" placeholder="Client" /><br />
                <span class="description">e.g. Client, Partner, Enterprise.</span>
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

    if (isset($_POST['cmg_phone'])) {
        update_user_meta($user_id, 'phone_number', sanitize_text_field($_POST['cmg_phone']));
    }
    if (isset($_POST['cmg_account_type'])) {
        update_user_meta($user_id, 'account_type', sanitize_text_field($_POST['cmg_account_type']));
    }
    if (isset($_POST['cmg_plan_status'])) {
        $plan = sanitize_text_field($_POST['cmg_plan_status']);
        update_user_meta($user_id, 'plan_status', $plan);
        update_user_meta($user_id, '_cmg_plan_status', $plan);
    }
}
