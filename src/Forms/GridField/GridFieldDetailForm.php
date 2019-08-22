<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Forms\GridField\GridFieldDetailForm as SS_GridFieldDetailForm;

class GridFieldDetailForm extends SS_GridFieldDetailForm
{
    protected $template = GridFieldDetailForm::class;
    protected $itemRequestClass = GridFieldDetailForm_ItemRequest::class;
}
