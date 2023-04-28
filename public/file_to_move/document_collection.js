$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
     
}); 

$(document).ready(function() {

    get_profile_list();
    get_buddy_list();
    get_department_list();
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

function get_profile_list(){


    table_cot = $('#doc_col_tb').DataTable({
        
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
            processInfo(this.api().page.info());
        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [3,9,10] }
        ],
        ajax: {
            url: get_candidate_docinfo_link,
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
            $( row ).find('td:eq(4)').attr('data-label', 'Position');
            $( row ).find('td:eq(5)').attr('data-label', 'Sub Position');
            $( row ).find('td:eq(6)').attr('data-label', 'Follow Up');
            $( row ).find('td:eq(7)').attr('data-label', 'Document Status');
            $( row ).find('td:eq(8)').attr('data-label', 'OAT Status');
          
            $( row ).find('td:eq(9)').attr('data-label', 'Finance Status');
            $( row ).find('td:eq(10)').attr('data-label', 'BH Status');
            $( row ).find('td:eq(11)').attr('data-label', 'Offer Letter');
            $( row ).find('td:eq(8)').attr('data-label', 'OAT Ageing');
            $( row ).find('td:eq(8)').attr('data-label', 'OAT Remark');
            $( row ).find('td:eq(12)').attr('data-label', 'Action');
            
        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'candidate_name', name: 'candidate_name'  },
            {   data: 'rfh_no', name: 'rfh_no'  },
            {   data: 'hepl_recruitment_ref_number', name: 'hepl_recruitment_ref_number'  },
            {   data: 'position_title', name: 'position_title'  },
            {   data: 'sub_position_title', name: 'sub_position_title'  },
            {   data: 'history', name: 'history'  },
            {   data: 'c_doc_status', name: 'c_doc_status'  },
            {   data: 'payroll_status', name: 'payroll_status'  },
            {   data: 'finance_status', name: 'finance_status'  },
          
            {   data: 'leader_status', name: 'leader_status'  },
            {   data: 'offer_letter_preview', name: 'offer_letter_preview'  },
            {   data: 'ageing', name: 'ageing'  },
            {   data: 'remark', name: 'remark'  },
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

function get_offer_release_pop(cdID,rfh_no,hepl_recruitment_ref_number){
    $('#show_offer_rel_pro_pop').click();
    $('#or_cdID').val(cdID);
    $('#or_rfh_no').val(rfh_no);
    $('#or_hepl_recruitment_ref_number').val(hepl_recruitment_ref_number);
}

$("#preview_ol_btn").click(function(){
    
    var or_cdID = $('#or_cdID').val();
    var or_rfh_no = $('#or_rfh_no').val();
    var or_closed_salary = $('#closed_salary_pa').val();
    var or_doj = $('#or_doj').val();
    var welcome_buddy_id = $('#welcome_buddy').val();
    var po_type = $('#po_type').val();
    var or_department = $('#or_department').val();
    var last_drawn_ctc = $('#last_drawn_ctc').val();
    var get_emp_mode = $('#get_emp_mode').val();
    var get_reg_type = $('#register_fee').val();
   // alert(get_reg_type);
    if(last_drawn_ctc =='' || get_reg_type == '' || get_emp_mode =='' || or_closed_salary =='' || welcome_buddy_id =='' || or_doj =='' || po_type =='' || or_department==''){
        
        $('#required_fields_err').css("display","block");

        setTimeout(
            function() {
                $('#required_fields_err').css("display","none");
                
            }, 2000);
    }
    else{
        
        $.ajax({
            type: "POST",
            url: process_offer_letter_release_link,
            data: { "last_drawn_ctc":last_drawn_ctc,
            "get_reg_type":get_reg_type,
            "get_emp_mode":get_emp_mode,
            "cdID":or_cdID,
            "or_department":or_department,"or_doj":or_doj,
            "po_type":po_type,
            "rfh_no":or_rfh_no,
            "or_closed_salary":or_closed_salary,
            "welcome_buddy_id":welcome_buddy_id,
        },
            
            success: function (response) {

                $('#offerReleasedProcessbtn').removeAttr("disabled");

                // return false;
                $('#offer_letter_name').val(response);
                 var host = window.location.origin+'/CK_recruitment_management_new/public'; 
               // var host = window.location.origin; 
                // alert(host);
                var host_url = host+"/"+response;
                window.open(host_url, '_blank').focus();
                // $("#offer_letter_preview").html('<embed src="'+host_url+'"  type="application/pdf"   height="500px" width="100%">');

            
            }
        });
    }
});

$("#offerReleasedProcessbtn").click(function(){

    $('#offerReleasedProcessbtn').prop("disabled",true);
    $('#offerReleasedProcessbtn').text("Processing");

    var hepl_rrno = $('#or_hepl_recruitment_ref_number').val();
    var or_cdID = $('#or_cdID').val();
    var or_rfh_no = $('#or_rfh_no').val();
    var get_emp_mode = $('#get_emp_mode').val();
    var closed_salary_pa = $('#closed_salary_pa').val();
    var or_salary_review = $('#or_salary_review').val();
    var or_joining_type = $('#or_joining_type').val();
    var or_remark = $('#or_remark').val();
    var or_doj = $('#or_doj').val();
    var or_bc_mailid = $('#or_bc_mailid').val();
    var or_cc_mailid = $('#or_cc_mailid').val();
    var welcome_buddy_id = $('#welcome_buddy').val();
    var offer_letter_name = $('#offer_letter_name').val();
    var or_department = $('#or_department').val();
    var last_drawn_ctc = $('#last_drawn_ctc').val();
    var po_type = $('#po_type').val();
    var get_reg_type = $('#register_fee').val();
    var or_approver = $('#or_approver').val();
  //  var proof_attach = $('#proof_of_attach').val().files[0].name;
//     var formData = new FormData();
//     var myformData = formData.append('candidate_id', proof_attach.files[0]);
 // alert(proof_attach);
    if(last_drawn_ctc =='' || get_reg_type == '' || get_emp_mode=='' || closed_salary_pa =='' || welcome_buddy_id =='' || or_doj =='' || po_type =='' || or_department==''){
        
        $('#required_fields_err').css("display","block");

        setTimeout(
            function() {
                $('#required_fields_err').css("display","none");
                
            }, 2000);
    }
    else{
        $.ajax({
            type: "POST",
            url: send_to_payroll_link,
            data: { "last_drawn_ctc":last_drawn_ctc,  "get_reg_type":get_reg_type,"hepl_rrno":hepl_rrno,"cdID":or_cdID,"rfh_no":or_rfh_no,"get_emp_mode":get_emp_mode,"closed_salary_pa":closed_salary_pa,
                "or_salary_review":or_salary_review,"or_joining_type":or_joining_type,"or_remark":or_remark,
                "or_doj":or_doj,"or_bc_mailid":or_bc_mailid,"or_cc_mailid":or_cc_mailid,"welcome_buddy_id":welcome_buddy_id,
                "offer_letter_name":offer_letter_name,"or_department":or_department,"po_type":po_type,"or_approver":or_approver,
            },
            
            success: function (data) {

                if(data.response =='success'){

                    $('#offerReleasedProcessbtn').text("Send to OAT");

                    $("#orpForm")[0].reset()
                    $('#offerReleasedProcessbtn').attr("disabled");

                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {

                            $('#close_offer_rel').click();
                            get_profile_list();

                            // window.location = 'view_recruit_request_default';
                            // location.reload();
                        }, 2000);

                }
                else{
                    $("#orpForm")[0].reset()
                    $('#offerReleasedProcessbtn').text("Send to OAT");
                    $('#offerReleasedProcessbtn').prop("disabled","false");


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
    }
});

function get_buddy_list(){
    $.ajax({
        type: "POST",
        url: get_buddylist_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                }
                
                $('#welcome_buddy').html(html);

            }
        }
    });
}
function get_department_list(){
    $.ajax({
        type: "POST",
        url: get_department_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].name+'">'+data[index].name+'</option>';
                }
                $('#or_department').html(html);

            }
        }
    });
}

