<?php
/**
 * Manage Teams
 * return html
 */

global $current_user;

wp_enqueue_style('jquery.auto-complete');
wp_enqueue_script('jquery.auto-complete');
?>
<div id="tg-content" class="tg-content">
  <div class="tg-dashboard tg-dashboardmanageteams">
    <form class="tg-themeform">
      <fieldset>
        <div class="tg-dashboardbox tg-manageteam tg-ourteam">
          <div class="tg-dashboardtitle">
            <h2><?php esc_html_e('Manage Team','docdirect'); ?></h2>
            <span><?php esc_html_e('Not in List?','docdirect'); ?> 
            	<a href="javascript:;" data-toggle="modal" data-target=".tg-categoryModal"><?php esc_html_e('Send Invitation Now','docdirect'); ?></a></span> 
          </div>
          <div class="tg-manageteambox">
            <div class="form-group">
                <div class="search-input-wrap">
                	<input type="text" name="searchmember" class="form-control searchmember-input" id="searchmember" placeholder="<?php esc_attr_e('Search Member Via Email ID','docdirect'); ?>">
                </div>
                <ul class="tg-teammembers tg-teammembers-wrap">
				<?php 
                    $limit = 30;
                    if (empty($paged)) $paged = 1;
                    $offset = ($paged - 1) * $limit;
                    $teams    = get_user_meta($current_user->ID,'teams_data', true);
                    $teams    = !empty($teams) && is_array( $teams ) ? $teams : array();
                    
                    $total_users = (int)count($teams); //Total Users
                    
                                    
                    $query_args	= array(
                                            'role'  => 'professional',
                                            'order' => 'DESC',
                                            'orderby' => 'ID',
                                            'include' => $teams
                                         );
                    
                    $query_args['number']	= $limit;
                    $query_args['offset']	= $offset;
                                                     
                    $user_query  = new WP_User_Query($query_args);
                    if ( ! empty( $teams ) ) {
                        if ( ! empty( $user_query->results ) ) {
                          foreach ( $user_query->results as $user ) {
                          
                          	$user_link = get_author_posts_url($user->ID);
							$username = docdirect_get_username($user->ID);
							$user_email = $user->user_email;
							$avatar = apply_filters(
												'docdirect_get_user_avatar_filter',
												 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $user->ID),
												 array('width'=>150,'height'=>150) //size width,height
											);
          			?>
                    <li data-id="<?php echo esc_attr( $user->ID );?>" id="team-<?php echo esc_attr( $user->ID );?>">
                        <div class="tg-teammember">
                            <a class="tg-btndel remove-team-member" href="javascript:;"><i class="fa fa-close"></i></a>
                            <figure><a href="<?php echo esc_url( $user_link );?>"><img width="60" height="60" src="<?php echo esc_url( $avatar );?>"></a></figure>
                            <div class="tg-memberinfo">
                                <h5><a href="<?php echo esc_url( $user_link );?>"><?php echo esc_attr( $username );?></a></h5>
                                <a href="<?php echo esc_url( $user_link );?>"><?php esc_html_e('View Full Profile','docdirect'); ?></a>
                            </div>
                        </div>
                    </li>
                    <?php }} else{?>
                            <li class="no-team-item"><div class="tg-list"><p><?php esc_html_e('No team members, Add your teams now.','docdirect'); ?></p></div></li>
                    <?php }?>
                    <?php } else{?>
                         <li class="no-team-item"><div class="tg-list"><p><?php esc_html_e('No team members, Add your teams now.','docdirect'); ?></p></div></li>
                  <?php }?>
                </ul>
            </div>
          </div>
          
        </div>
        <?php 
			//Pagination
			if( ( isset( $total_users ) && isset( $limit )  )
				&&
				$total_users > $limit 
			) {?>
			  <div class="tg-btnarea">
					<?php docdirect_prepare_pagination($total_users,$limit);?>
			  </div>
		  <?php }?>
      </fieldset>
    </form>
  </div>
