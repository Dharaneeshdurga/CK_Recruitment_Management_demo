function easyHTTP() {

	// Initializing new XMLHttpRequest method.
	this.http = new XMLHttpRequest();
}

// Make an HTTP POST Request
easyHTTP.prototype.post = function(url, data, callback) {

// Open an object (POST, PATH, ASYN-TRUE/FALSE)
this.http.open('POST', url, true);

// Set content-type
this.http.setRequestHeader('Content-type', 'application/json');

// Assigning this to self to have
// scope of this into the function
let self = this;

// When response is ready
this.http.onload = function() {

	// Callback function (Error, response text)
	callback(null, self.http.responseText);
}

// Since the data is an object so
// we need to stringify it
this.http.send(JSON.stringify(data));
}
$( document ).ready(function() {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});



var url_cdID;
var candidate_type;
$(document).ready(function () {
    const month = ["January", "February", "March", "April", "May", "June", "July", "August","September", "October","November","December"];

    d = new Date();
    var cur_month = month[d.getMonth()];
    var cur_year = new Date().getFullYear();

    $('.cur_month').val(cur_month).prop("selected",true);
    $('.cur_year').val(cur_year).change();


    get_datepicker();

    var get_candi_id = $(location).attr("href").split('/').pop();
    url_cdID = get_candi_id;
    get_candidate_details_exist(get_candi_id);
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
    });
function get_datepicker(){

    $(".datepicker").datepicker({
        format: "M-yyyy",
        startView: "months",
        minViewMode: "months",
        defaultDate: 'now'

    });
}


$("#v-pills-cbd-tab").on('click', function() {

    get_candidate_details_exist(url_cdID);
});

$("#v-pills-edu-tab").on('click', function() {

    get_candidate_details_exist(url_cdID);
});

$("#v-pills-exp-tab").on('click', function() {

    get_candidate_details_exist(url_cdID);
});

$("#v-pills-compensation-tab").on('click', function() {

    get_candidate_details_exist(url_cdID);
});

$(document).on('click', '#basicNextbtn', function(e) {
    document.getElementById('v-pills-edu-tab').click(); // Works!
});

$(document).on('click', '#benefitdocNextbtn', function(e) {
    document.getElementById('v-pills-proofdoc-tab').click(); // Works!
});

$(document).on('click', '#expdocNextbtn', function(e) {
    document.getElementById('v-pills-compensation-tab').click(); // Works!
});

$(document).on('click', '#edudocNextbtn', function(e) {

    candidate_type = $('#candidate_type_cmn').val(); // Works!

    if(candidate_type =='Fresher'){
        document.getElementById('v-pills-proofdoc-tab').click(); // Works!

    }else{
        document.getElementById('v-pills-exp-tab').click(); // Works!

    }
});

$(document).on('click', '#prodocNextbtn', function(e) {
    document.getElementById('v-pills-orel-tab').click(); // Works!
});



$(document).on('click', '#eduPreviousbtn', function(e) {
    document.getElementById('v-pills-cbd-tab').click(); // Works!
});

$(document).on('click', '#expPreviousbtn', function(e) {
    document.getElementById('v-pills-edu-tab').click(); // Works!
});

$(document).on('click', '#benefitPreviousbtn', function(e) {
    document.getElementById('v-pills-exp-tab').click(); // Works!
});

$(document).on('click', '#proofPreviousbtn', function(e) {

    candidate_type = $('#candidate_type_cmn').val(); // Works!

    if(candidate_type =='Fresher'){
        document.getElementById('v-pills-edu-tab').click(); // Works!

    }else{
        document.getElementById('v-pills-compensation-tab').click(); // Works!

    }
});
$(document).on('click', '#offerPreviousbtn', function(e) {

    candidate_type = $('#candidate_type_cmn').val(); // Works!

    if(candidate_type =='Fresher'){
        document.getElementById('v-pills-proofdoc-tab').click(); // Works!

    }else{
        document.getElementById('v-pills-compensation-tab').click(); // Works!

    }
});


