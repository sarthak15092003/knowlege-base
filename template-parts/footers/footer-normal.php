<?php
$opt = get_option('docy_opt');
$footer_visibility = docy_meta('footer_visibility', '1');
?>
<style>
/* CMGalaxy Footer Styles - Inline for immediate effect */
footer {
    background-color: #041543 !important;
    color: rgba(250, 250, 250, 0.5) !important;
    padding: 40px 0px !important;
    font-family: Arial, sans-serif !important;
   
}
    


.footer-content {
    width: 100% !important;
}

.footer-row {
    display: flex !important;
    justify-content: space-between !important;
    align-items: top !important;
    flex-wrap: wrap !important;
    margin-bottom: 20px !important;
}

.footer-logo img {
    max-height: 50px !important;
}

.footer-links {
    display: flex !important;
    gap: 30px !important;
}

.footer-links a {
    color: #ffffff !important;
    text-decoration: none !important;
    transition: color 0.3s ease, opacity 0.3s ease !important;
    opacity: 0.5 !important;
}

.footer-links a:hover {
    color: #60A5FA !important;
    opacity: 1 !important;
}

.footer-social p {
    margin-bottom: 10px !important;
    text-align: left !important;
    padding-left: 10px !important;
}

.social-icons {
    display: flex !important;
    gap: 15px !important;
}

.social-icon {
    color: #ffffff !important;
    font-size: 18px !important;
    transition: color 0.3s ease !important;
}

.social-icon:hover {
    color: #00c2ff !important;
}

.footer-copyright {
    text-align: start !important;
    border-top: 1px solid rgba(255, 255, 255, 0.1) !important;
    padding-top: 20px !important;
}

.footer-copyright p {
    color: #ffffff !important;
}

.footer-copyright a {
    color: #ffffff !important;
    text-decoration: none !important;
}

.footer-copyright a:hover {
    color: #00c2ff !important;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .footer-row {
        flex-direction: column !important;
        text-align: center !important;
        gap: 20px !important;
    }
    
    .footer-links, .footer-social {
        width: 100% !important;
        justify-content: center !important;
    }
    
    .footer-social p {
        text-align: center !important;
    }
}
</style>

<footer>
    <div class="container">
        <div class="footer-content">
            <div class="footer-row">
                <!-- Logo Section -->
                <div class="footer-logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/logo.png" alt="CMGalaxy Logo">
                    </a>
                </div>
                
                <!-- Links Section -->
                <div class="footer-links">
                    <a href="<?php echo esc_url(home_url('/privacy-policy')); ?>">Privacy Policy</a>
                    <a href="<?php echo esc_url(home_url('/terms-conditions')); ?>">Terms & Conditions</a>
                </div>
                
                <!-- Social Media Section -->
                <div class="footer-social">
                    <p>Follow on Social Media</p>
                    <div class="social-icons">
                        <a href="#" class="social-icon"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="social-icon"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
            
            <!-- Copyright Section -->
            <div class="footer-copyright">
                <p>Copyright © <?php echo date('Y'); ?> <a href="<?php echo esc_url(home_url('/')); ?>">CMGalaxy</a> | All Rights Reserved</p>
            </div>
        </div>
    </div>
</footer>