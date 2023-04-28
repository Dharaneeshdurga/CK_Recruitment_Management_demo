$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


});

$(document).ready(function() {

    getrecruitmentRequestlist();
    getlast_rfhno();
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
        '<td class="hide table-info"><strong>Request Raised By</strong></td>' +
        '<td>' + d.raised_by + '</td>' +
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
        '<tr>' +
        '<td class="hide table-info"><strong>Approval for hire</strong></td>' +
        '<td>' + d.approval_for_hire + '</td>' +
        '</tr>' +

        '</table>';
}

function getrecruitmentRequestlist(){
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
            { 'visible': false, 'targets': [10,11,12,13,14,15,16,17,18,19,20,21,22,23,24] }
        ],

        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            processInfo_tab1(this.api().page.info());

        },

        ajax: {
            url:get_recruitment_request_def_list_link,
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
            $( row ).find('td:eq(4)').attr('data-label', 'No. of Position');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(7)').attr('data-label', 'Location');
            $( row ).find('td:eq(8)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(9)').attr('data-label', 'Action');

            $( row ).find('td:eq(10)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(11)').attr('data-label', 'Position Status');
            $( row ).find('td:eq(12)').attr('data-label', 'Business');
            $( row ).find('td:eq(13)').attr('data-label', 'Band');
            $( row ).find('td:eq(14)').attr('data-label', 'Critical Position');
            $( row ).find('td:eq(15)').attr('data-label', 'Division');
            $( row ).find('td:eq(16)').attr('data-label', 'Function');
            $( row ).find('td:eq(17)').attr('data-label', 'Location');
            $( row ).find('td:eq(18)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(19)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(20)').attr('data-label', 'Maximum CTC(Per Month)');
            $( row ).find('td:eq(21)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(22)').attr('data-label', 'Edit');
            $( row ).find('td:eq(23)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(24)').attr('data-label', 'Approved by');
            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }


        },
        columnDefs: [
            {
                'searchable'    : false,
                'targets'       : [0,1,5,6,8,9,20,21,22,23,24]
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
            {data: 'no_of_position', name: 'rr.no_of_position', searchable: true},
            {data: 'ageing', name: 'ageing', searchable: false},
            {data: 'open_date', name: 'open_date', searchable: false},
            {data: 'location', name: 'rr.location', searchable: true},
            {data: 'assigned_status', name: 'assigned_status', searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},

            {data: 'as_title', name: 'rr.assigned_status', searchable: true},
            {data: 'ps_title', name: 'rr.request_status', searchable: true},
            {data: 'business', name: 'rr.business', searchable: true},
            {data: 'band_title', name: 'rr.band', searchable: true},
            {data: 'critical_position', name: 'rr.critical_position', searchable: true},
            {data: 'division', name: 'rr.division', searchable: true},
            {data: 'function', name: 'rr.function', searchable: true},
            {data: 'location', name: 'rr.location', searchable: true},
            {data: 'billing_status', name: 'rr.billing_status', searchable: true},
            {data: 'interviewer', name: 'rr.interviewer', searchable: true},
            {data: 'salary_range', name: 'rr.salary_range', searchable: true},
            {data: 'closed_date', name: 'closed_date', searchable: false},
            {data: 'edit_btn', name: 'edit_btn', searchable: false},
            {data: 'raised_by', name: 'raised_by', searchable: false},
            {data: 'approved_by', name: 'approved_by', searchable: false},
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

function processInfo_tab1(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab1').text(res_found);
}

function processInfo_tab2(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab2').text(res_found);
}

function processInfo_tab3(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab3').text(res_found);
}
function processInfo_tab4(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab4').text(res_found);
}


function getrecruitmentRequestlist_assigned(){
    table = $('#table2').DataTable({
        // order: [[0, 'asc']],

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
        lengthMenu:  [[15, 50, 100, 250, 500, -1], [15, 50, 100, 250, 500, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        scrollY: 800,
        scrollCollapse: true,
        autoWidth: false,
        aoColumnDefs: [
            { 'visible': false, 'targets': [11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34] }
        ],
        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            processInfo_tab2(this.api().page.info());
        },

        ajax: {
            url:get_recruitment_request_def_list_ag_link,
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
            $( row ).find('td:eq(9)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(10)').attr('data-label', 'Action');

            $( row ).find('td:eq(11)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(12)').attr('data-label', 'Position Status');
            $( row ).find('td:eq(13)').attr('data-label', 'Business');
            $( row ).find('td:eq(14)').attr('data-label', 'Band');
            $( row ).find('td:eq(15)').attr('data-label', 'Critical Position');
            $( row ).find('td:eq(16)').attr('data-label', 'Division');
            $( row ).find('td:eq(17)').attr('data-label', 'Function');
            $( row ).find('td:eq(18)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(19)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(20)').attr('data-label', 'Maximum CTC(Per Month)');
            $( row ).find('td:eq(21)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(22)').attr('data-label', 'Edit');
            $( row ).find('td:eq(23)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(24)').attr('data-label', 'Approved by');

            $( row ).find('td:eq(25)').attr('data-label', 'Ticket Number');
            $( row ).find('td:eq(26)').attr('data-label', 'Mobile');
            $( row ).find('td:eq(27)').attr('data-label', 'Email');
            $( row ).find('td:eq(28)').attr('data-label', 'Location Preferred');
            $( row ).find('td:eq(29)').attr('data-label', 'JD / Roles & Responsibilities');
            $( row ).find('td:eq(30)').attr('data-label', 'Qualification');
            $( row ).find('td:eq(31)').attr('data-label', 'Essential Skill sets');
            $( row ).find('td:eq(32)').attr('data-label', 'Good to have Skill sets');
            $( row ).find('td:eq(33)').attr('data-label', 'Experience');
            $( row ).find('td:eq(34)').attr('data-label', 'Any other specific consideration');

            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }


        },
        "aaSorting": [2,'desc'],
        columnDefs: [
            {
                'searchable'    : false,
                'targets'       : [0,1,5,6,8,9,20,21,22,23,24,25,26,27,28,29,30,31,32]
            },
        ],
        createdRow: function (row, data, dataIndex) {
            // $(row).find('td:eq(6)').attr('data-sort', data.ageing);
            $(row).find('td:eq(6)').attr('data-order', data.ageing);
        },
        "iDataSort": 6,
        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": ''
            },

            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false},
            {data: 'rfh_no', name: 'rr.rfh_no', searchable: true, sortable : true,},
            {data: 'position_title', name: 'rr.position_title', searchable: true},
            {data: 'sub_position_title', name: 'rr.sub_position_title', searchable: true},
            {data: 'no_of_position', name: 'rr.no_of_position', searchable: true},
            {data: 'ageing', name: 'ageing', searchable: false, type: "num-fmt@data-order",sort: "num-fmt@data-order",_: "num-fmt@data-order",},
            {data: 'open_date', name: 'open_date', searchable: false},
            {data: 'location', name: 'rr.location', searchable: true},
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
            {data: 'edit_btn', name: 'edit_btn', searchable: false},

            {data: 'raised_by', name: 'tr.raised_by', searchable: false},
            {data: 'approved_by', name: 'tr.approved_by', searchable: false},

            {data: 'mobile', name: 'tr.mobile', searchable: false},
            {data: 'email', name: 'tr.email', searchable: false},

            {data: 'ticket_number', name: 'tr.ticket_number', searchable: false},
            {data: 'location_preferred', name: 'tr.location_preferred', searchable: false},
            {data: 'jd_roles', name: 'tr.jd_roles', searchable: false},
            {data: 'qualification', name: 'tr.qualification', searchable: false},
            {data: 'essential_skill', name: 'tr.essential_skill', searchable: false},
            {data: 'good_skill', name: 'tr.good_skill', searchable: false},
            {data: 'experience', name: 'tr.experience', searchable: false},
            {data: 'any_specific', name: 'tr.any_specific', searchable: false},
        ],


    });

    $('#table2 tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table2 tbody').on('click', 'td.details-control', function() {
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
// yet_to_allocate tab click
$("#yet_to_allocate-tab").on('click', function() {

    $("#advanced_filter").css({ "display" :"block" });

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

    getrecruitmentRequestlist();

});
// allocated_list tab click
$("#allocated_list-tab").on('click', function() {
    $("#advanced_filter").css({ "display" :"block" });

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

    getrecruitmentRequestlist_assigned();

});
// offer released tab click
$("#offer_released-tab").on('click', function() {

    $("#advanced_filter").css({ "display" :"none" });

    table = $('#offer_released_tba').DataTable({

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
        autoWidth: false,

        drawCallback : function() {

            var status = $('#uploaded_tb').DataTable().column(2).data();
            // $('#show_cv_action_status').text(status[0]);
            processInfo_tab3(this.api().page.info());

        },
        ajax: {
            url: get_offer_released_bc_link,
            type: 'POST',
            data: function (d) {
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref No');
            $( row ).find('td:eq(3)').attr('data-label', 'Closed date');
            $( row ).find('td:eq(4)').attr('data-label', 'Closed Salary');
            $( row ).find('td:eq(5)').attr('data-label', 'Salary Review');
            $( row ).find('td:eq(6)').attr('data-label', 'Joining Type');
            $( row ).find('td:eq(7)').attr('data-label', 'Date of Joining');
            $( row ).find('td:eq(8)').attr('data-label', 'Remark');
            $( row ).find('td:eq(9)').attr('data-label', 'Candidate CV');
            $( row ).find('td:eq(10)').attr('data-label', 'Followup History');
            // $( row ).find('td:eq(11)').attr('data-label', 'Action');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'closed_date', name: 'closed_date'  },
            {   data: 'closed_salary', name: 'closed_salary'  },
            {   data: 'salary_review', name: 'salary_review'  },
            {   data: 'joining_type', name: 'joining_type'  },
            {   data: 'date_of_joining', name: 'date_of_joining'  },
            {   data: 'remark', name: 'remark'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'followup_history', name: 'followup_history'  },
            // {   data: 'action', name: 'action'  },
        ],
    });
});
// candidate onboard
$("#candidate_onboarded-tab").on('click', function() {

    $("#advanced_filter").css({ "display" :"none" });

    table_cot = $('#c_onboarded').DataTable({

        dom: 'lBfrtip',
        "buttons": [
            {
                "extend": 'copy',
                "text": '<i class="bi bi-clipboard" ></i>  Copy',
                "titleAttr": 'Copy',
                "exportOptions": {
                    'columns': [1,2,3,4,5,8]
                },
                "action": newexportaction
            },
            {
                "extend": 'excel',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': [1,2,3,4,5,8]
                },
                "action": newexportaction
            },
            {
                "extend": 'csv',
                "text": '<i class="bi bi-file-text" ></i>  CSV',
                "titleAttr": 'CSV',
                "exportOptions": {
                    'columns': [1,2,3,4,5,8]
                },
                "action": newexportaction
            },
            {
                "extend": 'pdf',
                "text": '<i class="bi bi-file-break" ></i>  PDF',
                "titleAttr": 'PDF',
                "exportOptions": {
                    'columns': [1,2,3,4,5,8]
                },
                "action": newexportaction
            },
            {
                "extend": 'print',
                "text": '<i class="bi bi-printer"></i>  Print',
                "titleAttr": 'Print',
                "exportOptions": {
                    'columns': [1,2,3,4,5,8]
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

        drawCallback : function() {

            processInfo_tab4(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        ajax: {
            url: get_candidate_onborded_history_bc_link,
            type: 'POST',
            data: function (d) {
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'Position');
            $( row ).find('td:eq(3)').attr('data-label', 'Sub Position');
            $( row ).find('td:eq(4)').attr('data-label', 'Candidate Source');
            $( row ).find('td:eq(5)').attr('data-label', 'Gender');
            $( row ).find('td:eq(6)').attr('data-label', 'CTC');
            $( row ).find('td:eq(7)').attr('data-label', 'CV');
            $( row ).find('td:eq(8)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(9)').attr('data-label', 'Status');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'candidate_source', name: 'candidate_source'  },
            {   data: 'gender', name: 'gender'  },
            {   data: 'ctc', name: 'ctc'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'history', name: 'history'  },
            {   data: 'status', name: 'status'  },
        ],
    });

});

function getlast_rfhno(){
    $.ajax({
        type: "POST",
        url: getlast_rfhno_link,
        data: { },
        success: function (data) {
            if(data.response !='no_data'){
                $('#last_rfhno').html('<strong>'+data.response+'</strong>');
            }
        }
    })
}

function ticket_edit_process(rfh_no){
    $('#show_edit_pop').click();
    $('#show_edit_pop_title').html('Edit Position Status - '+ rfh_no);
    $('#ticket_rfh_no').val(rfh_no);

}

function show_advanced_filter(){

    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
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


function ticket_delete_process(rfh_no){
    $('#confirmbox').click();
    $("#confirmSubmit").on('click', function() {
        var delete_remark = $('#delete_remark').val();
        if(delete_remark ==''){
            $('#error_delete_remark').css({"display":"block"});

            setTimeout(
                function() {
                    $('#error_delete_remark').css({"display":"none"});

                }, 2000);

            return false;
        }else{
            $('#error_delete_remark').css({"display":"none"});

            $('#close_delete_pop').click();
            $.ajax({
                url: process_ticket_delete_link,
                method: "POST",
                data:{"rfh_no":rfh_no,"delete_remark":delete_remark,},
                dataType: "json",
                success: function(data) {

                    if(data.response =='success'){
                        Toastify({
                            text: "Deleted Successfully",
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

            });
        }

    });
    $("#cancelSubmit").on('click', function() {

        $('#modal_close').click();
        location.reload();

        return false;
    });
}


$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_critical_position,#af_position_status,#af_band,#af_location,#af_business,#af_function,#af_billable,#af_division,#af_raisedby,#af_approvedby")
.on('change', function() {

    var current_tab_title = $("#myTab > .nav-item > a.active").html();

    if(current_tab_title == 'Yet To Allocate List'){
        getrecruitmentRequestlist();

    }else{
        getrecruitmentRequestlist_assigned();
    }

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

    var current_tab_title = $("#myTab > .nav-item > a.active").html();

    if(current_tab_title == 'Yet To Allocate List'){
        getrecruitmentRequestlist();

    }else{
        getrecruitmentRequestlist_assigned();
    }

});

function candidate_follow_up(created_by,hepl_recruitment_ref_number,cdID,candidate_name){

    $('#fh_candidate_name').text(candidate_name);

    $.ajax({
        type: "POST",
        url: candidate_follow_up_history_bc_link,
        data: { "cdID":cdID,"created_by":created_by,"hepl_recruitment_ref_number":hepl_recruitment_ref_number,},
        success: function (data) {
            if(data.ch_pdr.length !=0){
                $('#fh_hepl_ref_no').text(hepl_recruitment_ref_number);
                $('#fh_recruiter').text(data.ch_pdr[0].recruiter_name);
                $('#fh_position').text(data.ch_pdr[0].position_title);
            }
            if(data.chr.length != 0){
                var html = '';
                var sno = 1;
                for (let index = 0; index < data.chr.length; index++) {

                    var date = data.chr[index].created_on;
                    var newDate = date.split("-").reverse().join("-");

                    html +='<tr>';
                    html +='<td data-label="Sno.">';
                    html +=''+sno+'';
                    html +='</td>';
                    html +='<td data-label="Follow Up Status">';
                    html +=''+data.chr[index].follow_up_status+'';
                    html +='</td>';
                    html +='<td data-label="Created On">';
                    html +=''+newDate+'';
                    html +='</td>';
                    html +='</tr>';

                    sno++;

                    if(data.chr[index].follow_up_status =='Offer Released'){
                        $.ajax({
                            type: "POST",
                            url: get_offer_released_report_bc_link,
                            data: { "cdID":cdID,},
                            success: function (data) {


                                if(data.ord.length != 0){
                                    var or_detail = '<div class="table-responsive">';
                                    or_detail +='<br>';
                                    or_detail +='<h5>Offer Released Details</h5>';
                                    or_detail +='<table class="table mb-0">';
                                        or_detail +='<thead>';
                                            or_detail +='<tr>';

                                                or_detail +='<th>';
                                                or_detail +='Closed Salary';
                                                or_detail +='</th>';
                                                or_detail +='<th>';
                                                or_detail +='Salary Review';
                                                or_detail +='</th>';
                                                or_detail +='<th>';
                                                or_detail +='Joining Type';
                                                or_detail +='</th>';
                                                or_detail +='<th>';
                                                or_detail +='Date of Joining';
                                                or_detail +='</th>';
                                                or_detail +='<th>';
                                                or_detail +='Remark';
                                                or_detail +='</th>';
                                                or_detail +='<th>';
                                                or_detail +='Created On';
                                                or_detail +='</th>';
                                            or_detail +='</tr>';
                                        or_detail +='</thead>';
                                        or_detail +='<tbody>';
                                            for (let index_or = 0; index_or < data.ord.length; index_or++) {

                                                or_detail +='<tr>';

                                                    or_detail +='<td data-label="Closed Salary">';
                                                    or_detail +=''+data.ord[index_or].closed_salary+'';
                                                    or_detail +='</td>';
                                                    or_detail +='<td data-label="Salary Review">';
                                                    or_detail +=''+data.ord[index_or].salary_review+'';
                                                    or_detail +='</td>';
                                                    or_detail +='<td data-label="Joining Type">';
                                                    or_detail +=''+data.ord[index_or].joining_type+'';
                                                    or_detail +='</td>';
                                                    or_detail +='<td data-label="Date of Joining">';
                                                    or_detail +=''+data.ord[index_or].date_of_joining.split("-").reverse().join("-")+'';
                                                    or_detail +='</td>';
                                                    or_detail +='<td data-label="Remark">';
                                                    or_detail +=''+data.ord[index_or].remark+'';
                                                    or_detail +='</td>';
                                                    or_detail +='<td data-label="Created On">';
                                                    or_detail +=''+data.ord[index_or].created_on.split("-").reverse().join("-")+'';
                                                    or_detail +='</td>';
                                                or_detail +='</tr>';


                                    }
                                            or_detail +='</tbody>';
                                        or_detail +='</table>';
                                    or_detail +='</div>';

                                    $('#or_report_tb').html(or_detail);

                                }

                                if(data.orld.length != 0){
                                    var orld_detail = '<div class="table-responsive">';
                                    orld_detail +='<br>';

                                    orld_detail +='<h5>Offer Released Later Date Details</h5>';

                                    orld_detail +='<table class="table mb-0">';
                                        orld_detail +='<thead>';
                                            orld_detail +='<tr>';
                                                orld_detail +='<th>';
                                                orld_detail +='Resignation Received';
                                                orld_detail +='</th>';
                                                orld_detail +='<th>';
                                                orld_detail +='Touch Base';
                                                orld_detail +='</th>';
                                                orld_detail +='<th>';
                                                orld_detail +='Backfill';
                                                orld_detail +='</th>';
                                                orld_detail +='<th>';
                                                orld_detail +='Created On';
                                                orld_detail +='</th>';
                                            orld_detail +='</tr>';
                                        orld_detail +='</thead>';
                                        orld_detail +='<body>';
                                            for (let index_orld = 0; index_orld < data.orld.length; index_orld++) {
                                                orld_detail +='<tr>';
                                                    orld_detail +='<td data-label="Resignation Received">';
                                                    orld_detail +=''+data.orld[index_orld].orladj_resignation_received+'';
                                                    orld_detail +='</td>';
                                                    orld_detail +='<td data-label="Touch Base">';
                                                    orld_detail +=''+data.orld[index_orld].orladj_touchbase+'';
                                                    orld_detail +='</td>';
                                                    orld_detail +='<td data-label="Backfill">';
                                                    orld_detail +=''+data.orld[index_orld].initiate_backfil+'';
                                                    orld_detail +='</td>';
                                                    orld_detail +='<td data-label="Created On">';
                                                    orld_detail +=''+data.orld[index_orld].created_on.split("-").reverse().join("-")+'';
                                                    orld_detail +='</td>';
                                                orld_detail +='</tr>';

                                    }
                                            orld_detail +='</body>';
                                        orld_detail +='</table>';
                                    orld_detail +='</div>';
                                    $('#orld_report_tb').html(orld_detail);

                                }

                            }
                        });
                    }
                }

                $('#followupModalBody').html(html);

            }
            else{
                var html ='<tr><td colspan=3 style="text-align: center;">No Data Found</td></tr>';
                $('#followupModalBody').html(html);

            }


             $("#btnFollowup").click();


        }
    })

}

function pop_noofposition(nop,rfh_no){
    $('#show_nopedit_pop').click();
    $('#show_edit_noppop_title').html('Edit No of Position - '+ rfh_no);
    $('#current_no_of_position').val(nop);
    $('#rfh_nop').val(rfh_no);

}

$("#btnEditnopUpdate").on('click', function() {

    var current_nop = $('#current_no_of_position').val();
    var no_of_position = $('#no_of_position').val();
    var rfh_nop = $('#rfh_nop').val();
    var action_type = $('#action_type').val();

    $.ajax({
        type: "POST",
        url: update_no_of_position_link,
        data: { "action_type":action_type,"current_nop":current_nop,"no_of_position":no_of_position,"rfh_nop":rfh_nop,},
        success: function (data) {

            $('#current_no_of_position').val("");
            $('#no_of_position').val(1);
            $('#rfh_nop').val("");
            $('#action_type').val("Add");

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        var current_tab_title = $("#myTab > .nav-item > a.active").html();

                        if(current_tab_title == 'Yet To Allocate List'){
                            getrecruitmentRequestlist();

                        }else{
                            getrecruitmentRequestlist_assigned();
                        }
                    }, 2000);
            }else if(data.response == 'unassign_alert'){

                Toastify({
                    text: "Before updating no of position ... Kindly Unassign Ticket..!",
                    duration: 6000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

                setTimeout(
                    function() {
                        var current_tab_title = $("#myTab > .nav-item > a.active").html();

                        if(current_tab_title == 'Yet To Allocate List'){
                            getrecruitmentRequestlist();

                        }else{
                            getrecruitmentRequestlist_assigned();
                        }
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
                        var current_tab_title = $("#myTab > .nav-item > a.active").html();

                        if(current_tab_title == 'Yet To Allocate List'){
                            getrecruitmentRequestlist();

                        }else{
                            getrecruitmentRequestlist_assigned();
                        }
                    }, 2000);


            }
        }
    })
});