// get existing basic details
function get_candidate_details_exist(get_candi_id){

    $.ajax({
        type: "POST",
        url: get_candidate_details_exist_link,
        data: {"cdID":get_candi_id, },

        success: function (data) {

            if(data.length !=0){

                if(data.c_basic_details.length !=0){

                    $('#show_candidate_name').text(data.c_basic_details[0].candidate_name);
                        $('#candidate_id').val(data.c_basic_details[0].cdID);
                        $('#rfh_no').val(data.c_basic_details[0].rfh_no);
                        $('#hepl_recruitment_ref_number').val(data.c_basic_details[0].hepl_recruitment_ref_number);
                        var bd_html = '<form id="basicdocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                            bd_html +='<div class="row">';

                                bd_html +='<div class="col-md-6">';

                                    bd_html +='<div class="form-group">';
                                        bd_html +='<label for="candidate_name">First Name <code>*</code></label>';
                                        bd_html +='<input type="text" class="form-control form-control-sm bd_req_cls" id="candidate_name" name="candidate_name" placeholder="" readonly value="'+data.c_basic_details[0].candidate_name+'">';
                                        // bd_html +='<input type="hidden" name="candidate_id" id="candidate_id" value="'+data.c_basic_details[0].cdID+'">';
                                        // bd_html +='<input type="hidden" name="rfh_no" id="rfh_no" value="'+data.c_basic_details[0].rfh_no+'">';
                                        // bd_html +='<input type="hidden" name="hepl_recruitment_ref_number" id="hepl_recruitment_ref_number" value="'+data.c_basic_details[0].hepl_recruitment_ref_number+'">';
                                        bd_html +='<input type="hidden" name="created_by" id="created_by" value="'+data.c_basic_details[0].created_by+'">';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="middle_name">Middle Name </label>';
                                    bd_html +='<input type="text" name="middle_name" id="middle_name"  class="form-control form-control-sm bd_req_cls"  >';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="last_name">Last Name </label>';
                                    bd_html +='<input type="text" name="last_name" id="last_name"  class="form-control form-control-sm bd_req_cls">';
                                    bd_html +='</div>';



                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="dob">Date of Birth <code>*</code></label>';
                                    bd_html +='<input type="date" name="dob" id="dob"  class="form-control form-control-sm bd_req_cls" required >';

                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="age">Age*</code></label>';
                                    bd_html +='<input type="text" name="age" id="age"  class="form-control form-control-sm bd_req_cls"  >';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="blood_gr">Marital Status <code>*</code></label>';
                                    bd_html +='<select class="form-control" name="marital_status" id="marital_status">';
                                    bd_html +='<option value="">-Select Marital Status-</option>';
                                    bd_html +='<option value="Single">Single</option>';
                                    bd_html +='<option value="Married">Married</option>';
                                    bd_html +='<option value="Widowed">Widowed</option>';
                                    bd_html +='<option value="Separated">Separated</option>';
                                    bd_html +='<option value="Divorced">Divorced</option>';
                                    bd_html +='<option value="Others">Others</option>';
                                    bd_html +='</select>';

                                    bd_html +='</select>';
                                    bd_html +='</div>';

                                bd_html +='</div>';

                                bd_html +='<div class="col-md-6">';
                                bd_html +='<div class="form-group">';
                                bd_html +='<label for="candidate_source">Candidate Source <code>*</code></label>';
                                bd_html +='<input type="text" name="candidate_source[]" id="candidate_source" list="get_candidate_source" class="form-control form-control-sm bd_req_cls" placeholder="Candidate Source" value="'+data.c_basic_details[0].candidate_source+'" readonly>';
                            bd_html +='</div>';

                            bd_html +='<div class="form-group">';
                            bd_html +='<label for="candidate_contactno">Mobile Number <code>*</code></label>';
                            bd_html +='<input type="text" id="candidate_contactno" name="candidate_contactno" class="form-control form-control-sm bd_req_cls" placeholder="" value="'+data.c_basic_details[0].candidate_mobile+'" required>';
                            bd_html +='<span id="err_msg" style="display:none; color:red;">Please put 10 digit</span>';
                            bd_html +='<span id="succ_msg" style="display:none;color:green;">Mobile Number is valid</span>';

                            bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                        bd_html +='<label for="candidate_email">Email <code>*</code></label>';
                                        bd_html +='<small class="text-muted">eg.<i>someone@example.com</i></small>';
                                        bd_html +='<input type="text" class="form-control form-control-sm bd_req_cls" id="candidate_email" name="candidate_email" value="'+data.c_basic_details[0].candidate_email+'" readonly>';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                    bd_html +='<label for="blood_gr">Blood Group <code>*</code></label>';
                                    bd_html += '<select class="form-control" id="blood_gr" name="blood_gr"><option value="">-Select Blood Group-</option><option value="A+">A+</option><option value="A-">A-</option><option value="B+">B+</option><option value="B-">B-</option><option value="O+">O+</option><option value="O-">O-</option><option value="AB+">AB+</option><option value="AB-">AB-</option>';
                                    bd_html +='</select>';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';
                                        bd_html +='<label for="candidate_type">Candidate Type <code>*</code></label>';
                                        bd_html +='<input type="text" class="form-control form-control-sm bd_req_cls" name="candidate_type" id="candidate_type" value="'+data.c_basic_details[0].candidate_type+'" readonly>';
                                        $('#candidate_type_cmn').val(data.c_basic_details[0].candidate_type);
                                    bd_html +='</div>';

                                    bd_html +='<label for="candidate_gender">Gender <code>*</code></label>';

                                    if (data.c_basic_details[0].gender =='Male'){
                                        var male_status = 'checked';
                                        var female_status = '';
                                    }
                                    else{
                                        var male_status = '';
                                        var female_status = 'checked';
                                    }

                                    bd_html +='<div class="row">';

                                        bd_html +='<div class="col-md-2">';

                                            bd_html +='<div class="form-check">';
                                                bd_html +='<input class="form-check-input bd_req_cls" type="radio" value="Male" name="candidate_gender" id="candidate_gender1" '+male_status+'>';
                                                bd_html +='<label class="form-check-label" for="candidate_gender1"> Male </label>';
                                            bd_html +='</div>';

                                        bd_html +='</div>';

                                        bd_html +='<div class="col-md-2">';

                                            bd_html +='<div class="form-check">';
                                                bd_html +='<input class="form-check-input bd_req_cls" type="radio" value="Female" name="candidate_gender" id="candidate_gender2" '+female_status+'>';
                                                bd_html +='<label class="form-check-label" for="candidate_gender2"> Female </label>';
                                            bd_html +='</div>';

                                        bd_html +='</div>';

                                    bd_html +='</div>';

                                bd_html +='</div>';

                            bd_html +='</div>';

                            bd_html +='<div class="row">';

                                bd_html +='<div class="col-md-6">';
                                    bd_html +='<div class="form-group">';
                                        bd_html +='<label for="">Proof of Identity <code>*</code></label>';

                                        if (data.c_basic_details[0].proof_of_identity =='AADHAR Card'){
                                            var opt_1 = 'selected';
                                        }else{
                                            var opt_1 = '';
                                        }
                                        if (data.c_basic_details[0].proof_of_identity =='PAN Card'){
                                            var opt_2 = 'selected';
                                        }
                                        else{
                                            var opt_2 = '';
                                        }
                                        if (data.c_basic_details[0].proof_of_identity =='VOTER ID Card'){
                                            var opt_3 = 'selected';
                                        }
                                        else{
                                            var opt_3 = '';
                                        }
                                        if (data.c_basic_details[0].proof_of_identity =='PASSPORT'){
                                            var opt_4 = 'selected';
                                        }
                                        else{
                                            var opt_4 = '';
                                        }
                                        bd_html +='<select class="form-select form-select-sm" id="proof_of_identity" name="proof_of_identity" required>';
                                            bd_html +='<option value="">Select</option>';
                                            bd_html +='<option value="AADHAR Card" '+opt_1+'>AADHAR Card</option>';
                                            bd_html +='<option value="PAN Card" '+opt_2+'>PAN Card </option>';
                                            bd_html +='<option value="VOTER ID Card" '+opt_3+'>VOTER ID Card</option>';
                                            bd_html +='<option value="PASSPORT" '+opt_4+'>PASSPORT</option>';
                                        bd_html +='</select>';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';

                                        if (data.c_basic_details[0].poi_filename ==''){
                                            // var poi_req_attr = 'required';
                                            bd_html +='<fieldset>';
                                                bd_html +='<div class="input-group">';
                                                    bd_html +='<input class="form-control form-control-sm" type="file" id="poi_file" name="poi_file" required>';
                                                    // bd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                bd_html +='</div>';
                                            bd_html +='</fieldset>';

                                        }else{
                                            // var poi_req_attr = ' ';
                                            bd_html +='<fieldset>';
                                                bd_html +='<div class="input-group">';
                                                    bd_html +='<input class="form-control form-control-sm" type="file" id="poi_file" name="poi_file">';
                                                    bd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].poi_filename+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                                bd_html +='</div>';
                                            bd_html +='</fieldset>';

                                        }

                                        // if (data.c_basic_details[0].poi_filename !=''){
                                        //     bd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].poi_filename+'" target="_blank"><span class="badge bg-secondary">Preview</span></a>';
                                        // }

                                    bd_html +='</div>';
                                bd_html +='</div>';

                                bd_html +='<div class="col-md-6">';

                                    bd_html +='<div class="form-group">';
                                        bd_html +='<label for="">Proof of Address <code>*</code></label>';
                                        bd_html +='<small class="text-muted"><i>(Current Full Address of Residence)</i></small>';

                                        if (data.c_basic_details[0].proof_of_address =='AADHAR Card'){
                                            var opt_1 = 'selected';
                                        }else{
                                            var opt_1 = '';
                                            }
                                        if (data.c_basic_details[0].proof_of_address =='BANK STATEMENT'){
                                            var opt_3 = 'selected';
                                        }
                                        else{
                                            var opt_3 = '';
                                        }
                                        if (data.c_basic_details[0].proof_of_address =='VOTER ID Card'){
                                            var opt_2 = 'selected';
                                        }
                                        else{
                                            var opt_2 = '';
                                        }
                                        if (data.c_basic_details[0].proof_of_address =='PASSPORT'){
                                            var opt_4 = 'selected';
                                        }
                                        else{
                                            var opt_4 = '';
                                        }
                                        bd_html +='<select class="form-select form-select-sm" id="proof_of_address" name="proof_of_address" required>';
                                            bd_html +='<option value="">Select</option>';
                                            bd_html +='<option value="AADHAR CARD" '+opt_1+'>AADHAR CARD</option>';
                                            bd_html +='<option value="VOTER ID CARD" '+opt_2+'>VOTER ID CARD</option>';
                                            bd_html +='<option value="BANK STATEMENT" '+opt_3+'>BANK STATEMENT</option>';
                                            bd_html +='<option value="PASSPORT" '+opt_4+'>PASSPORT</option>';
                                        bd_html +='</select>';
                                    bd_html +='</div>';

                                    bd_html +='<div class="form-group">';

                                        if (data.c_basic_details[0].poa_filename ==''){
                                            bd_html +='<fieldset>';
                                                bd_html +='<div class="input-group">';
                                                    bd_html +='<input class="form-control form-control-sm" type="file" id="poa_file" name="poa_file" required>';
                                                    // bd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                bd_html +='</div>';
                                            bd_html +='</fieldset>';
                                        }else{

                                            bd_html +='<fieldset>';
                                                bd_html +='<div class="input-group">';
                                                    bd_html +='<input class="form-control form-control-sm" type="file" id="poa_file" name="poa_file">';
                                                    bd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].poa_filename+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                                bd_html +='</div>';
                                            bd_html +='</fieldset>';

                                        }


                                    bd_html +='</div>';

                                bd_html +='</div>';

                            bd_html +='</div>';
                            bd_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="basicNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';
                            bd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="basicdocSubmitbtn">Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';
                    bd_html +='</form>';

                    $('#v-pills-cbd').html(bd_html);

                    var pd_html = '<form id="prodocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';

                        pd_html +='<div class="row">';
                            pd_html +='<div class="col-md-6">';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Tax Entity Proof</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(PAN CARD bearing Name, PAN Number details)</i></small>';
                                    pd_html +='<input type="hidden" name="created_by" id="created_by" value="'+data.c_basic_details[0].created_by+'">';

                                    if (data.c_basic_details[0].tax_entity_proof !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="tax_proof" name="tax_proof">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].tax_entity_proof+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                            pd_html +='<input class="form-control form-control-sm" type="file" id="tax_proof" name="tax_proof">';
                                                // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                pd_html +='</div>';
                                            pd_html +='</fieldset>';
                                    }

                                pd_html +='</div>';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Proof Of Relieving</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(Relieving Letter/such documentation from Current/Latest Employer.)</i></small>';

                                    if (data.c_basic_details[0].proof_of_relieving !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_relieving" name="proof_of_relieving">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].proof_of_relieving+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                            pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_relieving" name="proof_of_relieving">';
                                                // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                pd_html +='</div>';
                                            pd_html +='</fieldset>';
                                    }
                                pd_html +='</div>';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Proof Of Vaccination</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(Final Vaccination Certificate as downloaded from Govt Portal)</i></small>';

                                    if (data.c_basic_details[0].proof_of_vaccination !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_vaccine" name="proof_of_vaccine">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].proof_of_vaccination+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                            pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_vaccine" name="proof_of_vaccine">';
                                                // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                pd_html +='</div>';
                                            pd_html +='</fieldset>';
                                    }
                                pd_html +='</div>';
                            pd_html +='</div>';

                            pd_html +='<div class="col-md-6">';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Proof Of Date Of Birth</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(Birth Certificate| SSLC  Marksheet | PAN Card etc)</i></small>';

                                    if (data.c_basic_details[0].proof_of_dob !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_dob" name="proof_of_dob">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].proof_of_dob+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                            pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_dob" name="proof_of_dob">';
                                                // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                pd_html +='</div>';
                                            pd_html +='</fieldset>';
                                    }
                                pd_html +='</div>';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Proof Of Blood Group</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(Medical Certificate or Blood Donation Card bearing details)</i></small>';

                                    if (data.c_basic_details[0].proof_of_bg !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_bg" name="proof_of_bg">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].proof_of_bg+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                            pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_bg" name="proof_of_bg">';
                                                // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                pd_html +='</div>';
                                            pd_html +='</fieldset>';
                                    }
                                pd_html +='</div>';
                                pd_html +='<div class="form-group">';
                                    pd_html +='<label for="">Proof Of Bank Account</label>';
                                    pd_html +=' <small class="text-muted">Eg.<i>(Cancelled CHECK LEAF | Bank Passbook Photo Identity Page with Account Details)</i></small>';

                                    if (data.c_basic_details[0].proof_of_bankacc !=''){
                                        pd_html +='<fieldset>';
                                            pd_html +='<div class="input-group">';
                                                pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_ba" name="proof_of_ba">';
                                                pd_html +='<a href="../../candidate_doc/'+data.c_basic_details[0].cdID+'/'+data.c_basic_details[0].proof_of_bankacc+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                            pd_html +='</div>';
                                        pd_html +='</fieldset>';

                                    }else{
                                        pd_html +='<fieldset>';
                                        pd_html +='<div class="input-group">';
                                        pd_html +='<input class="form-control form-control-sm" type="file" id="proof_of_ba" name="proof_of_ba">';
                                        // pd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                        pd_html +='</div>';
                                        pd_html +='</fieldset>';
                                    }
                                pd_html +='</div>';
                            pd_html +='</div>';
                        pd_html +='</div>';

                        pd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="prodocSubmitbtn">Submit <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button> ';
                        pd_html +='<button class="btn btn-sm btn-smn btn-info ol_cl" style="float:right;" type="button" id="prodocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        pd_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="proofPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    pd_html +='</form>';
                    $('#v-pills-proofdoc').html(pd_html);

                }else{

                }

                if(data.c_edu_details.length !=0){
                    var ed_html = '<form id="edudocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                        for(let i = 0; i < data.c_edu_details.length; i++){

                            ed_html +='<div class="row removeclass_st'+data.c_edu_details[i].id+'">';

                                ed_html +='<input type="hidden" name="c_edu_row_id[]" id="c_edu_row_id" value="'+data.c_edu_details[i].id+'">';

                                ed_html +='<div class="col-md-6">';

                                    ed_html +='<div class="form-group">';

                                        ed_html +='<label for="">Qualification <code>*</code></label>';
                                        ed_html +='<input type="text" class="form-control form-control-sm" id="degree" name="degree[]" value="'+data.c_edu_details[i].degree+'" placeholder="" required>';

                                    ed_html +='</div>';

                                    ed_html +='<div class="form-group">';

                                        ed_html +='<div class="row">';

                                            ed_html +='<div class="col-md-6">';
                                                ed_html +='<label for="">From <code>*</code></label>';
                                                ed_html +='<input type="text" class="form-control datepicker" name="edu_start_month[]" id="edu_start_month" required value="'+data.c_edu_details[i].edu_start_month+'-'+data.c_edu_details[i].edu_start_year+'" />';
                                            ed_html +='</div>';

                                            ed_html +='<div class="col-md-6">';
                                                ed_html +='<label for="">To <code>*</code></label>';
                                                ed_html +='<input type="text" class="form-control datepicker" name="edu_end_month[]" id="edu_end_month" required value="'+data.c_edu_details[i].edu_end_month+'-'+data.c_edu_details[i].edu_end_year+'" />';
                                            ed_html +='</div>';

                                        ed_html +='</div>';

                                    ed_html +='</div>';

                                ed_html +='</div>';

                                ed_html +='<div class="col-md-6">';

                                    ed_html +='<div class="form-group">';
                                        ed_html +='<label for="">School / University <code>*</code></label>';
                                        ed_html +='<input type="text" class="form-control form-control-sm"id="university" name="university[]" value="'+data.c_edu_details[i].university+'" placeholder="" required>';
                                    ed_html +='</div>';

                                    ed_html +='<div class="form-group">';

                                        ed_html +='<div class="row">';

                                            ed_html +='<div class="col-md-9">';


                                                if (data.c_edu_details[0].edu_certificate !=''){
                                                    ed_html +='<fieldset>';
                                                    ed_html +='<label for="">Certificate Upload </label>';

                                                    ed_html +='<div class="input-group">';

                                                        ed_html +='<input class="form-control form-control-sm" type="file" id="edu_certificate" name="edu_certificate[]">';
                                                            ed_html +='<a href="../../candidate_doc/'+data.c_edu_details[0].cdID+'/'+data.c_edu_details[0].edu_certificate+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                                            ed_html +='</div>';
                                                        ed_html +='</fieldset>';

                                                }else{
                                                    ed_html +='<fieldset>';
                                                    ed_html +='<label for="">Certificate Upload <code>*</code></label>';

                                                    ed_html +='<div class="input-group">';

                                                    ed_html +='<input class="form-control form-control-sm" type="file" id="edu_certificate" name="edu_certificate[]" required>';
                                                    // ed_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                    ed_html +='</div>';
                                                    ed_html +='</fieldset>';
                                                }

                                            ed_html +='</div>';

                                            ed_html +='<div class="col-md-3">';

                                                ed_html +='<label for=""></label>';
                                                ed_html +='<div class="input-group-btn">';
                                                    ed_html +='<button class="btn btn-sm btn-danger" type="button" onclick="remove_education_fields_exist('+"'"+data.c_edu_details[i].id+"'"+','+"'"+data.c_edu_details[i].cdID+"'"+');">';
                                                        ed_html +='<i class="bi bi-trash"> </i> Remove';
                                                    ed_html +='</button>';
                                                ed_html +='</div>';

                                            ed_html +='</div>';

                                        ed_html +='</div>';

                                    ed_html +='</div>';

                                ed_html +='</div>';

                            ed_html +='</div>';

                        }

                        ed_html +='<div class="row">';

                            ed_html +='<div id="education_fields">';
                            ed_html +='</div>';

                            ed_html +='<div class="form-group">';

                                ed_html +='<div class="row">';

                                    ed_html +='<div class="col-md-6">';

                                        ed_html +='<div class="input-group-btn">';
                                            ed_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="education_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                        ed_html +='</div>';

                                    ed_html +='</div>';

                                    ed_html +='<div class="col-md-6">';
                                    ed_html +='</div>';

                                ed_html +='</div>';

                            ed_html +='</div>';

                        ed_html +='</div>';

                        ed_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="edudocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button> ';
                        ed_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="edudocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';
                        // ed_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="edudocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        ed_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="eduPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    ed_html +='</form>';

                    $('#v-pills-edu').html(ed_html);
                }
                else{
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                    ];

                    var ed_html = '<form id="edudocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                        ed_html +='<div class="row">';

                            ed_html +='<input type="hidden" name="c_edu_row_id[]" id="c_edu_row_id">';

                            ed_html +='<div class="col-md-6">';

                                ed_html +='<div class="form-group">';
                                    ed_html +='<label for="">Qualification <code>*</code></label>';
                                    ed_html +='<input type="text" class="form-control form-control-sm" id="degree" name="degree[]" value="" placeholder=""required>';
                                ed_html +='</div>';

                                ed_html +='<div class="form-group">';

                                    ed_html +='<div class="row">';

                                        ed_html +='<div class="col-md-6">';
                                            ed_html +='<label for="">From <code>*</code></label>';
                                            var d = new Date();
                                            var cur_mon = d.getMonth();
                                            var cur_yr = d.getFullYear();

                                            ed_html +='<input type="text" class="form-control datepicker"name="edu_start_month[]" id="edu_start_month" required value="'+monthNames[d.getMonth()]+'-'+cur_yr+'" />';
                                        ed_html +='</div>';

                                        ed_html +='<div class="col-md-6">';
                                            ed_html +='<label for="">To <code>*</code></label>';
                                            ed_html +='<input type="text" class="form-control datepicker" name="edu_end_month[]" id="edu_end_month" required value="'+monthNames[d.getMonth()]+'-'+cur_yr+'" />';
                                        ed_html +='</div>';

                                    ed_html +='</div>';

                                ed_html +='</div>';

                            ed_html +='</div>';

                            ed_html +='<div class="col-md-6">';

                                ed_html +='<div class="form-group">';
                                    ed_html +='<label for="">School / University <code>*</code></label>';
                                    ed_html +='<input type="text" class="form-control form-control-sm" id="university" name="university[]" value="" placeholder="" required>';
                                ed_html +='</div>';

                                ed_html +='<div class="form-group">';

                                    ed_html +='<fieldset>';
                                        ed_html +='<label for="">Certificate Upload <code>*</code></label>';
                                        ed_html +='<div class="input-group">';
                                        ed_html +='<input class="form-control form-control-sm" type="file" id="edu_certificate" name="edu_certificate[]" required>';
                                        // ed_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                        ed_html +='</div>';
                                    ed_html +='</fieldset>';
                                ed_html +='</div>';

                            ed_html +='</div>';
                            ed_html +='<hr>';

                        ed_html +='</div>';

                        ed_html +='<div class="row">';

                            ed_html +='<div id="education_fields">';
                            ed_html +='</div>';

                            ed_html +='<div class="form-group">';

                                ed_html +='<div class="row">';

                                    ed_html +='<div class="col-md-6">';

                                        ed_html +='<div class="input-group-btn">';
                                            ed_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="education_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                        ed_html +='</div>';

                                    ed_html +='</div>';

                                    ed_html +='<div class="col-md-6">';
                                    ed_html +='</div>';

                                ed_html +='</div>';

                            ed_html +='</div>';

                        ed_html +='</div>';

                        ed_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="edudocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';
                        ed_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="edudocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        ed_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="eduPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';


                    ed_html +='</form>';

                    $('#v-pills-edu').html(ed_html);
                    get_datepicker();

                }

                if(data.c_exp_details.length !=0){

                    var exd_html = '<form id="expdocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                        for(let i = 0; i < data.c_exp_details.length; i++){

                            exd_html +='<div class="row removeclass_est'+data.c_exp_details[i].id+'">';

                                exd_html +='<input type="hidden" name="c_exp_row_id[]" id="c_exp_row_id" value="'+data.c_exp_details[i].id+'">';

                                exd_html +='<div class="col-md-6">';

                                    exd_html +='<div class="form-group">';

                                        exd_html +='<label for="">Job Title <code>*</code></label>';
                                        exd_html +='<input type="text" class="form-control form-control-sm " id="job_title" name="job_title[]" value="'+data.c_exp_details[i].job_title+'" placeholder="" required>';

                                    exd_html +='</div>';

                                    exd_html +='<div class="form-group">';

                                        exd_html +='<div class="row">';

                                            exd_html +='<div class="col-md-6">';
                                                exd_html +='<label for="">From <code>*</code></label>';
                                                exd_html +='<input type="text" class="form-control datepicker" name="exp_start_month[]" id="exp_start_month" required value="'+data.c_exp_details[i].exp_start_month+'-'+data.c_exp_details[i].exp_start_year+'" />';
                                            exd_html +='</div>';

                                            exd_html +='<div class="col-md-6">';
                                                exd_html +='<label for="">To <code>*</code></label>';
                                                exd_html +='<input type="text" class="form-control datepicker" name="exp_end_month[]" id="exp_end_month" required value="'+data.c_exp_details[i].exp_end_month+'-'+data.c_exp_details[i].exp_end_year+'" />';
                                            exd_html +='</div>';

                                        exd_html +='</div>';

                                    exd_html +='</div>';

                                exd_html +='</div>';

                                exd_html +='<div class="col-md-6">';

                                    exd_html +='<div class="form-group">';
                                        exd_html +='<label for="">Company Name <code>*</code></label>';
                                        exd_html +='<input type="text" class="form-control form-control-sm" id="company_name" name="company_name[]" value="'+data.c_exp_details[i].company_name+'" placeholder="" required>';
                                    exd_html +='</div>';

                                    exd_html +='<div class="form-group">';

                                        exd_html +='<div class="row">';

                                            exd_html +='<div class="col-md-9">';

                                                if (data.c_exp_details[0].certificate !=''){
                                                    exd_html +='<fieldset>';
                                                    exd_html +='<label for="">Appoinment Letter </label>';

                                                    exd_html +='<div class="input-group">';

                                                    exd_html +='<input class="form-control form-control-sm" type="file" id="exp_certificate" name="exp_certificate[]">';
                                                    exd_html +='<a href="../../candidate_doc/'+data.c_exp_details[0].cdID+'/'+data.c_exp_details[0].certificate+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                                    exd_html +='</div>';
                                                    exd_html +='</fieldset>';

                                                }else{
                                                    exd_html +='<fieldset>';
                                                    exd_html +='<label for="">Appoinment Letter <code>*</code></label>';

                                                    exd_html +='<div class="input-group">';

                                                    exd_html +='<input class="form-control form-control-sm" type="file" id="exp_certificate" name="exp_certificate[]" required>';
                                                    // exd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                    exd_html +='</div>';
                                                    exd_html +='</fieldset>';
                                                }

                                            exd_html +='</div>';

                                            exd_html +='<div class="col-md-3">';

                                                exd_html +='<label for=""></label>';
                                                exd_html +='<div class="input-group-btn">';
                                                    exd_html +='<button class="btn btn-sm btn-smn btn-danger" type="button" onclick="remove_experience_fields_exist('+"'"+data.c_exp_details[i].id+"'"+','+"'"+data.c_exp_details[i].cdID+"'"+');">';
                                                        exd_html +='<i class="bi bi-trash"> </i> Remove';
                                                    exd_html +='</button>';
                                                exd_html +='</div>';

                                            exd_html +='</div>';

                                        exd_html +='</div>';

                                    exd_html +='</div>';

                                exd_html +='</div>';

                            exd_html +='</div>';

                        }

                        exd_html +='<div class="row">';

                            exd_html +='<div id="experience_fields">';
                            exd_html +='</div>';

                            exd_html +='<div class="form-group">';

                                exd_html +='<div class="row">';

                                    exd_html +='<div class="col-md-6">';

                                        exd_html +='<div class="input-group-btn">';
                                            exd_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="experience_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                        exd_html +='</div>';

                                    exd_html +='</div>';

                                    exd_html +='<div class="col-md-6">';
                                    exd_html +='</div>';

                                exd_html +='</div>';

                            exd_html +='</div>';

                        exd_html +='</div>';

                        exd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="expdocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button> ';
                        exd_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="expdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        exd_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="expPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    exd_html +='</form>';

                    $('#v-pills-exp').html(exd_html);
                }
                else{
                    const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
                                        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
                                    ];

                    var exd_html = '<form id="expdocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                        exd_html +='<div class="row">';

                            exd_html +='<input type="hidden" name="c_exp_row_id[]" id="c_exp_row_id">';

                            exd_html +='<div class="col-md-6">';

                                exd_html +='<div class="form-group">';
                                    exd_html +='<label for="">Job Title <code>*</code></label>';
                                    exd_html +='<input type="text" class="form-control form-control-sm" id="job_title" name="job_title[]" value="" placeholder=""required>';
                                exd_html +='</div>';

                                exd_html +='<div class="form-group">';

                                    exd_html +='<div class="row">';

                                        exd_html +='<div class="col-md-6">';
                                            exd_html +='<label for="">From <code>*</code></label>';
                                            var d = new Date();
                                            var cur_mon = d.getMonth();
                                            var cur_yr = d.getFullYear();

                                            exd_html +='<input type="text" class="form-control datepicker"name="exp_start_month[]" id="exp_start_month" required value="'+monthNames[d.getMonth()]+'-'+cur_yr+'" />';
                                        exd_html +='</div>';

                                        exd_html +='<div class="col-md-6">';
                                            exd_html +='<label for="">To <code>*</code></label>';
                                            exd_html +='<input type="text" class="form-control datepicker" name="exp_end_month[]" id="exp_end_month" required value="'+monthNames[d.getMonth()]+'-'+cur_yr+'" />';
                                        exd_html +='</div>';

                                    exd_html +='</div>';

                                exd_html +='</div>';

                            exd_html +='</div>';

                            exd_html +='<div class="col-md-6">';

                                exd_html +='<div class="form-group">';
                                    exd_html +='<label for="">Company Name <code>*</code></label>';
                                    exd_html +='<input type="text" class="form-control form-control-sm" id="company_name" name="company_name[]" value="" placeholder="" required>';
                                exd_html +='</div>';

                                exd_html +='<div class="form-group">';
                                    exd_html +='<fieldset>';
                                        exd_html +='<label for="">Appoinment Letter <code>*</code></label>';
                                        exd_html +='<div class="input-group">';
                                        exd_html +='<input class="form-control form-control-sm" type="file" id="exp_certificate" name="exp_certificate[]" required>';
                                        // exd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                        exd_html +='</div>';
                                    exd_html +='</fieldset>';

                                exd_html +='</div>';

                            exd_html +='</div>';
                            exd_html +='<hr>';

                        exd_html +='</div>';

                        exd_html +='<div class="row">';

                            exd_html +='<div id="experience_fields">';
                            exd_html +='</div>';

                            exd_html +='<div class="form-group">';

                                exd_html +='<div class="row">';

                                    exd_html +='<div class="col-md-6">';

                                        exd_html +='<div class="input-group-btn">';
                                            exd_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="experience_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                        exd_html +='</div>';

                                    exd_html +='</div>';

                                    exd_html +='<div class="col-md-6">';
                                    exd_html +='</div>';

                                exd_html +='</div>';

                            exd_html +='</div>';

                        exd_html +='</div>';

                        exd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="expdocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';
                        exd_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="expdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        exd_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="expPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';


                    exd_html +='</form>';

                    $('#v-pills-exp').html(exd_html);
                    get_datepicker();

                }

                if(data.c_benefits_details.length !=0){
                    var cbd_html = '<form id="benefitdocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                    cbd_html +='<small class="text-muted">Eg.<i>(Latest 3 Months Payslips | Form 16 of Previous Years | Bank Statement disclosing Credits)</i></small>';

                    for(let i = 0; i < data.c_benefits_details.length; i++){

                        cbd_html +='<div class="row removeclass_bst'+data.c_benefits_details[i].id+'">';
                        cbd_html +='<input type="hidden" name="c_benefits_row_id[]" id="c_benefits_row_id" value="'+data.c_benefits_details[i].id+'">';

                            cbd_html +='<div class="col-md-6">';
                                cbd_html +='<div class="form-group">';
                                    cbd_html +='<label for="">Document Type <code>*</code></label>';
                                    cbd_html +='<input type="text" name="doc_type[]" id="doc_type" value="'+data.c_benefits_details[i].doc_type+'" class="form-control form-control-sm exp_ipt" required>';
                                cbd_html +='</div>';
                            cbd_html +='</div>';

                            cbd_html +='<div class="col-md-6">';
                                cbd_html +='<div class="row">';
                                    cbd_html +='<div class="col-md-9">';
                                        cbd_html +='<div class="form-group">';
                                            cbd_html +='<label for=""></label>';

                                            if (data.c_benefits_details[0].doc_filename !=''){
                                                cbd_html +='<fieldset>';
                                                cbd_html +='<div class="input-group">';
                                                cbd_html +='<input class="form-control form-control-sm" type="file" id="doc_type_file" name="doc_type_file[]">';
                                                cbd_html +='<a href="../../candidate_doc/'+data.c_benefits_details[0].cdID+'/'+data.c_benefits_details[0].doc_filename+'" target="_blank"><button class="btn btn-sm btn-primary" type="button" id="">Preview</button></a>';
                                                cbd_html +='</div>';
                                                cbd_html +='</fieldset>';

                                            }else{
                                                cbd_html +='<fieldset>';
                                                cbd_html +='<div class="input-group">';
                                                cbd_html +='<input class="form-control form-control-sm" type="file" id="doc_type_file" name="doc_type_file[]" required>';
                                                // cbd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                                cbd_html +='</div>';
                                                cbd_html +='</fieldset>';
                                            }

                                        cbd_html +='</div>';
                                    cbd_html +='</div>';

                                    cbd_html +='<div class="col-md-3">';
                                        cbd_html +='<div class="form-group" style="margin-bottom: unset;">';
                                            cbd_html +='<label for=""></label>';
                                            cbd_html +='<div class="input-group-btn">';
                                                cbd_html +='<button class="btn btn-sm btn-danger" type="button" onclick="remove_compensation_fields_exist();"> <i class="bi bi-trash"></i>Delete</button>';
                                            cbd_html +='</div>';
                                        cbd_html +='</div>';
                                    cbd_html +='</div>';

                                cbd_html +='</div>';
                            cbd_html +='</div>';

                        cbd_html +='</div>';

                    }

                    cbd_html +='<div class="row">';

                        cbd_html +='<div id="compensation_fields">';
                        cbd_html +='</div><br>';

                        cbd_html +='<div class="form-group">';

                            cbd_html +='<div class="row">';

                                cbd_html +='<div class="col-md-6">';

                                    cbd_html +='<div class="input-group-btn">';
                                        cbd_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="compensation_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                    cbd_html +='</div>';

                                cbd_html +='</div>';

                                cbd_html +='<div class="col-md-6">';
                                cbd_html +='</div>';

                            cbd_html +='</div>';

                        cbd_html +='</div>';

                    cbd_html +='</div>';

                        cbd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="benefitdocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button> ';
                        cbd_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="benefitdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        cbd_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="benefitPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    cbd_html +='</form>';

                    $('#v-pills-compensation').html(cbd_html);

                }else{
                    var cbd_html = '<form id="benefitdocSubmit" method="post" action="javascript:void(0)" enctype="multipart/form-data">';
                    cbd_html +='<input type="hidden" name="c_benefits_row_id[]" id="c_benefits_row_id" value="">';

                    cbd_html +='<div class="row">';
                            cbd_html +='<small class="text-muted">Eg.<i>(Latest 3 Months Payslips | Form 16 of Previous Years | Bank Statement disclosing Credits)</i></small>';
                            cbd_html +='<div class="col-md-6">';
                                cbd_html +='<div class="form-group">';
                                    cbd_html +='<label for="">Document Type <code>*</code></label>';
                                    cbd_html +='<input type="text" name="doc_type[]" id="doc_type" class="form-control form-control-sm exp_ipt" required>';
                                cbd_html +='</div>';
                            cbd_html +='</div>';

                            cbd_html +='<div class="col-md-6">';
                                // cbd_html +='<div class="row">';
                                    // cbd_html +='<div class="col-md-9">';
                                        cbd_html +='<div class="form-group">';
                                            cbd_html +='<fieldset>';
                                            cbd_html +='<label for=""></label>';
                                            cbd_html +='<div class="input-group">';
                                            cbd_html +='<input class="form-control form-control-sm" type="file" id="doc_type_file" name="doc_type_file[]" required>';
                                            // cbd_html +='<button class="btn btn-sm btn-primary" type="button" id="">Preview not found</button>';
                                            cbd_html +='</div>';
                                            cbd_html +='</fieldset>';
                                        cbd_html +='</div>';
                                    // cbd_html +='</div>';

                                    // cbd_html +='<div class="col-md-3">';
                                    //     cbd_html +='<div class="form-group">';
                                    //         cbd_html +='<label for=""></label>';
                                    //         cbd_html +='<div class="input-group-btn">';
                                    //             cbd_html +='<button class="btn btn-sm btn-success" type="button" onclick="compensation_fields();"> <i class="bi bi-plus"></i></button>';
                                    //         cbd_html +='</div>';
                                    //     cbd_html +='</div>';
                                    // cbd_html +='</div>';

                                // cbd_html +='</div>';
                            cbd_html +='</div>';


                        cbd_html +='</div>';

                        cbd_html +='<div class="row">';

                            cbd_html +='<div id="compensation_fields">';
                            cbd_html +='</div><br>';

                            cbd_html +='<div class="form-group">';

                                cbd_html +='<div class="row">';

                                    cbd_html +='<div class="col-md-6">';

                                        cbd_html +='<div class="input-group-btn">';
                                            cbd_html +='<button class="btn btn-sm btn-smn btn-success nv_cl" type="button" onclick="compensation_fields();"><i class="bi bi-plus"></i> Add More</button>';
                                        cbd_html +='</div>';

                                    cbd_html +='</div>';

                                    cbd_html +='<div class="col-md-6">';
                                    cbd_html +='</div>';

                                cbd_html +='</div>';

                            cbd_html +='</div>';

                        cbd_html +='</div>';

                        cbd_html +='<button class="btn btn-sm btn-smn btn-info nv_cl" style="float:right;" type="submit" id="benefitdocSubmitbtn">Save & Next<i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button> ';
                        cbd_html +='<button class="btn btn-sm btn-smn btn-info v_cl" style="float:right;" type="button" id="benefitdocNextbtn">Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i></button>';

                        cbd_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;margin-right: 5px;" type="button" id="benefitPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    cbd_html +='</form>';
                    $('#v-pills-compensation').html(cbd_html);

                }


                if(data.c_basic_details[0].c_doc_status == 'Verified'){
                    var nv_cl_count = document.getElementsByClassName("nv_cl");
                    for (var i=0;i<nv_cl_count.length;i+=1){
                        nv_cl_count[i].style.display = 'none';
                    }
                }else{
                    var v_cl_count = document.getElementsByClassName("v_cl");
                    for (var i=0;i<v_cl_count.length;i+=1){
                        v_cl_count[i].style.display = 'none';
                    }
                }

                if(data.c_basic_details[0].offer_rel_status == 1){

                    document.getElementById('v-pills-orel-tab').style.display = 'block';

                    var or_html = '<a href="../../'+data.c_basic_details[0].offer_letter_filename+'" target="_blank"><button type="button" class="btn btn-sm btn-smn btn-primary">Preview Offer Letter</button></a>';
                    or_html += '<br><br>';
                    // or_html += '<h4>Terms & Conditions</h4>';
                    // or_html += '<ul>';
                    //     or_html += '<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>';
                    //     or_html += '<li>Aliquam tincidunt mauris eu risus.</li>';
                    //     or_html += '<li>Vestibulum auctor dapibus neque.</li>';
                    //     or_html += '<li>Nunc dignissim risus id metus.</li>';
                    // or_html += '</ul>';
                    // or_html += '<div class="row">';
                    //     or_html += '<div class="col-md-8 offset-md-2">';
                    //         or_html += '<div class="form-check">';
                    //             or_html += '<div class="checkbox">';
                    //                 or_html += '<input type="checkbox" id="checkbox1" class="form-check-input" name="confirm_box" checked="" required>';
                    //                 or_html += '<label for="checkbox1">I acknowledge that i have read and agree to the above terms and conditions</label>';
                    //             or_html += '</div>';
                    //         or_html += '</div>';
                    //     or_html += '</div>';
                    // or_html += '</div>';

                    or_html += '<div class="row">';
                        or_html += '<div class="col-md-4 offset-md-4">';
                            or_html += '<div class="form-group">';
                                or_html += '<div class="row">';
                                    or_html += '<div class="col-md-6">';
                                        or_html += '<div class="form-check form-check-success">';
                                            or_html += '<input class="form-check-input" type="radio" name="offer_action" id="offer_st1" checked="" value="2">';
                                            or_html += '<label class="form-check-label" for="offer_st1"> Accept </label>';
                                        or_html += '</div>';
                                    or_html += '</div>';
                                    or_html += '<div class="col-md-6">';
                                        or_html += '<div class="form-check form-check-danger">';
                                            or_html += '<input class="form-check-input" onclick = "click_reject();" type="radio" name="offer_action" id="offer_st1" value="3">';
                                            or_html += '<label class="form-check-label" for="offer_st2"> Reject </label>';
                                        or_html += '</div>';
                                    or_html += '</div>';
                                or_html += '</div>';
                            or_html += '</div>';
                        or_html += '</div>';
                    or_html += '</div>';


                    or_html += '<div class="row">';
                        or_html += '<div class="col-md-6 offset-md-5">';
                            or_html += '<div class="form-group">';
                                or_html +='<div class="col-md-6">';
                                or_html +='<div class="form-group" id="rr" style="display:none">';
                                or_html +='<label for="">Reason for reject</code></label>';
                                or_html +='<input type="text" name="reject_reason" id="reject_reason"  class="form-control form-control-sm exp_ipt">';
                                or_html +='</div>';
                                or_html +='</div>';
                                or_html += '<button class="btn btn-sm btn-smn btn-info" type="button" id="offerFormbtn">Submit</button>';
                            or_html += '</div>';
                        or_html += '</div>';
                    or_html += '</div>';
                    or_html +='<button class="btn btn-sm btn-smn btn-info" style="float:right;" type="button" id="offerPreviousbtn"><i class="bi bi-arrow-left-circle" aria-hidden="true"></i> Previous </button>';

                    $('#v-pills-orel').html(or_html);


                    // document.getElementsByClassName('ol_cl').style.display = 'block';

                }
                else if(data.c_basic_details[0].offer_rel_status == 2){
                    document.getElementById('v-pills-orel-tab').style.display = 'block';

                    var or_html = '<a href="../../'+data.c_basic_details[0].offer_letter_filename+'" target="_blank"><button type="button" class="btn btn-sm btn-smn btn-primary">Preview Offer Letter</button></a>';
                    or_html += '<br><br>';
                    // or_html += '<h4>Terms & Conditions</h4>';
                    // or_html += '<ul>';
                    //     or_html += '<li>Lorem ipsum dolor sit amet, consectetuer adipiscing elit.</li>';
                    //     or_html += '<li>Aliquam tincidunt mauris eu risus.</li>';
                    //     or_html += '<li>Vestibulum auctor dapibus neque.</li>';
                    //     or_html += '<li>Nunc dignissim risus id metus.</li>';
                    // or_html += '</ul>';
                    // or_html += '<div class="row">';
                    //     or_html += '<div class="col-md-8 offset-md-2">';
                    //         or_html += '<div class="form-check">';
                    //             or_html += '<div class="checkbox">';
                    //                 or_html += '<input type="checkbox" id="checkbox1" class="form-check-input" name="confirm_box" checked="" required>';
                    //                 or_html += '<label for="checkbox1">I acknowledge that i have read and agree to the above terms and conditions</label>';
                    //             or_html += '</div>';
                    //         or_html += '</div>';
                    //     or_html += '</div>';
                    // // or_html += '</div>';
                    $('#v-pills-orel').html(or_html);
                }



                else{

                    var ol_cl_count = document.getElementsByClassName("ol_cl");
                    for (var i=0;i<ol_cl_count.length;i+=1){
                        ol_cl_count[i].style.display = 'none';
                    }

                    document.getElementById('v-pills-orel-tab').style.display = 'none';

                }

                if(data.c_basic_details[0].candidate_type =='Fresher'){
                    document.getElementById('v-pills-exp-tab').style.display = 'none';
                    document.getElementById('v-pills-compensation-tab').style.display = 'none';
                    $('.exp_ipt').prop("required",false);
                }

            }else{
                console.log('else');

            }

        }
    });

}

