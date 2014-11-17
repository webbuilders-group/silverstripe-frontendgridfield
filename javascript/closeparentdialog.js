(function($, dialog) {
	//bind to form submission (submission has started)
	$('form .Actions button[name=action_doDelete]').click(function(e){
		//bind to unload event (submission has completed)
		$(window).unload(function() {
			dialog.fegfdialog("close");
		});
	});
})(jQuery, window.parent.fegf_dialog);