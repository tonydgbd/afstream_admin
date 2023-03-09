"use strict";

$(function(){
    var dynamicKeys = JSON.parse(jsDynamicText);
    
    
    /*
    ** FUNCTION FOR UPLOAD IMAGE AND PREVIEW 
    */
    $(document).on('change', '.pz_filed_file', function () {
        let _this = $(this);

        $(".previewBlogImage").attr('data-select','upload');
        let file = _this[0].files[0];

        let reader = new FileReader();
        reader.addEventListener("load", function () {
            $('#'+_this.attr('data-id')).attr('src', reader.result);            
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    });
    
    $(document).ready(function(){
        $(".selectToArtist").hide();    
        $('#checkArtist').prop("checked",false);   
        $('#checkArtist').on('click', function(){
            if($(this).prop("checked") == true){
                $(".selectToArtist").show();
                $(".registerArtistField").addClass('require');
            }else if($(this).prop("checked") == false){
                $(".selectToArtist").hide();
                $(".registerArtistField").removeClass('require');
            }
        });
    });  
    
    if ($('.select2WithSearch').length) {
        $('.select2WithSearch').select2({
            placeholder: $(this).attr('placeholder')
        });
    }

    if ($('.multipleSelectWithSearch').length) {
        $('.multipleSelectWithSearch').select2({
            placeholder: $(this).attr("placeholder"),
        })
    }
    
    $(window).on('load', function(){
        if($('.grid').length){
            $('.grid').isotope({
                itemSelector: '.grid-item',
                masonry: {
                    columnWidth: 20,
                    gutter: $('.grid').data('gutter'),
                }
            });
        }
        if($('.paymentMethod').length){
            $(".paymentMethod").prop('checked',false);
            $(".ms_card_wrapper, .braintree_card, .instamojo-form, #payu-form, #paytm-form, #paypal-form, #paystack-form, #razorpayForm, #manualpay-form").addClass('d-none');
        }
    });

    if(isInspect == 1){
        $("body").on("keydown", function (e) {
            if(e.which === 123 || (e.ctrlKey && e.shiftKey && e.which == 73))
                return false;                 
        }); 

        $("body").on("bind", "contextmenu", function (e) {
            e.preventDefault(); 
        }); 
    }

    if(isRightClick == 1){
        $("body").on("bind", "contextmenu", function (e) {
            e.preventDefault(); 
        }); 
    }

    if($('[data-star="rating"]').length){
        $('[data-star="rating"]').each(function(){
            $("."+$(this).attr('class')).starRating({
                initialRating: $(this).attr('data-rating'),
                readOnly: true,
                starSize : 25
            });
        })
    }

    if($('.ms_payment_section').length){
        new Card({
            form: document.querySelector('form.card_Detail'),
            container: '.card-wrapper'
        });
    }

    if($(".rating").length){
        $(".rating").starRating({
            initialRating : 0,
            disableAfterRate : false,
            emptyColor : 'lightgray',
            hoverColor : '#2ec8e6',
            ratedColors : ['#2ec8e6','#2ec8e6','#2ec8e6','#2ec8e6','#2ec8e6'],
            strokeColor : 'black',
            strokeWidth : 9,
            callback : function(currentRating, $el){
                $('.live-rating').val(currentRating)
            }
        });
    }


    $(document).on('click','.addToFavourite',function(){

        var favid = $(this).attr('data-favourite');        
        if(favid.length == ''){
            return toastr.error('Track Not Found');
        }
        var type = $(this).attr('data-type');
        var page = $(this).attr('data-page');
        //console.log(page); return;
        var cur_ev = $(this);
        let _this = $(this);
        if(type == 'album')
            var data = 'albumid=';
        else if(type == 'artist')
            var data = 'artistid=';
        else if(type == 'audio')
            var data = 'audioid=';
        else if(type == 'playlist')
            var data = 'playlistid=';
        else if(type == 'genre')
            var data = 'genreid=';
        var formdata = data+favid;
        if(typeof favid != 'undefined'){
            $(".ms_ajax_loader").removeClass('d-none');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/add_to_favourite/'+type,
                data: formdata,
                success: function(response){
                    var result = JSON.parse(response);
                    $(".ms_ajax_loader").addClass('d-none');
                    if( result.status ){
                        if(result.action == 'removed'){
                            if(page == 'favourite_page'){
                                _this.parents(".removeFavRow").remove();   
                            }                            
                            toastr.success(result.msg);
                            _this.find('svg').remove();
                            _this.html('<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="16px"><path fill-rule="evenodd" fill="rgb(124, 142, 165)" d="M11.777,-0.000 C10.940,-0.000 10.139,0.197 9.395,0.585 C9.080,0.749 8.783,0.947 8.506,1.173 C8.230,0.947 7.931,0.749 7.618,0.585 C6.874,0.197 6.073,-0.000 5.236,-0.000 C2.354,-0.000 0.009,2.394 0.009,5.337 C0.009,7.335 1.010,9.428 2.986,11.557 C4.579,13.272 6.527,14.702 7.881,15.599 L8.506,16.012 L9.132,15.599 C10.487,14.701 12.436,13.270 14.027,11.557 C16.002,9.428 17.004,7.335 17.004,5.337 C17.004,2.394 14.659,-0.000 11.777,-0.000 ZM5.236,2.296 C6.168,2.296 7.027,2.738 7.590,3.507 L8.506,4.754 L9.423,3.505 C9.986,2.737 10.844,2.296 11.777,2.296 C13.403,2.296 14.727,3.660 14.727,5.337 C14.727,6.734 13.932,8.298 12.364,9.986 C11.114,11.332 9.604,12.490 8.506,13.255 C7.409,12.490 5.899,11.332 4.649,9.986 C3.081,8.298 2.286,6.734 2.286,5.337 C2.286,3.660 3.610,2.296 5.236,2.296 Z"/></svg>');
                            
                        }else{
                            toastr.success(result.msg);
                            _this.find('svg').remove();
                            _this.html("<svg width='19px' height='19px' id='Layer_1' data-name='Layer 1' xmlns='http://www.w3.org/2000/svg' viewBox='0 0 391.8 391.8'><defs><style>.cls-1{fill:#d80027;}</style></defs><path class='cls-1' d='M280.6,43.8A101.66,101.66,0,0,1,381.7,144.9c0,102-185.8,203.1-185.8,203.1S10.2,245.5,10.2,144.9A101.08,101.08,0,0,1,111.3,43.8h0A99.84,99.84,0,0,1,196,89.4,101.12,101.12,0,0,1,280.6,43.8Z'></path></svg>");
                        }
                    }else{
                        toastr.error(result.msg);
                    }
                }
            });
        }
    });

    $(document).on('change','[name="apply_coupon"]', function(){
        if($(this).val() == 1){
            $('#applyCouponForm').removeClass('d-none');
            $('#couponCode').addClass('require');
        }else{
            $('#applyCouponForm').addClass('d-none');
            $('#couponCode').removeClass('require');
        }
    });

    $(document).on('click',".language_filter", function(){
		var lang = [];
		$(".lang_filter:checked").each(function() {
            lang.push(this.value);
        });
        var lang_data = 'filter_lang='+lang;
        if(lang.length){
            $(".language_filter").hide();
            $(".ms_ajax_loader").removeClass('d-none');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/filter_language',
                data: lang_data,
                success: function(response){
                    localStorage.removeItem("jp_playlist")
                    $(".ms_ajax_loader").addClass('d-none');
                    location.reload();
                }
            });
        }else{
            toastr.error(dynamicKeys.select_lang);
        }
    });
    
    $(document).on('click','.likeDislikeAudio',function(){
        var songId = $(this).attr('data-id');
        var type = $(this).attr('data-type');
        var _this = $(this);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
			type: 'post',
			url: userBaseUrl+'/like_dislike_audio',
			data: {'id':songId, 'type':type},
			success: function(response){
                var resp = JSON.parse(response);
		        if(resp.status){
                    if(resp.resp.hasOwnProperty('like') && resp.resp.hasOwnProperty('dislike')){
                        if(resp.resp.dislike == 1 && resp.resp.like == 0){
                            _this.closest('span').find('[data-type="2"]').css('color','red');
                            _this.closest('span').find('[data-type="1"]').css('color','white');
                        }else{
                            _this.closest('span').find('[data-type="1"]').css('color','#3bc8e7');
                            _this.closest('span').find('[data-type="2"]').css('color','white');
                        }
                    }else if(resp.resp.hasOwnProperty('like')){
                        if(resp.resp.like)
                            _this.css('color','#3bc8e7');
                        else
                            _this.css('color','white');
                    }else if(resp.resp.hasOwnProperty('dislike')){
                        if(resp.resp.dislike)
                            _this.css('color','red');
                        else
                            _this.css('color','white');
                    }
                }else{
                    toastr.error(resp.msg)
                }
            }
		});
    })

    $(document).on('click',".download_track", function(){ 
		var id = $(this).attr('data-musicid');
		var formdata ='musicid='+id;
        $(".ms_ajax_loader").removeClass('d-none');
        setTimeout(function(){
            $(".ms_ajax_loader").addClass('d-none');
        }, 1500)
		if(id){
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'post',
				url: userBaseUrl+'/download_track',
				data: formdata,
				success: function(response){
					var result = JSON.parse(response);
                    $(".ms_ajax_loader").addClass('d-none');
					if( result.status && result.mp3_uri != '' ) {
                        var link = document.createElement("a");
                        link.href = result.mp3_uri;
                        link.download = result.mp3_name;
                        link.click();
                    }else{
						if(result.status == 'false' && result.plan_page != ''){
						    window.location.href = result.plan_page;
						}
					}
				}
            });
        }		
    });
    
    $(document).on('click',".download_artist_track", function(){ 
        
        var id = $(this).attr('data-musicid');
        var formdata ='musicid='+id;
        $(".ms_ajax_loader").removeClass('d-none');
        setTimeout(function(){
            $(".ms_ajax_loader").addClass('d-none');
        }, 1500)
        if(id){
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/download_artist_track',
                data: formdata,
                success: function(response){
                    var result = JSON.parse(response);
                    $(".ms_ajax_loader").addClass('d-none');
                    if( result.status && result.mp3_uri != '' ) {
                        var link = document.createElement("a");
                        link.href = userBaseUrl+'/download_audio/?url='+result.mp3_uri+'&audio_name='+result.mp3_name;
                        link.click();
                        toastr.success(result.msg);
                        setTimeout(function(){
                            location.reload();                
                        },800);
                    }else{
                        toastr.error(result.msg);
                    }
                }
            });
        }       
    });

    $(document).on('click',".download_list", function(){ 
        
		var id = $(this).attr('data-musicid');
		var type = $(this).attr('data-type');
        
		var formdata ='musicid='+id;
        $(".ms_ajax_loader").removeClass('d-none');
        setTimeout(function(){
            $(".ms_ajax_loader").addClass('d-none');
        }, 1500)
		if(id){
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'post',
				url: userBaseUrl+'/download_list/'+type,
				data: formdata,
				success: function(response){				    
				   
					var result = JSON.parse(response);
                    $(".ms_ajax_loader").addClass('d-none');
				    if(result.status == true){
				        toastr.success(result.msg);
				        var link = document.createElement("a");
                        link.href = userBaseUrl+'/'+result.mp3_uri;
                        link.download = result.mp3_name;
                        link.click();
				    }else{
				        toastr.error(result.msg);				
				    }				
				}
            });
        }		
    });
    
    $(".buy_to_download_audio").on('click', function(e){
        e.preventDefault();        
        $(".paymentMethod").prop('checked',false);
        $(".ms_card_wrapper, .braintree_card, .instamojo-form, #payu-form, #paytm-form, #paypal-form, #paystack-form, #razorpayForm, #manualpay-form").addClass('d-none');
        if(checkUserId != ''){
            $(".buyAudioPrice").html('');
            $(".audioIdToStripe").val('');
            $(".buy_audio_id").val('');
            var toggleHeaderAmount = $(this).siblings( ".getAudioAmountToDownload" ).val();
            var audioId = $(this).attr('data-musicid');
            $(".buy_audio_id").val(audioId);
            $(".audioIdToStripe").val(audioId);
            $(".buyAudioPrice").html(toggleHeaderAmount);
            $("#ms_purchase_music_download").modal("show");
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    });
    
    $(document).on('click',".buy_to_download_audio", function(e){       
        e.preventDefault();
        $(".paymentMethod").prop('checked',false);
        $(".ms_card_wrapper, .braintree_card, .instamojo-form, #payu-form, #paytm-form, #paypal-form, #paystack-form, #razorpayForm, #manualpay-form").addClass('d-none');        
        if(checkUserId != ''){
            $(".buyAudioPrice").html('');
            $(".audioIdToStripe").val('');
            $(".buy_audio_id").val('');
            var toggleHeaderAmount = $(this).siblings( ".getAudioAmountToDownload" ).val();       
            var audioId = $(this).attr('data-musicid');
            $(".buy_audio_id").val(audioId);
            $(".audioIdToStripe").val(audioId);
            $(".buyAudioPrice").html(toggleHeaderAmount);
            $("#ms_purchase_music_download").modal("show");
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    });


    $(".create_playlist").on('click', function(){
        $("#add_in_playlist_modal").modal("hide");
        $("#create_playlist_modal").modal("show");
    });
    $(document).on('click',".create_playlist", function(){
        $("#add_in_playlist_modal").modal("hide");
        $("#create_playlist_modal").modal("show");
    });

    $(".create_new_playlist").on('click', function(){
        
        var name = $.trim($("#playlist_name").val());
		if(name == ''){
			toastr.error(dynamicKeys.playlist_err);
		}else{
            $(".ms_ajax_loader").removeClass('d-none');
            $('.getAjaxRecord[data-type=playlist]').addClass('active');        
            $('.append_html_data').html('');

			var formdata ='playlist_name='+name;
        	$(".create_new_playlist").prop('disabled', true);
			
			$.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
				type: 'post',
				url: userBaseUrl+'/create_playlist',
				data: formdata,
				success: function(response){
                    $(".create_new_playlist").prop('disabled', false);
                    $(".ms_ajax_loader").addClass('d-none');
					var result = JSON.parse(response);
					if( result.status ) {                                              
                        $("#create_playlist_modal").modal("hide"); 
                        $('.append_html_data').html(result.html);     
                        setAllSliderData();                    
						toastr.success(result.msg);
						setTimeout(function(){
                            location.reload();                
                        },800);
					}else{
						toastr.error(result.msg);
						$(".create_new_playlist").show();
					}
				}
			});
		}
    });
    
    $(document).on('click',".ms_add_playlist",function(){
    	var music = $(this).attr("data-musicid");
        var type = $(this).attr("data-musictype");        
        if(type?.length){            
            $('#add_in_playlist_modal select[name="playlistname"]').attr("data-musictype", type);
        }else{           
            $('#add_in_playlist_modal select[name="playlistname"]').attr("data-musictype", "ms_audio");
        }       
    	$("#ms_share_music_modal_id").modal("hide");
    	$("#create_playlist_modal").modal("hide");
    	$('#add_in_playlist_modal select[name="playlistname"]').attr("data-musicid", music);        
    	$("#add_in_playlist_modal").modal("show");
    });
  

    $('.ms_add_in_playlist').on('click', function(){
		var playlistid = $('#add_in_playlist_modal select[name="playlistname"]').val();
		var musicid = $('#add_in_playlist_modal select[name="playlistname"]').attr('data-musicid');
        var musicType = $('#add_in_playlist_modal select[name="playlistname"]').attr("data-musictype");
        if(playlistid == ''){
            toastr.error(dynamicKeys.select_playlist);
        }else{
            if(typeof musicid != 'undefined'){
                var formdata ='playlistid='+playlistid+'&musicid='+musicid+'&type='+musicType; 
            
                $(".ms_add_in_playlist").hide();
                $(".ms_ajax_loader").removeClass('d-none');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: userBaseUrl+'/add_in_playlist',
                    data: formdata,
                    success: function(response){
                        var result = JSON.parse(response);
                        if( result.status == 1 ) {
                            $("#add_in_playlist_modal").modal("hide");
                            toastr.success(result.msg);
                        }else{
                            toastr.error(result.msg);
                        }
                        $(".ms_ajax_loader").addClass('d-none');
                        $(".ms_add_in_playlist").show();
                    }
                });
            }else{
                toastr.error(dynamicKeys.no_song);   
            }
        }		
    });
    
    $(document).on('click','.ms_remove_user_playlist',function(){
        deletePopup().then((result) => {
            if (result.value) {
                var playlist = $(this).attr('data-list-id');
                var formdata ='playlistid='+playlist;
                var _this = $(this);
                $(".ms_ajax_loader").removeClass('d-none');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: userBaseUrl+'/remove_playlist',
                    data: formdata,
                    success: function(response){
                        var result = JSON.parse(response);
                        $(".ms_ajax_loader").addClass('d-none');
                        if( result.status === 1 ) {
                            toastr.success(result.msg);
                            _this.closest(".swiper-slide").remove();                            
                        }else{
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        })
	});

	$(document).on('click','.ms_share_music', function(){
        var share_uri = $(this).attr('data-shareuri');
    	var share_title = $(this).attr('data-sharename');
    	$("#add_in_playlist_modal").modal("hide");
    	$("#create_playlist_modal").modal("hide");
    	if(typeof share_uri != 'undefined'){
            if (window.innerWidth <= 640) {
                $(".ms_share_facebook").attr('href', 'https://www.facebook.com/sharer/sharer.php?u='+encodeURIComponent(share_uri));
                $(".ms_share_linkedin").attr('href', 'https://www.linkedin.com/sharing/share-offsite/?url='+encodeURIComponent(share_uri));
                $(".ms_share_twitter").attr('href', 'https://twitter.com/intent/tweet?text='+share_title+'&amp;url='+encodeURIComponent(share_uri)+'&amp;via=Musioo');
                $(".ms_share_googleplus").attr('href', 'https://plus.google.com/share?url='+encodeURIComponent(share_uri));
            }
            else {
                var width = 200;
                var height = 150;
                $(".ms_share_facebook").attr('onclick', "window.open('https://www.facebook.com/sharer/sharer.php?u="+encodeURIComponent(share_uri)+"', 'Share', width='" + width + "', height='" + height + "' )");
                $(".ms_share_linkedin").attr('onclick', "window.open('https://www.linkedin.com/sharing/share-offsite/?url="+encodeURIComponent(share_uri)+"', 'Share', width='" + width + "', height='" + height + "' )");
                $(".ms_share_twitter").attr('onclick', "window.open('https://twitter.com/intent/tweet?text="+share_title+"&url="+encodeURIComponent(share_uri)+"&via=Musioo' , 'Share', width='" + width + "', height='" + height + "' )");
                $(".ms_share_googleplus").attr('onclick', "window.open('https://plus.google.com/share?url="+encodeURIComponent(share_uri)+"', 'Share', width='" + width + "', height='" + height + "' )");
            }
            $("#ms_share_music_modal_id").modal("show");
        }
    	
    });

    $(document).on('click','.remove_user_playlist_music',function(){
        deletePopup().then((result) => {
            if (result.value) {
                var musicType = '';
                var sid = $(this).attr('musicid');
                var listid = $(this).attr('data-list-id');
                var type = $(this).attr("musictype");
                if(type?.length){            
                    musicType = 'ms_video';
                }else{           
                    musicType = 'ms_audio';
                } 

                var formdata ='songid='+sid+'&listid='+listid+'&musictype='+musicType;
                var _this = $(this);
                $(".ms_ajax_loader").removeClass('d-none');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: userBaseUrl+'/playlist/remove_track',
                    data: formdata,
                    success: function(response){
                        var result = JSON.parse(response);
                        $(".ms_ajax_loader").addClass('d-none');
                        if( result.status ) {
                            _this.closest(".ms_list_songs").remove();
                            if(musicType == 'ms_video'){
                                _this.closest(".playlistYtVideo").remove();
                                //$("a[data-musicid='"+sid+"']").parent().parent('ul').slideUp("slow", function() { $(this).remove();});
                            }
                            toastr.success(result.msg);
                        }else{
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        })
    });
    
    $(document).on('click','#change_pass',function() {
        if($('.change_password_slide').css('display') == 'block'){
            $( ".change_password_slide" ).slideUp( "slow" );
            $('#userPassword, #confirmPassword').removeClass('require');
        }else{
            $( ".change_password_slide" ).slideDown( "slow" );
            $('#userPassword, #confirmPassword').addClass('require');
        }
        $("#userPassword, #confirmPassword").val("");
    });

    $(document).on('click','#updateUserProfile', function(){
        var name = $('#user_name').val().trim();
        var password = $('#userPassword').val().trim();
        var cnfPassword = $('#confirmPassword').val().trim();

        if(name == ''){
            toastr.error(dynamicKeys.required_fields);
            $('#user_name').focus();
        }else{
            if(password === '' && cnfPassword !== ''){
                toastr.error(dynamicKeys.pass_err);
                $('#userPassword').focus();
            }else if(cnfPassword === '' && password !== ''){
                toastr.error(dynamicKeys.cnf_pass_err);
                $('#confirmPassword').focus();
            }else if(password != cnfPassword){
                toastr.error(dynamicKeys.cnf_mismatch);
                $('#confirmPassword').focus();
            }else{
                $(".ms_ajax_loader").removeClass('d-none');
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: userBaseUrl+'/update_profile/',
                    data : new FormData($('#updateUserForm')[0]),
                    processData: false,
                    contentType: false,
                    success: function(response){
                        var result = JSON.parse(response);
                        $(".ms_ajax_loader").addClass('d-none');
                        console.log(result);
                        if( result.status ){
                           
                        }else{
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        }
    })

    $(document).on('change','#basicImage',function(e){
        if($(this)[0].files.length){
            var file = $(this)[0].files[0];
            var ext = (typeof $(this).attr('data-ext') != 'undefined') ? eval($(this).attr('data-ext')) : ['jpg','jpeg','png','svg'];
            
            if(jQuery.inArray(file.name.split('.').pop().toLowerCase(), ext) == -1){
                toastr.error(dynamicKeys.only_allowed+ext.toString()+dynamicKeys.files);
                return false;
            }else{
                obUrl = URL.createObjectURL(file);
                $('#showuserProfileImage').attr('src', obUrl)
               
            }
        }
    })
    

    $(document).on('click','#purchasePlan', function(){
        if(checkUserId != ''){
            var payment = $('#startPayment').val().trim();
            if(payment == ''){
                return;
            }else{
                var amnt = $('#planDetail').data('amnt');
                var plan_id = $('#planDetail').data('id');
                if(payment == 'paypal' || payment == 'instamojo'){
                    if(payment == 'paypal'){
                        $('#paypal-form').attr('action', userBaseUrl+'/paypal');
                    }else if(payment == 'instamojo'){
                        $('#paypal-form').attr('action', userBaseUrl+'/payinstamojo');
                    }
                    $('#paypal-form').find('[name="plan_id"]').val(plan_id);
                    $('#paypal-form').find('[name="amount"]').val(amnt);
                    $('#paypal-form')[0].submit();
                }
                else if(payment == 'paystack'){
                    $('#paystack-form').find('[name="email"]').val($('#userEmail').val());
                    $('#paystack-form').find('[name="orderID"]').val(Math.random().toString(36).substring(2, 15) + Math.random().toString(36).substring(2, 15))
                    $('#paystack-form').find('[name="amount"]').val(amnt);
                    var arr = {'user_id' : $('#userID').val(), 'plan_id' : plan_id}
                    $('#paystack-form').find('#paystackmetadata').val(JSON.stringify(arr));
                    $('#paystack-form')[0].submit();
                    
                }
                else if(payment == 'paytm' || payment == 'instamojo'){
                    $('#paytm-form').find('[name="plan_id"]').val(plan_id);
                    $('#paytm-form').find('[name="amount"]').val(amnt);
                    $('#paytm-form')[0].submit();
                }
                else if(payment == 'instamojo'){
                    $('')
                }
            }
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    })

    $(document).on('change','.paymentMethod', function(){
        var payment = $(this).data('name');
        var disc = $('#disAmt').data('discount');
        $('.ms_profile_box').find('form').not('#applyCouponForm').addClass('d-none');
        $(".ms_card_wrapper, .braintree_card, .instamojo-form, #payu-form, #paytm-form, #paypal-form, #paystack-form, #razorpayForm, #manualpay-form").addClass('d-none');
        if(payment == 'paypal'){
            $('#paypal-form').removeClass('d-none');            
        }else if(payment == 'payumoney'){
            $('#payu-form').removeClass('d-none');
        }
        else if(payment == 'paytm'){
            $('#paytm-form').removeClass('d-none');
        }
        else if(payment == 'braintree'){
            $('.braintree_card').removeClass('d-none');
            $('#bt-form').removeClass('d-none');
        }
        else if(payment == 'instamojo'){
           $('.instamojo-form').removeClass('d-none');
        }
        else if(payment == 'paystack'){
            $('#paystack-form').removeClass('d-none');
        }
        else if(payment == 'razorpay'){
            $('#razorpayForm').removeClass('d-none');
        }
        else if(payment == 'stripe'){            
            $('.ms_card_wrapper').removeClass('d-none');
            $('.card_Detail').removeClass('d-none');
        }
        else if(payment == 'manual_pay'){
            $('#manualpay-form').removeClass('d-none');
        }
    });

    $(document).on('click','#razorpay_submit', function(){

        if(checkUserId != ''){            
            var amnt = Math.ceil($('.rzrPayableAmount').val());            
            var disc = $('.discountApplied').val();            
            var planId = $('[name="plan_id"]').val();
            var planExactAmnt = $('.planExactAmnt').val();
            var taxPercent = $('.taxPercent').val();
            var taxApplied = $('.taxApplied').val();
            
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/razorpay/proceed',
                data : {'amount' : amnt, 'plan_id' : planId, 'discount' : disc},
                success: function(response){
                    var result = JSON.parse(response);
                    
                    if(result.status == 1){
                        var options = {
                            "key": result.data.razorpay_key,
                            "amount": result.data.amount, 
                            "currency": result.data.currency,
                            "name": result.data.name,
                            "description": result.data.description,
                            "image": result.data.image,
                            "handler": function (response){

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'post',
                                    url: userBaseUrl+'/razorpay/payment',
                                    data : {'plan_id' : planId, 'razorpay_payment_id' : response.razorpay_payment_id,'discountApplied' : disc,'planExactAmnt' : planExactAmnt ,'taxPercent' : taxPercent,'taxApplied' : taxApplied, 'is_ajax' : 1},
                                    success: function(response){
                                        var res = JSON.parse(response);
                                        if(res.status){
                                            toastr.success(res.msg);
                                        }else{
                                            toastr.error(res.msg);
                                        }
                                        location.href = userBaseUrl+'/payment-single/'+res.plan_id;
                                    }
                                })
                            },
                            "prefill": {
                                "name": result.data.name,
                                "email": result.data.email,
                                "contact": '91'+result.data.contact
                            },
                            "theme": {
                                "color": result.data.color
                            }
                        };
                        new Razorpay(options).open();
                    }else{                        
                        toastr.error(result.msg);
                    }
                }
            })
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    });

    $(document).on('click','#buy_audio_by_razorpay', function(){

        if(checkUserId != ''){            
            var audioId = $(".buy_audio_id").val();
            var currency = $("#cur").val();                        
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/razorpay/buySingleAudio', 
                data : {'audio_id' : audioId, 'currency' : currency},                

                success: function(response){
                    var result = JSON.parse(response);                        
                    if(result.status == 1){
                        var options = {
                            "key": result.data.razorpay_key,
                            "amount": result.data.amount, 
                            "currency": "USD",
                            "name": result.data.name,
                            "description": result.data.description,
                            "image": result.data.image,
                            "handler": function (response){

                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'post',
                                    url: userBaseUrl+'/razorpay/singleaudio/payment',
                                    data : {'audio_id': audioId, 'razorpay_payment_id' : response.razorpay_payment_id, 'currency': currency ,'is_ajax' : 1},
                                    success: function(response){
                                        var res = JSON.parse(response);
                                        if(res.status){
                                            toastr.success(res.msg);
                                            setTimeout(function(){
                                                location.reload();                
                                            },500);
                                        }else{
                                            toastr.error(res.msg);
                                        }                                       
                                    }
                                })
                            },
                            "prefill": {
                                "name": result.data.name,
                                "email": result.data.email,
                                "contact": result.data.contact
                            },
                            "theme": {
                                "color": result.data.color
                            }
                        };
                        new Razorpay(options).open();
                    }else{
                        
                        toastr.error(result.msg);
                    }
                }
            })
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    });


    $(document).on('click','.payWithPaystack', function(e){

        e.preventDefault();
        if(checkUserId != ''){            
            var audioId = $(this).attr("audio-id");
            var currency = 'NGN';
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'post',
                url: userBaseUrl+'/paywithPaystack/buySingleAudio', 
                data : {'audio_id' : audioId},
                success: function(response){

                    var result = JSON.parse(response); 
                    if(result.status == 1){
                        var handler = PaystackPop.setup({
                            key: result.data.paystack_key,
                            email: result.data.email,
                            amount: result.data.amount,
                            currency: 'NGN',
                            ref: result.data.reference,
                            metadata: {
                                custom_fields: [{
                                    display_name: result.data.user_name,
                                    variable_name: result.data.name,
                                    value: result.data.contact,                                    
                                }]
                            },
                            callback: function(response){
                               
                                $.ajax({
                                    headers: {
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    type: 'post',
                                    url: userBaseUrl+'/paystack/callback/buySingleAudio',
                                    data : {'audio_id': audioId, 'transaction' : response.transaction, 'currency': currency ,'message': response.message, 'reference' : response.reference, 'status': response.status },
                                    success: function(response){
                                        var res = JSON.parse(response);
                                        if(res.status){
                                            toastr.success(res.msg);
                                            setTimeout(function(){
                                                location.reload();                
                                            },500);
                                        }else{
                                            toastr.error(res.msg);
                                        }                                       
                                    }
                                })
                            },
                        });
                        handler.openIframe();
                    }
                }
            })
        }
        
    });

    
    $(document).on('click','#saveCoupon', function(){
        if(checkUserId != ''){
            var couponCode = $('#couponCode').val().trim();
            if(couponCode == ''){
                toastr.error(dynamicKeys.coupon_err);
                $('#couponCode').focus();
            }else{
                var url = $(this).closest('form').attr('action'); 
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: 'post',
                    url: url,
                    data: {'coupon_code' : couponCode},
                    success: function(response){
                        var result = JSON.parse(response);
                        $(".ms_ajax_loader").addClass('d-none');
                        if( result.status ){

                            if(parseFloat($('#amountVal').val()) < parseFloat(result.dis_amount)){
                                toastr.error('This coupon is not applied for this plan'); return;
                            }
                            
                            $('.razorpayForm').html(result.formHtml);
                            var dis_amount = parseInt(result.dis_amount);
                            
                            $('#couponDis').html('-'+$('#cur').val()+dis_amount);
                            var amt = parseFloat($('#amountVal').val()) - parseFloat(dis_amount);
                            $('#subTtl').html($('#cur').val()+amt);
                            $('.discountApplied').val(dis_amount);
                            
                            var metadata = $('#paystack-form #paystackmetadata').val();
                            
                            var paystack = (typeof metadata != 'undefined' ? JSON.parse(metadata) : {});
                            paystack.discountApplied = dis_amount;
                            $('#paystack-form #paystackmetadata').val(JSON.stringify(paystack));
                           
                            if($('#taxApplied').val() != 0){
                                var appliedTax = parseInt(amt)*parseInt($('#taxApplied').val())/100;
                                $('#taxAmount').html($('#cur').val()+appliedTax);
                                $('.taxApplied').val(appliedTax);
                                var totalAmt = parseFloat(amt) + parseFloat(appliedTax);
                            }else{
                                var totalAmt = amt;
                            }
                            
                            $('#totalAmt').html($('#cur').val()+totalAmt);

                            $('.payableAmount').val(totalAmt);
                            $('.rzrPayableAmount').val(totalAmt*100);
                            
                            $('#scriptFinalAmount').attr('data-amount',totalAmt*100);

                            $('#disAmt').attr({'data-discount' : dis_amount, 'data-newamount' : result.amount});
                            if($('[data-name="razorpay"]').is(':checked') == true && dis_amount != ''){
                                $('.razorpay-form').addClass('d-none');
                                $('#razorpayForm').removeClass('d-none');
                            }
                            toastr.success(result.msg);                        
                        }else{
                            toastr.error(result.msg);
                        }
                    }
                });
            }
        }else{
            toastr.error(dynamicKeys.login_err);
        }
    });
    

    $(document).on('keyup', '#search_value', (e) => {
        if(e.keyCode == 13){
            $('.searchData').trigger('click');
        }
    });

    $(document).on('click','.searchData', function(){
        var searchVal = $('#search_value').val().trim();
        if(searchVal == ''){
            toastr.error(dynamicKeys.search_err);
        }else{
            $('.append_html_data').html('');
            $(".ms_ajax_loader").removeClass('d-none');
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'get',
                url: userBaseUrl+'/search/'+searchVal,
                
                success: function(response){ 
                                  
                    if(response.status == true){                        
                        $('.append_html_data').html(response.html);
                        setAllSliderData();    
                    }
                    setTimeout(function(){
                        $(".ms_ajax_loader").addClass('d-none');                    
                    },500);
                    window.history.pushState({href: userBaseUrl+'/search/'+searchVal}, '', userBaseUrl+'/search/'+searchVal);
                }
            }); 
        }
    })

    $(document).on('change','#userCurr', function(){
        var id = $(this).val();
        $(".ms_ajax_loader").removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: userBaseUrl+'/changeCurrency/'+id,
            
            success: function(response){
                $(".ms_ajax_loader").addClass('d-none');
                setTimeout(function(){
                    location.reload();                
                },100)
            }
        });     
    })

    function deletePopup(param=''){
        return Swal.fire({
            title : (param.title) ? param.title : dynamicKeys.are_u_sure,
            text: (param.text) ? param.text : dynamicKeys.delete_records,
            showCancelButton: true,
            confirmButtonText: (param.cnfButton) ? param.cnfButton : dynamicKeys.delete,
            showCloseButton: true
        });
    }
    
    $('.markAsReadNotification').on('click', function(){
        var id = $(this).attr('data-id');
        $(".ms_ajax_loader").removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: userBaseUrl+'/notification/mark_read/'+id,
            
            success: function(response){
                //console.log(response.status); return;
                $(".ms_ajax_loader").addClass('d-none');
                if(response.status == true){
                    toastr.success(response.msg);
                    setTimeout(function(){
                        location.reload();                
                    },100)
                    
                }else{
                    toastr.error(response.msg);
                }
            }
        });     
    });
    
    
    $('.removeNotiFromView').on('click', function(){
        var id = $(this).attr('data-id');
        $(".ms_ajax_loader").removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: userBaseUrl+'/notification/remove/'+id,
            
            success: function(response){
                
                $(".ms_ajax_loader").addClass('d-none');
                if(response.status == true){
                    toastr.success(response.msg);
                    setTimeout(function(){
                        location.reload();                
                    },100)
                    
                }else{
                    toastr.error(response.msg);
                }
            }
        });     
    });
    
    $('.clearAllNotification').on('click', function(){
        $(".ms_ajax_loader").removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: userBaseUrl+'/notification/remove_all',
            
            success: function(response){
                
                $(".ms_ajax_loader").addClass('d-none');
                if(response.status == true){
                    toastr.success(response.msg);
                    setTimeout(function(){
                        location.reload();                
                    },100)
                    
                }else{
                    toastr.error(response.msg);
                }
            }
        });     
    });
    
    $(document).on('click','.clearAllHistory', function(){
        $(".ms_ajax_loader").removeClass('d-none');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: userBaseUrl+'/clear_history',
            
            success: function(response){
                
                $(".ms_ajax_loader").addClass('d-none');
                if(response.status == true){
                    toastr.success(response.msg);
                    setTimeout(function(){
                        location.reload();                
                    },100)
                    
                }else{
                    toastr.error(response.msg);
                }
            }
        });     
    });
    
    
    $(document).on('change','.checkCoupons', function(e){
        var coupon = $('option:selected', this).attr('value');
        $('#couponCode').val(coupon);
        e.preventDefault(); 
    });
    

    $(document).on('click',".getAjaxRecord",function(e){

        e.preventDefault(); 
        $(".ms_ajax_loader").removeClass('d-none');       
        var url = $(this).attr('data-url');   
        var type =  $(this).attr('data-type');        
        $('.checkActive').removeClass('active'); 
        $('.getAjaxRecord[data-type='+type+']').addClass('active');        
        $('.append_html_data').html('');

        getAjaxRecord(url,type);
        
    });
    
    $(window).on('popstate', function(event) {
        
        event.preventDefault(); 
        $(".ms_ajax_loader").removeClass('d-none'); 
        var url = window.location.href;             
        getAjaxRecord(url);
    });
    
    // Delete Users Account Permanently
    $(".deleteAccountPermanent").on('click',function(){
        
        var user_id = $(this).attr('data-id');
        deletePopup({ 'title': dynamicKeys.are_u_sure, 'text': dynamicKeys.want_to_delete, 'cnfButton': dynamicKeys.ok }).then((result) => {
            if (result.value) {

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: userBaseUrl + '/deleteAccountPermanent',
                    method: 'post',
                    data: {'user_id': user_id},

                    success: function(resp){
                        toastr.success(resp.msg);
                        if(resp.status == true){
                            setTimeout(function(){
                                window.location.href = userBaseUrl+'/home';
                            },500);
                        }                
                    }
                });
            }
        });
    });
    

    $('.updateCardExpiryFormate').on('input', function(e){
        e.preventDefault();
        var dInput = this.value;
        if (dInput.length === 2){
            dInput = dInput + '/';
        }else{
            if (dInput.length === 3 && dInput.charAt(2) === '/'){
                dInput = dInput.replace('/', '');
            }
        }    
        $('.updateCardExpiryFormate').val(dInput);
    });
    
    $('.updateCardCvvFormate').on('input', function(e){
        e.preventDefault();
        var dInput = this.value;
        if (dInput.length > 3) {
            dInput = dInput.slice(0,3);
            $('.updateCardCvvFormate').val(dInput);
        }
    });
    
    // Delete Users Account Permanently
    $(document).on('click', ".deleteAccountPermanent" ,function(){
        
        var user_id = $(this).attr('data-id');
        deletePopup({ 'title': dynamicKeys.are_u_sure, 'text': dynamicKeys.want_to_delete, 'cnfButton': dynamicKeys.ok }).then((result) => {
            if (result.value) {

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: userBaseUrl + '/deleteAccountPermanent',
                    method: 'post',
                    data: {'user_id': user_id},

                    success: function(resp){
                        toastr.success(resp.msg);
                        if(resp.status == true){
                            setTimeout(function(){
                                window.location.href = userBaseUrl+'/home';
                            },500);
                        }                
                    }
                });
            }
        });
    });
    
        
        
    function getAjaxRecord(url = null, type = null){

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'get',
            url: url,
            
            success: function(response){ 
                              
                if(response.status == true){
                    if(type != 'home'){
                        $(".ms_main_wrapper").removeClass('ms_mainindex_wrapper');
                    }else{
                        $(".ms_main_wrapper").addClass('ms_mainindex_wrapper');
                    }
                    if(response?.type){
                        if(response.type != 'home'){
                            $(".ms_main_wrapper").removeClass('ms_mainindex_wrapper');
                        }else{
                            $(".ms_main_wrapper").addClass('ms_mainindex_wrapper');
                        }                        
                    }
                    $('.append_html_data').html(response.html);
                    setAllSliderData();    
                    // Scrollbar Start
                    $(".ms_nav_wrapper").mCustomScrollbar({
                        theme: "minimal"
                    });
            
                    // Song list Scrollbar
                    $(".ms_songslist_wrap .ms_songslist_box").mCustomScrollbar({
                        theme: "minimal",
                        setHeight: 610
                    });
            
                    // music list Scrollbar
                    $(".music_listwrap").mCustomScrollbar({
                        theme: "minimal",
                        setHeight: 350
                    });
            
                    // Queue Scrollbar
                    $(".jp_queue_list_inner").mCustomScrollbar({
                        theme: "minimal",
                        setHeight: 345
                    });
                    // Scrollbar End
                }
                window.history.pushState({href: url}, '', url);
                setTimeout(function(){
                    $(".ms_ajax_loader").addClass('d-none');                    
                },500);
            }
        }); 
    }


    function setAllSliderData(){
        
        var swiper = new Swiper('.trending_artist_slider .swiper-container', {
            slidesPerView: 7,
            spaceBetween: 26,
            loop: false,
            speed: 1500,
            navigation: {
                nextEl: '.swiper-button-next1',
                prevEl: '.swiper-button-prev1',
            },
            breakpoints: {
                1800: {
                    slidesPerView: 6,
                },
                1600: {
                    slidesPerView: 5,
                },
                1400: {
                    slidesPerView: 4,
                },
                1200: {
                    slidesPerView: 3,
                },
                992: {
                    slidesPerView: 4,
                    spaceBetween: 20,
                },
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20,
                },
                600: {
                    slidesPerView: 2,
                    spaceBetween: 20,
                },
                420: {
                    slidesPerView: 2,
                    spaceBetween: 0,
                }
            },
        });
        var swiper = new Swiper('.recommended_artist_slider .swiper-container', {
                slidesPerView: 7,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 6,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 20,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    600: {
                        slidesPerView: 2,
                        spaceBetween: 20,
                    },
                    420: {
                        slidesPerView: 2,
                        spaceBetween: 0,
                    }
                },
            });
        var swiper = new Swiper('.top_album_slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next1',
                    prevEl: '.swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 2,
                    },
                    420: {
                        slidesPerView: 2,
                    },
                },
            });
        var swiper = new Swiper('.recommended_album_slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 26,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 2,
                    },
                    420: {
                        slidesPerView: 2,
                    },
                },
            });
        var swiper = new Swiper('.play-list-slider .swiper-container', {
                slidesPerView: 6,
                spaceBetween: 20,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.play-list-slider .swiper-button-next1',
                    prevEl: '.play-list-slider .swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 5,
                    },
                    1600: {
                        slidesPerView: 5,
                    },
                    1400: {
                        slidesPerView: 4,
                    },
                    1200: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    768: {
                        slidesPerView: 2,
                        spaceBetween: 10,
                    },
                    640: {
                        slidesPerView: 2,
                    },
                    420: {
                        slidesPerView: 1,
                    },
                },
            });
        var swiper = new Swiper('.also_like_slider .swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next1',
                    prevEl: '.swiper-button-prev1',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 3,
                    },
                    1600: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1500: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    800: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    700: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    480: {
                        slidesPerView: 1,
                    }
                },
            });
        var swiper = new Swiper('.also_like_slider2 .swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: false,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next2',
                    prevEl: '.swiper-button-prev2',
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 3,
                    },
                    1600: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1500: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    800: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    700: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    480: {
                        slidesPerView: 1,
                    }
                },
            });
        var swiper = new Swiper('.music_center_slider .swiper-container', {
                effect: 'coverflow',
                grabCursor: true,
                centeredSlides: false,
                slidesPerView: 'auto',
                loop:false,
                speed:600,
                autoplay:false, 
                autoplay: {
                    delay: 2500,
                    disableOnInteraction: false,
                },
                grabCursor: true,
                effect: 'coverflow',
                coverflowEffect: {
                    rotate: 0,
                    stretch: 480,
                    depth: 300,
                },
                navigation: {
                    nextEl: '.swiper-music-next',
                    prevEl: '.swiper-music-prev',
                },
                breakpoints: {
                    
                    575: {
                        coverflowEffect: {
                            stretch: 300,
                        },
                    },
                    600: {
                        coverflowEffect: {
                            stretch: 400,
                        },
                    },
                    700: {
                        coverflowEffect: {
                            stretch: 450,
                        },
                    },
                    800: {
                        coverflowEffect: {
                            stretch: 500,
                        },
                    },
                    992: {
                        coverflowEffect: {
                            stretch: 600,
                        },
                    },
                    1200: {
                        coverflowEffect: {
                            stretch: 500,
                        },
                    }, 
                    1399: {
                        coverflowEffect: {
                            stretch: 700,
                        },
                    }, 
                }
            });

        // For Admin Added Playlist
        var playlistCount = $("#admin_playlist_count").val();+3;
        var c = parseInt(playlistCount)+3;       
        for (let i = 3; i < c; i++) {
            var swiper = new Swiper('.also_like_slider'+i+' .swiper-container', {
                slidesPerView: 4,
                spaceBetween: 30,
                loop: true,
                speed: 1500,
                navigation: {
                    nextEl: '.swiper-button-next'+i,
                    prevEl: '.swiper-button-prev'+i,
                },
                breakpoints: {
                    1800: {
                        slidesPerView: 3,
                    },
                    1600: {
                        slidesPerView: 3,
                        spaceBetween: 20,
                    },
                    1500: {
                        slidesPerView: 2,
                    },
                    1399: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    1024: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    992: {
                        slidesPerView: 4,
                        spaceBetween: 10,
                    },
                    800: {
                        slidesPerView: 3,
                        spaceBetween: 10,
                    },
                    700: {
                        slidesPerView: 2,
                        spaceBetween: 15,
                    },
                    480: {
                        slidesPerView: 1,
                    }
                },
            });
        }
    }

        $('#progress-bar').on('mouseup touchend', function (e) {        
            var newTime = player.getDuration() * (e.target.value / 100);       
            player.seekTo(newTime);  
            //updateProgressBar();          
        }); 

        $(document).on('mouseup touchend','#progress-bar', function (e) {        
            var newTime = player.getDuration() * (e.target.value / 100);       
            player.seekTo(newTime);  
            //updateProgressBar();          
        });        
        
        $(document).on('click','.yt-pause' ,function(){

            $(".yt_play_pause").removeClass('yt-pause').addClass('yt-play');
            $(this).find('i').removeClass('fa fa-pause').addClass('fa fa-play');   
            player.pauseVideo();
        });

        $(document).on('click', '.yt-play', function(){

            $(".yt_play_pause").removeClass('yt-play').addClass('yt-pause');
            $(this).find('i').removeClass('fa fa-play').addClass('fa fa-pause');   
            player.playVideo();        
        });

        // $(document).on('click',".yt-repeat",function(){        
        //     player.cueVideoById($("#currentVideoId").val());
        // });

        
        $(document).on('click','.yt-previous', function () {
            console.log('yt-previous');
            player.nextVideo()
        });
        $(document).on('click','.yt-next', function () {
            console.log('yt-next');
            player.nextVideo()
        });
        
        var idleTime = 0;
        $(document).ready(function () {
            // Increment the idle time counter every minute.
            var idleInterval = setInterval(timerIncrement, 30000); // 1 minute
    
            // Zero the idle timer on mouse movement.
            $(this).mousemove(function (e) {
                idleTime = 0;
            });
            $(this).keypress(function (e) {
                idleTime = 0;
            });
            //console.log(idleTime);
        });
    
        function timerIncrement() {
            idleTime = idleTime + 1;
            //console.log(idleTime);
            if (idleTime > 180) { 
                //alert('Click here to continue'); // 20 minutes
                window.location.reload();
            }
        }
    
})
