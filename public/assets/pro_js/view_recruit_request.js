$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


});

$(document).ready(function () {
    // get_last_reference_no();

    getrecruitmentRequestlist();

});

function get_last_reference_no() {
    $.ajax({
        type: "POST",
        url: get_last_reference_no_link,
        data: {},
        success: function (data) {
            if (data.response != 'no_data') {
                $('#last_hepl_ref_no').html('<strong>' + data.response + '</strong>');
            }
        }
    })
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
        // '<tr>' +
        // '<td class="hide table-info">No of Position</td>' +
        // '<td data-label="No of Positione">' + d.no_of_position + '</td>' +
        // '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Critical Position</td>' +
        '<td data-label="Critical Position">' + d.critical_position + '</td>' +
        '</tr>' +

        '<tr>' +
        '<td class="hide table-info">Division</td>' +
        '<td data-label="Division">' + d.division + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Function</td>' +
        '<td data-label="Function">' + d.function+'</td>' +
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
        '<td class="hide table-info">Maximum CTC(Per Month):</td>' +
        '<td data-label="Maximum CTC(Per Month):">' + d.salary_range + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Recruiter Name</td>' +
        '<td data-label="Recruiter Name">' + d.recruiter_name + '</td>' +
        '</tr>' +

        '</table>';
}

