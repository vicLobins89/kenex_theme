<?php
/**
 * Downloads
 *
 * Shows downloads on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/downloads.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$downloads     = WC()->customer->get_downloadable_products();
$has_downloads = (bool) $downloads;

do_action( 'woocommerce_before_account_downloads', $has_downloads ); ?>

<h1 class="h3 flair">Downloads</h1>

<?php if ( $has_downloads ) : ?>

	<?php do_action( 'woocommerce_before_available_downloads' ); ?>

	<label for="time_period"><strong>Show</strong></label>
	<select name="time_period" id="time_period" class="time-period">
		<option value="7">Last week</option>
		<option value="30">Last month</option>
		<option value="90">Last 3 months</option>
		<option selected value="99999">All quotes</option>
	</select>

	<table class="woocommerce-table woocommerce-table--order-downloads shop_table shop_table_responsive order_details">
		<thead>
			<tr>
				<th class="download-product"><span class="nobr">File name</span></th>
				<th class="download-type"><span class="nobr">File type</span></th>
				<th class="download-date"><span class="nobr">Date</span></th>
				<th class="download-file"><span class="nobr">Action</span></th>
			</tr>
		</thead>

		<tbody>		
		<?php foreach ( $downloads as $download ) :
			$order = new WC_Order($download['order_id']);
			?>
			<tr>
				<td class="download-product" data-title="Product">
					<a href="<?php echo $download['product_url']; ?>"><?php echo $download['download_name']; ?></a>
				</td>
				
				<td>PDF</td>
				
				<td class="download-date woocommerce-orders-table__cell-order-date">
					<time datetime="<?php echo esc_attr( $order->get_date_created()->format( 'Ymd' ) ); ?>"><?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?></time>
				</td>
				
				<td class="download-file" data-title="Download">
					<a href="<?php echo $download['download_url']; ?>" class="woocommerce-MyAccount-downloads-file button alt">Download</a>			
				</td>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

	<?php do_action( 'woocommerce_after_available_downloads' ); ?>

	<?php //do_action( 'woocommerce_before_available_downloads' ); ?>

	<?php //do_action( 'woocommerce_available_downloads', $downloads ); ?>

	<?php //do_action( 'woocommerce_after_available_downloads' ); ?>

<?php else : ?>
	<div class="message cf">
		<p class="desc">
			<strong><?php esc_html_e( 'No order has been made yet.', 'woocommerce' ); ?></strong>
		</p>
		<a class="woocommerce-button button" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
			<?php esc_html_e( 'Go to', 'woocommerce' ); ?><br>
			<?php esc_html_e( 'the shop', 'woocommerce' ); ?>
		</a>
	</div>
<?php endif; ?>

<?php do_action( 'woocommerce_after_account_downloads', $has_downloads ); ?>
