$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

});

$(document).ready(function() {

    get_candidate_preview_details();
});

$(document).on('click', '#basicdocNextbtn', function(e) {
    document.getElementById('v-pills-edu-tab').click(); // Works!
});

$(document).on('click', '#edudocNextbtn', function(e) {

    var candidate_type = $('#cdp_ctype_val').val(); // Works!

    if(candidate_type =='Fresher'){
        document.getElementById('v-pills-proofdoc-tab').click(); // Works!

    }else{
        document.getElementById('v-pills-exp-tab').click(); // Works!

    }

});

$(document).on('click', '#benefitsdocNextbtn', function(e) {
    document.getElementById('v-pills-proofdoc-tab').click(); // Works!
});

$(document).on('click', '#proofdocNextbtn', function(e) {

    document.getElementById('v-pills-others-tab').click(); // Works!

});

$(document).on('click', '#benefitsdocNextbtn', function(e) {
    document.getElementById('v-pills-proofdoc-tab').click(); // Works!
});

$(document).on('click', '#eduPreviousbtn', function(e) {
    document.getElementById('v-pills-cbd-tab').click(); // Works!
});

$(document).on('click', '#expdocNextbtn', function(e) {
    document.getElementById('v-pills-compensation-tab').click(); // Works!
});

$(document).on('click', '#expPreviousbtn', function(e) {
    document.getElementById('v-pills-edu-tab').click(); // Works!
});

$(document).on('click', '#benefitsPreviousbtn', function(e) {
    document.getElementById('v-pills-exp-tab').click(); // Works!
});

$(document).on('click', '#proofPreviousbtn', function(e) {

    var candidate_type = $('#cdp_ctype_val').val(); // Works!

    if(candidate_type =='Fresher'){
        document.getElementById('v-pills-edu-tab').click(); // Works!

    }else{
        document.getElementById('v-pills-compensation-tab').click(); // Works!

    }
});

$(document).on('click', '#actionPreviousbtn', function(e) {
    document.getElementById('v-pills-proofdoc-tab').click(); // Works!
});


