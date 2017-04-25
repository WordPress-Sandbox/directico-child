<?php
global $post;
?>
<div id="job-manager-bookmarks">
	<table class="job-manager-bookmarks">
		<thead>
			<tr>
				<th><span><?php _e( 'Logo', 'listable' ); ?></span></th>
				<th><?php _e( 'Bookmark', 'listable' ); ?></th>
				<th><?php _e( 'Rating', 'listable' ); ?></th>
				<th><?php _e( 'My Notes', 'listable' ); ?></th>
				<th><span style="display: none"><?php _e( 'Actions', 'listable' ); ?></span></th>
			</tr>
		</thead>
		<tbody>
			<?php
			foreach ( $bookmarks as $bookmark ) :
				if ( get_post_status( $bookmark->post_id ) !== 'publish' ) {
					continue;
				}
				$has_bookmark = true;
				?>
				<tr>
					<td data-label="<?php _e( 'Image', 'listable' ); ?>" width="20%">
						<?php
							echo '<a href="' . get_permalink( $bookmark->post_id ) . '">';
							echo '<img src="' . listable_get_post_image_src( $bookmark->post_id ) . '" />';
							echo '</a>';
						?>
					</td>
					<td data-label="<?php _e( 'Bookmark', 'listable' ); ?>" width="30%">
						<?php echo '<a href="' . get_permalink( $bookmark->post_id ) . '">' . get_the_title( $bookmark->post_id ) . '</a>'; ?>
					</td>
					<td data-label="<?php _e( 'Rating', 'listable' ); ?>" width="25%">
						<?php
							$rating = get_average_listing_rating( $bookmark->post_id, 1 );
							if ( ! empty( $rating ) ) { ?>
								<div style="margin: 0;" class="rating  card__rating" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
									<meta itemprop="ratingValue" content = "<?php echo get_average_listing_rating( $bookmark->post_id, 1 ); ?>">
									<meta itemprop="reviewCount" content = "<?php echo get_comments_number( $bookmark->post_id ) ?>; ?>">
									<span style="color: #13adba;" class="js-average-rating"><?php echo get_average_listing_rating( $bookmark->post_id, 1 ); ?></span>
								</div>
						<?php } else { ?>
							<span><small><?php _e( '(Not rated yet)', 'listable' ); ?></small></span>
						<?php } ?>
					</td>
					<td data-label="<?php _e( 'My Notes', 'listable' ); ?>" width="40%"><?php echo wpautop( wp_kses_post( $bookmark->bookmark_note ) ); ?></td>
					<td>
						<ul class="job-manager-bookmark-actions">
							<?php
								$actions = apply_filters( 'job_manager_bookmark_actions', array(
									'delete' => array(
										'label' => __( 'Delete', 'wp-job-manager-bookmarks' ),
										'url'   =>  wp_nonce_url( add_query_arg( 'remove_bookmark', $bookmark->post_id ), 'remove_bookmark' )
									)
								), $bookmark );

								foreach ( $actions as $action => $value ) {
									echo '<li><a href="' . esc_url( $value['url'] ) . '" class="job-manager-bookmark-action-' . $action . '">' . $value['label'] . '</a></li>';
								}
							?>
						</ul>
					</td>
				</tr>
			<?php endforeach; ?>

			<?php if ( empty( $has_bookmark ) ) : ?>
				<tr>
					<td colspan="2"><?php _e( 'You currently have no bookmarks', 'wp-job-manager-bookmarks' ); ?></td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>