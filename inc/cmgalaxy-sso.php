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
    if (!empty($_GET['auth_token'])) {
        return sanitize_text_field($_GET['auth_token']);
    }
    if (!empty($_GET['cmg_token'])) {
        return sanitize_text_field($_GET['cmg_token']);
    }

    if (!empty($_SERVER['HTTP_AUTHORIZATION'])) {
        if (preg_match('/Bearer\s(\S+)/', $_SERVER['HTTP_AUTHORIZATION'], $matches)) {
            return $matches[1];
        }
    }

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

    if (isset($payload['exp']) && $payload['exp'] < time()) {
        return false;
    }

    return true;
}
