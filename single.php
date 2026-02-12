<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package docy
 */
get_header();

// Fixed 3-7-2 layout for single posts
$post_column        = '7'; // Main content always 7 columns
$p0                 = '';

if ( docy_toc('post') == '1' ) {
    wp_enqueue_script('anchor');
    wp_enqueue_script('bootstrap-toc');
}
$blog_column = '8'; // Main content fixed at 8 columns for 2-8-2 layout

$banner_type_page = docy_meta('banner_type','default');
$banner_type_page = docy_meta('banner_type','default');

$banner_type_opt = docy_opt('banner_type', 'colorful');

if ( $banner_type_page != 'default' ) {
    // Fix old data (that was saved as 'toc'. Now it's 'colorful')
    $banner_type = $banner_type_page == 'toc' ? 'colorful' : $banner_type_page;
} else {
    $banner_type = $banner_type_opt;
}

// Banner
get_template_part( 'template-parts/single-post/banner', $banner_type );

// Add breadcrumb for single posts - DISABLED (breadcrumb now inside content area)
// $is_breadcrumb = docy_meta_apply('is_breadcrumb');
// if ( $is_breadcrumb == '1' ) {
//     get_template_part( 'template-parts/header-elements/search-banner/breadcrumb' );
// }
?>
<section class="blog_area tip_doc_area" id="toc_stick">
    <style>
        /* Fix TOC anchor scrolling - prevent content from going under header */
        html, body {
            scroll-padding-top: 120px !important;
        }
        .admin-bar html, .admin-bar body {
            scroll-padding-top: 152px !important;
        }
        @media (max-width: 1024px) {
            html, body {
                scroll-padding-top: 70px !important;
            }
            .admin-bar html, .admin-bar body {
                scroll-padding-top: 116px !important;
            }
        }
        
        /* Add scroll margin to headings for better scrollspy accuracy */
        .blog_single_item h1, 
        .blog_single_item h2, 
        .blog_single_item h3, 
        .blog_single_item h4, 
        .blog_single_item h5 {
            scroll-margin-top: 120px !important;
        }
        .admin-bar .blog_single_item h1, 
        .admin-bar .blog_single_item h2, 
        .admin-bar .blog_single_item h3, 
        .admin-bar .blog_single_item h4, 
        .admin-bar .blog_single_item h5 {
            scroll-margin-top: 152px !important;
        }
        @media (max-width: 1024px) {
            .blog_single_item h1, 
            .blog_single_item h2, 
            .blog_single_item h3, 
            .blog_single_item h4, 
            .blog_single_item h5 {
                scroll-margin-top: 70px !important;
            }
            .admin-bar .blog_single_item h1, 
            .admin-bar .blog_single_item h2, 
            .admin-bar .blog_single_item h3, 
            .admin-bar .blog_single_item h4, 
            .admin-bar .blog_single_item h5 {
                scroll-margin-top: 116px !important;
            }
        }
        
        /* Prevent TOC from showing active state when at page top */
        body:not(.scrolled) .doc-sidebar #docy-toc .nav-link.active,
        body:not(.scrolled) .doc-sidebar #docy-toc li.active > a,
        body:not(.scrolled) .doc-sidebar .doc-nav .nav-link.active,
        body:not(.scrolled) #docy-toc .nav-link.active,
        body:not(.scrolled) #docy-toc li.active > a,
        body:not(.scrolled) .doc-nav .nav-link.active {
            color: #64748b !important; /* Default gray color */
            font-weight: normal !important;
            /* padding-left: 0 !important; Reset padding to match inactive items - REMOVED */
        }
        
        /* Hide the blue indicator line when at page top */
        body:not(.scrolled) .doc-sidebar #docy-toc .nav-link.active::before,
        body:not(.scrolled) .doc-sidebar #docy-toc li.active > a::before,
        body:not(.scrolled) .doc-sidebar .doc-nav .nav-link.active::before,
        body:not(.scrolled) #docy-toc .nav-link.active::before,
        body:not(.scrolled) #docy-toc li.active > a::before,
        body:not(.scrolled) .doc-nav .nav-link.active::before {
            display: none !important;
        }

        /* Show and style the blue indicator line when scrolled - TOP LEVEL ONLY */
        body.scrolled .doc-sidebar #docy-toc > ul > li.active > a::before,
        body.scrolled #docy-toc > ul > li.active > a::before,
        body.scrolled .doc-nav > ul > li.active > a::before {
            display: block !important;
            content: "" !important;
            width: 2px !important;
            background: #3B82F6 !important;
            position: absolute !important;
            top: 10px !important;
            left: 0 !important;
            bottom: 10px !important;
            z-index: 1 !important;
        }

        /* Hide indicator for any nested links */
        #docy-toc ul ul .nav-link::before,
        .doc-nav ul ul .nav-link::before {
            display: none !important;
        }

        /* Adjust spacing between TOC items */
        #docy-toc li, .doc-nav li {
            margin-bottom: 5px !important;
        }
        #docy-toc .nav-link, .doc-nav .nav-link {
            padding-top: 5px !important;
            padding-bottom: 5px !important;
            font-size: 14px !important;
        }
        
        /* Ensure the active TOC item is always visible and correctly highlighted */
        #docy-toc .nav-link.active,
        #docy-toc li.active > a,
        .doc-nav .nav-link.active {
            color: #000000 !important;
        }
        
        /* Remove bottom padding from section to eliminate gap */
        .blog_area.tip_doc_area {
            padding-bottom: 0 !important;
        }
        
        /* Make the sidebar column extend to full viewport height */
        .category-left-sidebar-col {
            min-height: 100vh !important;
        }
        
        /* Keep internal scrolling but ensure proper height */
        .category-left-sidebar-col .modern-sidebar {
            max-height: calc(100vh - 140px) !important;
            overflow-y: auto !important;
        }
        
        /* Remove right padding from col-lg-3 on all screen sizes */
        .col-lg-3 {
            padding-right: 0 !important;
        }
        
        /* Override max-width, flex, and width constraints to allow full column widths */
        @media (min-width: 992px) {
            .category-left-sidebar-col {
                max-width: none !important;
                flex: 0 0 20% !important;
                width: 20% !important;
            }
            .category-left-sidebar-col .modern-sidebar {
                max-width: none !important;
            }
            .doc-sidebar {
                max-width: none !important;
                flex: 0 0 25% !important;
                width: 25% !important;
            }
            .blog_single_info {
                flex: 0 0 55% !important;
                width: 55% !important;
            }
            .blog_area.tip_doc_area .container {
                padding-left: 20px !important;
                padding-right: 20px !important;
                max-width: 100% !important;
            }
        }
        
        /* Mobile Optimization */
        @media (max-width: 1024px) {
            /* Specifically target the desktop sidebars to hide them, avoiding conflicts with mobile TOC */
            .col-lg-3.category-left-sidebar-col,
            .col-lg-2.doc-sidebar {
                display: none !important;
            }
            
            .blog_single_info {
                flex: 0 0 100% !important;
                max-width: 100% !important;
                width: 100% !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .blog_area.tip_doc_area .container {
                padding-left: 15px !important;
                padding-right: 15px !important;
            }
            
            /* Ensure the mobile TOC bar and its content are above everything */
            .jrBzsJ {
                z-index: 9999 !important;
                overflow: visible !important; /* Prevent clipping of slide-up menu */
                box-shadow: none !important; /* Remove shadow */
            }
            .bottom_table_content {
                z-index: 10000 !important;
                box-shadow: none !important; /* Remove shadow */
            }
            .bottom_table_content .toc-title {
                color: #ffffff !important;
            }
            /* Apply white color to all TOC related links on mobile */
            #docy-toc .nav-link, 
            #docy-toc a, 
            .doc-nav .nav-link, 
            .doc-nav a, 
            .nav-sidebar.doc-nav .nav-link, 
            .nav-sidebar.doc-nav a, 
            .left_sidebarlist .nav-link, 
            .left_sidebarlist a {
                position: relative !important;
                transition: all 0.3s ease !important;
                padding-left: 15px !important;
                color: #ffffff !important;
                background-color: transparent !important;
                font-size: 14px !important;
            }
            .bottom_table_content nav ul li a:hover {
                color: #ffffff !important;
                opacity: 1;
            }

            /* Responsive Width Fixes */
            .blog_single_info,
            .doc-sidebar {
                max-width: 100% !important;
                flex: 0 0 100% !important;
                width: 100% !important;
            }

            /* Remove shadow from share part */
            #share-modal {
                box-shadow: none !important;
            }

            /* Requested TOC Overlay Styles */
            .jrBzsJ .eYVFtH #toc-overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 10;
            }
        }
    </style>
    <div class="container">
        <div class="row">
            <!-- Left Modern Sidebar (20%) -->
            <div class="col-lg-3 category-left-sidebar-col" style="border-right: 1px solid #e5e7eb; background: #ffffff !important;">
                <style>
                    /* Align single-post sidebar with category layout */
                    .modern-sidebar {
                        background: #ffffff;
                        
                        border-radius: 8px;
                        padding: 0 0px 0 0;
                        margin-bottom: 0;
                        position: sticky;
                        top: 130px;
                        width: 100%;
                        max-width: 240px;
                        margin-top: 20px;
                    }

                    @media (max-width: 1199.98px) {
                        .category-left-sidebar-col {
                            border-right: none !important;
                            max-width: 100% !important;
                            flex: 0 0 100% !important;
                        }
                    }
                </style>
                <?php get_template_part( 'template-parts/sidebar-modern' ); ?>
            </div>
            <?php
            if ( docy_toc('post') == '1' ) :
                // Will render TOC on the right after the content
            endif; ?>
            <?php // TOC will render after the content column ?>

            <div class="col-lg-<?php echo esc_attr( $blog_column ) ?> blog_single_info pe-lg-3" style="">
                <div class="main-post <?php echo docy_toc('post') == '1' ? 'anchor-enabled' : ''; ?>">
                    <div class="blog_single_item editor-content" style=" margin-top:20px; ">
                        <?php
                        // Add breadcrumb inside blog single item
                        if ( function_exists('docy_post_breadcrumbs') ) {
                            echo '<style>
                                .blog_single_item .breadcrumb {
                                    list-style: none !important;
                                    padding-left: 0 !important;
                                    display: flex !important;
                                    flex-wrap: wrap !important;
                                    font-size: 14px !important;
                                }
                                .blog_single_item .breadcrumb li {
                                    list-style: none !important;
                                }
                                .blog_single_item .breadcrumb li a {
                                    color: #000000 !important;
                                    text-decoration: none;
                                }
                                .blog_single_item .breadcrumb li a:hover {
                                    text-decoration: underline;
                                }
                                .blog_single_item .breadcrumb li.active {
                                    color: #007bff !important;
                                }
                                /* Mobile breadcrumb visibility */
                                @media (max-width: 1024px) {
                                    .blog_single_item nav[aria-label="breadcrumb"] {
                                        display: block !important;
                                        visibility: visible !important;
                                        margin-bottom: 15px !important;
                                        padding: 0 !important;
                                    }
                                    .blog_single_item .breadcrumb {
                                        font-size: 14px !important;
                                        gap: 5px !important;
                                        display: flex !important;
                                        flex-wrap: wrap !important;
                                    }
                                    .blog_single_item .breadcrumb li,
                                    .blog_single_item .breadcrumb .breadcrumb-item {
                                        display: inline-flex !important;
                                        visibility: visible !important;
                                    }
                                    .blog_single_item .breadcrumb .breadcrumb-item+.breadcrumb-item {
                                        padding-left: 0 !important;
                                    }
                                    .blog_single_item .breadcrumb li.active,
                                    .blog_single_item .breadcrumb .breadcrumb-item.active {
                                        display: inline-flex !important;
                                        visibility: visible !important;
                                        color: #007bff !important;
                                    }
                                }
                            </style>';
                            echo '<nav aria-label="breadcrumb" class="mb-4">';
                            docy_post_breadcrumbs();
                            echo '</nav>';
                        }
                        ?>
                        <?php
                        while ( have_posts() ) : the_post();
                            $user_desc = get_the_author_meta( 'description' );
                            the_post_thumbnail('full', array( 'class' => 'mb-4 featured-image' ) );
                            the_content();
                            wp_link_pages( array(
                                'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'docy' ) . '</span>',
                                'after'       => '</div>',
                                'link_before' => '<span>',
                                'link_after'  => '</span>',
                                'pagelink'    => '<span class="screen-reader-text">' . esc_html__( 'Page', 'docy' ) . ' </span>%',
                                'separator'   => '<span class="screen-reader-text">, </span>',
                            ));
                        endwhile;
                        ?>
                    </div>
                    <?php
                    if ( has_tag() && docy_opt('is_post_tag', '1') == '1' ) : ?>
                        <div class="single_post_tags post-tags">
                            <?php the_tags( esc_html__( 'Tags : ', 'docy' ), ' ' ); ?>
                        </div>
                    <?php endif; ?>

                    <!-- CMGalaxy Engagement Block -->
                    <div class="cmgalaxy-engagement-block mt-5 p-4" style=" border-radius: 12px; background: #ffffff;">
                        <p class="lead mb-4" style="color: #374151; font-size: 1.125rem; line-height: 1.75;">
                            Thanks for being here with us! We are beyond excited to see how you'll use CMGalaxy to drive growth for your business.
                        </p>

                        <div class="related-articles-section mb-4">
                            <h5 class="fw-semibold mb-3" style="color: #374151; font-size: 1.125rem;">Related Articles</h5>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-3">
                                    <a href="#" class="d-flex justify-content-between align-items-center p-3" style="border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; transition: all 0.2s ease;">
                                        <span>Summary Dashboard Metrics Library</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;">
                                            <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </li>
                                <li class="mb-3">
                                    <a href="#" class="d-flex justify-content-between align-items-center p-3" style="border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; transition: all 0.2s ease;">
                                        <span>Customizing Your Attribution Dashboard</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;">
                                            <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </li>
                                <li class="mb-3">
                                    <a href="#" class="d-flex justify-content-between align-items-center p-3" style="border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; transition: all 0.2s ease;">
                                        <span>Pinterest Integration</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;">
                                            <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </li>
                                <li class="mb-3">
                                    <a href="#" class="d-flex justify-content-between align-items-center p-3" style="border: 1px solid #e5e7eb; border-radius: 8px; color: #374151; text-decoration: none; transition: all 0.2s ease;">
                                        <span>Google Ads Integration</span>
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="color: #3b82f6;">
                                            <path d="M7 17L17 7M17 7H9M17 7V15" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="feedback-section">
                            <p class="fw-semibold mb-2" style="color: #374151;">Was this helpful?</p>
                            <div class="d-flex gap-2 mb-3">
                                <button type="button" class="btn btn-primary">Yes</button>
                                <button type="button" class="btn btn-outline-secondary">No</button>
                            </div>
                            <p class="small text-muted mb-0">
                                This form is used for documentation feedback only. Learn how to get help with <a href="#" style="color: #3b82f6;">CMGalaxy</a>.
                            </p>
                        </div>

                        <!-- Post Navigation Cards -->
                        <?php
                        $prev_post = get_previous_post();
                        $next_post = get_next_post();
                        if ( $prev_post || $next_post ) : ?>
                            <div class="post-navigation-cards mt-4 d-flex gap-3">
                                <?php if ( $prev_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $prev_post ) ); ?>" class="nav-card prev-card p-4" style="border: 1px solid #e5e7eb; border-radius: 16px; text-decoration: none; background: #ffffff; transition: all 0.2s ease; flex: 0 0 260px; max-width: 260px;">
                                        <div class="nav-card-content">
                                            <h5 class="mb-3" style="color: #1f2937; font-weight: 600; font-size: 1rem;"><?php echo esc_html( get_the_title( $prev_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center" style="color: #3b82f6; font-size: 0.875rem; font-weight: 500;">
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="me-2">
                                                    <path d="M15 6L9 12L15 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                                Previous
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>

                                <?php if ( $next_post ) : ?>
                                    <a href="<?php echo esc_url( get_permalink( $next_post ) ); ?>" class="nav-card next-card p-4" style="border: 1px solid #e5e7eb; border-radius: 16px; text-decoration: none; background: #ffffff; transition: all 0.2s ease; flex: 0 0 260px; max-width: 260px; margin-left: auto;">
                                        <div class="nav-card-content text-end">
                                            <h5 class="mb-3" style="color: #1f2937; font-weight: 600; font-size: 1rem;"><?php echo esc_html( get_the_title( $next_post ) ); ?></h5>
                                            <div class="nav-direction d-flex align-items-center justify-content-end" style="color: #3b82f6; font-size: 0.875rem; font-weight: 500;">
                                                Next
                                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="ms-2">
                                                    <path d="M9 6L15 12L9 18" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                    </a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <style>
                            .cmgalaxy-engagement-block a:hover {
                                background-color: rgba(59, 130, 246, 0.05) !important;
                                border-color: #3b82f6 !important;
                                transform: translateY(-1px);
                            }

                            .feedback-section .btn {
                                min-width: 88px;
                                padding: 0.45rem 0.9rem;
                                border-radius: 999px;
                            }

                            .nav-card:hover {
                                box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1) !important;
                                transform: translateY(-2px) !important;
                                border-color: #cbd5e1 !important;
                            }

                            .nav-card h5 {
                                line-height: 1.4;
                                margin-bottom: 1rem;
                            }

                            .nav-card .nav-direction {
                                margin-top: auto;
                            }

                            @media (max-width: 768px) {
                                .post-navigation-cards {
                                    flex-direction: column !important;
                                }
                            }
                        </style>
                    </div>
                </div>
            </div>

            <?php
            // Render TOC on the right side after the content column
            if ( docy_toc('post') == '1' ) : ?>
                <div class="col-lg-2 doc-sidebar pe-lg-0 ps-lg-2" style="margin-top:20px;">
                    <style>
                        /* Make right sidebar (TOC) sticky like left sidebar */
                        .doc-sidebar {
                            position: sticky;
                            top: 130px;
                            align-self: flex-start;
                            max-height: 100%;
                            overflow-y: auto;
                            margin-top: 50px; /* Align with left sidebar */
                            padding: 0 20px 0 25px !important; /* Added left padding for indicator */
                        }
                        
                        /* Keep TOC title visible on scroll */
                        body.scrolled .doc-sidebar .toc-title,
                        body.scrolled .left_sidebarlist .toc-title,
                        body.scrolled .toc-title,
                        body.scrolled .table_content {
                            display: block !important;
                            height: auto !important;
                            opacity: 1 !important;
                        }
                        
                        /* Adjust sidebar margin and padding on scroll */
                        body.scrolled .doc-sidebar {
                            margin-top: 0px !important;
                            top: 140px !important; /* Move below the 119px sticky header */
                        }
                        
                        body.scrolled .left_sidebarlist {
                            padding-top: 0px !important;
                        }
                        
                        .doc-sidebar .left_sidebarlist {
                            background: #ffffff;
                            border-radius: 8px;
                            width: 100%;
                        }
                        
                        /* TOC Active Section Color */
                        #docy-toc .nav-link.active,
                        #docy-toc li.active > a,
                        #docy-toc li.active,
                        #docy-toc li:has(li.active) > a,
                        #docy-toc li:has(.nav-link.active) > a {
                            color: #000000 !important;
                            font-weight: 500 !important;
                        }
                        
                        /* Smooth scrollbar for right sidebar */
                        .doc-sidebar::-webkit-scrollbar {
                            width: 6px;
                        }
                        
                        .doc-sidebar::-webkit-scrollbar-track {
                            background: #f1f1f1;
                            border-radius: 3px;
                        }
                        
                        .doc-sidebar::-webkit-scrollbar-thumb {
                            background: #888;
                            border-radius: 3px;
                        }
                        
                        .doc-sidebar::-webkit-scrollbar-thumb:hover {
                            background: #555;
                        }
                        
                        /* TOC Active Item - Blue Color with High Specificity */
                        .doc-sidebar #docy-toc .nav-link.active,
                        .doc-sidebar #docy-toc li.active > a,
                        .doc-sidebar #docy-toc a.active,
                        .doc-sidebar .doc-nav .nav-link.active,
                        .doc-sidebar .doc-nav li.active > a,
                        .doc-sidebar .doc-nav a.active,
                        #docy-toc .nav-link.active,
                        #docy-toc li.active > a,
                        #docy-toc a.active,
                        .nav-sidebar.doc-nav .nav-link.active,
                        .nav-sidebar.doc-nav li.active > a,
                        .nav-sidebar.doc-nav a.active {
                            color: #000000 !important;
                            text-decoration: none !important;
                            font-weight: normal !important;
                        }
                        
                        /* Remove underline and bold on hover for all TOC links */
                        .doc-sidebar #docy-toc a:hover,
                        .doc-sidebar .doc-nav a:hover,
                        #docy-toc a:hover,
                        .nav-sidebar.doc-nav a:hover {
                            text-decoration: none !important;
                            font-weight: normal !important;
                            color: #000000 !important;
                        }
                        
                        /* Hide nested sub-items in TOC - show only main steps */
                        #docy-toc .nav .nav,
                        .doc-nav .nav .nav,
                        .nav-sidebar.doc-nav .nav .nav {
                            display: none !important;
                        }
                        
                        /* Add padding to TOC container for consistent spacing */
                        .left_sidebarlist {
                            padding: 0 0 0 10px !important; /* Space for the blue indicator */
                        }
                        
                        /* Ensure indicator is correctly sized in SCSS context as well */
                        .left_sidebarlist .nav-link.active::before {
                            top: 10px !important;
                            bottom: 10px !important;
                        }
                        
                        .left_sidebarlist #docy-toc,
                        .left_sidebarlist .doc-nav {
                            /* padding-right: 0 !important; */
                            position: relative !important;
                        }
                        
                        /* Disable sticky on mobile/tablet */
                        @media (max-width: 1024px) {
                            .doc-sidebar {
                                position: static !important;
                                max-height: none !important;
                                overflow-y: visible !important;
                                max-width: 100% !important;
                                width: 100% !important;
                                flex: 0 0 100% !important;
                                display: none !important; /* Hide TOC on mobile */
                            }
                        }
                    </style>
                    <aside class="left_sidebarlist">
                        <h6 class="toc-title mb-3"><?php esc_html_e('Table of Contents', 'docy'); ?></h6>
                        <nav data-toggle="toc" class="list-unstyled nav-sidebar doc-nav" id="docy-toc"> </nav>
                        <div class="toc-sidebar-image mt-4">
                            <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/sidebarimg.png' ); ?>" alt="Sidebar CTA" class="img-fluid rounded-3" style="width: 100%;">
                        </div>
                        <div class="sidebar-cta-card" style="display: flex; align-items: center; gap: 16px; margin-top: 20px; padding: 1px 2px; border: 2px solid #3B82F6; border-radius: 18px; background: #f4f8ff; width: 100%; box-sizing: border-box;">
                            <div class="sidebar-cta-icon" aria-hidden="true">
                                <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <rect x="4" y="8" width="36" height="32" rx="8" stroke="#3B82F6" stroke-width="2" />
                                    <path d="M12 18H16" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M12 23H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M12 28H20" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 18H32" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 23H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                    <path d="M28 28H34" stroke="#3B82F6" stroke-width="2" stroke-linecap="round" />
                                </svg>
                            </div>
                            <div class="sidebar-cta-text" style="display: flex; flex-direction: column; gap: 4px;">
                                <p class="sidebar-cta-eyebrow" style="margin: 0;">New to CMGalaxy?</p>
                                <p class="sidebar-cta-body" style="margin: 0;">Check our <a href="#" class="sidebar-cta-link">Get Started</a> guides.</p>
                            </div>
                        </div>
                    </aside>
                </div>

                <div class="sc-jtXEFf jrBzsJ">
                    <div class="sc-eldieg eYVFtH">
                        <div class="overlay" id="toc-overlay"></div>
                        <button class="sc-kiIyQV fqmceZ table_content" aria-expanded="false" aria-controls="docy-toc">
                            <?php esc_html_e('Table of Contents', 'docy'); ?>
                        </button>
                        <aside class="bottom_table_content" id="docy-tocs" aria-hidden="true">
                            <button class="close-toc">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24" viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707 4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12 13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision"></path>
                                </svg>
                            </button>
                            <h6 class="toc-title mb-3"><?php esc_html_e('Table of Contents', 'docy'); ?></h6>
                            <nav data-toggle="toc" class="nav-sidebar doc-nav"></nav>
                        </aside>
                        <button class="sc-kiIyQV fqmceZ table_share_btn">
                            <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_ sc-cLpAjG cfZGuc" data-ui-name="Share" width="16" height="16" viewBox="0 0 16 16" data-name="Share" data-group="m">
                                <path d="M11.707 1.293a1 1 0 1 0-1.414 1.414L12.586 5H7a6 6 0 0 0-6 6v3a1 1 0 1 0 2 0v-3a4 4 0 0 1 4-4h5.586l-2.293 2.293a1 1 0 1 0 1.414 1.414l4-4a1 1 0 0 0 0-1.414l-4-4Z" shape-rendering="geometricPrecision"></path>
                            </svg>
                            <?php esc_html_e('Share', 'docy'); ?>
                        </button>
                        <div class="docy-modal-content" id="share-modal" aria-hidden="true">
                            <button class="close docy-close" aria-label="Close Share Modal">
                                <svg aria-hidden="true" tabindex="-1" disabled="" class="___SIcon_pchrv_gg_" data-ui-name="Close" width="24" height="24" viewBox="0 0 24 24" data-name="Close" data-group="l" title="Close">
                                    <path d="M20.707 4.707a1 1 0 0 0-1.414-1.414L12 10.586 4.707 3.293a1 1 0 0 0-1.414 1.414L10.586 12l-7.293 7.293a1 1 0 1 0 1.414 1.414L12 13.414l7.293 7.293a1 1 0 0 0 1.414-1.414L13.414 12l7.293-7.293Z" shape-rendering="geometricPrecision"></path>
                                </svg>
                            </button>
                            <div class="docy-share-wrap">
                                <div class="social-links">
                                    <a href="mailto:?subject=<?php the_title(); ?>&amp;body= <?php esc_html_e( 'Check out this doc', 'docy' ); the_permalink(); ?>" target="_blank"><i class="icon_mail"></i></a>
                                    <a href="https://www.facebook.com/share.php?u=<?php the_permalink(); ?>"><i class="social_facebook_circle"></i></a>
                                    <a href="https://www.linkedin.com/shareArticle?mini=true&url=<?php the_permalink(); ?>"><i class="social_linkedin_square"></i></a>
                                    <a href="https://twitter.com/share?url=<?php the_permalink(); ?>&amp;text=<?php the_title(); ?> &amp;hashtags=<?php echo esc_url(site_url()); ?>"><i class="social_twitter"></i></a>
                                </div>
                                <p>Copy link</p>
                                <div class="docy-copy-url-wrap">
                                    <div class="share-this-docs">
                                        <input readonly type="text" value="<?php the_permalink(); ?>" class="word-wrap">
                                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/clone.svg" alt="<?php esc_attr_e( 'Docy theme', 'docy' ); ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Secondary row: Related posts and comments below, without TOC on the side -->
        <div class="row mt-5">
            <div class="col-lg-<?php echo esc_attr( $blog_column ) ?> blog_single_info m-auto">
                <?php
                // Related posts
                if ( is_singular('post') ) {
                    get_template_part( 'template-parts/single-post/related-posts' );
                }

                ?>
            </div>
        </div>
    </div>
    
    <script>
        // Add 'scrolled' class to body when user scrolls down from top
        (function() {
            function updateScrolledClass() {
                if (window.scrollY > 50) {
                    if (!document.body.classList.contains('scrolled')) {
                        document.body.classList.add('scrolled');
                    }
                } else {
                    if (document.body.classList.contains('scrolled')) {
                        document.body.classList.remove('scrolled');
                    }
                }
            }
            
            // Check on load
            updateScrolledClass();
            
            // Check on scroll
            window.addEventListener('scroll', updateScrolledClass);
        })();
    </script>
</section>
<?php
get_footer();
