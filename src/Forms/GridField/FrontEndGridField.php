<?php
namespace WebbuildersGroup\FrontendGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridField;
use SilverStripe\View\Requirements;

class FrontEndGridField extends GridField {
    /**
     * Returns the whole gridfield rendered with all the attached components
     * @return string
     */
    public function FieldHolder($properties=array()) {
        Requirements::block('silverstripe/admin: css/GridField.css');
        Requirements::themedCSS(FrontEndGridField::class, FRONTEND_GRIDFIELD_BASE);
        
        Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
        Requirements::javascript('silverstripe/admin: jquery/jquery.js');
        Requirements::javascript('silverstripe/admin: jquery-ui/jquery-ui.js');
        Requirements::javascript('silverstripe/admin: javascript/ssui.core.js');
        Requirements::javascript('silverstripe/admin: javascript/lib.js');
        Requirements::javascript('silverstripe/admin: jquery-entwine/dist/jquery.entwine-dist.js');
        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/FrontEndGridField.js');
        
        
        return parent::FieldHolder();
    }
}
?>