<?php
/**
 * CMGALAXY Custom Header Layout
 */
$s_value = get_search_query() ? get_search_query() : '';
?>

<div class="collapse navbar-collapse cmgalaxy-header" id="navbarSupportedContent">
    
    <!-- Close Button for Mobile -->
    <button class="navbar-close-btn" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-label="Close navigation">
        <span class="close-icon">&times;</span>
    </button>
    
    <!-- Main Header Row -->
    <div class="cmgalaxy-header-main d-flex align-items-center w-100">
        <!-- Logo Section -->
        <div class="cmgalaxy-logo-section d-flex align-items-center">
            <div class="cmgalaxy-logo">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="d-block">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/img/kblogo.svg" alt="Knowledge Base Logo" class="cmgalaxy-logo-img">
                </a>
            </div>
        </div>

        <!-- Search Section -->
        <div class="cmgalaxy-search-section flex-grow-1" style="margin-left: 0; margin-right: 20px;">
            <div class="d-flex align-items-center gap-3">
                <form action="<?php echo esc_url(home_url("/")) ?>" class="cmgalaxy-search-form flex-grow-1" method="get">
                    <div class="search-input-wrapper">
                        <input type="search" 
                               placeholder="<?php esc_attr_e("Search ('/' to focus)", 'docy'); ?>" 
                               name="s" 
                               value="<?php echo esc_attr($s_value) ?>"
                               class="cmgalaxy-search-input"
                               autocomplete="off">
                        <span class="cmgalaxy-search-loader" aria-hidden="true"></span>
                        <div class="cmgalaxy-search-suggestions" id="search-suggestions"></div>
                    </div>
                </form>
                
                <!-- Ask Lex Button -->
                <a href="#" class="cmgalaxy-ask-lex-btn" style="height: 40px;">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/lexlogo.svg' ); ?>" alt="Lex Logo" class="cmgalaxy-ask-lex-logo me-2">
                    Ask Lex
                </a>
            </div>
        </div>

        <!-- Right Actions -->
        <div class="cmgalaxy-actions d-flex align-items-center">
            <!-- Community Link -->
            <a href="#" class="cmgalaxy-nav-link me-3">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/community.svg' ); ?>" alt="Community" class="cmgalaxy-community-icon me-2">
                Community
            </a>

            <?php if ( is_user_logged_in() ) : 
                $current_user = wp_get_current_user();
                $display_name = !empty($current_user->display_name) ? $current_user->display_name : (!empty($current_user->first_name) ? $current_user->first_name : $current_user->user_login);
                $initial = strtoupper(substr($display_name, 0, 1));
                $user_email = $current_user->user_email;
                $plan_status = get_user_meta($current_user->ID, 'plan_status', true);
                if (empty($plan_status)) $plan_status = 'paid';
            ?>
                <!-- User Profile Dropdown -->
                <div class="cmg-profile-dropdown-wrap">
                    <button type="button" class="cmg-profile-trigger" id="cmgProfileTrigger" aria-expanded="false" aria-haspopup="true">
                        <span class="cmg-avatar-circle"><?php echo esc_html($initial); ?></span>
                        <span class="cmg-profile-name"><?php echo esc_html($display_name); ?></span>
                        <svg class="cmg-arrow-down" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </button>

                    <div class="cmg-profile-dropdown-menu" id="cmgProfileDropdownMenu">
                        <div class="cmg-dropdown-header">
                            <div class="cmg-dropdown-user-row">
                                <div class="cmg-dropdown-avatar"><?php echo esc_html($initial); ?></div>
                                <div class="cmg-dropdown-user-meta">
                                    <div class="cmg-dropdown-user-name"><?php echo esc_html($display_name); ?></div>
                                    <div class="cmg-dropdown-user-email" title="<?php echo esc_attr($user_email); ?>"><?php echo esc_html($user_email); ?></div>
                                </div>
                            </div>
                            <div class="cmg-dropdown-badge-wrap">
                                <span class="cmg-plan-badge <?php echo esc_attr(strtolower($plan_status)); ?>">
                                    ✓ <?php echo esc_html(strtoupper($plan_status)); ?> ACCOUNT
                                </span>
                            </div>
                        </div>

                        <div class="cmg-dropdown-divider"></div>

                        <div class="cmg-dropdown-links">
                            <a href="https://platform.cmgalaxy.com" target="_blank" rel="noopener noreferrer" class="cmg-dropdown-item">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <rect x="3" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="3" width="7" height="7"></rect>
                                    <rect x="14" y="14" width="7" height="7"></rect>
                                    <rect x="3" y="14" width="7" height="7"></rect>
                                </svg>
                                <span>Platform App</span>
                                <svg class="cmg-ext-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
                            </a>
                            <a href="<?php echo esc_url(wp_logout_url(home_url('/'))); ?>" class="cmg-dropdown-item cmg-dropdown-logout">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                    <polyline points="16 17 21 12 16 7"></polyline>
                                    <line x1="21" y1="12" x2="9" y2="12"></line>
                                </svg>
                                <span>Sign Out</span>
                            </a>
                        </div>
                    </div>
                </div>
            <?php else : 
                $curr_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            ?>
                <!-- Sign In Link -->
                <a href="<?php echo esc_url(add_query_arg('redirect_to', urlencode($curr_url), home_url('/signin/'))); ?>" class="cmgalaxy-nav-link">
                    <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/signin.svg' ); ?>" alt="Sign In" class="cmgalaxy-signin-icon me-2">
                    Sign In
                </a>
            <?php endif; ?>
        </div>
    </div>

    <!-- Navigation Menu Row -->
    <div class="cmgalaxy-nav-menu w-100">
        <?php
        if ( has_nav_menu('main_menu') ) {
            wp_nav_menu( array (
                'menu' => 'main_menu',
                'theme_location' => 'main_menu',
                'container' => null,
                'menu_class' => "cmgalaxy-main-nav d-flex list-unstyled mb-0",
                'walker' => new Docy_Nav_Walker(),
                'depth' => 4
            ));
        } else {
            // Default menu items if no menu is set
            ?>
            <ul class="cmgalaxy-main-nav d-flex list-unstyled mb-0">
                <li class="nav-item me-4"><a href="<?php echo home_url('/'); ?>" class="nav-link">Home</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Get Started</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Docs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">Guide</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">FAQs</a></li>
                <li class="nav-item me-4"><a href="#" class="nav-link">API Docs</a></li>
            </ul>
            <?php
        }

        ?>
    </div>
