$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    
   
}); 

$(document).ready(function() {

    getrecruitmentRequestlist();

    get_position_title();
    get_sub_position_title();
    get_location();
    get_band_details();
    get_business();
    get_function();
    get_division();
    get_raisedby();
    get_approvedby();
});

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
                
                $('#af_business').html(html);

            }
        }
    });
}

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
                
                $('#af_raisedby').html(html);

            }
        }
    });
}

function get_approvedby(){

    $.ajax({
        type: "POST",
        url: get_approvedby_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].approved_by+'">'+data[index].approved_by+'</option>';
                }
                
                $('#af_approvedby').html(html);

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
                    html += '<option value="'+data[index].band_title+'">'+data[index].band_title+'</option>';
                }
                
                $('#af_band').html(html);

            }
        }
    });
}

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

// /* Formatting function for row details - modify as you need */
function format(d) {
    // `d` is the original data object for the row
    return '<table class="table table-striped" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" id="child_view">' +
        '<tr>' +
        '<td class="hide table-info">RFH No</td>' +
        '<td data-label="RFH No">' + d.rfh_no + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Business</td>' +
        '<td data-label="Business">' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Band</td>' +
        '<td data-label="Band">' + d.band_title + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Critical Position</td>' +
        '<td data-label="Critical Position">' + d.critical_position + '</td>' +
        '</tr>' +
        
        '<td class="hide table-info">Division</td>' +
        '<td data-label="Division">' + d.division + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Function</td>' +
        '<td data-label="Function">' + d.function + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Location</td>' +
        '<td data-label="Location">' + d.location + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Billing Status</td>' +
        '<td data-label="Billing Status">' + d.billing_status + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Interviewer</td>' +
        '<td data-label="Interviewer">' + d.interviewer + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Maximum CTC(Per Month)</td>' +
        '<td data-label="Maximum CTC(Per Month)">' + d.salary_range + '</td>' +
        '</tr>' +
        
        '</table>';
}

