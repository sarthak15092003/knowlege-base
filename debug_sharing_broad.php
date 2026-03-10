<?php
require_once('../../../wp-load.php');
global $wpdb;
$query = "SELECT ID, post_title, post_type, post_status FROM $wpdb->posts WHERE post_title LIKE '%Sharing%'";
$results = $wpdb->get_results($query);

echo "SEARCH FOR 'Sharing' IN TITLES:\n";
foreach ($results as $row) {
    echo "ID: {$row->ID} | Title: {$row->post_title} | Type: {$row->post_type} | Status: {$row->post_status}\n";
}
