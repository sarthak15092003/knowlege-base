<?php
$opt = get_option( 'docy_opt' );

$bg_image = ! empty( $opt['forum_btm_c2a_bg']['url'] ) ? $opt['forum_btm_c2a_bg']['url'] : '';
?>
<div class="call-to-action">
    <div class="overlay-bg" style="background-image: url( '<?php echo esc_url( $bg_image ) ?>'' );"></div>
    <div class="container">
        <div class="action-content-wrapper">
            <div class="action-title-wrap title-img">
				<?php if ( ! empty( $opt['forum_btm_c2a_logo']['url'] ) ) : ?>
                    <img src="<?php echo esc_url( $opt['forum_btm_c2a_logo']['url'] ) ?>" alt="<?php echo esc_attr( $opt['forum_btm_c2a_title'] ) ?>">
				<?php endif; ?>
				<?php if ( ! empty( $opt['forum_btm_c2a_title'] ) ) : ?>
                    <h2 class="action-title"><?php echo wp_kses( $opt['forum_btm_c2a_title'], docy_allowed_html() ) ?></h2>
				<?php endif; ?>
            </div>
			<?php if ( ! empty( $opt['forum_top_c2a_btn_title'] ) ) : ?>
                <a href="<?php echo esc_url( $opt['forum_top_c2a_btn_url'] ) ?>" class="action_btn">
					<?php echo esc_html( $opt['forum_top_c2a_btn_title'] ) ?> <i class="<?php docy_arrow_left_right() ?>"></i>
                </a>
			<?php endif; ?>
        </div>
        <!-- /.action-content-wrapper -->
    </div>
    <!-- /.container -->
</div>