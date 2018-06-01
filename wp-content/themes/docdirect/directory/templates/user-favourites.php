<?php
/**
 * User Favorites
 * return html
 */

global $current_user,$paged;
?>
<div class="tg-listingarea doc-favourites-wraper">
  <div class="tg-listing">
    <div class="tg-listing-head">
      <div class="tg-titlebox">
        <h3><?php esc_html_e('My Favorites','docdirect'); ?></h3>
      </div>
      <div class="tg-titlebox">
        <h3><?php esc_html_e('Category','docdirect'); ?></h3>
      </div>
      <div class="tg-titlebox">
        <h3><?php esc_html_e('Action','docdirect'); ?></h3>
      </div>
    </div>
    <div class="tg-lists tg-favorites">
     <?php 
	 	
		$limit = get_option('posts_per_page');
		if (empty($paged)) $paged = 1;
		$offset = ($paged - 1) * $limit;
		$wishlist    = get_user_meta($current_user->ID,'wishlist', true);
		$wishlist    = !empty($wishlist) && is_array( $wishlist ) ? $wishlist : array();
		
		$total_users = (int)count($wishlist); //Total Users
		
						
		$query_args	= array(
								'role'  => 'professional',
								'order' => 'DESC',
								'orderby' => 'ID',
								'include' => $wishlist
							 );
		
		$query_args['number']	= $limit;
		$query_args['offset']	= $offset;
										 
		$user_query  = new WP_User_Query($query_args);
		if ( ! empty( $wishlist ) ) {
			if ( ! empty( $user_query->results ) ) {
			  foreach ( $user_query->results as $user ) {
			  
			  $directories_array['name']	 	 = $user->first_name.' '.$user->last_name;
			  $directory_type	= $user->directory_type;
			  $avatar = apply_filters(
									'docdirect_get_user_avatar_filter',
									 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
									 array('width'=>150,'height'=>150) //size width,height
								);
			  ?>
			  <div class="tg-list" id="wishlist-<?php echo intval($user->ID); ?>">
				<div class="tg-listbox" data-title="my favorites">
				  <figure><a href="<?php echo get_author_posts_url($user->ID); ?>" class="list-avatar"><img src="<?php echo esc_attr( $avatar );?>" alt="<?php echo esc_attr( $directories_array['name'] );?>"></a></figure>
				  <div class="tg-listdata">
					<h4><a href="<?php echo get_author_posts_url($user->ID); ?>"><?php echo esc_attr( $directories_array['name'] );?></a></h4>
				  </div>
				</div>
				<div class="tg-listbox" data-insights="catagory"><span><?php echo esc_attr( ucwords( get_the_title( $directory_type ) ) );?></span></div>
				<div class="tg-listbox" data-action="action">
					<a class="tg-btn-list" href="<?php echo get_author_posts_url($user->ID); ?>"><i class="fa fa-eye"></i></a> 
					<a class="tg-btn-list remove-wishlist" href="javascript:;" data-wl_id="<?php echo intval($user->ID); ?>"><i class="fa fa-trash-o"></i></a> 
				</div>
			  </div>
		   <?php }} else{?>
				<div class="tg-list"><p><?php esc_html_e('Nothing found.','docdirect'); ?></p></div>
		  <?php }?>
         <?php } else{?>
            <div class="tg-list"><p><?php esc_html_e('Nothing found.','docdirect'); ?></p></div>
      <?php }?>
    </div>
    <?php 
	//Pagination
	if( $total_users > $limit ) {?>
	  <div class="tg-btnarea">
			<?php docdirect_prepare_pagination($total_users,$limit);?>
	  </div>
	<?php }?>
  </div>
</div>
