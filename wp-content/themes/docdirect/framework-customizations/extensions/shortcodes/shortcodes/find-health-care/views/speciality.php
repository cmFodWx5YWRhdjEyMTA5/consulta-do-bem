<?php
if (!defined('FW')) {
    die('Forbidden');
}
/**
 * @var $atts
 */
$uni_flag = fw_unique_increment();

$specialities	= docdirect_prepare_taxonomies('directory_type','specialities',0,'array',$atts['show_posts']);
$dir_search_page = fw_get_db_settings_option('dir_search_page');
if( isset( $dir_search_page[0] ) && !empty( $dir_search_page[0] ) ) {
	$search_page 	 = get_permalink((int)$dir_search_page[0]);
} else{
	$search_page 	 = '';
}
?>
<div class="sc-dir-search sc-specialities">
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
					if( isset( $specialities ) && !empty( $specialities ) ){
						foreach( $specialities as $key => $specialitie ){
							$permalink = add_query_arg( 
								array(
									'speciality[]'=>   urlencode( $specialitie->slug )  ), 
									esc_url( $search_page ) 
							);
							?>
            		<li><a href="<?php echo esc_url( $permalink );?>"><?php echo esc_attr( $specialitie->name );?></a></li>
                <?php }}?>
            </ul>
        </div>
    </div>
</div>
