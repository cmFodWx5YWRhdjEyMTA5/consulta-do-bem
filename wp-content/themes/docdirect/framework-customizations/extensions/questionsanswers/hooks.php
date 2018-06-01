<?php

if (!defined('FW')) {
    die('Forbidden');
}

/**
 * @hook render questions listing view
 * @type echo
 */
if (!function_exists('_filter_fw_ext_get_render_questions_view')) {

    function _filter_fw_ext_get_render_questions_view() {
        echo fw_ext_get_render_questions_view();
    }

    add_action('render_questions_listing_view', '_filter_fw_ext_get_render_questions_view', 10);
}


/**
 * @hook render add questions view
 * @type echo
 */
if (!function_exists('_filter_fw_ext_get_render_add_questions_view')) {

    function _filter_fw_ext_get_render_add_questions_view() {
        echo fw_ext_get_render_add_questions_view();
    }

    add_action('render_add_questions_view', '_filter_fw_ext_get_render_add_questions_view', 10);
}

/**
 * @hook render answers view
 * @type echo
 */
if (!function_exists('_filter_fw_ext_get_render_answers_view')) {

    function _filter_fw_ext_get_render_answers_view() {
        echo fw_ext_get_render_answers_view();
    }

    add_action('render_answers_view', '_filter_fw_ext_get_render_answers_view', 10, 1);
}

/**
 * @hook save questions
 */
if (!function_exists('fw_ext_docdirect_process_questions')) {

    function fw_ext_docdirect_process_questions() {

        global $current_user, $wp_roles, $userdata;
        $provider_category = docdirect_get_provider_category($current_user->ID);
        $json = array();
		$type = !empty($_POST['type']) ? esc_attr($_POST['type']) : '';
		
		remove_all_filters("content_save_pre");
		
		if( function_exists('docdirect_is_demo_site') ) { 
			docdirect_is_demo_site() ;
		}; //if demo site then prevent
		
		
        $do_check = check_ajax_referer('docdirect_question_answers_nounce', 'docdirect_question_answers_nounce', false);
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('No kiddies please!', 'docdirect');
            echo json_encode($json);
            die;
        }
		
		//if question is open
		if ( isset($_POST['category']) && empty( $_POST['category'] ) && $type === 'open' ) {
			$json['type'] = 'error';
            $json['message'] = esc_html__('Question category is required.', 'docdirect');
            echo json_encode($json);
            die;
		}

        if (empty($_POST['question_title'])) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Question title field should not be empty.', 'docdirect');
            echo json_encode($json);
            die;
        }

        $question_title = !empty($_POST['question_title']) ? esc_attr($_POST['question_title']) : esc_html__('unnamed', 'docdirect');
        $question_detail = force_balance_tags($_POST['question_description']);

        $author_id = !empty($_POST['author_id']) ? base64_decode($_POST['author_id']) : '';
        $questions_answers_post = array(
            'post_title' 	=> $question_title,
            'post_status' 	=> 'publish',
            'post_content'  => $question_detail,
            'post_author'   => $current_user->ID,
            'post_type' 	=> 'sp_questions',
            'post_date' 	=> current_time('Y-m-d H:i:s')
        );

        $post_id = wp_insert_post($questions_answers_post);
		
		
		
		if ( !empty($_POST['category']) ) {
			$category 	 = intval( $_POST['category'] );
		} else{
			$category 	 = get_user_meta($author_id, 'directory_type', true);
		}
		
        update_post_meta($post_id, 'question_to', $author_id);
        update_post_meta($post_id, 'question_by', $current_user->ID);
		update_post_meta($post_id, 'question_cat', $category);
		
		if (class_exists('DocDirectProcessEmail')) {
            if( isset( $type ) && $type === 'closed' && !empty( $author_id ) ){
				$email_helper = new DocDirectProcessEmail();
				$emailData	= array();
				$emailData['user_id']			= $author_id;
				$emailData['question_title']	= $question_title;
				
				//if method exist
				if (method_exists($email_helper, 'process_question_email')){
					$email_helper->process_question_email($emailData);
				}
            	
			}	
        }
		
        $json['type'] = 'success';
        $json['message'] = esc_html__('Question submit successfully.', 'docdirect');
        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_fw_ext_docdirect_process_questions', 'fw_ext_docdirect_process_questions');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_process_questions', 'fw_ext_docdirect_process_questions');
}


/**
 * @hook Save Answers
 */
