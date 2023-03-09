@extends('layouts.admin.main')
@section('title', __('adminWords.site_setting'))

@section('content')            

<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.site_setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.site_setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">  
	<div class="row">
		<div class="col-lg-12">
			<div class="card m-b-30">
				<div class="card-header">                                
					<div class="row align-items-center">
						<div class="col-6">
							<h5 class="card-title mb-0">{{ __('adminWords.create').' '.__('adminWords.site_setting') }}</h5>
						</div>
					</div>
				</div>
				<div class="card-body" id="ms_card_fimg">
				  {!! Form::open(['method' => 'POST','files' => true, 'route' => 'site.update','data-redirect' => route('site') ]) !!}
						<div class="row">
						  	<div class="col-lg-6">
						  	    
								<div class="form-group{{ $errors->has('w_title') ? ' has-error' : '' }}">
									<label for="w_title">{{  __('adminWords.website_title') }}<sup>*</sup></label>
								  	{!! Form::text('w_title', isset($settings['w_title']) ? $settings['w_title'] : null, ['class' => 'form-control require', 'placeholder' => __('adminWords.enter').' '.__('adminWords.website_title')]) !!}
								  <small class="text-danger">{{ $errors->first('w_title') }}</small>
								</div>													

							</div>
							
							<div class="col-lg-6">
								<div class="img-upload-preview">
									<div class="row">
										<div class="col-md-6">
											<div class="form-group{{ $errors->has('logo') ? ' has-error' : '' }} input-file-block label_full">
											<label for="logo">Mini {{  __('adminWords.logo') }}<sup>*</sup></label>
											<label for="minilogo" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Website Mini Logo">
												{!! Form::file('mini_logo', ['class' => 'input-file hide basicImage '.(isset($settings['mini_logo']) && $settings['mini_logo'] != null ? '' : 'require'), 'id'=>'minilogo', 'data-id'=>'showLogo', 'data-label'=>'logoLabel']) !!}
												<span class="js-fileName"></span>
											</label>
											<span class="info" id="minLogoLabel">{{ (isset($settings['mini_logo']) && $settings['mini_logo'] != '' ? $settings['mini_logo'] : '' ) }}</span>
											<small class="text-danger">{{ $errors->first('logo') }}</small>
											<p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 60X60 px' }} </p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="image-block site_image_dv">
												@if(isset($settings['mini_logo']) && $settings['mini_logo'] != null) 
													<img src="{{asset('public/images/sites/'.$settings['mini_logo'])}}" class="img-responsive" alt="">
												@else 
													<img src="" id="showLogo" class="img-responsive" alt="">
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-lg-6">
								<div class="img-upload-preview">
									<div class="row">
									  	<div class="col-md-6">
											<div class="form-group{{ $errors->has('favicon') ? ' has-error' : '' }} input-file-block label_full">
												<label for="favicon">{{  __('adminWords.website_fav') }}<sup>*</sup></label>
												<label for="favicon" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Website Favicon">
													{!! Form::file('favicon', ['class' => 'input-file hide basicImage'.(isset($settings['favicon']) && $settings['favicon'] != null ? '' : 'require'), 'id'=>'favicon', 'data-id'=>'showFavicon', 'data-label'=>"faviconLabel", 'data-ext' => "['jpg','jpeg','png','svg', 'ico']"]) !!}	
													<span class="js-fileName"></span>
												</label>
												<span class="info" id="faviconLabel">{{ (isset($settings['favicon']) && $settings['favicon'] != '' ? $settings['favicon'] : '') }}</span>
												<small class="text-danger">{{ $errors->first('favicon') }}</small>
												<p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 34X34 px' }} </p>
											</div>
								  		</div>
										<div class="col-md-6">
											<div class="image-block site_image_dv">
												@if(isset($settings['favicon']) && $settings['favicon'] != null) 
													<img src="{{asset('public/images/sites/' . $settings['favicon'])}}" class="img-responsive" alt="">
												@else 
													<img src="" id="showFavicon" class="img-responsive" alt="">
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-lg-6">
								<div class="img-upload-preview">
									<div class="row">
									  	<div class="col-md-6">
											<div class="form-group{{ $errors->has('preloader') ? ' has-error' : '' }} input-file-block label_full">
											  {!! Form::label('preloader', __('adminWords.website_preloader') ) !!}
											  <label for="preloader" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Website Preloader">
										  		{!! Form::file('preloader', ['class' => 'input-file hide basicImage '.(isset($settings['preloader']) && 	$settings['preloader'] != null ? '' : 'require'), 'id'=>'preloader', 'data-id'=>"showPreloader", 'data-label'=>'preloaderLabel', 'data-ext' => "['jpg','jpeg','png','svg', 'gif']"]) !!}
												<span class="js-fileName"></span>
											  </label>
											  <span class="info" id="preloaderLabel">{{ (isset($settings['preloader']) && $settings['preloader'] != '' ? $settings['preloader'] : '' ) }}</span>
											  <small class="text-danger">{{ $errors->first('preloader') }}</small>
											  <p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 100X100 px' }} </p>
											</div>
									  	</div>
										<div class="col-md-6">
											<div class="image-block site_image_dv">
												@if(isset($settings['preloader']) && $settings['preloader'] != null)
													<img src="{{asset('public/images/sites/'.$settings['preloader'])}}" class="img-responsive" alt="">
												@else 
													<img src="" id="showPreloader" class="img-responsive" alt="">
												@endif
											</div>
										</div>
									</div>									
								</div>
							</div>			
							
							<div class="col-lg-6">
								<div class="img-upload-preview">
									<div class="row">
										<div class="col-md-6"> 
											<div class="form-group{{ $errors->has('large_logo') ? ' has-error' : '' }} input-file-block label_full">
											<label for="logo">Large {{  __('adminWords.logo') }}<sup>*</sup></label>
											<label for="largeLogo" class="file-upload-wrapper js-labelFile" data-text="Select your file!" data-toggle="tooltip" data-original-title="Website Large Logo">
												{!! Form::file('large_logo', ['class' => 'input-file hide basicImage '.(isset($settings['large_logo']) && $settings['large_logo'] != null ? '' : 'require'), 'id'=>'largeLogo', 'data-id'=>'showLogo', 'data-label'=>'logoLabel']) !!}
												<span class="js-fileName"></span>
											</label>
											<span class="info" id="logoLabel">{{ (isset($settings['large_logo']) && $settings['large_logo'] != '' ? $settings['large_logo'] : '' ) }}</span>
											<small class="text-danger">{{ $errors->first('logo') }}</small>
											<p class="note_tooltip">Note: {{ __('adminWords.recommended').' size - 150X75 px' }} </p>
											</div>
										</div>
										<div class="col-md-6">
											<div class="image-block site_image_dv">
												@if(isset($settings['large_logo']) && $settings['large_logo'] != null) 
													<img src="{{asset('public/images/sites/'.$settings['large_logo'])}}" class="img-responsive" alt="">
												@else 
													<img src="" id="showLogo" class="img-responsive" alt="">
												@endif
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div class="col-lg-6">
								<div class="form-group{{ $errors->has('is_preloader') ? ' has-error' : '' }}  mb-0 dd-flex">
		                            <div class="checkbox mr-4 ml-4">
		                                {!! Form::checkbox('is_preloader', 1, (isset($settings['is_preloader']) && $settings['is_preloader'] == 1 ? 1 : 0),['id'=>'is_preloader']) !!}
								        <label for="is_preloader">{{ __('adminWords.select_to_show').' '.__('adminWords.website_preloader') }}</label>
		                                <small class="text-danger">{{ $errors->first('is_preloader') }}</small>
		                            </div> 
		                        </div>
		                        
		                        <div class="form-group{{ $errors->has('is_artist_register') ? ' has-error' : '' }}  mb-0 dd-flex">
		                            <div class="checkbox mr-4 ml-4">
		                                {!! Form::checkbox('is_artist_register', 1, (isset($settings['is_artist_register']) && $settings['is_artist_register'] == 1 ? 1 : 0),['id'=>'is_artist_register']) !!}
								        <label for="is_artist_register"> {{__('adminWords.is_artist_register') }}</label>
		                                <small class="text-danger">{{ $errors->first('is_artist_register') }}</small>
		                            </div> 
		                        </div>
		                        
						  	</div>
								  	
						  	
							<div class="col-lg-12 mt-3"> 
									<button type="button" class="effect-btn btn btn-primary" data-action="submitThisForm">{{ __('adminWords.save_setting_btn') }}</button>
								<div class="clear-both"></div>
							</div>
						</div>
				  {!! Form::close() !!}
			  </div>
			</div>
		</div>
	</div>
</div>
@endsection 
@section('script')
	<script src="{{ asset('assets/js/musioo-custom.js') }}"></script>  
@endsection