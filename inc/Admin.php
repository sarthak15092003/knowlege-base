<?php
namespace docy\inc;

class Admin
{

    private string $menu_slug = 'docy_template';

    public function __construct() {

        add_action( 'admin_menu', array( $this, 'admin_menu' ) );
        add_action('admin_head', array($this, 'custom_admin_css'));
    }

    public function admin_menu() {

        if ( ! current_user_can( 'manage_options' ) ) {
            return;
        }

        $menu_slug  = $this->menu_slug;
        $capability = 'manage_options';

        // Add a top-level menu page
        add_menu_page(
            esc_html__('Docy', 'docy'),           // Page title
            esc_html__('Docy', 'docy'),     // Menu title
            $capability,                     // Capability
            $menu_slug,                      // Menu slug
            array($this, 'docy_welcome_page'),    // Callback function
            'dashicons-feedback',                 // Dashicons icon
            2                                    // Position
        );


        // Add the Dashboard Submenu.
        add_submenu_page(
            $menu_slug,
            esc_html__( 'Docy', 'docy' ),
            esc_html__( 'Welcome', 'docy' ),
            $capability,
            $menu_slug,
            array( $this, 'docy_welcome_page' )
        );

        // Add the Register/Verify Submenu
        add_submenu_page(
            $menu_slug,
            esc_html__('Register/Verify', 'docy'),
            esc_html__('Register/Verify', 'docy'),
            $capability,
            'docy_verify',
            [$this, 'register_verify_page']
        );

        // Add the Header Template Submenu
        if (post_type_exists('docy_header') ) {
            add_submenu_page(
                $menu_slug,
                esc_html__( 'Headers', 'docy' ),
                esc_html__( 'Headers', 'docy' ),
                $capability,
                'edit.php?post_type=docy_header',
                false
            );
        }

        // Add the Footers Template Submenu
        if (post_type_exists('docy_footer') ) {
            add_submenu_page(
                $menu_slug,
                esc_html__( 'Footers', 'docy' ),
                esc_html__( 'Footers', 'docy' ),
                $capability,
                'edit.php?post_type=docy_footer',
                false
            );
        }

    }

