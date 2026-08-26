<?php
/**
 * CMGalaxy Paywall / Upgrade Modal Component
 * 
 * Usage:
 * get_template_part('template-parts/modal-upgrade');
 */

$default_signin = get_page_by_path('signin') ? home_url('/signin/') : wp_login_url(get_permalink());
$default_upgrade = get_page_by_path('signup') ? home_url('/signup/') : (get_page_by_path('pricing') ? home_url('/pricing/') : wp_registration_url());

$upgrade_url = isset($args['upgrade_url']) ? $args['upgrade_url'] : $default_upgrade;
$signin_url  = isset($args['signin_url']) ? $args['signin_url'] : $default_signin;
$modal_id    = isset($args['id']) ? $args['id'] : 'cmg-upgrade-modal';
$is_popup    = isset($args['is_popup']) && $args['is_popup'];
?>

<?php if ($is_popup): ?>
<div class="cmg-modal-overlay" id="<?php echo esc_attr($modal_id); ?>" role="dialog" aria-modal="true">
<?php endif; ?>

<div class="cmg-upgrade-card <?php echo $is_popup ? 'cmg-popup-content' : ''; ?>">
    <?php if ($is_popup): ?>
    <button type="button" class="cmg-modal-close" aria-label="Close" onclick="this.closest('.cmg-modal-overlay').classList.remove('active')">&times;</button>
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
