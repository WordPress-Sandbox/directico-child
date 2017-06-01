<?php
/**
 * Listable Child functions and definitions
 *
 * Bellow you will find several ways to tackle the enqueue of static resources/files
 * It depends on the amount of customization you want to do
 * If you either wish to simply overwrite/add some CSS rules or JS code
 * Or if you want to replace certain files from the parent with your own (like style.css or main.js)
 *
 * @package ListableChild
 */


/* require files */
require_once locate_template('inc/comments-ratings/hooks.php');
require_once locate_template('inc/facetwp/filter.php');

// remove extended location plugin default map init and load in assets/js/scripts.js after factwp loaded
function wpdocs_dequeue_script() {
	wp_dequeue_script('wpjmel-user-location');
}
add_action( 'wp_enqueue_scripts', 'wpdocs_dequeue_script', 100);

/**
 * Setup Listable Child Theme's textdomain.
 *
 * Declare textdomain for this child theme.
 * Translations can be filed in the /languages/ directory.
 */
function listable_child_theme_setup() {
	load_child_theme_textdomain( 'listable-child-theme', get_stylesheet_directory() . '/languages' );
}
add_action( 'after_setup_theme', 'listable_child_theme_setup' );





/**
 *
 * 1. Add a Child Theme "style.css" file
 * ----------------------------------------------------------------------------
 *
 * If you want to add static resources files from the child theme, use the
 * example function written below.
 *
 */

function listable_child_enqueue_styles() {
	$theme = wp_get_theme();
	// use the parent version for cachebusting
	$parent = $theme->parent();

	if ( !is_rtl() ) {
		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/style.css', array(), $parent->get( 'Version' ) );
	} else {
		wp_enqueue_style( 'listable-style', get_template_directory_uri() . '/rtl.css', array(), $parent->get( 'Version' ) );
	}

	// Here we are adding the child style.css while still retaining
	// all of the parents assets (style.css, JS files, etc)
	wp_enqueue_style( 'listable-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array('listable-style') //make sure the the child's style.css comes after the parents so you can overwrite rules
	);

	wp_enqueue_script( 'child-scripts',
			get_stylesheet_directory_uri() . '/assets/js/scripts.js',
			array( 'jquery'),
			'1.0.0', true );

	// localize listing locations for admin
	$additionallocations = get_post_meta($_GET['job_id'], '_additionallocations', true);
	wp_localize_script( 'child-scripts', 'additionallocations', $additionallocations );
	// wp_add_inline_script( 'child-scripts', 'L_DISABLE_3D = true;' );
}

add_action( 'wp_enqueue_scripts', 'listable_child_enqueue_styles' );

// admin scripts 
function admin_scripts_func() {
	 	global $post;
		wp_enqueue_script( 'admin-scripts',
			get_stylesheet_directory_uri() . '/assets/js/admin-scripts.js',
			array( 'jquery', 'mapify'),
			'1.0.0', true );
		// localize listing locations for admin
		$additionallocations = get_post_meta($post->ID, '_additionallocations', true);
		wp_localize_script( 'admin-scripts', 'additionallocations', $additionallocations );
		$options = array(
				'lat'         => esc_attr( get_option( 'wpjmel_start_geo_lat', 40.712784 ) ),
				'lng'         => esc_attr( get_option( 'wpjmel_start_geo_long', -74.005941 ) )
			);
		wp_localize_script( 'admin-scripts', 'latlng', $options );

		wp_enqueue_style( 'admin-styles', get_stylesheet_directory_uri() . '/assets/css/admin-style.css');
}

add_action( 'admin_enqueue_scripts', 'admin_scripts_func' );

function listable_enqueue_login_js() {
	wp_enqueue_script( 'login-screens-js', get_stylesheet_directory_uri() . '/assets/js/login-screens.js', false );
	wp_enqueue_style( 'login-screens-css', get_stylesheet_directory_uri() . '/assets/css/login-screens.css');
}

add_action( 'login_enqueue_scripts', 'listable_enqueue_login_js', 1 );

/**
 *
 * 2. Overwrite Static Resources (eg. style.css or main.js)
 * ----------------------------------------------------------------------------
 *
 * If you want to overwrite static resources files from the parent theme
 * and use only the ones from the Child Theme, this is the way to do it.
 *
 */


