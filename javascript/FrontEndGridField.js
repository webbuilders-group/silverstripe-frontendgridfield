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
        
        
        $('.frontendgrid.ss-gridfield:not(.ss-gridfield-editable) .grid-field__col-compact .action.gridfield-button-delete').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                // Confirmation on delete
                if(!confirm(ss.i18n._t('Admin.DELETECONFIRMMESSAGE', 'Are you sure you want to delete this record?'))) {
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
        $('.ss-gridfield:not(.ss-gridfield-editable) .ss-gridfield-item:not(.ss-gridfield-no-items) td:not(.grid-field__col-compact)').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
                var editButton=$(this).parent().find('a.edit-link, a.view-link');
                if (editButton.length) {
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
                }                
                e.preventDefault();
                return false;
            }
        });
        $('.ss-gridfield:not(.ss-gridfield-editable) .ss-gridfield-item:not(.ss-gridfield-no-items) td.grid-field__col-compact').entwine({
            /**
             * Function: onclick
             */
            onclick: function(e) {
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
        
        $('.grid-field__table .ss-gridfield-item.loading').entwine({
            onmatch: function() {
                this.append('<div class="cms-loading-container"> \
                        <div key="overlay" class="cms-content-loading-overlay ui-widget-overlay-light" /> \
                            <div key="spinner" class="cms-content-loading-spinner"> \
                              <div class="spinner"> \
                                <svg \
                                  xmlns="http://www.w3.org/2000/svg" \
                                  xmlnsXlink="http://www.w3.org/1999/xlink" \
                                  viewBox="0 0 30 30" \
                                  width="30" \
                                  height="30" \
                                  class="spinner__animation" \
                                > \
                                  <g> \
                                    <defs> \
                                      <path \
                                        id="spinner__animation__outline" \
                                        d="M17.6,9.8c-2.3,1.7-2.8,5-1.1,7.3l4.2-3.1 \
                                        c1.1-0.8,2.7-0.6,3.6,0.5c0.8,1.1,0.6,2.7-0.5,3.6l-6.2,4.6 \
                                        c-2.3,1.7-2.8,5-1.1,7.3l10.4-7.7c3.4-2.6,4.1-7.4,1.6-10.8 \
                                        C25.9,8,21.1,7.3,17.6,9.8z M13.4,12.9L9.3,16c-1.1,0.8-2.7,0.6-3.6-0.5 \
                                        s-0.6-2.7,0.5-3.6l6.2-4.6c2.3-1.7,2.8-5,1.1-7.3L3.1,7.7 \
                                        c-3.4,2.6-4.1,7.4-1.6,10.8c2.6,3.4,7.4,4.1,10.8,1.6 \
                                        C14.7,18.4,15.1,15.2,13.4,12.9z" \
                                      /> \
                                      <clipPath id="spinner__animation__mask"> \
                                        <use xlinkHref="' + window.location + '#spinner__animation__outline" /> \
                                      </clipPath> \
                                    </defs> \
                                    <use \
                                      class="spinner__animation__empty" \
                                      xlinkHref="' + window.location + '#spinner__animation__outline" \
                                    /> \
                                    <path \
                                      class="spinner__animation__fill" \
                                      clipPath="' + window.location + '#spinner__animation__mask" \
                                      d="M15,2.1L4.7,9.8c-2.3,1.7-2.8,4.9-1.1,7.2 \
                                      s4.9,2.8,7.2,1.1l8.3-6.1c2.3-1.7,5.5-1.2,7.2,1.1 \
                                      s1.2,5.5-1.1,7.2L15,27.9" \
                                    /> \
                                  </g> \
                                </svg> \
                              </div> \
                            </div> \
                        </div>');
                
                this._super();
            },
        
            onunmatch: function() {
                this.children('.cms-loading-container').remove();
                
                this._super();
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