</div>

<button id="lex-side-trigger" class="lex-side-trigger" aria-label="Open Lex Assistant">
    <svg width="12" height="20" viewBox="0 0 12 24" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M10 2l-8 10 8 10" stroke="#000000" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
    </svg>
</button>

<!-- Lex Drawer -->
<div id="lex-drawer" class="lex-drawer" role="dialog" aria-modal="true" aria-label="Lex Assistant">
    <div class="lex-drawer-panel">
        <div class="lex-drawer-body">
            <div class="lex-drawer-content">
                <iframe id="lex-assistant-frame" src="<?php echo esc_url( add_query_arg('ajax_url', admin_url('admin-ajax.php'), get_template_directory_uri() . '/assets/html/lex-assistant.html') ); ?>" style="width: 100%; height: 100%; border: none;" title="Lex Assistant"></iframe>
            </div>
        </div>
    </div>
    <div class="lex-drawer-overlay"></div>
</div>

<!-- Lex Logic configuration -->
<script>
    const lexLogoUrl = "<?php echo get_template_directory_uri(); ?>/assets/img/lexlogo.svg";
    const cmgalaxy_ajax_url = "<?php echo admin_url('admin-ajax.php'); ?>";
</script>
<div id="cmgalaxy-search-backdrop" class="cmgalaxy-search-backdrop"></div>
<style>
/* =============================================
   CMGalaxy User Profile Dropdown Styles
   ============================================= */