$(document).on('submit', '#basicdocSubmit', (function(e) {

    var candidate_id = $('#candidate_id').val();

    //$("#basicdocSubmitbtn").attr("disabled", true);
   // $('#basicdocSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    e.preventDefault();
    var formData = new FormData(this);
    formData.append('candidate_id', candidate_id);
// // Instantiating easyHTTP
//                 const http = new easyHTTP;

// // Create Data
//                     // const data = {
//                     // title: 'Custom HTTP Post',
//                     // body: 'This is a custom post data'
//                     // };
//                     const data = {
//                     'username': 'Custom HTTP Post',
//                     'm_name': 'Custom HTTP Post',
//                     'l_name': 'Custom HTTP Post',
//                     'blood_grp': 'Custom HTTP Post',
//                     'gender': 'Custom HTTP Post',
//                     'dob': 'Custom HTTP Post',
//                     }
// // Post prototype method(url, data,
// // response text)
//                         http.post(
//                         'http://127.0.0.1:8080/test',
//                         data,
//                         function(err, post) {
//                             if(err) {
//                                 console.log(err);
//                             } else {
//                                 // Parsing string data to object
//                                 // let data = JSON.parse(posts);
//                                 console.log(post);
//                             }
//                         });


    // $.ajax({
    //     url: "http://127.0.0.1:8080/test",
    //     type: 'POST',
    //     data:{"test":'test'},
    //     dataType: 'json',
    //     cors: true ,
    //     contentType:'text/json',
    //     secure: true,
    //     headers: {
    //       'Access-Control-Allow-Origin': '*',
    //     },
    //     beforeSend: function (xhr) {
    //       xhr.setRequestHeader ("Authorization", "Basic " + btoa(""));
    //     },
    //     success: function (response) {
    //         var resp = JSON.parse(response)
    //         alert(resp.status);
    //     },
    //     error: function (xhr, status) {
    //         alert("error");
    //     }

    // })


    $.ajax({
        url:  budgie_link + "add_skill_set",
        type: "POST",
        cors: true ,
        data:formData,
        dataType: "json",
        cache:false,
        contentType: false,
       processData: false,
        success: function (data) {
           if(data.response == "success"){
           console.log("outer data successfull")
           }
           else{
            console.log("error");
           }
        },


 })

    $.ajax({
        type:'POST',
        url: candidate_basic_document_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#basicdocSubmitbtn").removeAttr("disabled");
        $('#basicdocSubmitbtn').html('Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>');

        if(data =='success'){
            Toastify({
                    text: "Basic Details Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        $("#basicdocSubmit")[0].reset();
                        document.getElementById('v-pills-edu-tab').click(); // Works!
                        $("#v-pills-cbd").load(location.href + " #v-pills-cbd");

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
                    // location.reload();
                }, 2000);


        }

        },
    });


}));

