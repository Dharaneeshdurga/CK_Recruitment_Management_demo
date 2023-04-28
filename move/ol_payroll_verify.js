$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function() {

    get_offer_list_oat_po();
});

$("#po_offers-tab").on('click', function() {
    get_offer_list_oat_po();

});


$("#approved_offers-tab").on('click', function() {
    get_offer_list_oat_apo();

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

function get_offer_list_oat_po(){

    table_cot_1 = $('#po_tb').DataTable({

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
        // scrollX: true,
        // scrollY: 800,
        scrollCollapse: true,
        autoWidth: false,

        drawCallback: function() {
            processInfo_po(this.api().page.info());


        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [3,6,9,7,14,15] }
        ],
        ajax: {
            url: get_cor_oat_po_link,
            type: 'POST',
            data: function (d) {
                // d.af_from_date = $('#af_from_date').val();

            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'RFH No');
            $( row ).find('td:eq(3)').attr('data-label', 'HEPL Recruitment Ref.No');
            $( row ).find('td:eq(4)').attr('data-label', 'Department');
            $( row ).find('td:eq(5)').attr('data-label', 'Designation');
            $( row ).find('td:eq(6)').attr('data-label', 'Band');
            $( row ).find('td:eq(7)').attr('data-label', 'Followup');
            $( row ).find('td:eq(8)').attr('data-label', 'Offer Letter');
            $( row ).find('td:eq(9)').attr('data-label', 'Document Status');
            $( row ).find('td:eq(10)').attr('data-label', 'Approver');
            $( row ).find('td:eq(11)').attr('data-label', 'PO Detail');
            $( row ).find('td:eq(12)').attr('data-label', 'Finance Approvel');
            $( row ).find('td:eq(13)').attr('data-label', 'BH Approvel');
            $( row ).find('td:eq(14)').attr('data-label', 'OAT Status');
            $( row ).find('td:eq(15)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(15)').attr('data-label', 'Revert');
            $( row ).find('td:eq(16)').attr('data-label', 'Action for Approval');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'rfh_no', name: 'rfh_no'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'or_department', name: 'or_department'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'band_title', name: 'band_title'  },
            {   data: 'history', name: 'history'  },
            {   data: 'offer_letter_preview', name: 'offer_letter_preview'  },
            {   data: 'c_doc_status', name: 'c_doc_status'  },
            {   data: 'approver', name: 'approver'  },
            {   data: 'po_type', name: 'po_type'  },
            {   data: 'finance_status', name: 'finance_status'  },
            {   data: 'bhs_status', name: 'bhs_status'  },
            {   data: 'payroll_status', name: 'payroll_status'  },
            {   data: 'ageing', name: 'ageing'  },
            {   data: 'revert', name: 'revert'  },
            {   data: 'action', name: 'action'  },
        ],
    });
}

function processInfo_po(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab_po').text(res_found);


}

function get_offer_list_oat_apo(){
    table_cot_3 = $('#ao_tb').DataTable({

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
        // scrollX: true,
        // scrollY: 800,
        scrollCollapse: true,
        autoWidth: false,

        drawCallback: function() {
            processInfo_ao(this.api().page.info());
        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [3,6,9,7] }
        ],
        ajax: {
            url: get_cor_oat_ao_link,
            type: 'POST',
            data: function (d) {
                // d.af_from_date = $('#af_from_date').val();

            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'RFH No');
            $( row ).find('td:eq(3)').attr('data-label', 'HEPL Recruitment Ref.No');
            $( row ).find('td:eq(4)').attr('data-label', 'Department');
            $( row ).find('td:eq(5)').attr('data-label', 'Designation');
            $( row ).find('td:eq(6)').attr('data-label', 'Band');
            $( row ).find('td:eq(7)').attr('data-label', 'Followup');
            $( row ).find('td:eq(8)').attr('data-label', 'Offer Letter');
            $( row ).find('td:eq(9)').attr('data-label', 'Document Status');
            // $( row ).find('td:eq(10)').attr('data-label', 'OAT Status');
            $( row ).find('td:eq(10)').attr('data-label', 'Finance Status');
            $( row ).find('td:eq(11)').attr('data-label', 'BH Status');
            $( row ).find('td:eq(12)').attr('data-label', 'PO Detail');
            $( row ).find('td:eq(13)').attr('data-label', 'Approved By');

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'rfh_no', name: 'rfh_no'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'or_department', name: 'or_department'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'band_title', name: 'band_title'  },
            {   data: 'history', name: 'history'  },
            {   data: 'offer_letter_preview', name: 'offer_letter_preview'  },
            {   data: 'c_doc_status', name: 'c_doc_status'  },
            // {   data: 'payroll_status', name: 'payroll_status'  },
            {   data: 'finance_status', name: 'finance_status'  },
            {   data: 'leader_status', name: 'leader_status'  },
            {   data: 'po_type', name: 'po_type'  },
            {   data: 'approved_by', name: 'approved_by'  },
        ],
    });
}

