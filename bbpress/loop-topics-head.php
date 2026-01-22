<div class="post-header">
	<?php Docy_Forum()::forum_topics_open_close(); ?>
    <?php
    if ( is_singular('forum') ) : ?>
        <div class="support-category-menus">
            <ul class="category-menu">
                <li>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle UserList" type="button" id="dropdownMenuAuthor" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php esc_html_e( 'Author', 'docy' );?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuAuthor">
                            <h3 class="title"><?php esc_html_e( 'Filter by author', 'docy' );?></h3>
                            <form action="#" class="cate-search-form">
                                <input type="text" placeholder="<?php esc_attr_e('Search users', 'docy'); ?>" id="search_field" >
                            </form>
                            <div class="all-users" id="UserList">
                                <?php Docy_Forum()::forum_topics_authors();?>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle tagLista" type="button" id="dropdownMenuLabel" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php esc_html_e( 'Label', 'docy' );?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLabel">
                            <h3 class="title"><?php esc_html_e( 'Filter by label', 'docy' );?></h3>
                            <form action="#" class="cate-search-form">
                                <input type="text" placeholder="<?php esc_attr_e('Search label', 'docy'); ?>" id="search_fields">
                            </form>
                            <div class="all-users" id="tagList">
                                <?php Docy_Forum()::forum_topics_tags();?>
                            </div>
                        </div>
                    </div>
                </li>
                <li>
                    <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?php esc_html_e( 'Sort', 'docy' );?>
                        </button>
                        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                            <h3 class="title"><?php esc_html_e( 'Sort by', 'docy' );?></h3>
                            <div class="short-by">
                                <a class="dropdown-item sort-by newest_posts" href="#newest_posts" data-sort="newest_posts" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Newest', 'docy' ); ?> </a>
                                <a class="dropdown-item sort-by" href="#oldest_posts" data-sort="oldest_posts" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Oldest', 'docy' ); ?> </a>
                                <a class="dropdown-item sort-by" href="#comment_count" data-sort="comment_count" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Most commented', 'docy' ); ?> </a>
                                <a class="dropdown-item sort-by" href="#comment_date" data-sort="comment_date" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Least commented', 'docy' ); ?> </a>
                                <a class="dropdown-item sort-by" href="#recent_updated_post" data-sort="recent_updated_post" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Recently updated', 'docy' ); ?> </a>
                                <a class="dropdown-item sort-by" href="#last_recent_updated_post" data-sort="last_recent_updated_post" data-parent="<?php echo get_queried_object_id(); ?>"> <?php esc_html_e( 'Least recently updated', 'docy' );?> </a>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <!-- /.support-category-menus -->
        <?php
    endif;
    ?>
</div>