$(document).on('submit', '#edudocSubmit', (function() {

    var candidate_id = $('#candidate_id').val();
    var rfh_no = $('#rfh_no').val();
    var hepl_recruitment_ref_number = $('#hepl_recruitment_ref_number').val();
    var candidate_type = $('#candidate_type_cmn').val();
    $("#edudocSubmitbtn").attr("disabled", true);
    $('#edudocSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    // e.preventDefault();
    var formData = new FormData(this);
    formData.append('candidate_id', candidate_id);
    formData.append('rfh_no', rfh_no);
    formData.append('hepl_recruitment_ref_number', hepl_recruitment_ref_number);

    $.ajax({
        url:  budgie_link + "education_information_add",
        type: "POST",
        cors: true ,
        data:formData,
        dataType: "json",
        cache:false,
        contentType: false,
       processData: false,
        success: function (data) {
           if(data.response == "success"){
           console.log("Education data successfully sent")
           }
           else{
            console.log("error");
           }
        },


 })

    $.ajax({
        type:'POST',
        url: candidate_edu_document_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#edudocSubmitbtn").removeAttr("disabled");
        $('#edudocSubmitbtn').html('Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>');

        if(data =='success'){
            Toastify({
                    text: "Education Details Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        $("#edudocSubmit")[0].reset();
                        console.log(candidate_type);
                        if(candidate_type =='Fresher'){
                            document.getElementById('v-pills-proofdoc-tab').click(); // Works!

                        }else{
                            document.getElementById('v-pills-exp-tab').click(); // Works!

                        }
                        // $("#v-pills-edu").load(location.href + " #v-pills-edu");

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
                    // location.reload();
                }, 2000);


        }

        },
    });
}));

