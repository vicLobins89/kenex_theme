<div id="sidebar1" class="sidebar col-3 cf" role="complementary">

	<?php if ( is_active_sidebar( 'sidebar1' ) ) : ?>

		<?php dynamic_sidebar( 'sidebar1' ); ?>

	<?php else : ?>

		<?php
			$parentid = get_queried_object_id();
			$termParent = get_term($parentid, 'product_cat');
	
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
						echo '<li class="category ' . (get_queried_object_id() == $term->term_id ? 'current' : '') .'">';
							echo '<a href="' .  esc_url( get_term_link( $term ) ) . '" class="' . $term->slug . '">';
								echo $term->name;
							echo '</a>';
                            
                            $subcats = get_terms( 'product_cat', array(
                                'child_of' => $term->term_id
                            ) );
                            
                            if( !empty( $subcats ) ) {
                                echo '<ul class="subcats">';
                                foreach ( $subcats as $subcat ) {
                                    echo '<li>';
                                        echo '<a href="' .  esc_url( get_term_link( $subcat->term_id ) ) . '" class="' . $subcat->slug . '">';
                                            echo $subcat->name;
                                        echo '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                            }
						echo '</li>';
					}
				echo '</ul>';
			}
		?>

	<?php endif; ?>

</div>
