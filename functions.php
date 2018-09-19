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

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
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
	$fields['order']['order_comments']['placeholder'] = 'My new placeholder';
	$fields['order']['order_comments']['label'] = 'Choose something';
	$fields['order']['order_comments']['type'] = 'select';
	$fields['order']['order_comments']['options'] = array(
		'option_1' => 'Option 1 text',
		'option_2' => 'Option 2 text'
	);
	return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

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

// 2 Column list
if (!function_exists('loop_columns')) {
	function loop_columns() {
		return 2;
	}
}
add_filter('loop_shop_columns', 'loop_columns');

// Shop loop 
function theme_custom_action() {
	$parentid = get_queried_object_id();
	$termParent = get_term($parentid, 'product_cat');

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
		?>
			<div class="cf"></div>
			<section class="more-products cf">
				<h2>More Imaging accessories</h2>
				<?php while ( $more_products->have_posts() ) : $more_products->the_post(); ?>
					<div class="col-4">
						<div class="post-item" onclick="window.location='<?php the_permalink(); ?>';">
							<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-thumb">
								<?php the_post_thumbnail('rectangle-thumb-s'); ?>
							</a>

							<h3>
								<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="post-title">
									<?php the_title(); ?>
								</a>
							</h3>

							<?php the_excerpt(); ?>
						</div>
					</div>
				<?php endwhile; ?>
			</section>
		<?php endif;
	wp_reset_postdata();
	endif;
}
add_action( 'woocommerce_after_main_content', 'theme_custom_action', 15 );

// Tabs
function woo_rename_tabs( $tabs ) {
	$tabs['description']['title'] = __( 'Product details' );
	return $tabs;
}
add_filter( 'woocommerce_product_tabs', 'woo_rename_tabs', 98 );

function woo_new_product_tab( $tabs ) {
	// Adds the new tab
	if( have_rows('downloads') ) : 
		$tabs['test_tab'] = array(
			'title' 	=> __( 'Product Downloads', 'woocommerce' ),
			'priority' 	=> 50,
			'callback' 	=> 'woo_new_product_tab_content'
		);
	endif;
	return $tabs;
}
function woo_new_product_tab_content() {
	if( have_rows('downloads') ) : 
		echo '<h2>Product Downloads</h2>';
		while( have_rows('downloads') ): the_row();
			echo '<a class="product-downloads" target="_blank" href="'.get_sub_field('file').'">'.get_sub_field('name').'</a>';
		endwhile;
	endif;
}
add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );

/* DON'T DELETE THIS CLOSING TAG */ ?>
