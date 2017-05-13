<?php 
add_action('comment_post', 'save_update_listing_rating');
add_action('comment_edit_redirect', 'save_update_listing_rating');

function save_update_listing_rating(){
	global $post;
 	$pixRatingCalculated = get_average_listing_rating( $post->ID, 1 );
	update_post_meta($post->ID, '_pix_rating_calculated', $pixRatingCalculated);
}

// function run_the_query() {
//     $args = array(
//         'posts_per_page' => '-1',
//         'post_type'		=> 'job_listing'
//     ); 
//     $the_query = new WP_Query( $args );
//     if ( $the_query->have_posts() ){
//         while ( $the_query->have_posts() ) : $the_query->the_post();
//         save_update_listing_rating();
//         endwhile;
//     }

//     wp_reset_query();
// }

// add_action('init', 'run_the_query');