function getrecruitmentRequestlist() {

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var rfh_no = url.searchParams.get("rfh_no");

    table = $('#table1').DataTable({

        dom: 'Bfrtip',
        buttons: [{
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
        lengthMenu: [
            [10, 50, 100, 200, 300, 400, 500, 1000, -1],
            [10, 50, 100, 200, 300, 400, 500, 1000, "All"]
        ],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        scrollY: 800,
        scrollCollapse: true,
        drawCallback: function () {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            // $('head').append('<link rel="stylesheet" type="text/css" href="assets/vendors/choices.js/choices.min.css">');

        },
        'columnDefs': [{
                width: '3%',
                targets: 0
            },
            {
                width: '3%',
                targets: 1
            },
            {
                width: '10%',
                targets: 2
            },
            {
                width: '9%',
                targets: 3
            },
            {
                width: '10%',
                targets: 4
            },
            {
                width: '5%',
                targets: 5
            },
            {
                width: '10%',
                targets: 6
            },
            {
                width: '10%',
                targets: 7
            },
            {
                width: '10%',
                targets: 8
            },
            {
                width: '10%',
                targets: 9
            },
            {
                width: '20%',
                targets: 10
            },
        ],
        aoColumnDefs: [
            { 'visible': false, 'targets': [11,12] }
        ],
        ajax: {
            url: get_recruitment_request_list_link,
            type: 'POST',
            data: function (d) {
                d.rfh_no = rfh_no;
                // d.end_date = $('#end_date').val();
                // d.mobile_verify = $('#mobile_verify').val();
                // d.bank_verify = $('#bank_verify').val();
                // d.account_status = $('#account_status').val();
                // d.avail_points = $('#avail_points').val();
                // d.pts_range = $('#pts_range').val();


            }
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(0)').attr('data-label', '#');
            $(row).find('td:eq(1)').attr('data-label', 'Sno');
            $(row).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref Number');
            $(row).find('td:eq(3)').attr('data-label', 'Position Title');
            $(row).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $(row).find('td:eq(5)').attr('data-label', 'No. of Position');
            $(row).find('td:eq(6)').attr('data-label', 'Ageing');
            $(row).find('td:eq(7)').attr('data-label', 'Open Date');
            $(row).find('td:eq(8)').attr('data-label', 'Location');
            $(row).find('td:eq(9)').attr('data-label', 'Assigned Status');
            $(row).find('td:eq(10)').attr('data-label', 'Action');
            $(row).find('td:eq(11)').attr('data-label', 'Assigned Status');
            $(row).find('td:eq(12)').attr('data-label', 'Position Status');

        },
        columns: [{
                "className": 'details-control',
                "orderable": false,
                "data": null,
                "defaultContent": ''
            },

            {
                data: 'DT_RowIndex',
                name: 'DT_RowIndex'
            },
            {
                data: 'hepl_recruitment_ref_number',
                name: 'hepl_recruitment_ref_number'
            },
            {
                data: 'position_title',
                name: 'position_title'
            },
            {
                data: 'sub_position_title',
                name: 'sub_position_title'
            },
            {
                data: 'no_of_position',
                name: 'no_of_position'
            },
            {
                data: 'ageing',
                name: 'ageing'
            },
            {
                data: 'open_date',
                name: 'open_date'
            },
            {
                data: 'location',
                name: 'location'
            },
            {
                data: 'assigned_status',
                name: 'assigned_status'
            },

            {
                data: 'action',
                name: 'action',
                orderable: false,
                searchable: false
            },
            {
                data: 'as_title',
                name: 'as_title'
            },
            {
                data: 'ps_title',
                name: 'ps_title'
            },
        ],


    });

    $('#table1 tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table1 tbody').on('click', 'td.details-control', function () {
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

function process_assign(rowID) {
    
    $("#btnAssign_" + rowID).attr("disabled", true);
    $("#btnAssign_" + rowID).text("Processing");
    var recruiter_list = $("#recruiter_list_" + rowID).val();
    var position_title = $("#main_position_title_" + rowID).val();
    var sub_position_title = $("#sub_position_title_" + rowID).val();
    var hepl_recruitment_ref_number = $("#hepl_recruitment_ref_number_" + rowID).val();
   
    var hidden_status = $("#hidden_status_" + rowID).val();
    
    if (recruiter_list == '' || recruiter_list ==null) {
        
        if(sub_position_title !=''){
            $.ajax({
                type: "POST",
                url: update_sub_position_title_link,
                data: {
                    "position_title": position_title,
                    "sub_position_title": sub_position_title,
                    "hepl_recruitment_ref_number": hepl_recruitment_ref_number,
                    "rowID": rowID,
                },
                success: function (data) {

                    if (data.response == 'Updated') {
                        Toastify({
                            text: "Updated Successfully",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);
                    }
                    else {
                        Toastify({
                            text: "Request Failed..! Try Again",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#f3616d",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);


                    }
                    get_last_reference_no();
                    $("#btnAssign_" + rowID).attr("disabled", false);
                    $("#btnAssign_" + rowID).text("Assign");
                }
            });
        }   

        
    } else {

        if (hidden_status == 'Assigned') {
            $.ajax({
                type: "POST",
                url: process_assigned_assign_link,
                data: {
                    "recruiter_list": recruiter_list,
                    "position_title": position_title,
                    "sub_position_title": sub_position_title,
                    "hepl_recruitment_ref_number": hepl_recruitment_ref_number,
                    "rowID": rowID,
                },
                success: function (data) {

                    if (data.response == 'Updated') {
                        Toastify({
                            text: "Assigned Successfully",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);
                    } 
                    else if(data.response == 'already_assigned'){
                        
                        Toastify({
                            text: "Recruiter can't be Reassigned again..!",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#f3616d",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);


                    }
                    else {
                        Toastify({
                            text: "Request Failed..! Try Again",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#f3616d",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);


                    }
                    get_last_reference_no();
                }
            })
        } else {

            $.ajax({
                type: "POST",
                url: process_assign_link,
                data: {
                    "recruiter_list": recruiter_list,
                    "position_title": position_title,
                    "sub_position_title": sub_position_title,
                    "hepl_recruitment_ref_number": hepl_recruitment_ref_number,
                    "rowID": rowID,
                },
                success: function (data) {

                    if (data.response == 'Updated') {
                        Toastify({
                            text: "Assigned Successfully",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#4fbe87",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);
                    } 
                    else if(data.response == 'already_assigned'){
                        
                        Toastify({
                            text: "Recruiter can't be Reassigned again..!",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#f3616d",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);


                    }
                    else {
                        Toastify({
                            text: "Request Failed..! Try Again",
                            duration: 3000,
                            close: true,
                            backgroundColor: "#f3616d",
                        }).showToast();

                        setTimeout(
                            function () {
                                getrecruitmentRequestlist()
                            }, 2000);


                    }
                    get_last_reference_no();
                    $("#btnAssign_" + rowID).attr("disabled", false);
        $("#btnAssign_" + rowID).text("Assign");
                }
            })
        }

        
    }

}

function process_unassign(hepl_recruitment_ref_number,recReqID){
    $("#btn_unassign_yal").click();

    $('#heplrr_unassign_yal').val(hepl_recruitment_ref_number);
    $('#recReqID_unassign_yal').val(recReqID);
}

function delete_heplrr_row(hepl_recruitment_ref_number,recReqID){
    $('#heplrr_del').val(hepl_recruitment_ref_number);
    $('#recReqID_del').val(recReqID);
    $("#show_pop_hepl").click();

}

$("#btnConfirm_delete_hepl").on('click', function() {

    var heplrr_del = $('#heplrr_del').val();
    var recReqID_del = $('#recReqID_del').val();

    $.ajax({
        type: "POST",
        url: process_hepldelete_link,
        data: { "heplrr_del":heplrr_del,"recReqID_del":recReqID_del,},
        success: function (data) {
            
            $('#close_del_pop').click();

            if(data.response =='Deleted'){
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
    })
});

$("#btnConfirm_unassign_yal").on('click', function() {

    var heplrr_unassign_yal = $('#heplrr_unassign_yal').val();
    var recReqID_unassign_yal = $('#recReqID_unassign_yal').val();

    $.ajax({
        type: "POST",
        url: process_unassign_link,
        data: { "heplrr_unassign_yal":heplrr_unassign_yal,"recReqID_unassign_yal":recReqID_unassign_yal,},
        success: function (data) {
            
            $('#close_pop').click();

            if(data.response =='Updated'){
                Toastify({
                    text: "Unassigned Successfully",
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
