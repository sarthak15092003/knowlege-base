<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => 'any',
    's' => 'WhatsApp',
    'posts_per_page' => 10
]);

echo "SEARCHING FOR 'WhatsApp':\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "Match: " . get_the_title() . " (" . get_post_type() . ")\n";
    }
} else {
    echo "No hits for 'WhatsApp'.\n";
}
wp_reset_postdata();
