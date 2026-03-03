<?php
/**
 * Modern Sidebar Template - Clean Design with Icons
 * On single post pages: shows only the active category with a back button
 * On other pages: shows all categories
 */

// Determine if we're on a single post page
$is_single_post = is_singular('post');
$current_post_id = get_queried_object_id();

// Get current post's categories or current archive's category
$current_categories = array();
if ($is_single_post) {
    $cats = get_the_category($current_post_id);
    if ($cats) {
        foreach ($cats as $cat) {
            $current_categories[] = $cat->slug;
        }
    }
} elseif (is_category()) {
    $cat = get_queried_object();
    if ($cat && isset($cat->slug)) {
        $current_categories[] = $cat->slug;
    }
}

// Define all sidebar sections with their category slugs
$sidebar_sections = array(
    array(
        'slug'  => 'getting-started',
        'title' => 'Getting Started',
        'icon'  => 'getting start.svg',
        'id'    => 'getting-started',
    ),
    array(
        'slug'  => 'utm-parameters-guidelines',
        'title' => 'UTM Parameters Guidelines',
        'icon'  => 'link.svg',
        'id'    => 'utm-parameters-guidelines',
    ),
    array(
        'slug'  => 'sonar',
        'title' => 'Reporting Hub',
        'icon'  => 'sonar.svg',
        'id'    => 'sonar',
    ),
    array(
        'slug'  => 'dashboard-guides',
        'title' => 'Dashboard Guides',
        'icon'  => 'dashboard.svg',
        'id'    => 'dashboard-guides',
    ),
    array(
        'slug'  => 'chat-data',
        'title' => 'Chat with Data',
        'icon'  => 'chat.svg',
        'id'    => 'chat-data',
    ),
    array(
        'slug'  => 'data-library',
        'title' => 'Data Library',
        'icon'  => 'clone.svg',
        'id'    => 'data-library',
    ),
    array(
        'slug'  => 'faq',
        'title' => 'FAQ',
        'icon'  => 'faq.svg',
        'id'    => 'faq',
    ),
    array(
        'slug'  => 'global-filters',
        'title' => 'Global Filters',
        'icon'  => 'global.svg',
        'id'    => 'global-filters',
    ),
    array(
        'slug'  => 'navigation',
        'title' => 'Navigation',
        'icon'  => 'navigation.svg',
        'id'    => 'navigation',
    ),
);
?>