    public function docy_welcome_page() {
        ?>
        <div class="wrap docy_welcome_page">
            <div class="container">

                <div class="full_width">
                    <h2><?php esc_html_e('Welcome to the Docy Theme', 'docy'); ?></h2>
                    <p><?php esc_html_e('Docy is the ultimate WordPress theme for creating comprehensive documentation, knowledge base, and LMS websites. Our theme is loaded with powerful features, seamless integration with popular plugins, and extensive customization options to suit your needs.', 'docy'); ?></p>
                </div>

                <div class="left_content">
                    <p><?php esc_html_e('Watch this video to get an overview of the Docy Theme and its features.', 'docy'); ?></p>
                    <iframe width="100%" height="530" src="https://www.youtube.com/embed/eEaDZ4d7oQU" allowfullscreen></iframe>
                    <a href="https://www.youtube.com/playlist?list=PLeCjxMdg411XsM8AxV4My_MlU483jUlYI" target="_blank" class="button button-primary">
                        <?php esc_html_e('Watch More Videos', 'docy'); ?>
                    </a>
                </div>

                <div class="right_content">
                    <div class="grid-item">
                        <h2>
                            <span class="icon">
                                <svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M24 4.1v13.8c0 .7-.5 1.2-1.2 1.3-2 .1-6.1.5-8.9 1.9-.4.2-.9-.1-.9-.6V5.7c0-.2.1-.4.3-.5 2.7-1.7 7.2-2.1 9.4-2.3.7-.1 1.3.5 1.3 1.2zM1.4 2.9C.6 2.8 0 3.4 0 4.1v13.8c0 .7.5 1.2 1.2 1.3 2 .1 6.1.5 8.9 1.9.4.2.9-.1.9-.5V5.7c0-.2-.1-.4-.3-.5C8.1 3.5 3.6 3 1.4 2.9z"></path>
                                </svg>
                            </span>
                            <?php esc_html_e('Knowledge Base', 'docy'); ?>
                        </h2>
                        <p><?php esc_html_e('Find the full documentation for the Docy Theme:', 'docy'); ?></p>
                        <a href="https://helpdesk.spider-themes.net/docs/docy-wordpress-theme/" target="_blank" class="button button-primary">
                            <?php esc_html_e('View Documentation', 'docy'); ?>
                        </a>
                    </div>

                    <div class="grid-item">
                        <h2>
                            <span class="icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8.3 7.3 4.1 3C6.2 1.1 9 0 12 0s5.8 1.1 7.9 3l-4.3 4.3C14.6 6.5 13.4 6 12 6s-2.6.5-3.7 1.3zM12 18c-1.4 0-2.6-.5-3.7-1.3L4.1 21c2.1 1.9 4.9 3 7.9 3s5.8-1.1 7.9-3l-4.3-4.3c-1 .8-2.2 1.3-3.6 1.3zm9-13.9-4.3 4.3c.8 1 1.3 2.3 1.3 3.7s-.5 2.6-1.3 3.7l4.3 4.3c1.9-2.1 3-4.9 3-7.9s-1.1-6-3-8.1zM6 12c0-1.4.5-2.6 1.3-3.7L3 4.1C1.1 6.2 0 9 0 12s1.1 5.8 3 7.9l4.3-4.3C6.5 14.6 6 13.4 6 12z"></path>
                                </svg>
                            </span>
                            <?php esc_html_e('Support', 'docy'); ?>
                        </h2>
                        <p><?php esc_html_e('If your questions that have not been answered by our documentation or video tutorials, just drop us a line.', 'docy'); ?></p>
                        <a href="https://helpdesk.spider-themes.net/forums/forum/docy-wordpress/" target="_blank" class="button button-primary">
                            <?php esc_html_e('Submit a Ticket', 'docy'); ?>
                        </a>
                    </div>

                    <div class="grid-item">
                        <h2>
                            <span class="icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-7 14H8v-2h4v2zm4-4H8v-2h8v2zm0-4H8V7h8v2z"></path>
                                </svg>
                            </span>
                            <?php esc_html_e('Changelogs', 'docy'); ?>
                        </h2>
                        <p><?php esc_html_e('View the changelogs for the latest updates and features:', 'docy'); ?></p>
                        <a href="https://themeforest.net/item/docy-documentation-and-forum-wordpress-theme/31370838#item-description__changelog" target="_blank" class="button button-primary">
                            <?php esc_html_e('View Changelogs', 'docy'); ?>
                        </a>
                    </div>

                    <div class="grid-item">
                        <h2>
                            <span class="icon">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M12 17.27L18.18 21 16.54 13.97 22 9.24 14.81 8.63 12 2 9.19 8.63 2 9.24 7.46 13.97 5.82 21 12 17.27z"></path>
                                </svg>
                            </span>
                            <?php esc_html_e('Rate Us', 'docy'); ?>
                        </h2>
                        <p><?php esc_html_e('We love to hear from you, we would appreciate every single review.', 'docy'); ?></p>
                        <a href="https://themeforest.net/item/docy-documentation-and-forum-wordpress-theme/reviews/31370838" target="_blank" class="button button-primary">
                            <?php esc_html_e('Submit a Review', 'docy'); ?>
                        </a>
                    </div>

                </div>


            </div>

        </div>
        <?php

    }

    public function register_verify_page()
    {
        // Include the dashboard.php file for the Register/Verify page
        include_once( get_template_directory() . '/inc/admin/dashboard/dashboard.php' );
    }

    public function custom_admin_css() {
        // Inject custom CSS to hide the top-level menu item and admin notices on the welcome page
        echo '<style>
            .toplevel_page_docy_template .notice, div.fs-notice.success, div.fs-notice.updated {
                display: none !important;
            }
        </style>';
    }

}

new Admin();