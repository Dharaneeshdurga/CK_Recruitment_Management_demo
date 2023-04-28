$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    
   
}); 

$('#cv_count').click(function() {
    sortTable(5, 'number');
  });
  $('#pos_ageing').click(function() {
    sortTable(6, 'number');
  });
  $('#pro_ageing').click(function() {
    sortTable(7, 'number');
  });
  function sortTable(column, type) {

    //Get and set order
    //Use -data to store wheater it will be sorted ascending or descending
    var order = $('.table thead tr>th:eq(' + column + ')').data('order');
    order = order === 'ASC' ? 'DESC' : 'ASC';
    $('.table thead tr>th:eq(' + column + ')').data('order', order);
  
    //Sort the table
    $('.table tbody tr').sort(function(a, b) {
    //                                 ^  ^
    //                                 |  | 
    //        The 2 parameters needed to be compared. 
    //        Since you are sorting rows, a and b are <tr>                                 
  
      //Find the <td> using the column number and get the text value.
      //Now, the a and b are the text of the <td>
      a = $(a).find('td:eq(' + column + ')').text();
      b = $(b).find('td:eq(' + column + ')').text();
      
      switch (type) {
        case 'text':
          //Proper way to compare text in js is using localeCompare
          //If order is ascending you can - a.localeCompare(b)
          //If order is descending you can - b.localeCompare(a);
          return order === 'ASC' ? a.localeCompare(b) : b.localeCompare(a);
          break;
        case 'number':
          //You can use deduct to compare if number.
          //If order is ascending you can -> a - b. 
          //Which means if a is bigger. It will return a positive number. b will be positioned first
          //If b is bigger, it will return a negative number. a will be positioned first
          return order === 'ASC' ? a - b : b - a;
          break;
        case 'date':
          var dateFormat = function(dt) {
            [m, d, y] = dt.split('/');
            return [y, m - 1, d];
          }
  
          //convert the date string to an object using `new Date`
          a = new Date(...dateFormat(a));
          b = new Date(...dateFormat(b));
  
          //You can use getTime() to convert the date object into numbers. 
          //getTime() method returns the number of milliseconds between midnight of January 1, 1970
          //So since a and b are numbers now, you can use the same process if the type is number. Just deduct the values.
          return order === 'ASC' ? a.getTime() - b.getTime() : b.getTime() - a.getTime();
          break;
      }
  
    }).appendTo('.table tbody');
  }
  
