<?php
/*
* WooCommerce helper functions for theme
*/
// Add support
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//add_theme_support( 'wc-product-gallery-lightbox' );


// Short Description in Archive
function action_woocommerce_after_shop_loop_item_title(  ) { 
    return the_excerpt();
}; 
add_action( 'woocommerce_shop_loop_item_title', 'action_woocommerce_after_shop_loop_item_title', 10, 0 );


// Change cart button text
function woo_custom_single_add_to_cart_text() {
    return __( 'Add to quote basket', 'woocommerce' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );


//Remove billing fields 
function remove_billing_checkout_fields( $fields ) {
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_1']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_phone']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_email']);
    unset($fields['billing']['billing_city']);
    return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'remove_billing_checkout_fields' );


// Order notes
function custom_override_checkout_fields( $fields ) {
	$fields['order']['order_comments']['label'] = 'Delivery details';
	$fields['order']['order_comments']['type'] = 'select';
	$fields['order']['order_comments']['options'] = array(
		'Freight quote' => 'Freight quote',
		'Weight and dimensions' => 'Weight & dims',
		'None' => 'None'
	);
	$fields['shipping']['shipping_company']['required'] = true;
	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );


// Adding extra fields to Registration page and saving to shipping fields meta
function wooc_extra_register_fields() { ?>
	<p class="form-row form-row-wide">
		<label for="reg_shipping_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="shipping_first_name" id="reg_shipping_first_name" value="<?php if ( ! empty( $_POST['shipping_first_name'] ) ) esc_attr_e( $_POST['shipping_first_name'] ); ?>" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_shipping_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="shipping_last_name" id="reg_shipping_last_name" value="<?php if ( ! empty( $_POST['shipping_last_name'] ) ) esc_attr_e( $_POST['shipping_last_name'] ); ?>" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_shipping_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="shipping_phone" id="reg_shipping_phone" value="<?php esc_attr_e( $_POST['shipping_phone'] ); ?>" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_shipping_company"><?php _e( 'Company', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="shipping_company" id="reg_shipping_company" value="<?php esc_attr_e( $_POST['shipping_company'] ); ?>" />
	</p>
	<div class="clear"></div>
	<?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

// Validation for extra fields
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if( isset( $_POST['shipping_first_name'] ) && empty( $_POST['shipping_first_name'] ) ) {
		$validation_errors->add( 'shipping_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}
	if( isset( $_POST['shipping_last_name'] ) && empty( $_POST['shipping_last_name'] ) ) {
		$validation_errors->add( 'shipping_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}
	if( isset( $_POST['shipping_company'] ) && empty( $_POST['shipping_company'] ) ) {
		$validation_errors->add( 'shipping_company_error', __( '<strong>Error</strong>: Company is required!.', 'woocommerce' ) );
	}
	return $validation_errors;
}
add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

// Save to user meta
function wooc_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['shipping_phone'] ) ) {
		update_user_meta( $customer_id, 'shipping_phone', sanitize_text_field( $_POST['shipping_phone'] ) );
	}
	if ( isset( $_POST['shipping_company'] ) ) {
		update_user_meta( $customer_id, 'shipping_company', sanitize_text_field( $_POST['shipping_company'] ) );
	}
	if ( isset( $_POST['shipping_first_name'] ) ) {
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['shipping_first_name'] ) );
		update_user_meta( $customer_id, 'shipping_first_name', sanitize_text_field( $_POST['shipping_first_name'] ) );
	}
	if ( isset( $_POST['shipping_last_name'] ) ) {
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['shipping_last_name'] ) );
		update_user_meta( $customer_id, 'shipping_last_name', sanitize_text_field( $_POST['shipping_last_name'] ) );
	}
	if ( isset( $_POST['email'] ) ) {
		update_user_meta( $customer_id, 'shipping_email', sanitize_text_field( $_POST['email'] ) );
	}
    update_user_meta($customer_id, 'shipping_address_nickname', 'Main address');
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


// Job title additional field in checkout
function my_custom_checkout_field( $checkout ) {
    echo '<div id="my_custom_checkout_field">';
    woocommerce_form_field( 'my_field_name', array(
        'type'          => 'text',
        'class'         => array('my-field-class form-row-wide'),
        'label'         => __('Job title'),
        ), $checkout->get_value( 'my_field_name' ));
    echo '</div>';
}
add_action( 'woocommerce_after_order_notes', 'my_custom_checkout_field' );

