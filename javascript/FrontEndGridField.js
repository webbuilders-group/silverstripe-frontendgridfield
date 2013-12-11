(function($) {
    $.entwine('ss', function($) {
        $('.ss-gridfield').entwine({
            UUID: null,
            
            onmatch: function() {
                this._super();
                this.setUUID(new Date().getTime());
            }
        });
        
        //Init jQuery UI Buttons
        $('.ss-gridfield .ss-ui-button, .ss-gridfield .action, .ss-gridfield .trigger').entwine({
            onadd: function() {
                this.addClass('ss-ui-button');
                if(!this.data('button')) this.button();
                this._super();
            },
            onremove: function() {
                if(this.data('button')) this.button('destroy');
                this._super();
            }
        });
        
        
        $('.ss-gridfield button.gridfield-button-delete').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                // Confirmation on delete. 
                if(!confirm(ss.i18n._t('TABLEFIELD.DELETECONFIRMMESSAGE'))) {
                    e.preventDefault();
                    return false;
                }
                
                if(!this.is(':disabled')) {
                    this.parents('form').trigger('submit', [this]);
                }
                
                e.preventDefault();
                return false;
            }
        });
        
        $('.ss-gridfield a.edit-link, .ss-gridfield a.new-link').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                var self=this, id='ss-ui-dialog-'+this.getGridField().getUUID();
                var dialog=$('#'+id);
                
                if(!dialog.length) {
                    dialog=$('<div class="ss-ui-dialog" id="'+id+'" />');
                    $('body').append(dialog);
                }
                
                var extraClass=(this.data('popupclass') ? this.data('popupclass'):'');
                dialog.ssdialog({
                                iframeUrl: this.attr('href'),
                                autoOpen: true,
                                dialogExtraClass: extraClass,
                                close: function(e, ui) {
                                    self.getGridField().reload();
                                }
                            });
                
                
                e.preventDefault();
                return false;
            }
        });
    });
})(jQuery);