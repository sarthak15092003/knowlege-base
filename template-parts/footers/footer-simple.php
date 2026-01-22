<?php
$opt = get_option('docy_opt' );
?>
<footer class="simple_footer simple_footer--center">
    <div class="container">
        <div class="text-center py-5">
            <?php
            // Brand (text or logo)
            $site_name = 'CMGalaxy';
            echo '<div class="footer-brand mb-3">' . esc_html( $site_name ) . '</div>';

            // Copyright line
            $year = date_i18n( 'Y' );
            $brand = esc_html( $site_name );
            echo '<div class="footer-copy mb-3">&copy; ' . $brand . ' ' . esc_html( $year ) . '</div>';
            ?>

            <ul class="list-unstyled d-inline-flex gap-3 m-0 footer-social">
                <?php Docy_helper()->social_links(); ?>
            </ul>
        </div>
    </div>
</footer>