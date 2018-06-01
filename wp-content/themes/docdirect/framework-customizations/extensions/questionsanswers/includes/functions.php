<?php

if (!defined('FW')) {
    die('Forbidden');
}
/**
 * Question & Answers Extension 
 * General Helpers and Functions...
 */


/**
 * Return the total answers against question
 * @return string
 */
if (!function_exists('fw_ext_get_total_question_answers')) {
    function fw_ext_get_total_question_answers($post_id='') {
        $tqa_args = array('posts_per_page' => '-1',
			'post_type' => 'sp_answers',
			'orderby'   => 'ID',
			'post_status' => 'publish'
		);
		
		if( !empty( $post_id ) ){
			$meta_query_args	= array();
			$meta_query_args[]  = array(
				'key' 		=> 'answer_question_id',
				'value' 	=> $post_id,
				'compare' 	=> '=',
			);
			
			$query_relation = array('relation' => 'AND',);
			$meta_query_args = array_merge($query_relation, $meta_query_args);
			$tqa_args['meta_query'] = $meta_query_args;
		}
		
		$tqa_query = new WP_Query($tqa_args);
		$ans = $tqa_query->post_count; 
		
		return $ans;
    }
}

/**
 * Return the total questions
 * @return string
 */
if (!function_exists('fw_ext_get_total_questions')) {
    function fw_ext_get_total_questions($post_id='') {
        $tqa_args = array('posts_per_page' => '-1',
			'post_type' => 'sp_questions',
			'orderby'   => 'ID',
			'post_status' => 'publish'
		);
		
		
		$tqa_query = new WP_Query($tqa_args);
		$ans = $tqa_query->post_count; 
		
		return $ans;
    }
}


/**
 * Return votes html
 * @return string
 */
if (!function_exists('fw_ext_get_votes_html')) {
    function fw_ext_get_votes_html($post_id, $title='Is this helpful?' ) {
		global $current_user;
		$vote_users = array();
        $vote_users = get_post_meta($post_id, 'vote_users', false);
		$vote_users = !empty($vote_users) && is_array($vote_users) ? $vote_users : array();
		$voteupClass	 = 'updatevote';
		
		ob_start();
		?>
  		<ul class="tg-postmatadata tg-postmatadatalikeunlike">
			<li><span><?php echo esc_attr( $title ); ?></span></li>
			<li class="tg-votelikes">
				<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="<?php echo esc_attr( $voteupClass );?>" data-key="up">
					<i class="fa fa-thumbs-o-up"></i>
				</a>
			</li>
			<?php /*?><li class="tg-unlike">
				<a href="javascript:;" data-id="<?php echo intval( $post_id );?>" class="updatevote" data-key="down">
					<i class="fa fa-thumbs-o-down"></i>
				</a>
			</li><?php */?>
		</ul>
   		<?php
		echo ob_get_clean();
    }
}


/**
 * Return view and time
 * @return string
 */
if (!function_exists('fw_ext_get_views_and_time_html')) {
    function fw_ext_get_views_and_time_html($post_id,$skip_views='no') {
		$question_views = get_post_meta($post_id, 'question_views', true);
		$pfx_date = get_the_date( 'Y-m-d', $post_id );
        ob_start();
		?>
  		<ul class="tg-postmatadata">
  			<?php if( $skip_views === 'no' ){?>
			<li>
				<a href="javascript:;">
					<i class="fa fa-eye"></i>
					<span><?php echo intval($question_views); ?>&nbsp;<?php esc_html_e('views', 'docdirect'); ?></span>
				</a>
			</li>
			<?php }?>
			<li>
				<a href="javascript:;">
					<i class="fa fa-calendar"></i>
					<span><?php echo human_time_diff(strtotime($pfx_date), current_time('timestamp')) .'&nbsp;'. esc_html__('ago', 'docdirect'); ?></span>
				</a>
			</li>
		</ul>
   		<?php
		echo ob_get_clean();
    }
}

/**
 * Return votes html
 * @return string
 */
if (!function_exists('fw_ext_get_total_votes_and_answers_html')) {
    function fw_ext_get_total_votes_and_answers_html($post_id,$skip_ans='no') {
		$total_votes = get_post_meta($post_id, 'total_votes', true);
		$question_total_ans 	= fw_ext_get_total_question_answers($post_id);
        ob_start();
		?>
  		<ul class="tg-votesanswers">
			<li>
				<a href="javascript:;">
					<span class="votes_wrap"><?php echo intval($total_votes); ?></span>
					<em><?php esc_html_e('votes', 'docdirect'); ?></em>
				</a>
			</li>
			
			<?php if( $skip_ans === 'no' ){?>
			<li>
				<a href="javascript:;">
					<span><?php echo intval($question_total_ans); ?></span>
					<em><?php esc_html_e('answers', 'docdirect'); ?></em>
				</a>
			</li>
			<?php }?>
		</ul>
   		<?php
		echo ob_get_clean();
    }
}
