$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


});

function getRandomColor() {
    var letters = '0123456789ABCDEF';
    var color = '#';
    for (var i = 0; i < 6; i++) {
        color += letters[Math.floor(Math.random() * 16)];
    }
    return color;
}

$(document).ready(function () {
    get_cv_count_details();

    get_recruiter_list();
    get_division();
    get_raisedby();

});

function get_division(){

    $.ajax({
        type: "POST",
        url: get_division_link,
        data: { },
        success: function (data) {
            if(data.length != 0){
                var html = '<option value="">Select Division</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].division+'">'+data[index].division+'</option>';
                }
                
                $('#af_division').html(html);

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
                var html = '<option value="">Select Raised by</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].name+'">'+data[index].name+'</option>';
                }
                
                $('#af_raisedby').html(html);

            }
        }
    });
}
function get_recruiter_list() {
    $.ajax({
        type: "POST",
        url: get_recruiter_list_link,
        data: {},
        success: function (data) {
            if (data.length != 0) {
                var html = '<option value="">Select Recruiter</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="' + data[index].empID + '">' + data[index].name + '</option>';
                }

                $('#af_recruiter').html(html);
                $('#af_recruiter_cp').html(html);

            }
        }
    });
}

$('#dateFilterbtn').on('click', function () {
    get_cv_count_details();

});

$('#dateFilterbtn_cp').on('click', function () {
    get_cpcv_count_details();

});

$('#dateFilterbtnClear').on('click',function(){
    $('#cv_filter_from_date').val("");
    $('#cv_filter_to_date').val("");
    $('#af_recruiter').val("");
    $('#af_teams').val("");
    $('#show_cv_count').text(0);

    $('#cvcount-chart').empty();


});
function get_cv_count_details() {

    var get_from_date = $('#cv_filter_from_date').val();
    var get_to_date = $('#cv_filter_to_date').val();
    var af_recruiter = $('#af_recruiter').val();
    var af_teams = $('#af_teams').val();
    // alert(get_from_date);
    // alert(get_to_date);
    $.ajax({
        type: "POST",
        url: get_cv_count_details_link,
        data: {
            "get_from_date": get_from_date,
            "get_to_date": get_to_date,
            "af_recruiter": af_recruiter,
            "af_teams": af_teams,
        },
        dataType: "JSON",
        success: function (data) {

            console.log(data.response);
            if (data.response.length != '') {

                var cv_count_arr = [];
                var recruiter_arr = [];

                for (let index = 0; index < data.response.length; index++) {
                    cv_count_arr.push(data.response[index].cv_count);
                    recruiter_arr.push(data.response[index].recruiter);

                }

                cv_count_arr = cv_count_arr.map(Number);

                var show_cv_count = cv_count_arr.reduce(function (a, b) {
                    return a + b
                }, 0)


                $('#show_cv_count').text(show_cv_count);
                var get_predefined_range = $('#predefined_range').val();

                if (get_predefined_range == 'Today') {
                    var show_avg_cv_count = show_cv_count / 1;
                } else if (get_predefined_range == 'Yesterday') {
                    var show_avg_cv_count = show_cv_count / 1;

                } else if (get_predefined_range == 'Last 7 Days') {
                    var show_avg_cv_count = show_cv_count / 6;

                } else if (get_predefined_range == 'Last 30 Days') {
                    var show_avg_cv_count = show_cv_count / 25;

                } else if (get_predefined_range == 'This Month') {
                    var show_avg_cv_count = show_cv_count / 25;

                } else if (get_predefined_range == 'Last Month') {
                    var show_avg_cv_count = show_cv_count / 26;

                } else {

                }
                // $('#show_avg_cv_count').text(Math.round(show_avg_cv_count));

                draw_chart(cv_count_arr, recruiter_arr);
                // get_pie_chart(cv_count_arr, recruiter_arr)

            }else{
                $('#cvcount-chart').empty();
                $('#show_cv_count').text(0);

            }
        }
    })
}

