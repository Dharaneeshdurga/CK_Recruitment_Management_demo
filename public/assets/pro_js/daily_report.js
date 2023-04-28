

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

// get task details on load
$(document).ready(function() {
    get_recruiter_list();
    get_open_position();
    get_position_title();
    get_sub_position_title();
    get_location();
    get_band_details();
    get_business();
    get_raisedby();

    get_source();
    get_function();
    get_division();
    get_interviewer();
});

function get_function(){

    $.ajax({
        type: "POST",
        url: get_function_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].function+'">'+data[index].function+'</option>';
                }

                $('#af_function').html(html);
                $('#afc_function').html(html);

            }
        }
    });
}

function get_division(){

    $.ajax({
        type: "POST",
        url: get_division_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].division+'">'+data[index].division+'</option>';
                }

                $('#af_division').html(html);
                $('#afc_division').html(html);

            }
        }
    });
}
function get_interviewer(){
    $.ajax({
        type: "POST",
        url: get_interviewer_list_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].interviewer+'">'+data[index].interviewer+'</option>';
                }

                $('#af_interviewer').html(html);

            }
        }
    });
}

function get_source(){
    $.ajax({
        type: "POST",
        url: get_source_list_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].candidate_source+'">'+data[index].candidate_source+'</option>';
                }

                $('#afc_source').html(html);

            }
        }
    });
}

function get_recruiter_list(){
   // alert("call");
    $.ajax({
        type: "POST",
        url: get_recruiter_list_link_closed,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                }

                $('#afc_created_by').html(html);

            }
        }
    });
}

function get_position_title(){

    $.ajax({
        type: "POST",
        url: get_position_title_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].position_title+'">'+data[index].position_title+'</option>';
                }

                $('#afc_position_title').html(html);
                $('#af_position_title').html(html);

            }
        }
    });
}

function get_sub_position_title(){

    $.ajax({
        type: "POST",
        url: get_sub_position_title_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].sub_position_title+'">'+data[index].sub_position_title+'</option>';
                }

                $('#afc_sub_position_title').html(html);
                $('#af_sub_position_title').html(html);

            }
        }
    });
}

function get_business(){

    $.ajax({
        type: "POST",
        url: get_business_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].business+'">'+data[index].business+'</option>';
                }

                $('#afc_business').html(html);
                $('#af_business').html(html);

            }
        }
    });
}

function get_location(){

    $.ajax({
        type: "POST",
        url: get_location_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].location+'">'+data[index].location+'</option>';
                }

                $('#afc_location').html(html);
                $('#af_location').html(html);

            }
        }
    });
}

function get_raisedby(){

    $.ajax({
        type: "POST",
        url: get_raisedby_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].name+'">'+data[index].name+'</option>';
                }

                $('#afc_raisedby').html(html);
                $('#af_raisedby').html(html);

            }
        }
    });
}

function get_band_details(){

    $.ajax({
        type: "POST",
        url: get_band_details_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].id+'">'+data[index].band_title+'</option>';
                }

                $('#afc_band').html(html);

            }
        }
    });
}

$("#open_position-tab").on('click', function() {

    get_open_position();

});
$("#closed_position-tab").on('click', function() {

    get_closed_position();

});
// for export all data
function newexportaction(e, dt, button, config) {
    var self = this;
    var oldStart = dt.settings()[0]._iDisplayStart;
    dt.one('preXhr', function (e, s, data) {
        // Just this once, load all data from the server...
        data.start = 0;
        data.length = 2147483647;
        dt.one('preDraw', function (e, settings) {
            // Call the original action function
            if (button[0].className.indexOf('buttons-copy') >= 0) {
                $.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-excel') >= 0) {
                $.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-csv') >= 0) {
                $.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-pdf') >= 0) {
                $.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
                    $.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
                    $.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
            } else if (button[0].className.indexOf('buttons-print') >= 0) {
                $.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
            }
            dt.one('preXhr', function (e, s, data) {
                // DataTables thinks the first item displayed is index 0, but we're not drawing that.
                // Set the property to what it was before exporting.
                settings._iDisplayStart = oldStart;
                data.start = oldStart;
            });
            // Reload the grid with the original page. Otherwise, API functions like table.cell(this) don't work properly.
            setTimeout(dt.ajax.reload, 0);
            // Prevent rendering of the full data to the DOM
            return false;
        });
    });
    // Requery the server with the new one-time export settings
    dt.ajax.reload();
}

