<?php
require_once('../../../wp-load.php');
$post = get_post(55);
if ($post) {
    echo "ID: 55\n";
    echo "TITLE: " . $post->post_title . "\n";
    echo "CONTENT START:\n";
    echo substr($post->post_content, 0, 5000);
}
