<?php
/**
 * Template Name: Sign in Page
 */

// If user is already logged in, redirect to requested article or home
if ( is_user_logged_in() && ! isset( $_GET['action'] ) ) {
    $redirect_url = ! empty( $_GET['redirect_to'] ) ? esc_url_raw( $_GET['redirect_to'] ) : home_url( '/' );
    wp_safe_redirect( $redirect_url );
    exit;
}

get_header( 'empty' );

$redirect_to = ! empty( $_GET['redirect_to'] ) ? esc_url( $_GET['redirect_to'] ) : home_url( '/' );
$ajax_url    = admin_url( 'admin-ajax.php' );
?>

<style>
/* =============================================
   CMGalaxy Dedicated Sign-in Page Styles
   ============================================= */
.cmg-signin-wrapper {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: #ffffff;
    padding: 30px 20px;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
    box-sizing: border-box;
}

.cmg-signin-card {
    max-width: 440px;
    width: 100%;
    margin: 0 auto;
    padding: 20px 10px;
    box-sizing: border-box;
}

/* Header */
.cmg-signin-header {
    margin-bottom: 28px;
    text-align: left;
}

.cmg-signin-title-row {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
}

.cmg-signin-title {
    font-size: 30px;
    font-weight: 800;
    color: #0f172a;
    letter-spacing: -0.03em;
    margin: 0;
    line-height: 1.2;
}

.cmg-brand-logo {
    height: 32px;
    width: auto;
    display: inline-block;
    vertical-align: middle;
}

.cmg-signin-subtitle {
    font-size: 16px;
    color: #64748b;
    font-weight: 400;
    margin: 0;
}

/* Form Styles */
.cmg-signin-form {
    width: 100%;
}

.cmg-form-group {
    margin-bottom: 22px;
    text-align: left;
}

.cmg-form-label {
    display: block;
    font-size: 15px;
    font-weight: 700;
    color: #0f172a;
    margin-bottom: 8px;
}

.cmg-form-label.has-error {
    color: #ef4444;
}

.cmg-input-wrapper {
    position: relative;
    width: 100%;
}

.cmg-input {
    width: 100%;
    height: 48px;
    padding: 10px 16px;
    background-color: #f8fafc;
    border: 1.5px solid #e2e8f0;
    border-radius: 8px;
    font-size: 15px;
    color: #0f172a;
    transition: all 0.2s ease;
    box-sizing: border-box;
    outline: none;
    font-family: inherit;
}

.cmg-input::placeholder {
    color: #94a3b8;
}

.cmg-input:focus {
    background-color: #ffffff;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.cmg-input.has-error {
    border-color: #ef4444;
    background-color: #fef2f2;
}

/* Password Eye Toggle */
.cmg-password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: #64748b;
    cursor: pointer;
    padding: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: color 0.15s ease;
}

.cmg-password-toggle:hover {
    color: #0f172a;
}

.cmg-error-msg {
    font-size: 13px;
    color: #ef4444;
    margin-top: 6px;
    display: none;
    font-weight: 500;
}

.cmg-error-msg.active {
    display: block;
}

/* Global Alert Error */
.cmg-alert-error {
    background-color: #fef2f2;
    border: 1px solid #fecaca;
    color: #b91c1c;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    font-weight: 500;
    display: none;
}

.cmg-alert-error.active {
    display: block;
}

.cmg-alert-success {
    background-color: #f0fdf4;
    border: 1px solid #bbf7d0;
    color: #15803d;
    padding: 12px 16px;
    border-radius: 8px;
    font-size: 14px;
    margin-bottom: 20px;
    font-weight: 600;
    display: none;
}

.cmg-alert-success.active {
    display: block;
}

/* Submit Button */
.cmg-submit-btn {
    width: 100%;
    height: 48px;
    background-color: #3b82f6;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    font-size: 16px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-top: 26px;
    box-shadow: 0 2px 6px rgba(59, 130, 246, 0.25);
    font-family: inherit;
}

