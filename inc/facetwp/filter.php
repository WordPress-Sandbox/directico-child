<?php 

// Sort by title if the shortcode template is named "bravo"
add_filter( 'facetwp_query_args', function( $query_args, $class ) {
        $query_args['meta_key'] = '_package_id';
        $query_args['meta_value'] = 213;
        $query_args['meta_compare'] = '=';
    return $query_args;
}, 10, 2 );