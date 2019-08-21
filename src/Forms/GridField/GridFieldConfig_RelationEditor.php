<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor as SS_GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDetailForm as SS_GridFieldDetailForm;
use SilverStripe\Forms\GridField\GridFieldFilterHeader;
use SilverStripe\Forms\GridField\GridField_ActionMenu;

class GridFieldConfig_RelationEditor extends SS_GridFieldConfig_RelationEditor
{
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);
        
        $this
            ->removeComponentsByType(SS_GridFieldDetailForm::class)
            ->removeComponentsByType(GridField_ActionMenu::class)
            ->addComponent(new GridFieldDetailForm());
        
        //Use the legacy filter header as the GraphQL/React one will not work
        $this->getComponentByType(GridFieldFilterHeader::class)->useLegacyFilterHeader = true;
    }
}
