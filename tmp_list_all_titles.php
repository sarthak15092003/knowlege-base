<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => ['post', 'docs'],
    'posts_per_page' => -1,
    'fields' => 'ids'
]);

echo "LISTING ALL ARTICLE TITLES:\n";
echo "--------------------------\n";
if ($query->have_posts()) {
    foreach ($query->posts as $post_id) {
        echo get_the_title($post_id) . "\n";
    }
} else {
    echo "No articles found.\n";
}
wp_reset_postdata();