.cmg-submit-btn:hover {
    background-color: #2563eb;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.35);
    transform: translateY(-1px);
}

.cmg-submit-btn:active {
    transform: translateY(0);
}

.cmg-submit-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Footer Links */
.cmg-signin-footer {
    margin-top: 24px;
    text-align: center;
    font-size: 14px;
    color: #475569;
}

.cmg-signup-link {
    color: #2563eb;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.15s ease;
}

.cmg-signup-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

.cmg-forgot-wrap {
    margin-top: 10px;
    text-align: center;
}

.cmg-forgot-link {
    color: #2563eb;
    font-size: 14px;
    font-weight: 700;
    text-decoration: none;
    transition: color 0.15s ease;
}

.cmg-forgot-link:hover {
    color: #1d4ed8;
    text-decoration: underline;
}

/* Spinner */
.cmg-spinner {
    width: 20px;
    height: 20px;
    border: 2.5px solid rgba(255, 255, 255, 0.3);
    border-radius: 50%;
    border-top-color: #ffffff;
    animation: cmgSpin 0.7s linear infinite;
    display: inline-block;
    margin-right: 8px;
}

@keyframes cmgSpin {
    to { transform: rotate(360deg); }
}

@media (max-width: 480px) {
    .cmg-signin-title {
        font-size: 26px;
    }
    .cmg-brand-logo {
        height: 28px;
    }
}
</style>

