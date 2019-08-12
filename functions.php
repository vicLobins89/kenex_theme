<?php

// LOAD CORE (do not remove)
require_once( 'library/rarehoney.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
 require_once( 'library/admin.php' );

/*********************
LAUNCH
*********************/

function rarehoney_init() {

  //Allow editor style.
  add_editor_style( get_stylesheet_directory_uri() . '/library/css/editor-style.css' );

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
//  require_once( 'library/custom-post-type.php' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'rarehoney_init' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 680;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-600', 600, 150, true );
add_image_size( 'bones-thumb-300', 300, 100, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 100 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 150 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-600' => __('600px by 150px'),
        'bones-thumb-300' => __('300px by 100px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* THEME CUSTOMIZE *********************/
function bones_theme_customizer($wp_customize) {
  // Uncomment the below lines to remove the default customize sections 
   $wp_customize->remove_section('title_tagline');
   $wp_customize->remove_section('colors');
   $wp_customize->remove_section('background_image');
   $wp_customize->remove_section('static_front_page');
   $wp_customize->remove_section('nav');

  // Uncomment the below lines to remove the default controls
  // $wp_customize->remove_control('blogdescription');
  
  // Uncomment the following to change the default section titles
  // $wp_customize->get_section('colors')->title = __( 'Theme Colors' );
  // $wp_customize->get_section('background_image')->title = __( 'Images' );
}

add_action( 'customize_register', 'bones_theme_customizer' );

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar1',
		'name' => __( 'Sidebar 1', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	register_sidebar(array(
		'id' => 'footer1',
		'name' => __( 'Footer 1', 'bonestheme' ),
		'description' => __( 'Footer 1', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'footer2',
		'name' => __( 'Footer 2', 'bonestheme' ),
		'description' => __( 'Footer 2', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'footer3',
		'name' => __( 'Footer 3', 'bonestheme' ),
		'description' => __( 'Footer 3', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'footer4',
		'name' => __( 'Footer 4', 'bonestheme' ),
		'description' => __( 'Footer 4', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
	
	register_sidebar(array(
		'id' => 'bespoke',
		'name' => __( 'Bespoke service', 'bonestheme' ),
		'description' => __( 'Bespoke services message on category pages.', 'bonestheme' ),
		'before_widget' => '<div class="widget %2$s">',
		'after_widget' => '</div>'
	));
} // don't remove this bracket!


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_enqueue_style('googleFonts', '//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
}

add_action('wp_enqueue_scripts', 'bones_fonts');


//Page Slug Body Class
function add_slug_body_class( $classes ) {
	global $post;
	if( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );


// Page Excerpt
add_post_type_support( 'page', 'excerpt' );


// WOOCOMMERCE
function mytheme_add_woocommerce_support() {
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'mytheme_add_woocommerce_support' );

//add_theme_support( 'wc-product-gallery-lightbox' );

// Short Desc in Archive
function action_woocommerce_after_shop_loop_item_title(  ) { 
    return the_excerpt();
}; 
add_action( 'woocommerce_shop_loop_item_title', 'action_woocommerce_after_shop_loop_item_title', 10, 0 );

// Change cart button text
function woo_custom_single_add_to_cart_text() {
    return __( 'Add to quote basket', 'woocommerce' );
}
add_filter( 'woocommerce_product_single_add_to_cart_text', 'woo_custom_single_add_to_cart_text' );

// Order notes
function custom_override_checkout_fields( $fields ) {
	$fields['order']['order_comments']['label'] = 'Delivery details';
	$fields['order']['order_comments']['type'] = 'select';
	$fields['order']['order_comments']['options'] = array(
		'Freight quote' => 'Freight quote',
		'Weight and dimensions' => 'Weight & dims',
		'None' => 'None'
	);
	$fields['billing']['billing_company']['required'] = true;
	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

// Job title
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

// Remove elements
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

// Column list
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 3;
	}
}
add_filter('loop_shop_columns', 'loop_columns');

// Shop loop 
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

// Shop loop thumbs
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


// Gallery Thumbs
add_filter( 'woocommerce_get_image_size_gallery_thumbnail', function( $size ) {
	return array(
		'width' => 250,
		'height' => 250,
		'crop' => 1
	);
} );

//SKU
function theme_show_sku(){
    global $product;
    echo '<p class="sku">'.$product->get_sku().'</p>';
}
add_action( 'woocommerce_single_product_summary', 'theme_show_sku', 5 );
add_action( 'woocommerce_shop_loop_item_title', 'theme_show_sku', 11 );

// Anchor link for details
function anchor_link_for_details() {
	echo '<p><a href="#details"><u>View product details</u></a></p>';
}
add_action( 'woocommerce_before_add_to_cart_form', 'anchor_link_for_details', 5 );

// Tabs
function woo_edit_tabs( $tabs ) {
	$tabs['description']['title'] = __( 'Product details' );
	unset( $tabs['additional_information'] );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_edit_tabs', 98 );

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


// Login / Register
function wooc_extra_register_fields() { ?>
	<p class="form-row form-row-first">
		<label for="reg_billing_first_name"><?php _e( 'First name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_first_name" id="reg_billing_first_name" value="<?php if ( ! empty( $_POST['billing_first_name'] ) ) esc_attr_e( $_POST['billing_first_name'] ); ?>" />
	</p>
	<p class="form-row form-row-last">
		<label for="reg_billing_last_name"><?php _e( 'Last name', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_last_name" id="reg_billing_last_name" value="<?php if ( ! empty( $_POST['billing_last_name'] ) ) esc_attr_e( $_POST['billing_last_name'] ); ?>" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_billing_phone"><?php _e( 'Phone', 'woocommerce' ); ?></label>
		<input type="text" class="input-text" name="billing_phone" id="reg_billing_phone" value="<?php esc_attr_e( $_POST['billing_phone'] ); ?>" />
	</p>
	<p class="form-row form-row-wide">
		<label for="reg_billing_company"><?php _e( 'Company', 'woocommerce' ); ?><span class="required">*</span></label>
		<input type="text" class="input-text" name="billing_company" id="reg_billing_company" value="<?php esc_attr_e( $_POST['billing_company'] ); ?>" />
	</p>
	<div class="clear"></div>
	<?php
 }
 add_action( 'woocommerce_register_form_start', 'wooc_extra_register_fields' );

// Validation
function wooc_validate_extra_register_fields( $username, $email, $validation_errors ) {
	if( isset( $_POST['billing_first_name'] ) && empty( $_POST['billing_first_name'] ) ) {
		$validation_errors->add( 'billing_first_name_error', __( '<strong>Error</strong>: First name is required!', 'woocommerce' ) );
	}
	if( isset( $_POST['billing_last_name'] ) && empty( $_POST['billing_last_name'] ) ) {
		$validation_errors->add( 'billing_last_name_error', __( '<strong>Error</strong>: Last name is required!.', 'woocommerce' ) );
	}
	if( isset( $_POST['billing_company'] ) && empty( $_POST['billing_company'] ) ) {
		$validation_errors->add( 'billing_company_error', __( '<strong>Error</strong>: Company is required!.', 'woocommerce' ) );
	}
	return $validation_errors;
}

add_action( 'woocommerce_register_post', 'wooc_validate_extra_register_fields', 10, 3 );

// DB Save
function wooc_save_extra_register_fields( $customer_id ) {
	if ( isset( $_POST['billing_phone'] ) ) {
		update_user_meta( $customer_id, 'billing_phone', sanitize_text_field( $_POST['billing_phone'] ) );
	}
	if ( isset( $_POST['billing_company'] ) ) {
		update_user_meta( $customer_id, 'billing_company', sanitize_text_field( $_POST['billing_company'] ) );
	}
	if ( isset( $_POST['billing_first_name'] ) ) {
		update_user_meta( $customer_id, 'first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
		update_user_meta( $customer_id, 'billing_first_name', sanitize_text_field( $_POST['billing_first_name'] ) );
	}
	if ( isset( $_POST['billing_last_name'] ) ) {
		update_user_meta( $customer_id, 'last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
		update_user_meta( $customer_id, 'billing_last_name', sanitize_text_field( $_POST['billing_last_name'] ) );
	}
}
add_action( 'woocommerce_created_customer', 'wooc_save_extra_register_fields' );


// Checkout checkbox
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


// Shown variations
function handsome_bearded_guy_increase_variations_per_page() {
	return 50;
}
add_filter( 'woocommerce_admin_meta_boxes_variations_per_page', 'handsome_bearded_guy_increase_variations_per_page' );


// Custom image field
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
         
// add the filter 
add_filter( 'woocommerce_single_product_image_thumbnail_html', 'filter_woocommerce_single_product_image_thumbnail_html', 10, 2 );

/* DON'T DELETE THIS CLOSING TAG */ ?>
