<div id="sidebar1" class="sidebar col-3 cf" role="complementary">

	<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

		<?php dynamic_sidebar( 'sidebar1' ); ?>

	<?php else : ?>

		<?php
			$parentid = get_queried_object_id();
			$termParent = get_term($parentid, 'product_cat');
			$args;
	
			if( $termParent->parent ) {
				$args = array(
					'parent' => $termParent->parent
				);	
			} else {
				$args = array(
					'parent' => $parentid
				);
			}
			
			$terms = get_terms( 'product_cat', $args );
			
			if ( $terms ) {
				echo '<ul class="product-cats">';
					foreach ( $terms as $term ) {
						echo '<li class="category">';
							echo '<a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">';
								echo $term->name;
							echo '</a>';
						echo '</li>';
					}
				echo '</ul>';
			}
		?>

	<?php endif; ?>

</div>
