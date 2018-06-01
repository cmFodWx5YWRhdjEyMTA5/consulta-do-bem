<?php 
/**
 * The template for displaying Search Form
 */
?>
<div class="tg-search">
<form class="form-search tg-search-form" method="get" action="<?php echo esc_url(home_url('/')); ?>">
	<fieldset>
		<input type="text" value="<?php echo get_search_query(); ?>" class="form-control" placeholder="<?php esc_attr_e('Search','docdirect');?>" value="" name="s">
		<button type="submit" class="fa fa-search"></button>
	</fieldset>
</form>
</div>
