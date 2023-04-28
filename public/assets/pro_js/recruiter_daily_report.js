

$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

// get task details on load
$(document).ready(function() {

    get_recruiter_daily_report();
    //get_recruiter_list();
});

$("#Clearbtn1").on('click', function() {
    $("#form_date1").val("");
    $("#to_date1").val("");
    $("#team1").val("");
    $("#recruit_name1").val("");
    get_recruiter_daily_report();
});
$("#Clear_age").on('click', function() {
    $("#team_age").val("");
    get_ageing_report();
});
$("#team_age").on('change', function() {
    get_ageing_report();

})
$("#form_date1").on('change', function() {
    get_re_daily_filter();

})
$("#to_date1").on('change', function() {
    get_re_daily_filter();

})
$("#recruit_name1").on('change', function() {
    get_re_daily_filter();

})
$("#team1").on('change', function() {
    var team= $(this).val();
    $.ajax({
        type: "POST",
        url: get_recruiter_list_link,
        data: {"team":team },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].empID+'">'+data[index].name+'</option>';
                }

                $('#recruit_name1').html(html);
                get_re_daily_filter();
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
function get_date_filter(){
  var fd =   $("#form_date1").val();
  var td =   $("#to_date1").val();
  return "RECRUITER DAILY REPORT  From Date:"+fd+" To Date:"+td;
}
function get_date(){
    var date=  $('#today').val();
  return "RECRUITER DAILY REPORT  "+date;
}
function get_recruiter_daily_report(){

    table_cot = $('#r_daily_report_tb').DataTable({

       dom: 'lBfrtip',
         "buttons": [
        //     {
        //         "extend": 'copy',
        //         "text": '<i class="bi bi-clipboard" ></i>  Copy',
        //         "titleAttr": 'Copy',
        //         "exportOptions": {
        //             'columns': ':visible'
        //         },
        //         "action": newexportaction
        //     },
            {
                "extend": 'excelHtml5',
                "text": '<i class="bi bi-file-earmark-spreadsheet" ></i>  Excel',
                "titleAttr": 'Excel',
                "exportOptions": {
                    'columns': ':visible',
                    'format': {
                        body: function ( data, row, column, node ) {
                           // return data.toString().replace(/<p\s*\/?>/ig, "\n");
                          // data = data.toString().replace(/<.*?>/g, "");
                           // return $.trim(data);


                              data = data.toString().replace(/"/g, "'");

                          // replace p with br
                          data = data.toString().replace(/<p[^>]*>/g, '').replace(/<\/p>/g, '<br>');

                          // replace div with br
                          data = data.toString().replace(/<div[^>]*>/g, '').replace(/<\/div>/g, '<br>');

                          data = remove_tags(data);



                          //split at each new line
                          splitData = data.split('<br>');

                          //remove empty string
                          splitData = splitData.filter(function(v) {
                            return v !== ''
                          });

                          data = '';
                          for (i = 0; i < splitData.length; i++) {
                            //add escaped double quotes around each line
                            data += '\"' + splitData[i] + '\"';
                            //if its not the last line add CHAR(13)
                            if (i + 1 < splitData.length) {
                              data += ', CHAR(10), ';
                            }
                          }

                          //Add concat function
                          data = 'CONCATENATE(' + data + ')';
                          return data;
                        }
                    }
                },
                customize: function ( xlsx ){
                    	       var sSh = xlsx.xl['styles.xml'];

                    var styleSheet = sSh.childNodes[0];

                    cellXfs = styleSheet.childNodes[5];

                    // Using this instead of "" (required for Excel 2007+, not for 2003)
                    var ns = "http://schemas.openxmlformats.org/spreadsheetml/2006/main";

                    // Create a custom style
                    var lastStyleNum = $('cellXfs xf', sSh).length - 1;
                    var wrappedTopIndex = lastStyleNum + 1;
                    var newStyle = document.createElementNS(ns, "xf");
                    // Customize style
                    newStyle.setAttribute("numFmtId", 0);
                    newStyle.setAttribute("fontId", 0);
                    newStyle.setAttribute("fillId", 0);
                    newStyle.setAttribute("borderId", 0);
                    newStyle.setAttribute("applyFont", 1);
                    newStyle.setAttribute("applyFill", 1);
                    newStyle.setAttribute("applyBorder", 1);
                    newStyle.setAttribute("color", "FF006600");

                    newStyle.setAttribute("xfId", 0);
                    // Alignment (optional)
                    var align = document.createElementNS(ns, "alignment");
                    align.setAttribute("vertical", "top");

                    align.setAttribute("wrapText", 1);
                    newStyle.appendChild(align);




                    // Append the style next to the other ones
                    cellXfs.appendChild(newStyle);




               // xlsx.xl['styles.xml'] = $.parseXML(new_style);
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('c[r=A1] t', sheet).text(get_date());
                   $( 'row c', sheet ).attr( 's', wrappedTopIndex );
				//	$('row:nth-child(11) c:nth-child(4)', sheet).attr('s','10');

                    var firstExcelRow = 3;

                    table_cot.rows({
                      order: 'applied',
                      search: 'applied'
                    }).every(function(rowIdx, tableLoop, rowLoop) {

                      var node = this.node();

                      var num_colonne = $(node).find("td").length;

                      // the cell with biggest number of line inside it determine the height of entire row
                      var maxCountLinesRow = 1;


                      for (var indexCol = 1; indexCol <= num_colonne; indexCol++) {

                        var letterExcel = columnToLetter(indexCol);

                        $('c[r=' + letterExcel + (firstExcelRow + rowLoop) + ']', sheet).each(function(e) {

                           // $(this).attr( 's', '22' );

                          // how many lines are present in this cell?
                          var countLines = ($('is t', this).text().match(/\"/g) || []).length / 2;

                          if (countLines > maxCountLinesRow) {
                            maxCountLinesRow = countLines;
                          }

                          if ($('is t', this).text()) {
                            //wrap text top vertical top
                            $(this).attr('s', wrappedTopIndex);

                            //change the type to `str` which is a formula
                            $(this).attr('t', 'str');
                            //append the concat formula
                            $(this).append('<f>' + $('is t', this).text() + '</f>');
                            //remove the inlineStr
                            $('is', this).remove();
                          }

                        });

                        $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('ht', maxCountLinesRow * 26);
                       $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('customHeight', 1);
                      // $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('s',newstyle);
                      }

                    });







                },
                "action": newexportaction
            },
            {
              extend: "pdfHtml5",
              "exportOptions": {
                'columns': ':visible',
                'format': {
                    body: function ( data, row, column, node ) {
                      data = data.toString().replace(/"/g, "'");

                      // replace p with br
                      data = data.toString().replace(/<p[^>]*>/g, '').replace(/<\/p>/g, "<br\>");

                      // replace div with br
                   //   data = data.toString().replace(/<div[^>]*>/g, '').replace(/<\/div>/g, '/n');
                   return data.toString().replace(/<br\s*\/?>/ig, "\n");
                    }
                }
            },
              orientation: 'landscape',
              //pageSize: 'TABLOID',
                customize: function(doc) {
                  var cols = [];
   cols[4] = {alignment: 'center'};
   cols[5] = {alignment: 'center'};
                  for (var row = 0; row < doc.content[1].table.headerRows; row++) {
                    var header = doc.content[1].table.body[row];
                    for (var col = 0; col < header.length; col++) {
                      header[col].fillColor = '#0dcaf0';
                    }
                  }
                  var tblBody = doc.content[1].table.body;
                  doc.content[1].layout = {
                    // hLineWidth: function(i, node) {
                    //     return (i === 0 || i === node.table.body.length) ? 2 : 1;},
                    // vLineWidth: function(i, node) {
                    //     return (i === 0 || i === node.table.widths.length) ? 2 : 1;},
                    // hLineColor: function(i, node) {
                    //     return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';},
                    // vLineColor: function(i, node) {
                    //     return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';},
                      //  fillColor: function(i,node){
                      //   delete tblBody.style;
                      //    return (i === 0 || i === node.table.body.length) ? '#ff99e6' : '#ccff99';},

                      };
                      var colorCode = "1234567890abcdef";
                      var color = "";
                      $('#r_daily_report_tb').find('tr').each(function (ix, row) {
                        var index = ix;
                        var rowElt = row;
                        //delete rowElt.style;
                      //  $(row).fillColor = '#FFF9C4';
                        $(row).find('td').each(function (ind, elt) {
                          tblBody[index][4].alignment = 'center';
                          tblBody[index][5].alignment = 'center';
                           // if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {


                           // color = colorCode.charAt(Math.floor(Math.random() * colorCode.length));
if(index == 1){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#f2ccff';

}
if(index == 2){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffcccc';
}
if(index == 3){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccffcc';
}
if(index == 4){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#cce0ff';
}
if(index == 5){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffeecc';
}
if(index == 6){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3ff';
}
if(index == 7){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3b3';
}
if(index == 8){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#99ffd6';
}
if(index == 9){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccf2ff';
}
if(index == 10){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffddcc';
}
if(index == 11){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffff4d';
}
if(index == 12){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#d9ffb3';
}
if(index == 13){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3ff';
}
if(index == 14){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccf2ff';
}

                            // }
                            // else
                            // {
                            //     if (tblBody[index][2].text == '') {
                            //         delete tblBody[index][ind].style;
                            //         tblBody[index][ind].fillColor = '#FFFDE7';
                            //     }
                            // }
                        });
                    });
                } ,
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
            //processInfo_tab1(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        rowCallback: function( row, data, index ) {
            $(row).css({"background":data['color_code'],"color":"black","font-weight": "bold","border":"1px solid"});

        }  ,
        "columnDefs": [
            {"className": "text-left", "targets": 1},
            {"className": "text-center", "targets": 0},
            {"className": "text-left","width":"300", "targets": 2},
            {"className": "text-center","width":"200", "targets": 3},
            {"className": "text-center", "targets": 4},
            {"className": "text-center", "targets": 5},
            {"className": "text-left", "width":"400","targets": 6},
          ],
        ajax: {
            url: get_recruiter_daily_report_link,
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
            $( row ).find('td:eq(2)').attr('data-label', 'Position Working');
            $( row ).find('td:eq(3)').attr('data-label', 'RFH NO');
            $( row ).find('td:eq(4)').attr('data-label', 'CV per position');
            $( row ).find('td:eq(5)').attr('data-label', 'Total Cvs');
            $( row ).find('td:eq(6)').attr('data-label', 'RFH No. for Offers Released');



        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'recruiter', name: 'recruiter'    },
            {   data: 'position', name: 'position'    },
            {   data: 'rfh_no', name: 'rfh_no'    },
            {   data: 'cv_per_position', name: 'cv_per_position'    },
            {   data: 'total_cvs', name: 'total_cvs'    },
            {   data: 'offer_release_status', name: 'offer_release_status'    },


        ],
    });
}




function columnToLetter(column) {
    var temp, letter = '';
    while (column > 0) {
      temp = (column - 1) % 26;
      letter = String.fromCharCode(temp + 65) + letter;
      column = (column - temp - 1) / 26;
    }
    return letter;
  }

  function remove_tags(html) {
    html = html.replace(/<br>/g, "$br$");
    html = html.replace(/(?:\r\n|\r|\n)/g, '$n$');
    var tmp = document.createElement("DIV");
    tmp.innerHTML = html;
    html = tmp.textContent || tmp.innerText;

    html = html.replace(/\$br\$/g, "<br>");
    html = html.replace(/\$n\$/g, "<br>");

    return html;
  }







function get_re_daily_filter(){
var form_date = $('#form_date1').val();
var to_date = $('#to_date1').val();
var recruit = $('#recruit_name1').val();
var team = $('#team1').val();
    table_cot = $('#r_daily_report_tb').DataTable({

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
                    //'columns': ':visible',
                    format: {
                        body: function ( data, row, column, node ) {
                                          // return data.toString().replace(/<br\s*\/?>/ig, "  ");

                         // need to change double quotes to single
                          //  return data.toString().replace(/<br\s*\/?>/ig, "\n");
                          data = data.toString().replace(/"/g, "'");

                          // replace p with br
                          data = data.toString().replace(/<p[^>]*>/g, '').replace(/<\/p>/g, '<br>');

                          // replace div with br
                          data = data.toString().replace(/<div[^>]*>/g, '').replace(/<\/div>/g, '<br>');

                          data = remove_tags(data);



                          //split at each new line
                          splitData = data.split('<br>');

                          //remove empty string
                          splitData = splitData.filter(function(v) {
                            return v !== ''
                          });

                          data = '';
                          for (i = 0; i < splitData.length; i++) {
                            //add escaped double quotes around each line
                            data += '\"' + splitData[i] + '\"';
                            //if its not the last line add CHAR(13)
                            if (i + 1 < splitData.length) {
                              data += ', CHAR(10), ';
                            }
                          }

                          //Add concat function
                          data = 'CONCATENATE(' + data + ')';
                          return data;

                         }
                      }
                    },

                customize: function ( xlsx ){

                    var sSh = xlsx.xl['styles.xml'];

                    var styleSheet = sSh.childNodes[0];

                    cellXfs = styleSheet.childNodes[5];

                    // Using this instead of "" (required for Excel 2007+, not for 2003)
                    var ns = "http://schemas.openxmlformats.org/spreadsheetml/2006/main";

                    // Create a custom style
                    var lastStyleNum = $('cellXfs xf', sSh).length - 1;
                    var wrappedTopIndex = lastStyleNum + 1;
                    var newStyle = document.createElementNS(ns, "xf");
                    // Customize style
                    newStyle.setAttribute("numFmtId", 0);
                    newStyle.setAttribute("fontId", 0);
                    newStyle.setAttribute("fillId", 0);
                    newStyle.setAttribute("borderId", 0);
                    newStyle.setAttribute("applyFont", 1);
                    newStyle.setAttribute("applyFill", 1);
                    newStyle.setAttribute("applyBorder", 1);
                    newStyle.setAttribute("color", "FF006600");

                    newStyle.setAttribute("xfId", 0);
                    // Alignment (optional)
                    var align = document.createElementNS(ns, "alignment");
                    align.setAttribute("vertical", "top");

                    align.setAttribute("wrapText", 1);
                    newStyle.appendChild(align);




                    // Append the style next to the other ones
                    cellXfs.appendChild(newStyle);




               // xlsx.xl['styles.xml'] = $.parseXML(new_style);
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('c[r=A1] t', sheet).text(get_date_filter());
                   $( 'row c', sheet ).attr( 's', wrappedTopIndex );
				//	$('row:nth-child(11) c:nth-child(4)', sheet).attr('s','10');

                    var firstExcelRow = 3;

                    table_cot.rows({
                      order: 'applied',
                      search: 'applied'
                    }).every(function(rowIdx, tableLoop, rowLoop) {

                      var node = this.node();

                      var num_colonne = $(node).find("td").length;

                      // the cell with biggest number of line inside it determine the height of entire row
                      var maxCountLinesRow = 1;


                      for (var indexCol = 1; indexCol <= num_colonne; indexCol++) {

                        var letterExcel = columnToLetter(indexCol);

                        $('c[r=' + letterExcel + (firstExcelRow + rowLoop) + ']', sheet).each(function(e) {

                           // $(this).attr( 's', '22' );

                          // how many lines are present in this cell?
                          var countLines = ($('is t', this).text().match(/\"/g) || []).length / 2;

                          if (countLines > maxCountLinesRow) {
                            maxCountLinesRow = countLines;
                          }

                          if ($('is t', this).text()) {
                            //wrap text top vertical top
                            $(this).attr('s', wrappedTopIndex);

                            //change the type to `str` which is a formula
                            $(this).attr('t', 'str');
                            //append the concat formula
                            $(this).append('<f>' + $('is t', this).text() + '</f>');
                            //remove the inlineStr
                            $('is', this).remove();
                          }

                        });

                        $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('ht', maxCountLinesRow * 26);
                       $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('customHeight', 1);
                      // $('row:nth-child(' + (firstExcelRow + rowLoop) + ')', sheet).attr('s',newstyle);
                      }

                    });


                },
                "action": newexportaction
            },
            {
              extend: "pdfHtml5",
              "exportOptions": {
                'columns': ':visible',
                'format': {
                    body: function ( data, row, column, node ) {
                      data = data.toString().replace(/"/g, "'");

                      // replace p with br
                      data = data.toString().replace(/<p[^>]*>/g, '').replace(/<\/p>/g, "<br\>");

                      // replace div with br
                   //   data = data.toString().replace(/<div[^>]*>/g, '').replace(/<\/div>/g, '/n');
                   return data.toString().replace(/<br\s*\/?>/ig, "\n");
                    }
                }
            },
              orientation: 'landscape',
              //pageSize: 'TABLOID',
                customize: function(doc) {
                  for (var row = 0; row < doc.content[1].table.headerRows; row++) {
                    var header = doc.content[1].table.body[row];
                    for (var col = 0; col < header.length; col++) {
                      header[col].fillColor = '#0dcaf0';
                    }
                  }
                  var tblBody = doc.content[1].table.body;
                  doc.content[1].layout = {
                    // hLineWidth: function(i, node) {
                    //     return (i === 0 || i === node.table.body.length) ? 2 : 1;},
                    // vLineWidth: function(i, node) {
                    //     return (i === 0 || i === node.table.widths.length) ? 2 : 1;},
                    // hLineColor: function(i, node) {
                    //     return (i === 0 || i === node.table.body.length) ? 'black' : 'gray';},
                    // vLineColor: function(i, node) {
                    //     return (i === 0 || i === node.table.widths.length) ? 'black' : 'gray';},
                      //  fillColor: function(i,node){
                      //   delete tblBody.style;
                      //    return (i === 0 || i === node.table.body.length) ? '#ff99e6' : '#ccff99';},

                      };
                      var colorCode = "1234567890abcdef";
                      var color = "";
                      $('#r_daily_report_tb').find('tr').each(function (ix, row) {
                        var index = ix;
                        var rowElt = row;
                        //delete rowElt.style;
                      //  $(row).fillColor = '#FFF9C4';
                        $(row).find('td').each(function (ind, elt) {
                          tblBody[index][4].alignment = 'center';
                          tblBody[index][5].alignment = 'center';
                           // if (tblBody[index][1].text == '' && tblBody[index][2].text == '') {


                           // color = colorCode.charAt(Math.floor(Math.random() * colorCode.length));
if(index == 1){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#f2ccff';

}
if(index == 2){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffcccc';
}
if(index == 3){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccffcc';
}
if(index == 4){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#cce0ff';
}
if(index == 5){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffeecc';
}
if(index == 6){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3ff';
}
if(index == 7){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3b3';
}
if(index == 8){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#99ffd6';
}
if(index == 9){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccf2ff';
}
if(index == 10){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffddcc';
}
if(index == 11){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffff4d';
}
if(index == 12){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#d9ffb3';
}
if(index == 13){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ffb3ff';
}
if(index == 14){
  delete tblBody[index][ind].style;
  tblBody[index][ind].fillColor = '#ccf2ff';
}

                            // }
                            // else
                            // {
                            //     if (tblBody[index][2].text == '') {
                            //         delete tblBody[index][ind].style;
                            //         tblBody[index][ind].fillColor = '#FFFDE7';
                            //     }
                            // }
                        });
                    });
                } ,
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
            //processInfo_tab1(this.api().page.info());

        },
        // aoColumnDefs: [
        //     { 'visible': false, 'targets': [3] }
        // ],
        rowCallback: function( row, data, index ) {
         $( row ).css({"background":data['color_code'],"color":"black","font-weight": "bold","border":"1px solid"});

           // $('td', nrow).css('background-color', 'Red');
          // $( row ).find('td:eq(3)').css({"background":"rgb(225 160 153)","color":"black","font-weight": "bold","border":"1px solid"});
        }  ,
        "columnDefs": [
            {"className": "text-left", "targets": 1},
            {"className": "text-center", "targets": 0},
            {"className": "text-left", "width":"300","targets": 2},
            {"className": "text-center","width":"200" ,"targets": 3},
            {"className": "text-center", "targets": 4},
            {"className": "text-center", "targets": 5},
            {"className": "text-left","width":"400", "targets": 6},

          ],
        ajax: {
            url: get_recruiter_daily_report_filter_link,
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
            $( row ).find('td:eq(2)').attr('data-label', 'Position Working');
            $( row ).find('td:eq(3)').attr('data-label', 'RFH NO');
            $( row ).find('td:eq(4)').attr('data-label', 'CV per position');
            $( row ).find('td:eq(5)').attr('data-label', 'Total Cvs');
            $( row ).find('td:eq(6)').attr('data-label', 'RFH No. for Offers Released');



        },
        columns: [
            {   data: 'DT_RowIndex', name: 'DT_RowIndex'    },
            {   data: 'recruiter', name: 'recruiter'    },
            {   data: 'position', name: 'position'
            // render:function (data, type, full, meta) {
            //    // console.log(full);
            //     return '<span>'+data+' </span>';
            // }
        },
            {   data: 'rfh_no', name: 'rfh_no'    },
            {   data: 'cv_per_position', name: 'cv_per_position'    },
            {   data: 'total_cvs', name: 'total_cvs'    },
            {   data: 'offer_release_status', name: 'offer_release_status'    },


        ],
    });
}
$("#ageing-report-tab").on('click', function() {
    get_ageing_report();
});
function get_ageing_report(){
    var team = $('#team_age').val();
 //  alert(team);
    $('#pro').show();
    $('#age_tb').hide();
    $.ajax({
        type: "POST",
        url: get_ageing_report_link,
        data: { "team":team},
        success: function (data) {
            $('#pro').hide();
            $('#age_tb').show();
                    var inital_age = data['position_ageing'][0];
                var length = data['position_ageing'].length;
                var last = length - 1;
                var final_age = data['position_ageing'][last];
                   // var count = (input, arr) => arr.filter(x => x === input).length;
                    var html="";
                   var th="<th style='background:#FFC000; text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;'>Position Ageing</th>";
                  // var th="";16
                    html +='<th style="background:#F8CBAD; text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;">Billable</th>';
                    var nb = '<th style="background:#F8CBAD; text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;">Non Billable</th>';
                    var val = "";

                 var z=0;
               //  var i =0;
                    while(z <= 60){
                      //  console.log(z);
                     //   th +='<tr>';
                        if(z == 0){
                            y = z;

                        }
                        else{
                           y= z+1;
                        }
                        if(y >= 0 && (y+9) <= 29){
                            var color = '#92D050'; //green
                        }
                        else if(y >= 30 && (y+9) <= 59){
                            var color = '#ffff00c4'; //yellow
                        }
                        else{
                            var color = '#ff0c00bf'; //red
                        }
                      if((z+9) < 60){
                            th +='<th style="background:'+color+'; text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid; text-align: center;">'+(y)+'-'+(y+9)+'</th>';
                      }
                       else{
                        th +='<th style="background:#ff0c00bf; text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid; text-align: center;">60+</th>';

                        th +='<th style="background:#8000807a; text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid; text-align: center;">Total</th>';
                       }
                       // billable ageing
                 var c =0;
                 var ex =0;
                for (let index = 0; index <= data['position_ageing'].length; index++) {
                     val = data['position_ageing'][index];
                     if( (val >= y ) && (val <= (y+9))){
                        c= parseInt(c)+parseInt(1);
                     }

                      if(val >= 60) {
                        ex= parseInt(ex)+parseInt(1);
                     }


                   }
                //   if(y == 50 && (z+7) == 56 ){
                    if((y+9) >= 60){
                    html +='<th style="text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid;text-align: center;">'+(ex)+'</th>';
                     html +='<th  style="background:#F8CBAD;text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;text-align: center;">'+data['position_ageing'].length+'</th>';

                }
                  // else if(y == 57){
                  //  html +='<th  style="background:#F8CBAD;text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;text-align: center;">'+data['position_ageing'].length+'</th>';
                   //}
                   else {
                    html +='<th  style="text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid;text-align: center;">'+(c)+'</th>';
                  //  html +='<td>'+(z)+'</td>';

                   }


                   var bc= 0;
                   var ebc =0;
                   for (let index = 0; index <= data['position_ageing_closed'].length; index++) {
                    val = data['position_ageing_closed'][index];
                    if( (val >= y ) && (val <= (y+9))){
                        bc= parseInt(bc)+parseInt(1);
                     }

                      if(val >= 60) {
                        ebc= parseInt(ebc)+parseInt(1);
                     }


                   }
                   if((y+9) >= 60 ){
                    nb +='<th  style="text-align: center;text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid;">'+(ebc)+'</th>';
                    nb +='<th  style="text-align: center;background:#F8CBAD;text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;">'+data['position_ageing_closed'].length+'</th>';

                }
                //    else if(y == 57){
                //     nb +='<th  style="text-align: center;background:#F8CBAD;text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;">'+data['position_ageing_closed'].length+'</th>';
                //    }
                   else{
                    nb +='<th  style="text-align: center;text-transform:uppercase;font-size:16px;font-weight: bold;border: 1px solid;">'+(bc)+'</th>';
                  //  html +='<td>'+(z)+'</td>';

                   }
                        z = y+9;

                    }
                    var gt = '<th colspan="8" style="background:#92D050; text-transform:uppercase;font-size:18px;font-weight: bold;border: 1px solid;">Total No of Open positions</th>';
                    gt += '<th  style= " background:#8000807a;font-size:20px;font-weight: bold;border: 1px solid;text-align: center;">'+data['position_ageing_total'].length +'</th>';

                    $('#td_head').html(th);
                   $('#billable').html(html);
                   $('#non-billabe').html(nb);
                   $('#gt').html(gt);
                }



                    });
}
