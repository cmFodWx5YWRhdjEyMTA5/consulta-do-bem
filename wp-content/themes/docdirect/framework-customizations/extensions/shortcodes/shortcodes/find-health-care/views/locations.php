<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();

$locations	= docdirect_prepare_taxonomies('directory_type','locations',0,'array',$atts['show_posts']);
$dir_search_page = fw_get_db_settings_option('dir_search_page');
if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}
?>
<div class="sc-dir-search">
    <div class="tg-findbycategory">
        <div class="tg-box">
            <?php if( isset( $atts['title'] ) && !empty( $atts['title'] ) ){?>
                <div class="tg-heading-border tg-small">
                    <?php if( isset( $atts['icon'] ) ){?>
                        <i class="<?php echo esc_attr( $atts['icon'] );?>"></i>
                    <?php }?>
                    <h3><?php echo esc_attr( $atts['title'] );?></h3>
                </div>
            <?php }?>
            <ul>
            	<?php 
					if( isset( $locations ) && !empty( $locations ) ){
						foreach( $locations as $key => $location ){?>
            		<li><a href="<?php echo esc_url( $search_page );?>?location=<?php echo esc_attr( $location->slug );?>"><?php echo esc_attr( $location->name );?></a></li>
                <?php }}?>
            </ul>
        </div>
    </div>
</div>