function processInfo_ao(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show_tab_ao').text(res_found);
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


function edit_offer_letter_pop(rfh_no,cdID){

    $('#ctcedit_popbtn').click();
}


function approver_ld_npo_pop(rfh_no,cdID,po_type,approver){
    $('#action_btn_npo'+cdID).text('Processing');
    $('#action_btn_npo'+cdID).prop('disabled',true);

    $.ajax({
        type: "POST",
        url: send_to_leader_ol_link,
        data: { "rfh_no":rfh_no,"cdID":cdID,"po_type":po_type,"approver":approver,},
        success: function (data) {
            if(data.response =='success'){

                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {

                        $('#action_btn_npo'+cdID).text('Submit');
                        $('#action_btn_npo'+cdID).prop('disabled',false);

                        get_offer_list_oat_po();

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
                        $('#action_btn_npo'+cdID).text('Submit');
                        $('#action_btn_npo'+cdID).prop('disabled',false);
                        get_offer_list_oat_po();

                    }, 2000);

            }
        }
    });
}

function approver_ld_po_pop(rfh_no,cdID,po_type,approver){
    $('#action_btn_po'+cdID).text('Processing');
    $('#action_btn_po'+cdID).prop('disabled',true);

    $.ajax({
        type: "POST",
        url: send_po_bh_link,
        data: { "rfh_no":rfh_no,"cdID":cdID,"po_type":po_type,"approver":approver,},
        success: function (data) {
            if(data.response =='success'){

                Toastify({
                    text: "Sent Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {

                        $('#action_btn_po'+cdID).text('Submit');
                        $('#action_btn_po'+cdID).prop('disabled',false);

                        get_offer_list_oat_po();

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
                        $('#action_btn_po'+cdID).text('Submit');
                        $('#action_btn_po'+cdID).prop('disabled',false);
                        get_offer_list_oat_po();

                    }, 2000);

            }
        }
    });
}


function show_cpo_pop(rfh_no,cdID,leader_status,finance_status){

    $('#cpo_cdID').val(cdID);
    $('#cpo_rfh_no').val(rfh_no);
    $('#leader_status').val(leader_status);
    $('#finance_status').val(finance_status);
    $('#add_client_po_popbtn').click();

}
function show_cpo_att(rfh_no,cdID,leader_status,finance_status){

    $('#cpo_cdID1').val(cdID);
    $('#cpo_rfh_no1').val(rfh_no);
    $('#leader_status1').val(leader_status);
    $('#finance_status1').val(finance_status);
    $('#add_client_no_popbtn').click();

}

function show_fn_remark(cdID){

    var get_remark_content = $('#fn_remark_'+cdID+'').val();

    $('#show_fn_remark').text(get_remark_content);

    $('#view_fn_remark_btn').click();
}

function show_ld_remark(cdID){

    var get_remark_content = $('#ld_reject_remark_'+cdID+'').val();

    $('#show_ld_remark').text(get_remark_content);

    $('#view_ld_remark_btn').click();
}

