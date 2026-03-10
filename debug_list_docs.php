<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => 'docs',
    'posts_per_page' => -1,
    'post_status' => 'any'
]);

echo "LISTING ALL 'DOCS' TITLES:\n";
echo "--------------------------\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "ID: " . get_the_ID() . " | Title: " . get_the_title() . " | Status: " . get_post_status() . "\n";
    }
} else {
    echo "No 'docs' found.\n";
}
wp_reset_postdata();
