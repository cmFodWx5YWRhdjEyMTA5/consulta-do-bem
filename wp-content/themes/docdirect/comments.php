<?php
/**
 * The template for displaying comments.
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 * @package Doctor Directory
 */
/*
 * If the current post is protected by a password and
 * the visitor has not yet entered the password we will
 * return early without loading the comments.
 */
if (post_password_required()) {
    return;
}

/* ----------------------------------------------------------------------- */
/* 	Display Comments
  /*----------------------------------------------------------------------- */

    if (!function_exists('docdirect_comments')) {

        function docdirect_comments($comment, $args, $depth) {
            $GLOBALS['comment'] = $comment;
            $args['reply_text'] = esc_html__('Reply','docdirect');
			
            ?>
            <li <?php echo str_replace('comment', '', comment_class('comment-entry clearfix', '', '', false)); ?> id="comment-<?php comment_ID(); ?>">
				<div class="comment tg-border">
					<figure class="tg-author-img">
						<?php  echo get_avatar($comment, 80);?>
					</figure>
					<div class="comment-box">
						<div class="comment-head">
							<div class="pull-left">
								<h3><?php echo get_comment_author_link(); ?></h3>
							</div>
							<?php comment_reply_link(array_merge($args, array('depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
						</div>
						<span><?php esc_html_e('Posted on ','docdirect');?><?php comment_date('M d, Y'); ?><?php esc_html_e(' at ','docdirect');?> <?php comment_time( 'H:i a' ); ?></span>
						<div class="tg-description">
							<?php if ( $comment->comment_approved == '0' ) : ?>
                                <em><?php esc_html_e( 'Your comment is awaiting moderation.', 'docdirect' ); ?></em>
                                <br />
                            <?php endif; ?>
                            <?php comment_text() ?>
						</div>
					</div>
				</div>
            <?php
        }

    }

    //comments reply and edit
		
if (have_comments()) : ?>
	<div class="tg-haslayout" id="comments">
		<div class="tg-heading-border tg-small">
			<h3><?php comments_number( esc_html__('0 Comments','docdirect'), esc_html__('1 Comment','docdirect'), esc_html__('% Comments','docdirect') ); ?></h3>
		</div>
		<ul><?php wp_list_comments(array('callback' => 'docdirect_comments')); ?></ul>
		<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : ?>
			<?php previous_comments_link(esc_html__('&larr; Older Comments', 'docdirect')); ?>
			<?php next_comments_link(esc_html__('Newer Comments &rarr;', 'docdirect')); ?>
		<?php endif; ?>
	</div>
<?php endif;?>

<div class="tg-comment-formarea tg-haslayout">
	<div class="row">
		<?php
		global $aria_req, $user_identity, $commenter;
		ob_start();
		comment_form(array(
			'fields' => apply_filters(
				'comment_form_default_fields', array(
					'first_name' => '
						<div class="col-md-6 col-sm-6 col-xs-12"><div class="form-group">' . '<input class="form-control" id="author" placeholder="'.esc_attr__('Name','docdirect').'" name="author" type="text" value="' .
					esc_attr($commenter['comment_author']) . '" size="30"' . $aria_req . ' />' .
					'</div></div>'
					,
					'email' => '<div class="col-md-6 col-sm-6 col-xs-12"><div class="form-group">' . '<input class="form-control" id="email" placeholder="'.esc_attr__('Email','docdirect').'" name="email" type="text" value="' . esc_attr($commenter['comment_author_email']) .
					'" size="30"' . $aria_req . ' />' .
					'</div></div>',
					)
			),
			'comment_field' => '<div class="col-xs-12"><div class="form-group">' .
			'<textarea class="form-control" name="comment" placeholder="'.esc_attr__('Message','docdirect').'" rows="4"></textarea>' .
			'</div></div>',
			'logged_in_as' => '<div class="col-xs-12"><div class="form-group">' . sprintf( __('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>','docdirect'), admin_url('profile.php'), $user_identity, wp_logout_url(apply_filters('the_permalink', get_permalink()))) . '</div></div>',
			'notes' => '',
			'comment_notes_before' => '',
			'comment_notes_after' => '',
			'id_form' => 'reply-form',
			'id_submit' => 'tg-btn',
			'class_form' => 'form-comment tg-haslayout',
			'class_submit' => 'tg-btn',
			'name_submit' => 'submit',
			'title_reply' => esc_html__('', 'docdirect'),
			'title_reply_before' => '<div class="col-xs-12"><div class="tg-heading-border tg-small"><h3>'.esc_html__('Add Comments', 'docdirect'),
			'title_reply_after' => '</h3></div></div>',
			'cancel_reply_before' => '',
			'cancel_reply_after' => '',
			'cancel_reply_link' => esc_html__('Cancel reply', 'docdirect'),
			'label_submit' => esc_html__('Submit', 'docdirect'),
			'submit_button' => '<input name="%1$s" type="submit" id="%2$s" class="tg-btn" value="%4$s" />',
			'submit_field' => '<div class="col-xs-12">%1$s %2$s</div>',
			'format' => 'xhtml',
		));
	
		echo ob_get_clean();
		?>
	</div>
</div>
