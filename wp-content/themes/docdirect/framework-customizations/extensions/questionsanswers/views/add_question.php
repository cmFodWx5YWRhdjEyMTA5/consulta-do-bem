<?php
/**
 *
 * The template part to display the add question form.
 *
 * @package   Docdirect
 * @author    Themographics
 * @link      http://themographics.com/
 */

global $current_user, $wp_query;
//Get User Queried Object Data
$queried_object = $wp_query->get_queried_object();
$author_id = $queried_object->ID;

//Authentication page
$login_register = '';
$login_reg_link = '#';
if (function_exists('fw_get_db_settings_option')) {
	$login_register = fw_get_db_settings_option('enable_login_register');
}

if (!empty($login_register['enable']['login_reg_page'])) {
	$login_reg_link = $login_register['enable']['login_reg_page'];
}
?>
<div class="tg-askquestion">
	<span class="tg-questionicon">
		<img src="<?php echo esc_url( fw_get_template_customizations_directory_uri().'/extensions/questionsanswers/static/img/thumbnails/start.png' );?>" alt="<?php esc_html_e('Consult Q&A', 'docdirect'); ?>">
	</span>
	<div class="tg-getanswer">
		<p><?php esc_html_e('Get answers to your queries now', 'docdirect'); ?></p>
	</div>
	<?php if( $current_user->ID != $author_id ){?>
		<a class="tg-btn" href="javascript:;" data-toggle="collapse" data-target="#tg-add-questions"><?php esc_html_e('Ask Question', 'docdirect'); ?></a>
	<?php }?>
	<div id="tg-add-questions" class="collapse tg-add-questions">
		<?php if( is_user_logged_in() ) {?>
			<?php if( $current_user->ID != $author_id ){?>
				<form class="form fw_ext_questions_form tg-formtheme tg-formaddquestion">
					<fieldset>
						<div class="form-group">
							<label for=""></label>
							<input type="text" name="question_title" placeholder="<?php esc_html_e('Question Title', 'docdirect'); ?>">
						</div>
						<div class="form-group">
							<?php
							$content = '';
							$settings = array(
								'editor_class' => 'question_description',
								'teeny' => true,
								'media_buttons' => false,
								'textarea_rows' => 10,
								'wpautop' => true,
								'quicktags' => true,
								'editor_height' => 300,
							);

							wp_editor($content, 'question_description', $settings);
							?>
						</div>
						<div class="tg-btns">
							<?php wp_nonce_field('docdirect_question_answers_nounce', 'docdirect_question_answers_nounce'); ?>
							<input type="hidden" name="author_id" value="<?php echo base64_encode($author_id); ?>">
							<button type="button" class="tg-btn tg-btnaddanswer fw_ext_question_save_btn" data-type="closed"><?php esc_html_e('Submit Question', 'docdirect'); ?></button>
						</div>
					</fieldset>
				</form>
				<?php }?>
		<?php } else{?>
		<div class="login-to-add tg-haslayout">
				<a href="javascript:;" class="tg-btn" data-toggle="modal" data-target=".tg-user-modal"><?php esc_html_e('Login to add your answer','docdirect');?></a>
			</div>
		<?php }?>
	</div>
</div>


