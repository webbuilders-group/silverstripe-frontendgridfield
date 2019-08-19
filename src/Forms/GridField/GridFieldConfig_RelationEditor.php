<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldConfig_RelationEditor as SS_GridFieldConfig_RelationEditor;
use SilverStripe\Forms\GridField\GridFieldDetailForm as SS_GridFieldDetailForm;

class GridFieldConfig_RelationEditor extends SS_GridFieldConfig_RelationEditor
{
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage = null)
    {
        parent::__construct($itemsPerPage);
        
        $this->removeComponentsByType(SS_GridFieldDetailForm::class)->addComponent(new GridFieldDetailForm());
    }
}