.cmg-profile-dropdown-wrap {
    position: relative;
    display: inline-block;
}

.cmg-profile-trigger {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #f8fafc;
    border: 1.5px solid #e2e8f0;
    padding: 5px 12px 5px 6px;
    border-radius: 999px;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
    font-family: inherit;
}

.cmg-profile-trigger:hover,
.cmg-profile-trigger.active {
    background: #ffffff;
    border-color: #cbd5e1;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
}

.cmg-avatar-circle {
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    font-size: 13px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-transform: uppercase;
}

.cmg-profile-name {
    font-size: 14px;
    font-weight: 600;
    color: #0f172a;
    max-width: 120px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.cmg-arrow-down {
    color: #64748b;
    transition: transform 0.2s ease;
}

.cmg-profile-trigger.active .cmg-arrow-down {
    transform: rotate(180deg);
}

.cmg-profile-dropdown-menu {
    position: absolute;
    top: calc(100% + 10px);
    right: 0;
    width: 250px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.12), 0 8px 10px -6px rgba(0, 0, 0, 0.06);
    padding: 12px 0;
    z-index: 1050;
    display: none;
    opacity: 0;
    transform: translateY(-8px);
    transition: opacity 0.2s ease, transform 0.2s ease;
}

.cmg-profile-dropdown-menu.show {
    display: block;
    opacity: 1;
    transform: translateY(0);
}

.cmg-dropdown-header {
    padding: 4px 16px 10px 16px;
}

.cmg-dropdown-user-row {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
}

.cmg-dropdown-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
    color: #ffffff;
    font-size: 15px;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    text-transform: uppercase;
}

.cmg-dropdown-user-meta {
    overflow: hidden;
}

.cmg-dropdown-user-name {
    font-size: 14px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.cmg-dropdown-user-email {
    font-size: 12px;
    color: #64748b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.3;
}

.cmg-dropdown-badge-wrap {
    margin-top: 4px;
}

.cmg-plan-badge {
    display: inline-block;
    padding: 3px 8px;
    border-radius: 999px;
    font-size: 10.5px;
    font-weight: 700;
    letter-spacing: 0.4px;
    text-transform: uppercase;
}

.cmg-plan-badge.paid {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #86efac;
}

.cmg-plan-badge.demo {
    background: #dbeafe;
    color: #1d4ed8;
    border: 1px solid #93c5fd;
}

.cmg-dropdown-divider {
    height: 1px;
    background: #f1f5f9;
    margin: 8px 0;
}

.cmg-dropdown-links {
    display: flex;
    flex-direction: column;
}

.cmg-dropdown-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    color: #334155 !important;
    font-size: 13.5px;
    font-weight: 500;
    text-decoration: none !important;
    transition: all 0.15s ease;
}

.cmg-dropdown-item:hover {
    background: #f8fafc;
    color: #0f172a !important;
}

.cmg-dropdown-item svg {
    color: #64748b;
    flex-shrink: 0;
}

.cmg-dropdown-item .cmg-ext-icon {
    margin-left: auto;
    color: #94a3b8;
}

.cmg-dropdown-item.cmg-dropdown-logout {
    color: #dc2626 !important;
}

.cmg-dropdown-item.cmg-dropdown-logout svg {
    color: #dc2626;
}

.cmg-dropdown-item.cmg-dropdown-logout:hover {
    background: #fef2f2;
}

.cmgalaxy-search-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.4);
    backdrop-filter: blur(4px);
    z-index: 999;
    display: none;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.cmgalaxy-search-backdrop.active {
    display: block;
    opacity: 1;
}

.cmgalaxy-search-section {
    position: relative;
    z-index: 1001; /* Must be above backdrop */
}
/* =============================================
   LEX DRAWER STYLES
   ============================================= */

/* Drawer container — hidden by default, covers the full viewport when open */
.lex-drawer {
    display: none;
    position: fixed;
    inset: 0;
    z-index: 999999;
    pointer-events: none;
}

.lex-drawer.open {
    display: block;
    /* Do not set pointer-events: auto; here so background remains interactive */
}