<div class="cmg-signin-wrapper">
    <div class="cmg-signin-card">
        
        <!-- Header -->
        <div class="cmg-signin-header">
            <div class="cmg-signin-title-row">
                <h1 class="cmg-signin-title">Welcome to</h1>
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/img/kblogo.svg' ); ?>" alt="CMGALAXY" class="cmg-brand-logo">
            </div>
            <p class="cmg-signin-subtitle">Please sign-in to your account</p>
        </div>

        <div class="cmg-alert-error" id="globalErrorAlert"></div>
        <div class="cmg-alert-success" id="globalSuccessAlert"></div>

        <!-- Sign-in Form (Submits via AJAX) -->
        <form class="cmg-signin-form" id="cmgLoginForm" novalidate>
            
            <input type="hidden" id="cmg_redirect_to" value="<?php echo esc_attr( $redirect_to ); ?>">

            <!-- Email Field -->
            <div class="cmg-form-group">
                <label for="cmg_email" class="cmg-form-label" id="emailLabel">Email</label>
                <div class="cmg-input-wrapper">
                    <input type="email" 
                           id="cmg_email" 
                           name="email" 
                           class="cmg-input" 
                           placeholder="Enter your email address" 
                           autocomplete="username" 
                           required>
                </div>
                <div class="cmg-error-msg" id="emailError">Email is required.</div>
            </div>

            <!-- Password Field -->
            <div class="cmg-form-group">
                <label for="cmg_password" class="cmg-form-label" id="passwordLabel">Password</label>
                <div class="cmg-input-wrapper">
                    <input type="password" 
                           id="cmg_password" 
                           name="password" 
                           class="cmg-input" 
                           placeholder="Enter password" 
                           autocomplete="current-password" 
                           required>
                    <button type="button" class="cmg-password-toggle" id="togglePasswordBtn" aria-label="Show password">
                        <svg id="eyeIcon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                            <circle cx="12" cy="12" r="3"></circle>
                        </svg>
                    </button>
                </div>
                <div class="cmg-error-msg" id="passwordError">Password is required.</div>
            </div>

            <!-- Sign in Button -->
            <button type="submit" class="cmg-submit-btn" id="submitBtn">
                Sign in
            </button>

            <!-- Footer Links -->
            <div class="cmg-signin-footer">
                Don't have an account? <a href="https://cmgalaxy.com/book-a-demo" target="_blank" rel="noopener noreferrer" class="cmg-signup-link">Sign Up</a>
                <div class="cmg-forgot-wrap">
                    <a href="https://platform.cmgalaxy.com/forgot-password" target="_blank" rel="noopener noreferrer" class="cmg-forgot-link">Forgot Password?</a>
                </div>
            </div>

        </form>

    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var passwordInput = document.getElementById('cmg_password');
    var toggleBtn = document.getElementById('togglePasswordBtn');
    var emailInput = document.getElementById('cmg_email');
    var form = document.getElementById('cmgLoginForm');
    var emailError = document.getElementById('emailError');
    var passwordError = document.getElementById('passwordError');
    var emailLabel = document.getElementById('emailLabel');
    var passwordLabel = document.getElementById('passwordLabel');
    var submitBtn = document.getElementById('submitBtn');
    var globalError = document.getElementById('globalErrorAlert');
    var globalSuccess = document.getElementById('globalSuccessAlert');
    var redirectTo = document.getElementById('cmg_redirect_to').value;
    var ajaxUrl = <?php echo json_encode( $ajax_url ); ?>;

    // Toggle Password Visibility
    if (toggleBtn && passwordInput) {
        toggleBtn.addEventListener('click', function() {
            var type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            if (type === 'text') {
                toggleBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>';
            } else {
                toggleBtn.innerHTML = '<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>';
            }
        });
    }

    // Input cleanup listeners
    if (emailInput) {
        emailInput.addEventListener('input', function() {
            emailInput.classList.remove('has-error');
            emailLabel.classList.remove('has-error');
            emailError.classList.remove('active');
            globalError.classList.remove('active');
        });
    }
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            passwordInput.classList.remove('has-error');
            passwordLabel.classList.remove('has-error');
            passwordError.classList.remove('active');
            globalError.classList.remove('active');
        });
    }

    // AJAX Form Submission
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            var email = emailInput.value.trim();
            var password = passwordInput.value;
            var hasError = false;

            globalError.classList.remove('active');
            globalSuccess.classList.remove('active');

            if (!email) {
                emailInput.classList.add('has-error');
                emailLabel.classList.add('has-error');
                emailError.classList.add('active');
                hasError = true;
            }

            if (!password) {
                passwordInput.classList.add('has-error');
                passwordLabel.classList.add('has-error');
                passwordError.classList.add('active');
                hasError = true;
            }

            if (hasError) {
                return;
            }

            // Loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="cmg-spinner"></span> Signing in...';

            var formData = new FormData();
            formData.append('action', 'cmg_ajax_login');
            formData.append('email', email);
            formData.append('password', password);
            formData.append('redirect_to', redirectTo);

            fetch(ajaxUrl, {
                method: 'POST',
                body: formData,
                credentials: 'same-origin'
            })
            .then(function(res) { return res.json(); })
            .then(function(data) {
                console.log('🔐 CMGalaxy Login Response:', data);
                if (data && data.success) {
                    globalSuccess.innerText = data.data.message || 'Signed in successfully! Redirecting...';
                    globalSuccess.classList.add('active');
                    submitBtn.innerHTML = 'Success!';
                    
                    setTimeout(function() {
                        window.location.href = data.data.redirect || redirectTo || '/';
                    }, 500);
                } else {
                    var errorMsg = (data && data.data && data.data.message) ? data.data.message : 'Invalid email or password. Please try again.';
                    if (data && data.data && data.data.debug) {
                        console.warn('⚠️ API Debug Diagnostics:', data.data.debug);
                    }
                    globalError.innerText = errorMsg;
                    globalError.classList.add('active');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Sign in';
                    passwordInput.classList.add('has-error');
                    passwordLabel.classList.add('has-error');
                }
            })
            .catch(function(err) {
                globalError.innerText = 'Unable to connect to server. Please try again.';
                globalError.classList.add('active');
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Sign in';
            });
        });
    }
});
</script>

<?php
get_footer( 'empty' );