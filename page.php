<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="cf">

					<div id="main" class="cf" role="main" itemscope itemprop="mainContentOfPage" itemtype="http://schema.org/Blog">

						<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
						
						<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">	
							
							<?php // HERO AREA ?>
							<?php if( has_post_thumbnail() && is_page() ) : ?>
							<div class="featured-image">
								<?php the_post_thumbnail('full'); ?>
								<div class="hero-title">
									<?php the_content(); ?>
								</div>
							</div>
							<?php elseif( get_the_content() ) : ?>
							<section class="entry-content cf" itemprop="articleBody">
								<?php the_content(); ?>
							</section>
							<?php endif; ?>

							<?php // COLUMNS CONTENT ?>
							<?php if( have_rows('rows') ): $rowNum = 1; ?>
								<?php while( have_rows('rows') ): the_row(); ?>

									<?php
									$rowNum ++;
									$layout = get_sub_field('layout');
									$padding = get_sub_field('padding');
									$bgColour = get_sub_field('bg_colour');
									$addClasses = array();
									$addStyles = array();
									$styles;
									if( $padding ) {
										if( $padding[padding_top] ) { array_push($addStyles, "padding-top: $padding[padding_top];"); }
										if( $padding[padding_right] ) { array_push($addStyles, "padding-right: $padding[padding_right];"); }
										if( $padding[padding_bottom] ) { array_push($addStyles, "padding-bottom: $padding[padding_bottom];"); }
										if( $padding[padding_left] ) { array_push($addStyles, "padding-left: $padding[padding_left];"); }
									}
									if( get_sub_field('bg_colour') ) {
										array_push($addClasses, "bg-colour");
										array_push($addClasses, "bg-colour$rowNum");
										array_push($addStyles, "background: $bgColour");
									}
									if( get_sub_field('gradient') ) {
										array_push($addClasses, "gradient");
									}
									if( isset($addClasses) || isset($addStyles) ) {
										$styles = ' style="';
										$styles .= implode(" ", $addStyles);
										$styles .= '"';
									}

									if( $layout === 'hide' ) {
										echo '<section class="row entry-content cf" style="display: none;">';
										echo '<div class="cf">';
									} else if( $layout === 'wrap' ) {
										echo '<section class="row entry-content wrap cf '.implode(" ", $addClasses).'"'.$styles.'>';
										echo '<div class="cf">';
									} else if( $layout === 'full' ) {
										echo '<section class="row entry-content full cf '.implode(" ", $addClasses).'"'.$styles.'>';
										echo '<div class="cf">';
									} else if( $layout === 'alt' ) {
										echo '<section class="row entry-content alt cf '.implode(" ", $addClasses).'"'.$styles.'>';
										echo '<div class="cf">';
									} else {
										echo '<section class="row entry-content cf '.implode(" ", $addClasses).'"'.$styles.'>';
										echo '<div class="cf">';
									}
									?>

									<?php if( get_sub_field('title') ) : ?>
										<h2 class="row-title"><?php echo get_sub_field('title'); ?></h2>
									<?php endif; ?>

									<?php if( get_sub_field('column_size') === '1col' ) : ?>

										<div class="col-12"><?php the_sub_field('col1'); ?></div>

									<?php elseif( get_sub_field('column_size') === '2col' ) : ?>

										<div class="cf col-6">
											<?php the_sub_field('col2_a'); ?>
										</div>

										<div class="cf col-6">
											<?php the_sub_field('col2_b'); ?>
										</div>

									<?php elseif( get_sub_field('column_size') === '3col' ) : ?>

										<div class="col-4">
											<?php the_sub_field('col3_a'); ?>
										</div>

										<div class="col-4">
											<?php the_sub_field('col3_b'); ?>
										</div>

										<div class="col-4">
											<?php the_sub_field('col3_c'); ?>
										</div>

									<?php elseif( get_sub_field('column_size') === '4col' ) : ?>

										<div class="col-3">
											<?php the_sub_field('col4_a'); ?>
										</div>

										<div class="col-3">
											<?php the_sub_field('col4_b'); ?>
										</div>

										<div class="col-3">
											<?php the_sub_field('col4_c'); ?>
										</div>

										<div class="col-3">
											<?php the_sub_field('col4_d'); ?>
										</div>

									<?php endif; ?>

									</section>

								<?php endwhile; ?>
							<?php endif; ?>
						
							<?php // PRODUCTS (IF HOME PAGE) ?>
							<?php if( is_front_page() ) : ?>
								<?php
								$args = array(
									'post_type'   => 'product',
									'post_status' => 'publish',
									'posts_per_page'  => '4',
									'orderby'=>'rand',
								);

								$additional_products = new WP_Query( $args );
								if( $additional_products->have_posts() ) :
                                global $product;
								?>
									<section class="row entry-content products-slider cf">
										<div class="cf">
										<div class="multiple-objects">
											<?php while ( $additional_products->have_posts() ) : $additional_products->the_post(); ?>
												<li <?php wc_product_class(); ?>>
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
								<?php endif; ?>
								<?php wp_reset_postdata(); ?>
							<?php endif; ?>

							<?php // PRE-FOOTER ?>
							<?php if( !empty(get_field('pre_footer')) ) : ?>
								<section class="pre-footer row cf">
									<div class="max-width cf wrap">
										<?php if( !empty(get_field('pre_footer_media')) ) : ?>
											<div class="col-6"><?php the_field('pre_footer_media') ?></div>
											<div class="col-6"><?php the_field('pre_footer') ?></div>
										<?php else : ?>
											<div class="col-12"><?php the_field('pre_footer') ?></div>
										<?php endif; ?>
									</div>
								</section>
							<?php endif; ?>

						</article>

						<?php endwhile; endif; ?>

					</div>

				</div>

			</div>

<?php get_footer(); ?>