$('#clientPoForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();
    var up_file = $('#client_po').val();
    var up_input = $('#client_po_no').val();

    if(up_file || up_input !== "" ){

     $('#uploadClientpobtn').prop("disabled",true);
    $('#uploadClientpobtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
    var can_id = $('#cpo_cdID').val();
    send_data_budgie(can_id);
    $.ajax({
            url:upload_clientpo_link,
            method:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#uploadClientpobtn').prop("disabled",false);
                $('#uploadClientpobtn').html('Upload');

                if(data.response =='success'){

                    Toastify({
                        text: "Uploaded Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            $('#clientPoclosebtn').click();

                            get_offer_list_oat_po();

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
                            $('#clientPoclosebtn').click();

                            get_offer_list_oat_po();

                        }, 2000);

                }

            }
        });
    }
    else{
        alert("Please upload or Po client Number..!!");
    }

});
function send_data_budgie(can_id){
    $.ajax({
        url: get_candidate_for_budgie_link,
        type: "POST",
        cors: true ,
        data:{'can_id':can_id},
        dataType: "json",
    //     cache:false,
    //     contentType: false,
    //    processData: false,
        success: function (data) {
           if(data.response == "success"){
          /// console.log(data)
           call_budgieGetdata(data);
           }
           else{
            console.log("error");
           }
        },


 })
}
function call_budgieGetdata(data){
   var cdID =  data.candidate[0].cdID;
   var can_name =  data.candidate[0].candidate_name;
   var doj =  data.candidate[0].or_doj;
   var rfh_no =  data.candidate[0].rfh_no;
   var closed_salary_pa =  data.candidate[0].closed_salary_pa;
   var created_by =  data.candidate[0].created_by;
   var or_recruiter_name =  data.candidate[0].or_recruiter_name;
   var 	welcome_buddy =  data.candidate[0].welcome_buddy;


   var business =  data.rfh[0].business;
   var vertical =  data.rfh[0].vertical;
   var week_off =  data.rfh[0].week_off;
   var designation =  data.rfh[0].designation;
   var department =  data.rfh[0].department;
   var division =  data.rfh[0].division;
   var emp_category =  data.rfh[0].emp_category;
   var attendance_format =  data.rfh[0].attendance_format;
   var location =  data.rfh[0].location;
   var approve_by =  data.rfh[0].approve_by;
   var position_reports =  data.rfh[0].position_reports;
   var reporter_id =  data.rfh[0].reporter_id;
   var approver_id =  data.rfh[0].approver_id;


    $.ajax({
     //   url: "http://127.0.0.1:8080/api/get_candidate_details_prohire",
        url: "http://216.48.177.166/budgie/public/index.php/api/get_candidate_details_prohire",
        type: "POST",
        cors: true ,
        data:{
            "approve_by":approve_by,
            "position_reports":position_reports,
            "reporter_id":reporter_id,
            "approver_id":approver_id,
            "cdID":cdID,
            "business":business,
            "vertical":vertical,
            "week_off":week_off,
            "designation":designation,
            "department":department,
            "division":division,
            "emp_category":emp_category,
            "attendance_format":attendance_format,
            "location":location,
            "approve_by":approve_by,
           "can_name" :  can_name,
           "doj" : doj,
           "rfh_no" :  rfh_no,
           "closed_salary_pa" : closed_salary_pa,
           "created_by" :  created_by,
           "or_recruiter_name" :  or_recruiter_name,
           "welcome_buddy" : welcome_buddy

    },
        dataType: "json",
        success: function (data) {
           if(data.response == "success"){
            console.log("All candidate data sends to budgie");
           }
           else{
            console.log("error");
           }
        },


 })

}
$('#clientPoForm1').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();
    var up_file = $('#client_po1').val();
    //var up_input = $('#client_po_no').val();

    if(up_file  !== "" ){

     $('#uploadClientpobtn1').prop("disabled",true);
    $('#uploadClientpobtn1').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    $.ajax({
            url:upload_clientpo_link,
            method:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#uploadClientpobtn1').prop("disabled",false);
                $('#uploadClientpobtn1').html('Upload');

                if(data.response =='success'){

                    Toastify({
                        text: "Uploaded Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            $('#clientPoclosebtn1').click();

                            get_offer_list_oat_po();

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
                            $('#clientPoclosebtn1').click();

                            get_offer_list_oat_po();

                        }, 2000);

                }

            }
        });
    }
    else{
        alert("Please upload  Po client Attachment..!!");
    }

});
function get_oat_age_dt(created_by,hepl_recruitment_ref_number,cdID,candidate_name){
    $('#oat_candidate_name').text(candidate_name);
    $('#view_oat_ageing').modal("show");
    $.ajax({
        url:get_oat_ageing_link,
        method:"POST",
        data: { "cdID":cdID, "created_by":created_by,"hepl_recruitment_ref_number":hepl_recruitment_ref_number,},
        dataType:"json",

        success:function(data) {
           // console.log(data.age_dt);
           if(data.ch_pdr.length !=0){
            $('#oat_hepl_ref_no').text(hepl_recruitment_ref_number);
            $('#oat_recruiter').text(data.ch_pdr[0].recruiter_name);
            $('#oat_position').text(data.ch_pdr[0].position_title);
        }

           var td_html ="";
           for (let index = 0; index < data.age_dt.length; index++) {
            var offer_date = new Date(data.age_dt[index].created_at);
            var date = offer_date.getDate();
            var month = offer_date.getMonth();
            var year = offer_date.getFullYear();
            td_html += '<tr>';
            td_html += '<td>'+(index+1)+'</td>';
            td_html += '<td>'+data.age_dt[index].description+'</td>';
            td_html += '<td>'+date+'-'+month+'-'+year+'</td>';
            td_html += '</tr>';
           }
$('#oatageing_body').html(td_html);
        }
    });
}
function get_payroll_remark(cdID){
    $("#can_id").val(cdID);
    $('#view_remark_oat').modal("show");
}
$('#oatRemarkform').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

    $('#Remarkbtn').prop("disabled",true);
    $('#Remarkbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
    $.ajax({
            url:payroll_revert_update_link,
            method:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#Remarkbtn').prop("disabled",false);
                $('#Remarkbtn').html('Submit');
                if(data.response =='success'){

                    Toastify({
                        text: "Reverted Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            $('#Remarkclosebtn').click();

                            get_offer_list_oat_po();

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
                            $('#Remarkclosebtn').click();

                            get_offer_list_oat_po();

                        }, 2000);

                }

            }
        });


});