function get_cpcv_count_details() {

    var get_from_date = $('#cv_filter_from_date_cp').val();
    var get_to_date = $('#cv_filter_to_date_cp').val();
    var af_recruiter = $('#af_recruiter_cp').val();
    var af_teams = $('#af_teams_cp').val();
    var af_division = $('#af_division').val();
    var af_raisedby = $('#af_raisedby').val();
    var af_billable = $('#af_billable').val();

    $.ajax({
        type: "POST",
        url: get_cv_count_details_link,
        data: {
            "get_from_date": get_from_date,
            "get_to_date": get_to_date,
            "af_recruiter": af_recruiter,
            "af_teams": af_teams,
            "af_division": af_division,
            "af_raisedby": af_raisedby,
            "af_billable": af_billable,
        },
        dataType: "JSON",
        success: function (data) {

            if (data.response.length != '') {

                var cv_count_arr = [];
                var recruiter_arr = [];

                for (let index = 0; index < data.response.length; index++) {
                    cv_count_arr.push(data.response[index].cv_count);
                    recruiter_arr.push(data.response[index].recruiter);

                }

                cv_count_arr = cv_count_arr.map(Number);

                var show_cv_count = cv_count_arr.reduce(function (a, b) {
                    return a + b
                }, 0)


                $('#show_cv_count').text(show_cv_count);
                var get_predefined_range = $('#predefined_range').val();

                if (get_predefined_range == 'Today') {
                    var show_avg_cv_count = show_cv_count / 1;
                } else if (get_predefined_range == 'Yesterday') {
                    var show_avg_cv_count = show_cv_count / 1;

                } else if (get_predefined_range == 'Last 7 Days') {
                    var show_avg_cv_count = show_cv_count / 6;

                } else if (get_predefined_range == 'Last 30 Days') {
                    var show_avg_cv_count = show_cv_count / 25;

                } else if (get_predefined_range == 'This Month') {
                    var show_avg_cv_count = show_cv_count / 25;

                } else if (get_predefined_range == 'Last Month') {
                    var show_avg_cv_count = show_cv_count / 26;

                } else {

                }
                // $('#show_avg_cv_count').text(Math.round(show_avg_cv_count));

                $.ajax({
                    type: "POST",
                    url: get_cpcv_count_details_link,
                    data: {
                        "get_from_date": get_from_date,
                        "get_to_date": get_to_date,
                        "af_recruiter": af_recruiter,
                        "af_teams": af_teams,
                        "af_division": af_division,
                        "af_raisedby": af_raisedby,
                        "af_billable": af_billable,
                    },
                    dataType: "JSON",
                    success: function (data_cp) {
                        if (data_cp.array_cpcv.length != 0) {
                            var cp_cv_count = data_cp.array_cpcv;
                            var cp_recruiter_list = data_cp.array_recruiter;
                        }

                        draw_cp_chart(cp_recruiter_list, cp_cv_count,cv_count_arr);

                    }
                });

            }
        }
    })
}

function draw_cp_chart(cp_recruiter_list,cp_cv_count,total_cv_count) {
   
    $('#bar').empty();

    var barOptions = {
        series: [
          {
            name: "Total CV Count",
            data: total_cv_count,
          },
          {
            name: "Closed CV Count",
            data: cp_cv_count
          },
          
        ],
        chart: {
          type: "bar",
          height: 350,
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "35%",
            // endingShape: "rounded",
            endingShape: "flat",
            dataLabels: {
                position: 'top'
              }
          },
        },
        dataLabels: {
          enabled: true,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"],
        },
        xaxis: {
          categories: cp_recruiter_list,
        },
        yaxis: {
          title: {
            text: "CV Counts",
          },
        },
        fill: {
          opacity: 1,
        },
        tooltip: {
          y: {
            formatter: function(val) {
            //   return  val + " thousands";
              return  val;
            },
          },
        },
      };
      
      var bar = new ApexCharts(document.querySelector("#bar"), barOptions);
      bar.render();
     
}

function draw_chart(cv_count_arr, recruiter_arr) {

    $('#cvcount-chart').empty();

    var barcvOptions = {
        series: [
          {
            name: "Total CV Count",
            data: cv_count_arr,
          },
          
        ],
        chart: {
          type: "bar",
          height: 350,
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "35%",
            endingShape: "flat",
            dataLabels: {
                position: 'top'
              }
          },
        },
        dataLabels: {
          enabled: true,
        },
        stroke: {
          show: true,
          width: 2,
          colors: ["transparent"],
        },
        xaxis: {
          categories: recruiter_arr,
        },
        yaxis: {
          title: {
            text: "CV Count",
          },
        },
        fill: {
          opacity: 1,
        },
        tooltip: {
          y: {
            formatter: function(val) {
            //   return  val + " thousands";
              return  val;
            },
          },
        },
      };
      
      var bar_cv = new ApexCharts(document.querySelector("#cvcount-chart"), barcvOptions);
      bar_cv.render();

}

function get_pie_chart(cv_count_arr, recruiter_arr) {
    var xValues = recruiter_arr;
    var yValues = cv_count_arr;
    var background_colors = [];
    for (var i = 0, len = yValues.length; i < len; i++) {
        background_colors.push(getRandomColor()); // I use @Benjamin method here
    }

    console.log(background_colors);
    var barColors = [
        "#b91d47",
        "#00aba9",
        "#2b5797",
        "#e8c3b9",
        "#1e7145"
    ];

    new Chart("myChart", {
        type: "bar",
        data: {
            labels: xValues,
            datasets: [{
                backgroundColor: background_colors,
                data: yValues
            }]
        },
        options: {
            legend: {
                display: false
            },
            title: {
                display: true,
                text: "World Wine Production 2018"
            }
        }
    });
}
