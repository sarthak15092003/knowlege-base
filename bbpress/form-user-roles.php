<?php

/**
 * User Roles Profile Edit Part
 *
 * @package bbPress
 * @subpackage Theme
 */

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

?>

<div class="row g-3">
    <div class="col-lg-6">
        <?php bbp_edit_user_blog_role(); ?>
    </div>

    <div class="col-lg-6">
        <?php bbp_edit_user_forums_role(); ?>
    </div>
</div>
