"use strict";

var myCustom = {
	checkFormFields : function(formId){
		var check = 0;
		var target = (typeof formId == 'object')? $(formId):$('#'+formId);
		target.find('input , textarea , select').each(function(){
            var email = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;	 
			var url = /(http|ftp|https):\/\/[\w-]+(\.[\w-]+)+([\w.,@?^=%&amp;:\/~+#-]*[\w@?^=%&amp;\/~+#-])?/;
			var websiteUrl = /^(?:http(s)?:\/\/)?[\w.-]+(?:\.[\w\.-]+)+[\w\-\._~:/?#[\]@!\$&'\(\)\*\+,;=.]+$/;
			var image = /\.(jpe?g|gif|png|PNG|JPE?G)$/;
			var mobile =  /((?:\+|00)[17](?: |\-)?|(?:\+|00)[1-9]\d{0,2}(?: |\-)?|(?:\+|00)1\-\d{3}(?: |\-)?)?(0\d|\([0-9]{3}\)|[1-9]{0,3})(?:((?: |\-)[0-9]{2}){4}|((?:[0-9]{2}){4})|((?: |\-)[0-9]{3}(?: |\-)[0-9]{4})|([0-9]{7}))/;
			var audioUrl = /\.(mp3|MP3|wav|WAV)$/;
			var facebook = /^(https?:\/\/)?(www\.)?facebook.com/;
			var twitter = /^(https?:\/\/)?(www\.)?twitter.com/;
			var google_plus = /^(https?:\/\/)?(www\.)?plus.google.com/;
			var linkedin = /^(https?:\/\/)?(www\.)?linkedin.com/;
			var number = /^[\s()+-]*([0-9][\s()+-]*){1,20}$/; ///^[0-9]{1,10}$/;
			var password = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&#])[A-Za-z\d$@$!%*?&#]{8,}$/;
						
			//var vimeo = /(https?:\/\/)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/; 
			if($(this).hasClass('require')){
				if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() == '')){ 
					check = 1;
					if($(this).hasClass('require')){
						toastr.error('Some required fields are missing.');
					}
					$(this).addClass('error').focus();
					return false; 
				}else if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() != '') && typeof $(this).attr('data-match') != 'undefined'){
					var fieldToMatch = $(this).attr('data-match');
					if($(this).val() != $('#'+fieldToMatch).val()){
						toastr.error($(this).attr('data-error'));
						return;
					}
					$(this).addClass('error').focus();
					return false; 
				}else{
					$(this).removeClass('error');
				} 
			}

		
			if((typeof $(this).val() == 'object' && isEmpty($(this).val()) == true) || (typeof $(this).val() != 'object' && $(this).val().trim() != '')){
				var valid = $(this).attr('data-valid'); 
				var length = $(this).attr('length');
				var amount = $(this).attr('amount');
				var imgId = $(this).attr('data-image-id');
				var imgErr = $(this).attr('data-image');
				if(typeof valid != 'undefined'){
					if(!eval(valid).test($(this).val().trim())){
						$(this).addClass('error').focus();
						check = 1;
						toastr.error($(this).attr('data-error'));
						return false; 
					}else if($(this).attr('data-valid-change') != 'undefined' && $(this).attr('data-valid-change') == '1'){
						$(this).attr('data-valid', 'audioUrl').attr('data-error','We are only allowed mp3,wav file URL.');
						if(!eval($(this).attr('data-valid')).test($(this).val().trim())){
							$(this).addClass('error').focus();
							check = 1;
							toastr.error($(this).attr('data-error'));
							return false; 
						}else	
							$(this).removeClass('error');
					}else{
						$(this).removeClass('error');
					}
				}else if(typeof length != 'undefined'){
					if($(this).val().trim().length < length){
						$(this).addClass('error').focus();
						check = 1;
						toastr.error($(this).attr('data-length-error'));
						return false; 
					}else
						$(this).removeClass('error');
				}else if(typeof amount != 'undefined'){
					if($(this).val().trim() == '0'){
						$(this).addClass('error').focus();
						check = 1;
						toastr.error(amount);
						return false; 
					}else
						$(this).removeClass('error');
				}else if(typeof imgId != 'undefined'){
					if($('#'+imgId).val() == ''){
						check = 1;
						toastr.error(imgErr);
						return false; 
					}else
						$(this).removeClass('error');
				}
			}
		});

		return check;		

		function isEmpty(obj) {
			for(var key in obj) {
				if(obj.hasOwnProperty(key))
					return false;
			}
			return true;
		}
	}, 	

	callFormAjax : function(targetForm){
	    $('.ms_loader, .ms_ajax_loader').removeClass('d-none').css('z-index',9999999); 
		$('.admin_loader_wrapper, .ms_ajax_loader').removeClass('d-none').css('z-index',9999999); 
		var targetUrl = targetForm.attr('action');
		if(targetForm.find('[type="file"]').length){
			var AjaxOption = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				url: targetUrl,
				method: "post",
				data : new FormData(targetForm[0]),
				processData: false,
				contentType: false,
			};
		}else{
			var AjaxOption = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				url: targetUrl,
				method: "post",
				data : targetForm.serialize()
			};
		}


		return $.ajax(AjaxOption);
	}

};



