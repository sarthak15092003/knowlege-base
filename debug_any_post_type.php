<?php
require_once('../../../wp-load.php');
$post_types = get_post_types(['public' => true], 'names');
echo "Post Types Found: " . implode(', ', $post_types) . "\n\n";

$query = new WP_Query([
    'post_type' => 'any',
    's' => 'Sharing',
    'posts_per_page' => 10
]);

echo "SEARCHING 'ANY' POST TYPE FOR 'Sharing':\n";
echo "--------------------------------------\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "Match: " . get_the_title() . " (Post Type: " . get_post_type() . ")\n";
    }
} else {
    echo "Nothing found in 'any' post type.\n";
}
wp_reset_postdata();
