<?php
/**
 *
 * Service Providers display author articles..
 *
 * @package   Service Providers
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
global $current_user, $wp_query;
//Get User Queried Object Data
$queried_object = $wp_query->get_queried_object();

if( is_single() ){
	$exclude_post 	= $queried_object->ID;
	$post_author_id = $queried_object->post_author;
} else{
	$exclude_post	= array();
	$post_author_id = $queried_object->ID;
}

$args = array(
    'post_type' => 'sp_articles',
    'post_status' => 'publish',
    'posts_per_page' => 3,
    'author' => $post_author_id,
    'order' => 'DESC',
	'post__not_in' => array(intval($exclude_post))
);

$username = docdirect_get_username($post_author_id);
$query = new WP_Query($args);


if ($query->have_posts()) {
  if ( post_type_exists( 'sp_articles' ) ) {
    ?>
    <div class="tg-widgetrelatedposts sp-provider-articles">
        <div class="tg-widgettitle">
            <h3><?php esc_html_e('Articles', 'docdirect'); ?>&nbsp;<span class="written-by-sp"><?php esc_html_e('Written by', 'docdirect'); ?>&nbsp;<?php echo esc_attr( $username );?></span></h3>
        </div>
        <div class="tg-widgetcontent">
            <ul>
                <?php
                while ($query->have_posts()) : $query->the_post();
                    global $post;
                    $height = 150;
                    $width = 150;
                    $user_ID = get_the_author_meta('ID');
                    $user_url = get_author_posts_url($user_ID);
                    $thumbnail = docdirect_prepare_thumbnail($post->ID, $width, $height);
                    ?>
                    <li>
                        <div class="tg-serviceprovidercontent">
                            <?php if (!empty($thumbnail)) { ?>
                                <div class="tg-companylogo">
                                    <a href="<?php echo esc_url(get_permalink()); ?>">
                                        <img src="<?php echo esc_url($thumbnail); ?>" alt="<?php esc_html_e('Related', 'docdirect'); ?>">
                                    </a>
                                </div>
                            <?php } ?>
                            <div class="tg-companycontent">
                                <div class="tg-title">
                                    <h3><a href="<?php echo esc_url(get_permalink()); ?>"> <?php echo esc_attr(get_the_title()); ?> </a></h3>
                                </div>
                                <ul class="tg-matadata">
                                    <li><a href="<?php echo esc_url(get_permalink()); ?>">  <?php esc_html_e('Read More', 'docdirect'); ?> </a> </li>
                                </ul>
                            </div>
                        </div>
                    </li>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </ul>
        </div>
    </div>
<?php }else{
		DoctorDirectory_NotificationsHelper::informations(esc_html__('Sorry, This feature is compatible with latest version of DocDirect Core Plugin( Since release 3.5 ). Please contact to your site administrator for this issue.','docdirect'));			
	} 
 }