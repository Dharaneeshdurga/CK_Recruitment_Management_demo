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
    get_location();
    get_band_details();
    get_business();
    get_function();
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
        '<td data-label="Band">' + d.band + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Critical Position</td>' +
        '<td data-label="Critical Position">' + d.critical_position + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Business</td>' +
        '<td data-label="Business">' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
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
        
        dom: 'Bfrtip',
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
        lengthMenu: [[10, 50, 100, 200, 300, 400, 500, 1000, -1], [10, 50, 100, 200, 300, 400, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            // $('head').append('<link rel="stylesheet" type="text/css" href="assets/vendors/choices.js/choices.min.css">');

        },

        ajax: {
            url:get_ticket_report_recruiter_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_critical_position = $('#af_critical_position').val();
                d.af_position_status = $('#af_position_status').val();
                d.af_assigned_status = $('#af_assigned_status').val();
                d.af_salary_range = $('#af_salary_range').val();
                d.af_band = $('#af_band').val();
                d.af_location = $('#af_location').val();
                d.af_business = $('#af_business').val();
                d.af_billing_status = $('#af_billing_status').val();
                d.af_function = $('#af_function').val();
                                
                
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'No. of Position');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(7)').attr('data-label', 'Location');
            $( row ).find('td:eq(8)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(9)').attr('data-label', 'Action');
            
            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }
        },
        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },

            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'},
            {data: 'position_title', name: 'position_title'},
            {data: 'no_of_position', name: 'no_of_position'},
            {data: 'ageing', name: 'ageing'},
            {data: 'open_date', name: 'open_date'},
            {data: 'location', name: 'location'},
            {data: 'assigned_status', name: 'assigned_status'},
            
            {data: 'action', name: 'action', orderable: false, searchable: false},

           
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
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

$("#af_from_date,#af_to_date,#af_position_title,#af_critical_position,#af_position_status,#af_assigned_status,#af_salary_range,#af_band,#af_location,#af_business,#af_billing_status,#af_function")
.on('change', function() {
    
    getrecruitmentRequestlist();

});

$("#afClearbtn").on('click', function() {
    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");
    $("#af_critical_position").val("");
    $("#af_position_status").val("");
    $("#af_assigned_status").val("");
    $("#af_salary_range").val("");
    $("#af_band").val("");
    $("#af_location").val("");
    $("#af_business").val("");
    $("#af_billing_status").val("");
    $("#af_function").val("");
    getrecruitmentRequestlist();

});