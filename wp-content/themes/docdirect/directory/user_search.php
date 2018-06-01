<?php
/**
 *  Template Name: Search Page
 * 
 */

global $paged,$wp_query;

$dir_search_pagination = fw_get_db_settings_option('dir_search_pagination');
if( !empty( $_GET['per_page'] ) ){
	$per_page	= $_GET['per_page'];
} else{
	$per_page	= !empty( $dir_search_pagination ) ? $dir_search_pagination : get_option('posts_per_page');
}

$limit = (int)$per_page;

$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

$offset = ($paged - 1) * $limit;

$json	= array();
$directories	= array();
$meta_query_args = array();

$city = '';
$directory_type = '';
$insurance = '';
$speciality = '';

//Category Search
if( !empty( $_GET['directory_type'] ) ) {
	$directory_type = docdirect_get_page_by_slug( $_GET['directory_type'], 'directory_type','id' );
} else{
	if (is_singular('directory_type')) {
        $directory_type = $wp_query->get_queried_object_id();
    } else {
        $directory_type = '';
    }
}

//City search
if (!empty($_GET['city'])) {
    $city = !empty($_GET['city']) ? esc_attr($_GET['city']) : '';
} else {
    if (is_tax('locations')) {
        $sub_cat = $wp_query->get_queried_object();
        if (!empty($sub_cat->slug)) {
            $city = $sub_cat->slug;
        }
    } else {
        $city = '';
    }
}

//insurance search
if (!empty($_GET['insurance'])) {
    $insurance = !empty($_GET['insurance']) ? $_GET['insurance'] : '';
} else {
    if (is_tax('insurance')) {
        $sub_cat = $wp_query->get_queried_object();
        if (!empty($sub_cat->slug)) {
            $insurance = $sub_cat->slug;
        }
    } else {
        $insurance = '';
    }
}

//Category seearch

if (!empty($_GET['sub_category'])) {
    $sub_category = !empty($_GET['sub_category']) ? $_GET['sub_category'] : '';
} else {
    if (is_tax('sub_category')) {
        $sub_cat = $wp_query->get_queried_object();
        if (!empty($sub_cat->slug)) {
            $sub_category = array( $sub_cat->slug );
        }
    } else {
        $sub_category = '';
    }
}


//speciality search
if (!empty($_GET['speciality'])) {
    $speciality = !empty($_GET['speciality']) ? $_GET['speciality'] : '';
} else {
    if (is_tax('specialities')) {
        $sub_cat = $wp_query->get_queried_object();
        if (!empty($sub_cat->slug)) {
            $speciality = array( $sub_cat->slug );
        }
    } else {
        $speciality = '';
    }
}

//Other filters
$geo_location  = !empty( $_GET['geo_location'] ) ? $_GET['geo_location'] : '';
$location	   = !empty( $_GET['location'] ) ? $_GET['location'] : '';
$keyword	   = !empty( $_GET['keyword'] ) ? $_GET['keyword'] : '';
$languages	   = !empty( $_GET['languages'] ) ? $_GET['languages'] : '';
$appointments  = !empty( $_GET['appointments'] ) ? $_GET['appointments'] : '';
$sort_by  	   = !empty( $_GET['sort_by'] ) ? $_GET['sort_by'] : 'recent';
$photos  	   = !empty( $_GET['photos'] ) ? $_GET['photos'] : '';
$zip  	   	   = !empty( $_GET['zip'] ) ? $_GET['zip'] : '';

//Order
$order	= 'DESC';
if( isset( $_GET['order'] ) && !empty( $_GET['order'] ) ){
	$order	= $_GET['order'];
}

$sorting_order	= 'ID';
if( $sort_by === 'recent' ){
	$sorting_order	= 'ID';
} else if( $sort_by === 'title' ){
	$sorting_order	= 'display_name';
}

$query_args	= array(
					'role'  => 'professional',
					'order' => $order,
					'orderby' => $sorting_order,
				 );


//Search Featured
if( $sort_by === 'featured' ){
	$query_args['orderby']	   = 'meta_value_num';	
	$query_args['order']	   = 'DESC';
	
	$query_relation = array('relation' => 'OR',);
	$featured_args	= array();
	$featured_args[] = array(
							'key'     => 'user_featured',
							'compare' => 'EXISTS'
						);
	
	$meta_query_args[]	= array_merge( $query_relation,$featured_args );
		
}	

