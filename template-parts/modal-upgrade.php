<?php
/**
 * CMGalaxy Paywall / Upgrade Modal Component
 * 
 * Usage:
 * get_template_part('template-parts/modal-upgrade');
 */

$current_page_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$default_signin   = add_query_arg('redirect_to', urlencode($current_page_url), home_url('/signin/'));
$default_upgrade  = 'https://cmgalaxy.com/book-a-demo';

$upgrade_url = isset($args['upgrade_url']) ? $args['upgrade_url'] : $default_upgrade;
$signin_url  = isset($args['signin_url']) ? $args['signin_url'] : $default_signin;
$modal_id    = isset($args['id']) ? $args['id'] : 'cmg-upgrade-modal';
$is_popup    = isset($args['is_popup']) && $args['is_popup'];
?>

<style>
/* =============================================
   CMGalaxy Upgrade Modal & Paywall Gate Styles
   ============================================= */
.cmg-paywall-container {
    position: relative !important;
    width: 100% !important;
    margin-top: 10px !important;
}

.cmg-teaser-content {
    color: #334155 !important;
    font-size: 16px !important;
    line-height: 1.75 !important;
    margin-bottom: 0 !important;
}

.cmg-teaser-content p {
    margin-bottom: 1.25rem !important;
    color: #334155 !important;
    font-size: 16px !important;
    line-height: 1.75 !important;
}

.cmg-paywall-gate {
    position: relative !important;
    margin-top: 0 !important;
    padding-bottom: 30px !important;
    width: 100% !important;
}

.cmg-blurred-backdrop {
    user-select: none !important;
    pointer-events: none !important;
    filter: blur(1.5px) !important;
    opacity: 0.35 !important;
    max-height: 180px !important;
    overflow: hidden !important;
    mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 80%) !important;
    -webkit-mask-image: linear-gradient(to bottom, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 80%) !important;
    margin-bottom: -60px !important;
}

.cmg-paywall-card-wrap {
    position: relative !important;
    z-index: 10 !important;
    display: flex !important;
    justify-content: center !important;
    width: 100% !important;
    margin-top: 20px !important;
}

/* Modal Box */
.cmg-upgrade-card {
    background: #ffffff !important;
    border: 1.5px solid #3b82f6 !important;
    border-radius: 28px !important;
    max-width: 580px !important;
    width: 100% !important;
    padding: 48px 36px 40px !important;
    text-align: center !important;
    box-shadow: 0 10px 30px -10px rgba(59, 130, 246, 0.12), 0 20px 40px -15px rgba(0, 0, 0, 0.08) !important;
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    box-sizing: border-box !important;
    margin: 20px auto !important;
    position: relative !important;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif !important;
}

.cmg-lock-icon-wrap {
    margin-bottom: 22px !important;
    display: inline-flex !important;
    align-items: center !important;
    justify-content: center !important;
}

.cmg-lock-icon {
    width: 44px !important;
    height: 48px !important;
    display: block !important;
}

.cmg-upgrade-title {
    font-size: 30px !important;
    font-weight: 700 !important;
    line-height: 1.25 !important;
    color: #0f172a !important;
    letter-spacing: -0.025em !important;
    margin: 0 0 16px 0 !important;
    padding: 0 !important;
    text-align: center !important;
    font-family: inherit !important;
}

.cmg-upgrade-subtitle {
    font-size: 19px !important;
    font-weight: 600 !important;
    line-height: 1.35 !important;
    color: #1e293b !important;
    letter-spacing: -0.015em !important;
    margin: 0 0 12px 0 !important;
    padding: 0 !important;
    text-align: center !important;
    font-family: inherit !important;
}

.cmg-upgrade-desc {
    font-size: 15px !important;
    font-weight: 400 !important;
    line-height: 1.5 !important;
    color: #64748b !important;
    margin: 0 0 26px 0 !important;
    padding: 0 !important;
    max-width: 460px !important;
    text-align: center !important;
    font-family: inherit !important;
}

.cmg-upgrade-btn {
    display: inline-block !important;
    background-color: #2f73f6 !important;
    color: #ffffff !important;
    font-size: 15px !important;
    font-weight: 500 !important;
    text-decoration: none !important;
    padding: 13px 32px !important;
    border-radius: 8px !important;
    border: 1px solid transparent !important;
    cursor: pointer !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 2px 6px rgba(47, 115, 246, 0.25) !important;
    margin: 0 0 18px 0 !important;
    font-family: inherit !important;
}

.cmg-upgrade-btn:hover {
    background-color: #1d5ed8 !important;
    box-shadow: 0 4px 12px rgba(47, 115, 246, 0.35) !important;
    transform: translateY(-1px) !important;
    color: #ffffff !important;
}

.cmg-upgrade-btn:active {
    transform: translateY(0) !important;
    box-shadow: 0 1px 3px rgba(47, 115, 246, 0.2) !important;
}

.cmg-signin-text {
    font-size: 14px !important;
    color: #475569 !important;
    text-align: center !important;
    margin: 0 !important;
    padding: 0 !important;
    font-family: inherit !important;
}

