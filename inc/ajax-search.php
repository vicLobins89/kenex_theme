<?php
/* AJAX QUICK QUOTE SEARCH FUNCTION */

// the ajax function
function data_fetch(){
    
    $the_query = new WP_Query( 
        array( 
            'posts_per_page' => 4,
            'post_type' => array('product', 'product_variation'),
            'meta_query' => array(
                array(
                    'key'   => '_sku',
                    'value' => $_POST['keyword'],
                    'compare' => 'LIKE'
                )
            )
        ) );
    
    if( $the_query->have_posts() ) : ?>
        
        <thead>
            <tr>
                <th class="product-name">Product</th>
                <th class="product-code">Code</th>
                <th>Description</th>
                <th>Image</th>
                <th>Quantity</th>
                <th class="cart-button"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        while( $the_query->have_posts() ): $the_query->the_post();

        $product = wc_get_product( get_the_ID() );
        $description = strip_tags( $product->get_variation_description() );
        $description_array = explode(' ', $description);
        $max_words = 15;
        if( count($description_array) > $max_words && $max_words > 0 ) {
            $description = implode(' ',array_slice($description_array, 0, $max_words)).'...';
        }
        $atts = $product->get_attributes();
        ?>
      
            <tr>
                <td class="product-name">
                    <a href="<?php echo esc_url( post_permalink() ); ?>"><?php the_title();?></a>
                </td>
                
                <td class="product-code"><?php echo $product->get_sku(); ?></td>
                
                <td>
                    <?php echo $description; ?>
                </td>
                
                <td class="product-thumbnail" width="80"><?php echo $product->get_image(); ?></td>
                
                <td width="80"><input type="number" id="qty" name="qty" class="custom_qty" onchange="Data(this)" value="1" min="1" max="99"></td>
                
                <td>
                    <?php
                    // Check if variation allows for blank options (eg Sandbags) and divert to select options
                    if( !in_array('', $atts) ) {
                        woocommerce_template_loop_add_to_cart( /*array('quantity' => 3)*/ );
                    } else {
                        echo '<a class="button product_type_variable add_to_cart_button" href="' . esc_url( post_permalink() ) . '">Select options</a>';
                    }
                    ?>
                </td>
            </tr>

        <?php endwhile;
        ?></tbody><?php
    
		wp_reset_postdata();  
        
	else: 
		echo '<h3>No Results Found</h3>';
    endif;
    
    die();
}
add_action('wp_ajax_data_fetch' , 'data_fetch');
add_action('wp_ajax_nopriv_data_fetch','data_fetch');

// add the ajax fetch js
function ajax_fetch() {
?>
<script type="text/javascript">
var timeout = null;
function fetchResults(){
    var keyword = jQuery('#searchInput').val();
    
    clearTimeout(timeout);
    
    timeout = setTimeout(function () {
        if( keyword == "" || keyword.length < 3 ) {
            jQuery('#datafetch').html('');
        } else {
            jQuery.ajax({
                url: '<?php echo admin_url('admin-ajax.php'); ?>',
                type: 'post',
                data: { action: 'data_fetch', keyword: keyword  },
                beforeSend: function() {
                    jQuery('#loader').show();
                },
                complete: function(){
                    jQuery('#loader').hide();
                },
                success: function(data) {
                    jQuery('#datafetch').fadeOut( 100, function(){
                        jQuery(this).html( data );
                    }).fadeIn( 500 )
                }
            });
        }
    }, 500);
    
}

function Data(el){
    var quantity = jQuery(el).val();
    jQuery(el).closest('tr').find('.add_to_cart_button').attr('data-quantity', quantity);
}

jQuery(document).ready(function($){
    if( $('body').hasClass('page-quick-quote') ) {
        fetchResults();
    }
    
    $('#quickseaerch').submit(function(e){
        e.preventDefault();
        fetchResults();
    });
});
</script>
<?php
}
add_action( 'wp_footer', 'ajax_fetch' );
?>