<?php
/**
 * CMGalaxy Direct API Authentication
 * 
 * Authenticates users against the CMGalaxy API (https://api.cmgalaxy.com/api/v2/authentication/login/)
 * when logging in via the Knowledge Base /signin/ form.
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Hook into WordPress authentication pipeline
 */
add_filter('authenticate', function($user, $username, $password) {
    // If already authenticated or missing credentials, let WordPress handle it
    if ($user instanceof WP_User || empty($username) || empty($password)) {
        return $user;
    }

    // 1. Prepare API Request to CMGalaxy
    $api_url = 'https://api.cmgalaxy.com/api/v2/authentication/login/';

    // Prepare JSON payload (Supports both email & username field names)
    $payload = array(
        'email'    => $username,
        'password' => $password
    );

    $response = wp_remote_post($api_url, array(
        'method'      => 'POST',
        'timeout'     => 15,
        'redirection' => 5,
        'httpversion' => '1.1',
        'blocking'    => true,
        'headers'     => array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json'
        ),
        'body'        => json_encode($payload),
        'sslverify'   => true
    ));

    // If email failed or network error, also try username payload
    if (is_wp_error($response) || wp_remote_retrieve_response_code($response) >= 400) {
        $payload_alt = array(
            'username' => $username,
            'password' => $password
        );
        $response_alt = wp_remote_post($api_url, array(
            'method'      => 'POST',
            'timeout'     => 15,
            'headers'     => array(
                'Content-Type' => 'application/json',
                'Accept'       => 'application/json'
            ),
            'body'        => json_encode($payload_alt),
            'sslverify'   => true
        ));

        if (!is_wp_error($response_alt) && wp_remote_retrieve_response_code($response_alt) < 400) {
            $response = $response_alt;
        }
    }

    // Check if API returned successful login (HTTP 200/201)
    if (!is_wp_error($response)) {
        $status_code = wp_remote_retrieve_response_code($response);
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);

        if (($status_code === 200 || $status_code === 201) && !empty($data)) {
            // Extract user email from response
            $user_email = '';
            if (!empty($data['email'])) {
                $user_email = $data['email'];
            } elseif (!empty($data['user']['email'])) {
                $user_email = $data['user']['email'];
            } elseif (!empty($data['data']['email'])) {
                $user_email = $data['data']['email'];
            } elseif (is_email($username)) {
                $user_email = $username;
            }

            if (!empty($user_email) && is_email($user_email)) {
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

                    if (!is_wp_error($new_user_id)) {
                        // Save any token or meta if returned
                        if (!empty($data['token']) || !empty($data['access_token']) || !empty($data['jwt'])) {
                            $token = !empty($data['token']) ? $data['token'] : (!empty($data['access_token']) ? $data['access_token'] : $data['jwt']);
                            update_user_meta($new_user_id, '_cmg_api_token', sanitize_text_field($token));
                        }

                        $wp_user = get_user_by('id', $new_user_id);
                    }
                } else {
                    // Update user's password locally so WP stays in sync
                    wp_set_password($password, $wp_user->ID);
                }

                if ($wp_user && !is_wp_error($wp_user)) {
                    // Return authenticated user object to complete login
                    return $wp_user;
                }
            }
        }
    }

    // 3. Fallback: If API did not match, allow standard WordPress authentication (for admin accounts)
    return $user;
}, 20, 3);
