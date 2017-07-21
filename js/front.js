
var miFront = function (jquery) 
{
	$ = jquery; 
}

miFront.prototype = {
	currentForm: null, 
	nonce: '', 
	post_id: 0,
	ajaxurl: '',
	ajaxaction: '',
	
}

miFront.prototype.init = function()
{
	this.ajaxurl = mibfront.ajaxurl;
	this.ajaxaction = mibfront.ajaxaction;
	this.sysslug = mibfront.plugin_slug;
	
	this.currentForm = $('.mib form'); 
	
	// No form loaded
	if (this.currentForm.length == 0) 
	{
		return;
	}
	
	if( this.currentForm.is(':visible') )
		this.form_visible();
	else
		$('.mib form').on('visible', $.proxy(this.form_visible, this )); 
		
	//console.log('init');
	this.hook_events();	

}

miFront.prototype.hook_events = function() 
{
	this.currentForm = $('.mib form'); 
	
	this.currentForm.validate({
		debug: true,
		invalidHandler: $.proxy(this.form_errors, this), 
		submitHandler:  $.proxy(function (f, e) { e.preventDefault(); this.form_send(f, e); return false }, this),
		errorClass: 'mib-error',
	}); 
	
	$('.mib [data-action="close"]').on('click', $.proxy(function (e)
		{ this.form_close(); } , this) );

}

miFront.prototype.send_ajax_cookie = function()
{
		this.nonce = $('input[name="nonce"]').val(); 
		this.post_id = $('input[name="post_id"]').val(); 
	
		var data = { 
			'action': this.ajaxaction,
			'plugin_action': 'visit-cookie',  
			'nonce': this.nonce,
			'post_id': this.post_id,
		}
		var url = this.ajaxurl;
 
		$.ajax({
			url: url, 
			type: 'POST',
			dataType: "JSON",
			data: data,
			success: function()  { 
				$('body').trigger('mi_form_init'); 
			},
			error: function(data){
				console.log('error setting cookie!');
				console.log(data.error());
			  }
      			
		});
			
}


miFront.prototype.form_errors = function(e, v) 
{
	var errors = v.numberOfInvalids();
		if (errors) {
			var invalidElements = v.invalidElements();
			$('#subscription').removeClass('shake animated').addClass('shake animated').one('webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend', function(){
				$(this).removeClass();
			});
		}
    			
}

miFront.prototype.form_send = function(form, event) 
{		
		event.preventDefault(); 
		
		// collect all form items
		$('body').trigger('mi_form_send'); 
		var elements = $(form).find(':input'); 
		var data = {}; 
		
		elements.each(function(index, item)
		{
				data[$(this).attr('name')] = $(this).val(); 
		}); 

		
		data['plugin_action'] = 'post-form'; 

		$.ajax({
			url: this.ajaxurl,
			type: 'POST',
			dataType: "JSON",
			data: data,
			success: $.proxy(this.form_thanks, this), 
		});
		
		// be done with it 
		return false;
}

miFront.prototype.form_visible = function () 
{
	$('body').addClass('template-active'); 
	this.send_ajax_cookie(); 	

}

miFront.prototype.form_thanks = function ()
{
		$('.mib form').children().filter(":not(.after-submit,[class^=background], link, style)").remove();
		$('.mib .after-submit').removeClass('hidden'); 
		window.setTimeout(this.form_close, 2000);
}

miFront.prototype.form_close= function()
{
		$('body').trigger('mi_form_close');
		$('.mib').fadeOut(300).remove(); 
		$('body').removeClass('mi-template-body').removeClass('template-active'); 
		
		$('link[id^=' + this.sysslug + ']').remove(); 
}


jQuery(document).ready(function($) {	

// inits  
maxInboundJS = new miFront($); 
maxInboundJS.init(); 


if (typeof window.maxFoundry === 'undefined') 
	window.maxFoundry = {} ; 
	
window.maxFoundry.maxInbound = maxInboundJS;

 
}); /* END OF JQUERY */

