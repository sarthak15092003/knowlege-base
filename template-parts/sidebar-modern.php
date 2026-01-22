<?php
/**
 * Modern Sidebar Template - Clean Design with Icons
 * Based on the provided UI design with UTM parameter support
 */

// Get UTM parameters from URL
$utm_source = isset($_GET['utm_source']) ? sanitize_text_field($_GET['utm_source']) : '';
$utm_medium = isset($_GET['utm_medium']) ? sanitize_text_field($_GET['utm_medium']) : '';
$utm_campaign = isset($_GET['utm_campaign']) ? sanitize_text_field($_GET['utm_campaign']) : '';
$utm_term = isset($_GET['utm_term']) ? sanitize_text_field($_GET['utm_term']) : '';
$utm_content = isset($_GET['utm_content']) ? sanitize_text_field($_GET['utm_content']) : '';

// Check if any UTM parameters are present
$has_utm_params = !empty($utm_source) || !empty($utm_medium) || !empty($utm_campaign) || !empty($utm_term) || !empty($utm_content);

// Always show UTM section for demonstration purposes
$has_utm_params = true;

// Function to get articles related to UTM parameters
function get_utm_related_articles($utm_params) {
    $articles = array();
    
    // Search for posts that contain UTM-related keywords in title or content
    $search_terms = array();
    if (!empty($utm_params['source'])) $search_terms[] = $utm_params['source'];
    if (!empty($utm_params['medium'])) $search_terms[] = $utm_params['medium'];
    if (!empty($utm_params['campaign'])) $search_terms[] = $utm_params['campaign'];
    if (!empty($utm_params['term'])) $search_terms[] = $utm_params['term'];
    
    if (!empty($search_terms)) {
        $search_query = implode(' ', $search_terms);
        
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => 5,
            's' => $search_query,
            'meta_query' => array(
                'relation' => 'OR',
                array(
                    'key' => 'utm_source',
                    'value' => $utm_params['source'],
                    'compare' => 'LIKE'
                ),
                array(
                    'key' => 'utm_campaign',
                    'value' => $utm_params['campaign'],
                    'compare' => 'LIKE'
                )
            )
        );
        
        $utm_query = new WP_Query($query_args);
        
        if ($utm_query->have_posts()) {
            while ($utm_query->have_posts()) {
                $utm_query->the_post();
                $articles[] = array(
                    'title' => get_the_title(),
                    'url' => get_permalink(),
                    'excerpt' => wp_trim_words(get_the_excerpt(), 15)
                );
            }
            wp_reset_postdata();
        }
    }
    
    return $articles;
}

$utm_articles = array();
if ($has_utm_params) {
    $utm_params = array(
        'source' => $utm_source,
        'medium' => $utm_medium,
        'campaign' => $utm_campaign,
        'term' => $utm_term,
        'content' => $utm_content
    );
    $utm_articles = get_utm_related_articles($utm_params);
    
    // Add dummy articles for testing if no real articles found
    if (empty($utm_articles)) {
        $utm_articles = array(
            array(
                'title' => 'Getting Started with UTM Tracking',
                'url' => '#',
                'excerpt' => 'Learn how to set up and use UTM parameters for campaign tracking.'
            ),
            array(
                'title' => 'UTM Best Practices Guide',
                'url' => '#',
                'excerpt' => 'Best practices for naming and organizing your UTM campaigns.'
            ),
            array(
                'title' => 'Analytics Setup for UTM Campaigns',
                'url' => '#',
                'excerpt' => 'How to configure your analytics to track UTM parameters effectively.'
            )
        );
    }
}
?>

