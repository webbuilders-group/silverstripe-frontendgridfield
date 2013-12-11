<?php
class FrontEndGridFieldConfig_Base extends GridFieldConfig_Base {
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage=null) {
        parent::__construct($itemsPerPage);
        
        $this->removeComponentsByType('GridFieldDetailForm')->addComponent(new FrontEndGridFieldDetailForm());
    }
    
}

class FrontEndGridFieldConfig_RecordViewer extends GridFieldConfig_RecordViewer {
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage=null) {
        parent::__construct($itemsPerPage);
        
        $this->removeComponentsByType('GridFieldDetailForm')->addComponent(new FrontEndGridFieldDetailForm());
    }
}

class FrontEndGridFieldConfig_RecordEditor extends GridFieldConfig_RecordEditor {
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage=null) {
        parent::__construct($itemsPerPage);
        
        $this->removeComponentsByType('GridFieldDetailForm')->addComponent(new FrontEndGridFieldDetailForm());
    }
}

class FrontEndGridFieldConfig_RelationEditor extends GridFieldConfig_RelationEditor {
    /**
     * @param {int} $itemsPerPage How many items per page should show up
     */
    public function __construct($itemsPerPage=null) {
        parent::__construct($itemsPerPage);
        
        $this->removeComponentsByType('GridFieldDetailForm')->addComponent(new FrontEndGridFieldDetailForm());
    }
}
?>