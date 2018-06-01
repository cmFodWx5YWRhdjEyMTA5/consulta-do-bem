<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
global $wp_query, $current_user;
$query_object = $wp_query->get_queried_object();

$ques_id = $query_object->ID;
$posts_per_page	= get_option('posts_per_page');

$q_args = array(
    'post_type'   => 'sp_answers',
    'post_status' => 'publish',
	'post_parent' => $ques_id,
    'posts_per_page' 	=> $posts_per_page,
    'order' => 'DESC',
);

$t_args = array(
    'post_type'   => 'sp_answers',
    'post_status' => 'publish',
	'post_parent' => $ques_id,
    'posts_per_page' 	=> -1,
);
$t_query = new WP_Query($t_args);
$total_posts	= $t_query->post_count;


$q_query = new WP_Query($q_args);

?>
<div class="tg-companyfeaturebox tg-answers" data-q_id="<?php echo intval( $ques_id );?>">
	<div class="tg-companyfeaturetitle">
		<h3><?php esc_html_e('Answers', 'docdirect'); ?></h3>
	</div>
	<?php if ($q_query->have_posts()) {?>
		<div class="tg-widgetrelatedposts sp-provider-articles">
			<div class="questions-area tg-haslayout">
			   <?php
				while ($q_query->have_posts()) : $q_query->the_post();
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
			   ?>
			</div>
		</div>
		<?php if( $total_posts > $posts_per_page ){?>
			<div class="tg-haslayout loadmore-wrap">
				<a class="loadmore_a tg-btn"><?php esc_html_e('Load more..', 'docdirect'); ?></a>
			</div>
        <?php }?>
	<?php } else{?>
		<p><?php esc_html_e('No answered yet.', 'docdirect'); ?></p>
	<?php }?>
</div>

