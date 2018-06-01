<?php
/**
 *
 * The template part for displaying the dashboard articles.
 *
 * @package   Service Providers
 * @author    Themographics
 * @link      http://themographics.com/
 * @since 1.0
 */
global $current_user,
 $wp_roles,
 $userdata,
 $paged;

$user_identity = $current_user->ID;
$url_identity = $user_identity;
if (!empty($_GET['identity'])) {
    $url_identity = $_GET['identity'];
}

$dir_profile_page = '';
if (function_exists('fw_get_db_settings_option')) {
    $dir_profile_page = fw_get_db_settings_option('dir_profile_page', $default_value = null);
}

$get_username = docdirect_get_username($url_identity);
$profile_page = isset($dir_profile_page[0]) ? $dir_profile_page[0] : '';
$show_posts = get_option('posts_per_page') ? get_option('posts_per_page') : '2';

$pg_page = get_query_var('page') ? get_query_var('page') : 1; //rewrite the global var
$pg_paged = get_query_var('paged') ? get_query_var('paged') : 1; //rewrite the global var
//paged works on single pages, page - works on homepage
$paged = max($pg_page, $pg_paged);

$order = 'DESC';
if (!empty($_GET['order'])) {
    $order = esc_attr($_GET['order']);
}

$sorting = 'ID';
if (!empty($_GET['sort'])) {
    $sorting = esc_attr($_GET['sort']);
}

$args = array('posts_per_page' => '-1',
    'post_type' => 'sp_articles',
    'orderby' => 'ID',
    'post_status' => 'publish',
    'author' => $url_identity,
    'suppress_filters' => false
);
$query = new WP_Query($args);
$count_post = $query->post_count;

$args = array('posts_per_page' => $show_posts,
    'post_type' => 'sp_articles',
    'orderby' => $sorting,
    'order' => $order,
    'post_status' => array( 'publish','pending' ),
    'author' => $url_identity,
    'paged' => $paged,
    'suppress_filters' => false
);

$query = new WP_Query($args);
?>
<div id="tg-content" class="tg-content">
    <div class="tg-joblisting tg-dashboardmanagejobs">
        <div class="tg-dashboardhead">
            <div class="tg-dashboardtitle">
                <h2><?php esc_html_e('Manage Articles', 'docdirect'); ?></h2>
            </div>
            <div class="tg-btnaddservices">
                <a href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $url_identity, '', 'add'); ?>"><?php esc_html_e('Add New Article', 'docdirect'); ?></a>
            </div>
        </div>
        <?php if ($query->have_posts()) { ?>
            <div class="tg-sortfilters">
                <form class="form-sort-articles" method="get" action="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $url_identity, '', 'listing'); ?>">
                    <div class="tg-sortfilter tg-sortby">
                        <div class="tg-select">
                            <select name="sort" class="sort_by">
                                <option value="ID" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'ID' ? 'selected' : ''; ?>><?php esc_html_e('Latest articles at top', 'docdirect'); ?></option>
                                <option value="title" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'title' ? 'selected' : ''; ?>><?php esc_html_e('Order by title', 'docdirect'); ?></option>
                                <option value="name" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'name' ? 'selected' : ''; ?>><?php esc_html_e('Order by article name', 'docdirect'); ?></option>
                                <option value="date" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'date' ? 'selected' : ''; ?>><?php esc_html_e('Order by date', 'docdirect'); ?></option>
                                <option value="rand" <?php echo isset($_GET['sort']) && $_GET['sort'] == 'rand' ? 'selected' : ''; ?>><?php esc_html_e('Random order', 'docdirect'); ?></option>
                            </select>
                        </div>
                    </div>
                    <div class="tg-sortfilter tg-arrange">
                        <div class="tg-select">
                            <select name="order" class="order_by">
                                <option value="DESC" <?php echo isset($_GET['order']) && $_GET['order'] == 'DESC' ? 'selected' : ''; ?>><?php esc_html_e('DESC', 'docdirect'); ?></option>
                                <option value="ASC" <?php echo isset($_GET['order']) && $_GET['order'] == 'ASC' ? 'selected' : ''; ?>><?php esc_html_e('ASC', 'docdirect'); ?></option>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" class="" value="articles" name="ref">
                    <input type="hidden" class="" value="listing" name="mode">
                    <input type="hidden" class="" value="<?php echo intval($url_identity); ?>" name="identity">
                </form>
            </div>
            <table class="tg-tablejoblidting job-listing-wrap fw-ext-article-listing">
                <tbody>
                    <?php
                    $today = time();
                    while ($query->have_posts()) : $query->the_post();
                        global $post;
							$status	= get_post_status($post->ID);	
							
                        ?>
                        <tr>
                            <td>
                                <figure class="tg-companylogo">
                                    <a class="tg-btnedite" href="<?php DocDirect_Scripts::docdirect_profile_menu_link($profile_page, 'articles', $url_identity, '', 'edit', $post->ID); ?>"><i class="fa fa-pencil"></i></a>
                                    <a class="tg-btnedite btn-article-del" data-key="<?php echo intval($post->ID); ?>"><i class="fa fa-trash"></i></a>
                                </figure>
                                <div class="tg-contentbox">
                                    <?php if( isset( $status ) && $status === 'publish' ){?>
                                    	<div class="at-status at-publish" title="<?php esc_html_e('Published', 'docdirect'); ?>"><i class="fa fa-check"></i></div> 
                                    <?php } elseif( isset( $status ) && $status === 'pending' ){?>
                                    	<div class="at-status at-pending" title="<?php esc_html_e('Pending', 'docdirect'); ?>"><i class="fa fa-exclamation-triangle"></i></div> 
                                    <?php }?>
                                    
                                    <div class="tg-title">
                                        <h3><a href="<?php echo esc_url(get_the_permalink()); ?>"><?php the_title(); ?></a></h3>
                                    </div>
                                    <span><?php esc_html_e('By', 'docdirect'); ?>:&nbsp;<?php echo esc_attr($get_username); ?></span> 
                                </div>
                            </td>
                        </tr>
                        <?php
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </tbody>
            </table>
            <?php
            if (!empty($count_post) && $count_post > $show_posts) {
                docdirect_prepare_pagination($count_post, $show_posts);
            }
            ?>
        <?php } else { ?>
            <div class="tg-dashboardappointmentbox">
                <?php DoctorDirectory_NotificationsHelper::informations(esc_html__('No articles found.', 'docdirect')); ?>
            </div>
        <?php } ?>
    </div>
</div>