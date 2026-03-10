<?php
require_once('../../../wp-load.php');
$post = get_post(55);
file_put_contents('id_55_full.txt', $post->post_content);
echo "Saved ID 55 content to id_55_full.txt\n";
