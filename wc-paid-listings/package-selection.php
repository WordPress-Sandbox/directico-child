<?php if ( $packages || $user_packages ) :
	function listable_get_woocommerce_price_format_on_paid_listings( $format, $currency_pos  ) {
		$currency_pos = get_option( 'woocommerce_currency_pos' );
		$format = '%1$s%2$s';

		switch ( $currency_pos ) {
			case 'left' :
				$format = '<span class="package__currency">%1$s</span>%2$s';
				break;
			case 'right' :
				$format = '%2$s<span class="package__currency">%1$s</span>';
				break;
			case 'left_space' :
				$format = '<span class="package__currency">%1$s</span>&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;<span class="package__currency">%1$s</span>';
				break;
		}
		return $format;
	}
	add_filter( 'woocommerce_price_format', 'listable_get_woocommerce_price_format_on_paid_listings', 10, 2 );

	$checked = 1; ?>
	<?php if ( $user_packages ) : ?>
		<h2 class="package-list__title"><?php _e( "Your packages", "listable" ); ?></h2>
		<div class="package-list  package-list--user">
			<?php foreach ( $user_packages as $key => $package ) :
				$package = wc_paid_listings_get_package( $package );
				?>
				<div class="package  package--featured">
					<h2 class="package__title"><?php echo $package->get_title(); ?></h2>
					<div class="package__content">
					<?php
						if ( $package->get_limit() ) {
							printf( _n( '%s listing posted out of %d', '%s listings posted out of %d', $package->get_count(), 'listable' ) . ', ', $package->get_count(), $package->get_limit() );
						} else {
							printf( _n( '%s listing posted', '%s listings posted', $package->get_count(), 'listable' ) . ', ', $package->get_count() );
						}

						if ( $package->get_duration() ) {
							printf( _n( 'listed for %s day', 'listed for %s days', $package->get_duration(), 'listable' ), $package->get_duration() );
						}

						$checked = 0;
					?>
					</div>
					<button class="btn package__btn" type="submit" name="job_package" value="user-<?php echo $key; ?>" id="package-<?php echo $product->id; ?>">
						<?php _e('New Listing', 'listable') ?>
					</button>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
	<?php if ( $packages ) : ?>
		<div class="package-list general-packages">
			<?php foreach ( $packages as $key => $package ) :
				$product = wc_get_product( $package );
				if ( ! $product->is_type( array( 'job_package', 'job_package_subscription' ) ) || ! $product->is_purchasable() ) {
					continue;
				}
				$tags = get_the_terms($product->id, 'product_tag');
				$taggedClass = ( ! is_wp_error( $tags ) && ! empty($tags) ) ? 'package--labeled' : '';
				$taggedClass = $taggedClass !== '' ? $taggedClass . ' ' . $taggedClass . '-' . $tags[0]->slug : '';
				$featuredClass = $product->is_featured() ? 'package--featured' : '';
				?>
				<div class="package <?php echo $taggedClass . ' ' . $featuredClass; ?>">
					<?php if ( ! is_wp_error( $tags ) && ! empty($tags) ) { ?>
						<div class="featured-label"><?php echo $tags[0]->name; ?></div>
					<?php } ?>
					<h2 class="package__title">
						<?php echo $product->get_title(); ?>
					</h2>
					<div class="package__price">
						<?php if ( $product->price ){
							echo wc_price( $product->price );
							echo '<sup style="position: relative; top: -5px; font-size: 15px; text-transform: none;"><small> / mes</small></sup>';
							echo '<div style="font-size: 16px;"><small>o</small></div>';
						} else {
							esc_html_e('Free', 'listable');
						} ?>
					</div>
					<?php
						if( $product->id == 210 ) {
							echo '<em>₡99,995 / año</em>';
						}
						if( $product->id == 215 ) {
							echo '<em>₡49,995 / año</em>';
						}
					?>
					<div class="package__description">
						<?php echo apply_filters( 'woocommerce_short_description', $product->post->post_excerpt ) ?>
					</div>
					<div class="package__content">
						<?php echo apply_filters( 'the_content', $product->post->post_content ) ?>
					</div>
					<?php
						if( $product->id == 213 ) {
							echo '<button class="btn package__btn" type="submit" name="job_package" value="213" id="package-213">COMENZAR</button>';
						}
						if( $product->id == 210 ) {
							echo '<button class="btn package__btn" type="submit" name="job_package" value="210" id="package-210">MENSUAL</button>';
							echo '<button class="btn package__btn" type="submit" name="job_package" value="12956" id="package-12956">ANUAL</button>';
						}
						if( $product->id == 215 ) {
							echo '<button class="btn package__btn" type="submit" name="job_package" value="215" id="package-215">MENSUAL</button>';
							echo '<button class="btn package__btn" type="submit" name="job_package" value="12955" id="package-12955">ANUAL</button>';
						}
					?>
				</div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
<?php else : ?>

	<p class="no-packages"><?php _e( 'No packages found', 'wp-job-manager-wc-paid-listings' ); ?></p>

<?php endif; ?>
