@extends('layouts.admin.main')
@section('title', __('adminWords.admin').' '.__('adminWords.setting'))
@section('style')
    <link href="{{ asset('public/assets/plugins/select2/select2.min.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')
               
<div class="row">
    <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
        <div class="page-title-wrapper">
            <div class="page-title-box">
                <h4 class="page-title bold">{{ __('adminWords.admin').' '.__('adminWords.setting') }}</h4>
            </div>
            <div class="musioo-brdcrmb breadcrumb-list">
                <ul>
                    <li class="breadcrumb-link">
                        <a href="{{url('/admin')}}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.home') }}</a>
                    </li>
                    <li class="breadcrumb-link active">{{ __('adminWords.admin').' '.__('adminWords.setting') }}</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="contentbar">                
    <div class="row">
        <div class="col-lg-12">
            <form action="{{ url('dashbord-setting') }}" method="POST">
                <div class="table-responsive ">
                    <table class="table table-styled">
                        <thead>
                            <tr>
                                <th>{{ __('adminWords.widget_name') }}</th>
                                <th>{{ __('adminWords.status') }}</th>
                                <th>{{ __('adminWords.max_item') }}</th> 
                            </tr>
                        </thead>

                        <tbody>
                            <tr>
                                <td>{{ __('adminWords.latest_subs') }}</td>
                                <td>
                                    <div class="checkbox success-check">
                                        <input id="switch1" class="enableDisableSettngStatus" {{ (!empty($settings) && isset($settings['latest_subs']) && $settings['latest_subs'] == 1 ? 'checked' : '' ) }} name="latest_subs" type="checkbox">
                                        <label for="switch1"></label>
                                    </div>
                                </td>
                                <td class="numberOfItem {{ (!empty($settings) && isset($settings['latest_subs']) && $settings['latest_subs'] == 0 ? 'd-none' : (!empty($settings) && !isset($settings['latest_subs']) ? 'd-none' : '') ) }}">
                                    <input class="wid require" min="1" name="max_latest_subs" type="number" value="{{ (!empty($settings) && isset($settings['latest_subs']) && $settings['latest_subs'] == 1 ? $settings['max_latest_subs'] : 1 ) }}">
                                </td>                        
                            </tr>

                            <tr>
                                <td>{{ __('frontWords.recently_added').' '.__('frontWords.track') }}</td>
                                <td>
                                    <div class="checkbox success-check">
                                        <input id="switch2" class="enableDisableSettngStatus" {{ (!empty($settings) && isset($settings['rcnt_add_track']) && $settings['rcnt_add_track'] == 1 ? 'checked' : '' ) }}  name="rcnt_add_track" type="checkbox">
                                        <label for="switch2"></label>
                                    </div>
                                </td>
                    
                                <td class="numberOfItem {{ (!empty($settings) && isset($settings['rcnt_add_track']) && $settings['rcnt_add_track'] == 0 ? 'd-none' : (!empty($settings) && !isset($settings['rcnt_add_track']) ? 'd-none' : '') ) }}">
                                    <input class="wid require" min="1" name="max_rcnt_add_track" type="number" value="{{ (!empty($settings) && isset($settings['rcnt_add_track']) && $settings['rcnt_add_track'] == 1 ? $settings['max_rcnt_add_track'] : 1 ) }}">
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('frontWords.recently_added').' '.__('frontWords.album') }}</td>
                                <td>
                                    <div class="checkbox success-check">
                                        <input id="switch3" class="enableDisableSettngStatus" {{ (!empty($settings) && isset($settings['rcnt_add_album']) && $settings['rcnt_add_album'] == 1 ? 'checked' : 0 ) }} name="rcnt_add_album" type="checkbox">
                                        <label for="switch3"></label>
                                    </div>
                                </td>
                    
                                <td class="numberOfItem {{ (!empty($settings) && isset($settings['rcnt_add_album']) && $settings['rcnt_add_album'] == 0 ? 'd-none' : (!empty($settings) && !isset($settings['rcnt_add_album']) ? 'd-none' : '') ) }}">
                                    <input class="wid require" min="1" name="max_rcnt_add_album" type="number" value="{{ (!empty($settings) && isset($settings['rcnt_add_album']) && $settings['rcnt_add_album'] == 1 ? $settings['max_rcnt_add_album'] : 1 ) }}">
                                </td>
                            </tr>

                            <tr>
                                <td>{{ __('frontWords.recent_user') }}</td>
                                <td>
                                    <div class="checkbox success-check">
                                        <input id="switch4" class="enableDisableSettngStatus" {{ (!empty($settings) && isset($settings['rcnt_add_user']) && $settings['rcnt_add_user'] == 1 ? 'checked' : '' ) }} name="rcnt_add_user" type="checkbox">
                                        <label for="switch4"></label>
                                    </div>
                                </td>
                    
                                <td class="numberOfItem {{ (!empty($settings) && isset($settings['rcnt_add_user']) && $settings['rcnt_add_user'] == 0 ? 'd-none' : (!empty($settings) && !isset($settings['rcnt_add_user']) ? 'd-none' : '') ) }}">
                                    <input class="wid require" min="1" name="max_rcnt_add_user" type="number" value="{{ (!empty($settings) && isset($settings['rcnt_add_user']) && $settings['rcnt_add_user'] == 1 ? $settings['max_rcnt_add_user'] : 1 ) }}">
                                </td>                        
                            </tr>

                            <tr>
                                <td>{{ __('adminWords.select').' '.__('adminWords.home') }}</td>
                                <td>
                                    <div class="checkbox success-check">
                                    </div>
                                </td>
                    
                                <td>
                                    <select name="is_dashboard" class="wid form-control select2WithSearch dashboard_id" placeholder= "{{ __('adminWords.select').' '.__('adminWords.home') }}" id = "home" ]>
                                        <option value="">{{ __('adminWords.select').' '.__('adminWords.home') }}</option>
                                        <option value="dashboard" @if(isset($settings['is_dashboard'])) {{ $settings['is_dashboard'] == 'dashboard' ? "selected" : "" }} @endif>
                                            {{ __('adminWords.home').' 1' }}
                                        </option>
                                        <option value="dashboard_2" @if(isset($settings['is_dashboard'])) {{ $settings['is_dashboard'] == 'dashboard_2' ? "selected" : "" }} @endif>
                                            {{ __('adminWords.home').' 2' }}
                                        </option>
                                    </select>
                                </td>                        
                            </tr>

                        </tbody>
                    </table> 

                    <button type="button" data-action="submitThisForm" class="effect-btn btn btn-primary mt-2 mr-2"> {{ __('adminWords.save_setting_btn') }}</button>                   
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
@section('script')
    <script src="{{ asset('public/assets/js/musioo-custom.js') }}"></script>  
    <script src="{{ asset('public/assets/plugins/select2/select2.min.js') }}"></script> 
@endsection 