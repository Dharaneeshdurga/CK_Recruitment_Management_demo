$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function() {

    get_profile_list();
    get_position_title();
});

function show_advanced_filter(){

    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

function processInfo(info) {
    var res_found = info.recordsDisplay;
    $('#total_res_show').text(res_found);
}

function get_position_title(){

    $.ajax({
        type: "POST",
        url: get_position_apply_title_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].position_applying_to+'">'+data[index].position_applying_to+'</option>';
                }

                $('#af_position_title').html(html);

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


table_cot = $('#cand_profile').DataTable({

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
        url: get_external_candidate_database_link,
        type: 'POST',
        dataType: "json",
        data: function (d) {
            d.af_from_date = $('#af_from_date').val();
            d.af_to_date = $('#af_to_date').val();
            d.af_position_title = $('#af_position_title').val();
        }
    },
    createdRow: function( row, data, dataIndex ) {
        $( row ).find('td:eq(0)').attr('data-label', 'Sno');
        $( row ).find('td:eq(1)').attr('data-label', 'Name');
        $( row ).find('td:eq(2)').attr('data-label', 'Email');
        $( row ).find('td:eq(3)').attr('data-label', 'Mobile Number');
        $( row ).find('td:eq(4)').attr('data-label', 'Current Location');
        $( row ).find('td:eq(5)').attr('data-label', 'Department Type');
        $( row ).find('td:eq(6)').attr('data-label', 'Skill Set');
        $( row ).find('td:eq(7)').attr('data-label', 'Position Applying To');
        $( row ).find('td:eq(8)').attr('data-label', 'Years of Experience');
        $( row ).find('td:eq(9)').attr('data-label', 'Remarks');
        $( row ).find('td:eq(9)').attr('data-label', 'CV Upload');
        $( row ).find('td:eq(9)').attr('data-label', 'Created At');

    },
    columns: [
        {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
        {   data: 'name', name: 'name'    },
        {   data: 'email', name: 'email'  },
        {   data: 'mobile_number', name: 'mobile_number'  },
        {   data: 'current_location', name: 'current_location'  },
        {   data: 'exp', name: 'exp'  },
        {   data: 'skill_set', name: 'skill_set'  },
        {   data: 'position_applying_to', name: 'position_applying_to'  },
        {   data: 'years_of_experience', name: 'years_of_experience'  },
        {   data: 'remarks', name: 'remarks'  },
        {   data: 'cv_upload', name: 'cv_upload'  },
        {   data: 'created_at', name: 'created_at'  }

    ],
});
}

$("#af_from_date,#af_to_date,#af_position_title")
.on('change', function() {

    get_profile_list();

});

$("#afClearbtn").on('click', function() {
    $("#af_from_date").val("");
    $("#af_to_date").val("");
    $("#af_position_title").val("");

    get_profile_list();
    get_position_title();
});

function show_import_div(){
    $('#show_nopedit_pop').click();
}

function show_cv_upload_div(id){
    var hidden_id = id;
    $('#show_cv_upload_edit_pop').click();
    $('#hiddenid').val(hidden_id);
    // $value = id;
    // alert($('#hiddenid').val())
}

 //CV upload data
 $('#form_cv_upload').submit(function(e) {
    $('#btnCVSubmit').prop("disabled",true);
    $('#btnCVSubmit').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    e.preventDefault();
    var formData = new FormData(this);

    // var hiddenid = $('#hiddenid').val();
    // alert($('#hiddenid').val())
    // var file = $('#file').val();

    $.ajax({
        url:save_CV_form_link,
        method:"POST",
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        dataType:"json",

        success:function(data) {
            $('#btnCVSubmit').prop("disabled",false);
                $('#btnCVSubmit').html('Submit');

                console.log(data);
            if(data.response =='success'){

                Toastify({
                    text: "Added Successfully..!",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        location.reload();
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
                        location.reload();
                    }, 2000);

            }

        }
    });
});
