@section('title', __('adminWords.artist').' '.__('adminWords.dashboard'))
@extends('layouts.artist.main') 

@section('content') 
<!-- Container Start -->
       @php
        if(!empty($defaultCurrency->symbol)){
            $curr = $defaultCurrency->symbol; 
        }else{
            $curr = session()->get('currency')['symbol'];
        }
       @endphp
        <!-- Page Title Start -->
        <div class="row">
            <div class="colxl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                <div class="page-title-wrapper">
                    <div class="page-title-box">
                        <h4 class="page-title bold">{{ __('adminWords.dashboard') }}</h4>
                    </div>
                    <div class="breadcrumb-list">
                        <ul>
                            <li class="breadcrumb-link">
                                <a href="{{ route('artist.home') }}"><i class="fas fa-home mr-2"></i>{{ __('adminWords.dashboard') }}</a>
                            </li>
                            <li class="breadcrumb-link active">{{ __('adminWords.artist') }}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dashboard Start -->
        <div class="dashboard-info-boxes">            
            <!-- Artist Audio Count-->
            <div class="dashboard-info-sections">
                <div class="card ad-info-card">
                    <a href="{{ route('artist.audio') }}">
                        <div class="card-body dd-flex align-items-center">
                            <div class="icon-info">
                               <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg" id="XMLID_1043_"><g id="XMLID_694_"><path id="XMLID_697_" d="m255.985 210.001c-25.364 0-46 20.636-46 46s20.636 46 46 46 46-20.636 46-46-20.635-46-46-46zm0 72c-14.336 0-26-11.663-26-26s11.664-26 26-26 26 11.663 26 26-11.663 26-26 26z" fill="currentColor" data-original="#000000"/><path id="XMLID_726_" d="m255.985 162.001c-51.832 0-94 42.168-94 94s42.168 94 94 94 94-42.168 94-94-42.168-94-94-94zm0 168c-40.804 0-74-33.196-74-74s33.196-74 74-74 74 33.196 74 74-33.196 74-74 74z" fill="currentColor" data-original="#000000"/><path id="XMLID_729_" d="m511.594 7.232c-1.529-5.307-7.07-8.364-12.378-6.84l-50.946 14.682c-4.806.729-8.489 4.878-8.489 9.887v71.132c-4.718-2.913-10.05-4.921-15.756-5.775-21.235-21.549-45.991-38.6-73.657-50.684-29.824-13.027-61.579-19.633-94.382-19.633-63.038 0-122.303 24.548-166.877 69.123-44.574 44.574-69.123 103.839-69.123 166.877 0 30.368 5.656 59.777 16.803 87.595-7.116 2.872-13.783 7.182-19.543 12.941-22.976 22.977-22.976 60.362 0 83.339l69.196 69.196c1.953 1.952 4.512 2.929 7.071 2.929s5.119-.977 7.071-2.929l44.633-44.633c33.937 18.053 72.067 27.562 110.769 27.562 46.681 0 91.817-13.608 130.531-39.354 4.599-3.059 5.848-9.266 2.789-13.865-3.058-4.599-9.266-5.847-13.864-2.789-35.421 23.558-76.729 36.009-119.456 36.009-33.365 0-66.257-7.722-95.912-22.418l9.707-9.707c22.976-22.977 22.976-60.362 0-83.339-20.747-20.745-53.24-22.76-76.267-6.04-10.909-7.922-23.944-11.63-36.847-11.142-11.064-26.387-16.68-54.39-16.68-83.354 0-119.103 96.897-216 216-216 53.482 0 103.659 19.14 143.146 54.233-13.824 6.882-23.35 21.155-23.35 37.617 0 23.159 18.841 42 42 42 12.549 0 23.822-5.539 31.525-14.292 14.88 29.804 22.679 62.801 22.679 96.442 0 42.729-12.452 84.035-36.009 119.455-3.059 4.599-1.81 10.807 2.789 13.864 1.703 1.133 3.626 1.675 5.529 1.675 3.235 0 6.41-1.567 8.336-4.463 25.747-38.712 39.355-83.85 39.355-130.531 0-42.159-11.216-83.4-32.465-119.52.168-1.521.261-3.064.261-4.629v-99.28l44.973-12.961c5.305-1.532 8.367-7.073 6.838-12.38zm-411.011 363.448c15.179-15.178 39.876-15.177 55.054 0 15.178 15.178 15.178 39.875 0 55.054l-62.125 62.125-62.125-62.125c-15.178-15.179-15.178-39.876 0-55.054 7.589-7.589 17.559-11.384 27.527-11.384s19.938 3.795 27.527 11.384c3.906 3.904 10.237 3.904 14.142 0zm317.198-216.828c-12.131 0-22-9.869-22-22s9.869-22 22-22 22 9.869 22 22-9.869 22-22 22z" fill="currentColor" data-original="#000000"/><path id="XMLID_737_" d="m255.985 444.668c104.031 0 188.667-84.636 188.667-188.667 0-5.522-4.477-10-10-10s-10 4.478-10 10c0 93.003-75.664 168.667-168.667 168.667-5.523 0-10 4.478-10 10s4.477 10 10 10z" fill="currentColor" data-original="#000000"/><path id="XMLID_738_" d="m245.985 387.334c0 5.522 4.477 10 10 10 77.932 0 141.333-63.401 141.333-141.333 0-5.522-4.477-10-10-10s-10 4.478-10 10c0 66.903-54.43 121.333-121.333 121.333-5.523 0-10 4.478-10 10z" fill="currentColor" data-original="#000000"/><path id="XMLID_739_" d="m415.795 405.811c-2.63 0-5.21 1.061-7.08 2.92-1.86 1.87-2.92 4.44-2.92 7.08 0 2.63 1.06 5.21 2.92 7.07 1.87 1.86 4.44 2.93 7.08 2.93 2.63 0 5.21-1.069 7.07-2.93s2.93-4.44 2.93-7.07c0-2.64-1.07-5.21-2.93-7.08-1.86-1.86-4.44-2.92-7.07-2.92z" fill="currentColor" data-original="#000000"/></g></g></g></svg>
                            </div>
                            <div class="icon-info-text">
                                <h5 class="ad-title">{{ __('adminWords.song') }}</h5>
                                <h4 class="ad-card-title">{{ $countAudio }}</h4>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            
            <!-- Start Card-->
            <div class="dashboard-info-sections">
                <div class="card ad-info-card">
                    <a href="{{ route('artist.sales_history') }}">
                        <div class="card-body dd-flex align-items-center">
                            <div class="icon-info">
                               <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 494.3 494.3" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M416.163,313.3V136.2c0-5.5-4.5-9.3-10-9.3h-38.7c-5.5,0-10.3,3.8-10.3,9.3V296c-6.4,0.2-12.8,1-19,2.5V188.4    c0-5.5-4.7-9.5-10.2-9.5h-38.7c-5.5,0-10.1,3.9-10.1,9.5v149.3c-11.9,16.7-18.3,36.8-18.2,57.4c0,54.8,44.4,99.2,99.1,99.2    c54.8,0,99.2-44.4,99.2-99.1C459.263,362.4,443.163,331.8,416.163,313.3z M377.163,146.9h19v155.8c-6.1-2.4-12.5-4.2-19-5.2V146.9    z M299.163,198.9h19v106.5c-6.7,3.1-13.1,6.9-19,11.4V198.9z M428.263,435.4c-14.3,24.1-40.2,38.8-68.1,38.8    c-43.7,0-79.1-35.5-79.1-79.1c0-32,19.3-60.9,48.9-73.1c1.2-0.2,2.3-0.7,3.3-1.3c8.6-3.2,17.8-4.8,27-4.8c2.1,0,4.1,0.1,6.2,0.3    h0.1c12,0.9,23.7,4.6,34,10.8C438.162,349.3,450.563,397.8,428.263,435.4z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M360.263,387c-7,0-12.6-5.6-12.6-12.6s5.5-12.6,12.5-12.7c7,0,12.6,5.7,12.6,12.6c0,5.5,4.5,10,10,10    c5.6,0,10.1-4.4,10.2-10c0-13.4-8.2-25.5-20.7-30.4v-8.3c0-5.5-4.5-10-10-10s-10,4.5-10,10v7.2c-17.4,4.5-27.9,22.3-23.4,39.7    c3.6,14.4,16.5,24.4,31.3,24.4c6.9,0,12.5,5.7,12.5,12.6c0,6.9-5.7,12.5-12.6,12.5c-6.9,0-12.5-5.7-12.5-12.6c0-5.5-4.5-10-10-10    s-9.9,4.4-9.8,9.9v0.1c0.1,14.8,10.1,27.8,24.4,31.5v4.5c0,5.5,4.5,10,10,10s10-4.5,10-10v-5.5c16.8-6.6,25-25.5,18.5-42.2    C385.763,395.2,373.663,387,360.263,387z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M171.163,258.9h-39c-5.5,0-10,4.5-10,10v157c0,5.5,4.5,10,10,10h39c5.5,0,10-4.5,10-10v-157    C181.163,263.4,176.663,258.9,171.163,258.9z M161.163,416.9h-19v-138h19V416.9z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M250.163,235.9h-39c-5.5,0-10,4.5-10,10v181c0,5.5,4.5,10,10,10h39c5.5,0,10-4.5,10-10v-181    C260.163,240.4,255.663,235.9,250.163,235.9z M240.163,416.9h-19v-161h19V416.9z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M92.163,344.9h-39c-5.5,0-10,4.5-10,10v71c0,5.5,4.5,10,10,10h39c5.5,0,10-4.5,10-10v-71    C102.163,349.4,97.663,344.9,92.163,344.9z M82.163,416.9h-19v-52h19V416.9z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M414.663,0l-68.4,0.1c-5.5-0.1-10,4.4-10.1,9.9s4.4,10,9.9,10.1c0.1,0,0.1,0,0.2,0l44.3-0.1l-108.5,108.1l-37.9-37.6    c-1.9-1.8-4.5-2.7-7.1-2.6c-2.6-0.1-5.2,0.8-7.1,2.6l-192,192c-3.9,3.9-3.9,10.2,0,14c1.9,1.9,4.4,2.9,7.1,2.9s5.2-1,7.1-2.9    l185-185l37.9,38.2c1.8,2,4.4,3.1,7.1,3.2c2.7-0.1,5.2-1.3,7.1-3.2l115.4-115.5l-0.1,44.5c0,5.6,4.4,10.1,10,10.3    c5.6-0.1,10-4.7,10-10.3l0.1-68.6C424.663,4.6,420.163,0,414.663,0z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg>
                            </div>
                            <div class="icon-info-text">
                                <h5 class="ad-title">{{ __('adminWords.total_sales') }}</h5>
                                <h4 class="ad-card-title">{{ $curr.$totalSalesAmount }}</h4>
                            </div>
                        </div>
                    </a>    
                </div>
            </div>
            
            <div class="dashboard-info-sections">
                <div class="card ad-info-card">
                    <a href="{{ route('artist.payment_history') }}">
                        <div class="card-body dd-flex align-items-center">
                            <div class="icon-info">
                               <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 512.009 512.009" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><path xmlns="http://www.w3.org/2000/svg" d="m106 80.005c5.522 0 10-4.478 10-10v-20h20c5.522 0 10-4.478 10-10s-4.478-10-10-10h-20v-20c0-5.522-4.478-10-10-10s-10 4.478-10 10v20h-20c-5.522 0-10 4.478-10 10s4.478 10 10 10h20v20c0 5.522 4.478 10 10 10z" fill="currentColor" data-original="#000000"/><path xmlns="http://www.w3.org/2000/svg" d="m472 260.005c5.522 0 10-4.478 10-10v-20h20c5.522 0 10-4.478 10-10s-4.478-10-10-10h-20v-20c0-5.522-4.478-10-10-10s-10 4.478-10 10v20h-20c-5.522 0-10 4.478-10 10s4.478 10 10 10h20v20c0 5.522 4.478 10 10 10z" fill="currentColor" data-original="#000000"/><path xmlns="http://www.w3.org/2000/svg" d="m156 130.005c0 71.683 58.317 130 130 130s130-58.317 130-130-58.317-130-130-130-130 58.317-130 130zm20 0c0-57.283 44.015-104.471 100-109.541v40.807c-17.233 4.452-30 20.13-30 38.734 0 22.056 17.944 40 40 40 11.028 0 20 8.972 20 20s-8.972 20-20 20-20-8.972-20-20c0-5.522-4.478-10-10-10s-10 4.478-10 10c0 18.604 12.767 34.282 30 38.734v40.807c-55.985-5.07-100-52.258-100-109.541zm220 0c0 57.283-44.015 104.471-100 109.541v-40.807c17.233-4.452 30-20.13 30-38.734 0-22.056-17.944-40-40-40-11.028 0-20-8.972-20-20s8.972-20 20-20 20 8.972 20 20c0 5.522 4.478 10 10 10s10-4.478 10-10c0-18.604-12.767-34.282-30-38.734v-40.807c55.985 5.069 100 52.258 100 109.541z" fill="currentColor" data-original="#000000"/><circle xmlns="http://www.w3.org/2000/svg" cx="200" cy="488.003" r="10" fill="currentColor" data-original="#000000"/><path xmlns="http://www.w3.org/2000/svg" d="m447.024 331.137c-.251.199 4.207-3.49-85.48 70.867h-4.926c3.415-5.888 5.382-12.717 5.382-20 0-22.056-17.944-40-40-40h-97.782c-8.678-8.479-33.711-30-64.218-30-22.718 0-42.386 11.931-54.539 21.526-6.685-12.779-20.067-21.525-35.461-21.525h-60c-5.522 0-10 4.478-10 10v180c0 5.522 4.478 10 10 10h90c5.522 0 10-4.478 10-10v-18.262l43.963 7.078c5.46.876 10.585-2.831 11.463-8.283.878-5.453-2.831-10.585-8.283-11.463l-47.143-7.589v-107.392c6.949-6.707 27.164-24.09 50-24.09 28.15 0 52.322 26.418 52.55 26.671 1.897 2.118 4.606 3.329 7.45 3.329h102c11.028 0 20 8.972 20 20s-8.972 20-20 20h-87c-5.522 0-10 4.478-10 10s4.478 10 10 10h130.15c2.331 0 4.588-.814 6.383-2.302l87.952-72.921c8.625-6.715 20.833-5.406 27.844 3.007 7.174 8.603 5.97 21.451-2.809 28.545 0 0-109.143 85.334-109.218 85.396-21.962 18.233-49.777 28.274-78.322 28.274-15.211 0-22.948-2.426-50.975-6.624-5.464-.885-10.585 2.83-11.463 8.283s2.831 10.585 8.283 11.463c27.497 4.084 36.743 6.878 54.154 6.878 33.15 0 65.457-11.646 90.982-32.791 0 0 109.007-85.227 109.042-85.254 17.507-14.073 20.076-39.72 5.688-56.978-14.016-16.816-38.469-19.385-55.667-5.843zm-357.024 160.868h-70v-160h50c11.028 0 20 8.972 20 20z" fill="currentColor" data-original="#000000"/></g></svg>
                            </div>
                            <div class="icon-info-text">
                                <h5 class="ad-title">{{ __('adminWords.total_earnings') }}</h5>
                                <h4 class="ad-card-title">{{ $curr.$totalEarnAmount }}</h4>
                            </div>
                        </div>
                    </a>    
                </div>
            </div>
            <div class="dashboard-info-sections">
                <div class="card ad-info-card">
                    <a href="{{ route('artist.request_payment') }}">
                        <div class="card-body dd-flex align-items-center">
                            <div class="icon-info">
                               <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve"><g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M253.614,333.891c-14.336,0-25.999,11.663-25.999,25.999s11.664,25.999,25.999,25.999s25.999-11.663,25.999-25.999    S267.951,333.891,253.614,333.891z M253.614,365.89c-3.309,0-6-2.691-6-6s2.691-6,6-6c3.309,0,6,2.691,6,6    S256.923,365.89,253.614,365.89z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M333.878,300.75c-1.86-1.86-4.44-2.931-7.07-2.931c-2.63,0-5.21,1.07-7.07,2.931c-1.87,1.859-2.93,4.439-2.93,7.069    c0,2.631,1.06,5.211,2.93,7.07c1.86,1.861,4.44,2.93,7.07,2.93c2.63,0,5.21-1.069,7.07-2.93c1.86-1.859,2.93-4.439,2.93-7.07    C336.808,305.189,335.738,302.609,333.878,300.75z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M461.994,143.987h-10.781L310.158,2.932c-3.905-3.905-10.238-3.905-14.142,0L259.88,39.069L115.356,0.344    c-5.335-1.431-10.819,1.736-12.248,7.071L66.516,143.987H42.006c-23.158,0-41.999,18.84-41.999,41.999    c0,0.555,0.021,1.106,0.042,1.656c-0.023,0.275-0.042,0.551-0.042,0.832v273.528C0.007,489.57,22.437,512,50.006,512h357.989    c0.155,0,0.307-0.01,0.462-0.012h53.536c27.569,0,49.998-22.429,49.998-49.999V193.985    C511.992,166.415,489.563,143.987,461.994,143.987z M453.06,163.986h8.934c16.542,0,29.999,13.458,29.999,29.999v80.143    c0,13.069-10.633,23.701-23.701,23.701h-10.298v-39.846c0-27.221-21.869-49.415-48.959-49.973L453.06,163.986z M269.94,57.293    c0.001-0.001,0.002-0.003,0.003-0.004l33.145-33.145L433.854,154.91l-52.75,52.75h-18.294l23.928-23.929    c3.905-3.905,3.905-10.237,0-14.142c-8.095-8.095-8.095-21.265,0-29.36c3.905-3.905,3.905-10.237,0-14.143L331.91,71.26    c-3.905-3.905-10.237-3.905-14.143,0c-8.095,8.096-21.266,8.096-29.36,0c-3.905-3.905-10.238-3.905-14.142,0l-136.4,136.401    h-18.294L269.94,57.293z M240.511,170.091c-22.828,0-41.954,16.119-46.619,37.57h-27.745L282.213,91.595    c12.811,7.636,28.937,7.636,41.748,0l42.441,42.441c-7.635,12.81-7.635,28.938,0,41.748l-31.877,31.877H287.13    C282.465,186.209,263.34,170.091,240.511,170.091z M266.297,207.661h-51.568c4.058-10.277,14.083-17.57,25.784-17.57    S262.238,197.384,266.297,207.661z M109.002,189.946l26.482-98.833c7.261,0.117,14.484-1.737,20.953-5.474    c6.47-3.735,11.676-9.046,15.217-15.409l45.005,12.059L151.348,147.6L109.002,189.946z M119.839,22.25L243.55,55.398    L232.989,65.96l-64.63-17.318c-2.563-0.687-5.292-0.327-7.588,0.999c-2.297,1.326-3.973,3.51-4.659,6.072    c-1.436,5.356-4.871,9.833-9.673,12.606c-4.803,2.773-10.398,3.509-15.755,2.074c-5.333-1.43-10.818,1.736-12.247,7.071    L83.463,207.985H70.071L119.839,22.25z M42.006,163.986h19.149l-11.79,43.999h-7.36c-12.131,0-21.999-9.869-21.999-21.999    C20.007,173.855,29.877,163.986,42.006,163.986z M491.993,461.989c0,16.542-13.458,29.999-29.999,29.999h-14.018    c6.287-8.36,10.018-18.745,10.018-29.987v-40.176h6c10.444,0,20.111-3.363,27.999-9.049V461.989z M491.993,373.827    c0,15.439-12.561,27.999-27.999,27.999H254c-23.158,0-41.999-18.84-41.999-41.999s18.84-41.999,41.999-41.999h30.306    c5.523,0,10-4.478,10-10s-4.477-10-10-10H254c-34.186,0-61.998,27.812-61.998,61.998c0,34.186,27.812,61.998,61.998,61.998    h183.995v40.176c0,16.387-13.209,29.75-29.537,29.999H68.005V379.39c0-5.522-4.477-10-10-10s-10,4.478-10,10v112.536    c-15.612-1.033-27.999-14.056-27.999-29.924V221.742c6.403,3.954,13.938,6.242,21.999,6.242h365.989    c16.542,0,29.999,13.458,29.999,29.999v39.846H365.69c-5.523,0-10,4.478-10,10s4.477,10,10,10h102.602    c8.733,0,16.869-2.585,23.701-7.015V373.827z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"><g><path d="M65.076,333.318c-1.86-1.859-4.44-2.93-7.07-2.93s-5.21,1.07-7.07,2.93c-1.86,1.86-2.93,4.44-2.93,7.07    c0,2.63,1.07,5.21,2.93,7.07c1.86,1.86,4.44,2.93,7.07,2.93c2.63,0,5.21-1.07,7.07-2.93c1.86-1.86,2.93-4.44,2.93-7.07    C68.005,337.758,66.935,335.178,65.076,333.318z" fill="currentColor" data-original="#000000"/></g></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g><g xmlns="http://www.w3.org/2000/svg"></g></g></svg>
                            </div>
                            <div class="icon-info-text">
                                <h5 class="ad-title">{{ __('adminWords.balance') }}</h5>
                                <h4 class="ad-card-title">{{ getArtistWithdrawBalance() }}</h4>
                            </div>
                        </div>
                    </a>    
                </div>
            </div>
        </div> 
        
        <div class="dash-graph-wrapper">
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12">
                    <div class="card chart-card">
                        <div class="card-header">
                            <h4 class="has-btn">
                                {{ __('adminWords.stream_count_in').' '.date('Y') }}
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xl-12 col-lg-12 col-md-12">
                                    <div class="chart-holder">
                                        <div id="chartAM"></div>
                                        <input type="hidden" id="artistStreamCount" value="{{ json_encode($artistAudioStreamCount) }}" data-label="{{ __('adminWords.stream_count_in').' '.date('Y') }}"> 
                                    </div> 
                                </div>                                    
                            </div>
                        </div>
                    </div>
                </div>                         
            </div>
        </div>

        <div class="dash-recent-data-wrapper">
            
             <!-- HTML New -->
            <div class="row">
                <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12">
                    <div class="card chart-card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12">
                                    <h4>{{ __('adminWords.recently_added').' '.__('adminWords.audio') }}</h4> 
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="tab-pane fade show active" id="tabA" role="tabpanel" aria-labelledby="tabA-tab">
                                    <div class="tab-wrapper">
                                        <div class="content-holder audio-alubm-wrapper">
                                            <div class="table-responsive">
                                                <table class="table table-styled mb-0">
                                                    <tbody>
                                                        @php 
                                                            $song = 1; 
                                                            $albm = 1; 
                                                            $user = 1;
                                                        @endphp
                                                        @if(sizeof($recent_track) > 0)
                                                            @foreach($recent_track as $track)
                                                                <tr>
                                                                    <td>
                                                                        {{ $song++ }}
                                                                    </td>
                                                                    <td>
                                                                        <span class="img-thumb">
                                                                            <img src="{{ asset('public/images/audio/thumb/'.$track->image) }}" alt="">
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-desc">
                                                                            <span>{{ $track->audio_title }}</span>
                                                                        </span>
                                                                    </td>
                                                                    <td class="text-right">
                                                                        <label class="mb-0 badge badge-success toltiped " title="" data-original-title="{{ date('d-m-Y', strtotime($track->created_at)) }}">
                                                                            {{ date('d-m-Y', strtotime($track->created_at)) }}
                                                                        </label>
                                                                    </td>
                                                                </tr>
                                                            @endforeach
                                                        @else
                                                            <p>{{ __('frontWords.no_track') }}</p>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                            <div class="view-btn-wrap">
                                                @if(sizeof($recent_track) > 0)
                                                    <a href="{{ route('artist.audio') }}" class="effect-btn btn btn-primary mt-2">
                                                        {{ __('adminWords.view_all').' '.__('adminWords.audio') }}
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>
                                       
            </div>
        </div>

@endsection 

@section('script')
    <script src="{{ asset('public/assets/js/admin/apexcharts.min.js') }}"></script>
    <script src="{{ asset('public/assets/js/admin/artist-chart-apexcharts.js') }}"></script>
@endsection