</div>

<script>
jQuery(document).ready(function (e) {
	var loder_html	= '<div class="docdirect-site-wrap"><div class="docdirect-loader"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div></div>';
	var timer;
	jQuery('#searchmember').autoComplete({
		minChars: 1,
		delay: 500,
		cache: true,
		source: function (term, suggest) {

			var term = term.toLowerCase();

			if( term !== '' ) {
				jQuery('.search-input-wrap').find('.fa-spinner').remove();
				jQuery('.search-input-wrap').append("<i class='fa fa-spinner fa-spin'></i>");
				jQuery.ajax({
					type: "POST",
					url: scripts_vars.ajaxurl,
					data: 'email=' + term + '&action=docdirect_get_team_members',
					dataType: "json",
					success: function (response) {

						jQuery('.search-input-wrap').find('.fa-spinner').remove();
						if (response.type === 'success') {
							/*var Z_TEAMS = {};
							Z_TEAMS.elements = {};
							window.Z_TEAMS = Z_TEAMS;
							Z_TEAMS.elements = jQuery.parseJSON(response.teams_data);*/

							var data = response.user_json;

							var teamData = [];
							for (var i in data) {
								var item = data[i];
								var outer = [];
								outer.push(data[i]['user_email']);
								outer.push(data[i]['id']);
								outer.push(data[i]['user_link']);
								outer.push(data[i]['username']);
								outer.push(data[i]['photo']);

								teamData.push(outer);
							}

							var choices = teamData;

							var suggestions = [];

							for (i = 0; i < choices.length; i++) {

								if (~(choices[i][0] + ' ' + choices[i][1]).toLowerCase().indexOf(term))
									suggestions.push(choices[i]);
							}

							suggest(suggestions);

						} else{
							jQuery.sticky(response.msg, {classList: 'important', speed: 200, autoclose: 5000});
						}
					}
				});
			}
		},
		renderItem: function (item, search) {
			//search = search.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
			var re = new RegExp("(" + search.split(' ').join('|') + ")", "gi");
			return '<div class="autocomplete-suggestion" data-name="'+item[3]+'" data-url="' + item[2] + '" data-photo="' + item[4] + '" data-id="' + item[1] + '" data-val="' + search + '"><div class="searched-item-wrap"><img width="50" height="50" src="' + item[4] + '" /><span class="searched-name">' + item[3].replace(re, "<b>$1</b>") + '</span><a class="searched-add-new" href="javascript:;">' + scripts_vars.add_now + '</a></div></div>';
		},
		onSelect: function (e, term, item) {
			
			var id 		= item.data('id');
			var name 	= item.data('name');
			var url 	= item.data('url');
			var photo	= item.data('photo');
			var email	= item.data('val');
			
			jQuery('body').append(loder_html);
			var _html	 = '<li data-id="'+id+'" id="team-'+id+'"><div class="tg-teammember"><a class="tg-btndel remove-team-member" href="javascript:;"><i class="fa fa-close"></i></a><figure><a href="'+url+'"><img width="60" height="60" src="'+photo+'"></a></figure><div class="tg-memberinfo"><h5><a href="'+url+'">'+name+'</a></h5><a href="'+url+'">' + scripts_vars.view_profile + '</a> </div></div></li>';
			
			jQuery.ajax({
				type: "POST",
				url: scripts_vars.ajaxurl,
				data: 'id=' + id + '&action=docdirect_update_team_members',
				dataType: "json",
				success: function (response) {
					jQuery('body').find('.docdirect-site-wrap').remove();
				
					if( response.type == 'error' ) {
						jQuery.sticky(response.message, {classList: 'important', speed: 200, autoclose: 5000});
					} else{
						jQuery('.tg-teammembers-wrap').find('li.no-team-item').remove();
						jQuery.sticky(response.message, {classList: 'success', speed: 200, autoclose: 5000});
						jQuery('.tg-teammembers-wrap').append(_html);
					}	
				}
			});
			
		}
	});
});
</script>