//Search By likes
if( $sort_by === 'likes' ){
	$query_args['order']	   = $order;
	$query_args['orderby']	   = 'meta_value_num';	
				
	$query_relation = array('relation' => 'OR',);
	$likes_args	= array();
	$likes_args[] = array(
							'key'     => 'doc_user_likes_count',
							'compare' => 'EXISTS'
						);
	
	$likes_args[] = array(
							'key'     => 'doc_user_likes_count',
							'compare' => 'NOT EXISTS'
						);
	
	$meta_query_args[]	= array_merge( $query_relation,$likes_args );
	
}


//Search By Keywords
if( isset( $_GET['by_name'] ) && !empty( $_GET['by_name'] ) ){
	$s = sanitize_text_field($_GET['by_name']);
	$search_args	= array(
							'search'         => '*'.esc_attr( $s ).'*',
							'search_columns' => array(
								'ID',
								'display_name',
								'user_login',
								'user_nicename',
								'user_email',
								'user_url',
							)
						);
	
	$meta_by_name	=  array();
	$meta_by_name[] = array(
							'key' 	    => 'first_name',
							'value' 	=> $s,
							'compare'   => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	   => 'last_name',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	    => 'nickname',
							'value' 	=> $s,
							'compare'   => 'LIKE',
						);

	$meta_by_name[] = array(
							'key' 	   => 'username',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	   => 'full_name',
							'value' 	 => $s,
							'compare'   => 'LIKE',
						);

	$meta_by_name[] = array(
							'key' 	    => 'description',
							'value' 	=> $s,
							'compare'   => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'professional_statements',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'prices_list',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'user_address',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'awards',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);

	$meta_by_name[] = array(
							'key' 	     => 'user_profile_specialities',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'location',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);
	
	$meta_by_name[] = array(
							'key' 	     => 'tagline',
							'value' 	 => $s,
							'compare'    => 'LIKE',
						);

	$query_string	= explode(' ',$s);
	
	if( !empty($query_string ) ){
		foreach( $query_string as $key => $value ){
			$meta_by_name[] = array(
							'key' 	   => 'first_name',
							'value' 	 => $value,
							'compare'   => 'LIKE',
						);
	
			$meta_by_name[] = array(
									'key' 	   => 'last_name',
									'value' 	 => $value,
									'compare'   => 'LIKE',
								);
			$meta_by_name[] = array(
									'key' 	   => 'full_name',
									'value' 	 => $value,
									'compare'   => 'LIKE',
								);
			
		}
	}
	
	if( !empty( $meta_by_name ) ) {
		$query_relation = array('relation' => 'OR',);
		$meta_query_args[]	= array_merge( $query_relation,$meta_by_name );
	}
	
}

//Directory Type Search
if( isset( $directory_type ) && !empty( $directory_type ) ){
	$meta_query_args[] = array(
							'key' 	   		=> 'directory_type',
							'value' 	 	=> $directory_type,
							'compare'   	=> '=',
						);
}


//Cities
if(  !empty( $city ) ){
	$meta_query_args[] = array(
							'key' 	    => 'location',
							'value' 	=> $city,
							'compare'   => '=',
						);
}


//Photos search
if( !empty( $photos ) &&  $photos === 'true' ){
	$meta_query_args[] = array(
							'key' 	   => 'userprofile_media',
							'value'    => array('',0),
        					'compare'  => 'NOT IN'
						);
}

//insurance
if( !empty( $insurance ) ){
	$meta_query_args[] = array(
							'key' 	  => 'insurance',
							'value'   => serialize( strval( $insurance ) ),
							'compare' => 'LIKE',
						);
}

//online appointments Search
if( !empty( $appointments ) && $appointments === 'true' ){
	$meta_query_args[] = array(
							'key'     => 'appointments',
							'value'   => 'on',
							'compare' => '='
						);
}

//Zip Search
if( isset( $zip ) && !empty( $zip ) ){
	$meta_query_args[] = array(
							'key'     => 'zip',
							'value'   => $zip,
							'compare' => '='
						);
}

//Location Search
if( isset( $location ) && !empty( $location ) ){
	$meta_query_args[] = array(
							'key'     => 'location',
							'value'   => $location,
							'compare' => '='
						);
}

