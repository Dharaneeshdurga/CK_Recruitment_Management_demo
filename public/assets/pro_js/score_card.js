

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});


// get task details on load
$(document).ready(function() {
    get_score_card();
    var from_date="";
    var to_date="";
    var p_date= $("#today").val();
    get_sc_count_report(from_date,to_date,p_date)
    get_closure_count_report(from_date,to_date,p_date)
    get_average();
   // get_recruiter_list();
});
$("#form_date_close").on('change', function() {
    var from_date=$("#form_date_close").val();
    var to_date="";
    var p_date="";
    get_closure_count_report(from_date,to_date,p_date)
    });
    $("#to_date_close").on('change', function() {
        var from_date=$("#form_date_close").val();
        var to_date=$("#to_date_close").val();
        var p_date="";
        get_closure_count_report(from_date,to_date,p_date)
        });
    //     $("#team_close").on('change', function() {
    //         var team= $(this).val();
    //     $.ajax({
    //         type: "POST",
    //         url: get_recruiter_list_link,
    //         data: {"team":team },
    //         success: function (data) {
    //             if(data.length != 0){
    //                 var html = '';
    //                 for (let index = 0; index < data.length; index++) {
    //                     html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
    //                 }

    //                 $('#recruit_name').html(html);
    //                 get_score_card_filter();

    //             }
    //         }
    //     });
    // })
$("#form_date_sc").on('change', function() {
    var from_date=$("#form_date_sc").val();
    var to_date="";
    var p_date="";
    get_sc_count_report(from_date,to_date,p_date)
    });
    $("#to_date_sc").on('change', function() {
        var from_date=$("#form_date_sc").val();
        var to_date=$("#to_date_sc").val();
        var p_date="";
        get_sc_count_report(from_date,to_date,p_date)
        });


$("#Clearbtn").on('click', function() {
    $("#form_date").val("");
    $("#to_date").val("");
    $("#team").val("");
    $("#recruit_name").val("");
    get_score_card();
});
$("#Clearbtn_sc").on('click', function() {
   // alert("test");
    $("#form_date_sc").val("");
    $("#to_date_sc").val("");

    var from_date="";
    var to_date="";
    var p_date= $("#today").val();
    get_sc_count_report(from_date,to_date,p_date)
});

function score_card_export(){
   // alert("test");
   var fd= $("#form_date").val();
   var td= $("#to_date").val();
    var team = $("#team").val();
   var rn= $("#recruit_name").val();

   var host = "allocation_list/"+rfh_no;
    var link ="scorecard_export/";
   // $("#export_score").attr("href", link);
}
$("#form_date").on('change', function() {
    get_score_card_filter();
    });
    $("#to_date").on('change', function() {
        get_score_card_filter();
        });
$("#recruit_name").on('change', function() {
get_score_card_filter();
});
//function get_recruiter_list(){
    $("#team").on('change', function() {
        var team= $(this).val();
    $.ajax({
        type: "POST",
        url: get_recruiter_list_link,
        data: {"team":team },
        success: function (data) {
            if(data.length != 0){
                var html = '';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                }

                $('#recruit_name').html(html);
                get_score_card_filter();

            }
        }
    });
})

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
function get_date(){
    var date=  $('#today').val();
  return "RECRUITER HOURLY SC  "+date;
}
function get_date_filter(){
    var fd =   $("#form_date").val();
    var td =   $("#to_date").val();
    return "RECRUITER HOURLY SC  From Date:"+fd+" To Date:"+td;
  }
