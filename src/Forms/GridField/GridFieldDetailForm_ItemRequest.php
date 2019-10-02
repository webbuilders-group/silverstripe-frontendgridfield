<?php
namespace WebbuildersGroup\FrontEndGridField\Forms\GridField;

use SilverStripe\Admin\LeftAndMain;
use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Form;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\HiddenField;
use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\GridField\GridFieldDetailForm_ItemRequest as SS_GridFieldDetailForm_ItemRequest;
use SilverStripe\ORM\HasManyList;
use SilverStripe\ORM\ManyManyList;
use SilverStripe\ORM\ValidationException;
use SilverStripe\ORM\ValidationResult;
use SilverStripe\View\Requirements;

class GridFieldDetailForm_ItemRequest extends SS_GridFieldDetailForm_ItemRequest
{
    private static $allowed_actions = [
                                        'view',
                                        'edit',
                                        'ItemEditForm'
                                    ];

    /**
     * Builds an item edit form. The arguments to getFrontEndFields() are the popupController and popupFormName, however this is an experimental API and may change.
     * @return Form
     */
    public function ItemEditForm()
    {
        $list = $this->gridField->getList();
        
        if (empty($this->record)) {
            $controller = $this->getToplevelController();
            $noActionURL = $controller->removeAction($controller->getRequest()->getURL(true));
            
            $controller->getResponse()->removeHeader('Location');   //clear the existing redirect
            
            return $controller->redirect($noActionURL, 302);
        }

        $canView = $this->record->canView();
        $canEdit = $this->record->canEdit();
        $canDelete = $this->record->canDelete();
        $canCreate = $this->record->canCreate();

        if (!$canView) {
            return $this->getToplevelController()->httpError(403);
        }
        
        $actions = new FieldList();
        if ($this->record->ID !== 0) {
            if ($canEdit) {
                $actions->push(FormAction::create('doSave', _t('GridFieldDetailForm.Save', 'Save'))
                                                ->setUseButtonTag(true)
                                                ->addExtraClass('btn-primary font-icon-save')
                                                ->setAttribute('data-icon', 'accept'));
            }
            
            if ($canDelete) {
                $actions->push(FormAction::create('doDelete', _t('GridFieldDetailForm.Delete', 'Delete'))
                                                ->setUseButtonTag(true)
                                                ->addExtraClass('btn-outline-danger btn-hide-outline font-icon-trash-bin action-delete'));
            }
        } else { // adding new record
            //Change the Save label to 'Create'
            $actions->push(FormAction::create('doSave', _t('GridFieldDetailForm.Create', 'Create'))
                                            ->setUseButtonTag(true)
                                            ->addExtraClass('btn-primary font-icon-plus-thin')
                                            ->setAttribute('data-icon', 'add'));
            
            // Add a Cancel link which is a button-like link and link back to one level up.
            $curmbs = $this->Breadcrumbs();
            if ($curmbs && $curmbs->count() >= 2) {
                $one_level_up = $curmbs->offsetGet($curmbs->count() - 2);
                $text = sprintf(
                    "<a class=\"%s\" href=\"%s\">%s</a>",
                    "crumb ss-ui-button btn-outline-danger btn-hide-outline font-icon-trash-bin cms-panel-link ui-corner-all", // CSS classes
                    $one_level_up->Link, // url
                    _t('GridFieldDetailForm.CancelBtn', 'Cancel') // label
                );
                
                $actions->push(new LiteralField('cancelbutton', $text));
            }
        }
        
        // If we are creating a new record in a has-many list, then
        // pre-populate the record's foreign key.
        if ($list instanceof HasManyList && !$this->record->isInDB()) {
            $key = $list->getForeignKey();
            $id = $list->getForeignID();
            $this->record->$key = $id;
        }
        
        $fields = $this->component->getFields();
        if (!$fields) {
            $fields = ($this->record->hasMethod('getFrontEndFields') ? $this->record->getFrontEndFields() : $this->record->getCMSFields());
        }
        
        // If we are creating a new record in a has-many list, then
        // Disable the form field as it has no effect.
        if ($list instanceof HasManyList) {
            $key = $list->getForeignKey();
            
            if ($field = $fields->dataFieldByName($key)) {
                $fields->makeFieldReadonly($field);
            }
        }
        
        // this pushes the current page ID in as a hidden field
        // this means the request will have the current page ID in it
        // rather than relying on session which can have been rewritten
        // by the user having another tab open
        // see LeftAndMain::currentPageID
        if ($this->controller->hasMethod('currentPageID') && $this->controller->currentPageID()) {
            $fields->push(new HiddenField('CMSMainCurrentPageID', null, $this->controller->currentPageID()));
        }
        
        // Caution: API violation. Form expects a Controller, but we are giving it a RequestHandler instead.
        // Thanks to this however, we are able to nest GridFields, and also access the initial Controller by
        // dereferencing GridFieldDetailForm_ItemRequest->getController() multiple times. See getToplevelController
        // below.
        $form = new Form(
            $this,
            'ItemEditForm',
            $fields,
            $actions,
            $this->component->getValidator()
        );
        
        $form->loadDataFrom($this->record, ($this->record->ID == 0 ? Form::MERGE_IGNORE_FALSEISH : Form::MERGE_DEFAULT));
        
        if ($this->record->ID && !$canEdit) {
            // Restrict editing of existing records
            $form->makeReadonly();
            
            // Hack to re-enable delete button if user can delete
            if ($canDelete) {
                $form->Actions()->fieldByName('action_doDelete')->setReadonly(false);
            }
        } else if (!$this->record->ID && !$canCreate) {
            // Restrict creation of new records
            $form->makeReadonly();
        }
        
        // Load many_many extraData for record.
        // Fields with the correct 'ManyMany' namespace need to be added manually through getCMSFields().
        if ($list instanceof ManyManyList) {
            $extraData = $list->getExtraData('', $this->record->ID);
            $form->loadDataFrom(['ManyMany' => $extraData]);
        }
        
        $cb = $this->component->getItemEditFormCallback();
        if ($cb) {
            $cb($form, $this);
        }
        
        $this->extend("updateItemEditForm", $form);
        return $form;
    }
    
