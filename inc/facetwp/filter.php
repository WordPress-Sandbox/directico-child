<?php 

// exclude basic package listings in explore page.
add_filter( 'facetwp_query_args', function( $query_args, $class ) {
        $query_args['meta_key'] = '_package_id';
        $query_args['meta_value'] = 213; // 
        $query_args['meta_compare'] = '=';
    return $query_args;
}, 10, 2 );

$args = array(
	'post_type' => 'job_listing',
	'posts_per_page' => -1,
	'meta_key'	=> '_package_id',
	'meta_value'	=> 213,
	'meta_compare'	=> '!=',
);

$ALLposts = get_posts($args);

var_dump($ALLposts);
