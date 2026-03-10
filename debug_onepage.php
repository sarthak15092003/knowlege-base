<?php
require_once('../../../wp-load.php');
$query = new WP_Query([
    'post_type' => 'onepage-docs',
    'posts_per_page' => -1
]);
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "Title: " . get_the_title() . "\n";
    }
} else {
    echo "No onepage-docs found.\n";
}