    /**
     * Renders the view form
     * @param {SS_HTTPRequest} $request Request data
     * @return {string} Rendered view form
     */
    public function view($request)
    {
        if (!$this->record->canView()) {
            $this->httpError(403);
        }
        
        $controller = $this->getToplevelController();
        $form = $this->ItemEditForm($this->gridField, $request);
        
        if (!is_a($form, Form::class)) {
            return $form;
        }
        
        $form->makeReadonly();
        
        
        return $controller->customise([
                                    'Title' => ($this->record && $this->record->exists() ? $this->record->Title : sprintf(_t('GridField.NewRecord', 'New %s'), singleton($this->gridField->getModelClass())->i18n_singular_name())),
                                    'ItemEditForm' => $form,
                                ])->renderWith($this->template);
    }
    
    /**
     * Renders the edit form
     * @param {SS_HTTPRequest} $request Request data
     * @return {string} Rendered edit form
     */
    public function edit($request)
    {
        $controller = $this->getToplevelController();
        $form = $this->ItemEditForm($this->gridField, $request);
        
        if (!is_a($form, Form::class)) {
            return $form;
        }
        
        return $controller->customise([
                                    'Title' => ($this->record && $this->record->exists() ? $this->record->Title : sprintf(_t('GridField.NewRecord', 'New %s'), singleton($this->gridField->getModelClass())->i18n_singular_name())),
                                    'ItemEditForm' => $form,
                                ])->renderWith($this->template);
    }
    
    /**
     * Disabled, the front end does not use breadcrumbs to remember the paths
     */
    public function Breadcrumbs($unlinked = false)
    {
        return;
    }
    
    public function doSave($data, $form)
    {
        $new_record = $this->record->ID == 0;
        $controller = $this->getToplevelController();
        $list = $this->gridField->getList();

        if (!$this->record->canEdit()) {
            return $controller->httpError(403);
        }

        if (isset($data['ClassName']) && $data['ClassName'] != $this->record->ClassName) {
            $newClassName = $data['ClassName'];
            // The records originally saved attribute was overwritten by $form->saveInto($record) before.
            // This is necessary for newClassInstance() to work as expected, and trigger change detection
            // on the ClassName attribute
            $this->record->setClassName($this->record->ClassName);
            // Replace $record with a new instance
            $this->record = $this->record->newClassInstance($newClassName);
        }

        try {
            $form->saveInto($this->record);
            $this->record->write();
            $extraData = $this->getExtraSavedData($this->record, $list);
            $list->add($this->record, $extraData);
        } catch (ValidationException $e) {
            $form->setSessionValidationResult($e->getResult());
            
            $controller->getRequest()->getSession()->set("FormInfo.{$form->FormName()}.data", $form->getData());
            
            return $controller->redirectBack();
        }

        // TODO Save this item into the given relationship

        $link = '<a href="' . $this->Link('edit') . '">"' . htmlspecialchars($this->record->Title, ENT_QUOTES) . '"</a>';
        $message = _t(
            'GridFieldDetailForm.Saved',
            'Saved {name} {link}',
            [
                'name' => $this->record->i18n_singular_name(),
                'link' => $link,
            ]
        );

        $form->sessionMessage($message, ValidationResult::TYPE_GOOD, ValidationResult::CAST_HTML);

        if ($new_record) {
            return $controller->redirect($this->Link());
        } else if ($this->gridField->getList()->byId($this->record->ID)) {
            return $controller->redirectBack();
        } else {
            // Changes to the record properties might've excluded the record from
            // a filtered list, so return back to the main view if it can't be found
            $noActionURL = $controller->removeAction($data['url']);
            $controller->getRequest()->addHeader('X-Pjax', 'Content');
            return $controller->redirect($noActionURL, 302);
        }
    }
    
    public function doDelete($data, $form)
    {
        $title = $this->record->Title;
        try {
            if (!$this->record->canDelete()) {
                throw new ValidationException(_t('GridFieldDetailForm.DeletePermissionsFailure', "No delete permissions"), 0);
            }
        
            $this->record->delete();
        } catch (ValidationException $e) {
            $form->sessionMessage($e->getResult()->message(), ValidationResult::TYPE_ERROR);
            return Controller::curr()->redirectBack();
        }
        
        $message = sprintf(_t('GridFieldDetailForm.Deleted', 'Deleted %s %s'), $this->record->i18n_singular_name(), htmlspecialchars($title, ENT_QUOTES));
        
        $toplevelController = $this->getToplevelController();
        if ($toplevelController && $toplevelController instanceof LeftAndMain) {
            $backForm = $toplevelController->getEditForm();
            $backForm->sessionMessage($message, ValidationResult::TYPE_GOOD);
        } else {
            $form->sessionMessage($message, ValidationResult::TYPE_GOOD);
        }
        
        
        //Remove all requirements
        Requirements::clear();
        
        return $this->customise(['GridFieldID' => $this->gridField->ID()])->renderWith(GridField::class . '_deleted');
    }
    
    /**
     * Wrapper for redirectBack()
     * @see Controller::redirectBack()
     */
    public function redirectBack()
    {
        return Controller::curr()->redirectBack();
    }
}
