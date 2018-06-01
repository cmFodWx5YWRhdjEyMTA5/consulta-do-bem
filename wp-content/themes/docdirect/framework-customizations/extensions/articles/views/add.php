<?php
/**
 *
 * The template part to add new article.
 *
 * @package   Service Providers
 * @author    Themographics
 * @link      http://themographics.com/
 */
global $current_user;
$user_identity = $current_user->ID;
$content = esc_html__('Add your article content here.', 'docdirect');
$settings = array('media_buttons' => true);
$thumbnail	= get_template_directory_uri().'/images/user80x80.jpg';

$article_limit = 0;
if (function_exists('fw_get_db_settings_option')) {
	$article_limit = fw_get_db_settings_option('article_limit');
}

$db_categories	= '';
$article_limit = !empty( $article_limit ) ? $article_limit  : 0;

if( apply_filters('docdirect_get_packages_setting','default') === 'custom' ){
	$package_expiry     = get_user_meta( $user_identity, 'user_current_package_expiry', true);
	$current_package	= get_user_meta($user_identity, 'user_current_package', true);
	$current_date		= date('Y-m-d H:i:s');
	
	$type	= get_post_type($current_package);
	if( !empty( $type ) && $type === 'product' ){
		$remaining_articles = get_post_meta( $current_package, 'dd_articles', true );
		$remaining_articles = intval( $remaining_articles ) + intval( $article_limit ); //total in package and one free
	} else{
		$remaining_articles = fw_get_db_post_option($current_package, 'articles', true);
		$remaining_articles = intval( $remaining_articles ) + intval( $article_limit ); //total in package and one free
	}
	
} else{
	$remaining_articles = $article_limit; //total in package and one free
}

$args = array('posts_per_page' => '-1',
    'post_type' => 'sp_articles',
    'orderby' => 'ID',
    'post_status' => array('publish','pending'),
    'author' => $user_identity,
    'suppress_filters' => false
);
$query = new WP_Query($args);
$posted_articles = $query->post_count;

?>
<div id="tg-content" class="tg-content">
    <div class="tg-dashboardbox tg-businesshours">
        <div class="tg-dashboardtitle">
            <h2><?php esc_html_e('Post an article', 'docdirect'); ?></h2>
        </div>
        <?php if (isset($remaining_articles) && $remaining_articles > $posted_articles) { ?>
        <div class="tg-servicesmodal tg-categoryModal">
             <form class="tg-themeform tg-formamanagejobs tg-addarticle sp-dashboard-profile-form">
				<fieldset>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<input type="text" name="article_title" class="form-control" placeholder="<?php esc_html_e('Article Title', 'docdirect'); ?>">
							</div>
						</div>
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="form-group">
								<?php wp_editor($content, 'article_detail', $settings); ?>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<h2><?php esc_html_e('Tags', 'docdirect'); ?></h2>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="tg-addallowances">
								<div class="tg-addallowance">
									<div class="form-group">
										<input type="text" name="article_tags" class="form-control input-feature" placeholder="<?php esc_html_e('Article Tags', 'docdirect'); ?>">
										<a class="tg-btn add-article-tags" href="javascript:;"><?php esc_html_e('Add Now', 'docdirect'); ?></a>
									</div>
									<ul class="tg-tagdashboardlist sp-feature-wrap">

									</ul>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<h2><?php esc_html_e('Categories', 'docdirect'); ?></h2>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="tg-addallowances">
								<div class="tg-addallowance">
									<div class="form-group">
										<select name="categories[]" class="chosen-select" multiple>
											<option value=""><?php esc_attr_e('Select categories','docdirect');?></option>
											<?php docdirect_get_article_categories('','article_categories');?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
				</fieldset>
				<fieldset>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
							<div class="tg-upload">
								<div class="tg-uploadhead">
									<span>
										<h3><?php esc_html_e('Upload Featured Image', 'docdirect'); ?></h3>
										<i class="fa fa-exclamation-circle"></i>
									</span>
									<i class="fa fa-upload"></i>
								</div>
								<div class="tg-box">
									<label class="tg-fileuploadlabel" for="tg-featuredimage">
										<a href="javascript:;" id="upload-featured-image" class="tg-fileinput sp-upload-container">
											<i class="fa fa-cloud-upload"></i>
											<span><?php esc_html_e('Click button to upload article feature image.', 'docdirect'); ?></span>
										</a>
										<div id="plupload-container"></div> 
									</label>
									<div class="tg-gallery">
										<div class="tg-galleryimg tg-galleryimg-item">
											<figure>
												<img src="<?php echo esc_url( $thumbnail );?>" class="attachment_src" />
												<input type="hidden" class="attachment_id" name="attachment_id" value="">
												<a href="javascript:;" data-placeholder="<?php echo esc_url( $thumbnail );?>" class="tg-deleteimg del-featured-image"><i class="fa fa-trash-o"></i></a>
											</figure>
										</div>
									</div>
								</div>
							</div>
						</div>

					</div>
				</fieldset>
				<fieldset>
					<div id="tg-updateall" class="tg-updateall">
						<div class="tg-holder">
							<span class="tg-note"><?php esc_html_e('Click to', 'docdirect'); ?> <strong> <?php esc_html_e('Submit Article Button', 'docdirect'); ?> </strong> <?php esc_html_e('to add the article.', 'docdirect'); ?></span>
							<?php wp_nonce_field('docdirect_article_nounce', 'docdirect_article_nounce'); ?>
							<a class="tg-btn process-article" data-type="add" href="javascript:;"><?php esc_html_e('Submit Article', 'docdirect'); ?></a>
						</div>
					</div>
				</fieldset>
			</form>
        </div>
        <?php } else {?>
            <div class="tg-dashboardappointmentbox">
                <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('Oops! You reached to maximum limit of articles post. Please upgrade your package to add more articles.', 'docdirect')); ?>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/template" id="tmpl-load-featured-thumb">
    <div class="tg-galleryimg tg-galleryimg-item">
    <figure>
    <img src="{{data.thumbnail}}">
    <input type="hidden" name="attachment_id" value="{{data.id}}">
    </figure>
    </div>
</script>
<script type="text/template" id="tmpl-load-article-tags">
    <li>
    <span class="tg-tagdashboard">
    <i class="fa fa-close delete_article_tags"></i>
    <em>{{data}}</em>
    </span>
    <input type="hidden" name="article_tags[]" value="{{data}}">
    </li>
</script>