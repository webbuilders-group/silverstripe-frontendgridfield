<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Control\Director;
use SilverStripe\Core\Convert;
use SilverStripe\Forms\GridField\GridField as SS_GridField;
use SilverStripe\Security\SecurityToken;
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
        
        Requirements::javascriptTemplate(dirname(__FILE__) . '/../../../javascript/boot.template.js', [
            'SecurityID' => Convert::raw2js(SecurityToken::inst()->getValue()),
            'AbsoluteBaseURL' => Convert::raw2js(Director::absoluteBaseURL()),
            'BaseURL' => Convert::raw2js(Director::baseURL()),
            'Environment' => Convert::raw2js(Director::get_environment_type()),
            'Debugging' => (Director::isDev() ? 'true' : 'false')
        ]);
        Requirements::javascript('silverstripe/admin: client/dist/js/i18n.js');
        Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
        Requirements::javascript('silverstripe/admin: client/dist/js/vendor.js');
        Requirements::javascript('silverstripe/admin: client/dist/js/bundle.js');
        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/FrontEndGridField.js');
        
        
        return parent::FieldHolder();
    }
}