if (!function_exists('fw_ext_docdirect_process_answers')) {

    function fw_ext_docdirect_process_answers() {
        global $current_user, $wp_roles, $userdata;
        $json = array();
		
		remove_all_filters("content_save_pre");
		
		if( function_exists('docdirect_is_demo_site') ) { 
			docdirect_is_demo_site() ;
		}; //if demo site then prevent
		
		$offset = get_option('gmt_offset') * intval(60) * intval(60);
		
        $do_check = check_ajax_referer('docdirect_answers_nounce', 'docdirect_answers_nounce', false);
        if ($do_check == false) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('No kiddies please!', 'docdirect');
            echo json_encode($json);
            die;
        }

        if (empty($_POST['answer_description'])) {
            $json['type'] = 'error';
            $json['message'] = esc_html__('Answers description area should not be empty.', 'docdirect');
            echo json_encode($json);
            die;
        }

        $answer_detail = force_balance_tags($_POST['answer_description']);

        $question_id = !empty($_POST['question_id']) ? intval($_POST['question_id']) : '';
        $questions_answers_post = array(
            'post_title' 	=> '',
            'post_status' 	=> 'publish',
            'post_content' 	=> $answer_detail,
            'post_author' 	=> $current_user->ID,
            'post_type' 	=> 'sp_answers',
			'post_parent'	=> $question_id,
            'post_date' 	=> current_time('Y-m-d H:i:s')
        );

        $post_id = wp_insert_post($questions_answers_post);

        update_post_meta($post_id, 'answer_question_id', $question_id);
        update_post_meta($post_id, 'answer_user_id', $current_user->ID);
		
		if (class_exists('DocDirectProcessEmail')) {
            $email_helper = new DocDirectProcessEmail();
			$emailData	= array();
			$question_author = get_post_meta($question_id, 'question_by', true);
			$emailData['answer_author']		= $current_user->ID;
			$emailData['question_author']	= $question_author;
			$emailData['question_title']	= get_the_title($question_id);
			$emailData['link']				= get_the_permalink($question_id);
			
			//if method exist
			if (method_exists($email_helper, 'process_answer_email')){
				$email_helper->process_answer_email($emailData);
			}
        }

		
        $json['type'] = 'success';
        $json['message'] = esc_html__('Answer submitted successfully.', 'docdirect');
        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_fw_ext_docdirect_process_answers', 'fw_ext_docdirect_process_answers');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_process_answers', 'fw_ext_docdirect_process_answers');
}

/**
 * @hook update votes
 */
if (!function_exists('fw_ext_docdirect_update_votes')) {

    function fw_ext_docdirect_update_votes() {
        global $current_user, $wp_roles, $userdata;
        $json = array();
		$key	= !empty( $_POST['key'] ) ? esc_attr( $_POST['key'] ) : '';
		$id		= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		
		if(empty( $id ) || empty( $current_user->ID )){ return;}
		
		$db_key	= 'total_votes';
		$count  = get_post_meta($id, $db_key, true);

		if ( empty( $count ) ) {
			if( $key === 'up' ){
				$count = 1;
				do_action('fw_add_user_to_votes',$id);
			} else{
				$count = -1;
				do_action('fw_remove_user_from_votes',$id);
			}
			
			update_post_meta($id, $db_key, $count);
		} else {
			if( $key === 'up' ){
				$count++;
				do_action('fw_add_user_to_votes',$id);
			} else{
				$count--;
				do_action('fw_remove_user_from_votes',$id);
			}
			
			update_post_meta($id, $db_key, $count);
		}
		
        $json['vote'] = $count;
        $json['type'] = 'success';
        $json['message'] = esc_html__('Your vote update.', 'docdirect');
        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_fw_ext_docdirect_update_votes', 'fw_ext_docdirect_update_votes');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_update_votes', 'fw_ext_docdirect_update_votes');
}

/**
 * @hook update likes
 */
if (!function_exists('fw_ext_docdirect_update_likes')) {

    function fw_ext_docdirect_update_likes() {
        global $current_user, $wp_roles, $userdata;
        $json = array();
		$key	= !empty( $_POST['key'] ) ? esc_attr( $_POST['key'] ) : '';
		$id		= !empty( $_POST['id'] ) ? intval( $_POST['id'] ) : 0;
		
		if(empty( $id ) || empty( $current_user->ID )){ return;}
		
		$db_key	= 'total_votes';
		$count  = get_post_meta($id, $db_key, true);
		
		$vote_users = array();
        $vote_users = get_post_meta($id, 'vote_users', true);
		$vote_users = !empty($vote_users) && is_array($vote_users) ? $vote_users : array();
		
		if( in_array( $current_user->ID, $vote_users) ){
			do_action('fw_remove_user_from_votes',$id);
			$count--;
			update_post_meta($id, $db_key, $count);
			$json['message'] = esc_html__('Your vote has removed', 'docdirect');
		} else{
			do_action('fw_add_user_to_votes',$id);
			$count++;
			update_post_meta($id, $db_key, $count);
			$json['message'] = esc_html__('Your vote has update', 'docdirect');
		}

        $json['vote'] = $count;
        $json['type'] = 'success';
        
        echo json_encode($json);
        die;
    }

    add_action('wp_ajax_fw_ext_docdirect_update_likes', 'fw_ext_docdirect_update_likes');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_update_likes', 'fw_ext_docdirect_update_likes');
}