function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['my_field_name'] ) ) {
        update_post_meta( $order_id, 'My Field', sanitize_text_field( $_POST['my_field_name'] ) );
    }
}
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );

function my_custom_checkout_field_display_admin_order_meta($order){
    echo '<p><strong>'.__('My Field').':</strong> ' . get_post_meta( $order->id, 'My Field', true ) . '</p>';
}
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );


// Remove unnecessary elements
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
// Remove price
function no_price($return, $price, $args) {
    return '';
}
add_filter( 'wc_price', 'no_price', 10, 3 );


// Define number of columns
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}
add_filter('loop_shop_columns', 'loop_columns');


/* Shop loop
Adding a custom related products function into a products slider
*/
function theme_custom_action() {
	$parentid = get_queried_object_id();
	$termParent = get_term($parentid, 'product_cat');
	$parent = get_term($termParent->parent, 'product_cat');

	if( $termParent->parent != 0 ) :

		$args = array(
			'post_type'   => 'product',
			'post_status' => 'publish',
			'posts_per_page'  => '-1',
			'tax_query'   => array(
				'relation'  => 'AND',
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $termParent->parent
				),
				array(
					'taxonomy' => 'product_cat',
					'field'    => 'id',
					'terms'    => $parentid,
					'operator' => 'NOT IN'
				),
			)
		);

		$more_products = new WP_Query( $args );
		if( $more_products->have_posts() ) :
        global $product;
		?>
			<section class="row entry-content products-slider cf">
				<div class="cf">
                <h2>More <?php echo $parent->name; ?></h2>
				<div class="multiple-objects">
					<?php while ( $more_products->have_posts() ) : $more_products->the_post(); ?>
						<li class="product">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-thumb">
								<?php the_post_thumbnail('full'); ?>
							</a>

							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-title">
								<h3><?php the_title(); ?><br><?php echo $product->get_sku(); ?></h3>

								<?php the_excerpt(); ?>
							</a>
						</li>
					<?php endwhile; ?>
				</div>
				</div>
			</section>
		<?php endif;
	wp_reset_postdata();
	
	else :
		if( is_product_category() ) {
			get_sidebar('bespoke');
		}	
	endif;
}
add_action( 'woocommerce_after_main_content', 'theme_custom_action', 15 );


// Custom thumbs in shop loop
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
    function woocommerce_template_loop_product_thumbnail() {
        echo woocommerce_get_product_thumbnail();
    }
}
if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
    function woocommerce_get_product_thumbnail( $size = 'full', $placeholder_width = 0, $placeholder_height = 0  ) {
        global $post, $woocommerce;
        if ( has_post_thumbnail() ) {
            $output .= get_the_post_thumbnail( $post->ID, $size );
        }
        return $output;
    }
}


// Cropping gallery Thumbs
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
	return array(
		'width' => 250,
		'height' => 250,
		'crop' => 1
	);
} );


// Shop loop titles
function wrap_title() {
    echo '<div class="shop-loop-title">';
}
add_action('woocommerce_before_shop_loop_item_title', 'wrap_title', 10);
function wrap_title_end() {
	echo '</div>';
}
add_action('woocommerce_after_shop_loop_item_title', 'wrap_title_end', 20);
function shop_loop_sku() {
	global $product;
    echo '<h2 class="woocommerce-loop-product__title">'. get_the_title() . ' <span>' . $product->get_sku().'</span></h2>';
}
remove_action( 'woocommerce_shop_loop_item_title', 'woocommerce_template_loop_product_title', 10 );
add_action('woocommerce_shop_loop_item_title', 'shop_loop_sku', 5);


// Displaying SKU
function theme_show_sku(){
    global $product;
    echo '<p class="sku">'.$product->get_sku().'</p>';
}
add_action( 'woocommerce_single_product_summary', 'theme_show_sku', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'theme_show_sku', 11 );


// Adding anchor link for details
function anchor_link_for_details() {
	echo '<p><a href="#details"><u>View product details</u></a></p>';
}
add_action( 'woocommerce_before_add_to_cart_form', 'anchor_link_for_details', 5 );