$(document).on('submit', '#expdocSubmit', (function() {

    // $('#edudocSubmitbtn').on('submit',(function(e) {
    var candidate_id = $('#candidate_id').val();
    var rfh_no = $('#rfh_no').val();
    var hepl_recruitment_ref_number = $('#hepl_recruitment_ref_number').val();
    $("#expdocSubmitbtn").attr("disabled", true);
    $('#expdocSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    // e.preventDefault();
    var formData = new FormData(this);
    formData.append('candidate_id', candidate_id);
    formData.append('rfh_no', rfh_no);
    formData.append('hepl_recruitment_ref_number', hepl_recruitment_ref_number);
    $.ajax({
        url:  budgie_link + "experience_information",
        type: "POST",
        cors: true ,
        data:formData,
        dataType: "json",
        cache:false,
        contentType: false,
       processData: false,
        success: function (data) {
           if(data.response == "success"){
           console.log("Experience data successfully sent")
           }
           else{
            console.log("error");
           }
        },


 })
    $.ajax({
        type:'POST',
        url: candidate_exp_document_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#expdocSubmitbtn").removeAttr("disabled");
        $('#expdocSubmitbtn').html('Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>');

        if(data =='success'){
            Toastify({
                    text: "Experience Details Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        $("#expdocSubmit")[0].reset();
                        document.getElementById('v-pills-compensation-tab').click(); // Works!

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

                    // location.reload();
                }, 2000);


        }

        },
    });
}));