/*

function listable_child_overwrite_files() {

	// 1. The "main.js" file
	//
	// Let's assume you want to completely overwrite the "main.js" file from the parent

	// First you will have to make sure the parent's file is not loaded
	// See the parent's function.php -> the listable_scripts_styles() function
	// for details like resources names

		wp_dequeue_script( 'listable-scripts' );


	// We will add the main.js from the child theme (located in assets/js/main.js)
	// with the same dependecies as the main.js in the parent
	// This is not required, but I assume you are not modifying that much :)

		wp_enqueue_script( 'listable-child-scripts',
			get_stylesheet_directory_uri() . '/assets/js/main.js',
			array( 'jquery' ),
			'1.0.0', true );



	// 2. The "style.css" file
	//
	// First, remove the parent style files
	// see the parent's function.php -> the hive_scripts_styles() function for details like resources names

		wp_dequeue_style( 'listable-style' );


	// Now you can add your own, modified version of the "style.css" file

		wp_enqueue_style( 'listable-child-style',
			get_stylesheet_directory_uri() . '/style.css'
		);
}

// Load the files from the function mentioned above:

	add_action( 'wp_enqueue_scripts', 'listable_child_overwrite_files', 11 );

// Notes:
// The 11 priority parameter is need so we do this after the function in the parent so there is something to dequeue
// The default priority of any action is 10

*/



/* azizultex */

/* Save Geo for New Post */
add_action( 'job_manager_save_job_listing', 'save_post_location', 31, 2 );
add_action( 'resume_manager_save_resume', 'save_post_location', 31, 2 );
add_action( 'wpjm_events_save_event', 'save_post_location', 30, 2 );

/* Save Geo on Update Post */
add_action( 'job_manager_update_job_data', 'save_post_location', 26, 2 );
add_action( 'resume_manager_update_resume_data', 'save_post_location', 25, 2 );
add_action( 'wpjm_events_update_event_data', 'save_post_location', 26, 2 );


function save_post_location($post_id, $values) {
	$post_type = get_post_type( $post_id );

	update_option('testlocations', $_POST['additionallocation']);

	/* Job Listing Location */
	if( 'job_listing' == $post_type && isset ( $_POST[ 'additionallocation' ] ) ){
		update_post_meta( $post_id, '_additionallocations', $_POST[ 'additionallocation' ]);
	} else {
		update_post_meta( $post_id, '_additionallocations', []);
	}
}