function get_candidate_preview_details(){

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var cdID = url.searchParams.get("cdID");
  //  var link = "http://127.0.0.1:8080/";
  var link = budgie_link;
//alert(cdID);
$.ajax({
    type: "POST",
   // url: get_candidate_preview_details_link,
 //  url:"http://127.0.0.1:8080/api/get_all_candidate_details",
 url: budgie_link + "api/get_all_candidate_details",
    data: { "cdID":cdID, },
    success: function (data) {
        if(data.candidate_basic_details.length !=0){
            $('#cdp_name').text(data.candidate_basic_details[0].username);
            $('#cdp_mobileno').text(data.candidate_basic_details[0].contact_no);
            $('#cdp_email').text(data.candidate_basic_details[0].p_email);
            $('#cdp_gender').text(data.candidate_basic_details[0].gender);
            $('#cdp_csource').text(data.candidate_basic_details[0].can_source);
            $('#cdp_ctype').text(data.candidate_basic_details[0].can_type);
            $('#cdp_ctype_val').val(data.candidate_basic_details[0].can_type);

            if(data.candidate_basic_details[0].candidate_type == 'Fresher'){
                $('#v-pills-exp-tab').css("display","none");
                $('#v-pills-compensation-tab').css("display","none");
            }

            $('#poi_name').text(data.candidate_benefits[0].pan);
            $('#poa_name').text(data.candidate_benefits[0].aadhaar_card_proof);

         //   $('#poi_file').html('<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].poi_filename+'" title="Preview" target="_blank"><button class="btn btn-sm btn-secondary" type="button">Preview</button></a>');
           $('#poi_file').html('<a href="'+link+'/Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].pan+'" title="Preview" target="_blank"><button class="btn btn-sm btn-secondary" type="button">Preview</button></a>');
           $('#poi_file').html('<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].aadhaar_card_proof+'" title="Preview" target="_blank"><button class="btn btn-sm btn-secondary" type="button">Preview</button></a>');

     //   $('#poa_file').html('<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].poa_filename+'" title="Preview" target="_blank"><button class="btn btn-sm btn-secondary" type="button">Preview</button></a>');



            var opd_html = '';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Tax Entity Proof</strong></span>';
            if(data.candidate_benefits[0].pan !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].pan+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Proof Of Relieving</strong></span>';
            if(data.candidate_benefits[0].Relieving_letter !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].Relieving_letter+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Proof Of Vaccination</strong></span>';
            if(data.candidate_benefits[0].Vaccination !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].Vaccination+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Proof Of Date Of Birth</strong></span>';
            if(data.candidate_benefits[0].dob_proof !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_benefits[0].cdID+'/'+data.candidate_benefits[0].dob_proof+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Proof Of Blood Group</strong></span>';
            if(data.candidate_benefits[0].blood_grp_proof !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].blood_grp_proof+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';

            opd_html +='<div class="timeline__content">';
            opd_html +='<span class="content_p"><strong>Proof Of Bank Account</strong></span>';
            if(data.candidate_benefits[0].bank_passbook !=''){
                opd_html +='<a href="'+link+'Documents/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_benefits[0].bank_passbook+'" target="_blank" title="Preview"><span class="content_tag">Proof</span></a>';

            }else{
                opd_html +='<a href="#" target="_blank" title="Preview not Found"><span class="content_tag">Proof</span></a>';

            }
            opd_html +='</div>';


            $('#put_proofdoc_details').html(opd_html);

        }

        if(data.candidate_education.length !=0){

            var edu_html ='';
            for (let edi = 0; edi < data.candidate_education.length; edi++) {

                edu_html +='<div class="timeline__content">';
                edu_html +='<a href="'+link+'education/'+data.candidate_education[edi].edu_certificate+'" title="Preview" target="_blank"><span class="content_tag">Certificate</span></a>';
                edu_html +='<span class="content_date">';
                edu_html +=''+data.candidate_education[edi].edu_start_month +' '+data.candidate_education[edi].edu_start_year +'';
                edu_html +=' - '+data.candidate_education[edi].edu_end_month +' '+data.candidate_education[edi].edu_end_year +'';
                edu_html +='</span>';
                edu_html +='<p class="content_p"><strong>Degree - </strong>'+data.candidate_education[edi].degree+'</p>';
                edu_html +='<p class="content_p"><strong>School / University - </strong>'+data.candidate_education[edi].university+'</p>';
                edu_html +='</div>';

            }

            $('#put_candidate_edu_details').html(edu_html);
        }

        if(data.candidate_experience.length !=0){

            var exp_html ='';
            for (let exi = 0; exi < data.candidate_experience.length; exi++) {

                exp_html +='<div class="timeline__content">';
                exp_html +='<a href="'+link+'Documents/'+data.candidate_experience[exi].cdID+'/'+data.candidate_experience[exi].certificate+'" title="Preview" target="_blank"><span class="content_tag">Certificate</span></a>';
                exp_html +='<span class="content_date">';
                exp_html +=''+data.candidate_experience[exi].exp_start_month +' '+data.candidate_experience[exi].exp_start_year +'';
                exp_html +=' - '+data.candidate_experience[exi].exp_end_month +' '+data.candidate_experience[exi].exp_end_year +'';
                exp_html +='</span>';
                exp_html +='<p class="content_p"><strong>Job Title - </strong>'+data.candidate_experience[exi].job_title+'</p>';
                exp_html +='<p class="content_p"><strong>Company Name - </strong>'+data.candidate_experience[exi].company_name+'</p>';
                exp_html +='</div>';


            }
            $('#put_candidate_exp_details').html(exp_html);
        }

        if(data.candidate_benefits.length !=0){

            var ben_html ='';
          //  for (let bei = 0; bei < data.candidate_benefits.length; bei++) {

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].passport_photo+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].passport_photo+'</p>';
                ben_html +='</div>';


                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].Payslips+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].Payslips+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].Relieving_letter+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].Relieving_letter+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].pan+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].pan+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].bank_passbook+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].bank_passbook+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].Vaccination+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].Vaccination+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].signature+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].signature+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].dob_proof+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].dob_proof+'</p>';
                ben_html +='</div>';


                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].blood_grp_proof+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].blood_grp_proof+'</p>';
                ben_html +='</div>';

                ben_html +='<div class="timeline__content">';
                ben_html +='<a href="'+link+'Documents/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].aadhaar_card_proof+'" title="Preview" target="_blank"><span class="content_tag">Proof</span></a>';
                ben_html +='<p class="content_p"><br><strong>Doc Type - </strong>'+data.candidate_benefits[bei].aadhaar_card_proof+'</p>';
                ben_html +='</div>';

          //  }
            $('#put_candidate_benefits_details').html(ben_html);

        }

    }
})
}
$("#candidate_doc_status").on('change', function() {

    var c_doc_status = $('#candidate_doc_status').val();

    if(c_doc_status =='Not Verified'){
        $('#c_doc_remark_div').css("display","block");
    }else{
        $('#c_doc_remark_div').css("display","none");
    }

});

$("#statusUpdatebtn").on('click', function() {

    $('#statusUpdatebtn').prop("disabled",true);
    $('#statusUpdatebtn').text("Processing");

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var cdID = url.searchParams.get("cdID");

    var c_doc_status = $('#candidate_doc_status').val();

    if(c_doc_status =='Verified'){

        var c_doc_remark = '';
    }else{
        var c_doc_remark = $('#c_doc_remark').val();

    }


    $.ajax({
        type: "POST",
        url: update_cdoc_status_link,
        data: {"c_doc_status":c_doc_status,"cdID":cdID,"c_doc_remark":c_doc_remark, },

        success: function (data) {

            if(data.response =='success'){
                Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {

                             $('#statusUpdatebtn').text("Update");
                             $('#statusUpdatebtn').prop("disabled",false);

                            window.location = 'document_collection';
                            // location.reload();
                        }, 2000);

            }else{
                Toastify({
                    text: "Request Failed..! Try Again",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#f3616d",
                }).showToast();

                setTimeout(
                    function() {
                        $('#statusUpdatebtn').text("Update");
                        $('#statusUpdatebtn').prop("disabled",false);

                        location.reload();
                    }, 2000);


            }
        }
    });

});


