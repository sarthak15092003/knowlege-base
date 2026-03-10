<?php
require_once('../../../wp-load.php');
$post = get_post(55);
if ($post) {
    echo "TITLE: " . $post->post_title . "\n";
    echo "CONTENT: " . $post->post_content . "\n";
} else {
    echo "Post 55 not found.\n";
}