function getrecruitmentRequestlist(){
    table = $('#table1').DataTable({
        
        dom: 'lBfrtip',
        // buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
        buttons: [
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
                    'columns':':visible'
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
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            // $('head').append('<link rel="stylesheet" type="text/css" href="assets/vendors/choices.js/choices.min.css">');
            processInfo(this.api().page.info());

        },
        rowCallback: function( row, data, index ) {
            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }

            if (data.profile_ageing_status == "four_show_recruiter_ageing_highlight") {
                $(row).find('td:eq(8)').addClass('table-danger-orange');
            }
            if(data.profile_ageing_status == "three_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(8)').addClass('table-danger');
            }
            if(data.profile_ageing_status == "five_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(8)').addClass('table-danger-dark');
            }
        }  ,    
        ajax: {
            url:get_ticket_report_recruiter_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_sub_position_title = $('#af_sub_position_title').val();
                d.af_critical_position = $('#af_critical_position').val();
                d.af_position_status = $('#af_position_status').val();
                d.af_assigned_status = $('#af_assigned_status').val();
                d.af_salary_range = $('#af_salary_range').val();
                d.af_band = $('#af_band').val();
                d.af_location = $('#af_location').val();
                d.af_business = $('#af_business').val();
                // d.af_billing_status = $('#af_billing_status').val();
                d.af_function = $('#af_function').val();
                d.af_division = $('#af_division').val();
                d.af_raisedby = $('#af_raisedby').val();
                d.af_approvedby = $('#af_approvedby').val();          
                
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'No. of Position');
            $( row ).find('td:eq(6)').attr('data-label', 'CV Count');
            $( row ).find('td:eq(7)').attr('data-label', 'Current Status');
            $( row ).find('td:eq(8)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(9)').attr('data-label', 'Progile Ageing');
            $( row ).find('td:eq(10)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(11)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(12)').attr('data-label', 'Location');
            $( row ).find('td:eq(13)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(14)').attr('data-label', 'Action');

            $( row ).find('td:eq(15)').attr('data-label', 'RFH No');
            $( row ).find('td:eq(16)').attr('data-label', 'Business');
            $( row ).find('td:eq(17)').attr('data-label', 'Band');
            $( row ).find('td:eq(18)').attr('data-label', 'Critical Position');
            $( row ).find('td:eq(19)').attr('data-label', 'Division');
            $( row ).find('td:eq(20)').attr('data-label', 'Function');
            $( row ).find('td:eq(21)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(22)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(23)').attr('data-label', 'Maximum CTC(Per Month)');
            $( row ).find('td:eq(24)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(25)').attr('data-label', 'Approved by');
            
            
        },

        aoColumnDefs: [
            { 'visible': false, 'targets': [10,15,16,17,18,19,20,21,22,23,24,25] }
        ],

        // columnDefs        : [         // see https://datatables.net/reference/option/columns.searchable
        //     { 
        //         'searchable'    : false, 
        //         'targets'       : [0,9,10] 
        //     },
        // ],
        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": ''
            },

            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
            {data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number', searchable: true},
            {data: 'position_title', name: 'position_title', searchable: true},
            {data: 'sub_position_title', name: 'sub_position_title', searchable: true},
            {data: 'no_of_position', name: 'no_of_position', searchable: true},
            {data: 'cv_count', name: 'cv_count', searchable: false},
            {data: 'current_status', name: 'current_status', searchable: true},
            {data: 'ageing', name: 'ageing', searchable: false},
            {data: 'profile_ageing', name: 'profile_ageing', searchable: false},
            {data: 'open_date', name: 'open_date', searchable: false},
            {data: 'close_date', name: 'close_date', searchable: false},
            {data: 'location', name: 'location', searchable: true},
            {data: 'assigned_status', name: 'assigned_status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},

            {data: 'rfh_no', name: 'rfh_no', searchable: true},
            {data: 'business', name: 'business', searchable: true},
            {data: 'band_title', name: 'band', searchable: true},
            {data: 'critical_position', name: 'critical_position', searchable: true},
            {data: 'division', name: 'division', searchable: true},
            {data: 'function', name: 'function', searchable: true},
            {data: 'billing_status', name: 'billing_status', searchable: true},
            {data: 'interviewer', name: 'interviewer', searchable: true},
            {data: 'salary_range', name: 'salary_range', searchable: true},
            {data: 'raised_by', name: 'tr.raised_by', searchable: false},
            {data: 'approved_by', name: 'tr.approved_by', searchable: false},
        ],
        
        
    });
    
    $('#table1 tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table1 tbody').on('click', 'td.details-control', function() {
        var tr = $(this).closest('tr');
        var row = table.row(tr);

        if (row.child.isShown()) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        } else {
            // Open this row
            row.child(format(row.data())).show();
            tr.addClass('shown');
        }
    });

   
    
  } 
  function processInfo(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show').text(res_found);
}  
function ticket_edit_process(rfh_no){
    $('#show_edit_pop').click();
    $('#show_edit_pop_title').html('Edit Position Status - '+ rfh_no);
    $('#ticket_rfh_no').val(rfh_no);
    
}



$("#btnEditUpdate").on('click', function() {
    
    var ticket_rfh_no = $("#ticket_rfh_no").val();
    var ticket_status = $("#ticket_status").val();

    $.ajax({
        type: "POST",
        url: process_ticket_edit_link,
        data: { "ticket_rfh_no":ticket_rfh_no,"ticket_status":ticket_status,},
        success: function (data) {
            
            $('#close_edit_pop').click();

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        getrecruitmentRequestlist();
                    }, 2000);
            }
            else{
                Toastify({
                    text: "Request Failed..! Try Again",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

                setTimeout(
                    function() {
                        getrecruitmentRequestlist();
                    }, 2000);

                
            }
        }
    })
});

function show_advanced_filter(){
    
    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });

        // $("#table1_filter").css({ "display" :"none" });

    }
    else{
        $("#show_filter_div").css({ "display" :"none" });
        // $("#table1_filter").css({ "display" :"block" });


    }
}



