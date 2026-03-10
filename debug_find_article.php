<?php
require_once('../../../wp-load.php');
$target_title = 'Sharing Reports (Email, WhatsApp & In-App)';
$query = new WP_Query([
    'post_type' => ['post', 'docs'],
    'title' => $target_title,
    'posts_per_page' => 1
]);

echo "CHECKING FOR ARTICLE: '$target_title'\n";
echo "---------------------------------\n";
if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();
        echo "FOUND!\n";
        echo "ID: " . get_the_ID() . "\n";
        echo "Title: " . get_the_title() . "\n";
        echo "Status: " . get_post_status() . "\n";
        echo "Post Type: " . get_post_type() . "\n";
        echo "Content Snippet: " . substr(strip_tags(get_the_content()), 0, 100) . "...\n";
    }
} else {
    echo "NOT FOUND by exact title.\n";
    echo "Searching with 's' parameter for 'Sharing Reports'...\n";
    $query2 = new WP_Query([
        'post_type' => ['post', 'docs'],
        's' => 'Sharing Reports',
        'posts_per_page' => 10
    ]);
    if ($query2->have_posts()) {
        while ($query2->have_posts()) {
            $query2->the_post();
            echo "Match: " . get_the_title() . " (ID: " . get_the_ID() . ")\n";
        }
    } else {
        echo "No hits for 'Sharing Reports' either.\n";
    }
}
wp_reset_postdata();
