<?php
require_once('../../../wp-load.php');
global $wpdb;
$query = "SELECT ID, post_title FROM $wpdb->posts WHERE post_title LIKE '%Share%' AND post_status = 'publish'";
$results = $wpdb->get_results($query);

echo "SEARCHING DATABASE FOR 'Share' IN TITLE:\n";
foreach ($results as $row) {
    echo "ID: " . $row->ID . " | Title: " . $row->post_title . "\n";
}
