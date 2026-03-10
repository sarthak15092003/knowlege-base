<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => ['post', 'docs'],
    'posts_per_page' => -1,
    's' => 'Sharing'
]);

echo "SEARCHING CONTENT FOR 'Sharing':\n";
echo "-------------------------------\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "Match: " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
    }
} else {
    echo "No content matches for 'Sharing'.\n";
}
wp_reset_postdata();