function get_score_card(){
  //var today_date="22-03-2022";
    table_cot = $('#score_card_tb').DataTable({

        dom: 'lBfrtip',
        "buttons": [
            // {
            //     "extend": 'copy',
            //     "text": '<i class="bi bi-clipboard" ></i>  Copy',
            //     "titleAttr": 'Copy',
            //     "exportOptions": {
            //         'columns': ':visible'
            //     },
            //     "action": newexportaction
            // },
            {
                "extend": 'excelHtml5',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "\n");
                        }
                    }
                },
                customize: function ( xlsx ){
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $( 'row c', sheet ).attr( 's', '25' );
                    $('row c[r^="A1"]', sheet).attr('s', '51');
                    $('row c[r^="A2"]', sheet).attr('s', '37');
                    $('row c[r^="B2"]', sheet).attr('s', '37');
                    $('row c[r^="C2"]', sheet).attr('s', '37');
                    $('row c[r^="D2"]', sheet).attr('s', '37');
                    $('row c[r^="E2"]', sheet).attr('s', '37');
                    $('row c[r^="F2"]', sheet).attr('s', '22');
                    $('row c[r^="G2"]', sheet).attr('s', '22');
                    $('row c[r^="H2"]', sheet).attr('s', '22');
                    $('row c[r^="I2"]', sheet).attr('s', '22');
                    $('row c[r^="J2"]', sheet).attr('s', '22');
                    $('row c[r^="K2"]', sheet).attr('s', '22');
                    $('row c[r^="L2"]', sheet).attr('s', '22');
                    $('row c[r^="M2"]', sheet).attr('s', '22');
                    $('row c[r^="N2"]', sheet).attr('s', '17');
                    $('row c[r^="A1"]', sheet).attr('s', '2');

                    $('c[r=A1] t', sheet).text( get_date());
                   // $('c[r=b1] t', sheet).text("test");
                   // $('c[r=O1] t', sheet).text(get_date());

                   // $('c[r=A1] t', sheet).after( 'RECRUITER HOURLY SC');

                    // jQuery selector to add a border
                   // $('row c[r*="10"]', sheet).attr( 's', '26' );
                },
                "action": newexportaction
            },
            // {
            //     "extend": 'csv',
            //     "text": '<i class="bi bi-file-text" ></i>  CSV',
            //     "titleAttr": 'CSV',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "\n");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
            // {
            //     "extend": 'pdf',
            //     'orientation': 'landscape',
            //     'pageSize': 'LEGAL',

            //     "text": '<i class="bi bi-file-break" ></i>  PDF',
            //     "titleAttr": 'PDF',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "\n");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
            // {
            //     "extend": 'print',
            //     "text": '<i class="bi bi-printer"></i>  Print',
            //     "titleAttr": 'Print',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "<br/>");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
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
            //processInfo_tab1(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        rowCallback: function( row, data, index ) {
            $( row ).find('td:eq(0)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(1)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(2)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(3)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(4)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});

            $( row ).find('td:eq(5)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(6)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(7)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(8)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});

            $( row ).find('td:eq(9)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(10)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(11)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(12)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(13)').css({"background":"rgb(145 231 136)","color":"black","font-weight": "bold","border":"1px solid"});


        }  ,
        "columnDefs": [
            {"className": "text-left", "targets": 1},
            {"className": "text-center", "targets": 0},
            {"className": "text-center", "targets": 2},
            {"className": "text-center", "targets": 3},
            {"className": "text-center", "targets": 4},
            {"className": "text-center", "targets": 5},
            {"className": "text-center", "targets": 6},
            {"className": "text-center", "targets": 7},
            {"className": "text-center", "targets": 8},
            {"className": "text-center", "targets": 9},
            {"className": "text-center", "targets": 10},
            {"className": "text-center", "targets": 11},
            {"className": "text-center", "targets": 12},
            {"className": "text-center", "targets": 13},
          ],
        ajax: {
            url: get_score_card_link,
            type: 'POST',
            data: function (d) {
                // d.af_from_date = $('#af_from_date').val();
                // d.af_to_date = $('#af_to_date').val();
                // d.af_position_title = $('#af_position_title').val();
                // d.af_sub_position_title = $('#af_sub_position_title').val();
                // d.af_location = $('#af_location').val();
                // d.af_business = $('#af_business').val();
                // d.af_teams = $('#af_teams').val();
                // d.af_raisedby = $('#af_raisedby').val();
                // d.af_interviewer = $('#af_interviewer').val();
                // d.af_billable = $('#af_billable').val();
                // d.af_function = $('#af_function').val();
                // d.af_division = $('#af_division').val();

            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Recruiter');
            $( row ).find('td:eq(2)').attr('data-label', 'Position');
            $( row ).find('td:eq(3)').attr('data-label', 'Interviews');
            $( row ).find('td:eq(4)').attr('data-label', 'Offers');
            $( row ).find('td:eq(5)').attr('data-label', '10am');
            $( row ).find('td:eq(6)').attr('data-label', '11am');
            $( row ).find('td:eq(7)').attr('data-label', '12pm');
            $( row ).find('td:eq(8)').attr('data-label', '1pm');
          //  $( row ).find('td:eq(9)').attr('data-label', '2pm');
            $( row ).find('td:eq(10)').attr('data-label', '3pm');
            $( row ).find('td:eq(11)').attr('data-label', '4pm');
            $( row ).find('td:eq(12)').attr('data-label', '5pm');
            $( row ).find('td:eq(14)').attr('data-label', 'Total');


        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'recruiter', name: 'recruiter'    },
            {   data: 'position', name: 'position'    },
            {   data: 'interviews', name: 'interviews'    },
            {   data: 'offers', name: 'offers'    },
            {   data: '10am', name: '10am'    },
            {   data: '11am', name: '11am'    },
            {   data: '12pm', name: '12pm'    },
            {   data: '1pm', name: '1pm'    },
         //   {   data: '2pm', name: '2pm'    },
            {   data: '3pm', name: '3pm'    },
            {   data: '4pm', name: '4pm'    },
            {   data: '5pm', name: '5pm'    },
            {   data: '6pm', name: '6pm'    },
            {   data: 'total', name: 'total' },

        ],
    });
}
function get_score_card_filter(){
var form_date = $('#form_date').val();
var to_date = $('#to_date').val();
var recruit = $('#recruit_name').val();
var team = $('#team').val();
//alert(team);
    table_cot = $('#score_card_tb').DataTable({

        dom: 'lBfrtip',
        "buttons": [
            // {
            //     "extend": 'copy',
            //     "text": '<i class="bi bi-clipboard" ></i>  Copy',
            //     "titleAttr": 'Copy',
            //     "exportOptions": {
            //         'columns': ':visible'
            //     },
            //     "action": newexportaction
            // },
            {
                "extend": 'excel',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                            return data.toString().replace(/<br\s*\/?>/ig, "\n");
                        }
                    }
                },
                customize: function ( xlsx ){
                    var sheet = xlsx.xl.worksheets['sheet1.xml'];
                    $( 'row c', sheet ).attr( 's', '25' );
                    $('row c[r^="A1"]', sheet).attr('s', '51');
                    $('row c[r^="A2"]', sheet).attr('s', '37');
                    $('row c[r^="B2"]', sheet).attr('s', '37');
                    $('row c[r^="C2"]', sheet).attr('s', '37');
                    $('row c[r^="D2"]', sheet).attr('s', '37');
                    $('row c[r^="E2"]', sheet).attr('s', '37');
                    $('row c[r^="F2"]', sheet).attr('s', '22');
                    $('row c[r^="G2"]', sheet).attr('s', '22');
                    $('row c[r^="H2"]', sheet).attr('s', '22');
                    $('row c[r^="I2"]', sheet).attr('s', '22');
                    $('row c[r^="J2"]', sheet).attr('s', '22');
                    $('row c[r^="K2"]', sheet).attr('s', '22');
                    $('row c[r^="L2"]', sheet).attr('s', '22');
                    $('row c[r^="M2"]', sheet).attr('s', '22');
                    $('row c[r^="N2"]', sheet).attr('s', '17');
                    $('row c[r^="A1"]', sheet).attr('s', '2');

                    $('c[r=A1] t', sheet).text( get_date_filter());
                   // $('c[r=b1] t', sheet).text("test");
                   // $('c[r=O1] t', sheet).text(get_date());

                   // $('c[r=A1] t', sheet).after( 'RECRUITER HOURLY SC');

                    // jQuery selector to add a border
                   // $('row c[r*="10"]', sheet).attr( 's', '26' );
                },
                "action": newexportaction
            },
            // {
            //     "extend": 'csv',
            //     "text": '<i class="bi bi-file-text" ></i>  CSV',
            //     "titleAttr": 'CSV',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "\n");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
            // {
            //     "extend": 'pdf',
            //     'orientation': 'landscape',
            //     'pageSize': 'LEGAL',

            //     "text": '<i class="bi bi-file-break" ></i>  PDF',
            //     "titleAttr": 'PDF',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "\n");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
            // {
            //     "extend": 'print',
            //     "text": '<i class="bi bi-printer"></i>  Print',
            //     "titleAttr": 'Print',
            //     "exportOptions": {
            //         'columns': ':visible',
            //         'format': {
            //             body: function ( data, row, column, node ) {
            //                 return data.toString().replace(/<br\s*\/?>/ig, "<br/>");
            //             }
            //         }
            //     },
            //     "action": newexportaction
            // },
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
            //processInfo_tab1(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        rowCallback: function( row, data, index ) {
            $( row ).find('td:eq(0)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(1)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(2)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(3)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(4)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});

            $( row ).find('td:eq(5)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(6)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(7)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(8)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});

            $( row ).find('td:eq(9)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(10)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(11)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(12)').css({"background":"rgb(195 197 241)","color":"black","font-weight": "bold","border":"1px solid"});
            $( row ).find('td:eq(13)').css({"background":"rgb(145 231 136)","color":"black","font-weight": "bold","border":"1px solid"});
        }  ,
        "columnDefs": [
            {"className": "text-left", "targets": 1},
            {"className": "text-center", "targets": 0},
            {"className": "text-center", "targets": 2},
            {"className": "text-center", "targets": 3},
            {"className": "text-center", "targets": 4},
            {"className": "text-center", "targets": 5},
            {"className": "text-center", "targets": 6},
            {"className": "text-center", "targets": 7},
            {"className": "text-center", "targets": 8},
            {"className": "text-center", "targets": 9},
            {"className": "text-center", "targets": 10},
            {"className": "text-center", "targets": 11},
            {"className": "text-center", "targets": 12},
            {"className": "text-center", "targets": 13},
          ],
        ajax: {
            url: get_score_card_filter_link,
            type: 'POST',
            data: function (d) {
                 d.from_date = form_date;
                 d.to_date = to_date;
                 d.recruit = recruit;
                 d.team = team;


            }
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(0)').attr('data-label', 'Sno');
            $( row ).find('td:eq(1)').attr('data-label', 'Recruiter');
            $( row ).find('td:eq(2)').attr('data-label', 'Position');
            $( row ).find('td:eq(3)').attr('data-label', 'Interviews');
            $( row ).find('td:eq(4)').attr('data-label', 'Offers');
            $( row ).find('td:eq(5)').attr('data-label', '10am');
            $( row ).find('td:eq(6)').attr('data-label', '11am');
            $( row ).find('td:eq(7)').attr('data-label', '12pm');
            $( row ).find('td:eq(8)').attr('data-label', '1pm');
          //  $( row ).find('td:eq(9)').attr('data-label', '2pm');
            $( row ).find('td:eq(10)').attr('data-label', '3pm');
            $( row ).find('td:eq(11)').attr('data-label', '4pm');
            $( row ).find('td:eq(12)').attr('data-label', '5pm');
            $( row ).find('td:eq(14)').attr('data-label', 'Total');


        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'recruiter', name: 'recruiter'    },
            {   data: 'position', name: 'position'    },
            {   data: 'interviews', name: 'interviews'    },
            {   data: 'offers', name: 'offers'    },
            {   data: '10am', name: '10am'    },
            {   data: '11am', name: '11am'    },
            {   data: '12pm', name: '12pm'    },
            {   data: '1pm', name: '1pm'    },
           // {   data: '2pm', name: '2pm'    },
            {   data: '3pm', name: '3pm'    },
            {   data: '4pm', name: '4pm'    },
            {   data: '5pm', name: '5pm'    },
            {   data: '6pm', name: '6pm'    },
            {   data: 'total', name: 'total' },

        ],
    });
}
function get_sc_count_report(from_date,to_date,p_date){

    $.ajax({
        url:get_sc_count_filter_link,
        method: "POST",
        data:{
            "from_date":from_date,
            "to_date":to_date,
            "p_date":p_date,
        },
        dataType: "JSON",
        success: function(data) {
            $('#total_open').html(data.total_open);
            $('#avg_open').html(data.get_avg_open);
            $('#total_op').html(data.total_op);
            $('#total_billable').html(data.total_billable);
            $('#total_non_billable').html(data.total_non_billable);
           $('#total_recruit').html(data.get_recruitment);
           $('#avg_recruit').html(data.avg_recruitment_per_recruiter);
           $('#max_count').html(data.max_count);
           $('#min_count').html(data.min_count);



        }

    });
}
$("#Clearbtn_close").on('click', function() {
    // alert("test");
     $("#form_date_close").val("");
     $("#to_date_close").val("");

     var from_date="";
     var to_date="";
     var p_date= $("#today").val();
     get_closure_count_report(from_date,to_date,p_date)
 });