/* add new fields to job form */
add_filter( 'submit_job_form_fields', 'custom_frontend_fields' );
add_filter( 'job_manager_job_listing_data_fields', 'custom_backend_fields' );
function custom_frontend_fields($fields) {
	$fields['job']['fb_profile'] = array(
		'label'       => __( 'Perfil de Facebook', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico',
		'priority'    => 7
	);	
	$fields['job']['fb_url'] = array(
		'label'       => __( 'URL de Facebook', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico',
		'priority'    => 8
	);	
	$fields['job']['instagram'] = array(
		'label'       => __( 'Usuario de Instagram', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico',
		'priority'    => 9
	);
  return $fields;
}

function custom_backend_fields($fields){
	$fields['_fb_profile'] = array(
		'label'       => __( 'Perfil de Facebook', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico',
		'priority'    => 7
	);	
	$fields['_fb_url'] = array(
		'label'       => __( 'URL de Facebook', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico',
		'priority'    => 8
	);	
	$fields['_instagram'] = array(
		'label'       => __( 'Usuario de Instagram', 'job_manager' ),
		'type'        => 'text',
		'required'    => false,
		'placeholder' => 'directico/',
		'priority'    => 9
	);
  return $fields;
}



/* redirect after job post / update */
// add_action('job_manager_save_job_listing', 'redirect_users_to_dashboard', 99, 2);
add_action('job_manager_update_job_data', 'redirect_users_to_dashboard', 99, 2);

function redirect_users_to_dashboard($post_id, $values) {
	$status = get_post_status($post_id);
	$dash = get_option('job_manager_job_dashboard_page_id');
	if($status !== 'preview') {
		echo '<script>
				setTimeout(function(){
					window.location = window.location.href;
				}, 4000);
				</script>';
	}
}


/* localize available additional locations */

function getMetaValue($result, $item) {
	$locations = get_post_meta($item, '_additionallocations', true);
	$result[$item] =  $locations;
	return $result;
}

add_action( 'wp_enqueue_scripts', 'localize_data', 12 );

function localize_data() {
	wp_dequeue_script('listable-scripts');
	wp_enqueue_script('listable-scripts');
	wp_localize_script( 'listable-scripts', 'themeLocation', array(
		'childTheme' => get_stylesheet_directory_uri()
	) );
	// we need to localize all data to make it work on explore page
	if(is_page('explore') || is_tax()) {
		$listings = get_posts("post_type=job_listing&posts_per_page=-1&post_status=publish");
		$listingids = wp_list_pluck($listings, 'ID');
		$additionallocations = array_filter(array_reduce($listingids, 'getMetaValue', array()));
		wp_localize_script( 'listable-scripts', 'additionallocations', $additionallocations );
	}
}



/* remove map sidebar widget */
function remove_calendar_widget() {
	unregister_widget('Listing_Sidebar_Map_Widget');
}
add_action( 'widgets_init', 'remove_calendar_widget', 99 );

/* register custom map widget */
register_widget('Custom_Listing_Sidebar_Map_Widget');

class Custom_Listing_Sidebar_Map_Widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			'listing_sidebar_map', // Base ID
			'&#x1F536; ' . esc_html__( 'Listing', 'listable' ) . '  &raquo; ' . esc_html__( 'Location Map', 'listable' ), // Name
			array( 'description' => esc_html__( 'A Map View of the listing location along with a Directions link to Google Map.', 'listable' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		global $post;

		$address = listable_get_formatted_address();

		if ( empty( $address ) ) {
			return;
		}

		$geolocation_lat  = get_post_meta( get_the_ID(), 'geolocation_lat', true );
		$geolocation_long = get_post_meta( get_the_ID(), 'geolocation_long', true );

		$get_directions_link = '';
		if ( ! empty( $geolocation_lat ) && ! empty( $geolocation_long ) && is_numeric( $geolocation_lat ) && is_numeric( $geolocation_long ) ) {
			$get_directions_link = '//maps.google.com/maps?daddr=' . $geolocation_lat . ',' . $geolocation_long;
		}
		
		if ( empty( $get_directions_link ) ) {
			return;
		}
		echo $args['before_widget']; 

		/* localize the listing location to show in preview and single location map */
		$previewlocations = array(
				$post->ID => get_post_meta($post->ID, '_additionallocations', true),
		);
		wp_localize_script( 'listable-scripts', 'additionallocations', $previewlocations );


		?>

		<div class="listing-map-container" itemprop="geo" itemscope itemtype="http://schema.org/GeoCoordinates">
			<div id="map" class="listing-map"></div>

			<?php if ( ! empty( $geolocation_lat ) && ! empty( $geolocation_long ) && is_numeric( $geolocation_lat ) && is_numeric( $geolocation_long ) ) : ?>

				<meta itemprop="latitude" content="<?php echo $geolocation_lat; ?>"/>
				<meta itemprop="longitude" content="<?php echo $geolocation_long; ?>"/>

			<?php endif; ?>

		</div>
		<div class="listing-map-content">
			<div class="address-container">
				<address class="listing-address" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
					<?php
					echo '' . $address;
					if ( true == apply_filters( 'listable_skip_geolocation_formatted_address', false ) ) { ?>
						<meta itemprop="streetAddress" content="<?php echo trim( get_post_meta( $post->ID, 'geolocation_street_number', true ), '' ); ?> <?php echo trim( get_post_meta( $post->ID, 'geolocation_street', true ), '' ); ?>">
						<meta itemprop="addressLocality" content="<?php echo trim( get_post_meta( $post->ID, 'geolocation_city', true ), '' ); ?>">
						<meta itemprop="postalCode" content="<?php echo trim( get_post_meta( $post->ID, 'geolocation_postcode', true ), '' ); ?>">
						<meta itemprop="addressRegion" content="<?php echo trim( get_post_meta( $post->ID, 'geolocation_state', true ), '' ); ?>">
						<meta itemprop="addressCountry" content="<?php echo trim( get_post_meta( $post->ID, 'geolocation_country_short', true ), '' ); ?>">
					<?php } ?>
				</address>
				<a href="<?php echo $get_directions_link; ?>" class="listing-address-directions" target="_blank">
					Cómo llegar
				</a>
				<br>
				<a class="waze-desktop-link" href="https://www.waze.com/es/livemap?zoom=17&lat=<?php $key="geolocation_lat"; echo get_post_meta($post->ID, $key, true); ?>&lon=<?php $key="geolocation_long"; echo get_post_meta($post->ID, $key, true); ?>" class="listing-address-directions" target="_blank">
					Waze
				</a>
				<a class="waze-mobile-link" href="waze://?ll=<?php $key="geolocation_lat"; echo get_post_meta($post->ID, $key, true); ?>,<?php $key="geolocation_long"; echo get_post_meta($post->ID, $key, true); ?>" class="listing-address-directions" target="_blank">
					Waze
				</a>
			</div>
			<?php
				$locations = get_post_meta( $post->ID, '_additionallocations', true); 
				if (is_array($locations)) {
					foreach ($locations as $key => $value) {
						echo '<div class="address-container">';
						echo '<address class="listing-address">' . $value['name'] . '</address>';
						echo '<a href="//maps.google.com/maps?daddr='.$value['geo_lat'].','.$value['geo_lng'].'" class="listing-address-directions" target="_blank">Cómo llegar</a>';
						echo '<a class="waze-desktop-link" href="https://www.waze.com/es/livemap?zoom=17&lat='.$value['geo_lat'].'&lon='.$value['geo_lng'].'" class="listing-address-directions" target="_blank">
							Waze
						</a>
						<a class="waze-mobile-link" href="waze://?ll='.$value['geo_lat'].','.$value['geo_lng'].'" class="listing-address-directions" target="_blank">
							Waze
						</a>';
						echo '</div>';
					}
				}
			?>
		</div><!-- .listing-map-content -->

		<?php
		echo $args['after_widget'];
	}
	public function form( $instance ) {
		echo '<p>' . $this->widget_options['description'] . '</p>';
	}
} // class Custom_Listing_Sidebar_Map_Widget

// Remove location from jobs permalinks
add_filter( 'submit_job_form_prefix_post_name_with_location', '__return_false' );

add_filter('show_admin_bar', '__return_false');


// custom gravatar 
// https://www.billerickson.net/wordpress-custom-avatar/

function my_woocommerce_edit_account_form() {
	if(class_exists('WP_User_Avatar_Setup')) {
		echo do_shortcode('[avatar_upload] ');
	}
}

add_action( 'woocommerce_before_edit_account_form', 'my_woocommerce_edit_account_form' );

// redirect after profile udpate

// function redirect_after_profile_update( $user_id ) {
// 	wp_safe_redirect( 'https://google.com' );
// 	exit();
// }
// add_action( 'woocommerce_save_account_details', 'redirect_after_profile_update', 10);

function add_referrer_code(){
	$site = get_bloginfo('url');
	$prev_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '';
	$urlTo = $site . '/my-account/edit-account';
	if( $prev_url == $urlTo ) : 
		wp_safe_redirect( $urlTo );
		// echo '<script>
		// setTimeout(function(){
		// 	window.location = '. $urlTo .';
		// }, 100);
		// </script>';
	endif;
}

// add_action('wp_head', 'add_referrer_code');


/* edit profile */
function update_user_profile_func(){

	$message = __('Profile updated', 'listable-child-theme');
	$status = 'SUCCESS';

	$form_data = array();
	$userId = get_current_user_id();
    if (isset($_POST['form_data'])) {
        parse_str($_POST['form_data'], $form_data);
    }
	$data = array_filter($form_data);

	$password_current = $data['password_current'];
	$password_1 = $data['password_1'];
	$password_2 = $data['password_2'];

    foreach ($data as $key => $value) {
    	switch ($key) {
    		case 'account_first_name':
    			update_user_meta($userId, 'first_name', $value);
    			break;    		
    		case 'account_last_name':
    			update_user_meta($userId, 'last_name', $value);
    			break;
    		    		
    		case 'account_email':
				wp_update_user( array( 'ID' => $userId, 'user_email' => $value ) );    			
				break;    		
    		
    		default:
    			// update_user_meta($userId, $key, $value);
    			break;
    	}
    }

    if(isset($password_current)) {
    	$user = get_user_by( 'ID', $userId );
	    if( $password_1 != $password_2 ) 
	    {
	        $message = __('Password didn\'t match', 'listable-child-theme');
	        $status = 'FAILED';
	    } 
	    else if ( ( $status == 'SUCCESS' ) && 
	        !wp_check_password( $password_current, $user->data->user_pass, $userId)) 
	    {
	        $message = __('Incorrect current password!', 'listable-child-theme');
	        $status = 'FAILED';
	    }

	    if( ( $status == 'SUCCESS' ) && $password_1 ) {
	        $user_id = wp_update_user( array( 'ID' => $userId, 'user_pass' => $password_1 ));
	        if(is_wp_error($user_id)) {
	            $message = __('Current password wrong!', 'listable-child-theme');
	            $status = 'FAILED';
	        } else {
	            $message = __('Password changed successfully', 'listable-child-theme');
	            $status = 'SUCCESS';
	        }
	    } elseif( $status == 'SUCCESS' ) {
	    	$message = __('New password required!', 'listable-child-theme');
	    	$status = 'FAILED';
	    }	
    }

    echo json_encode(array( 'status' => $status, 'message' => $message, 'data' => $data));
    die();
}

add_action('wp_ajax_update_user_profile', 'update_user_profile_func');