<?php if ($is_single_post) : ?>
<!-- ==================== SINGLE POST SIDEBAR ==================== -->
<div class="modern-sidebar single-post-sidebar">
    <style>
        /* Back button styling */
        .sidebar-back-btn {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            margin-bottom: 8px;
            color: #64748b;
            font-size: 13px;
            font-weight: 500 !important;
            text-decoration: none !important;
            border-radius: 8px;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
        }
        .sidebar-back-btn:hover {
            background: #f1f5f9;
            color: #1e293b;
            text-decoration: none !important;
        }
        .sidebar-back-btn svg {
            flex-shrink: 0;
            transition: transform 0.2s ease;
        }
        .sidebar-back-btn:hover svg {
            transform: translateX(-3px);
        }
        .sidebar-back-btn span {
            font-weight: 500 !important;
        }

        /* Single post sidebar - active category header */
        .single-post-sidebar .active-cat-header {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px 16px;
            margin-bottom: 4px;
            border-radius: 10px;
            background: #dbeafe2b;
            border: none;
            cursor: pointer;
            transition: background 0.2s ease;
            user-select: none;
        }
        .single-post-sidebar .active-cat-header:hover {
            background: #dbeafe55;
        }
        .single-post-sidebar .active-cat-header .cat-icon {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .single-post-sidebar .active-cat-header .cat-icon img {
            width: 22px;
            height: 22px;
            border: none !important;
        }
        .single-post-sidebar .active-cat-header .cat-title {
            flex: 1;
            font-size: 14px;
            color: #007bff !important;
            line-height: 1.3;
            white-space: nowrap;
        }
        .single-post-sidebar .active-cat-header .cat-toggle {
            width: 20px;
            height: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            color: #94a3b8;
            transition: transform 0.3s ease, color 0.2s ease;
            transform: rotate(180deg);
        }
        .single-post-sidebar .active-cat-header .cat-toggle.open {
            transform: rotate(270deg);
            color: #1e40af;
        }

        /* Active category article list */
        .single-post-sidebar .cat-articles {
            padding: 4px 0 0 0;
            max-height: 500px;
            overflow: hidden;
            transition: max-height 0.3s ease, opacity 0.3s ease, padding 0.3s ease;
            opacity: 1;
        }
        .single-post-sidebar .cat-articles.collapsed {
            max-height: 0;
            opacity: 0;
            padding: 0;
        }
        .single-post-sidebar .cat-article-item {
            position: relative;
            padding: 8px 12px 8px 20px;
            border-left: 2px solid transparent;
            transition: all 0.2s ease;
            margin-left: 16px;
        }
        .single-post-sidebar .cat-article-item:hover {
            border-left-color: #93c5fd;
            background: #f8fafc;
            border-radius: 0 6px 6px 0;
        }
        .single-post-sidebar .cat-article-item.active-article {
            border-left-color: transparent;
            background: #dbeafe2b;
            border-radius: 6px;
        }
        .single-post-sidebar .cat-article-item a {
            display: block;
            font-size: 13px;
            font-weight: 400 !important;
            color: #64748b;
            text-decoration: none !important;
            line-height: 1.5;
            transition: color 0.2s ease;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .single-post-sidebar .cat-article-item:hover a {
            color: #1e293b;
        }
        .single-post-sidebar .cat-article-item.active-article a {
            color: #007bff !important;
        }

        /* Divider */
        .sidebar-divider {
            height: 1px;
            background: #e2e8f0;
            margin: 12px 16px;
        }
    </style>

    <div class="sidebar-content">
        <!-- Back Button -->
        <?php
        // Get the category link for the back button
        $back_url = home_url('/');
        $back_label = 'All Categories';
        if (!empty($current_categories)) {
            $cat_obj = get_category_by_slug($current_categories[0]);
            if ($cat_obj) {
                $back_url = get_category_link($cat_obj->term_id);
                $back_label = 'All Categories';
            }
        }
        ?>
        <a href="<?php echo esc_url($back_url); ?>" class="sidebar-back-btn">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M19 12H5M5 12L12 19M5 12L12 5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            </svg>
            <span>All Categories</span>
        </a>


        <?php
        // Find and display only the active category
        $active_section_found = false;
        foreach ($sidebar_sections as $section) :
            if (in_array($section['slug'], $current_categories)) :
                $active_section_found = true;
        ?>
                <!-- Active Category Header (Always expanded) -->
                <div class="active-cat-header">
                    <div class="cat-icon">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/' . $section['icon']); ?>" alt="<?php echo esc_attr($section['title']); ?>">
                    </div>
                    <div class="cat-title">
                        <?php echo esc_html($section['title']); ?>
                    </div>
                </div>

                <!-- Articles in this category -->
                <div class="cat-articles">
                    <?php
                    $cat_obj = get_category_by_slug($section['slug']);
                    $cat_id = $cat_obj ? $cat_obj->term_id : 0;
                    
                    $cat_args = array(
                        'cat'            => $cat_id,
                        'posts_per_page' => 20,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $cat_query = new WP_Query($cat_args);

                    if ($cat_query->have_posts()) :
                        while ($cat_query->have_posts()) : $cat_query->the_post();
                            $is_current = (get_the_ID() == $current_post_id);
                    ?>
                        <div class="cat-article-item <?php echo $is_current ? 'active-article' : ''; ?>">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="cat-article-item">
                            <span style="color: #94a3b8; font-size: 13px;">No articles found</span>
                        </div>
                    <?php endif; ?>
                </div>
        <?php
            endif;
        endforeach;

        // Fallback: if no matching section found, show first category
        if (!$active_section_found && !empty($current_categories)) :
            $first_cat = get_category_by_slug($current_categories[0]);
            if ($first_cat) :
        ?>
                <div class="active-cat-header">
                    <div class="cat-icon">
                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/link.svg'); ?>" alt="<?php echo esc_attr($first_cat->name); ?>">
                    </div>
                    <div class="cat-title">
                        <?php echo esc_html($first_cat->name); ?>
                    </div>
                </div>

                <div class="cat-articles">
                    <?php
                    $cat_args = array(
                        'cat'            => $first_cat->term_id,
                        'posts_per_page' => 20,
                        'orderby'        => 'date',
                        'order'          => 'DESC',
                    );
                    $cat_query = new WP_Query($cat_args);

                    if ($cat_query->have_posts()) :
                        while ($cat_query->have_posts()) : $cat_query->the_post();
                            $is_current = (get_the_ID() == $current_post_id);
                    ?>
                        <div class="cat-article-item <?php echo $is_current ? 'active-article' : ''; ?>">
                            <a href="<?php the_permalink(); ?>" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
                        </div>
                    <?php
                        endwhile;
                        wp_reset_postdata();
                    else :
                    ?>
                        <div class="cat-article-item">
                            <span style="color: #94a3b8; font-size: 13px;">No articles found</span>
                        </div>
                    <?php endif; ?>
                </div>
        <?php
            endif;
        endif;
        ?>
    </div>
</div>

<script>
function toggleCatArticles(header) {
    var articles = header.nextElementSibling;
    while (articles && !articles.classList.contains('cat-articles')) {
        articles = articles.nextElementSibling;
    }
    var toggle = header.querySelector('.cat-toggle');
    if (articles) {
        articles.classList.toggle('collapsed');
    }
    if (toggle) {
        toggle.classList.toggle('open');
    }
}
</script>

<?php else : ?>
<!-- ==================== DEFAULT SIDEBAR (All Categories) ==================== -->
<div class="modern-sidebar">
    <div class="sidebar-content">
        <?php foreach ($sidebar_sections as $section) :
            // Check if current page is in this category
            $is_active_cat = in_array($section['slug'], $current_categories);
            $header_class = 'section-header expandable' . ($is_active_cat ? ' active' : '');
            $content_class = 'section-content' . ($is_active_cat ? ' expanded' : '');
            $expand_class = 'expand-icon' . ($is_active_cat ? ' expanded' : '');
        ?>
        <div class="sidebar-section">
            <div class="<?php echo esc_attr($header_class); ?>" data-target="<?php echo esc_attr($section['id']); ?>">
                <div class="section-icon">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/' . $section['icon']); ?>" alt="<?php echo esc_attr($section['title']); ?> Icon">
                </div>
                <span class="section-title"><?php echo esc_html($section['title']); ?></span>
                <span class="<?php echo esc_attr($expand_class); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none">
                        <path d="M15 6L9 12.0001L15 18" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="16" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>

            <div class="<?php echo esc_attr($content_class); ?>" id="<?php echo esc_attr($section['id']); ?>">
                <?php
                // Get the category object to ensure we have the correct ID
                $slug_to_check = $section['slug'];
                $cat_obj = get_category_by_slug($slug_to_check);
                
                // Fallback for case sensitivity or common Slug variations
                if ( ! $cat_obj ) {
                    $cat_obj = get_category_by_slug(strtolower($slug_to_check));
                }
                
                if ( ! $cat_obj && is_numeric($section['id']) ) {
                    $cat_obj = get_term($section['id'], 'category');
                }
                
                // Final fallback if slug is mismatched
                if ( ! $cat_obj ) {
                    $cat_obj = get_term_by('name', $section['title'], 'category');
                }

                $cat_id = $cat_obj ? $cat_obj->term_id : 0;
                
                $args = array(
                    'cat'            => $cat_id,
                    'posts_per_page' => 15,
                    'orderby'        => 'date',
                    'order'          => 'DESC',
                );
                
                $query = new WP_Query($args);
                
                echo "<!-- DEBUG: Section: {$section['slug']} | Cat ID: {$cat_id} | Posts Found: {$query->found_posts} -->";

                if ($query->have_posts()) :
                    while ($query->have_posts()) : $query->the_post();
                        $is_current_post = (get_the_ID() == $current_post_id);
                        $item_wrapper_class = $is_current_post ? 'subsection-item current-page' : 'subsection-item';
                ?>
                    <div class="<?php echo esc_attr($item_wrapper_class); ?>">
                        <a href="<?php the_permalink(); ?>" class="subsection-title" title="<?php echo esc_attr(get_the_title()); ?>"><?php the_title(); ?></a>
                        <span class="subsection-arrow">▶</span>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                    // No posts found in this category
                    ?>
                    <div class="subsection-item">
                        <span class="subsection-title-plain" style="color: #94a3b8; font-size: 13px; padding-left: 20px;">No articles found</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle expandable sections
    const expandableHeaders = document.querySelectorAll('.section-header.expandable');

    expandableHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.tagName === 'A') return;

            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const expandIcon = this.querySelector('.expand-icon');

            if (content) {
                const isExpanding = !content.classList.contains('expanded');

                // If we are opening a section, close all others first
                if (isExpanding) {
                    expandableHeaders.forEach(otherHeader => {
                        if (otherHeader !== this) {
                            const otherTargetId = otherHeader.getAttribute('data-target');
                            const otherContent = document.getElementById(otherTargetId);
                            const otherIcon = otherHeader.querySelector('.expand-icon');
                            
                            otherHeader.classList.remove('active');
                            if (otherContent) otherContent.classList.remove('expanded');
                            if (otherIcon) {
                                otherIcon.style.transform = 'rotate(180deg)';
                                otherIcon.classList.remove('expanded');
                            }
                        }
                    });
                }

                // Toggle the clicked section
                content.classList.toggle('expanded');
                this.classList.toggle('active');

                if (content.classList.contains('expanded')) {
                    expandIcon.style.transform = 'rotate(270deg)';
                    expandIcon.classList.add('expanded');
                } else {
                    expandIcon.style.transform = 'rotate(180deg)';
                    expandIcon.classList.remove('expanded');
                }
            }
        });
    });

    // Handle non-expandable sections
    const nonExpandableHeaders = document.querySelectorAll('.section-header:not(.expandable)');
    nonExpandableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            console.log('Clicked:', this.querySelector('.section-title').textContent);
        });
    });
});
</script>
<?php endif; ?>
