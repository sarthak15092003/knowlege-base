<?php
$opt = get_option( 'docy_opt' );

// Re-arrange the related products, upsell product
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
add_action( 'woocommerce_single_product_after_main_content', 'woocommerce_upsell_display', 20);
add_action( 'woocommerce_single_product_after_main_content', 'woocommerce_output_related_products', 25);

// Enabling the gallery in themes that declare
add_theme_support( 'wc-product-gallery-zoom' );
add_theme_support( 'wc-product-gallery-lightbox' );
add_theme_support( 'wc-product-gallery-slider' );

// Product Gallery thumbnail size
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
	return array(
		'width' => 120,
		'height' => 140,
		'crop' => 1,
	);
} );

// WooCommerce review list
function docy_woocommerce_comments($comment, $args, $depth) {
    $GLOBALS['comment'] = $comment;
    extract($args, EXTR_SKIP);
    ?>
    <li class="post-comment" id="comment-<?php comment_ID() ?>">
        <div class="comment-content">
            <a href="#" class="avatar">
                <?php echo get_avatar($comment, 70); ?>
            </a>
            <div class="post-body">
                <div class="comment-header">
                    <a href="#"> <?php comment_author(); ?> </a>
                    <?php echo get_comment_time(get_option( 'date_format')); ?>
                </div>
                <div class="rating">
                    <?php woocommerce_review_display_rating() ?>
                </div>
                <?php comment_text() ?>
                <div class="hr mt_30 mb-0"></div>
            </div>
        </div>
    </li>
    <?php
}

// Add/remove fields from checkout
function docy_disable_checkout_fields($fields) {
    $remove_fields = docy_opt('remove_checkout_fields');
    // Unset $remove_fields with foreach loop
    if ( !empty($remove_fields) ) {
	    foreach ( $remove_fields as $field ) {
		    unset( $fields['billing'][ 'billing_' . $field ] );
		    unset( $fields['shipping'][ 'shipping_' . $field ] );
	    }
    }

	// if the last name is unset then the first name will be full width
	if ( isset( $fields['billing']['billing_last_name'] ) ) {
		$fields['billing']['billing_first_name']['class'] = array( 'col-md-6' );
		$fields['shipping']['shipping_first_name']['class'] = array( 'col-md-6' );
	} else {
		$fields['billing']['billing_first_name']['class'] = array( 'col-md-12' );
		$fields['shipping']['shipping_first_name']['class'] = array( 'col-md-12' );
	}
	return $fields;
}
add_filter('woocommerce_checkout_fields', 'docy_disable_checkout_fields');


// Ajax add to cart
/**
 * Get the "add to cart" button.
 *
 * @param \WC_Product $product Product.
 * @return string Rendered product output.
 */
function docy_get_add_to_cart( $product ) {
	$attributes = array(
		'aria-label'       => $product->add_to_cart_description(),
		'data-quantity'    => '1',
		'data-product_id'  => $product->get_id(),
		'data-product_sku' => $product->get_sku(),
		'data-price'       => wc_get_price_to_display( $product ),
		'rel'              => 'nofollow',
		'class'            => ( function_exists( 'wc_wp_theme_get_element_class_name' ) ? wc_wp_theme_get_element_class_name( 'button' ) : '' ) . ' add_to_cart_button',
	);

	if (
		$product->supports( 'ajax_add_to_cart' ) &&
		$product->is_purchasable() &&
		( $product->is_in_stock() || $product->backorders_allowed() )
	) {
		$attributes['class'] .= ' ajax_add_to_cart';
	}

	return sprintf(
		'<a href="%s" %s>%s</a>',
		esc_url( $product->add_to_cart_url() ),
		wc_implode_html_attributes( $attributes ),
		esc_html( $product->add_to_cart_text() )
	);
}