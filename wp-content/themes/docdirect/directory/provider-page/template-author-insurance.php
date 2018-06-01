<?php
/**
 *
 * Author insurance Template.
 *
 * @package   Listingo
 * @author    themographics
 * @link      https://themeforest.net/user/themographics/portfolio
 * @since 1.0
 */
/**
 * Get User Queried Object Data
 */
global $current_user;
$author_profile = $wp_query->get_queried_object();

if( !empty( $author_profile->insurance ) ) {
	if( apply_filters('docdirect_is_setting_enabled',$author_profile->ID,'insurance' ) === true ){?>
	<div class="tg-innetworkinsurrance tg-tagsstyle tg-listview-v3 user-section-style">
		<div class="tg-userheading">
			<h2><?php esc_html_e('In-Network Insurance','docdirect');?></h2>
		</div>
		<div class="see-more-info">
			<p><a href="javascript:;"><?php esc_html_e('See which insurance(s) covers your care.','docdirect');?>
			<span><i class="fa fa-plus"></i></span></a></p>
		</div>
		<ul class="elm-display-none insurance-wrap">
			<?php
			foreach( $author_profile->insurance as $key => $value ){
				$insurance	    = get_term_by( 'slug', $value, 'insurance');
				if( !empty( $insurance ) ) {
					$insurance_logo = get_term_meta( $insurance->term_id, 'insurance_logo', true );
					if( !empty( $insurance->name ) ){
				?>
				<li>
					<span><?php echo esc_attr( $insurance->name );?></span>
					<?php if( !empty( $insurance_logo ) ) {?>
						<span class="insurance_logo"><img src="<?php echo esc_url( $insurance_logo );?>"></span>
					<?php }?>
				</li>
			<?php }}}?>
		</ul>
	</div>
  <?php }
}