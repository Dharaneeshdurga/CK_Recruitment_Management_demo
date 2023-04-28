

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
     
}); 

// get task details on load
$(document).ready(function() {

    get_profile_list();
    get_position_title();
    get_sub_position_title();
    get_recruiter_list();
});

function get_recruiter_list(){
    $.ajax({
        type: "POST",
        url: get_recruiter_list_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                }
                
                $('#af_created_by').html(html);

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

function get_profile_list(){


    table_cot = $('#c_profile').DataTable({
        
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
            processInfo(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        ajax: {
            url: get_candidate_profile_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_sub_position_title = $('#af_sub_position_title').val();
                d.af_position_status = $('#af_position_status').val();
                d.af_created_by = $('#af_created_by').val();
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Candidate Name');
            $( row ).find('td:eq(2)').attr('data-label', 'Position');
            $( row ).find('td:eq(3)').attr('data-label', 'Sub Position');
            $( row ).find('td:eq(4)').attr('data-label', 'Candidate Source');
            $( row ).find('td:eq(5)').attr('data-label', 'Gender');
            $( row ).find('td:eq(6)').attr('data-label', 'Email');
            $( row ).find('td:eq(7)').attr('data-label', 'Business');
            $( row ).find('td:eq(8)').attr('data-label', 'Closed Salary');
            $( row ).find('td:eq(9)').attr('data-label', 'CV');
            $( row ).find('td:eq(10)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(11)').attr('data-label', 'Status');
            $( row ).find('td:eq(12)').attr('data-label', 'RFH NO');
            $( row ).find('td:eq(13)').attr('data-label', 'HEPL Recruitment Ref. No.');
            $( row ).find('td:eq(14)').attr('data-label', 'Created By');
            $( row ).find('td:eq(15)').attr('data-label', 'Created On');
            $( row ).find('td:eq(16)').attr('data-label', 'Action');
            
            if (data.red_flag_status == "1") {
                $(row).addClass('table-danger');
            }
            if (data.status_cont == "Offer Rejected") {
                $(row).addClass('table-warning');
            }

        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'candidate_source', name: 'candidate_source'  },
            {   data: 'gender', name: 'gender'  },
            {   data: 'candidate_email', name: 'candidate_email'  },
            {   data: 'business', name: 'business'  },
            {   data: 'closed_salary', name: 'closed_salary'  },
            {   data: 'candidate_cv', name: 'candidate_cv'  },
            {   data: 'history', name: 'history'  },
            {   data: 'status', name: 'status'  },  
            {   data: 'created_name', name: 'created_name'  },
            {   data: 'rfh_no', name: 'rfh_no'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'created_on', name: 'created_on'  },
            {   data: 'action', name: 'action'  },
        ],
    });
}

function processInfo(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show').text(res_found);
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

function show_advanced_filter(){
    
    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_position_status,#af_created_by")
.on('change', function() {
    
    get_profile_list();

});

$("#afClearbtn").on('click', function() {
    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");
    $("#af_sub_position_title").val("");
    $("#af_position_status").val("");
    $("#af_created_by").val("");
    $(".js-select2").select2();
    
    get_profile_list();

});

function delete_candidate_pop(candidate_id){
    $('#confirmbox').click();
    $("#confirmSubmit").on('click', function() {
        $('#close_delete_pop').click();

        $.ajax({
            url: process_candidate_delete_link,
            method: "POST",
            data:{"candidate_id":candidate_id,},
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
                            get_profile_list();
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
                            get_profile_list();
                        }, 2000);
    

                }
            }

        });
    })
    $("#cancelSubmit").on('click', function() {
        
        $('#modal_close').click();
        get_profile_list();

        return false;
    });

}

function edit_candidate_pop(candidate_id){

    $.ajax({
        url: get_candidate_details_ed_link,
        method: "POST",
        data:{"candidate_id":candidate_id,},
        dataType: "json",
        success: function(data) {
            $('#confirmEditbox').click();

            if(data.length !=0){
                $('#candidate_name').val(data[0].candidate_name);
                $('#candidate_gender').val(data[0].gender);
                $('#candidate_email').val(data[0].candidate_email);
                $('#candidate_source').val(data[0].candidate_source);
                $('#candidate_id').val(data[0].cdID);

            }
        }
    })
}

$('#editSubmit').on('click',function(){
    $("#editSubmit").attr("disabled", true);
    $('#editSubmit').html('Processing..!');

    var candidate_name = $('#candidate_name').val();
    var candidate_gender = $('#candidate_gender').val();
    var candidate_email = $('#candidate_email').val();
    var candidate_source = $('#candidate_source').val();
    var cdID = $('#candidate_id').val();

    $.ajax({
        url: process_candidate_edit_link,
        method: "POST",
        data:{ "cdID":cdID,"candidate_name":candidate_name,"candidate_gender":candidate_gender,"candidate_email":candidate_email,"candidate_source":candidate_source,},
        dataType: "json",
        success: function(data) {
            $("#editSubmit").attr("disabled", false);
            $('#editSubmit').html('Update');
            $('#close_edit_pop').click();
            
            if(data.response =='success'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        get_profile_list();
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
                        get_profile_list();
                    }, 2000);


            }
        }

    });
});

function pop_salary_edit(salary,rfh_no,cdID){
    $('#show_ctcedit_pop').click();
    $('#show_edit_ctcpop_title').html('Edit Closed Salary');
    $('#current_closed_salary').val(salary);
    $('#rfh_ctc').val(rfh_no);
    $('#cid_ctc').val(cdID);
    
}

$("#btnEditCtcUpdate").on('click', function() {

    var current_closed_salary = $('#current_closed_salary').val();
    var rfh_ctc = $('#rfh_ctc').val();
    var cid_ctc = $('#cid_ctc').val();

    $('#btnEditCtcUpdate').prop("disabled", true);
    $('#updateCtcbtn').text("Processing...");
    
    $.ajax({
        type: "POST",
        url: update_closed_salary_bc_link,
        data: { "current_closed_salary":current_closed_salary,"rfh_ctc":rfh_ctc,"cid_ctc":cid_ctc,},
        success: function (data) {
            $('#updateCtcbtn').text("Update");
            $('#btnEditCtcUpdate').prop("disabled", false);

            $('#current_closed_salary').val("");
            $('#rfh_ctc').val("");
            $('#cid_ctc').val("");

            $('#close_edit_ctcpop').click();

            if(data.response =='success'){
                Toastify({
                    text: "Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        get_profile_list();

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
                        get_profile_list();

                    }, 2000);

                
            }
        }
    })
});
