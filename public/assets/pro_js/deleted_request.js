$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    
   
}); 

$(document).ready(function() {

    getdeletedRequestlist();
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
                    html += '<option value="'+data[index].id+'">'+data[index].band_title+'</option>';
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
    var edit_btn;
    var closed_date;
    
    // if(d.assigned_status_text =='Assigned'){
    //     edit_btn = '<button class="btn btn-sm btn-secondary" disabled id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button>';

    // }else{
    //     edit_btn = '<a href="edit_recruit_request_new?rfh_no='+d.rfh_no+'"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';

    // }
    edit_btn = '<a href="edit_recruit_request_new?rfh_no='+d.rfh_no+'"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';

    if(d.request_status =='Closed'){
        closed_date = d.close_date.split("-").reverse().join("-");

    }else{
        closed_date = '-';

    }
    return '<table class="table " cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" id="child_view">' +
        '<tr>' +
        '<td class="hide table-info"><strong>Business</strong></td>' +
        '<td>' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Band</strong></td>' +
        '<td>' + d.band_title + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Critical Position</strong></td>' +
        '<td>' + d.critical_position + '</td>' +
        '</tr>' +
        
        '<tr>' +
        '<td class="hide table-info"><strong>Division</strong></td>' +
        '<td>' + d.division + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Function</strong></td>' +
        '<td>' + d.function + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Location</strong></td>' +
        '<td>' + d.location + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Billing Status</strong></td>' +
        '<td>' + d.billing_status + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Interviewer</strong></td>' +
        '<td>' + d.interviewer + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Maximum CTC(Per Month)</strong></td>' +
        '<td>' + d.salary_range + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Closed Date</strong></td>' +
        '<td>' + closed_date + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Edit</strong></td>' +
        '<td>' + edit_btn + '</td>' +
        '</tr>' +

        
        '</table>';
}


// /* Formatting function for row details - modify as you need */
function format(d) {
    // `d` is the original data object for the row
    var edit_btn;
    var closed_date;
    
    // if(d.assigned_status_text =='Assigned'){
    //     edit_btn = '<button class="btn btn-sm btn-secondary" disabled id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button>';

    // }else{
    //     edit_btn = '<a href="edit_recruit_request_new?rfh_no='+d.rfh_no+'"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';

    // }
    edit_btn = '<a href="edit_recruit_request_new?rfh_no='+d.rfh_no+'"><button class="btn btn-sm btn-secondary" id="btnAssign" title="Edit Recruitment"><i class="bi bi-pen-fill"></i></button></a>';

    if(d.request_status =='Closed'){
        closed_date = d.close_date.split("-").reverse().join("-");

    }else{
        closed_date = '-';

    }
    return '<table class="table " cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" id="child_view">' +
        '<tr>' +
        '<td class="hide table-info"><strong>Business</strong></td>' +
        '<td>' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Band</strong></td>' +
        '<td>' + d.band_title + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Critical Position</strong></td>' +
        '<td>' + d.critical_position + '</td>' +
        '</tr>' +
        
        '<tr>' +
        '<td class="hide table-info"><strong>Division</strong></td>' +
        '<td>' + d.division + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Function</strong></td>' +
        '<td>' + d.function + '</td>' +
        '</tr>' +
        
        '<tr>' +
        '<td class="hide table-info"><strong>Billing Status</strong></td>' +
        '<td>' + d.billing_status + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Interviewer</strong></td>' +
        '<td>' + d.interviewer + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Maximum CTC(Per Month)</strong></td>' +
        '<td>' + d.salary_range + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Closed Date</strong></td>' +
        '<td>' + closed_date + '</td>' +
        '</tr>' +
        
        
        '</table>';
}

function getdeletedRequestlist(){
    table = $('#table1').DataTable({
        
        dom: 'lBfrtip',
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
        lengthMenu: [[15, 50, 100, 250, 500, -1], [15, 50, 100, 250, 500, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        scrollY: 800,
        scrollCollapse: true,
        autoWidth: false,
        aoColumnDefs: [
            { 'visible': false, 'targets': [12,13,14,15,16,17,18,19,20,21,22,23,24] }
        ],

        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            processInfo(this.api().page.info());

        },

        ajax: {
            url:get_deleted_request_list_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_sub_position_title = $('#af_sub_position_title').val();
                d.af_band = $('#af_band').val();
                d.af_critical_position = $('#af_critical_position').val();
                d.af_position_status = $('#af_position_status').val();
                d.af_billable = $('#af_billable').val();
                d.af_location = $('#af_location').val();
                d.af_business = $('#af_business').val();
                d.af_function = $('#af_function').val();
                d.af_division = $('#af_division').val();                
                d.af_raisedby = $('#af_raisedby').val();
                d.af_approvedby = $('#af_approvedby').val();
                }
           },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'RFH No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'No. of Position');
            $( row ).find('td:eq(6)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(7)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(8)').attr('data-label', 'Location');
            $( row ).find('td:eq(9)').attr('data-label', 'Remark');
            $( row ).find('td:eq(10)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(11)').attr('data-label', 'Action');

            $( row ).find('td:eq(12)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(13)').attr('data-label', 'Position Status');
            $( row ).find('td:eq(14)').attr('data-label', 'Business');
            $( row ).find('td:eq(15)').attr('data-label', 'Band');
            $( row ).find('td:eq(16)').attr('data-label', 'Critical Position');
            $( row ).find('td:eq(17)').attr('data-label', 'Division');
            $( row ).find('td:eq(18)').attr('data-label', 'Function');
            $( row ).find('td:eq(19)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(20)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(21)').attr('data-label', 'Maximum CTC(Per Month)');
            $( row ).find('td:eq(22)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(23)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(24)').attr('data-label', 'Approved by');
            
            // if (data.tat_process == "show_tat_highlight") {
            //     $(row).addClass('table-danger');
            // }
            

        },
        columnDefs: [   
            { 
                'searchable'    : false, 
                'targets'       : [0,1,5,6,9,10,21] 
            },
        ],
        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": ''
            },

            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
            {data: 'rfh_no', name: 'rr.rfh_no', searchable: true},
            {data: 'position_title', name: 'rr.position_title', searchable: true},
            {data: 'sub_position_title', name: 'rr.sub_position_title', searchable: true},
            {data: 'no_of_position', name: 'rr.no_of_position', searchable: true},
            {data: 'ageing', name: 'ageing', searchable: false},
            {data: 'open_date', name: 'open_date', searchable: false},
            {data: 'location', name: 'rr.location', searchable: true},
            {data: 'delete_remark', name: 'tr.delete_remark', searchable: true},
            {data: 'assigned_status', name: 'assigned_status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},

            {data: 'as_title', name: 'rr.assigned_status', searchable: true},
            {data: 'ps_title', name: 'rr.request_status', searchable: true},
            {data: 'business', name: 'rr.business', searchable: true},
            {data: 'band_title', name: 'rr.band', searchable: true},
            {data: 'critical_position', name: 'rr.critical_position', searchable: true},
            {data: 'division', name: 'rr.division', searchable: true},
            {data: 'function', name: 'rr.function', searchable: true},
            {data: 'billing_status', name: 'rr.billing_status', searchable: true},
            {data: 'interviewer', name: 'rr.interviewer', searchable: true},
            {data: 'salary_range', name: 'rr.salary_range', searchable: true},
            {data: 'closed_date', name: 'closed_date', searchable: false},
            {data: 'raised_by', name: 'tr.name', searchable: true},
            {data: 'approved_by', name: 'tr.approved_by', searchable: true},
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

function show_advanced_filter(){
    
    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_critical_position,#af_position_status,#af_band,#af_location,#af_business,#af_function,#af_billable,#af_division,#af_raisedby,#af_approvedby")
.on('change', function() {
    
    getdeletedRequestlist();

});

$("#afClearbtn").on('click', function() {
    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");
    $("#af_sub_position_title").val("");
    $("#af_critical_position").val("");
    $("#af_position_status").val("");
    $("#af_band").val("");
    $("#af_location").val("");
    $("#af_business").val("");
    $("#af_function").val("");
    $("#af_billable").val("");
    $("#af_division").val("");

    $("#af_raisedby").val("");
    $("#af_approvedby").val("");
    $(".js-select2").select2();
    
    getdeletedRequestlist();

});