function get_closure_count_report(from_date,to_date,p_date){
//alert(p_date);
    $.ajax({
        url:get_closure_details_link,
        method: "POST",
        data:{
            "from_date":from_date,
            "to_date":to_date,
            "p_date":p_date,
            "column":"",
            "order":"asc"
        },
        dataType: "JSON",
        success: function(data) {
          var td_html="";
          for(index = 0; index < data.recruiter.length; index++){
        td_html +="<tr>";
        td_html +="<td style='background:"+data.recruit_color[index]+";'>"+data.recruiter[index]+"</td>";
        td_html +="<td style='background:"+data.recruit_color[index]+";'>"+data.billable_dt[index]+"</td>";
        td_html +="<td style='background:"+data.recruit_color[index]+";'>"+data.non_billable_dt[index]+"</td>";
        td_html +="<td style='background:"+data.recruit_color[index]+";'>"+data.closed_dt[index]+"</td>";

        td_html +="</tr>";

          }
          td_html +="<tr style='font-weight:bold;font-size:18px;color:black';>";
          td_html +="<td style='background:#eb887d;'>TOTAL</td>";
          td_html +="<td style='background:#eb887d;'>"+data.total_billable+"</td>";
          td_html +="<td style='background:#eb887d;'>"+data.total_non_billable+"</td>";
          td_html +="<td style='background:#db6a5e;'>"+data.total_closed+"</td>";

          td_html +="</tr>";
          $('#closure_table').html(td_html);


        }

    });
}

