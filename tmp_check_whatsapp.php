<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => ['post', 'docs'],
    'posts_per_page' => -1,
    's' => 'whatsapp'
]);
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "ID: " . get_the_ID() . " | Title: " . get_the_title() . "\n";
    }
} else {
    echo "No articles found with 'whatsapp'\n";
}
wp_reset_postdata();
