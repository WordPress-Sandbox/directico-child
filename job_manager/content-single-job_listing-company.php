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
//$facebook_url = get_post_meta( get_the_ID(), '_company_facebook', true);
$location = get_post_meta( get_the_ID(), '_job_location', true);
$phone = get_post_meta( get_the_ID(), '_company_phone', true);
$twitter = get_post_meta( get_the_ID(), '_company_twitter', true);
?>
<div class="single-meta">
	<?php
	display_average_listing_rating();

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
</div>
<div class="single-meta">
	<?php if( get_field('perfil_de_facebook') ): ?>
	<a class="listing-contact  listing--facebook" href="<?php the_field('url_de_facebook'); ?>" target="_blank" itemprop="url">/<?php the_field('perfil_de_facebook'); ?></a>
	<?php endif; ?>
	<?php if( get_field('usuario_de_instagram') ): ?>
	<a class="listing-contact  listing--instagram" href="https://www.instagram.com/<?php the_field('usuario_de_instagram'); ?>" target="_blank" itemprop="url">/<?php the_field('usuario_de_instagram'); ?></a>
	<?php endif; ?>
	<?php if( get_field('perfil_de_facebook') ): ?>
	<a class="listing-contact  listing--twitter listing-facebook-copy" href="#" target="_blank" itemprop="url">---</a>
	<?php endif; ?>
</div>