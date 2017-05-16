<?php 

// exclude basic package listings in explore page.
function get_basic_listing_ids() {
	$args = array(
		'post_type' => 'job_listing',
		'posts_per_page' => -1,
		'meta_key'	=> '_package_id',
		'meta_value'	=> 213,
		'meta_compare'	=> '=',
	);

	$ALLposts = get_posts($args);
	$ids = wp_list_pluck($ALLposts, 'ID');
	return $ids;
}

add_filter( 'facetwp_query_args', function( $query_args, $class ) {
        $query_args['post__not_in'] = get_basic_listing_ids();
    return $query_args;
}, 10, 2 );