/* Semi-transparent backdrop */
.lex-drawer-overlay {
    position: absolute;
    top: 117px;
    left: 0;
    right: 0;
    bottom: 0;
    background: transparent;
    transition: background 0.3s ease, backdrop-filter 0.3s ease;
    animation: lex-fade-in 0.3s ease forwards;
    pointer-events: none; /* Never block background in sidebar mode */
}

.lex-drawer-panel.expanded ~ .lex-drawer-overlay {
    background: rgba(0, 0, 0, 0.45);
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
    pointer-events: auto; /* Blocking interaction only when expanded */
}

.lex-drawer.closing .lex-drawer-overlay {
    animation: lex-fade-out 0.28s ease forwards;
}

/* Sliding panel */
.lex-drawer-panel {
    position: absolute;
    top: 117px; /* Reduced to match the navigation menu line */
    right: 0;
    height: calc(100vh - 117px); /* Adjusted for the new top offset */
    width: 390px;
    max-width: 95vw;
    overflow: visible;
    display: flex;
    flex-direction: column;
    transition: width 0.4s cubic-bezier(0.4, 0, 0.2, 1), 
                height 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                top 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                right 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                transform 0.4s cubic-bezier(0.4, 0, 0.2, 1),
                border-radius 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    animation: lex-slide-in 0.35s cubic-bezier(0.4, 0, 0.2, 1) forwards;
    transform: translateX(100%);
    z-index: 999999;
    pointer-events: auto; /* Ensure panel is always interactive */
}

