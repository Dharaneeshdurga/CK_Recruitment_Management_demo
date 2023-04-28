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


function get_candidate_preview_details(){

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var cdID = url.searchParams.get("cdID");

    $.ajax({
        type: "POST",
        url: get_candidate_preview_details_link,
        data: { "cdID":cdID, },
        success: function (data) {
            if(data.candidate_basic_details.length !=0){
                $('#cdp_name').text(data.candidate_basic_details[0].candidate_name);
                $('#cdp_mobileno').text(data.candidate_basic_details[0].candidate_mobile);
                $('#cdp_email').text(data.candidate_basic_details[0].candidate_email);
                $('#cdp_gender').text(data.candidate_basic_details[0].gender);
                $('#cdp_csource').text(data.candidate_basic_details[0].candidate_source);
                $('#cdp_ctype').text(data.candidate_basic_details[0].candidate_type);

                var poi_html = '';

                poi_html +='<div class="col-md-6">';
                poi_html +='<h5>Proof of Identity</h5>';
                poi_html +='<p>'+data.candidate_basic_details[0].proof_of_identity+'</p>';
                poi_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].poi_filename+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';
                poi_html +='</div>';
                poi_html +='<div class="col-md-6">';
                poi_html +='<h5>Proof of Address</h5>';
                poi_html +='<p>'+data.candidate_basic_details[0].proof_of_address+'</p>';
                poi_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].poa_filename+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';
                poi_html +='</div>';

                $('#put_poi_poa_details').html(poi_html);
                
                var opd_html = '';

                opd_html +='<div class="col-md-6">';
                opd_html +='<h5>Tax Entity Proof</h5>';
                if(data.candidate_basic_details[0].tax_entity_proof !=''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].tax_entity_proof+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash-fill"></i></button>';

                }
                opd_html +='<h5>Proof Of Relieving</h5>';
                if(data.candidate_basic_details[0].proof_of_relieving !=''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].proof_of_relieving+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash"></i></button>';

                }
                opd_html +='<h5>Proof Of Vaccination</h5>';
                if(data.candidate_basic_details[0].proof_of_vaccination !=''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].proof_of_vaccination+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash"></i></button>';

                }
                opd_html +='</div>';
                opd_html +='<div class="col-md-6">';
                opd_html +='<h5>Proof Of Date Of Birth</h5>';
                if(data.candidate_basic_details[0].proof_of_dob !=''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].proof_of_dob+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash"></i></button>';

                }
                opd_html +='<h5>Proof Of Blood Group</h5>';
                if(data.candidate_basic_details[0].proof_of_bg !=''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].proof_of_bg+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash"></i></button>';

                }
                opd_html +='<h5>Proof Of Bank Account</h5>';
                if(data.candidate_basic_details[0].proof_of_bankacc ==''){
                    opd_html +='<a href="../candidate_doc/'+data.candidate_basic_details[0].cdID+'/'+data.candidate_basic_details[0].proof_of_bankacc+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i  class="bi bi-eye"></i></button></a>';

                }else{
                    opd_html +='<button type="button" class="btn btn-sm btn-warning"><i class="bi bi-eye-slash"></i></button>';

                }
                opd_html +='</div>';

                $('#put_proofdoc_details').html(opd_html);

            }

            if(data.candidate_education.length !=0){

                var edu_html ='';
                for (let edi = 0; edi < data.candidate_education.length; edi++) {
                    
                    edu_html +='<div class="timeline__content">';
                    edu_html +='<a href="../candidate_doc/'+data.candidate_education[edi].cdID+'/'+data.candidate_education[edi].edu_certificate+'" title="Preview" target="_blank"><span class="content_tag">Certificate</span></a>';
                    edu_html +='<span class="content_date">';
                    edu_html +=''+data.candidate_education[edi].edu_start_month + data.candidate_education[edi].edu_start_year +'';
                    edu_html +=''+data.candidate_education[edi].edu_end_month + data.candidate_education[edi].edu_end_year +'';
                    edu_html +='</span>';
                    edu_html +='<p class="content_p"><strong>Degree - </strong>'+data.candidate_education[edi].degree+'</p>';
                    edu_html +='<p class="content_p"><strong>School / University - </strong>'+data.candidate_education[edi].university+'</p>';
                    edu_html +='</div>';


                    edu_html +='<div class="col-md-6">';
                    edu_html +='<h5>Degree</h5>';
                    edu_html +='<p>'+data.candidate_education[edi].degree+'</p>';
                        edu_html +='<div class="row">';
                            edu_html +='<div class="col-md-6">';
                                edu_html +='<h5>Start Date</h5>';
                                edu_html +='<p>'+data.candidate_education[edi].edu_start_month+'</p>';
                            edu_html +='</div>';
                            edu_html +='<div class="col-md-6">';
                                edu_html +='<h5>Year</h5>';
                                edu_html +='<p>'+data.candidate_education[edi].edu_start_year+'</p>';
                            edu_html +='</div>';
                        edu_html +='</div>';
                    edu_html +='<h5>Certificate</h5>';
                    edu_html +='<a href="../candidate_doc/'+data.candidate_education[edi].cdID+'/'+data.candidate_education[edi].edu_certificate+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';
                    edu_html +='</div>';
                    
                    edu_html +='<div class="col-md-6">';
                    edu_html +='<h5>School / University</h5>';
                    edu_html +='<p>'+data.candidate_education[edi].university+'</p>';
                        edu_html +='<div class="row">';
                            edu_html +='<div class="col-md-6">';
                                edu_html +='<h5>To Date</h5>';
                                edu_html +='<p>'+data.candidate_education[edi].edu_end_month+'</p>';
                            edu_html +='</div>';
                            edu_html +='<div class="col-md-6">';
                                edu_html +='<h5>Year</h5>';
                                edu_html +='<p>'+data.candidate_education[edi].edu_end_year+'</p>';
                            edu_html +='</div>';
                        edu_html +='</div>';
                    
                    edu_html +='</div>';
                }
               
                $('#put_candidate_edu_details').html(edu_html);
            }
            
            if(data.candidate_experience.length !=0){

                var exp_html ='';
                for (let exi = 0; exi < data.candidate_experience.length; exi++) {
                    
                    exp_html +='<div class="col-md-6">';
                    exp_html +='<h5>Job Title</h5>';
                    exp_html +='<p>'+data.candidate_experience[exi].job_title+'</p>';
                        exp_html +='<div class="row">';
                            exp_html +='<div class="col-md-6">';
                                exp_html +='<h5>Start Date</h5>';
                                exp_html +='<p>'+data.candidate_experience[exi].exp_start_month+'</p>';
                            exp_html +='</div>';
                            exp_html +='<div class="col-md-6">';
                                exp_html +='<h5>Year</h5>';
                                exp_html +='<p>'+data.candidate_experience[exi].exp_start_year+'</p>';
                            exp_html +='</div>';
                        exp_html +='</div>';
                    exp_html +='<h5>Appoinment Letter Proof</h5>';
                    exp_html +='<a href="../candidate_doc/'+data.candidate_experience[exi].cdID+'/'+data.candidate_experience[exi].certificate+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';
                    exp_html +='</div>';
                    
                    exp_html +='<div class="col-md-6">';
                    exp_html +='<h5>Company Name</h5>';
                    exp_html +='<p>'+data.candidate_experience[exi].company_name+'</p>';
                        exp_html +='<div class="row">';
                            exp_html +='<div class="col-md-6">';
                                exp_html +='<h5>To Date</h5>';
                                exp_html +='<p>'+data.candidate_experience[exi].exp_end_month+'</p>';
                            exp_html +='</div>';
                            exp_html +='<div class="col-md-6">';
                                exp_html +='<h5>Year</h5>';
                                exp_html +='<p>'+data.candidate_experience[exi].exp_end_year+'</p>';
                            exp_html +='</div>';
                        exp_html +='</div>';
                    
                    exp_html +='</div>';
                }
                $('#put_candidate_exp_details').html(exp_html);
            }

            if(data.candidate_benefits.length !=0){

                var ben_html ='<h5>Document Type</h5>';
                for (let bei = 0; bei < data.candidate_benefits.length; bei++) {
                    ben_html +='<div class="col-md-6">';
                    ben_html +='<p>'+data.candidate_benefits[bei].doc_type+'</p>';
                    ben_html +='</div>';
                    ben_html +='<div class="col-md-6">';
                    ben_html +='<a href="../candidate_doc/'+data.candidate_benefits[bei].cdID+'/'+data.candidate_benefits[bei].doc_filename+'" target="_blank"><button type="button" class="btn btn-sm btn-secondary"><i class="bi bi-eye"></i></button></a>';
                    ben_html +='</div>';

                }
                $('#put_candidate_benefits_details').html(ben_html);

            }

        }
    })

}