/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('fw_remove_user_from_votes')) {

    function fw_remove_user_from_votes($id) {
		global $current_user;
		$vote_users = array();
        $vote_users = get_post_meta($id, 'vote_users', true);
        $vote_users = !empty($vote_users) && is_array($vote_users) ? $vote_users : array();
		
		$wl_id[]    = intval($current_user->ID);
		$vote_users = array_diff($vote_users, $wl_id);
        update_post_meta($id, 'vote_users', $vote_users);
    }
    add_action('fw_remove_user_from_votes', 'fw_remove_user_from_votes', 1, 10);
}

/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('fw_add_user_to_votes')) {

    function fw_add_user_to_votes($id) {
		global $current_user;
		$vote_users = array();
        $vote_users = get_post_meta($id, 'vote_users', true);
        $vote_users = !empty($vote_users) && is_array($vote_users) ? $vote_users : array();
		
		$vote_users[]  = intval($current_user->ID);
		$vote_users 	= array_unique($vote_users);
		update_post_meta($id, 'vote_users', $vote_users);
		
    }
    add_action('fw_add_user_to_votes', 'fw_add_user_to_votes', 1, 10);
}

/**
 * @Set Post Views
 * @return {}
 */
if (!function_exists('docdirect_set_question_views')) {

    function docdirect_set_question_views($post_id = '', $key = '') {
		
        if (!isset($_COOKIE[$key . $post_id])) {
            setcookie($key . $post_id, 'question_view_count', time() + 3600);
            $count = get_post_meta($post_id, $key, true);
			
            if ($count == '') {
                $count = 0;
                update_post_meta($post_id, $key, $count);
            } else {
                $count++;
                update_post_meta($post_id, $key, $count);
            }
        }
    }
    add_action('sp_set_question_views', 'docdirect_set_question_views', 2, 10);
}

/**
 * @load more questions
 */
if (!function_exists('fw_ext_docdirect_laodmore_questions')) {

    function fw_ext_docdirect_laodmore_questions() {
        global $current_user, $wp_roles, $userdata;
        $json = array();
		$page_no	= !empty( $_POST['page'] ) ? esc_attr( $_POST['page'] ) : '';
		$parent_id		= !empty( $_POST['parent_id'] ) ? esc_attr( $_POST['parent_id'] ) : '';
		$posts_per_page	= get_option('posts_per_page');;
		
		$q_args = array(
			'post_type' 		=> 'sp_questions',
			'post_status' 		=> 'publish',
			'posts_per_page' 	=> $posts_per_page,
			'paged' 			=> $page_no,
		);
		
		$meta_query_args	= array();
		$meta_query_args[] = array(
			'key' 		=> 'question_to',
			'value' 	=> $parent_id,
			'compare' 	=> '=',
		);

		$query_relation = array('relation' => 'AND',);
		$meta_query_args = array_merge($query_relation, $meta_query_args);
		$q_args['meta_query'] = $meta_query_args;
		
		$q_query = new WP_Query( $q_args );
		
		if ( $q_query->have_posts() ) :
			while ($q_query->have_posts()) : $q_query->the_post();
				global $post;
				$question_by = get_post_meta($post->ID, 'question_by', true);
				$question_to = get_post_meta($post->ID, 'question_to', true);
				$category 	 = get_post_meta($post->ID, 'question_cat', true);
	
				$category_icon = '';
				if (function_exists('fw_get_db_post_option') && !empty( $category )) {
					$category_icon = fw_get_db_post_option($category, 'dir_icon', true);
				}
				?>
				<div class="tg-question">
					<div class="tg-questioncontent">
						<div class="tg-answerholder spq-v2">
							<?php if (!empty($category_icon)) {?>
								<figure class="tg-docimg"><span class="<?php echo esc_attr($category_icon); ?> tg-categoryicon"></span></figure>
							<?php }?>

							<h4><a href="<?php echo esc_url(get_permalink()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h4>
							<div class="tg-description">
								<p><?php docdirect_prepare_excerpt('255','false');?></p>
							</div>
							<div class="tg-questionbottom">
								<a class="tg-btn" href="<?php echo esc_url(get_permalink()); ?>">  <?php esc_html_e('Add/View Answers', 'docdirect'); ?> </a>
								<?php fw_ext_get_total_votes_and_answers_html($post->ID);?>
							</div>
						</div>
					</div>
					<div class="tg-matadatahelpfull">
						<?php fw_ext_get_views_and_time_html($post->ID);?>
						<?php fw_ext_get_votes_html($post->ID,esc_html__('Is this helpful?', 'docdirect'));?>
					</div>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();
		endif;
        wp_die();

    }

    add_action('wp_ajax_fw_ext_docdirect_laodmore_questions', 'fw_ext_docdirect_laodmore_questions');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_laodmore_questions', 'fw_ext_docdirect_laodmore_questions');
}