.cmg-signin-link {
    color: #475569 !important;
    text-decoration: underline !important;
    text-underline-offset: 3px !important;
    transition: color 0.15s ease !important;
    cursor: pointer !important;
}

.cmg-signin-link:hover {
    color: #1e293b !important;
}

/* Popup Overlay */
.cmg-modal-overlay {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    background: rgba(15, 23, 42, 0.65) !important;
    backdrop-filter: blur(8px) !important;
    -webkit-backdrop-filter: blur(8px) !important;
    z-index: 999999 !important;
    display: none !important;
    align-items: center !important;
    justify-content: center !important;
    padding: 20px !important;
    box-sizing: border-box !important;
}

.cmg-modal-overlay.active {
    display: flex !important;
    animation: cmgOverlayFadeIn 0.25s ease forwards !important;
}

.cmg-modal-overlay .cmg-upgrade-card {
    max-height: 90vh !important;
    overflow-y: auto !important;
    margin: 0 !important;
    animation: cmgModalSlideUp 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
}

@keyframes cmgOverlayFadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

@keyframes cmgModalSlideUp {
    from {
        opacity: 0;
        transform: scale(0.92) translateY(16px);
    }
    to {
        opacity: 1;
        transform: scale(1) translateY(0);
    }
}

.cmg-modal-close {
    position: absolute !important;
    top: 18px !important;
    right: 22px !important;
    background: transparent !important;
    border: none !important;
    font-size: 30px !important;
    line-height: 1 !important;
    color: #94a3b8 !important;
    cursor: pointer !important;
    transition: color 0.15s ease, transform 0.15s ease !important;
    padding: 4px 8px !important;
    z-index: 10 !important;
}

.cmg-modal-close:hover {
    color: #0f172a !important;
    transform: scale(1.1) !important;
}

@media (max-width: 600px) {
    .cmg-upgrade-card {
        padding: 36px 20px 30px !important;
        border-radius: 20px !important;
    }

    .cmg-upgrade-title {
        font-size: 24px !important;
        margin-bottom: 14px !important;
    }

    .cmg-upgrade-subtitle {
        font-size: 17px !important;
        margin-bottom: 10px !important;
    }

    .cmg-upgrade-desc {
        font-size: 14px !important;
        margin-bottom: 24px !important;
    }

    .cmg-upgrade-btn {
        width: 100% !important;
        padding: 12px 20px !important;
    }
}
</style>

<?php if ($is_popup): ?>
<div class="cmg-modal-overlay" id="<?php echo esc_attr($modal_id); ?>" role="dialog" aria-modal="true">
<?php endif; ?>

<div class="cmg-upgrade-card <?php echo $is_popup ? 'cmg-popup-content' : ''; ?>">
    <?php if ($is_popup): ?>
    <button type="button" class="cmg-modal-close" aria-label="Close" onclick="if(window.cmgCloseUpgradeModal){window.cmgCloseUpgradeModal();}else{this.closest('.cmg-modal-overlay').classList.remove('active');}">&times;</button>
    <?php endif; ?>

    <!-- Lock Icon with 3 dots inside -->
    <div class="cmg-lock-icon-wrap">
        <svg class="cmg-lock-icon" viewBox="0 0 44 48" fill="none" xmlns="http://www.w3.org/2000/svg">
            <!-- Shackle -->
            <path d="M12.5 19V11.5C12.5 6.25329 16.7533 2 22 2C27.2467 2 31.5 6.25329 31.5 11.5V19" stroke="#334155" stroke-width="2.6" stroke-linecap="round"/>
            <!-- Body -->
            <rect x="2.5" y="19" width="39" height="26.5" rx="8" stroke="#334155" stroke-width="2.6" fill="none"/>
            <!-- 3 dots -->
            <circle cx="16" cy="32.5" r="1.5" fill="#334155"/>
            <circle cx="22" cy="32.5" r="1.5" fill="#334155"/>
            <circle cx="28" cy="32.5" r="1.5" fill="#334155"/>
        </svg>
    </div>

    <!-- Main Heading -->
    <h2 class="cmg-upgrade-title">
        Access everything. Upgrade<br>to a paid account.
    </h2>

    <!-- Subheading -->
    <h3 class="cmg-upgrade-subtitle">
        Unlock the complete CMGalaxy Knowledge Base.
    </h3>

    <!-- Description -->
    <p class="cmg-upgrade-desc">
        Get full access to premium CMGalaxy documentation, guides and resources.
    </p>

    <!-- Primary Action Button -->
    <a href="<?php echo esc_url($upgrade_url); ?>" class="cmg-upgrade-btn">
        Upgrade to Paid Account
    </a>

    <!-- Sign-in Link -->
    <div class="cmg-signin-text">
        <a href="<?php echo esc_url($signin_url); ?>" class="cmg-signin-link">Already paid? Sign in</a>
    </div>
</div>

<?php if ($is_popup): ?>
</div>
<?php endif; ?>
