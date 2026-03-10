<?php
require_once('../../../wp-load.php');
global $wpdb;
$query = "SELECT ID, post_title, post_status, post_type FROM $wpdb->posts WHERE post_title LIKE '%WhatsApp%' OR post_content LIKE '%WhatsApp%'";
$results = $wpdb->get_results($query);

echo "SEARCHING DATABASE FOR 'WhatsApp':\n";
foreach ($results as $row) {
    echo "ID: " . $row->ID . " | Title: " . $row->post_title . " | Status: " . $row->post_status . " | Type: " . $row->post_type . "\n";
}