$(document).ready(function() {
    getrecruitmentRequestlist();

    get_recruiter_list();
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
                
                $('#af_recruiters').html(html);

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
    return '<table class="table table-striped" cellpadding="5" cellspacing="0" border="0" style="padding-left:50px;" id="child_view">' +
        '<tr>' +
        '<td class="hide table-info">RFH No</td>' +
        '<td data-label="RFH No">' + d.rfh_no + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">No of Position</td>' +
        '<td data-label="No of Position">' + d.no_of_position + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Business</td>' +
        '<td data-label="Business">' + d.business + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Request Raised By</td>' +
        '<td>' + d.raised_by + '</td>' +
        '</tr>' +
        '<tr>' +
        '<td class="hide table-info">Band</td>' +
        '<td data-label="Band">' + d.band_title + '</td>' +
        '</tr>' +
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
        '<td data-label="Function">' + d.function + '</td>' +
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
        '<td class="hide table-info">Maximum CTC(Per Month)</td>' +
        '<td data-label="Maximum CTC(Per Month)">' + d.salary_range + '</td>' +
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
            
            $.getScript("../assets/vendors/choices.js/choices.min.js");
            // $(".up_href").attr("href", "http://cupcream.com");
            var get_af_recruiters = $('#af_recruiters').val();
            if(get_af_recruiters !='' && get_af_recruiters !=null){
                var af_recruiters =get_af_recruiters;
            }
            else{
                var af_recruiters ="no_user";

            }

            var allLinks = $('.up_href').map( function() {
                // console.log( $(this).attr('href'));
                var current_href = $(this).attr('href');
                var new_href = current_href+"&rec_id="+af_recruiters;
                $(this).attr("href", new_href);

            }).get();

            processInfo(this.api().page.info());

        },
        aoColumnDefs: [
            { 'visible': false, 'targets': [9,12,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31] },
        ],
        ajax: {
            url:get_ticket_report_link,
            type: 'POST',
            data: function (d) {
                d.af_from_date = $('#af_from_date').val();
                d.af_to_date = $('#af_to_date').val();
                d.af_position_title = $('#af_position_title').val();
                d.af_sub_position_title = $('#af_sub_position_title').val();
                d.af_critical_position = $('#af_critical_position').val();
                d.af_position_status = $('#af_position_status').val();
                d.af_assigned_status = $('#af_assigned_status').val();
                d.af_salary_range = $('#af_salary_range').val();
                d.af_band = $('#af_band').val();
                d.af_location = $('#af_location').val();
                d.af_business = $('#af_business').val();
                d.af_billing_status = $('#af_billing_status').val();
                d.af_function = $('#af_function').val();
                d.af_recruiters = $('#af_recruiters').val();
                d.af_division = $('#af_division').val();
                d.af_teams = $('#af_teams').val();
                d.af_raisedby = $('#af_raisedby').val();
                d.af_approvedby = $('#af_approvedby').val();
            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', '#');
            $( row ).find('td:eq(1)').attr('data-label', 'Sno');
            $( row ).find('td:eq(2)').attr('data-label', 'HEPL Recruitment Ref No');
            $( row ).find('td:eq(3)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(4)').attr('data-label', 'Sub Position Title');
            $( row ).find('td:eq(5)').attr('data-label', 'No. of Position');
            $( row ).find('td:eq(6)').attr('data-label', 'Ageing');
            $( row ).find('td:eq(7)').attr('data-label', 'Recruiter Ageing');
            $( row ).find('td:eq(8)').attr('data-label', 'Open Date');
            $( row ).find('td:eq(9)').attr('data-label', 'Closed Date');
            $( row ).find('td:eq(10)').attr('data-label', 'Current Status');
            $( row ).find('td:eq(11)').attr('data-label', 'Last Modified');
            $( row ).find('td:eq(12)').attr('data-label', 'Location');
            $( row ).find('td:eq(13)').attr('data-label', 'Recruiter');
            $( row ).find('td:eq(14)').attr('data-label', 'Assigned Status');
            $( row ).find('td:eq(15)').attr('data-label', 'Action');

            $( row ).find('td:eq(16)').attr('data-label', 'Assigned Title');
            $( row ).find('td:eq(17)').attr('data-label', 'Position Title');
            $( row ).find('td:eq(18)').attr('data-label', 'RFH No');
            $( row ).find('td:eq(19)').attr('data-label', 'No of Position');
            $( row ).find('td:eq(20)').attr('data-label', 'Business');
            $( row ).find('td:eq(21)').attr('data-label', 'Band');
            $( row ).find('td:eq(22)').attr('data-label', 'Critical Position');
            $( row ).find('td:eq(23)').attr('data-label', 'Division');
            $( row ).find('td:eq(24)').attr('data-label', 'Function');
            $( row ).find('td:eq(25)').attr('data-label', 'Billing Status');
            $( row ).find('td:eq(26)').attr('data-label', 'Interviewer');
            $( row ).find('td:eq(27)').attr('data-label', 'Maximum CTC(Per Month)');
            $( row ).find('td:eq(28)').attr('data-label', 'Request raised by');
            $( row ).find('td:eq(29)').attr('data-label', 'Approved by');
            $( row ).find('td:eq(30)').attr('data-label', 'Assigned Date');
            $( row ).find('td:eq(31)').attr('data-label', 'Closed Date');

            if (data.tat_process == "show_tat_highlight") {
                $(row).addClass('table-danger');
            }

            if (data.recruiter_ageing_status == "four_show_recruiter_ageing_highlight") {
                $(row).find('td:eq(7)').addClass('table-danger-orange');
            }
            if(data.recruiter_ageing_status == "three_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(7)').addClass('table-danger');
            }
            if(data.recruiter_ageing_status == "five_show_recruiter_ageing_highlight"){
                $(row).find('td:eq(7)').addClass('table-danger-dark');
            }
        },
        
        'columnDefs'        : [         // see https://datatables.net/reference/option/columns.searchable
            { 
                'searchable'    : false, 
                'targets'       : [0,1,5,6,7,9,12,13,14,27,28,29,30] 
            },
            { 
                'orderable'    : false, 
                'targets'       : [6,7] 
            },
        ],
        createdRow: function (row, data, rowIndex) {
            $.each($('td', row), function (colIndex) {
              if (colIndex === 5) {
                $(this).attr('data-order', data.cv_count);
              }
              if (colIndex === 6) {
                $(this).attr('data-order', data.ageing);
              }
              if (colIndex === 7) {
                $(this).attr('data-order', data.recruiter_ageing);
              }
            });
          },
          
        columns: [
            {
                "className": 'details-control',
                "orderable": false,
                "searchable": false,
                "data": null,
                "defaultContent": ''
            },

            {data: 'DT_RowIndex', name: 'DT_RowIndex', searchable: false,},
            {data: 'hepl_recruitment_ref_number', name: 'rr.hepl_recruitment_ref_number', searchable: true,},
            {data: 'position_title', name: 'rr.position_title', searchable: true,sortable : true,},
            {data: 'sub_position_title', name: 'rr.sub_position_title', searchable: true,sortable : true,},
            {data: 'cv_count', name: 'cv_count'},
            {data: 'ageing', name: 'ageing', searchable: false,sortable : false,orderable: false,},
            {data: 'recruiter_ageing', name: 'recruiter_ageing'},
            {data: 'open_date', name: 'rr.open_date', searchable: false,},
            {data: 'closed_date', name: 'rr.close_date', searchable: false,},
            
            {data: 'current_status', name: 'current_status', searchable: true,sortable : true,orderable: true,},
            {data: 'last_modified', name: 'last_modified', searchable: true,sortable : true,orderable: true,},
            {data: 'location', name: 'rr.location', searchable: true,sortable : true,orderable: true,},
            {data: 'recruiter_name', name: 'recruiter_name', searchable: false,},
            {data: 'assigned_status', name: 'rr.assigned_status', searchable: false,},
            {data: 'action', name: 'action', orderable: false, searchable: false},
            
            {data: 'as_title', name: 'as_title', searchable: false},
            {data: 'ps_title', name: 'ps_title', searchable: false},
            {data: 'rfh_no', name: 'rr.rfh_no', searchable: true},
            {data: 'no_of_position', name: 'rr.no_of_position', searchable: true},
            {data: 'business', name: 'rr.business', searchable: true},
            {data: 'band_title', name: 'rr.band', searchable: true},
            {data: 'critical_position', name: 'rr.critical_position', searchable: true},
            {data: 'division', name: 'rr.division', searchable: true},
            {data: 'function', name: 'rr.function', searchable: true},
            {data: 'billing_status', name: 'rr.billing_status', searchable: true},
            {data: 'interviewer', name: 'rr.interviewer', searchable: true},
            {data: 'salary_range', name: 'rr.salary_range', searchable: true},
            {data: 'raised_by', name: 'tr.raised_by', searchable: false},
            {data: 'approved_by', name: 'tr.approved_by', searchable: false},
            {data: 'assigned_date', name: 'assigned_date', searchable: false},
            {data: 'closed_date', name: 'closed_date', searchable: false},
           
        ],
        
        "order":[[2, 'desc']],
		// "aaSorting": [ [6,'desc'], ],

    });
    

    // Add event listener for opening and closing details
    $('#table1 tbody').unbind('click');

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
function ticket_edit_process(rfh_no){
    $('#show_edit_pop').click();
    $('#show_edit_pop_title').html('Edit Position Status - '+ rfh_no);
    $('#ticket_rfh_no').val(rfh_no);
    
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

function show_advanced_filter(){
    
    if ($('#show_filter_div').css('display') == 'none') {
        $("#show_filter_div").css({ "display" :"flex" });
    }
    else{
        $("#show_filter_div").css({ "display" :"none" });

    }
}

$("#af_from_date,#af_to_date,#af_position_title,#af_sub_position_title,#af_critical_position,#af_position_status,#af_assigned_status,#af_salary_range,#af_band,#af_location,#af_business,#af_billing_status,#af_function,#af_teams,#af_division,#af_raisedby,#af_approvedby")
.on('change', function() {
    
    getrecruitmentRequestlist();

    var get_team = $('#af_teams').val();

    if(get_team !=''){

        $.ajax({
            type: "POST",
            url: get_recruiter_team_list_link,
            data: { "team":get_team,},
            success: function (data) {
                if(data.length != 0){
                    var html = '<option value="">Select</option>';
                    for (let index = 0; index < data.length; index++) {
                        html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                    }
                    
                    $('#af_recruiters').html(html);
    
                }
            }
        });
    }

});

$("#af_recruiters").on('change', function() {
    
    getrecruitmentRequestlist();


});
$("#afClearbtn").on('click', function() {
    // $("#af_from_date").val("");
    // $("#af_to_date").val("");
    // $("#af_position_title").val("");
    // $("#af_critical_position").val("");
    // $("#af_position_status").val("");
    // $("#af_assigned_status").val("");
    // $("#af_salary_range").val("");
    // $("#af_band").val("");
    // $("#af_location").val("");
    // $("#af_business").val("");
    // $("#af_billing_status").val("");
    // $("#af_function").val("");
    // $("#af_recruiters").val("");
    // getrecruitmentRequestlist();

    location.reload();

});

function show_stagesof_recruitment_pop(hepl_recruitment_ref_number,rfh_no,assigned_to){
    // alert(hepl_recruitment_ref_number);
    $('#stages_of_recruitment_btn').click();

    $.ajax({
        type: "POST",
        url: get_stagesof_recruitment_rr_link,
        data: { "hepl_recruitment_ref_number":hepl_recruitment_ref_number,"rfh_no":rfh_no,"assigned_to":assigned_to,},
        success: function (data) {
            if(data.length !=0){
                $('#sor_one').text(data.stage_one);
                $('#sor_two').text(data.stage_two);
                $('#sor_three').text(data.stage_three);
                $('#sor_four').text(data.stage_four);
                $('#sor_five').text(data.stage_five);

                $('#fh_hepl_ref_no').text(hepl_recruitment_ref_number);

                if(data.get_original_rfh_date !=''){
                    $('#fh_open_date').text(data.get_original_rfh_date);
                }
                if(data.current_open_date !=''){
                    $('#fh_new_open_date_title').text("Re Open Date - "+data.current_open_date);
                }else{
                    $('#fh_new_open_date_title').text("");
                }
                $('#fh_position_title').text(data.position_title);

                if(data.get_candidate_no_show_details.length !=0){
                    $('#cns_div').html("<strong>Reopen Status - </strong>"+data.get_candidate_no_show_details[0].follow_up_status);

                }else{
                    $('#cns_div').html("");

                }
                // if(data.get_candidate_no_show_details.length !=0){
                //     var cns_html = '<div class="table-responsive">';
                //     cns_html += '<table  class="table table-bordered mb-0">';
                //     cns_html += '<thead>';
                //     cns_html += '<tr>';
                //     cns_html += '<th>S.No.</th>';
                //     cns_html += '<th>Candidate Name</th>';
                //     cns_html += '<th>Status </th>';
                //     cns_html += '<th>Updated On </th>';
                //     cns_html += '</tr>';
                //     cns_html += '</thead>';
                //     cns_html += '<tbody>';

                //     var sno = 1;
                //     for (let index = 0; index < data.get_candidate_no_show_details.length; index++) {
                //         var dateAr = data.get_candidate_no_show_details[0].created_on.split('-');
                //         var newDate = dateAr[2] + '-' + dateAr[1] + '-' + dateAr[0];

                //         cns_html += '<tr>';
                //         cns_html += '<td>'+sno+'</td>';
                //         cns_html += '<td>'+data.get_candidate_no_show_details[0].candidate_name+'</td>';
                //         cns_html += '<td>'+data.get_candidate_no_show_details[0].follow_up_status+'</td>';
                //         cns_html += '<td>'+newDate+'</td>';
                //         cns_html += '</tr>';

                //         sno++;
                //     }
                //     cns_html += '</tbody>';
                //     cns_html += '</table>';
                //     cns_html += '</div>';

                    // $('#cns_div').html("<strong>Reopen Status - </strong>"+data.get_candidate_no_show_details[0].follow_up_status);
                //     $('#fh_new_open_date_title').css("display","block");

                // }else{
                //     $('#fh_new_open_date_title').css("display","none");
                // }
            }
        }
    });
}