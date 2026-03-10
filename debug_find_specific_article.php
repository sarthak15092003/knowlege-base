<?php
require_once('../../../wp-load.php');
global $wpdb;
$target = 'Sharing Reports (Email, WhatsApp & In-App)';
$query = $wpdb->prepare("SELECT ID, post_title, post_type, post_status FROM $wpdb->posts WHERE post_title = %s", $target);
$results = $wpdb->get_results($query);

echo "SEARCH FOR EXACT TITLE: '$target'\n";
if ($results) {
    foreach ($results as $row) {
        echo "FOUND! ID: {$row->ID} | Type: {$row->post_type} | Status: {$row->post_status}\n";
    }
} else {
    echo "NOT FOUND by exact title.\n";
    echo "Similar titles:\n";
    $query_similar = $wpdb->prepare("SELECT post_title FROM $wpdb->posts WHERE post_title LIKE %s LIMIT 10", '%Sharing%');
    $results_sim = $wpdb->get_results($query_similar);
    foreach ($results_sim as $row) {
        echo " - " . $row->post_title . "\n";
    }
}
