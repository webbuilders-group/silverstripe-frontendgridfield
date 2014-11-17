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

        Requirements::javascript(THIRDPARTY_DIR . '/jquery/jquery.js');
        Requirements::javascript(FRONTEND_GRIDFIELD_BASE.'/javascript/closeparentdialog.js');
        
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
}
?>