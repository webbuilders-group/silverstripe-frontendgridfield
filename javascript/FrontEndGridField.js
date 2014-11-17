(function($) {
    $.entwine('ss', function($) {
        $('.ss-gridfield').entwine({
            UUID: null,
            
            onmatch: function() {
                this._super();
                this.setUUID(new Date().getTime());
            },
            
            closeDialog: function() {
                var dialog=$('#ss-ui-dialog-'+(this.getUUID()));
                
                if(dialog.length>0) {
                    dialog.fegfdialog('close');
                }
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
        
        
        $('.frontendgrid.ss-gridfield:not(.ss-gridfield-editable) .col-buttons .action.gridfield-button-delete').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                // Confirmation on delete
                if(!confirm(ss.i18n._t('TABLEFIELD.DELETECONFIRMMESSAGE'))) {
                    e.preventDefault();
                    return false;
                }
                
                if(!this.is(':disabled')) {
                    var filterState='show'; //filterstate should equal current state.
                    
                    if(this.hasClass('ss-gridfield-button-close') || !(this.closest('.ss-gridfield').hasClass('show-filter'))){
                        filterState='hidden';
                    }
                    
                    this.getGridField().reload({data: [{name: this.attr('name'), value: this.val(), filter: filterState}]});
                }
                
                e.preventDefault();
                return false;
            }
        });
        
        
        //Row Click
        $('.ss-gridfield:not(.ss-gridfield-editable) .ss-gridfield-item:not(.ss-gridfield-no-items) td').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                var editButton=$(this).parent().find('a.edit-link, a.view-link');
                var self=this, id='ss-ui-dialog-'+this.getGridField().getUUID();
                var dialog=$('#'+id);
                
                if(!dialog.length) {
                    dialog=$('<div class="ss-ui-dialog" id="'+id+'" />');
                    $('body').append(dialog);
                }
                
                var extraClass=(this.data('popupclass') ? this.data('popupclass'):'');
                dialog.fegfdialog({
                                title: editButton.text(),
                                iframeUrl: editButton.attr('href'),
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
        
        //View/Edit Button Click
        $('.ss-gridfield a.edit-link, .ss-gridfield a.view-link, .ss-gridfield a.new-link').entwine({
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
                dialog.fegfdialog({
                                title: $(this).text(),
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
    
    $.widget("fegf.fegfdialog", $.ssui.ssdialog, {
        _resizeIframe: function() {
            //Call Parent
            $.ssui.ssdialog.prototype._resizeIframe.call(this);
            
            
            var iframe=this.element.children('iframe');
            var titlebar=jQuery(this.uiDialog).find('.ui-dialog-titlebar');
            
            
            //Resize the iframe taking into account the title bar
            if(titlebar.length>0 && titlebar.is(':visible')) {
                iframe.attr('height', iframe.attr('height')-titlebar.outerHeight());
            }
        }
    });
})(jQuery);