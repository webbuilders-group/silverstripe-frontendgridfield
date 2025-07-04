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
     * Whether to use the admin's React based apis or not
     * @config WebbuildersGroup\FrontEndGridField\Forms\GridField\GridField.use_admin_api
     * @var bool
     */
    private static $use_admin_api = false;

    protected $useAdminAPI = false;

    /**
     * Returns the whole gridfield rendered with all the attached components
     * @return string
     */
    public function FieldHolder($properties = [])
    {
        Requirements::block('silverstripe/admin: css/GridField.css');
        Requirements::css('silverstripe/admin: thirdparty/jquery-ui-themes/smoothness/jquery-ui.min.css');
        Requirements::css('webbuilders-group/silverstripe-frontendgridfield: css/FrontEndGridField.css');
        Requirements::themedCSS('FrontEndGridField');


        if ($this->getUseAdminAPI()) {
            Requirements::javascriptTemplate(dirname(__FILE__) . '/../../../javascript/boot.template.js', [
                'SecurityID' => Convert::raw2js(SecurityToken::inst()->getValue()),
                'AbsoluteBaseURL' => Convert::raw2js(Director::absoluteBaseURL()),
                'BaseURL' => Convert::raw2js(Director::baseURL()),
                'Environment' => Convert::raw2js(Director::get_environment_type()),
                'Debugging' => (Director::isDev() ? 'true' : 'false')
            ]);

            Requirements::javascript('silverstripe/admin: client/dist/js/vendor.js');
            Requirements::javascript('silverstripe/admin: client/dist/js/bundle.js');
            Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
        } else {
            Requirements::javascript('https://code.jquery.com/jquery-3.7.0.min.js');
            Requirements::javascript('https://code.jquery.com/jquery-migrate-1.4.1.min.js');
            Requirements::javascript('silverstripe/admin: thirdparty/jquery-ui/jquery-ui.js');
            Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/externals/hafriedlander/jquery-entwine/jquery.entwine-dist.js');
            Requirements::javascript('silverstripe/admin: client/dist/js/i18n.js');
            Requirements::add_i18n_javascript('silverstripe/admin: javascript/lang');
            Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/externals/silverstripe/lib.js');
            Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/externals/silverstripe/ssui.core.js');
            Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/GridField.js');
        }

        Requirements::javascript('webbuilders-group/silverstripe-frontendgridfield: javascript/FrontEndGridField.js');


        return parent::FieldHolder();
    }

    /**
     * Allow this GridField to use the Admin's API, you should not mix and match this otherwise you will run into issues!
     * @param bool $value Whether or not to use the admin's api
     * @return GridField
     */
    public function setUseAdminAPI($value)
    {
        $this->useAdminAPI = $value;
        return $this;
    }

    /**
     * Get's whether this GridField is to use the Admin's API
     * @return Whether or not to use the admin's api
     */
    public function getUseAdminAPI()
    {
        return $this->useAdminAPI || $this->config()->use_admin_api;
    }

    /**
     * Gets the type for this field
     * @return string
     */
    public function Type()
    {
        return 'frontendgrid ' . parent::Type();
    }

    /**
     * Custom Readonly transformation to remove actions which shouldn't be present for a readonly state.
     * @return GridField
     */
    public function performReadonlyTransformation()
    {
        $this->readonlyComponents[] = GridFieldDetailForm::class;

        return parent::performReadonlyTransformation();
    }
}
