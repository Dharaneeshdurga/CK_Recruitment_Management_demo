$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

// get task details on load
$(document).ready(function() {

    getrecruitmentRequestlist();
    //call_test();

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

function validateAlpha(thi){

    var input = thi;

    var textInput = input.value;
    textInput = textInput.replace(/[&\/\-_=|\][;\#,+()$~%.'":*?<>{}@^!`0-9]/g, "");

    input.value = textInput;

}

// /* Formatting function for row details - modify as you need */
function format(d) {

    var view_btn;

    view_btn = '<a href="view_recruit_request_new?rfh_no='+d.rfh_no+'" target="_blank"><button class="btn btn-sm btn-secondary"  title="View Recruitment"><i class="bi bi-eye"></i></button></a>';

    // `d` is the original data object for the row
    return '<table class="table" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" id="child_view">' +
        '<tr>' +
        '<td class="hide table-info"><strong>Business</strong></td>' +
        '<td>' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>RFH NO</strong></td>' +
        '<td>' + d.rfh_no + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>Band</strong></td>' +
        '<td>' + d.band_title + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>No of Position</strong></td>' +
        '<td>' + d.no_of_position + '</td>' +
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
        '<td class="hide table-info"><strong>Maximum CTC(Per Month):</strong></td>' +
        '<td>' + d.salary_range + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info"><strong>View</strong></td>' +
        '<td>' + view_btn + '</td>' +
        '</tr>' +

        '</table>';
}

function getrecruitmentRequestlist(){

    table = $('#table1').DataTable({

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
        stateSave: true,

        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })

            processInfo_tab1(this.api().page.info());

        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [11,12] }
        ],
        ajax: {
            url: get_assigned_recruitment_request_list_link,
            type: 'POST',
            data: function (d) {

            }
           },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Assigned Date');
            $( row ).find('td:eq(7)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(8)').attr('data-label', 'Location');
            $( row ).find('td:eq(9)').attr('data-label', 'Position Status');
            $( row ).find('td:eq(10)').attr('data-label', 'Action');
            $( row ).find('td:eq(11)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(12)').attr('data-label', 'Approved by');

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
            {data: 'sub_position_title', name: 'sub_position_title'},
            {data: 'ageing', name: 'ageing'},
            {data: 'assigned_date', name: 'assigned_date'},
            {data: 'open_date', name: 'open_date'},
            {data: 'location', name: 'location'},
            {data: 'request_status', name: 'request_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'raised_by', name: 'tr.raised_by', searchable: true},
            {data: 'approved_by', name: 'tr.approved_by', searchable: true},

        ],


    });
    $('#table1 tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table1 tbody').on('click', 'td.details-control', function() {
    // $('#btnHistory').unbind('click');

        var cur_row = $(this);
        var tr = cur_row.closest('tr');
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
function processInfo_tab5(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab5').text(res_found);
}

// get old positions
function getrecruitmentRequest_oldlist(){

    table_od = $('#table_op').DataTable({

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
        stateSave: true,
        autoWidth: false,

        drawCallback: function() {
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            processInfo_tab2(this.api().page.info());

        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [10,14,15] }
        ],
        ajax: {
            url: get_assigned_recruitment_request_oldlist_link,
            type: 'POST',
            data: function (d) {

            }
           },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'CV Count');
            $( row ).find('td:eq(7)').attr('data-label', 'Assigned Date');
            $( row ).find('td:eq(8)').attr('data-label', 'Current Status');
            $( row ).find('td:eq(9)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(10)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(11)').attr('data-label', 'Location');
            $( row ).find('td:eq(12)').attr('data-label', 'Position Status');
            $( row ).find('td:eq(13)').attr('data-label', 'Action');
            $( row ).find('td:eq(14)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(15)').attr('data-label', 'Approved by');

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
            {data: 'sub_position_title', name: 'sub_position_title'},
            {data: 'ageing', name: 'ageing'},
            {data: 'cv_count', name: 'cv_count'},
            {data: 'assigned_date', name: 'assigned_date'},
            {data: 'current_status', name: 'current_status'},
            {data: 'open_date', name: 'open_date'},
            {data: 'close_date', name: 'close_date', "visible": false},
            {data: 'location', name: 'location'},
            {data: 'request_status', name: 'request_status'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'raised_by', name: 'tr.raised_by', searchable: true},
            {data: 'approved_by', name: 'tr.approved_by', searchable: true},

        ],


    });
    $('#table_op tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table_op tbody').on('click', 'td.details-control', function() {
        var cur_row_nw = $(this);
        var tr = cur_row_nw.closest('tr');
        var row = table_od.row(tr);

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


// action for the day dropdown option
function process_actionday(el,hepl_recruitment_ref_number,rfh_no){

    var action_for_the_day = el.value;
    var show_pc_position_title = $(el).data("position_title");

    $('#hepl_recruitment_ref_number').val(hepl_recruitment_ref_number);
    $('#cv_up_rfh_no').val(rfh_no);

        $("html, body").animate({ scrollTop: $(document).height() }, "slow");

        $('#show_pc_hepl_ref_no').text(hepl_recruitment_ref_number);
        $('#show_pc_action_status').text(action_for_the_day);
        $('#show_pc_position_title').text(show_pc_position_title);

        $("#show_cv_process").css({ "display" :"block" });
        $("#show_profile_count").css({ "display" :"block" });

        $(".btnDefaultSubmit").css({ "display" :"none" });

        $("#show_cv_uploaded_div").css({ "display" :"none" });
        $("#show_offer_released_div").css({ "display" :"none" });


}

// offer released action status
function cv_process_actionday(el,cdID,candidate_name,hepl_recruitment_ref_number){
    var action_for_the_day = el.value;


    if(action_for_the_day == 'Offer Released'){

        $("html, body").animate({ scrollTop: $(document).height() }, "slow");

        $('#or_closed_salary').val("");
        $('#or_salary_review').val("");
        $('#or_joining_type').val("");
        $('#or_doj').val("");
        $('#or_remark').val("");

        $('#or_hepl_ref_no').text(hepl_recruitment_ref_number);
        $('#or_action_status').text(action_for_the_day);
        $('#or_candidate_name').text(candidate_name);

        $('#or_cdID').val(cdID);
        $('#or_hepl_recruitment_ref_number').val(hepl_recruitment_ref_number);
        $('#or_action_for_the_day').val(action_for_the_day);

        $("#show_offer_released_div ").css({ "display" :"block" });
        $(".btnDefaultSubmit").css({ "display" :"none" });


        // $("#show_cv_uploaded_div").css({ "display" :"none" });

    }
    else{
        $("#btnDefaultSubmit_"+cdID).css({ "display" :"block" });
        $("#show_offer_released_div ").css({ "display" :"none" });

    }

    if(action_for_the_day == 'Offer Accepted'){
        $("#offer_accep_date_"+cdID).css({ "display" :"block" });
        $("#cd_span_"+cdID).css({ "display" :"block" });

        $("#oa_candidate_email_"+cdID).css({ "display" :"block" });
        $("#oa_email_span_"+cdID).css({ "display" :"block" });

    }
    else{
        $("#offer_accep_date_"+cdID).css({ "display" :"none" });
        $("#cd_span_"+cdID).css({ "display" :"none" });

        $("#oa_candidate_email_"+cdID).css({ "display" :"none" });
        $("#oa_email_span_"+cdID).css({ "display" :"none" });
    }
    if(action_for_the_day == 'Document Collection'){
        $("#oa_candidate_email_"+cdID).css({ "display" :"block" });
        $("#oa_email_span_"+cdID).css({ "display" :"block" });

        $("#oa_candidate_type_"+cdID).css({ "display" :"block" });
        $("#oa_candi_type_span_"+cdID).css({ "display" :"block" });

    }else{
        $("#oa_candidate_email_"+cdID).css({ "display" :"none" });
        $("#oa_email_span_"+cdID).css({ "display" :"none" });

        $("#oa_candidate_type_"+cdID).css({ "display" :"none" });
        $("#oa_candi_type_span_"+cdID).css({ "display" :"none" });
    }
    $("#show_cv_process").css({ "display" :"none" });
    $("#show_profile_count").css({ "display" :"none" });
}

// show cv upload form
$("#btnactionPc").on('click', function() {
    $("#btnactionPc").css({ "display" :"none" });
    $("#profile_count_st").css({ "display" :"none" });

    var profile_count = Number($('#profile_count').val());

    var html = '';
    for (let index = 0; index < profile_count; index++) {
        html +='<tr>';
        html +='<td>';
        html +='<input type="text" name="candidate_name[]" id="candidate_name[]" class="form-control candidate_name" oninput="validateAlpha(this);" required placeholder="Candidate Name">';
        html +='</td>';
        html +='<td>';
        html +='<input type="file" name="candidate_cv[]" accept=".pdf, .PDF, .doc, .DOC, .docx, .DOCX" id="candidate_cv" class="basic-filepond" required>';
        html +='</td>';
        html +='<td>';

        html += '<input type="text" name="candidate_source[]" id="candidate_source" list="get_candidate_source" class="form-control form-control-sm" placeholder="Candidate Source" required>';
        html += '<datalist id="get_candidate_source" placeholder="Choose Remark">';
        html += '<option value="Naukri">';
        html += '<option value="LinkedIn">';
        html += '<option value="Advertisement">';
        html += '<option value="Reference">';
        html += '</datalist>';

        html +='</td>';
        html +='<td>';
        html +='<select name="gender[]" id="gender" class="form-control" required>';
        html +='<option value="">Select Gender</option>';
        html +='<option value="Male">Male</option>';
        html +='<option value="Female">Female</option>';
        html +='</select>';
        html +='</td>';
        html +='<td>';
        html +='<span class="badge bg-danger" id="btnDeleterow" class="btnDeleterow" onclick="deleteRow(this)" title="Delete Row"><i class="bi bi-trash"></i></span>';
        html +='</td>';

        html +='</tr>';
    }

    $("#show_cv_upload").css({ "display" :"block" });
    $("#btnaddmorePc").attr("disabled", false);
    $('#put_cv_upload_form').html(html);


});

// check profile count validation is number
function numberValid(){
    var textInput = document.getElementById("profile_count").value;
    textInput = textInput.replace(/[^0-9]/g, "");
    document.getElementById("profile_count").value = textInput;
    if(textInput ==''){
        $("#profile_count").addClass("is-invalid");
        $("#btnactionPc").attr("disabled", true);

    }
    else{
        $("#profile_count").addClass("is-valid");
        $("#profile_count").removeClass("is-invalid");
        $('#btnactionPc').removeAttr("disabled");

    }
}

// cv upload form for add more row
$("#btnaddmorePc").on('click', function() {

    var html = '';
        html +='<tr>';
        html +='<td>';
        html +='<input type="text" name="candidate_name[]" id="candidate_name[]" class="form-control candidate_name" oninput="validateAlpha();" required placeholder="Candidate Name">';
        html +='</td>';
        html +='<td>';
        html +='<input type="file" name="candidate_cv[]" accept=".pdf, .PDF, .doc, .DOC, .docx, .DOCX" id="candidate_cv" class="basic-filepond" required>';
        html +='</td>';

        html +='<td>';
        html += '<input type="text" name="candidate_source[]" id="candidate_source" list="get_candidate_source" class="form-control form-control-sm placeholder="Candidate Source" required>';
        html += '<datalist id="get_candidate_source" placeholder="Choose Remark">';
        html += '<option value="Naukri">';
        html += '<option value="LinkedIn">';
        html += '<option value="Advertisement">';
        html += '<option value="Reference">';
        html += '</datalist>';

        html +='</td>';
        html +='<td>';
        html +='<select name="gender[]" id="gender" class="form-control" required>';
        html +='<option value="">Select Gender</option>';
        html +='<option value="Male">Male</option>';
        html +='<option value="Female">Female</option>';
        html +='</select>';
        html +='</td>';
        html +='<td>';
        html +='<span class="badge bg-danger" id="btnDeleterow" class="btnDeleterow" onclick="deleteRow(this)" title="Delete Row"><i class="bi bi-trash"></i></span>';
        html +='</td>';
        html +='</tr>';

        $('#upload_tb > tbody:last-child').append(html);

});

// delete cv upload row
function deleteRow(r) {
    var i = r.parentNode.parentNode.rowIndex;
    document.getElementById("upload_tb").deleteRow(i);
}



// offer released submit process
$('#orSubmit').on('submit',(function(e) {
    // $('#show_offer_rel_pro_pop').click();

    var hepl_rno = $('#or_hepl_recruitment_ref_number').val();
    var or_cdID = $('#or_cdID').val();
    var or_rfh_no = $('#or_rfh_no').val();
    var or_closed_salary = $('#or_closed_salary').val();




    // var host = "process_offer_letter_release/"+or_rfh_no+"/"+hepl_rno+"/"+or_closed_salary+"/"+or_cdID;
    // alert(host);
    // $("#preview_offer_letter").attr("href", host);

    // return false;
    $("#orbtnSubmit").attr("disabled", true);
    $('#orbtnSubmit').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');



    e.preventDefault();
    var formData = new FormData(this);
    formData.append('rfh_no', or_rfh_no);

    $.ajax({
        type:'POST',
        url: process_offer_release_details_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#orbtnSubmit").removeAttr("disabled");
        $('#orbtnSubmit').html('Submit');
        $('#orSubmit')[0].reset();

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();



            }
            else if(data.response == 'already_exits'){
                Toastify({
                    text: "Record Already Exits",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();


            }
            else {
                Toastify({
                    text: "Request Failed..! Try Again",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

            }

            setTimeout(
                function() {

                    var current_tab_title = $("#myTab > .nav-item > a.active").html();


                    if(current_tab_title == 'New positions allocated for the day'){
                        $('#new_position-tab').click();
                        $('#table1').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Old positions'){
                        $('#old_position-tab').click();
                        $('#table_op').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Offers released'){
                        $('#offer_released_ldj-tab').click();
                        $('#offer_released_tba').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Candidate Onboarded'){
                        $('#candidate_onboarded_ldj-tab').click();
                        $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                    }else{
                        $('#inactive-tab').click();
                        $('#table_inactive').DataTable().ajax.reload(null, false);
                    }
                    $("#show_offer_released_ldj").css({ "display" :"none" });
                    $("#show_cv_process").css({ "display" :"none" });
                    $("#show_cv_upload").css({ "display" :"none" });
                    // $("#show_offer_released_div").css({ "display" :"none" });
                    $("#show_cv_uploaded_div").css({ "display" :"none" });
                    // location.reload();

                    var hepl_recruitment_ref_number = $('#or_hepl_ref_no').text();
                    view_history_table(hepl_recruitment_ref_number);

                }, 2000);


        },
    });

}));



// cv upload submit process
$('#cvSubmit').on('submit',(function(e) {


    var current_tab_title = $("#myTab > .nav-item > a.active").html();
    // alert(current_tab_title);
    // return false;
    $("#cvSubmitbtn").attr("disabled", true);
    $('#cvSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    e.preventDefault();
    var formData = new FormData(this);

    var action_for_the_day = $('#action_for_the_day_put').val();
    formData.append('action_for_the_day', action_for_the_day);

        $.ajax({
            type:'POST',
            url: upload_cvprocess_link,
            data:formData,
            cache:false,
            contentType: false,
            processData: false,

            success:function(data){
            $("#cvSubmitbtn").removeAttr("disabled");
            $('#cvSubmitbtn').html('Submit');

                if($.isEmptyObject(data.error)){
                    Toastify({
                        text: "Inserted Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                }
                else {
                    Toastify({
                        text: "Request Failed..! Try Again",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                }
                setTimeout(
                    function() {
                        if(current_tab_title == 'New positions allocated for the day'){
                            $('#new_position-tab').click();
                            $('#table1').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Old positions'){
                            $('#old_position-tab').click();
                            $('#table_op').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Offers released'){
                            $('#offer_released_ldj-tab').click();
                            $('#offer_released_tba').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Candidate Onboarded'){
                            $('#candidate_onboarded_ldj-tab').click();
                            $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                        }else{
                            $('#inactive-tab').click();
                            $('#table_inactive').DataTable().ajax.reload(null, false);
                        }

                        $("#show_offer_released_ldj").css({ "display" :"none" });
                        $("#show_cv_process").css({ "display" :"none" });
                        $("#show_cv_upload").css({ "display" :"none" });
                        $("#show_offer_released_div").css({ "display" :"none" });
                        $("#show_cv_uploaded_div").css({ "display" :"none" });


                        $("#btnactionPc").css({ "display" :"inline-flex" });
                        $("#profile_count_st").css({ "display" :"block" });

                        $('#profile_count').val("");
                        $("#profile_count").removeClass("is-valid");

                        // location.reload();
                }, 2000);

            },
        });
    }));

// view cv uploaded history table
function view_history_table(hepl_recruitment_ref_number){
    table = $('#uploaded_tb').DataTable({

        dom: 'Bfrtip',
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
        lengthMenu: [[50, 100, 200, 300, 400, 500, 1000, -1], [50, 100, 200, 300, 400, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        stateSave: true,

        drawCallback : function() {

            var status = $('#uploaded_tb').DataTable().column(2).data();
            $('#show_cv_action_status').text(status[0]);

        },
        ajax: {
            url: show_uploaded_cv_link,
            type: 'POST',
            data: function (d) {
                d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            // $( row ).find('td:eq(1)').attr('data-label', 'Candidate ID');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'Status');
            $( row ).find('td:eq(3)').attr('data-label', 'CV');
            $( row ).find('td:eq(4)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Updated On');
            $( row ).find('td:eq(7)').attr('data-label', 'Action');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            // {   data: 'cdID', name: 'cdID'  },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'status', name: 'status'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'follow_up', name: 'follow_up'  },
            {   data: 'ageing', name: 'ageing'  },
            {   data: 'updated_on', name: 'updated_on'  },
            {   data: 'action', name: 'action'  },
        ],
    });

}
function view_history(el,hepl_recruitment_ref_number,rfh_no){

    var show_cv_position_title = $(el).data("position_title");

    $("html, body").animate({ scrollTop: $(document).height() }, "slow");

    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_offer_released_ldj").css({ "display" :"none" });

    $("#show_cv_uploaded_div").css({ "display" :"block" });

    $('#show_cv_hepl_ref_no').text(hepl_recruitment_ref_number);
    $('#show_cv_position_title').text(show_cv_position_title);

    $('#history_rfh_no').val(rfh_no);
    $('#or_rfh_no').val(rfh_no);

    table = $('#uploaded_tb').DataTable({

        dom: 'Bfrtip',
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
        lengthMenu: [[50, 100, 200, 300, 400, 500, 1000, -1], [50, 100, 200, 300, 400, 500, 1000, "All"]],
        processing: true,
        serverSide: true,
        serverMethod: 'post',
        bDestroy: true,
        scrollX: true,
        stateSave: true,

        drawCallback : function() {

            var status = $('#uploaded_tb').DataTable().column(2).data();
            $('#show_cv_action_status').text(status[0]);

        },
        ajax: {
            url: show_uploaded_cv_link,
            type: 'POST',
            data: function (d) {
                d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            // $( row ).find('td:eq(1)').attr('data-label', 'Candidate ID');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'Status');
            $( row ).find('td:eq(3)').attr('data-label', 'CV');
            $( row ).find('td:eq(4)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Updated On');
            $( row ).find('td:eq(7)').attr('data-label', 'Action');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            // {   data: 'cdID', name: 'cdID'  },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'status', name: 'status'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'follow_up', name: 'follow_up'  },
            {   data: 'ageing', name: 'ageing'  },
            {   data: 'updated_on', name: 'updated_on'  },
            {   data: 'action', name: 'action'  },
        ],
    });

}

function process_default_status(cdID,hepl_recruitment_ref_number){

    var offer_accep_date = $('#offer_accep_date_'+cdID).val();
    var get_candidate_email = $('#oa_candidate_email_'+cdID).val();
    // alert(offer_accep_date);
    var action_for_the_day = $('#cv_action_for_the_day_'+cdID).val();
    var history_rfh_no = $('#history_rfh_no').val();
    var candidate_type = $('#oa_candidate_type_'+cdID).val();

    $('#btnDefaultSubmit_'+cdID).prop('disabled',true);
    $('#btnDefaultSubmit_'+cdID).text('Processing');
    $.ajax({
        type: "POST",
        url: process_default_status_link,
        data: { "candidate_type":candidate_type,"candidate_email":get_candidate_email,"offer_accep_date":offer_accep_date,"history_rfh_no":history_rfh_no,"action_for_the_day":action_for_the_day,"cdID":cdID,"hepl_recruitment_ref_number":hepl_recruitment_ref_number,},
        success: function (data) {

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();
                call_budgieApiInsert(data.candidate_id,data.candidate_type,get_candidate_email,data.candidate_source,data.recruiter_name,data.recruiter_id,);

            }
            else{
                Toastify({
                    text: "Request Failed..! Try Again",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

            }

            setTimeout(
                function() {
                    var current_tab_title = $("#myTab > .nav-item > a.active").html();

                    // view_history('thi',hepl_recruitment_ref_number,history_rfh_no,show_cv_position_title);
                    // getrecruitmentRequestlist();
                    view_history_table(hepl_recruitment_ref_number);
                    if(current_tab_title == 'New positions allocated for the day'){
                        $('#new_position-tab').click();
                        $('#table1').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Old positions'){
                        $('#old_position-tab').click();
                        $('#table_op').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Offers released'){
                        $('#offer_released_ldj-tab').click();
                        $('#offer_released_tba').DataTable().ajax.reload(null, false);
                    }else if(current_tab_title == 'Candidate Onboarded'){
                        $('#candidate_onboarded_ldj-tab').click();
                        $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                    }else{
                        $('#inactive-tab').click();
                        $('#table_inactive').DataTable().ajax.reload(null, false);
                    }

                    $("#show_offer_released_ldj").css({ "display" :"none" });
                    $("#show_cv_process").css({ "display" :"none" });
                    $("#show_cv_upload").css({ "display" :"none" });
                    $("#show_offer_released_div").css({ "display" :"none" });
                    $("#show_cv_uploaded_div").css({ "display" :"none" });

                    $('#btnDefaultSubmit_'+cdID).prop('disabled',false);
                    $('#btnDefaultSubmit_'+cdID).text('Submit');

                }, 2000);




            // location.reload();

        }
    })

}
function call_budgieApiInsert(candidate_id,candidate_type,can_email,can_source,r_name,r_id){

    $.ajax({
      //  url: "http://127.0.0.1:8080/api/insert_candidate_prohire",
        url: "http://216.48.177.166/budgie/public/index.php/api/insert_candidate_prohire",
        type: "POST",
        cors: true ,
        data:{
            'candidate_id':candidate_id,
            'candidate_type':candidate_type,
            'can_email':can_email,
            'can_source':can_source,
            'r_name':r_name,
            'r_id':r_id

        },
        dataType: "json",
    //     cache:false,
    //     contentType: false,
    //    processData: false,
        success: function (data) {
           if(data.response == "success"){
           console.log("candidate details successfully sent")
           }
           else{
            console.log("error");
           }
        },


 })

}
// function call_test(){

//     $.ajax({
//         url: "http://127.0.0.1:8080/api/get_buisness",
//         type: "POST",
//         cors: true ,
//         data:{},
//         dataType: "json",
//     //     cache:false,
//     //     contentType: false,
//     //    processData: false,
//         success: function (data) {
//            if(data.response == "success"){
//            console.log(data);
//            }
//            else{
//             console.log("error");
//            }
//         },


//  })

// }
//new positions tab click
$("#new_position-tab").on('click', function() {
    $("#show_offer_released_ldj").css({ "display" :"none" });

    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_cv_uploaded_div").css({ "display" :"none" });

    getrecruitmentRequestlist();

});

//old positions tab click
$("#old_position-tab").on('click', function() {
    $("#show_offer_released_ldj").css({ "display" :"none" });
    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_cv_uploaded_div").css({ "display" :"none" });

    getrecruitmentRequest_oldlist();

});


$("#inactive-tab").on('click', function() {
    $("#show_offer_released_ldj").css({ "display" :"none" });
    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_cv_uploaded_div").css({ "display" :"none" });

    getrecruitment_inactive_list();

});

// offer released tab click
$("#offer_released_ldj-tab").on('click', function() {

    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_cv_uploaded_div").css({ "display" :"none" });

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
        stateSave: true,

        drawCallback : function() {

            var status = $('#uploaded_tb').DataTable().column(2).data();
            $('#show_cv_action_status').text(status[0]);
            processInfo_tab3(this.api().page.info());

        },
        ajax: {
            url: get_offer_released_tb_link,
            type: 'POST',
            data: function (d) {
                // d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref No');
            // $( row ).find('td:eq(3)').attr('data-label', 'Closed date');
            $( row ).find('td:eq(3)').attr('data-label', 'Closed Salary');
            $( row ).find('td:eq(4)').attr('data-label', 'Salary Review');
            $( row ).find('td:eq(5)').attr('data-label', 'Joining Type');
            $( row ).find('td:eq(6)').attr('data-label', 'Date of Joining');
            $( row ).find('td:eq(7)').attr('data-label', 'Remark');
            $( row ).find('td:eq(8)').attr('data-label', 'Candidate CV');
            $( row ).find('td:eq(9)').attr('data-label', 'Followup History');
            $( row ).find('td:eq(10)').attr('data-label', 'Action');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            // {   data: 'closed_date', name: 'closed_date'  },
            {   data: 'closed_salary', name: 'closed_salary'  },
            {   data: 'salary_review', name: 'salary_review'  },
            {   data: 'joining_type', name: 'joining_type'  },
            {   data: 'date_of_joining', name: 'date_of_joining'  },
            {   data: 'remark', name: 'remark'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'followup_history', name: 'followup_history'  },
            {   data: 'action', name: 'action'  },
        ],
    });
});

//candidate onboarded tab click

$("#candidate_onboarded_ldj-tab").on('click', function() {
    $("#show_cv_process").css({ "display" :"none" });
    $("#show_cv_upload").css({ "display" :"none" });
    $("#show_offer_released_div").css({ "display" :"none" });
    $("#show_cv_uploaded_div").css({ "display" :"none" });
    $("#show_offer_released_ldj").css({ "display" :"none" });

    table_cot = $('#c_onboarded').DataTable({

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
        stateSave: true,

        drawCallback : function() {

            processInfo_tab4(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        ajax: {
            url: get_candidate_onborded_history_link,
            type: 'POST',
            data: function (d) {
                // d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'Position');
            $( row ).find('td:eq(3)').attr('data-label', 'Sub Position');
            $( row ).find('td:eq(4)').attr('data-label', 'Candidate Source');
            $( row ).find('td:eq(5)').attr('data-label', 'Gender');
            $( row ).find('td:eq(6)').attr('data-label', 'CV');
            $( row ).find('td:eq(7)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(8)').attr('data-label', 'Status');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'candidate_source', name: 'candidate_source'  },
            {   data: 'gender', name: 'gender'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'history', name: 'history'  },
            {   data: 'status', name: 'status'  },
        ],
    });

});


// offer released edit show
function edit_offer_released(el,cdID,candidate_name,hepl_recruitment_ref_number,rfh_no){
    $("#show_cv_uploaded_div").css({ "display" :"none" });
    var show_pc_position_title = $(el).data("position_title");
    $("#or_ldj_position_title").text(show_pc_position_title);

    $("html, body").animate({ scrollTop: $(document).height() }, "slow");

    $.ajax({
        type: "POST",
        url: or_ldj_history_link,
        data: { "cdID":cdID,},
        success: function (data) {
            if(data.length != 0){
                var html = '';
                var sno = 1;
                for (let index = 0; index < data.length; index++) {
                    html +='<tr>';
                    html +='<td data-label="Sno.">';
                    html +=''+sno+'';
                    html +='</td>';
                    html +='<td data-label="Resignation Received">';
                    html +=''+data[index].orladj_resignation_received+'';
                    html +='</td>';
                    html +='<td data-label="Touch Base">';
                    html +=''+data[index].orladj_touchbase+'';
                    html +='</td>';
                    html +='<td data-label="Initiate Backfil">';
                    html +=''+data[index].initiate_backfil+'';
                    html +='</td>';
                    html +='<td data-label="Created On">';
                    html +=''+data[index].created_on+'';
                    html +='</td>';
                    html +='</tr>';

                    sno++;
                }

                $('#or_ldj_history_body').html(html);

            }
            else{
                var html ='<tr><td colspan=5 style="text-align: center;">No Data Found</td></tr>';
                $('#or_ldj_history_body').html(html);

            }
        }
    })

    $("#show_offer_released_ldj").css({ "display" :"block" });

    $('#or_ldj_hepl_ref_no').text(hepl_recruitment_ref_number);
    $('#or_ldj_candidate_name').text(candidate_name);

    $('#or_ldj_hepl_recruitment_ref_number').val(hepl_recruitment_ref_number);
    $('#or_ldj_cdID').val(cdID);
    $('#or_ldj_rfh_no').val(rfh_no);

}

// offer released edit process
$('#or_ldj_Submit').on('submit',(function(e) {
    $("#orldjbtnSubmit").attr("disabled", true);
    $('#orldjbtnSubmit').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    e.preventDefault();
    var formData = new FormData(this);

        $.ajax({
            type:'POST',
            url: offer_released_edit_process_link,
            data:formData,
            cache:false,
            contentType: false,
            processData: false,

            success:function(data){
                var current_tab_title = $("#myTab > .nav-item > a.active").html();
                $("#orldjbtnSubmit").removeAttr("disabled");
                $('#orldjbtnSubmit').html('Submit');

                if(data.response =='Updated'){
                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            if(current_tab_title == 'New positions allocated for the day'){
                                $('#new_position-tab').click();
                                $('#table1').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Old positions'){
                                $('#old_position-tab').click();
                                $('#table_op').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Offers released'){
                                $('#offer_released_ldj-tab').click();
                                $('#offer_released_tba').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Candidate Onboarded'){
                                $('#candidate_onboarded_ldj-tab').click();
                                $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                            }else{
                                $('#inactive-tab').click();
                                $('#table_inactive').DataTable().ajax.reload(null, false);
                            }
                        }, 2000);
                }
                else {
                    Toastify({
                        text: "Request Failed..! Try Again",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            if(current_tab_title == 'New positions allocated for the day'){
                                $('#new_position-tab').click();
                                $('#table1').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Old positions'){
                                $('#old_position-tab').click();
                                $('#table_op').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Offers released'){
                                $('#offer_released_ldj-tab').click();
                                $('#offer_released_tba').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Candidate Onboarded'){
                                $('#candidate_onboarded_ldj-tab').click();
                                $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                            }else{
                                $('#inactive-tab').click();
                                $('#table_inactive').DataTable().ajax.reload(null, false);
                            }
                        }, 2000);

                }
            },
        });
}));

function or_ldj_onboard(cdID,rfh_no,hepl_recruitment_ref_number){

    $("#btnConfirm").click();

    $("#btnConfirmsubmit").on('click', function() {
        $("#confirmClose").click();

        $.ajax({
            type: "POST",
            url: or_ldj_onboard_link,
            data: { "rfh_no":rfh_no,"cdID":cdID,"hepl_recruitment_ref_number":hepl_recruitment_ref_number,},
            success: function (data) {
                var current_tab_title = $("#myTab > .nav-item > a.active").html();

                if(data.response =='Updated'){
                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            if(current_tab_title == 'New positions allocated for the day'){
                                $('#new_position-tab').click();
                                $('#table1').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Old positions'){
                                $('#old_position-tab').click();
                                $('#table_op').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Offers released'){
                                $('#offer_released_ldj-tab').click();
                                $('#offer_released_tba').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Candidate Onboarded'){
                                $('#candidate_onboarded_ldj-tab').click();
                                $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                            }else{
                                $('#inactive-tab').click();
                                $('#table_inactive').DataTable().ajax.reload(null, false);
                            }
                        }, 2000);
                }
                else if(data.response =='position_filled'){

                    Toastify({
                        text: "Position Filled",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            if(current_tab_title == 'New positions allocated for the day'){
                                $('#new_position-tab').click();
                                $('#table1').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Old positions'){
                                $('#old_position-tab').click();
                                $('#table_op').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Offers released'){
                                $('#offer_released_ldj-tab').click();
                                $('#offer_released_tba').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Candidate Onboarded'){
                                $('#candidate_onboarded_ldj-tab').click();
                                $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                            }else{
                                $('#inactive-tab').click();
                                $('#table_inactive').DataTable().ajax.reload(null, false);
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
                            if(current_tab_title == 'New positions allocated for the day'){
                                $('#new_position-tab').click();
                                $('#table1').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Old positions'){
                                $('#old_position-tab').click();
                                $('#table_op').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Offers released'){
                                $('#offer_released_ldj-tab').click();
                                $('#offer_released_tba').DataTable().ajax.reload(null, false);
                            }else if(current_tab_title == 'Candidate Onboarded'){
                                $('#candidate_onboarded_ldj-tab').click();
                                $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                            }else{
                                $('#inactive-tab').click();
                                $('#table_inactive').DataTable().ajax.reload(null, false);
                            }

                        }, 2000);


                }
            }
        })
    });
}


function candidate_follow_up(created_by,hepl_recruitment_ref_number,cdID,candidate_name){

    $('#fh_candidate_name').text(candidate_name);

    $.ajax({
        type: "POST",
        url: candidate_follow_up_history_link,
        data: { "cdID":cdID, "created_by":created_by,"hepl_recruitment_ref_number":hepl_recruitment_ref_number,},
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
                            url: get_offer_released_report_link,
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
                                                    or_detail +='<span id="pop_doj">'+data.ord[index_or].date_of_joining.split("-").reverse().join("-")+'</span> <button type="button" onclick="pop_dateof_joining('+"'"+cdID+"'"+','+"'"+data.ord[index_or].date_of_joining+"'"+');" class="btn btn-sm btn-dark"><i class="fa fa-edit"></i></button>';
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


function getrecruitment_inactive_list(){

    table_in = $('#table_inactive').DataTable({

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
        stateSave: true,

        drawCallback : function() {

            processInfo_tab5(this.api().page.info());

        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [10,11] }
        ],
        ajax: {
            url: get_recruitment_inactive_link,
            type: 'POST',
            data: function (d) {
                // d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref. No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(6)').attr('data-label', 'Assigned Date');
            $( row ).find('td:eq(7)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(8)').attr('data-label', 'Location');
            $( row ).find('td:eq(9)').attr('data-label', 'Position Status');
            // $( row ).find('td:eq(9)').attr('data-label', 'Action');
            $( row ).find('td:eq(10)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(11)').attr('data-label', 'Approved by');

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
            {data: 'sub_position_title', name: 'sub_position_title'},
            {data: 'ageing', name: 'ageing'},
            {data: 'assigned_date', name: 'assigned_date'},
            {data: 'open_date', name: 'open_date'},
            {data: 'location', name: 'location'},
            {data: 'request_status', name: 'request_status'},
            // {data: 'action', name: 'action', orderable: false, searchable: false},
            {data: 'raised_by', name: 'tr.raised_by', searchable: true},
            {data: 'approved_by', name: 'tr.approved_by', searchable: true},

        ],

    });
    $('#table_inactive tbody').unbind('click');

    // Add event listener for opening and closing details
    $('#table_inactive tbody').on('click', 'td.details-control', function() {
        var cur_row = $(this);
        var tr = cur_row.closest('tr');
        var row = table_in.row(tr);

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

function process_closedate(cd_rowid){

    $('#cd_updatebtn_'+cd_rowid+'').removeAttr("disabled");

    var get_close_date = $('#close_date_edit_'+cd_rowid+'').val();
    var hrr_cd = $('#hrr_cd_'+cd_rowid+'').val();
    $.ajax({
        type: "POST",
        url: closedate_update_link,
        data: { "cd_rowid":cd_rowid, "close_date":get_close_date, "hrr_cd":hrr_cd,},

        success:function(data){
            $('#cd_updatebtn_'+cd_rowid+'').removeAttr("disabled");
            var current_tab_title = $("#myTab > .nav-item > a.active").html();

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        if(current_tab_title == 'New positions allocated for the day'){
                            $('#new_position-tab').click();
                            $('#table1').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Old positions'){
                            $('#old_position-tab').click();
                            $('#table_op').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Offers released'){
                            $('#offer_released_ldj-tab').click();
                            $('#offer_released_tba').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Candidate Onboarded'){
                            $('#candidate_onboarded_ldj-tab').click();
                            $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                        }else{
                            $('#inactive-tab').click();
                            $('#table_inactive').DataTable().ajax.reload(null, false);
                        }
                    }, 2000
                );
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
                        if(current_tab_title == 'New positions allocated for the day'){
                            $('#new_position-tab').click();
                            $('#table1').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Old positions'){
                            $('#old_position-tab').click();
                            $('#table_op').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Offers released'){
                            $('#offer_released_ldj-tab').click();
                            $('#offer_released_tba').DataTable().ajax.reload(null, false);
                        }else if(current_tab_title == 'Candidate Onboarded'){
                            $('#candidate_onboarded_ldj-tab').click();
                            $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                        }else{
                            $('#inactive-tab').click();
                            $('#table_inactive').DataTable().ajax.reload(null, false);
                        }
                    }, 2000
                );
            }
        },
    });
}


function pop_dateof_joining(cdID,doj){

    $('#get_new_doj').val(doj);
    $('#doj_candidate_id').val(cdID);
    $('#show_dojedit_pop').click();
}

$('#btnEditdojUpdate').on('click',function(){


    $('#btnEditdojUpdate').attr("disabled",true);
    $('#btnEditdojUpdate').html("Processing");

    var get_new_doj = $('#get_new_doj').val();
    var cdID = $('#doj_candidate_id').val();

    $.ajax({
        type: "POST",
        url: dateofjoining_update_link,
        data: { "get_new_doj":get_new_doj, "cdID":cdID,},
        success:function(data){
            $('#btnEditdojUpdate').attr("disabled",false);
            $('#btnEditdojUpdate').html("Update");

            $('#close_edit_doj').click();
            var current_tab_title = $("#myTab > .nav-item > a.active").html();

            if(data.response =='success'){

                $('#pop_doj').text(get_new_doj.split("-").reverse().join("-"));
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                // setTimeout(
                //     function() {
                //         if(current_tab_title == 'New positions allocated for the day'){
                //             $('#new_position-tab').click();
                //             $('#table1').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Old positions'){
                //             $('#old_position-tab').click();
                //             $('#table_op').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Offers released'){
                //             $('#offer_released_ldj-tab').click();
                //             $('#offer_released_tba').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Candidate Onboarded'){
                //             $('#candidate_onboarded_ldj-tab').click();
                //             $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                //         }else{
                //             $('#inactive-tab').click();
                //             $('#table_inactive').DataTable().ajax.reload(null, false);
                //         }
                //     }, 2000
                // );
            }
            else{
                Toastify({
                    text: "Request Failed..! Try Again",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

                // setTimeout(
                //     function() {
                //         if(current_tab_title == 'New positions allocated for the day'){
                //             $('#new_position-tab').click();
                //             $('#table1').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Old positions'){
                //             $('#old_position-tab').click();
                //             $('#table_op').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Offers released'){
                //             $('#offer_released_ldj-tab').click();
                //             $('#offer_released_tba').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Candidate Onboarded'){
                //             $('#candidate_onboarded_ldj-tab').click();
                //             $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                //         }else{
                //             $('#inactive-tab').click();
                //             $('#table_inactive').DataTable().ajax.reload(null, false);
                //         }
                //     }, 2000
                // );
            }
        },
    });
})
