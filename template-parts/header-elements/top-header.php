<div class="top_header">
	<?php
	// Extract the domain from the current URL
	if ( docy_opt( 'top_header_left_items' ) ) {
		?>
        <div class="left_contents">
            <ul class="list-unstyled">
				<?php
				foreach ( docy_opt( 'top_header_left_items' ) as $item ) {
					// Extract the domain from the link URL
					$item_url = ! empty( $item['link'] ) ? $item['link'] : '';

					// Check if the current domain matches the link's domain
					$is_active_class = docy_opt( 'is_active_left_items' ) == '1' && home_url() == $item_url ? 'is-active' : '';
					$btn_target      = $item['link_target'] ?? '_self';
					?>
                    <li class="<?php echo esc_attr( $is_active_class ) ?>">
                        <a href="<?php echo esc_url( $item_url ) ?>" target="<?php echo esc_attr( $btn_target ) ?>">
							<?php echo ! empty( $item['image']['id'] ) ? wp_get_attachment_image( $item['image']['id'], 'full' ) : '' ?>
							<?php echo esc_html( $item['text'] ) ?>
                        </a>
                    </li>
					<?php
				}
				?>
            </ul>
        </div>
		<?php
	}

	if ( docy_opt( 'top_header_right_items' ) ) {
		?>
        <div class="right_contents">
            <ul class="list-unstyled">
				<?php
				foreach ( docy_opt( 'top_header_right_items' ) as $item ) {
					echo '<li><a href="' . esc_url( $item['link'] ) . '">' . esc_html( $item['text'] ) . '</a></li>';
				}
				?>
            </ul>
        </div>
		<?php
	}
	?>
</div>