
$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
     
}); 

// get task details on load
$(document).ready(function() {

    get_recruiter();
    
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

function get_recruiter(){

    table_view = $('#recriter_tb').DataTable({
        
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
        lengthMenu:[[15, 50, 100, 250, 500, -1], [15, 50, 100, 250, 500, "All"]],
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
        ajax: {
            url: get_recruiter_list_link,
            type: 'POST',
            data: function (d) {
                // d.hepl_recruitment_ref_number =hepl_recruitment_ref_number;
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'EmpID');
            $( row ).find('td:eq(2)').attr('data-label', 'Name');
            $( row ).find('td:eq(3)').attr('data-label', 'Designation');
            $( row ).find('td:eq(4)').attr('data-label', 'Email');
            $( row ).find('td:eq(5)').attr('data-label', 'Team');
            $( row ).find('td:eq(6)').attr('data-label', 'Action');
                
        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'empID', name: 'empID'  },
            {   data: 'name', name: 'name'  },
            {   data: 'designation', name: 'designation'  },
            {   data: 'email', name: 'email'  },
            {   data: 'team', name: 'team'  },
            {   data: 'action', name: 'action'  },
        ],
    });
}

function processInfo(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show').text(res_found);
} 

function reset_password(empID){

    $("#btnConfirm").click();

    $("#btnConfirmsubmit").on('click', function() {
        $("#confirmClose").click();

        $.ajax({
            type: "POST",
            url: reset_password_link,
            data: { "empID":empID,},
            success: function (data) {
                
                if(data.response =='Updated'){
                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();
    
                    setTimeout(
                        function() {
                            get_recruiter();
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
                            get_recruiter();

                        }, 2000);
    
                    
                }
            }
        })
    });
}

function recruiter_delete_process(empID){
    $('#confirmbox').click();
    $("#confirmSubmit").on('click', function() {
        $('#close_delete_pop').click();

        $.ajax({
            url: process_recruiter_delete_link,
            method: "POST",
            data:{"empID":empID,},
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
                            get_recruiter();
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
                            get_recruiter();
                        }, 2000);
    

                }
            }

        });
    });
    $("#cancelSubmit").on('click', function() {
        
        $('#modal_close').click();
        location.reload();

        return false;
    });
}


function recruiter_edit_process(empID){
    
    $('#edit_pop_modal_div').modal('show');

    $.ajax({
        url: get_recruiter_details_link,
        method: "POST",
        data:{"empID":empID,},
        dataType: "json",
        success: function(data) {

            if(data.length !=0){
                $('#ed_emp_name').val(data[0].name);
                $('#ed_designation').val(data[0].designation);
                $('#ed_email').val(data[0].email);
                $('#ed_team').val(data[0].team);
                $('#ed_empID').val(empID);

                if(data[0].designation !='Recruiter' ){
                    $('#ed_team_label').css({'display':'none'});
                    $('#ed_team').css({'display':'none'});
                }
            }
        }
    });
}


$("#editUpdate").on('click', function() {
    
    $("#editUpdate").attr("disabled", true);
    $('#editUpdate').html('Processing..!');

    var ed_emp_name = $('#ed_emp_name').val();
    var ed_designation = $('#ed_designation').val();
    var ed_email = $('#ed_email').val();
    var ed_team = $('#ed_team').val();
    var ed_empID = $('#ed_empID').val();

    if(ed_designation == 'Backend Coordinator'){
        ed_team = "";
    }
    $.ajax({
        url: update_recruiter_details_link,
        method: "POST",
        data:{
            "empID":ed_empID,
            "name":ed_emp_name,
            "designation":ed_designation,
            "email":ed_email,
            "team":ed_team,
        },
        dataType: "json",
        success: function(data) {

            $('#close_edit_pop').click();
            $("#editUpdate").attr("disabled", false);
            $('#editUpdate').html('Update');

            if(data.response =='Updated'){
                Toastify({
                    text: "Updated Successfully",
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
                    get_recruiter();
                }, 2000);

        }
    });
});