$(document).on('submit', '#benefitdocSubmit', (function() {

    // $('#edudocSubmitbtn').on('submit',(function(e) {
    var candidate_id = $('#candidate_id').val();
    var rfh_no = $('#rfh_no').val();
    var hepl_recruitment_ref_number = $('#hepl_recruitment_ref_number').val();

    $("#benefitdocSubmitbtn").attr("disabled", true);
    $('#benefitdocSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    // e.preventDefault();
    var formData = new FormData(this);
    formData.append('candidate_id', candidate_id);
    formData.append('rfh_no', rfh_no);
    formData.append('hepl_recruitment_ref_number', hepl_recruitment_ref_number);

    $.ajax({
        type:'POST',
        url: candidate_benefit_document_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#benefitdocSubmitbtn").removeAttr("disabled");
        $('#benefitdocSubmitbtn').html('Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>');

        if(data =='success'){
            Toastify({
                    text: "Compensation Details Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        $("#benefitdocSubmit")[0].reset();
                        document.getElementById('v-pills-proofdoc-tab').click(); // Works!

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

                    // location.reload();
                }, 2000);


        }

        },
    });
}));

$(document).on('submit', '#prodocSubmit', (function() {

    // $('#edudocSubmitbtn').on('submit',(function(e) {
    var candidate_id = $('#candidate_id').val();
    var rfh_no = $('#rfh_no').val();
    var hepl_recruitment_ref_number = $('#hepl_recruitment_ref_number').val();

    var created_by = $('#created_by').val();
   // alert(created_by);
    $("#prodocSubmitbtn").attr("disabled", true);
    $('#prodocSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    // e.preventDefault();
    var formData = new FormData(this);
    formData.append('candidate_id', candidate_id);
    formData.append('created_by', created_by);
    formData.append('rfh_no', rfh_no);
    formData.append('hepl_recruitment_ref_number', hepl_recruitment_ref_number);
    $.ajax({
        url:  budgie_link + "other_documents",
        type: "POST",
        cors: true ,
        data:formData,
        dataType: "json",
        cache:false,
        contentType: false,
       processData: false,
        success: function (data) {
           if(data.response == "success"){
           console.log("proof data successfully sent")
           }
           else{
            console.log("error");
           }
        },


 })
    $.ajax({
        type:'POST',
        url: candidate_proof_document_link,
        data:formData,
        cache:false,
        contentType: false,
        processData: false,

        success:function(data){
        $("#prodocSubmitbtn").removeAttr("disabled");
        $('#prodocSubmitbtn').html('Save & Next <i class="bi bi-arrow-right-circle" aria-hidden="true"></i>');

        if(data =='success'){
            Toastify({
                    text: "Proof Document Updated Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        $("#prodocSubmitbtn")[0].reset();

                        document.getElementById('v-pills-cbd-tab').click(); // Works!
                        location.reload();

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

                    // location.reload();
                }, 2000);


        }

        },
    });
}));

$('#docSubmitaa').on('submit',(function(e) {

    $("#docSubmitbtn").attr("disabled", true);
    $('#docSubmitbtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');

    e.preventDefault();
    var formData = new FormData(this);

    // var action_for_the_day = $('#action_for_the_day_put').val();
    // formData.append('action_for_the_day', action_for_the_day);

        $.ajax({
            type:'POST',
            url: candidate_document_link,
            data:formData,
            cache:false,
            contentType: false,
            processData: false,

            success:function(data){
            $("#docSubmitbtn").removeAttr("disabled");
            $('#docSubmitbtn').html('Submit');

            if(data =='success'){
                Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            location.reload();
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
                        location.reload();
                    }, 2000);


            }
                // if($.isEmptyObject(data.error)){
                //     Toastify({
                //         text: "Inserted Successfully",
                //         duration: 3000,
                //         close:true,
                //         backgroundColor: "#4fbe87",
                //     }).showToast();

                // }
                // else {
                //     Toastify({
                //         text: "Request Failed..! Try Again",
                //         duration: 3000,
                //         close:true,
                //         backgroundColor: "#f3616d",
                //     }).showToast();

                // }
                // setTimeout(
                //     function() {
                //         if(current_tab_title == 'New positions allocated for the day'){
                //             $('#new_position-tab').click();
                //             $('#table1').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Old positions'){
                //             $('#old_position-tab').click();
                //             $('#table_op').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Offers released'){
                //             $('#offer_released_ldj-tab').click();
                //             $('#offer_released_tba').DataTable().ajax.reload(null, false);
                //         }else if(current_tab_title == 'Candidate Onboarded'){
                //             $('#candidate_onboarded_ldj-tab').click();
                //             $('#candidate_onboarded_ldj').DataTable().ajax.reload(null, false);
                //         }else{
                //             $('#inactive-tab').click();
                //             $('#table_inactive').DataTable().ajax.reload(null, false);
                //         }

                //         $("#show_offer_released_ldj").css({ "display" :"none" });
                //         $("#show_cv_process").css({ "display" :"none" });
                //         $("#show_cv_upload").css({ "display" :"none" });
                //         $("#show_offer_released_div").css({ "display" :"none" });
                //         $("#show_cv_uploaded_div").css({ "display" :"none" });


                //         $("#btnactionPc").css({ "display" :"inline-flex" });
                //         $("#profile_count_st").css({ "display" :"block" });

                //         $('#profile_count').val("");
                //         $("#profile_count").removeClass("is-valid");

                //         // location.reload();
                // }, 2000);

            },
        });
    }));