<div class="modern-sidebar">
    <div class="sidebar-content">
        <!-- Getting Started Section (Expandable) -->
        <div class="sidebar-section">
            <div class="section-header expandable active" data-target="getting-started">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/getting start.svg" alt="Getting Started Icon">
                </div>
                <span class="section-title">Getting Started</span>
                <span class="expand-icon expanded">
                    <svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </span>
            </div>
            
            <div class="section-content expanded" id="getting-started">
                <?php
                // Get posts from "Getting Started" category
                $getting_started_args = array(
                    'category_name' => 'getting-started', // Category slug
                    'posts_per_page' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                $getting_started_query = new WP_Query($getting_started_args);
                
                if ($getting_started_query->have_posts()) :
                    while ($getting_started_query->have_posts()) : $getting_started_query->the_post();
                        $is_current = (get_the_ID() == get_queried_object_id());
                        $item_class = $is_current ? 'subsection-item current-page' : 'subsection-item';
                ?>
                    <div class="<?php echo esc_attr($item_class); ?>">
                        <a href="<?php the_permalink(); ?>" class="subsection-title"><?php the_title(); ?></a>
                        <span class="subsection-arrow">▶</span>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <div class="subsection-item">
                        <span class="subsection-title-plain">No articles found in Getting Started category</span>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <?php if ($has_utm_params) : 
            // Check if current post is in UTM Parameters Guidelines category
            $current_post_id = get_queried_object_id();
            $is_utm_category = has_category('utm-parameters-guidelines', $current_post_id);
            $utm_section_class = $is_utm_category ? 'section-header expandable active' : 'section-header expandable';
            $utm_content_class = $is_utm_category ? 'section-content expanded' : 'section-content';
        ?>
        <!-- UTM Parameters Section (Expandable) -->
        <div class="sidebar-section">
            <div class="<?php echo esc_attr($utm_section_class); ?>" data-target="utm-articles">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/link.svg" alt="UTM Parameters Icon">
                </div>
                <span class="section-title">UTM Parameters Guidelines</span>
                <span class="expand-icon <?php echo $is_utm_category ? 'expanded' : ''; ?>"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
            
            <div class="<?php echo esc_attr($utm_content_class); ?>" id="utm-articles">
                <?php
                // Get posts from "UTM Parameters Guidelines" category
                $utm_args = array(
                    'category_name' => 'utm-parameters-guidelines', // Category slug
                    'posts_per_page' => 10,
                    'orderby' => 'date',
                    'order' => 'DESC'
                );
                
                $utm_query = new WP_Query($utm_args);
                
                if ($utm_query->have_posts()) :
                    while ($utm_query->have_posts()) : $utm_query->the_post();
                        $is_current = (get_the_ID() == $current_post_id);
                        $item_class = $is_current ? 'subsection-item current-page' : 'subsection-item';
                ?>
                    <div class="<?php echo esc_attr($item_class); ?>">
                        <a href="<?php the_permalink(); ?>" class="subsection-title"><?php the_title(); ?></a>
                        <span class="subsection-arrow">▶</span>
                    </div>
                <?php
                    endwhile;
                    wp_reset_postdata();
                else :
                ?>
                    <div class="subsection-item">
                        <span class="subsection-title-plain">No articles found in UTM Parameters Guidelines category</span>
                    </div>
                <?php endif; ?>
                
            </div>
        </div>
        <?php endif; ?>

        <!-- Sonar Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="sonar">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/sonar.svg" alt="Sonar Icon">
                </div>
                <span class="section-title">Sonar</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- Dashboard Guides Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="dashboard-guides">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/dashboard.svg" alt="Dashboard Icon">
                </div>
                <span class="section-title">Dashboard Guides</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- Chat with Data Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="chat-data">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/chat.svg" alt="Chat Icon">
                </div>
                <span class="section-title">Chat with Data</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- Data Library Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="data-library">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/clone.svg" alt="Data Library Icon">
                </div>
                <span class="section-title">Data Library</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="faq">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/faq.svg" alt="FAQ Icon">
                </div>
                <span class="section-title">FAQ</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- Global Filters Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="global-filters">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/global.svg" alt="Global Icon">
                </div>
                <span class="section-title">Global Filters</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>

        <!-- Navigation Section -->
        <div class="sidebar-section">
            <div class="section-header" data-target="navigation">
                <div class="section-icon">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/navigation.svg" alt="Navigation Icon">
                </div>
                <span class="section-title">Navigation</span>
                <span class="expand-icon"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle expandable sections
    const expandableHeaders = document.querySelectorAll('.section-header.expandable');
    
    expandableHeaders.forEach(header => {
        header.addEventListener('click', function(e) {
            // Don't expand if clicking on a link
            if (e.target.tagName === 'A') return;
            
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const expandIcon = this.querySelector('.expand-icon');
            
            if (content) {
                // Toggle expanded class
                content.classList.toggle('expanded');
                this.classList.toggle('active');
                
                // Rotate arrow
                if (content.classList.contains('expanded')) {
                    expandIcon.style.transform = 'rotate(90deg)';
                    expandIcon.classList.add('expanded');
                } else {
                    expandIcon.style.transform = 'rotate(0deg)';
                    expandIcon.classList.remove('expanded');
                }
            }
        });
    });
    
    // Handle non-expandable sections (just navigation)
    const nonExpandableHeaders = document.querySelectorAll('.section-header:not(.expandable)');
    
    nonExpandableHeaders.forEach(header => {
        header.addEventListener('click', function() {
            // Add click effect or navigation logic here
            console.log('Clicked:', this.querySelector('.section-title').textContent);
        });
    });
});
</script>