//Language Search;
if( !empty( $languages ) && !empty( $languages[0] ) && is_array( $languages ) ){ 
	$query_relation = array('relation' => 'OR',);
	$language_args	= array();
	foreach( $languages as $key => $value ){
		$language_args[] = array(
								'key'     => 'languages',
								'value'   => serialize( strval( $value ) ),
								'compare' => 'LIKE'
							);
	}
	
	$meta_query_args[]	= array_merge( $query_relation,$language_args );
}

//Speciality Search;
if( !empty( $speciality ) && !empty( $speciality[0] ) && is_array( $speciality ) ){ 
	$query_relation = array('relation' => 'OR',);
	$speciality_args	= array();
	foreach( $speciality as $key => $value ){
		$speciality_args[] = array(
								'key'     => $value,
								'value'   => $value,
								'compare' => '='
							);
	}
	
	$meta_query_args[]	= array_merge( $query_relation,$speciality_args );
}

//Sub Category Search;
if( !empty( $sub_category ) && !empty( $sub_category[0] ) && is_array( $sub_category ) ){ 
	$query_relation = array('relation' => 'OR',);
	$subcategory_args	= array();
	foreach( $sub_category as $key => $value ){
		$subcategory_args[] = array(
								'key' 		=> 'doc_sub_categories',
								'value'   	=> serialize( strval( $value ) ),
								'compare' 	=> 'LIKE',
							);
	}
	
	$meta_query_args[]	= array_merge( $query_relation,$subcategory_args );
}

//Verify user
$meta_query_args[] = array(
							'key'     => 'verify_user',
							'value'   => 'on',
							'compare' => '='
						);
						
if( !empty( $meta_query_args ) ) {
	$query_relation = array('relation' => 'AND',);
	$meta_query_args	= array_merge( $query_relation,$meta_query_args );
	$query_args['meta_query'] = $meta_query_args;
}
					
//Radius Search
if( (isset($_GET['geo_location']) && !empty($_GET['geo_location'])) ){

	$prepAddr   = '';
	$minLat	 = '';
	$maxLat	 = '';
	$minLong	= '';
	$maxLong	= '';
	
	$address	 = !empty($_GET['geo_location']) ? $_GET['geo_location'] : '';
	$prepAddr	= str_replace(' ','+',$address);
	
	$Latitude   = !empty( $_GET['lat'] ) ? $_GET['lat'] : '';
	$Longitude  = !empty( $_GET['long'] ) ? $_GET['long'] : '';
	
	if( isset( $_GET['geo_distance'] ) && !empty( $_GET['geo_distance'] ) ){
		$radius = $_GET['geo_distance'];
	} else{
		$radius = 300;
	}
	
	//Distance in miles or kilometers
	if (function_exists('fw_get_db_settings_option')) {
		$dir_distance_type = fw_get_db_settings_option('dir_distance_type');
	} else{
		$dir_distance_type = 'mi';
	}
	
	if( $dir_distance_type === 'km' ) {
		$radius = $radius * 0.621371;
	}
	
	if( !empty( $Latitude ) && !empty( $Longitude ) ){
		$Latitude	 = $Latitude;
		$Longitude   = $Longitude;
		
	} else{

		$args = array(
			'timeout'     => 15,
			'headers' => array('Accept-Encoding' => ''),
			'sslverify' => false
		);

		$url	    = 'http://maps.google.com/maps/api/geocode/json?address='.$prepAddr.'&sensor=false';
		$response   = wp_remote_get( $url, $args );
		$geocode	= wp_remote_retrieve_body($response);

		$output	  = json_decode($geocode);
		
		if( isset( $output->results ) && !empty( $output->results ) ) {
			$Latitude	= $output->results[0]->geometry->location->lat;
			$Longitude   = $output->results[0]->geometry->location->lng;
		}
	}
	
	
	if( !empty( $Latitude ) && !empty( $Longitude ) ){

		$zcdRadius = new RadiusCheck($Latitude,$Longitude,$radius);
		$minLat  = $zcdRadius->MinLatitude();
		$maxLat  = $zcdRadius->MaxLatitude();
		$minLong = $zcdRadius->MinLongitude();
		$maxLong = $zcdRadius->MaxLongitude();
		
		$meta_query_args = array(
			'relation' => 'AND',
			 array(
				'relation' => 'AND',
				array(
					'key' 		=> 'latitude',
					'value'  	=> array($minLat, $maxLat),
					'compare' 	=> 'BETWEEN',
					'type' 	=> 'DECIMAL(20,10)',
				),
				array(
					'key' 		=> 'longitude',
					'value'   	  => array($minLong, $maxLong),
					'compare' 	=> 'BETWEEN',
					'type' 	=> 'DECIMAL(20,10)',
				)
			),
		);
		
		if( isset( $query_args['meta_query'] ) && !empty( $query_args['meta_query'] ) ) {
			$meta_query	= array_merge($meta_query_args,$query_args['meta_query']);
		} else{
			$meta_query	= $meta_query_args;
		}

		$query_args['meta_query']	= $meta_query;
	}
}


