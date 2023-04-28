$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    
   
}); 


$(document).ready(function() {
    get_cv_count_details();

});

$('#dateFilterbtn').on('click',function(){
    get_cv_count_details();
})


function get_cv_count_details(){

    var get_from_date = $('#cv_filter_from_date').val();
    var get_to_date = $('#cv_filter_to_date').val();

    $.ajax({
        type: "POST",
        url: get_cv_count_details_link,
        data: {"get_from_date":get_from_date,"get_to_date":get_to_date, },
        dataType: "JSON",
        success: function (data) {

            console.log(data.response);
            if (data.response !='') {
                
                var cv_count_arr = [];
                var recruiter_arr = [];

                for (let index = 0; index < data.response.length; index++) {
                    cv_count_arr.push(data.response[index].cv_count);
                    recruiter_arr.push(data.response[index].recruiter);

                }

                console.log(cv_count_arr);
                console.log(recruiter_arr);
                var cv_count_input = cv_count_arr.join(',');
                alert(cv_count_input);

                var get_ra = recruiter_arr.toString();
                var recruiter_input = '\'' + get_ra.split(',').join('\',\'') + '\'';
                console.log(recruiter_input);

                alert(recruiter_input);
                var options = {
                    chart: {
                        width: "100%",
                        height: 380,
                        type: "bar"
                    },
                    plotOptions: {
                        bar: {
                            horizontal: false,
                            columnWidth: '20%',
                            endingShape: 'rounded'
                        },
                    },
                    dataLabels: {
                        enabled: false
                    },
                    stroke: {
                        width: 1,
                        colors: ["#fff"]
                    },
                    series: [{
                        data: [52,73,13,63,77,45,84]
                    }],
                    xaxis: {
                        categories: ['Devi','Dilip Kumar','Pratibha Sharma','Preethi Arulprakasam','Preethi M','Raghavi GN','Sowmiya.S'],
                    },
                    tooltip: {
        
                        custom: function({
                            series,
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            return (
                                '<div class="arrow_box">' +
                                "<span>" +
                                w.globals.labels[dataPointIndex] +
                                ": " +
                                series[seriesIndex][dataPointIndex] +
                                "</span>" +
                                "</div>"
                            );
                        }
                    }
                };
        
                var chart = new ApexCharts(document.querySelector("#apex-chart"), options);
        
                chart.render();
        
                
            }
        }
    })
}

function draw_chart(a_data,b_data) {     
              
}