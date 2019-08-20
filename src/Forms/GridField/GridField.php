<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridField as SS_GridField;
use SilverStripe\View\Requirements;

class GridField extends SS_GridField
{
    /**
     * Returns the whole gridfield rendered with all the attached components
     * @return string
     */
    public function FieldHolder($properties = [])
    {
        Requirements::block('silverstripe/admin: css/GridField.css');
        Requirements::css('webbuilders-group/silverstripe-frontendgridfield: css/FrontEndGridField.css');
        Requirements::themedCSS('FrontEndGridField');
        
        Requirements::customScript('window.ss = window.ss || {}; window.ss.config = window.ss.config || {default: {find: function() {}, getSection: function() {}}, sections: {find: function() {}, getSection: function() {}}};');
        Requirements::javascript('silverstripe/admin: client/dist/js/i18n.js');
        Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
        Requirements::javascript('silverstripe/admin: client/dist/js/vendor.js');
        Requirements::javascript('silverstripe/admin: client/dist/js/bundle.js');
        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/FrontEndGridField.js');
        
        
        return parent::FieldHolder();
    }
}
