<?php
if (!defined('FW'))
    die('Forbidden');
/**
 * @var $atts
 */
$uniq_flag = fw_unique_increment();
?>
<div class="sc-services">
	<?php if ( !empty($atts['heading']) || !empty($atts['description'])) { ?>
		<div class="col-sm-10 col-sm-offset-1 col-xs-12">
			<div class="tg-theme-heading">
				<?php if (isset($atts['heading']) && !empty($atts['heading'])) { ?>
					<h2><?php echo esc_attr($atts['heading']); ?></h2>
				<?php } ?>
				<?php if (isset($atts['description']) && !empty($atts['description'])) { ?>
					<span class="tg-roundbox"></span>
					<div class="tg-description">
						<p><?php echo esc_attr($atts['description']); ?></p>
					</div>
				<?php } ?>
			</div>
		</div>
	<?php } ?>	
	<div class="tg-search-categories">
        <div class="row">
        <?php
        if (isset($atts['services_popup']) && !empty( $atts['services_popup'] )) {
            foreach ($atts['services_popup'] as $services) {
                    $responsive_classes	= !empty( $services['responsive_classes'] ) ? $services['responsive_classes'] : 'col-sm-6';
                    $media	= '';
					$title	= !empty( $services['title'] ) ? $services['title'] : '';
                    if ( isset( $services['media_type']['gadget'] ) && $services['media_type']['gadget'] === 'image' && !empty( $services['media_type']['image']['image']['url'] ) ) {
                        $media	= '<img src="'.esc_url( $services['media_type']['image']['image']['url'] ).'" alt="'.$title.'">';
                    } else if ( isset( $services['media_type']['gadget'] ) && $services['media_type']['gadget'] === 'icon' && !empty( $services['media_type']['icon']['icon'] ) ) {
                        $media	= '<i class="'.$services['media_type']['icon']['icon'].'">';
                    }
                ?>
                <div class="<?php echo sanitize_html_class( $responsive_classes );?> col-xs-12 tg-expectwidth">
                    <div class="tg-search-category">
                        <div class="tg-displaytable">
                            
                                <div class="tg-displaytablecell">
                                    <div class="tg-box">
                                        <?php if ( isset( $services['title'] ) && !empty($services['title']) ) { ?>
                                            <a href="<?php echo esc_url($services['link']); ?>"><h3><?php echo esc_attr($services['title']); ?></h3></a>
                                        <?php } ?>
                                        <?php echo force_balance_tags( $media );?>
                                        <?php 
                                            if (isset($services['lists']) && !empty($services['lists'])) { 
                                                foreach( $services['lists'] as $key => $value ){
                                                    echo '<span class="service-list">'.$value.'</span>';
                                                }
                                            }
                                            
                                        ?>
                                        
                                        <?php if (isset($services['link']) && !empty($services['link'])) { ?>
                                            <a href="<?php echo esc_url($services['link']); ?>"><span class="tg-show"><em class="icon-add"></em></span></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            
                        </div>
                    </div>
                </div>
            <?php } }?>
        </div>
     </div>
</div>

