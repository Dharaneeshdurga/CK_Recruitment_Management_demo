$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


});

$(document).ready(function() {

    get_band_details();
    get_raisedby_list();
    get_recruitment_edit_details_new();

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var rfh_no = url.searchParams.get("rfh_no");

    var host = "allocation_list/"+rfh_no;

    $("#export_link_id").attr("href", host)

});

function get_raisedby_list(){

    $.ajax({
        type: "POST",
        url: get_raisedby_list_link,
        data: { },
        dataType: "JSON",

        success: function (data) {

            if(data.length != 0){
                var html = '<option value="">Select</option>';
                for (let index = 0; index < data.length; index++) {
                    html += '<option value="'+data[index].raised_by+'">'+data[index].raised_by+'</option>';
                }

                $('#name_list').html(html);

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

                $('#band').html(html);

            }
        }
    });
}

function get_recruitment_edit_details_new(){
    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var rfh_no = url.searchParams.get("rfh_no");


    $.ajax({
        type: "POST",
        url: get_recruitment_edit_details_new_link,
        data: {"rfh_no":rfh_no, },

        success: function (data) {
            var position_reports = data.tbl_rfh[0].position_reports;
            var approved_by = data.tbl_rfh[0].approved_by;
            var location = data.tbl_rfh[0].location;
            var buisness = data.tbl_rfh[0].business;
           var department = data.tbl_rfh[0].department;
           var vertical = data.tbl_rfh[0].vertical;
           var attendance_format = data.tbl_rfh[0].attendance_format;
           var emp_category = data.tbl_rfh[0].emp_category;
           var week_off = data.tbl_rfh[0].week_off;
            get_buisness_list_budgie(position_reports,approved_by,location,buisness,department,vertical,attendance_format,emp_category,week_off);
            if(data.tbl_rfh.length != 0){

                $("input[name=rolls_option][value='"+data.tbl_rfh[0].rolls_option+"']").prop("checked",true);
                $('#name').val(data.tbl_rfh[0].name);
                $('#mobile').val(data.tbl_rfh[0].mobile);
                $('#email').val(data.tbl_rfh[0].email);


                //$('#position_reports').select2('val',data.tbl_rfh[0].position_reports);
                $('#position_reports').select2('data', {id: 100, a_key: 'Lorem Ipsum'});
               // $('#approved_by').val(data.tbl_rfh[0].approved_by);
                $('#ticket_number').val(data.tbl_rfh[0].ticket_number);
                $('#position_title').val(data.tbl_rfh[0].position_title);

                $("input[name=location][value='"+data.tbl_rfh[0].location+"']").prop("checked",true);

                $('#location_preferred').val(data.tbl_rfh[0].location_preferred);

                //$('#business').val(data.tbl_rfh[0].business);
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
                $('#ck_supervisior').val(data.tbl_rfh[0].ck_supervisior);
                $('#ck_supervisior_mail').val(data.tbl_rfh[0].ck_mail);



            }
        }
    });
}
function get_buisness_list_budgie(position_reports,approved_by,location,buisness,department,vertical,attendance_format,emp_category,week_off){

	$.ajax({
		type: "POST",
		//url:"http://127.0.0.1:8080/api/get_buisness",
		url:"http://hub1.cavinkare.in/BUDGIE/public/index.php/api/get_buisness",
		data: { },
		cors:"true",
		dataType: "JSON",
		success: function (data) {
			var buiss_data = data.data.buiss_tbl;
			if(buiss_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < buiss_data.length; index++) {
                    if(buisness == buiss_data[index].business_name){
                        html += '<option selected value="'+buiss_data[index].business_name+'">'+buiss_data[index].business_name+'</option>';

                    }
                    else{
                        html += '<option value="'+buiss_data[index].business_name+'">'+buiss_data[index].business_name+'</option>';

                    }
				}
				//html += '<option value="Others">Others</option>';

				$('#buiss_list').html(html);

			}
			var locate_data = data.data.locate_tbl;
			if(locate_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < locate_data.length; index++) {
                    if(location == locate_data[index].location_name){
                        html += '<option selected value="'+locate_data[index].location_name+'">'+locate_data[index].location_name+'</option>';

                    }
                    else{
                        html += '<option value="'+locate_data[index].location_name+'">'+locate_data[index].location_name+'</option>';

                    }
				}
				$('#location').html(html);

			}

			var depart_data = data.data.depart_tbl;
			if(depart_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < depart_data.length; index++) {
                    if(department == depart_data[index].department_name){
                        html += '<option selected value="'+depart_data[index].department_name+'">'+depart_data[index].department_name+'</option>';

                    }
					html += '<option value="'+depart_data[index].department_name+'">'+depart_data[index].department_name+'</option>';
				}
				$('#department').html(html);

			}

			var design_data = data.data.design_tbl;
			if(design_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < design_data.length; index++) {
					html += '<option value="'+design_data[index].designation_name+'">'+design_data[index].designation_name+'</option>';
				}
				$('#designation').html(html);

			}

			var vert_data = data.data.vertical_tbl;
			if(vert_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < vert_data.length; index++) {
                    if(vertical == vert_data[index].vertical_name){
                        html += '<option selected value="'+vert_data[index].vertical_name+'">'+vert_data[index].vertical_name+'</option>';

                    }
					html += '<option value="'+vert_data[index].vertical_name+'">'+vert_data[index].vertical_name+'</option>';
				}
				$('#vertical').html(html);

			}
			var week_data = data.data.week_tbl;
			if(week_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < week_data.length; index++) {
                    if(week_off == week_data[index].week_off){
                        html += '<option selected value="'+week_data[index].week_off+'">'+week_data[index].week_off+'</option>';

                    }
                    else{
                        html += '<option value="'+week_data[index].week_off+'">'+week_data[index].week_off+'</option>';
                    }
				}
				$('#week_off').html(html);

			}
			var role_data = data.data.role_tbl;
			if(role_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < role_data.length; index++) {
                    if(emp_category == role_data[index].role_category_name){
                        html += '<option selected value="'+role_data[index].role_category_name+'">'+role_data[index].role_category_name+'</option>';

                    }
					html += '<option value="'+role_data[index].role_category_name+'">'+role_data[index].role_category_name+'</option>';
				}
				$('#emp_category').html(html);

			}
			var attn_data = data.data.attn_tbl;
			if(attn_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < attn_data.length; index++) {
                    if(attendance_format == attn_data[index].attendance_format){
                        html += '<option selected value="'+attn_data[index].attendance_format+'">'+attn_data[index].attendance_format+'</option>';

                    }
					html += '<option value="'+attn_data[index].attendance_format+'">'+attn_data[index].attendance_format+'</option>';
				}
			$('#attendance_format').html(html);

			}
			var user_data = data.data.users_tbl;
			if(user_data.length != 0){
				var html = '<option value="">Select</option>';
				for (let index = 0; index < user_data.length; index++) {
                    if(position_reports == user_data[index].username ){
                        html += '<option selected value="'+user_data[index].username+'-'+user_data[index].empID+'">'+user_data[index].username+'</option>';

                    }
                    else{
					html += '<option value="'+user_data[index].username+'-'+user_data[index].empID+'">'+user_data[index].username+'</option>';
                    }
                }
            }
                if(user_data.length != 0){
                    var htmla = '<option value="">Select</option>';
                    for (let index = 0; index < user_data.length; index++) {
                        if(approved_by == user_data[index].username ){
                            htmla += '<option selected value="'+user_data[index].username+'-'+user_data[index].empID+'">'+user_data[index].username+'</option>';

                        }
                        else{
                            htmla += '<option value="'+user_data[index].username+'-'+user_data[index].empID+'">'+user_data[index].username+'</option>';
                        }
                    }
				$('#approved_by').html(htmla);
				$('#position_reports').html(html);

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

function get_recruitment_edit_details(){

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var rfh_no = url.searchParams.get("rfh_no");

    $.ajax({
        type: "POST",
        url: get_recruitment_edit_details_link,
        data: {"rfh_no":rfh_no, },

        success: function (data) {

            if(data.length != 0){

                $('#rfh_no').val(data[0].rfh_no);
                $('#position_title').val(data[0].position_title);
                $('#no_of_position').val(data[0].no_of_position);
                $('#band').val(data[0].band);
                $('#open_date').val(data[0].open_date);
                $('#critical_position').val(data[0].critical_position).prop('selected', true);
                $('#business').val(data[0].business).prop('selected', true);
                $('#division').val(data[0].division).prop('selected', true);
                $('#function').val(data[0].function);
                $('#location').val(data[0].location);
                $('#billing_status').val(data[0].billing_status).prop('selected', true);


                $('#interviewer').val(data[0].interviewer);
                $('#salary_range').val(data[0].salary_range);


            }
        }
    });
}


$('#requestEditForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

    $('#btnUpdate').prop("disabled",true);
    $('#btnUpdate').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

       $.ajax({
            url:reqcruitment_request_editprocess_new_link,
            method:"POST",
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#btnUpdate').prop("disabled",false);
                $('#btnUpdate').html('Update');


                if(data.response =='Updated'){

                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            window.location = 'view_recruit_request_default';
                            // location.reload();
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




