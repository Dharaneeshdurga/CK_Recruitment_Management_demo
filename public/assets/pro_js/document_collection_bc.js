$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
     
}); 

$(document).ready(function() {

    get_profile_list();
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
            $( row ).find('td:eq(12)').attr('data-label', 'OAT Ageing');
            $( row ).find('td:eq(13)').attr('data-label', 'Action');
            
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