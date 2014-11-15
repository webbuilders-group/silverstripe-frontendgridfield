<?php
class FrontEndGridField extends GridField {
	/**
	 * Returns the whole gridfield rendered with all the attached components
	 * @return string
	 */
	public function FieldHolder($properties = array()) {
		Requirements::block(FRAMEWORK_DIR.'/css/GridField.css');
		Requirements::css(FRONTEND_GRIDFIELD_BASE.'/css/FrontEndGridField.css');

		Requirements::add_i18n_javascript(FRAMEWORK_DIR.'/javascript/lang');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-ui/jquery-ui.js');
		Requirements::javascript(FRAMEWORK_ADMIN_DIR.'/javascript/ssui.core.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-entwine/dist/jquery.entwine-dist.js');
		Requirements::javascript(FRONTEND_GRIDFIELD_BASE.'/javascript/FrontEndGridField.js');

		return parent::FieldHolder();
	}

}