.lex-drawer-body {
    position: relative;
    width: 100%;
    height: 100%;
    background: #ffffff;
    box-shadow: -12px 0 48px rgba(58, 125, 255, 0.18);
    border-left: 1.5px solid #e0e9f9;
    border-radius: 0; /* Removed radius to match the straight line of header */
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

/* Suppress initial animation for persistence */
.lex-drawer.lex-no-animation .lex-drawer-panel,
.lex-drawer.lex-no-animation .lex-drawer-overlay {
    animation: none !important;
    transition: none !important;
}

.lex-drawer-panel.expanded {
    width: 1000px; /* Increased width */
    height: calc(100vh - 117px);
    max-width: 95vw;
    max-height: calc(100vh - 117px);
    top: 117px;
    right: 0;
    transform: none !important;
}

.lex-drawer-panel.expanded .lex-drawer-body {
    border-radius: 0;
}

@media (max-width: 1024px) {
    .lex-drawer-panel, 
    .lex-drawer-overlay,
    .lex-drawer-panel.expanded {
        top: 60px; /* Reduced for mobile/tablet header */
        height: calc(100vh - 60px);
        max-height: calc(100vh - 60px);
    }
}

/* Removed redundant parent header */

/* Removed redundant parent styles */

.lex-drawer-expand {
    position: absolute;
    top: 50%;
    left: -18px; /* Half of width to sit on the line */
    transform: translateY(-50%);
    background: #ffffff;
    border: 1.5px solid #e0e9f9;
    box-shadow: -4px 0 15px rgba(0, 0, 0, 0.05);
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    z-index: 101;
    transition: background 0.2s ease, transform 0.25s ease, left 0.3s ease;
    flex-shrink: 0;
}

.lex-drawer-expand svg {
    transition: transform 0.3s ease;
}

.lex-icon-collapse {
    display: none;
}

.lex-drawer-panel.expanded .lex-icon-expand {
    display: none;
}

.lex-drawer-panel.expanded .lex-icon-collapse {
    display: flex;
}

.lex-drawer-expand:hover {
    background: #f8fafc;
    transform: translateY(-50%) scale(1.1);
}

.lex-drawer.closing .lex-drawer-panel {
    animation: lex-slide-out 0.28s cubic-bezier(0.4, 0, 0.2, 1) forwards;
}

/* Side Trigger Button - Minimalist Circular */
.lex-side-trigger {
    position: fixed;
    right: -18px; /* Sit halfway off the screen edge */
    top: 50%;
    transform: translateY(-50%);
    z-index: 9999;
    background: #ffffff;
    border: 1.5px solid #f1f5f9;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
    padding-left: 6px;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.lex-side-trigger:hover {
    right: -10px;
    background: #ffffff;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12);
}

/* Vertical line behind the button */
.lex-side-trigger::before {
    content: '';
    position: absolute;
    right: 17px;
    top: -100vh;
    height: 200vh;
    width: 2px;
    background: #e2e8f0;
    z-index: -1;
    pointer-events: none;
}

.lex-side-trigger svg {
    transition: transform 0.3s ease;
}

.lex-side-trigger:hover svg {
    transform: translateX(-2px);
}

/* Hide side trigger when drawer is open */
body.lex-drawer-open .lex-side-trigger {
    opacity: 0;
    visibility: hidden;
    transform: translateY(-50%) translateX(100%);
}

/* Keyframe animations */
@keyframes lex-slide-in {
    from { transform: translateX(100%); }
    to   { transform: translateX(0); }
}

@keyframes lex-slide-out {
    from { transform: translateX(0); }
    to   { transform: translateX(100%); }
}

@keyframes lex-fade-in {
    from { opacity: 0; }
    to   { opacity: 1; }
}

@keyframes lex-fade-out {
    from { opacity: 1; }
    to   { opacity: 0; }
}

/* Close button */
/* Removed redundant parent close button styles */

.lex-drawer-content {
    flex: 1;
    display: block;
    overflow: hidden;
    width: 100%;
    height: 100%;
    margin: 0;
    padding: 0;
}

.lex-drawer-content iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: none;
    margin: 0;
    padding: 0;
}

.lex-drawer-img {
    width: 100%;
    height: auto;
    display: block;
    user-select: none;
    pointer-events: none;
}

/* Prevent body scroll while drawer is open - Commented out to allow background scrolling as requested */
/* body.lex-drawer-open {
    overflow: hidden !important;
} */

/* =============================================
   CSS Fixes for Mobile Menu Visibility and Interaction
   ============================================= */
@media (max-width: 1024px) {
    /* Ensure the mobile bar is ALWAYS on top */
    .mobile_main_menu {
        z-index: 100000 !important;
        background: #ffffff !important;
    }
    
    /* Ensure the hamburger button is clickable */
    .mobile_menu_btn {
        position: relative !important;
        z-index: 100001 !important;
        cursor: pointer !important;
        background: transparent !important;
        border: none !important;
        padding: 10px !important;
    }
    
    /* Hide desktop items absolutely */
    #sticky, .header, .cmgalaxy-header {
        display: none !important;
    }
    
    /* Show the mobile menu bar if theme hid it */
    .mobile_main_menu.display_none {
        display: block !important;
    }
}

/* Side Menu and Overlay z-index */
.side_menu {
    z-index: 100002 !important;
}

.click_capture {
    z-index: 100001 !important;
    background: rgba(0,0,0,0.5) !important;
}

/* Ensure body doesn't scroll when menu is open - Restrict to mobile only */
@media (max-width: 991px) {
    body.menu-is-opened {
        overflow: hidden !important;
    }
}

<style>
/* CMGALAXY Header Styles */
.cmgalaxy-header {
    flex-direction: column !important;
    padding: 1rem 0;
}

/* Search input — taller only when popup is open */
.cmgalaxy-search-section.popup-active .cmgalaxy-search-input {
    height: 50px !important;
    line-height: 50px !important;
    padding-top: 0 !important;
    padding-bottom: 0 !important;
}




.cmgalaxy-logo-section {
    min-width: 200px;
}

.cmgalaxy-logo-img {
    max-height: 50px;
    height: auto;
    width: auto;
    max-width: 300px;
    object-fit: contain;
}

.cmgalaxy-brand {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1f2937;
    text-decoration: none;
    line-height: 1.2;
}

.cmgalaxy-subtitle {
    font-size: 0.875rem;
    color: #6b7280;
    display: block;
    margin-top: -2px;
}

