<?php if (!defined('FW')) die('Forbidden');

add_action('fw_ext_builder:template_components_register', '_action_fw_ext_builder_template_component_column');
function _action_fw_ext_builder_template_component_column() {
	require_once get_template_directory().'/framework-customizations/extensions/shortcodes/shortcodes/column/includes/template-component/class-fw-ext-builder-templates-component-column.php';

	FW_Ext_Builder_Templates::register_component(
		new FW_Ext_Builder_Templates_Component_Column()
	);
}
