"use strict";

$(function() {


    // MUSIOO CUSTOM JS FILE FOR ADMIN ONLY

    var start = moment().subtract(29, 'days');
    var end = moment();
    var filterStartDate = '';
    var filterEndDate = '';


    function updateDateRange(start, end) {
        filterStartDate = start;
        filterEndDate = end;
        $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
    }

    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        setDate: [moment().subtract(29, 'days'), moment()],
        ranges: {
            'Today': [moment(), moment()],
            'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days': [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month': [moment().startOf('month'), moment().endOf('month')],
            'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
            'Last Year': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')]
        }
    }, updateDateRange);

    updateDateRange(start, end);

    if ($('.musiooDtToShowData').length) {

        var url = $('.musiooDtToShowData').attr('data-url');
        var method = $('.musiooDtToShowData').attr('data-method');

        var rowClass = '';
        if ($('#usersForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'name', name: 'name' }, { data: 'email', name: 'email' }, { data: 'plan', name: 'plan' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3] } }];

        } else if ($('#locationForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'country', name: 'country' }, { data: 'iso', name: 'iso' }, { data: 'iso3', name: 'iso3' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3] } }];

        } else if ($('#faqsForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'question', name: 'question' }, { data: 'answer', name: 'answer' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2] } }];

        } else if ($('#blogCategoryForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'title', name: 'title' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2] } }];

        } else if ($('#blogsDataForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'title', name: 'title' }, { data: 'detail', name: 'detail' }, { data: 'category', name: 'category' }, { data: 'created_at', name: 'created_at' }, { data: 'is_active', name: 'is_active' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4] } }];

        } else if ($('#pagesDataForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'title', name: 'title' }, { data: 'detail', name: 'detail' }, { data: 'created_at', name: 'created_at' }, { data: 'is_active', name: 'is_active' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3] } }];

        } else if ($('#testimonailsForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'client_name', name: 'client_name' }, { data: 'designation', name: 'designation' }, { data: 'rating', name: 'rating' }, { data: 'detail', name: 'detail' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 5] } }];

        } else if ($('#stateForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'state', name: 'state' }, { data: 'country', name: 'country' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [0, 1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [0, 1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [0, 1, 2] } }];

        } else if ($('#cityForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'city', name: 'city' }, { data: 'state', name: 'state' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [0, 1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [0, 1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [0, 1, 2] } }];

        } else if ($('#sliderDataForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'title', name: 'title' }, { data: 'link', name: 'link' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4] } }];
            var rowClass = function(row, data, dataIndex) {
                $(row).addClass('sortSlider');
                $(row).attr('data-id', data['id'])
            }

        } else if ($('#albumForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'album_name', name: 'album_name' }, { data: 'copyright', name: 'copyright' }, { data: 'album_movie', name: 'album_movie' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 5] } }];

        } else if ($('#audioForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'audio_title', name: 'audio_title' }, { data: 'price', name: 'price' }, { data: 'audio_genre', name: 'audio_genre' }, { data: 'artist_name', name: 'artist_name' }, { data: 'language', name: 'language' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 5, 6] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 5, 6] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 5, 6] } }];

        } else if ($('#audioGenreForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'genre_name', name: 'genre_name' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3] } }];

        } else if ($('#artistForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'artist_name', name: 'artist_name' }, { data: 'genre_name', name: 'genre_name' }, { data: 'created_at', name: 'created_at' }, { data: 'balance', name: 'balance' }, { data: 'verify_status', name: 'verify_status' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 5] } }];

        } else if ($('#artistGenreForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'genre_name', name: 'genre_name' }, { data: 'created_at', name: 'created_at' }, { "class": "relative", "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3] } }];

        } else if ($('#notificationForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'user_name', name: 'user_name' }, { data: 'message', name: 'message' }, { data: 'created_at', name: 'created_at' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4] } }];

        } else if ($('#languageForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'language_name', name: 'language_name' }, { data: 'language_code', name: 'language_code' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3] } }];

        } else if ($('#planForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'plan_name', name: 'plan_name' }, { data: 'plan_amount', name: 'plan_amount' }, { data: 'validity', name: 'validity' }, { data: 'is_download', name: 'is_download' }, { data: 'show_advertisement', name: 'show_advertisement' }, { data: 'status', name: 'status' }, { data: 'created_at', name: 'created_at' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 8] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 8] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 8] } }];

        } else if ($('#radioForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'radio_name', name: 'radio_name' }, { data: 'copyright', name: 'copyright' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4] } }];

        } else if ($('#commentDataForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'user_name', name: 'user_name' }, { data: 'message', name: 'message' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3] } }];

        } else if ($('#couponForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'coupon_code', name: 'coupon_code' }, { data: 'description', name: 'description' }, { data: 'coupon_used_count', name: 'coupon_used_count' }, { data: 'applicable_on', name: 'applicable_on' }, { data: 'starting_date', name: 'starting_date' }, { data: 'expiry_date', name: 'expiry_date' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];

        } else if ($('#advForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'title', name: 'title' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2] } }];

        } else if ($('#menuForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'menu_heading', name: 'menu_heading' }, { data: 'page_name', name: 'page_name' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2] } }];

        } else if ($('#adminDataForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'menu_heading', name: 'menu_heading' }, { data: 'page_name', name: 'page_name' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];

        } else if ($('#manualPayForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'payment_proof', name: 'payment_proof' }, { data: 'order_id', name: 'order_id' }, { data: 'user_name', name: 'user_name' }, { data: 'plan_name', name: 'plan_name' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6] } }];

        } else if ($('#subscriptionForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'name', name: 'name' }, { data: 'qty', name: 'qty' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];

        }else if ($('#salesHistoryForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'user_name', name: 'user_name' }, { data: 'audio_name', name: 'audio_name' }, { data: 'artist_name', name: 'artist_name' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];

        }else if ($('#artistSalesHistoryForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'user_name', name: 'user_name' }, { data: 'audio_name', name: 'audio_name' }, { data: 'amount', name: 'amount' }, { data: 'payment_method', name: 'payment_method' }, { data: 'commission', name: 'commission' }, { data: 'tax_rate', name: 'tax_rate' }, { data: 'total_tax', name: 'total_tax' }, { data: 'earning', name: 'earning' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }];

        }else if ($('#paymentHistoryForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'artist_name', name: 'artist_name' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }];

        }else if ($('#artistPaymentHistoryForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5] } }];

        }else if ($('#paymentRequestForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },  { data: 'artist_name', name: 'artist_name' }, { data: 'amount', name: 'amount' }, { "class": "relative", data: 'status', name: 'status' }, { data: 'bank_status', name: 'bank_status' }, { data: 'request_date', name: 'request_date' }, { data: 'action', name: 'action' } ];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [0,1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [0,1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [0,1, 2, 3] } }];

        } else if ($('#currencyForm').length) {
            var column = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'code', name: 'code' }, { data: 'rate', name: 'rate' }, { data: 'symbol', name: 'symbol' }, { data: 'default_currency', name: 'default_currency' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3] } }];

        } else if ($('#tvShowGenreForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'genre_name', name: 'genre_name' }, { data: 'created_at', name: 'created_at' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2] } }];

        } else if ($('#playlistForm').length) {
            var column = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'playlist_title', name: 'playlist_title' }, { data: 'audio_language', name: 'audio_language' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
            var button = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4] } }];
        }

        $('.musiooDtToShowData').DataTable({

            responsive: true,
            ajax: {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                type: method
            },
            "createdRow": rowClass,
            columns: column,
            select: {
                style: 'os',
                selector: 'td:first-child'
            },
            dom: '<"data-table-control"<"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"<"row"<"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"B><"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"f> > ><"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"rt> <"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"<"row"<"col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12"i><"col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12"p>>> >',
            buttons: button,
            initComplete: function() {
                var btns = $('.buttons-html5');
                btns.removeClass('btn btn-secondary');
                btns.addClass('effect-btn btn btn-primary');
                className: 'effect-btn btn btn-primary';
            },
        });
 
    }


    $('.multiple_dt_filter').on('click', function() {  

        var artistStatus = '';
        var artistId = '';

        if($(".getArtistId").length) {
            var artistId = $("select.getArtistId option").filter(":selected").val();
        } 

        if($(".getArtistStatus").length) {
            var artistStatus = $("select.getArtistStatus option").filter(":selected").val();
        }
        
        var from_date = filterStartDate.format('Y-MM-DD');
        var to_date = filterEndDate.format('Y-MM-DD');
        var url = $(this).attr('data-url');
        var dataTable = $(this).attr('data-tabel');
        if (from_date != '' || to_date != '') {
            $('.'+dataTable).DataTable().destroy();
            if (dataTable == 'usersDataTables') {
                var colums = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'name', name: 'name' }, { data: 'email', name: 'email' }, { data: 'plan', name: 'plan' }, { data: 'created_at', name: 'created_at' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3] } }];
            } else if (dataTable == 'subscriptionDataTables') {
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'name', name: 'name' }, { data: 'qty', name: 'qty' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];
            }else if(dataTable == 'salesHistoryDataTables'){
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'user_name', name: 'user_name' }, { data: 'audio_name', name: 'audio_name' }, { data: 'artist_name', name: 'artist_name' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];
            }else if(dataTable == 'artistSalesHistoryDataTables'){
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'user_name', name: 'user_name' }, { data: 'audio_name', name: 'audio_name' }, { data: 'amount', name: 'amount' }, { data: 'payment_method', name: 'payment_method' }, { data: 'commission', name: 'commission' }, { data: 'tax_rate', name: 'tax_rate' }, { data: 'total_tax', name: 'total_tax' }, { data: 'earning', name: 'earning' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7,8,9,10] } }];
            }else if (dataTable == 'paymentHistoryDataTables') {
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'artist_name', name: 'artist_name' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];
            }else if (dataTable == 'artistPaymentHistoryDataTables') {
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false }, { data: 'order_id', name: 'order_id' }, { data: 'payment_method', name: 'payment_method' }, { data: 'amount', name: 'amount' }, { data: 'ordered_at', name: 'ordered_at' }, { "class": "relative", data: 'status', name: 'status' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'excelHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }, { extend: 'pdfHtml5', exportOptions: { columns: [1, 2, 3, 4, 5, 6, 7] } }];
            }else if(dataTable == 'paymentRequestDataTables'){
                var colums = [{ data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false },  { data: 'artist_name', name: 'artist_name' }, { data: 'amount', name: 'amount' }, { "class": "relative", data: 'status', name: 'status' }, { data: 'bank_status', name: 'bank_status' }, { data: 'request_date', name: 'request_date' }, { data: 'action', name: 'action' } ];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [0,1, 2, 3] } }, { extend: 'excelHtml5', exportOptions: { columns: [0,1, 2, 3] } }, { extend: 'pdfHtml5', exportOptions: { columns: [0,1, 2, 3] } }];
            }else if(dataTable == 'artistDataTables'){
                var colums = [{ data: 'checkbox', name: 'checkbox', className: 'select-checkbox', targets: 0, searchable: false, orderable: false }, { data: 'image', name: 'image' }, { data: 'artist_name', name: 'artist_name' }, { data: 'genre_name', name: 'genre_name' }, { data: 'created_at', name: 'created_at' }, { data: 'balance', name: 'balance' }, { data: 'verify_status', name: 'verify_status' }, { data: 'status', name: 'status' }, { "class": "relative", data: 'action', name: 'action' }];
                var buttonn = [{ extend: 'csvHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'excelHtml5', exportOptions: { columns: [2, 3, 4, 5] } }, { extend: 'pdfHtml5', exportOptions: { columns: [2, 3, 4, 5] } }];
            }

            $('.'+dataTable).DataTable({
                responsive: true,
                ajax: {
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: url,
                    type: 'post',
                    data: { artist_status:artistStatus, artist_id:artistId, from_date: from_date, to_date: to_date }
                },
                "createdRow": rowClass,
                columns: colums,
                select: {
                    style: 'os',
                    selector: 'td:first-child'
                },
                dom: '<"data-table-control"<"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"<"row"<"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"B><"col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12"f> > ><"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"rt> <"col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12"<"row"<"col-xl-5 col-lg-5 col-md-5 col-sm-12 col-12"i><"col-xl-7 col-lg-7 col-md-7 col-sm-12 col-12"p>>> >',
                buttons: buttonn,
                initComplete: function() {
                    var btns = $('.buttons-html5');
                    btns.removeClass('btn btn-secondary');
                    btns.addClass('effect-btn btn btn-primary');
                    className: 'effect-btn btn btn-primary';
                },
            });
            
        } else {
            toastr.error('Some required fields are missing.');
        }
    });
    
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
				url: adminBaseUrl+'/download_track',
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
    
    $(document).on('click', '#deleteRecordById', function() {
        var __this = $(this);
        var table = $('.musiooDtToShowData').DataTable();
        var msg = __this.attr('data-msg');
        var obj = {};
        if (typeof msg != 'undefined') {
            var obj = { 'title': msg }
        }
        deletePopup(obj).then((result) => {
            if (result.value) {
                ajaxCall.ajax({
                    url: __this.attr('data-url'),
                    method: 'post',
                    data: '',
                }, function(resp) {
                    if (resp.status == 1) {
                        table.row(__this.parents('tr')).remove().draw();
                    } else {
                        toastr.error(resp.msg);
                    }
                })
            }
        })
    })


    $(document).on('click', '#bulkDelete', function() {
        deletePopup().then((result) => {
            if (result.value) {
                var errMSg = $(this).attr('data-msg');
                var check = $('.CheckBoxes:checked');
                var type = $(this).attr('data-type');
                if (check.length > 0) {
                    var idArr = [];
                    var userIdArr = [];
                    for (var i = 0; i < check.length; i++) {
                        idArr.push($(check[i]).val());
                        if (typeof type != 'undefined') {
                            userIdArr.push($(check[i]).attr('data-user'))
                        }
                    }

                    var data = { 'checked': idArr };
                    if (typeof type != 'undefined') {
                        data.user_id = userIdArr;
                    }
                    ajaxCall.ajax({
                        url: $(this).attr('data-url'),
                        method: 'delete',
                        data: data,
                    }, function(resp) {
                        $('.musiooDtToShowData').DataTable().ajax.reload();
                    })
                } else {
                    toastr.error(errMSg);
                }
            }
        })
    });


    jsDynamicText = JSON.parse(jsDynamicText);
    $(document).on('change', '.artistAudioLanguageId', function() {

        var getLanguage = $(this).val();
        var artist = $('#audio_artist_list').empty();
        if (getLanguage) {

            ajaxCall.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: adminBaseUrl + '/audio/langugage/artist',
                data: { 'getLanguage': getLanguage },
                method: "post"
            }, function(resp) {

                if (resp.status == 1) {
                    artist.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                    resp.data.forEach(function(artists) {
                        var options = `<option value="${ artists.id }">${ artists.artist_name }</option>`
                        artist.append(options).trigger('change');
                    });
                } else {
                    artist.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                }
            })
        }

    });


    $(document).on('change', '.albumAudioLanguageId', function() {

        var getLanguage = $(this).val();
        var song_list = $('#album_audio_list').empty();

        if (getLanguage) {

            ajaxCall.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: adminBaseUrl + '/audio/langugage/album',
                data: { 'getLanguage': getLanguage },
                method: "post"
            }, function(resp) {

                if (resp.status == 1) {
                    song_list.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                    resp.data.forEach(function(song) {
                        var options = `<option value="${ song.id }" data-language="${ song.audio_language }"> ${song.audio_title }</option>`
                        song_list.append(options).trigger('change');
                    });
                } else {
                    song_list.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                }
            })
        }

    });


    $('[data-toggle="tooltip"]').tooltip();
    toastr.clear();
    toastr.options = {
        "debug": false,
        "positionClass": "toast-top-right",
        "onclick": null,
        "fadeIn": 300,
        "fadeOut": 1000,
        "timeOut": 5000,
        "extendedTimeOut": 1000,
        "closeButton": true,
        "closeEasing": 'swing'
    }

    if ($(".rating").length) {
        $(".rating").starRating({
            initialRating: $('.rating').attr('data-rating'),
            emptyColor: 'lightgray',
            hoverColor: '#506fe4',
            ratedColors: ['#506fe4', '#506fe4', '#506fe4', '#506fe4', '#506fe4'],
            strokeColor: 'black',
            activeColor: '#506fe4',
            strokeWidth: 15,
            callback: function(currentRating, $el) {
                $('.live-rating').val(currentRating)
            }
        });
    }


    if ($('.autoclose-date').length) {
        $('.autoclose-date').datepicker({
            language: "en",
            autoclose: true,
            dateFormat: 'yyyy-mm-dd',
        });
    }

    if ($('#summernote').length) {
        $('#summernote').summernote({
            height: 320,
            minHeight: null,
            maxHeight: null,
            focus: true,
            dialogsInBody: true,
        });
    }

    $('.openMenusToggle').on('click', function() {
        $('#g_box').toggle();
    })


    $(document).on('change', '.basicImage', function(e) {
        if ($(this)[0].files.length) {
            var imageId = $(this).attr('data-image-id');
            $('#' + imageId).val('');
            $('.image_title').html(jsDynamicText.chooseImage);
            var file = $(this)[0].files[0];
            var _this = $(this);
            var dataId = $(this).attr('data-id');
            var dataLabel = $(this).attr('data-label');
            var ext = (typeof $(this).attr('data-ext') != 'undefined') ? eval($(this).attr('data-ext')) : ['jpg', 'jpeg', 'png', 'svg'];
            var dimension = (typeof $(this).attr('data-dimension') != 'undefined') ? $(this).attr('data-dimension').split('x') : '';
            if (dimension != '') {
                if (jQuery.inArray(file.name.split('.').pop().toLowerCase(), ext) == -1) {
                    toastr.error(jsDynamicText.imgExtErr + ext.toString() + jsDynamicText.fileType);
                    return false;
                } else {
                    var img = new Image();
                    var obUrl = URL.createObjectURL(file);
                    img.src = obUrl;
                    img.onload = function() {
                        if (typeof dimension == 'object' && this.width != dimension[0] || this.height != dimension[1]) {
                            toastr.error(jsDynamicText.dimensionErr + dimension[0] + 'x' + dimension[1] + '.');
                            return false;
                        } else {
                            _this.closest('div').find('#' + imageId).val(file.name);
                            _this.closest('div').find('#' + dataLabel).html(file.name);
                            $('#' + dataId).attr('src', obUrl);
                        }
                    };
                }
            } else {
                if (jQuery.inArray(file.name.split('.').pop().toLowerCase(), ext) == -1) {
                    toastr.error(jsDynamicText.imgExtErr + ext.toString() + jsDynamicText.fileType);
                } else {
                    _this.closest('div').find('#' + dataLabel).html(file.name);
                    _this.closest('div').find('#' + imageId).val(file.name);
                }
            }
        } else {
            toastr.error(jsDynamicText.selectImgErr);
            _this.closest('div').find('#' + dataLabel).html(jsDynamicText.chooseImage)
        }
    })

    $(document).on('change', 'input[name="userProfileImage"]', function(e) {
        if ($('input[name="userProfileImage"]')[0].files.length) {
            var file = $('input[name="userProfileImage"]')[0].files[0];
            var ext = ['jpg', 'jpeg', 'png'];
            if (jQuery.inArray(file.name.split('.').pop().toLowerCase(), ext) == -1) {
                toastr.error(jsDynamicText.imgExtErr + ext.toString() + jsDynamicText.fileType);
            } else {
                $('.image_title').html(file.name)
            }
        } else {
            if ($('#image_name').val() != '')
                $('.image_title').html($('#image_name').val());
            else
                $('.image_title').html(jsDynamicText.chooseImage);
        }
    })

    $('#mobile').keypress(function(e) {
        var regex = new RegExp("^[0-9-]+$");
        var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
        if (!regex.test(str)) {
            e.preventDefault();
            return false;
        } else if ($(this).attr('max-length') == $.trim($(this).val().length)) {
            return false;
        }
    });

    $(document).on('click', '.toggle-view-password', function() {
        $(this).toggleClass("fa-eye-slash fa-eye");
        var input = $($(this).attr("toggle"));
        if (input.attr("type") == "password") {
            input.attr("type", "text");
        } else {
            input.attr("type", "password");
        }
    });

    if ($('.paymentGatewayAccordation').length) {
        if ($('#razorpay_check').is(':checked')) {
            $('#razorpay_box').show('fast');
        } else {
            $('#razorpay_box').hide('fast');
        }
        if ($('#paypal_check').is(':checked')) {
            $('#paypal_box').show('fast');
        } else {
            $('#paypal_box').hide('fast');
        }
        if ($('#payu_check').is(':checked')) {
            $('#payu_box').show('fast');
        } else {
            $('#payu_box').hide('fast');
        }
        if ($('#bt_check').is(':checked')) {
            $('#bt_box').show('fast');
        } else {
            $('#bt_box').hide('fast');
        }
        if ($('#stripe_check').is(':checked')) {
            $('#stripe_box').show('fast');
        } else {
            $('#stripe_box').hide('fast');
        }
        if ($('#instamojo_check').is(':checked')) {
            $('#instamojo_box').show('fast');
        } else {
            $('#instamojo_box').hide('fast');
        }
        if ($('#paystack_check').is(':checked')) {
            $('#paystack_box').show('fast');
        } else {
            $('#paystack_box').hide('fast');
        }
        if ($('#braintree_check').is(':checked')) {
            $('#braintree_box').show('fast');
        } else {
            $('#braintree_box').hide('fast');
        }
        if ($('#manual_check').is(':checked')) {
            $('#manual_box').show('fast');
        } else {
            $('#manual_box').hide('fast');
        }
    }
    
    if ($('.emailAccordation').length) {       
        if ($('#smtp_check').is(':checked')) {
            $('#smtp_box').show('fast');
        } else {
            $('#smtp_box').hide('fast');
        }
    }
    
    if ($('.paypalDonationAccordation').length) {
        if ($('#header_msg_check').is(':checked')) {
            $('#header_msg_box').show('fast');
        } else {
            $('#header_msg_box').hide('fast');
        }
        if ($('#footer_section_check').is(':checked')) {
            $('#footer_box').show('fast');
        } else {
            $('#footer_box').hide('fast');
        }
        if ($('#paypal_donation_check').is(':checked')) {
            $('#paypal_donation_box').show('fast');
        } else {
            $('#paypal_donation_box').hide('fast');
        }
        if ($('#newsltr_check').is(':checked')) {
            $('#newsltr_box').show('fast');
        } else {
            $('#newsltr_box').hide('fast');
        }
    }

    if ($('.googleAdAccordation').length) {
        if ($('#g_check').is(':checked')) {
            $('#g_box').show('fast');
        } else {
            $('#g_box').hide('fast');
        }
    }

    if ($('.taxCommissionAccordation').length) {
        if ($('#tax_check').is(':checked')) {
            $('#tax_box').show('fast');
        } else {
            $('#tax_box').hide('fast');
        }
        if ($('#artist_commission_check').is(':checked')) {
            $('#commission_box').show('fast');
        } else {
            $('#commission_box').hide('fast');
        }
    }

    $(document).on('click','.artistCommissionType',function(){
        $(".commission_field").hide();
        var comissionId = $(this).attr('data-id');
        $('#'+comissionId).show();           
    }); 


    if ($('.integrationAccordation').length) {

        if ($('#s3_check').is(':checked')) {
            $('#s3_box').show('fast');
        } else {
            $('#s3_box').hide('fast');
        }
        if ($('#youtube_check').is(':checked')) {
            $('#youtube_box').show('fast');
        } else {
            $('#youtube_box').hide('fast');
        }
    }

    if ($('.artistIntegrationAccordation').length) {

        if ($('#artists3_check').is(':checked')) {
            $('#artists3_box').show('fast');
        } else {
            $('#artists3_box').hide('fast');
        }
        if ($('#artistyoutube_check').is(':checked')) {
            $('#artistyoutube_box').show('fast');
        } else {
            $('#artistyoutube_box').hide('fast');
        }
    }
    


    if ($('.socialLoginAccordation').length) {
        if ($('#g_check').is(':checked')) {
            $('#g_box').show('fast');
        } else {
            $('#g_box').hide('fast');
        }
        if ($('#fb_check').is(':checked')) {
            $('#fb_box').show('fast');
        } else {
            $('#fb_box').hide('fast');
        }
        if ($('#git_check').is(':checked')) {
            $('#git_box').show('fast');
        } else {
            $('#git_box').hide('fast');
        }
        if ($('#twitter_check').is(':checked')) {
            $('#twitter_box').show('fast');
        } else {
            $('#twitter_box').hide('fast');
        }
        if ($('#amazon_check').is(':checked')) {
            $('#amazon_box').show('fast');
        } else {
            $('#amazon_box').hide('fast');
        }
        if ($('#linkedin_check').is(':checked')) {
            $('#linkedin_box').show('fast');
        } else {
            $('#linkedin_box').hide('fast');
        }
    }


    $('.updateSettingRecords').on('change', function() {
        var id = $(this).attr('data-id');
        var requireId = $(this).attr('required-id');
        var type = $(this).attr('data-type');
        var name = $(this).data('name');        

        if ($(this).is(':checked')) {
            $('#' + id).show('fast');
            $(requireId).addClass('require');
        } else {
            $('#' + id).hide('fast');
            $(requireId).removeClass('require');
            onupdateStatus({ 'url': $('#URL').val(), data: { status: 0, 'type': type, 'gateway_name': name } });
        }
    });

    $(document).on('change', '#is_paid', function() {
        if ($(this).is(':checked')) {
            $('#pay_amount').slideDown();
            $('#amount').addClass('require');
        } else {
            $('#pay_amount').slideUp();
            $('#amount').removeClass('require');
        }
    })

    $(document).on('change', '#is_download', function() {
        if ($(this).is(':checked')) {
            $('#download_count').slideDown();
            $('#downloadCount').addClass('require');
        } else {
            $('#download_count').slideUp();
            $('#downloadCount').removeClass('require');
        }
    })

    $(document).on('keyup', '#plan_amount', function() {
        if ($(this).val() == 0) {
            $('#monthDays').val('0');
            $('.validity_month_day').html('Validity In Days');
        } else {
            $('#monthDays').val('1');
            $('.validity_month_day').html('Validity In Months');
        }
    })

    function onupdateStatus(param) {
        ajaxCall.ajax({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            url: param.url,
            method: 'post',
            data: param.data,
        }, function(data) {

        });
    }

    var ajaxCall = {
        ajax: (param, callBack) => {
            var ajaxOption = {
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: param.url,
                method: param.method,
                data: param.data,
                success: (resp) => {
                    resp = (typeof resp != 'object') ? JSON.parse(resp) : resp;
                    if (resp.status == 1) {
                        if (resp.msg) {
                            (resp.msg && resp.msg.trim() != '') ? toastr.success(resp.msg): '';
                        }
                        callBack(resp);
                    } else if (resp.status == 0) {
                        if (resp.msg) {
                            toastr.error(resp.msg);
                        }
                    }
                },
                error: (resp) => {
                    toastr.error(resp.msg);
                }
            }
            if (param.formData) {
                ajaxOption.processData = false;
                ajaxOption.contentType = false;
            }

            if (param.dataType) {
                ajaxOption.dataType = param.dataType;
            }
            $.ajax(ajaxOption);
        }
    }

    $(document).on('change', '#country_id', function() {
        var url = $(this).attr('data-url');
        var state = $('#state_id').empty();
        var city = $('#city_id').empty();
        var country = $.trim($(this).val());
        if (country != '') {
            ajaxCall.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: { 'country_id': country },
            }, function(data) {
                state.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                $('#state_id, #city_id').addClass('require');
                $('[for="state_id"]').html(`${jsDynamicText.select} ${jsDynamicText.state} *`);
                $('[for="city_id"]').html(`${jsDynamicText.select} ${jsDynamicText.city} *`);
                $.each(data.data, function(id, title) {
                    var options = new Option(title, id, false, false);
                    state.append(options).trigger('change');
                })
            });
        } else {
            $('[for="state_id"]').html(`${jsDynamicText.select} ${jsDynamicText.state}`);
            $('[for="city_id"]').html(`${jsDynamicText.select} ${jsDynamicText.city}`);
            $('#state_id, #city_id').removeClass('require');
            state.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>').trigger('change');
            city.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>').trigger('change');
        }
    })

    $(document).on('change', '#state_id', function() {
        var url = $(this).attr('data-url');
        var city = $('#city_id').empty();
        var state = $.trim($(this).val());
        if (state != '') {
            ajaxCall.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: url,
                method: 'post',
                data: { 'state_id': state },
            }, function(data) {
                city.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                $.each(data.data, function(id, title) {
                    var options = new Option(title, id, false, false);
                    city.append(options).trigger('change');
                })
            });
        } else {
            city.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>').trigger('change');
        }
    })


    $(document).on('click', '.locationPopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditLocation').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="country"]').val('');
            $('#addEditLocation .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.country}`);
            $('#addEditLocation').modal('toggle');
            $('#addEditLocation #conBtn').html(`${jsDynamicText.add}`);
        } else {
            $('#addEditLocation #conBtn').html(`${jsDynamicText.update}`);
            $('#addEditLocation').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="country"]').val(resp.data[0]['country']);
                $('#addEditLocation').modal('toggle');
            })
            $('#addEditLocation .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.country}`);
        }

    });

    $(document).on('click', '.statePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditState').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="state"]').val('');
            $('.country_id').val('').trigger('change');
            $('#addEditState .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.state}`);
            $('#addEditState').modal('toggle');
        } else {
            $('#addEditState').find('form').attr('action', $(this).attr('data-save'));

            $('#addEditState .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.state}`);
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="state"]').val(resp.data[0]['name']);
                $('.country_id').val(resp.data[0]['country_id']).trigger('change');
                $('#addEditState').modal('toggle');
            })
        }
    });


    $(document).on('click', '.cityPopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditCity').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="city"]').val('');
            $('.state_id').val('').trigger('change');
            $('#addEditCity .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.city}`);
            $('#addEditCity').modal('toggle');
        } else {
            $('#addEditCity').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="city"]').val(resp.data[0]['name']);
                $('.state_id').val(resp.data[0]['state_id']).trigger('change');
                $('#addEditCity').modal('toggle');
            })
            $('#addEditCity .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.city}`);
        }
    });

    $(document).on('click', '.blogCategoryPopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditBlogCat').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="title"]').val('');
            $('#addEditBlogCat .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.blogCat}`);
            $('#addEditBlogCat').modal('toggle');
        } else {
            $('#addEditBlogCat').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
            }, function(resp) {
                $('input[name="title"]').val(resp.data['title']);
                if (resp.data['is_active']) {
                    $('input[name="is_active"]').prop('checked', true);
                } else {
                    $('input[name="is_active"]').prop('checked', false);
                }
                $('#addEditBlogCat').modal('toggle');
            })
            $('#addEditBlogCat .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.blogCat}`);
        }
    });

    $(document).on('click', '.audioGenrePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addUpdateAudioGenre').find('form').attr('action', $(this).attr('data-url'));
            $('#genreImage').html('Choose Image');
            $('input[name="title"]').val('');
            $('#addUpdateGenreForm #audioGenreBtn').html(`${jsDynamicText.create}`);
            $('#addUpdateAudioGenre .modal-title').html(`${jsDynamicText.create} ${jsDynamicText.audioGenre}`);
            $('#addUpdateAudioGenre').modal('toggle');
        } else {
            
            $('#addUpdateAudioGenre').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
            }, function(resp) {
                
                $('input[name="genre_name"]').val(resp.data['genre_name']);
                $('#genreImage').html(resp.data['image']);
                (resp.data['is_featured']) ? $('input[name="is_featured"]').prop('checked', true): $('input[name="is_featured"]').prop('checked', false);
                (resp.data['is_trending']) ? $('input[name="is_trending"]').prop('checked', true): $('input[name="is_trending"]').prop('checked', false);
                (resp.data['is_recommended']) ? $('input[name="is_recommended"]').prop('checked', true): $('input[name="is_recommended"]').prop('checked', false);
                (resp.data['status']) ? $('input[name="status"]').prop('checked', true): $('input[name="status"]').prop('checked', false);
                $('#addUpdateAudioGenre').modal('toggle');
            })
            $('#addUpdateGenreForm #audioGenreBtn').html(`${jsDynamicText.update}`);
            $('#addUpdateAudioGenre .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.audioGenre}`);
        }

    });

    $(document).on('click', '.playlistGenrePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {

            $('#addUpdatePlaylistGenre').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="title"]').val('');
            $('#addUpdatePlaylistGenre .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.playlistGenre}`);
            $('#addUpdatePlaylistGenre').modal('toggle');
        } else {
            $('#addUpdatePlaylistGenre').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
            }, function(resp) {
                //console.log(resp)
                $('input[name="genre_name"]').val(resp.data['genre_name']);
                (resp.data['status']) ? $('input[name="status"]').prop('checked', true): $('input[name="status"]').prop('checked', false);
                $('#addUpdatePlaylistGenre').modal('toggle');
            })
            $('#addUpdatePlaylistGenre .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.playlistGenre}`);
        }

    });

    $(document).on('click', '.artistGenrePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditArtistGenre').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="genre_name"]').val('');
            $('#addEditArtistGenre .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.artistGenre}`);
            $('#addEditArtistGenre #genreBtn').html(`${jsDynamicText.add}`);
            $('#addEditArtistGenre').modal('toggle');
        } else {
            $('#addEditArtistGenre').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="genre_name"]').val(resp.data['genre_name']);
                $('#addEditArtistGenre').modal('toggle');
            })
            $('#addEditArtistGenre #genreBtn').html(`${jsDynamicText.update}`);
            $('#addEditArtistGenre .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.artistGenre}`);
        }
    });


    $(document).on('click', '.notificationPopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addNotification').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="genre_name"]').val('');
            $('#addNotification .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.notification}`);
            $('#addNotification #genreBtn').html(`${jsDynamicText.add}`);
            $('#addNotification').modal('toggle');
        } else {
            $('#addNotification').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="genre_name"]').val(resp.data['genre_name']);
                $('#addNotification').modal('toggle');
            })
            $('#addNotification #genreBtn').html(`${jsDynamicText.update}`);
            $('#addNotification .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.notification}`);
        }
    });

    $(document).on('click', '.languagePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addUpdateLocalLanguage').find('form').attr('action', $(this).attr('data-url'));
            $('#language_name, #langauge_code').val('');
            $('#addUpdateLocalLanguage .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.language}`);
            $('#addUpdateLocalLanguage #langSubmitBtn').html(`${jsDynamicText.add}`);
            $('#addUpdateLocalLanguage').modal('toggle');
        } else {
            $('#addUpdateLocalLanguage').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('[name="language_name"]').val(resp.data['language_name']);
                $('[name="language_code"]').val(resp.data['language_code']);
                (resp.data['status'] == 1) ? $('#switchStatus').prop('checked', true): $('#switchStatus').prop('checked', false);
                (resp.data['is_default'] == 1) ? $('#switchDefault').prop('checked', true): $('#switchDefault').prop('checked', false);
                $('#addUpdateLocalLanguage').modal('show');
            })
            $('#addUpdateLocalLanguage #langSubmitBtn').html(`${jsDynamicText.update}`);
            $('#addUpdateLocalLanguage .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.language}`);
        }
    });

    $(document).on('click', '.currencyPopupToggle', function() {
        $('#addEditCurrency').modal('toggle');
    })

    $(document).on('click', '.makeCurrencyDefault', function() {
        var _this = $(this);
        var name = _this.attr('data-name');
        var cur_id = _this.val();
        deletePopup({ 'title': jsDynamicText.are_u_sure, 'text': jsDynamicText.make_default + name + jsDynamicText.default_curr, 'cnfButton': jsDynamicText.ok }).then((result) => {
            if (result.value) {
                ajaxCall.ajax({
                    url: adminBaseUrl + '/make_default',
                    method: 'post',
                    data: { id: cur_id }
                }, function(resp) {
                    $('.musiooDtToShowData').DataTable().ajax.reload();
                })
            } else {
                _this.prop('checked', false)
            }
        })
    });

    $(document).on('click', '.tvShowGenrePopupToggle', function() {
        var add = $(this).attr('data-add');
        if (typeof add != 'undefined') {
            $('#addEditTvShowGenre').find('form').attr('action', $(this).attr('data-url'));
            $('input[name="genre_name"]').val('');
            $('#addEditTvShowGenre .modal-title').html(`${jsDynamicText.add} ${jsDynamicText.artistGenre}`);
            $('#addEditTvShowGenre #genreBtn').html(`${jsDynamicText.add}`);
            $('#addEditTvShowGenre').modal('toggle');
        } else {
            $('#addEditTvShowGenre').find('form').attr('action', $(this).attr('data-save'));
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: "post"
            }, function(resp) {
                $('input[name="genre_name"]').val(resp.data['genre_name']);
                $('#addEditTvShowGenre').modal('toggle');
            })
            $('#addEditTvShowGenre #genreBtn').html(`${jsDynamicText.update}`);
            $('#addEditTvShowGenre .modal-title').html(`${jsDynamicText.update} ${jsDynamicText.artistGenre}`);
        }
    });

    $('.updateRateSetting').on('click', function() {
        var _this = $(this);
        deletePopup({ 'title': jsDynamicText.are_u_sure, 'text': jsDynamicText.update_rate_text, 'cnfButton': jsDynamicText.ok }).then((result) => {
            if (result.value) {
                _this.find('i').addClass('fa-spin fa-fw').prop('disabled', true);
                ajaxCall.ajax({
                    url: adminBaseUrl + '/auto_update/rate',
                    method: 'post',
                }, function(resp) {})
                _this.find('i').removeClass('fa-spin fa-fw').prop('disabled', false);
                window.location.reload();
            }
        })
    });

    function deletePopup(param = '') {
        return Swal.fire({
            title: (param.title) ? param.title : jsDynamicText.delete_records,
            text: (param.text) ? param.text : jsDynamicText.cantUndone,
            showCancelButton: true,
            confirmButtonText: (param.cnfButton) ? param.cnfButton : jsDynamicText.delete
        });
    }

    $(document).on('change', '.updateStatus', function() {
        if ($(this).is(':checked'))
            var status = 1;
        else
            var status = 0;
        ajaxCall.ajax({
            url: $(this).attr('data-url'),
            method: 'patch',
            data: { status: status }
        }, function(resp) {})
    });

    $(document).on('change', '.changePlanDataVal', function() {
        var field = $(this).attr('data-field');
        if ($(this).is(':checked'))
            var value = 1;
        else
            var value = 0;
        ajaxCall.ajax({
            url: $(this).attr('data-url'),
            method: 'patch',
            data: {
                [field]: value
            }
        }, function(resp) {})
    });

    if ($('.sortDataOfTableVal').length) {
        $(".sortDataOfTableVal tbody").sortable({
            items: "tr",
            cursor: 'move',
            opacity: 0.6,
            update: function() {
                sendOrderTypeToServer();
            }
        });

        function sendOrderTypeToServer() {
            var order = [];
            $('tr.sortSlider').each(function(index, element) {
                order.push({
                    id: $(this).attr('data-id'),
                    position: index + 1
                });
            });
            ajaxCall.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: "saveSliderPosition",
                method: 'post',
                data: { order: order },
                dataType: "json",
            }, function(data) {
                //console.log(data)
            });
        }
    }

    $(document).on('change', 'input[name="is_album_movie"]', function() {
        if ($(this).val() == 0) {
            $('.showMovieName').removeClass('d-none');
            $('.showAlbumName').addClass('d-none');
            $('[name="movie_id"]').addClass('require');
            $('[name="album_id"]').removeClass('require');
        } else {
            $('.showMovieName').addClass('d-none');
            $('.showAlbumName').removeClass('d-none');
            $('[name="album_id"]').addClass('require');
            $('[name="movie_id"]').removeClass('require');
        }
    });

    $(document).on('click', '#replyOnUserComment', function() {
        $('#add_reply #replyBox').html('');
        var url = $(this).data('url');
        var getUrl = $(this).data('get-url');
        $('#addReply').find('form').attr('action', url);
        ajaxCall.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: getUrl,
            method: 'post',
        }, function(data) {
            $('#add_reply #replyBox').html(data.reply);
        })
        $('#addReply').modal('toggle');
    });

    $(document).on('change', 'input[name="applicable_on"]', function() {
        if ($(this).val() == 0) {
            $('.applicable_plan').addClass('d-none');
            $('#planId').removeClass('require');
        } else {
            $('.applicable_plan').removeClass('d-none');
            $('#planId').addClass('require');
        }
    });


    $(document).on('change', '.enableDisableSettngStatus', function() {
        if ($(this).is(':checked')) {
            $(this).closest('tr').find('.numberOfItem').removeClass('d-none');
            $(this).closest('tr').find('.numberOfItem input').addClass('require');

        } else {
            $(this).closest('tr').find('.numberOfItem').addClass('d-none');
            $(this).closest('tr').find('.numberOfItem input').removeClass('require');
        }
    });

    $(document).on('click', '#paymentProofImgPopup', function() {
        var img = $(this).attr('src');
        $('#payment_proof_popup').find('img').attr('src', img);
        $('#payment_proof_popup').modal('show');
    });

    $(document).on('change', '[name="paymentStatus"]', function() {
        var status = $(this).val();
        var id = $(this).closest('select').attr('data-payment-id');
        ajaxCall.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            url: adminBaseUrl + '/payment_status',
            method: 'post',
            data: { 'status': status, 'payment_id': id }
        }, function(data) {
            $('.musiooDtToShowData').DataTable().ajax.reload();
        })
    });


    $(document).on('click', '.addTermCond', function() {
        $('.appendTermCond').append(`<div class="form-group"><input type="text" name="termCond" value="" class="termCond form-control require" placeholder="${$('.termCond').attr('placeholder')}" /><a href="javascript:;" class="addTermCond"><i class="fa fa-plus-circle" class="addTermCond"></i></a> <a href="javascript:void(0);" class="deleteCond"><i class="far fa-trash-alt ml-2"></i></a></div>`);
    });

    $(document).on('click', '.addPlaylistApiKey', function() {
        $('.appendYoutubeApiKey').append(`<div class="form-group"><input type="text" name="youtube_playlist_key[]" value="" class="form-control require" placeholder="${$('.ytPlaylistKey').attr('placeholder')}" /><a href="javascript:;" class="addPlaylistApiKey"><i class="fa fa-plus-circle"></i></a> <a href="javascript:void(0);" class="deleteCond"><i class="far fa-trash-alt ml-2"></i></a></div>`);
    }); 

    $(document).on('click', '.deleteCond', function() {
        $(this).parent().remove();
    });

    $('#saveInvoiceFormData').on('click', function() {
        var form = $(this).closest('form').attr('id');
        var formValid = myCustom.checkFormFields(form);
        if (!formValid) {
            var formData = new FormData($('#invoiceSett')[0]);
            var termsArr = [];
            $('.termCond').each(function() {
                termsArr.push($(this).val());
            })
            formData.append('terms', termsArr);
            $.ajax({
                url: adminBaseUrl + '/saveInvoice',
                method: 'post',
                data: formData,
                contentType: false,
                processData: false,
                success: function(resp) {
                    var res = JSON.parse(resp);
                    if (res.status == 1) {
                        toastr.success(res.msg);
                        setTimeout(function() {
                            location.reload();
                        }, 1000)
                    }
                }
            })
        }
    });


    $(document).on('change', ".changeArtistVerifyStatus", function(){ 

        var value = $(this).find('option:selected').val();
        ajaxCall.ajax({
            url: $(this).attr('data-url'),
            method: 'patch',
            data: { varify_status: value }
        }, function(resp) {})
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

    // Check Bulk delete checkbox check

    $("#bulkDelete").hide();
    $(document).on("change", ".selectAllUser, .CheckBoxes", function() {
        if ($(this).is(':checked')) {
            $("#bulkDelete").show();
        } else {
            $("#bulkDelete").hide();
        }
        
        if(!$('.CheckBoxes').prop("checked")) {
    		$(".selectAllUser").prop("checked", false);
    	}
    });

    $(document).on('change', '.getSelectedLanguage', function() {

        var getLangauge = $(this).val();
        var album = $('#album_list_ids').empty();
        var artist = $('#artist_list_ids').empty();
        var song_list = $('#audio_list_ids').empty();

        if (getLangauge) {
            ajaxCall.ajax({
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                url: adminBaseUrl + '/admin/getRecordbylanguage/' + getLangauge,
                method: "get"
            }, function(resp) {
                if (resp.status == true) {
                    album.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                    resp.data.album.forEach(function(albums) {
                        var options = `<option value="${ albums.id }" data-language="${ albums.language_id }" >${ albums.album_name }</option>`; //new Option(album_name, id, false, false);
                        album.append(options).trigger('change');
                    });

                    artist.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                    resp.data.artist.forEach(function(artists) {
                        var options = `<option value="${ artists.id }" data-language="${ artists.audio_language_id }">${ artists.artist_name }</option>`
                        artist.append(options).trigger('change');
                    });

                    song_list.append('<option value="">' + jsDynamicText.pleaseChoose + '</option>');
                    resp.data.song_list.forEach(function(song) {
                        var options = `<option value="${ song.id }" data-language="${ song.audio_language }"> ${song.audio_title }</option>`
                        song_list.append(options).trigger('change');
                    });
                }
            })
        }
    });
    
    // ARTIST REQUEST TO PAYMENT START
    
    $(document).on("click", "#alreadyRequested", function(){
        toastr.error("You have already requested.");
    });
    
    $(document).on("click", "#defaultGatewayeError", function(){
        toastr.error("Please select default payment method first.");
    });
    
    $(document).on("click", "#artistWithdrawRequest", function(){

        var withdrawBalance = $("#withdrawBalance").data("amount");
        var request_amount = $(".request_amount").val();    
        
        
        if(withdrawBalance != '' && withdrawBalance > 0 && request_amount != '' && request_amount > 0) {
            
            if(withdrawBalance >= request_amount){
                ajaxCall.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    url: adminBaseUrl + '/artist/request_to_payment',
                    method: "post", 
                    data: {'request_amount': request_amount},
                }, function(resp) {
                   setTimeout(function(){
                        location.reload();                
                    },500);
                });
            }else{
                toastr.error("You do not have sufficient balance."); 
            }
        }else{
            toastr.error("Some required fields are missing."); 
        }            
    });
    // ARTIST REQUEST TO PAYMENT END
    
    $('.checkDiscountLimit').on("input", function() {
        
        if ($(this).val() > 99){
            Swal.fire({
              icon: 'warning',
              title: jsDynamicText.coupon_discount_value_error,  //'Value should be less then 100.',
              showConfirmButton: false,
              timer: 1700
            })
            $(this).val('99');
            return
        }
    });
    
    $(document).on('change', ".artistPaymentReleaseByAdmin", function(){  

        var value = $(this).find('option:selected').val();

        if(value != ''){
            ajaxCall.ajax({
                url: $(this).attr('data-url'),
                method: 'patch',
                data: { 'optionValue': value }
            }, function(respvalues) {
                if(respvalues.status == true){
                    $('#releasePaymentToArtistByAdmin .modal-title').html('');
                    $(".paypalSubmit", "stripeSubmit").hide();

                    if(value == '1'){ 

                        if(respvalues.data.pay_method == 'razorpay'){
                            var options = {
                                "key": respvalues.data.razorpay_key,
                                "amount": respvalues.data.amount, 
                                "currency": respvalues.data.currency,
                                "name": respvalues.data.name,
                                "description": respvalues.data.description,
                                "image": respvalues.data.image,
                                "handler": function (response){ 

                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        type: 'post',
                                        url: adminBaseUrl+'/admin/releasePaymentCallback',
                                        data : {'request_id': respvalues.data.request_id, 'razorpay_payment_id' : response.razorpay_payment_id, 'currency': respvalues.data.currency ,'is_ajax' : 1},
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
                                    "name": respvalues.data.name,
                                    "email": respvalues.data.email,
                                    "contact": respvalues.data.contact
                                },
                                "theme": {
                                    "color": respvalues.data.color
                                }
                            };                            
                            new Razorpay(options).open();

                        }else if(respvalues.data.pay_method == 'paystack'){
                            
                            var handler = PaystackPop.setup({
                                key: respvalues.data.paystack_key,
                                email: respvalues.data.email,
                                amount: respvalues.data.amount,
                                currency: 'NGN',
                                ref: respvalues.data.reference,
                                metadata: {
                                    custom_fields: [{
                                        display_name: respvalues.data.user_name,
                                        value: respvalues.data.contact,                                    
                                    }]
                                },
                                callback: function(payresponse){

                                    $.ajax({
                                        headers: {
                                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                        },
                                        type: 'post',
                                        url: adminBaseUrl+'/paystack/artistReleaseCallback',
                                        data : {'request_id': respvalues.data.request_id,'currency': 'NGN' ,'transaction' : payresponse.transaction,'message': payresponse.message, 'reference' : payresponse.reference, 'status' : payresponse.status,'is_ajax':1},
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
                        } else if(respvalues.data.pay_method == 'paypal'){

                            $('#releasePaymentToArtistByAdmin .modal-title').html(`${jsDynamicText.pay_with_paypal}`);                       
                            $('#paypal-form').attr('action', respvalues.data.url);
                            $('.artistRequestId').val(respvalues.data.request_id); 
                            $('.artistPayableAmount').val(respvalues.data.amount);
                            $('.paypalSubmit').show();      
                            $('#releasePaymentToArtistByAdmin').modal('toggle');                            
                            
                        }else if(respvalues.data.pay_method == 'stripe'){

                            $('#releasePaymentToArtistByAdmin .modal-title').html(`${jsDynamicText.pay_with_stripe}`);                       
                            $('#stripe-form').attr('action', respvalues.data.url);
                            $('.artistRequestId').val(respvalues.data.request_id); 
                            $('.artistPayableAmount').val(respvalues.data.amount);
                            $('.stripeSubmit').show();      
                            $('#releasePaymentToArtistByAdmin').modal('toggle');

                        }                      

                    }else{
                        setTimeout(function() {
                            location.reload();
                        }, 1000);
                    }                
                }
            });            
        }
    });
    
    if ($('#in_app_purchase').length) { 
        if ($('#in_app_purchase').is(':checked')) {
            $('#IosAppProductId').show();
        } else {
            $('#IosAppProductId').hide();
        }
    }
    $(document).on('change', '#in_app_purchase', function() {
        if ($(this).is(':checked')) {
            $('#IosAppProductId').show();
            $('.intAppProductId').addClass('require');
        } else {
            $('#IosAppProductId').hide();
            $('.intAppProductId').removeClass('require');
        }
    });
    
});





(function() {
    var $sumNote = $("#summernote")
        .summernote({
            callbacks: {
                onPaste: function(e, x, d) {
                    $sumNote.code(($($sumNote.code()).find("font").remove()));
                }
            },

            dialogsInBody: true,
            dialogsFade: true,
            disableDragAndDrop: true,
            //                disableResizeEditor:true,
            height: "150px",
            tableClassName: function() {
                alert("tbl");
                $(this)
                    .addClass("table table-bordered")

                .attr("cellpadding", 0)
                    .attr("cellspacing", 0)
                    .attr("border", 1)
                    .css("borderCollapse", "collapse")
                    .css("table-layout", "fixed")
                    .css("width", "100%");

                $(this)
                    .find("td")
                    .css("borderColor", "#ccc")
                    .css("padding", "4px");
            }
        })
        .data("summernote");



});