"use strict";

$(function(){
	
	$(document).on('click','[data-action="submitThisForm"]', function(){
	
		var targetForm = $(this).closest('form'); 
		if(!myCustom.checkFormFields(targetForm)){
			myCustom.callFormAjax(targetForm).done(function(res){
				var resp = $.parseJSON(res);
				if(resp.status == 1){ 
				    
					if(typeof targetForm.attr('data-reset') != 'undefined' && targetForm.attr('data-reset') == 1){ //check reset form data
						targetForm[0].reset();
						if(targetForm.find('#summernote').length){
							$('.note-editable').html('');
						}
						if(targetForm.find('select').hasClass('select2WithSearch')){
							$('.select2WithSearch').val('').trigger('change');
						}
						if(targetForm.find('input[type="image"]')){
							$('.image_title').html('Choose Image');
						}
						if(targetForm.find('select').hasClass('multipleSelectWithSearch')){
							$('.multipleSelectWithSearch').val([]).trigger('change');
						}
					}
					if(typeof targetForm.attr('data-redirect') != 'undefined'){ //check reset form data
						if(resp.msg != ''){
							if('swal' in resp){
								Swal.fire({
									text: resp.msg,
									showCloseButton: true,
									customClass : {
										popup : 'paymentErrorPopup'
									}
								});
							}else
								toastr.success(resp.msg);
						}		
						if(resp.hasOwnProperty('redirect_url')){
							setTimeout(function(){
								location.href = resp.redirect_url;	
							},1500)	
						}else{
							setTimeout(function(){
								location.href = targetForm.attr('data-redirect');	
							},1500)
						}
					}else if(resp.msg){
						if('swal' in resp){
							Swal.fire({
								text: resp.msg,
								showCloseButton: true,
								customClass : {
									popup : 'paymentErrorPopup'
								}
							});
						}else
							toastr.success(resp.msg);
					}
					
					if(typeof targetForm.attr('data-modal') != 'undefined' && targetForm.attr('data-modal') == 1){ // modal toogle
						var modalId = targetForm.closest('.modal').attr('id');
						$('#'+modalId).modal('toggle');
						if(typeof targetForm.attr('modal-open') != 'undefined' && targetForm.attr('modal-open') != ''){
							$('#'+targetForm.attr('modal-open')).modal('toggle');
						}
						setTimeout(function(){
							if(typeof targetForm.attr('table-reload') != 'undefined' && targetForm.attr('table-reload') != ''){
								var tableId = targetForm.attr('table-reload');
								$('.'+tableId).DataTable().ajax.reload();
							}
						},100)
					}					
					
				}else if(resp.status == 2){
					toastr.error((resp.msg)?resp.msg:resp.error);
                }
				else if(resp.status == 0){
					if('swal' in resp){
						Swal.fire({
							text: resp.msg,
							showCloseButton: true,
							customClass : {
								popup : 'paymentErrorPopup'
							}
						});
						
					}else
						toastr.error((resp.msg)?resp.msg:resp.error);
						
                }else if(resp.errors != ''){
					toastr.error(resp.errors);
				}
				$('.ms_loader, .ms_ajax_loader').addClass('d-none');
				$('.admin_loader_wrapper, .ms_ajax_loader').addClass('d-none');
			});
		}
	});

	$(document).on('click', '#logout', function(event){
		event.preventDefault(); 
		document.getElementById('logout-form').submit();
	})
});