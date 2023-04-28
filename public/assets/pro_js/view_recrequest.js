$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
    
   
}); 

$(document).ready(function() {

    get_band_details();
    
    get_recruitment_view_details_new();

});

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
                
                $('#band').html(html);

            }
        }
    });
}

function get_recruitment_view_details_new(){
    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var rfh_no = url.searchParams.get("rfh_no");

    
    $.ajax({
        type: "POST",
        url: get_recruitment_view_details_new_link,
        data: {"rfh_no":rfh_no, },

        success: function (data) {

            if(data.tbl_rfh.length != 0){
                
                $("input[name=rolls_option][value='"+data.tbl_rfh[0].rolls_option+"']").prop("checked",true);
                $('#name').val(data.tbl_rfh[0].name);
                $('#mobile').val(data.tbl_rfh[0].mobile);
                $('#email').val(data.tbl_rfh[0].email);
                $('#position_reports').val(data.tbl_rfh[0].position_reports);
                $('#approved_by').val(data.tbl_rfh[0].approved_by);
                $('#ticket_number').val(data.tbl_rfh[0].ticket_number);
                $('#position_title').val(data.tbl_rfh[0].position_title);

                $("input[name=location][value='"+data.tbl_rfh[0].location+"']").prop("checked",true);

                $('#location_preferred').val(data.tbl_rfh[0].location_preferred);
                $('#business').val(data.tbl_rfh[0].business);
                getdivision(data.tbl_rfh[0].business,data.tbl_rfh[0].division);

                $('#band').val(data.tbl_rfh[0].band);

                $('#function').val(data.tbl_rfh[0].function);
                $('#no_of_positions').val(data.tbl_rfh[0].no_of_positions);
                $('#jd_roles').val(data.tbl_rfh[0].jd_roles);

                $('#qualification').val(data.tbl_rfh[0].qualification);
                $('#essential_skill').val(data.tbl_rfh[0].essential_skill);
                $('#good_skill').val(data.tbl_rfh[0].good_skill);
                $('#experience').val(data.tbl_rfh[0].experience);
                $('#salary_range').val(data.tbl_rfh[0].salary_range);
                $('#any_specific').val(data.tbl_rfh[0].any_specific);
                $('#res_id').val(data.tbl_rfh[0].res_id);


            }
        }
    });
}

function getdivision(business,division){
    
    var val = business;
// alert(val);
    if(val == 'CKPL'){
        $('.ckpl').show();
        $('.cipl').hide();
        $('.ck_institution').hide();
        $('.ck_farms').hide();
        $('#division1').val(division);

    }
    else if(val == 'CIPL'){
        $('.cipl').show();
        $('.ckpl').hide();
        $('.ck_institution').hide();
        $('.ck_farms').hide();
        $('#division2').val(division);

    }
    else if(val == 'CK Institutions'){
        $('.ck_institution').show();
        $('.ckpl').hide();
        $('.cipl').hide();
        $('.ck_farms').hide();
        $('#division3').val(division);

    }
    else if(val == 'Farms'){
        $('.ck_farms').show();
        $('.ck_institution').hide();
        $('.ckpl').hide();
        $('.cipl').hide();
        $('#division4').val(division);

    }
    else{
        //$('.pay1_ele').show();
        $('.ckpl').hide();
        $('.cipl').hide();
        $('.ck_institution').hide();
        $('.ck_farms').hide();

    }

}