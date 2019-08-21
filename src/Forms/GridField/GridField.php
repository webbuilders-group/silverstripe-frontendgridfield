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
        
        Requirements::javascript('silverstripe/admin: thirdparty/jquery/jquery.js');
        Requirements::javascript('silverstripe/admin: thirdparty/jquery-ui/jquery-ui.js');
        Requirements::javascript('silverstripe/admin: thirdparty/jquery-entwine/dist/jquery.entwine-dist.js');
        Requirements::javascript('silverstripe/admin: client/dist/js/i18n.js');
        Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/GridField.js');
        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/FrontEndGridField.js');
        
        
        return parent::FieldHolder();
    }
}