// Change title of description tab
function woo_edit_tabs( $tabs ) {
	$tabs['description']['title'] = __( 'Product details' );
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_edit_tabs', 98 );


// Adding extra tabs in product page (uses ACF Fields)
function woo_new_product_tab( $tabs ) {
	// Adds the new tab
//    if( get_field('kit_codes') ) {
//        $tabs['kit_codes'] = array(
//			'title' 	=> __( 'Kit codes', 'woocommerce' ),
//			'priority' 	=> 40,
//			'callback' 	=> 'render_kit_codes_tab'
//		);
//    }
    
	if( have_rows('downloads') ) {
		$tabs['downloads'] = array(
			'title' 	=> __( 'Downloads', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'render_downloads_tab'
		);
	}
	
	if( have_rows('accreditations') ) {
		$tabs['accreditations'] = array(
			'title' 	=> __( 'Accreditations', 'woocommerce' ),
			'priority' 	=> 60,
			'callback' 	=> 'render_accreditations_tab'
		);
	}
	
	return $tabs;
}

function render_kit_codes_tab() {
	if( get_field('kit_codes') ) : 
		the_field('kit_codes');
	endif;
}

function render_downloads_tab() {
	if( have_rows('downloads') ) : 
		echo '<ul>';
		while( have_rows('downloads') ): the_row();
			echo '<li><a class="product-downloads" target="_blank" href="'.get_sub_field('file').'"> '.get_sub_field('name').'</a></li>';
		endwhile;
		echo '</ul>';
	endif;
}

function render_accreditations_tab() {
	$accreditations = get_field('accreditations');
	if( $accreditations ) : ?>
		<ul class="product-accreditations">
			<?php foreach( $accreditations as $accreditation ) :
                $acc_url = strtolower($accreditation);
                $acc_url = str_replace(' ', '', $acc_url);
				echo '<li><img src="'.get_template_directory_uri().'/library/images/certs/'.$acc_url.'.jpg" alt="'.$accreditation.'"></li>';
			endforeach; ?>
		</ul>
	<?php endif;
}
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );


// Add privacy checkbox to checkout
function add_privacy_checkbox() {
	woocommerce_form_field( 'privacy_policy', array(
		'type' => 'checkbox',
		'class' => array('form-row privacy'),
		'label_class' => array('woocommerce-form__label woocommerce-form__label-for-checkbox checkbox'),
		'input_class' => array('woocommerce-form__input woocommerce-form__input-checkbox input-checkbox'),
		'required' => true,
		'label' => 'I\'ve read and accept the <a href="/kenex/privacy-policy">Privacy Policy</a>',
	));
}
add_action( 'woocommerce_review_order_before_submit', 'add_privacy_checkbox', 9 );
function privacy_checkbox_error_message() {
	if ( ! (int) isset( $_POST['privacy_policy'] ) ) {
		wc_add_notice( __( 'You have to agree to our privacy policy in order to proceed' ), 'error' );
	}
}
add_action( 'woocommerce_checkout_process', 'privacy_checkbox_error_message' );


// Show more variations in product edit page
function handsome_bearded_guy_increase_variations_per_page() {
	return 50;
}
add_filter( 'woocommerce_admin_meta_boxes_variations_per_page', 'handsome_bearded_guy_increase_variations_per_page' );


// Custom input field for images for use with video links
function edit_media_custom_field( $form_fields, $post ) {
    $form_fields['custom_field'] = array( 'label' => 'Custom Field', 'input' => 'text', 'value' => get_post_meta( $post->ID, '_custom_field', true ) );
    return $form_fields;
}
function save_media_custom_field( $post, $attachment ) {
    update_post_meta( $post['ID'], '_custom_field', $attachment['custom_field'] );
    return $post;
}
add_filter('attachment_fields_to_edit', 'edit_media_custom_field', 11, 2 );
add_filter('attachment_fields_to_save', 'save_media_custom_field', 11, 2 );


// define the woocommerce_single_product_image_thumbnail_html callback 
function filter_woocommerce_single_product_image_thumbnail_html( $sprintf, $post_id ) {
    return $sprintf; 
}; 
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'filter_woocommerce_single_product_image_thumbnail_html', 10, 2 );

?>