$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_critical_position,#af_position_status,#af_assigned_status,#af_salary_range,#af_band,#af_location,#af_business,#af_billing_status,#af_function,#af_division,#af_raisedby,#af_approvedby")
.on('change', function() {
    
    getrecruitmentRequestlist();

});

$("#afClearbtn").on('click', function() {
    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");
    $("#af_sub_position_title").val("");
    $("#af_critical_position").val("");
    $("#af_position_status").val("");
    $("#af_assigned_status").val("");
    $("#af_salary_range").val("");
    $("#af_band").val("");
    $("#af_location").val("");
    $("#af_business").val("");
    $("#af_billing_status").val("");
    $("#af_function").val("");
    $("#af_division").val("");
    $("#af_raisedby").val("");
    $("#af_approvedby").val("");
    $(".js-select2").select2();
    
    getrecruitmentRequestlist();

});

function show_stagesof_recruitment_pop(hepl_recruitment_ref_number,rfh_no,assigned_to){
    // alert(hepl_recruitment_ref_number);
    $('#stages_of_recruitment_btn').click();

    $.ajax({
        type: "POST",
        url: get_stagesof_recruitment_rr_link,
        data: { "hepl_recruitment_ref_number":hepl_recruitment_ref_number,"rfh_no":rfh_no,"assigned_to":assigned_to,},
        success: function (data) {
            if(data.length !=0){
                $('#sor_one').text(data.stage_one);
                $('#sor_two').text(data.stage_two);
                $('#sor_three').text(data.stage_three);
                $('#sor_four').text(data.stage_four);
                $('#sor_five').text(data.stage_five);

                $('#fh_hepl_ref_no').text(hepl_recruitment_ref_number);

                if(data.get_original_rfh_date !=''){
                    $('#fh_open_date').text(data.get_original_rfh_date);
                }
                if(data.current_open_date !=''){
                    $('#fh_new_open_date_title').text("Re Open Date - "+data.current_open_date);
                }else{
                    $('#fh_new_open_date_title').text("");
                }
                $('#fh_position_title').text(data.position_title);

                if(data.get_candidate_no_show_details.length !=0){
                    $('#cns_div').html("<strong>Reopen Status - </strong>"+data.get_candidate_no_show_details[0].follow_up_status);

                }else{
                    $('#cns_div').html("");

                }
                // if(data.get_candidate_no_show_details.length !=0){
                //     var cns_html = '<div class="table-responsive">';
                //     cns_html += '<table  class="table table-bordered mb-0">';
                //     cns_html += '<thead>';
                //     cns_html += '<tr>';
                //     cns_html += '<th>S.No.</th>';
                //     cns_html += '<th>Candidate Name</th>';
                //     cns_html += '<th>Status </th>';
                //     cns_html += '<th>Updated On </th>';
                //     cns_html += '</tr>';
                //     cns_html += '</thead>';
                //     cns_html += '<tbody>';

                //     var sno = 1;
                //     for (let index = 0; index < data.get_candidate_no_show_details.length; index++) {
                //         var dateAr = data.get_candidate_no_show_details[0].created_on.split('-');
                //         var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

                //         cns_html += '<tr>';
                //         cns_html += '<td>'+sno+'</td>';
                //         cns_html += '<td>'+data.get_candidate_no_show_details[0].candidate_name+'</td>';
                //         cns_html += '<td>'+data.get_candidate_no_show_details[0].follow_up_status+'</td>';
                //         cns_html += '<td>'+newDate+'</td>';
                //         cns_html += '</tr>';

                //         sno++;
                //     }
                //     cns_html += '</tbody>';
                //     cns_html += '</table>';
                //     cns_html += '</div>';

                    // $('#cns_div').html("<strong>Reopen Status - </strong>"+data.get_candidate_no_show_details[0].follow_up_status);
                //     $('#fh_new_open_date_title').css("display","block");

                // }else{
                //     $('#fh_new_open_date_title').css("display","none");
                // }
            }
        }
    });
}