.cmgalaxy-search-section {
    max-width: 500px;
    width: 100%;
    transition: max-width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.cmgalaxy-search-section.popup-active {
    max-width: 750px;
}

.cmgalaxy-search-section.popup-active .cmgalaxy-ask-lex-btn {
    display: none !important;
}

.cmgalaxy-search-form {
    width: 100%;
}

.search-input-wrapper {
    position: relative;
    width: 100%;
}

.cmgalaxy-search-input {
    width: 100%;
    padding: 0.75rem 2.5rem 0.75rem 2.5rem;
    border: 1px solid #d1d5db;
    border-radius: 0.5rem;
    font-size: 0.875rem;
    background-color: #f9fafb;
    transition: all 0.2s ease;
}

.cmgalaxy-search-loader {
    position: absolute;
    right: 12px;
    top: 50%;
    width: 16px;
    height: 16px;
    margin-top: -8px;
    border: 2px solid #d1d5db;
    border-top-color: #3b82f6;
    border-radius: 50%;
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

.cmgalaxy-search-loader.active {
    opacity: 1;
    visibility: visible;
    animation: cmgalaxy-search-spin 0.75s linear infinite;
}

.cmgalaxy-search-input.is-loading::-webkit-search-cancel-button {
    -webkit-appearance: none;
    appearance: none;
    display: none;
}

@keyframes cmgalaxy-search-spin {
    to {
        transform: rotate(360deg);
    }
}

.cmgalaxy-search-input:focus {
    outline: none;
    border-color: #3b82f6;
    background-color: #ffffff;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* --- Improved Search Suggestions --- */
.cmgalaxy-search-suggestions {
    position: absolute;
    top: calc(100% + 12px);
    left: 0;
    right: 0;
    background: #ffffff;
    border: 1px solid rgba(0, 0, 0, 0.08);
    border-radius: 14px;
    box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.12), 0 10px 15px -5px rgba(0, 0, 0, 0.05);
    z-index: 1002;
    overflow: hidden;
    display: none;
    text-align: left !important; /* Force left alignment against banner centering */
    max-height: 480px;
    overflow-y: auto;
}

.suggestion-item {
    padding: 12px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none !important;
    border-bottom: 1px solid rgba(0, 0, 0, 0.03);
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover {
    background: #f8fafc;
}

.suggestion-icon {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    background: #f1f5f9;
    color: #64748b;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.suggestion-item:hover .suggestion-icon {
    background: #e2e8f0;
    color: #475569;
}

.suggestion-content {
    flex: 1;
    min-width: 0;
    text-align: left !important;
}

.suggestion-title {
    font-size: 15px;
    font-weight: 600;
    color: #111827 !important;
    margin-bottom: 3px;
    line-height: 1.4;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.suggestion-meta {
    font-size: 12px;
    color: #6b7280 !important;
    line-height: 1;
}

/* Ask Lex Suggestion (AI) */
.ask-ai-suggestion {
    border-top: 1px solid rgba(0, 0, 0, 0.06);
    background: #f8fafc; /* Subtle light blue/gray background */
}

.ask-ai-item {
    padding: 14px 18px;
    display: flex;
    align-items: center;
    gap: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.ask-ai-item:hover {
    background: #eff6ff; /* Light blue on hover */
}

.ask-ai-icon {
    flex-shrink: 0;
    width: 36px;
    height: 36px;
    background: #fff;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.ask-ai-text {
    font-size: 15px;
    font-weight: 600;
    color: #1e40af !important; /* Brand Blue */
}

.ask-ai-query {
    color: #3b82f6;
    font-style: italic;
}

.search-highlight {
    background: #fef08a; /* Soft yellow highlight */
    color: #854d0e;
    padding: 0 2px;
    border-radius: 2px;
}
.search-icon-left {
    position: absolute;
    left: 0.75rem;
    top: 50%;
    transform: translateY(-50%);
    color: #6b7280;
    font-size: 0.875rem;
}

.cmgalaxy-actions {
    min-width: 250px;
    justify-content: flex-end;
}

.cmgalaxy-ask-lex-btn {
    background: white;
    color: #484A61;
    padding: 0.5rem 1rem;
    border-radius: 50px;
    display: inline-flex;
    align-items: center;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: all 0.2s ease;
}

.cmgalaxy-ask-lex-logo {
    width: 20px;
    height: auto;
    display: inline-block;
    vertical-align: middle;
}

.cmgalaxy-ask-lex-btn:hover {
    background: linear-gradient(135deg, #059669, #047857);
    color: white;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

.cmgalaxy-nav-link {
    color: #4b5563;
    text-decoration: none;
    font-size: 0.875rem;
    font-weight: 500;
    transition: color 0.2s ease;
}

.cmgalaxy-nav-link:hover {
    color: #1f2937;
    text-decoration: none;
}

.cmgalaxy-community-icon {
    width: 24px;
    height: auto;
    display: inline-block;
}

.cmgalaxy-signin-icon {
    width: 24px;
    height: auto;
    display: inline-block;
}

.cmgalaxy-nav-menu {
    padding-top: 0px;
    margin-top: 0px !important;
    padding-bottom: 0rem !important;
}

.menu-is-opened .navbar-collapse.cmgalaxy-header,
.menu-is-opened .cmgalaxy-nav-menu {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
    overflow: hidden !important;
}

.cmgalaxy-main-nav {
    gap: 2rem;
}

.cmgalaxy-main-nav .nav-item {
    margin: 0;
}

.cmgalaxy-main-nav .nav-link {
    color: #4b5563;
    font-size: 0.875rem;
    font-weight: 500;
    padding: 0.5rem 0;
    text-decoration: none;
    transition: color 0.2s ease;
    border-bottom: 2px solid transparent;
}

.cmgalaxy-main-nav .nav-link:hover,
.cmgalaxy-main-nav .nav-item.active .nav-link {
    color: #1f2937;
    border-bottom-color: #3b82f6;
}

/* Desktop Header Main Padding */
@media (min-width: 1025px) {
    #sticky .container {
        padding-left: 30px !important;
        padding-right: 30px !important;
        max-width: 100% !important;
    }

    /* Target single post page specifically for 40px header padding */
    body.single-post #sticky .container {
        padding-left: 30px !important;
        padding-right: 30px !important;
    }

    .cmgalaxy-header {
        padding-bottom: 0 !important;
    }

    .cmgalaxy-header-main {
        padding-left: 0px;
        padding-right: 0px; /* Removed individual padding */
    }
    
    .cmgalaxy-nav-menu {
        padding-left: 0px;
        padding-right: 0px; /* Removed individual padding */
    }
}

/* Mobile & Tablet Responsive Styles */
@media (max-width: 768px) {
    /* Navbar adjustments for mobile */
    .header .navbar,
    .navbar.sticky-nav,
    nav.menu_one,
    #sticky {
        padding: 0.5rem 0 !important;
    }

    /* Removed conflicting styles - now controlled by cmgalaxy-header.css */

    .cmgalaxy-header-container {
        flex-direction: column;
        gap: 0.75rem;
        padding: 0.75rem 1rem;
    }

    .cmgalaxy-logo-section {
        width: 100%;
        min-width: auto;
        justify-content: center;
    }

    .cmgalaxy-logo-img {
        max-height: 40px;
    }

    .cmgalaxy-search-section {
        width: 100%;
        max-width: 100%;
    }

    .cmgalaxy-actions {
        width: 100%;
        min-width: auto;
        justify-content: center;
        gap: 1rem;
    }

    /* Removed conflicting nav-menu styles */

    .cmgalaxy-main-nav {
        flex-wrap: nowrap;
        gap: 1rem;
        padding: 0 1rem;
    }

    .cmgalaxy-main-nav .nav-item {
        white-space: nowrap;
    }
}

@media (max-width: 576px) {
    /* Mobile-specific navbar adjustments */
    .header .navbar,
    .navbar.sticky-nav,
    nav.menu_one,
    #sticky {
        padding: 0.25rem 0 !important;
    }

    .cmgalaxy-header-container {
        gap: 0.5rem;
        padding: 0.5rem 0.75rem;
    }

    .cmgalaxy-logo-img {
        max-height: 35px;
    }

    .cmgalaxy-search-input {
        padding: 0.5rem 0.75rem 0.5rem 2rem;
        font-size: 0.8125rem;
    }

    .search-icon-left {
        left: 0.5rem;
        font-size: 0.75rem;
    }

    .cmgalaxy-ask-lex-btn {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    .cmgalaxy-ask-lex-logo {
        width: 16px;
    }

    .cmgalaxy-nav-link {
        font-size: 0.8125rem;
    }

    .cmgalaxy-main-nav {
        gap: 0.75rem;
        padding: 0 0.75rem;
    }

    .cmgalaxy-main-nav .nav-link {
        font-size: 0.8125rem;
        padding: 0.25rem 0.5rem;
    }
}

@media (max-width: 480px) {
    /* Extra small mobile devices */
    .cmgalaxy-header-container {
        padding: 0.5rem;
    }

    .cmgalaxy-logo-img {
        max-height: 30px;
    }

    .cmgalaxy-actions {
        flex-direction: column;
        gap: 0.5rem;
    }

    .cmgalaxy-ask-lex-btn,
    .cmgalaxy-nav-link {
        width: 100%;
        text-align: center;
        justify-content: center;
    }
}

@media (max-width: 1024px) {
    /* NUCLEAR HIDE: Ensure desktop header layout NEVER shows on mobile */
    #sticky,
    .header,
    .navbar-collapse.cmgalaxy-header,
    .cmgalaxy-header-main,
    .cmgalaxy-nav-menu,
    #navbarSupportedContent {
        display: none !important;
        visibility: hidden !important;
        height: 0 !important;
        padding: 0 !important;
        margin: 0 !important;
        overflow: hidden !important;
    }
}

/* Desktop Header Visibility */
@media (min-width: 1025px) {
    .navbar-collapse.cmgalaxy-header {
        display: flex !important;
    }
}

/* Sticky/Fixed Navbar - Using position: fixed for better browser support */
.header {
    position: relative;
}

.header .navbar,
.navbar.sticky-nav,
nav.menu_one,
#sticky {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    right: 0 !important;
    width: 100% !important;
    z-index: 9999 !important;
    background-color: #ffffff !important;
    border-bottom: 1px solid #e5e7eb !important;
    padding: 0px !important;
    margin: 0 !important;
    display: block !important;
    visibility: visible !important;
    opacity: 1 !important;
    transform: none !important;
}

/* Super aggressive override for inline styles */
nav#sticky[style] {
    top: 0 !important;
    position: fixed !important;
    display: block !important;
}

.header .navbar.scrolled {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12) !important;
}

/* Add padding to body to prevent content from hiding under fixed navbar */
body {
    padding-top: 114px !important; /* Space for fixed navbar */
}

/* Adjust for mobile */
@media (max-width: 1024px) {
    body {
        padding-top: 70px !important; /* Smaller space for mobile header */
    }
    
    .body_wrapper {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
}

/* Logo is now visible in navbar */

/* Keyboard shortcut styling */
.cmgalaxy-search-input::placeholder {
    color: #9ca3af;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var trigger = document.getElementById('cmgProfileTrigger');
    var menu = document.getElementById('cmgProfileDropdownMenu');

    if (trigger && menu) {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            var isOpen = menu.classList.contains('show');
            if (isOpen) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.add('show');
                trigger.classList.add('active');
                trigger.setAttribute('aria-expanded', 'true');
            }
        });

        document.addEventListener('click', function(e) {
            if (!trigger.contains(e.target) && !menu.contains(e.target)) {
                menu.classList.remove('show');
                trigger.classList.remove('active');
                trigger.setAttribute('aria-expanded', 'false');
            }
        });
    }
});
</script>

