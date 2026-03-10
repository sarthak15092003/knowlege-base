<?php
require_once('../../../wp-load.php');
$post = get_post(55);
if ($post) {
    file_put_contents('article_content.txt', $post->post_content);
    echo "Saved to " . __DIR__ . "/article_content.txt\n";
}