$(document).on('click', '#offerFormbtn', function(e) {

// $("#offerFormbtn").on('click', function() {


    var cdID = $('#candidate_id').val();
    var rfh_no = $('#rfh_no').val();
    var hepl_recruitment_ref_number = $('#hepl_recruitment_ref_number').val();
    var candidate_email = $('#candidate_email').val();
    var created_by = $('#created_by').val();

    var cd_or_status = $("input[name='offer_action']:checked").val();
    var myvar = $("input[name='confirm_box']:checked").is(':checked');

    if(cd_or_status ==2){
        var resp = confirm("Do you want to Accept this Offer?");
         if(resp === true){
            $('#offerFormbtn').text('Processing');
            $('#offerFormbtn').prop('disabled', true);
            $.ajax({
                type: "POST",
                url: offer_response_candidate_link,
                data: {"cdID":cdID,"created_by":created_by,"candidate_email":candidate_email,"cd_or_status":cd_or_status,"rfh_no":rfh_no,"hepl_recruitment_ref_number":hepl_recruitment_ref_number, },

                success: function (data) {

                    if(data.response =='Updated'){
                        Toastify({
                                text: "Updated Successfully",
                                duration: 3000,
                                close:true,
                                backgroundColor: "#4fbe87",
                            }).showToast();

                            setTimeout(
                                function() {
                                    $('#offerFormbtn').text('Submit');
                                    $('#offerFormbtn').removeAttr('disabled');

                                    location.reload();
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
                                $('#offerFormbtn').text('Submit');
                                    $('#offerFormbtn').removeAttr('disabled');
                                location.reload();
                            }, 2000);


                    }
                }
            });

         }
    }
    else if(cd_or_status ==3){

                var reason = $('#reject_reason').val();
                 if(reason != ""){
                    $('#offerFormbtn').text('Processing');
                    $('#offerFormbtn').prop('disabled', true);
                    $.ajax({
                        type: "POST",
                        url: offer_reject_candidate_link,
                        data: {"cdID":cdID,"created_by":created_by,"rfh_no":rfh_no ,"remark":reason},

                        success: function (data) {

                            if(data.response =='Updated'){
                                Toastify({
                                        text: "Updated Successfully",
                                        duration: 3000,
                                        close:true,
                                        backgroundColor: "#4fbe87",
                                    }).showToast();

                                    setTimeout(
                                        function() {
                                            $('#offerFormbtn').text('Submit');
                                            $('#offerFormbtn').removeAttr('disabled');

                                            location.reload();
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
                                        $('#offerFormbtn').text('Submit');
                                            $('#offerFormbtn').removeAttr('disabled');
                                        location.reload();
                                    }, 2000);


                            }
                        }
                    });

                 }

                // else if(reason == ){
                //     alert("Please enter the reason...!!");
                //     return false;
                // }


        }





});

function click_reject(){
    var resp = confirm("Do you want to reject this Offer?");
           if(resp == true){
                 $('#rr').css("display","block");
           }
}
$(document).on('keyup', '#candidate_contactno', (function() {
    var mobNum = $(this).val();
    var filter = /^\d*(?:\.\d{1,2})?$/;

      //if (filter.test(mobNum)) {
        if(mobNum.length==10){
             // alert("valid");
              $("#succ_msg").css("display","block");
              $("#err_msg").css("display","none");
              $('#basicdocSubmitbtn').prop("disabled",false);
              $('#basicNextbtn').prop("disabled",false);
         } else {
          //  alert('Please put 10  digit mobile number');
           $("#err_msg").css("display","block");
           $("#succ_msg").css("display","none");
           $('#basicdocSubmitbtn').prop("disabled",true);
           $('#basicNextbtn').prop("disabled",true);
          // $("#mobile-valid").addClass("hidden");
           // return false;
          }


}));
//function agedifference(firstDate, secondDate){
	$(document).on('change', '#dob', function(e) {
                var dob= $(this).val();
                dob = new Date(dob);
                var today = new Date();
                var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                $('#age').val(age+' years');

});
