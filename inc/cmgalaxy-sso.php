<?php
/**
 * CMGalaxy Single Sign-On (SSO) & JWT Cookie Authentication
 * 
 * Automatically authenticates users from CMGalaxy Main App (app.cmgalaxy.com / api.cmgalaxy.com)
 * when a shared JWT cookie or token is present in the browser.
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Configurable Cookie Names & Settings
if (!defined('CMG_SSO_COOKIE_NAMES')) {
    define('CMG_SSO_COOKIE_NAMES', serialize(array(
        'cmg_token',
        'cmg_jwt',
        'access_token',
        'token',
        'jwt_token',
        'auth_token',
        'session_token'
    )));
}

/**
 * Helper: Extract JWT Token from Cookies, Header, or URL Parameter
 */
function cmg_sso_get_token() {
    // A. Check URL query parameters (e.g. ?auth_token=... or ?cmg_token=...)
    if (!empty($_GET['auth_token'])) {
        return sanitize_text_field($_GET['auth_token']);
    }
    if (!empty($_GET['cmg_token'])) {
        return sanitize_text_field($_GET['cmg_token']);
    }

    // B. Check Authorization Header (Bearer token)
    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return $matches[1];
        }
    }

    // C. Check Cookies
    $cookie_names = unserialize(CMG_SSO_COOKIE_NAMES);
    foreach ($cookie_names as $cookie_name) {
        if (!empty($_COOKIE[$cookie_name])) {
            return sanitize_text_field($_COOKIE[$cookie_name]);
        }
    }

    return null;
}

/**
 * Helper: Safely decode JWT payload without external library
 */
function cmg_sso_decode_jwt($jwt) {
    if (empty($jwt) || !is_string($jwt)) {
        return false;
    }

    $parts = explode('.', $jwt);
    if (count($parts) !== 3) {
        return false;
    }

    $payload_b64 = $parts[1];
    // Base64URL to Base64 decode
    $remainder = strlen($payload_b64) % 4;
    if ($remainder) {
        $padlen = 4 - $remainder;
        $payload_b64 .= str_repeat('=', $padlen);
    }
    $payload_b64 = strtr($payload_b64, '-_', '+/');
    $payload_json = base64_decode($payload_b64);

    if (!$payload_json) {
        return false;
    }

    $payload = json_decode($payload_json, true);
    return is_array($payload) ? $payload : false;
}

/**
 * Helper: Check if current visitor has a valid SSO session or paid token
 */
function cmg_sso_is_authenticated_user() {
    if (is_user_logged_in()) {
        return true;
    }

    $token = cmg_sso_get_token();
    if (!$token) {
        return false;
    }

    $payload = cmg_sso_decode_jwt($token);
    if (!$payload) {
        return false;
    }

    // Check expiration if exp claim is present
    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false; // Token expired
    }

    return true;
}

/**
 * Auto-login WordPress user on init if a valid JWT token is found
 */
add_action('init', function() {
    if (is_admin() || is_user_logged_in()) {
        return;
    }

    $token = cmg_sso_get_token();
    if (!$token) {
        return;
    }

    $payload = cmg_sso_decode_jwt($token);
    if (!$payload) {
        return;
    }

    // Check expiration
    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return;
    }

    // Extract email or username from JWT payload
    $email = '';
    if (!empty($payload['email'])) {
        $email = sanitize_email($payload['email']);
    } elseif (!empty($payload['user_email'])) {
        $email = sanitize_email($payload['user_email']);
    } elseif (!empty($payload['username']) && is_email($payload['username'])) {
        $email = sanitize_email($payload['username']);
    }

    if (empty($email) || !is_email($email)) {
        return;
    }

    // Look for existing user or create one
    $user = get_user_by('email', $email);
    if (!$user) {
        $username = sanitize_user(strstr($email, '@', true));
        // Ensure username is unique
        $base_username = $username;
        $i = 1;
        while (username_exists($username)) {
            $username = $base_username . $i;
            $i++;
        }

        $random_password = wp_generate_password(24, true);
        $user_id = wp_create_user($username, $random_password, $email);

        if (is_wp_error($user_id)) {
            return;
        }

        // Add first/last name if in token
        if (!empty($payload['first_name'])) {
            update_user_meta($user_id, 'first_name', sanitize_text_field($payload['first_name']));
        }
        if (!empty($payload['last_name'])) {
            update_user_meta($user_id, 'last_name', sanitize_text_field($payload['last_name']));
        }

        $user = get_user_by('id', $user_id);
    }

    if ($user && !is_wp_error($user)) {
        // Automatically sign in the user
        wp_clear_auth_cookie();
        wp_set_current_user($user->ID);
        wp_set_auth_cookie($user->ID, true);
        do_action('wp_login', $user->user_login, $user);
    }
}, 1);
