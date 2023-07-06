<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridField_ActionMenu;
use SilverStripe\Forms\GridField\GridFieldConfig_Base as SS_GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;

class GridFieldConfig_Base extends SS_GridFieldConfig_Base
{
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);

        if (!GridField::config()->use_admin_api) {
            $this->removeComponentsByType(GridField_ActionMenu::class);
        }

        //Use the legacy filter header as the GraphQL/React one will not work
        $filterHeader = $this->getComponentByType(GridFieldFilterHeader::class);
        if ($filterHeader) {
            if (property_exists($filterHeader, 'useLegacyFilterHeader')) {
                $filterHeader->useLegacyFilterHeader = true;
            } else {
                $this->removeComponent($filterHeader);
            }
        }
    }
}
