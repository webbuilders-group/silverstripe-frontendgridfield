<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldConfig_Base as SS_GridFieldConfig_Base;
use SilverStripe\Forms\GridField\GridFieldDetailForm as SS_GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridField_ActionMenu;

class GridFieldConfig_Base extends SS_GridFieldConfig_Base
{
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);
        
        $this
            ->removeComponentsByType(SS_GridFieldDetailForm::class)
            ->addComponent(new GridFieldDetailForm());
        
        if (!GridField::config()->use_admin_api) {
            $this->removeComponentsByType(GridField_ActionMenu::class);
        }
        
        //Use the legacy filter header as the GraphQL/React one will not work
        $this->getComponentByType(GridFieldFilterHeader::class)->useLegacyFilterHeader = true;
    }
}