/**
 * @load more questions
 */
if (!function_exists('fw_ext_docdirect_laodmore_answers')) {

    function fw_ext_docdirect_laodmore_answers() {
        global $current_user, $wp_roles, $userdata;
        $json = array();
		$page_no	= !empty( $_POST['page'] ) ? esc_attr( $_POST['page'] ) : '';
		$ques_id	= !empty( $_POST['q_id'] ) ? esc_attr( $_POST['q_id'] ) : '';
		
		$posts_per_page	= get_option('posts_per_page');
		
		$a_args = array(
			'post_type'   => 'sp_answers',
			'post_status' => 'publish',
			'post_parent' 		=> $ques_id,
			'posts_per_page' 	=> $posts_per_page,
			'order' 			=> 'DESC',
			'paged' 			=> $page_no,
		);

		$a_query = new WP_Query( $a_args );
		
		if ( $a_query->have_posts() ) :
			while ($a_query->have_posts()) : $a_query->the_post();
				global $post;
				$answer_user_id = get_post_meta($post->ID, 'answer_user_id', true);				   
				$user_avatar = apply_filters(
							'docdirect_get_user_avatar_filter',
							 docdirect_get_user_avatar(array('width'=>150,'height'=>150), $answer_user_id),
							 array('width'=>150,'height'=>150) //size width,height
						);

				$user_name  = docdirect_get_username($answer_user_id);
				$author_url	= get_author_posts_url($answer_user_id);
				?>
				<div class="tg-answerholder">
					<figure class="tg-docimg">
						<?php if (apply_filters('docdirect_do_check_user_type', $answer_user_id) === true){?>
							<a target="_blank" href="<?php echo esc_url($author_url); ?>"><img src="<?php echo esc_attr( $user_avatar );?>" alt="<?php echo esc_attr( $user_name );?>"></a>
						<?php } else{?>
							<img src="<?php echo esc_attr( $user_avatar );?>" alt="<?php echo esc_attr( $user_name );?>">
						<?php }?>
					</figure>
					<div class="tg-question">
						<div class="tg-questioncontent">
							<?php if (apply_filters('docdirect_do_check_user_type', $answer_user_id) === true){?>
								<h4><a target="_blank" href="<?php echo esc_url($author_url); ?>"><?php echo esc_attr( $user_name );?></a></h4>
							<?php } else{?>
								<h4><?php echo esc_attr( $user_name );?></h4>
							<?php }?>
							<div class="tg-description">
								<?php the_content(); ?>
							</div>
							<div class="tg-questionbottom">
								<?php if (apply_filters('docdirect_do_check_user_type', $answer_user_id) === true){?>
									<?php if( intval( $answer_user_id ) !== intval( $current_user->ID ) ){?>
										<a target="_blank" class="tg-btn" href="<?php echo esc_url($author_url); ?>"><?php esc_html_e('Consult Now', 'docdirect'); ?> </a>
									<?php }?>
								<?php }?>
								<?php fw_ext_get_total_votes_and_answers_html($post->ID,'yes');?>
							</div>
						</div>
						<div class="tg-matadatahelpfull">
							<?php fw_ext_get_views_and_time_html($post->ID,'yes');?>
							<?php fw_ext_get_votes_html($post->ID,esc_html__('Was this answers helpful?', 'docdirect'));?>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			wp_reset_postdata();
		endif;
        wp_die();

    }

    add_action('wp_ajax_fw_ext_docdirect_laodmore_answers', 'fw_ext_docdirect_laodmore_answers');
    add_action('wp_ajax_nopriv_fw_ext_docdirect_laodmore_answers', 'fw_ext_docdirect_laodmore_answers');
}