function get_open_position(){

    table_cot = $('#table_open').DataTable({

        dom: 'lBfrtip',
        "buttons": [
            {
                "extend": 'copy',
                "text": '<i class="bi bi-clipboard" ></i>  Copy',
                "titleAttr": 'Copy',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'excel',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "\n");
                        }
                    }
                },
                "action": newexportaction
            },
            {
                "extend": 'csv',
                "text": '<i class="bi bi-file-text" ></i>  CSV',
                "titleAttr": 'CSV',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "\n");
                        }
                    }
                },
                "action": newexportaction
            },
            {
                "extend": 'pdf',
                'orientation': 'landscape',
                'pageSize': 'LEGAL',

                "text": '<i class="bi bi-file-break" ></i>  PDF',
                "titleAttr": 'PDF',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "\n");
                        }
                    }
                },
                "action": newexportaction
            },
            {
                "extend": 'print',
                "text": '<i class="bi bi-printer"></i>  Print',
                "titleAttr": 'Print',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "<br/>");
                        }
                    }
                },
                "action": newexportaction
            },
            {
                "extend": 'colvis',
                "text": '<i class="bi bi-eye" ></i>  Colvis',
                "titleAttr": 'Colvis',
                // "action": newexportaction
            },

        ],
        lengthMenu: [[15, 50, 100, 250, 500, -1], [15, 50, 100, 250, 500, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        scrollY: 800,
        scrollCollapse: true,
        drawCallback: function() {
            processInfo_tab1(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        rowCallback: function( row, data, index ) {
            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }

            if (data.profile_ageing_status == "four_show_recruiter_ageing_highlight") {
                $(row).find('td:eq(7)').addClass('table-danger-orange');
            }
            if(data.profile_ageing_status == "three_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(7)').addClass('table-danger');
            }
            if(data.profile_ageing_status == "five_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(7)').addClass('table-danger-dark');
            }
        }  ,
        ajax: {
            url: get_open_position_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_sub_position_title = $('#af_sub_position_title').val();
                d.af_location = $('#af_location').val();
                d.af_business = $('#af_business').val();
                d.af_teams = $('#af_teams').val();
                d.af_raisedby = $('#af_raisedby').val();
                d.af_interviewer = $('#af_interviewer').val();
                d.af_billable = $('#af_billable').val();
                d.af_function = $('#af_function').val();
                d.af_division = $('#af_division').val();

            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(2)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(3)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(5)').attr('data-label', 'Position Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Total CV');
            $( row ).find('td:eq(7)').attr('data-label', 'Profile Ageing');
            $( row ).find('td:eq(8)').attr('data-label', 'Location');
            $( row ).find('td:eq(9)').attr('data-label', 'Business');
            $( row ).find('td:eq(10)').attr('data-label', 'Recruiter');
            $( row ).find('td:eq(11)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(12)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(13)').attr('data-label', 'Current Status');
            $( row ).find('td:eq(14)').attr('data-label', 'Last Modified');
            $( row ).find('td:eq(15)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(16)').attr('data-label', 'Division');
            $( row ).find('td:eq(17)').attr('data-label', 'Function');
            $( row ).find('td:eq(18)').attr('data-label', 'Maximum CTC(Per Month)');


        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'open_date', name: 'open_date'  },
            {   data: 'position_ageing', name: 'position_ageing'  },
            {   data: 'cv_count', name: 'cv_count'  },
            {   data: 'profile_ageing', name: 'profile_ageing'  },
            {   data: 'location', name: 'location'  },
            {   data: 'business', name: 'business'  },
            {   data: 'recruiters', name: 'recruiters'  },
            {   data: 'request_raised_by', name: 'request_raised_by'  },
            {   data: 'interviewer', name: 'interviewer'  },
            {   data: 'current_status', name: 'current_status'  },
            {   data: 'last_updated_at', name: 'last_updated_at'  },

            {   data: 'billing_status', name: 'billing_status'  },
            {   data: 'division', name: 'division'  },
            {   data: 'function', name: 'function'  },
            {   data: 'salary_range', name: 'salary_range'  },

        ],
    });
}

