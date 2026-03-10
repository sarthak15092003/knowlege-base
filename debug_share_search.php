<?php
require_once('../../../wp-load.php');
$search_term = 'share';
$query = new WP_Query([
    'post_type' => ['post', 'docs'],
    'posts_per_page' => 10,
    's' => $search_term
]);

echo "SEARCH RESULTS FOR: '$search_term'\n";
echo "-------------------------------\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "ID: " . get_the_ID() . " | Title: " . get_the_title() . "\n";
    }
} else {
    echo "No articles found with '$search_term'\n";
}
wp_reset_postdata();