function show_ld_remark(cdID){

    var get_remark_content = $('#ld_reject_remark_'+cdID+'').val();

    $('#show_ld_remark').text(get_remark_content);
    
    $('#view_ld_remark_btn').click();
}

$('#get_emp_mode').on('change', function() {

    if(this.value !='HEPL'){
        $('#closed_salary_pa_lb').text("Offer Amount(PM)*");
    }else{
        $('#closed_salary_pa_lb').text("Offer CTC(PA)*");
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
function get_payroll_remark(remark){
    $("#oat_remark").val(remark);
    $('#view_remark_oat').modal("show");
}

$(document).on("change", "#proof_of_attach", function() {   // If you want to upload without a submit button 
  //  $(document).on("click", "#upload", function() {
      var file_data = $("#proof_of_attach").prop("files")[0]; // Getting the properties of file from file field
      var form_data = new FormData(); // Creating object of FormData class
      form_data.append("proof_attach", file_data) // Appending parameter named file with properties of file_field to form_data
      var or_cdID = $('#or_cdID').val();
      var or_rfh_no = $('#or_rfh_no').val();
      form_data.append('cdID', or_cdID);
      form_data.append('rfh_no', or_rfh_no);
     // alert("file_data");
      $.ajax({
        url: upload_rc_file_attach_link, // Upload Script
        dataType: 'script',
        cache: false,
        contentType: false,
        processData: false,
        data: form_data, // Setting the data attribute of ajax with file_data
        type: 'post',
        success: function(data) {
          // Do something after Ajax completes 
        }
      });
    });