$query_args	= apply_filters('docdirec_apply_extra_search_filters',$query_args);

//Count total users for pagination
$total_query    = new WP_User_Query( $query_args );
$total_users	= $total_query->total_users;

$query_args['number']	= $limit;
$query_args['offset']	= $offset;

if( !empty( $geo_location ) 
	&& !empty( $directory_type )
){ 
	$found_title	= $total_users.'&nbsp;'.esc_html__('matche(s) found for','docdirect').'&nbsp;:&nbsp;<em>'.get_the_title($directory_type).'&nbsp;in&nbsp;'. $geo_location.'</em>';
} else if( empty( $geo_location ) 
	&& !empty( $directory_type )
){ 
	$found_title	= $total_users.'&nbsp;'.esc_html__('matche(s) found for','docdirect').'&nbsp;:&nbsp;<em>'.get_the_title($directory_type).'</em>';
} else if( !empty( $geo_location ) 
	&& empty( $directory_type )
){ 
	$found_title	= $total_users.'&nbsp;'.esc_html__('matche(s) found in','docdirect').'<em>&nbsp;'. $geo_location.'</em>';
} else {
	$found_title	= $total_users . esc_html__('&nbsp;matches found','docdirect');
}

$default_view = 'list-v2';
$default_listing_type = 'list-v2';
if (function_exists('fw_get_db_post_option')) {
	$default_view = fw_get_db_settings_option('dir_search_view');
	$default_listing_type = fw_get_db_settings_option('dir_listing_type');
}


$get_view	= isset(  $_GET['view'] ) && !empty( $_GET['view'] ) ?  $_GET['view'] : '';

//Demo user Only
if(  (  $get_view === 'grid-left' || $get_view === 'list-left'  )
		||
		(   $default_listing_type === 'left' 
			&& 
			( $default_view != 'list_v2' && $default_view != 'grid_v2')
			&& 
			( $default_view == 'list' || $default_view == 'grid' )
			&& 
			( $get_view != 'list-v2' && $get_view != 'grid-v2' )
		) 
){
	$dir_listing_type	= 'left';
} else if( ( $get_view === 'grid' || $get_view === 'list' ) 
		||
	     (  $default_listing_type === 'top' 
		 	&& ( $default_view != 'list_v2' && $default_view != 'grid_v2')
			&& ( $default_view == 'list' || $default_view == 'grid')
			&& ( $get_view != 'list-v2' && $get_view != 'grid-v2' )
		 ) 
){
	$dir_listing_type	= 'top';
} else if(  $get_view === 'grid-v2'
		||
		( isset( $default_view ) && $default_view === 'grid_v2' )
) {
	$dir_listing_type	= 'grid-v2';
}  else if( $get_view === 'list-v2'
		||
		( isset( $default_view ) && $default_view === 'list_v2' )
) {
	$dir_listing_type	= 'list-v2';
}

if( isset( $dir_listing_type ) && $dir_listing_type === 'left' ){
	include(locate_template('directory/templates/map-search-left.php'));
} else if( isset( $dir_listing_type ) && $dir_listing_type === 'top' ){
	include(locate_template('directory/templates/map-search-top.php'));
} else if( isset( $dir_listing_type ) && $dir_listing_type === 'grid-v2'){
	include(locate_template('directory/templates/map-search-grid.php'));
} else{
	include(locate_template('directory/templates/map-search-list.php'));
}