function get_closed_position(){

    table_cot = $('#table_closed').DataTable({

        dom: 'lBfrtip',
        "buttons": [
            {
                "extend": 'copy',
                "text": '<i class="bi bi-clipboard" ></i>  Copy',
                "titleAttr": 'Copy',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'excel',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'csv',
                "text": '<i class="bi bi-file-text" ></i>  CSV',
                "titleAttr": 'CSV',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'pdf',
                "text": '<i class="bi bi-file-break" ></i>  PDF',
                "titleAttr": 'PDF',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'print',
                "text": '<i class="bi bi-printer"></i>  Print',
                "titleAttr": 'Print',
                "exportOptions": {
                    'columns': ':visible'
                },
                "action": newexportaction
            },
            {
                "extend": 'colvis',
                "text": '<i class="bi bi-eye" ></i>  Colvis',
                "titleAttr": 'Colvis',
                // "action": newexportaction
            },

        ],
        lengthMenu: [[15, 50, 100, 250, 500, -1], [15, 50, 100, 250, 500, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        scrollY: 800,
        scrollCollapse: true,
        drawCallback: function() {
            processInfo_tab2(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        ajax: {
            url: get_closed_position_link,
            type: 'POST',
            data: function (d) {
                d.afc_from_date = $('#afc_from_date').val();
                d.afc_to_date = $('#afc_to_date').val();
                d.afc_position_title = $('#afc_position_title').val();
                d.afc_sub_position_title = $('#afc_sub_position_title').val();
                d.afc_source = $('#afc_source').val();
                d.afc_location = $('#afc_location').val();
                d.afc_business = $('#afc_business').val();
                d.afc_band = $('#afc_band').val();
                d.afc_created_by = $('#afc_created_by').val();
                d.afc_raisedby = $('#afc_raisedby').val();
                d.afc_billable = $('#afc_billable').val();
                d.afc_teams = $('#afc_teams').val();
                d.afc_function = $('#afc_function').val();
                d.afc_division = $('#afc_division').val();
                d.afc_doj_from_date = $('#afc_doj_from_date').val();
                d.afc_doj_to_date = $('#afc_doj_to_date').val();

            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(2)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(6)').attr('data-label', 'Gender');
            $( row ).find('td:eq(7)').attr('data-label', 'Source');
            $( row ).find('td:eq(8)').attr('data-label', 'DOJ');
            $( row ).find('td:eq(9)').attr('data-label', 'Location');
            $( row ).find('td:eq(10)').attr('data-label', 'Business');
            $( row ).find('td:eq(11)').attr('data-label', 'Band');
            $( row ).find('td:eq(12)').attr('data-label', 'Recruiter');
            $( row ).find('td:eq(13)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(14)').attr('data-label', 'Billing Status<');
            $( row ).find('td:eq(15)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(16)').attr('data-label', 'Closed CTC Salary/Month');
            $( row ).find('td:eq(17)').attr('data-label', 'Request Status');
            $( row ).find('td:eq(18)').attr('data-label', 'Division');
            $( row ).find('td:eq(19)').attr('data-label', 'Function');
            $( row ).find('td:eq(20)').attr('data-label', 'Current Status');
            $( row ).find('td:eq(21)').attr('data-label', 'Assigned date');


        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'open_date', name: 'open_date'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'gender', name: 'gender'  },
            {   data: 'candidate_source', name: 'candidate_source'  },
            {   data: 'date_of_joining', name: 'date_of_joining'  },
            {   data: 'location', name: 'location'  },
            {   data: 'business', name: 'business'  },
            {   data: 'band', name: 'band'  },
            {   data: 'closed_by_name', name: 'closed_by_name'  },
            {   data: 'request_raised_by', name: 'request_raised_by'  },
            {   data: 'billing_status', name: 'billing_status'  },
            {   data: 'close_date', name: 'close_date'  },
            {   data: 'closed_salary', name: 'closed_salary'  },
            {   data: 'request_status', name: 'request_status'  },
            {   data: 'division', name: 'division'  },
            {   data: 'function', name: 'function'  },
            {   data: 'current_status', name: 'current_status'  },
            {   data: 'assigned_date', name: 'assigned_date'  },
        ],
    });
}

function processInfo_tab1(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab1').text(res_found);
}

function processInfo_tab2(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab2').text(res_found);
}
function show_advanced_filter_closed(){

    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

function show_advanced_filter_open(){

    if ($('#show_filter_div_open').css('display') == 'none') {
        $("#show_filter_div_open").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div_open").css({ "display" :"none" });

    }
}


$("#afc_from_date,#afc_to_date,#afc_position_title,#afc_sub_position_title,#afc_source,#afc_location,#afc_business,#afc_band,#afc_created_by,#afc_raisedby,#afc_billable,#afc_teams,#afc_function,#afc_division,#afc_doj_from_date,#afc_doj_to_date")
.on('change', function() {

    get_closed_position();

});

$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_location,#af_business,#af_teams,#af_raisedby,#af_interviewer,#af_billable,#af_division,#af_function")
.on('change', function() {

    get_open_position();

});

$("#afClearbtn").on('click', function() {

    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");
    $("#af_sub_position_title").val("");
    $("#af_location").val("");
    $("#af_business").val("");
    $("#af_teams").val("");
    $("#af_raisedby").val("");
    $("#af_interviewer").val("");

    $(".js-select2").select2();

    get_open_position();

});

$("#afcClearbtn").on('click', function() {

    $("#afc_from_date").val("");
    $("#afc_to_date").val("");
    $("#afc_position_title").val("");
    $("#afc_sub_position_title").val("");
    $("#afc_source").val("");
    $("#afc_location").val("");
    $("#afc_business").val("");
    $("#afc_band").val("");
    $("#afc_created_by").val("");
    $("#afc_raisedby").val("");
    $("#afc_billable").val("");
    $("#afc_teams").val("");
    $("#afc_doj_from_date").val("");
    $("#afc_doj_to_date").val("");

    $(".js-select2").select2();

    get_closed_position();

});