function sort_table_closure(column,name,id){
    var sort = $("#sort").val();
    var from_date= $("#form_date_close").val();
    var to_date= $("#to_date_close").val();
    var p_date= $("#today").val();

    $.ajax({
        url:get_closure_details_link,
        method: "POST",
        data:{
            "from_date":from_date,
            "to_date":to_date,
            "p_date":p_date,
            "column":"",
            "order":"asc"
        },
        dataType: "JSON",
        success: function(data) {
            var arr =[];
           // arr += '[';
            for(index = 0; index < data.recruiter.length; index++){

                arr.push({"recruiter":data.recruiter[index],"billable_dt":data.billable_dt[index],"non_billable_dt":data.non_billable_dt[index],"closed_dt":data.closed_dt[index]});
//arr.push({"name":"test","age":"test"});
            }
           // arr += ']';
            var myarray = arr;
//  var testarray = [{"name":"dsfnd","age":"jfdjn"},{"name":"sdfds","age":"dsfd"},]
//         console.log(myarray,testarray);
                var sym = name.substring(0, name.length -1)
            if(sort == "asc"){
                $("#sort").val("desc")
                sym += '&#9660';
                myarray = myarray.sort((a,b) => a[column] < b[column] ? 1 : -1)
              }else{
                $("#sort").val("asc");
                sym += '&#9650';
                myarray = myarray.sort((a,b) => a[column] > b[column] ? 1 : -1)
              }
              $('#'+id).html(sym);
             // console.log(myarray);
              var td_html="";
          for(index = 0; index < myarray.length; index++){
        td_html +="<tr>";
        td_html +="<td>"+myarray[index].recruiter+"</td>";
        td_html +="<td>"+myarray[index].billable_dt+"</td>";
        td_html +="<td>"+myarray[index].non_billable_dt+"</td>";
        td_html +="<td>"+myarray[index].closed_dt+"</td>";

        td_html +="</tr>";

          }
          td_html +="<tr style='font-weight:bold;font-size:18px;color:black';>";
          td_html +="<td style='background:#eb887d;'>TOTAL</td>";
          td_html +="<td style='background:#eb887d;'>"+data.total_billable+"</td>";
          td_html +="<td style='background:#eb887d;'>"+data.total_non_billable+"</td>";
          td_html +="<td style='background:#db6a5e;'>"+data.total_closed+"</td>";

          td_html +="</tr>";
          $('#closure_table').html(td_html);
              //console.log(sort);
            }


    });

}







$("#form_date_avg").on('change', function() {
    get_average();
    });
    $("#to_date_avg").on('change', function() {
        get_average();
        });
        $("#Clearbtn_avg").on('click', function() {
            // alert("test");
             $("#form_date_avg").val("");
             $("#to_date_avg").val("");
             get_average();

         });
function get_average(){
    var today_date= $("#today").val();
    var from_date= $('#form_date_avg').val();
    var to_date= $('#to_date_avg').val();

    $.ajax({
        url:get_average_link,
        method: "POST",
        data:{
            "today_date":today_date,
            "from_date":from_date,
            "to_date":to_date,

        },
        dataType: "JSON",
        success: function(data) {

          $('#avg_count').html(data);


        }

    });
}
