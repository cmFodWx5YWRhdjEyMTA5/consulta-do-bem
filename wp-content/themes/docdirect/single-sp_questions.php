<?php
/**
 *
 * The template used for displaying default article post style
 *
 * @package   Docdirect
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $post, $current_user;
do_action('sp_set_question_views', $post->ID, 'question_views');
get_header();

$docdirect_sidebar = 'full';
$section_width = 'col-xs-12 col-sm-12 col-md-12 col-lg-12';
if (function_exists('fw_ext_sidebars_get_current_position')) {
    $current_position = fw_ext_sidebars_get_current_position();
    if ($current_position != 'full' && ( $current_position == 'left' || $current_position == 'right' )) {
        $docdirect_sidebar = $current_position;
        $section_width = 'col-xs-12 col-sm-8 col-md-8 col-lg-8';
    }
}

if (isset($docdirect_sidebar) && $docdirect_sidebar === 'right') {
    $aside_class = 'pull-right';
    $content_class = 'pull-left';
} else {
    $aside_class = 'pull-left';
    $content_class = 'pull-right';
}

//Authentication page
//Authentication page
$login_register = '';
$login_reg_link = '#';
if (function_exists('fw_get_db_settings_option')) {
	$login_register = fw_get_db_settings_option('enable_login_register');
}

if (!empty($login_register['enable']['login_reg_page'])) {
	$login_reg_link = $login_register['enable']['login_reg_page'];
}

//Either any user can post answer or only paid users can post answers 
$post_question_settings	= 'yes';
if( apply_filters('docdirect_get_theme_settings', 'qa_restriction') === 'paid' ){
	if( apply_filters('docdirect_is_setting_enabled',$current_user->ID,'dd_qa' ) === true ){
		$post_question_settings	= 'yes';
	}else{
		$post_question_settings	= 'no';
	}
}
							   
?>
<div class="container">
    <div class="row">
        <div id="tg-twocolumns" class="tg-twocolumns">
            <div class="<?php echo esc_attr($section_width); ?> <?php echo sanitize_html_class($content_class); ?>">
                <?php
                if (have_posts()) {
                    while (have_posts()) {
                        the_post();
						$question_by = get_post_meta($post->ID, 'question_by', true);
						$question_to = get_post_meta($post->ID, 'question_to', true);
						$category 	 = get_post_meta($post->ID, 'question_cat', true);
	
						$category_icon = '';
						if (function_exists('fw_get_db_post_option') && !empty( $category )) {
							$category_icon = fw_get_db_post_option($category, 'dir_icon', true);
						}
						
                        global $post;
                        ?>
                        <div id="tg-content" class="tg-content tg-companyfeaturebox">
                            <div class="tg-question">
								<div class="tg-questioncontent">
									<div class="tg-answerholder spq-v2">
										<?php if (!empty($category_icon)) {?>
											<figure class="tg-docimg"><span class="<?php echo esc_attr($category_icon); ?> tg-categoryicon"></span></figure>
										<?php }?>
										
										<h4><a href="<?php echo esc_url(get_permalink()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h4>
										<div class="tg-description">
											<?php the_content(); ?>
										</div>
										<div class="tg-questionbottom">
											<?php if( intval( $question_by ) !== intval( $current_user->ID ) ){?>
												<a class="tg-btn" data-toggle="collapse" data-target="#tg-add-answer" href="javascript:;">
													<i class="fa fa-plus"></i><?php esc_html_e('Add Your Answer', 'docdirect'); ?>
												</a>
											<?php }?>
											<?php fw_ext_get_total_votes_and_answers_html($post->ID);?>
										</div>
									</div>
								</div>
								<div class="tg-matadatahelpfull">
									<?php fw_ext_get_views_and_time_html($post->ID);?>
									<?php fw_ext_get_votes_html($post->ID,esc_html__('Is this helpful?', 'docdirect'));?>
								</div>
							</div>
							<div id="tg-add-answer" class="collapse tg-add-answer">
							<?php 
							if( is_user_logged_in() ) {
								if( intval( $question_by ) !== intval( $current_user->ID ) ){
									if( isset( $post_question_settings ) && $post_question_settings === 'yes' ){
									?>
									<div class="tg-companyfeaturebox tg-addyouranswer">
									<div class="tg-companyfeaturetitle">
										<h3><?php esc_html_e('Add your answer', 'docdirect'); ?></h3>
									</div>
									<form class="docdirect_answer_form tg-formtheme tg-formaddquestion">
										<fieldset>
											<div class="form-group">
												<?php
													$content = '';
													$settings = array(
														'editor_class' => 'answer_description',
														'teeny' => true,
														'media_buttons' => false,
														'textarea_rows' => 10,
														'wpautop' => true,
														'quicktags' => true,
														'editor_height' => 300,
													);

													wp_editor($content, 'answer_description', $settings);
												?>
											</div>
											<div class="tg-btns">
												<?php wp_nonce_field('docdirect_answers_nounce', 'docdirect_answers_nounce'); ?>
												<input type="hidden" name="question_id" value="<?php echo intval($post->ID); ?>">
												<button type="button" class="tg-btn tg-btnaddanswer answer_save_btn"><?php esc_html_e('Submit Answer', 'docdirect'); ?></button>
											</div>
										</fieldset>
									</form>
								</div>
									<?php }else{?>
										<div class="post-replies-wrap tg-haslayout">
											<?php DoctorDirectory_NotificationsHelper::informations(esc_html__('You are not authorized to post replies. Please buy/upgrade your package to post replies' ,'docdirect'));?>
										</div>
									<?php }?>
								<?php }
								} else{?>
									<div class="login-to-add tg-haslayout">
										<a href="javascript:;" class="tg-btn" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Login to add your answer','docdirect');?></a>
									</div>
								<?php }?>
							</div>
                            <?php
								if (function_exists('fw_get_db_settings_option') && fw_ext('questionsanswers')) {
									do_action('render_answers_view');
								}
                            ?>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <?php
            if (function_exists('fw_ext_sidebars_get_current_position')) {
                if ($current_position !== 'full') {
                    ?>
                    <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 <?php echo sanitize_html_class($aside_class); ?>">
                        <aside id="tg-sidebar" class="tg-sidebar">
                            <?php echo fw_ext_sidebars_show('blue'); ?>
                        </aside>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>
<?php
get_footer();
