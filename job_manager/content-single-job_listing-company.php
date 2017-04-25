<?php
/**
 * Single view Company information box
 *
 * Hooked into single_job_listing_start priority 30
 *
 * @since  1.14.0
 */

global $post;
// get our custom meta
$location = get_post_meta( get_the_ID(), '_job_location', true);
$phone = get_post_meta( get_the_ID(), '_company_phone', true);
$twitter = get_post_meta( get_the_ID(), '_company_twitter', true);
$_fb_profile = get_post_meta( get_the_ID(), '_fb_profile', true);
$_fb_url = get_post_meta( get_the_ID(), '_fb_url', true);
$_instagram = get_post_meta( get_the_ID(), '_instagram', true);
?>
<div style="padding-bottom: 0;" class="single-meta">
	<?php
		display_average_listing_rating();
	?>
</div>
<div style="padding: 0 0 36px 0;" class="single-meta">
	<?php
	if ( ! empty( $phone ) ) :
		if ( strlen( $phone ) > 30 ) : ?>
			<a class="listing-contact  listing--phone" href="tel:<?php echo $phone; ?>" itemprop="telephone"><?php esc_html_e( 'Phone', 'listable' ); ?></a>
		<?php else : ?>
			<a class="listing-contact  listing--phone" href="tel:<?php echo $phone; ?>" itemprop="telephone"><?php echo $phone; ?></a>
		<?php endif; ?>
	<?php endif;

	do_action( 'listable_single_job_listing_after_social_icons' );

	if ( $website = get_the_company_website() ) {
		$website_pure = preg_replace('#^https?://#', '', rtrim(esc_url($website),'/'));
		if ( strlen( $website_pure ) > 30 ) : ?>
			<a class="listing-contact  listing--website" href="<?php echo esc_url( $website ); ?>" itemprop="url" target="_blank" rel="nofollow"><?php esc_html_e( 'Website', 'listable' ); ?></a>
		<?php else : ?>
			<a class="listing-contact  listing--website" href="<?php echo esc_url( $website ); ?>" itemprop="url" target="_blank" rel="nofollow"><?php echo $website_pure; ?></a>
		<?php endif; ?>
	<?php } ?>
	<?php if( $_fb_profile ): ?>
		<a class="listing-contact listing--facebook listing-facebook"
		href="<?php if( $_fb_url ) { echo $_fb_url; } ?>"
		target="_blank" itemprop="url">
			<?php echo $_fb_profile; ?>
		</a>
	<?php endif; ?>
	<?php if( $_instagram ): ?>
		<a class="listing-contact listing--instagram" href="https://www.instagram.com/<?php echo $_instagram; ?>" target="_blank" itemprop="url">
			/<?php echo $_instagram; ?>
		</a>
	<?php endif; ?>
</div>