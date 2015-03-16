<?php
class FrontEndGridFieldDetailForm extends GridFieldDetailForm {
    protected $template='FrontEndGridFieldDetailForm';
}

class FrontEndGridFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest {
    private static $allowed_actions=array(
                                        'view',
                                        'edit'
                                    );
    
    
    /**
     * Renders the view form
     * @param {SS_HTTPRequest} $request Request data
     * @return {string} Rendered view form
     */
    public function view($request) {
        if(!$this->record->canView()) {
            $this->httpError(403);
        }
        
        $controller=$this->getToplevelController();
        $form=$this->ItemEditForm($this->gridField, $request);
        $form->makeReadonly();
        
        
        return $controller->customise(array(
                                            'Title'=>($this->record && $this->record->ID ? $this->record->Title:sprintf(_t('GridField.NewRecord', 'New %s'), $this->record->i18n_singular_name())),
                                            'ItemEditForm'=>$form
                                        ))->renderWith($this->template);
    }
    
    /**
     * Renders the edit form
     * @param {SS_HTTPRequest} $request Request data
     * @return {string} Rendered edit form
     */
    public function edit($request) {
        $controller=$this->getToplevelController();
        $form=$this->ItemEditForm($this->gridField, $request);
        
        
        return $controller->customise(array(
                                    'Title'=>($this->record && $this->record->ID ? $this->record->Title:sprintf(_t('GridField.NewRecord', 'New %s'), $this->record->i18n_singular_name())),
                                    'ItemEditForm'=>$form,
                                ))->renderWith($this->template);
    }
    
    /**
     * Disabled, the front end does not use breadcrumbs to remember the paths
     */
    public function Breadcrumbs($unlinked = false) {
        return;
    }
    
    public function doDelete($data, $form) {
        $title=$this->record->Title;
        try {
            if(!$this->record->canDelete()) {
                throw new ValidationException(_t('GridFieldDetailForm.DeletePermissionsFailure', "No delete permissions"), 0);
            }
        
            $this->record->delete();
        }catch(ValidationException $e) {
            $form->sessionMessage($e->getResult()->message(), 'bad');
            return Controller::curr()->redirectBack();
        }
        
        $message=sprintf(_t('GridFieldDetailForm.Deleted', 'Deleted %s %s'), $this->record->i18n_singular_name(), htmlspecialchars($title, ENT_QUOTES));
        
        $toplevelController=$this->getToplevelController();
        if($toplevelController && $toplevelController instanceof LeftAndMain) {
            $backForm = $toplevelController->getEditForm();
            $backForm->sessionMessage($message, 'good');
        }else {
            $form->sessionMessage($message, 'good');
        }
        
        
        //Remove all requirements
        Requirements::clear();
        
        return $this->customise(array('GridFieldID'=>$this->gridField->ID()))->renderWith('FrontEndGridField_deleted');
    }
    
    /**
     * Wrapper for redirectBack()
     * @see Controller::redirectBack()
     */
    public function redirectBack() {
        return Controller::curr()->redirectBack();
    }
}
?>