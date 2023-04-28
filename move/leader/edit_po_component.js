$(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    }); 
     
}); 

$(document).ready(function() {

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var cdID = url.searchParams.get("cdID");
    var rfh_no = url.searchParams.get("rfh_no");

    $('#rfh_no').val(rfh_no);
    $('#cdID').val(cdID);
    get_po_components(cdID,rfh_no);
    change_submit_button();
});

function get_po_components(cdID,rfh_no){

    $.ajax({
        type: "POST",
        url: get_po_components_link,
        data: {"cdID":cdID, "rfh_no":rfh_no,},
        success: function (data) {
            
            var html ='';

            var headcount_cost = 0;
            if(data.po_details_result.length !=0){
                
                var po_details_result = JSON.parse(data.po_details_result[0].po_detail)
                var po_description_result = JSON.parse(data.po_details_result[0].po_description)
                var po_amount_result = JSON.parse(data.po_details_result[0].po_amount)

                var sno =1;
                for (let index = 0; index < po_details_result.length; index++) {
                    
                    if(po_details_result[index] =='Type of Engagement'){
                        html +='<tr>';
                            html +='<td class="sno_cl">'+sno+'</td>';
                            html +='<td>'+po_details_result[index]+'*';
                            html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                            html +='</td>';
                            html +='<td>';
                                html +='<select name="po_description[]" id="type_of_engage" required class="form-control">';
                                    html +='<option value="">Choose</option>';
                                    html +='<option value="Regular" selected>Regular</option>';
                                    html +='<option value="Contractual">Contractual</option>';
                                    html +='<option value="Part Time">Part Time</option>';
                                    html +='<option value="Trainee">Trainee</option>';
                                    html +='<option value="Intern">Intern</option>';
                                    html +='<option value="FTE">FTE</option>';
                                html +='</select>';
                            html +='</td>';
                            html +='<td><input type="hidden" name="po_amount[]" value="'+po_amount_result[index]+'" class="form-control" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text"  placeholder ="Enter Your Remark" name="remark[]"  class="form-control""></td></div>';

                            html +='</tr>';
                    }
                    else if (po_details_result[index] =='Total Purchase Order Value (Rs)') {
                        html +='<tr>';
                        html +='<td class="sno_cl">'+sno+'</td>';
                        html +='<td>'+po_details_result[index]+'*';
                        html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                        html +='</td>';
                        if(po_description_result[index] ==null){
                            html +='<td><input type="text" name="po_description[]" value="" class="form-control"></td>';
                        }
                        else{
                            html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" required class="form-control"></td>';
                        }
                        html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="purchase_order" value="'+po_amount_result[index]+'" class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                        html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount_month[]"  id="purchase_order_month" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                        html +='<td><input type="text"  placeholder ="Enter Your Remark" name="remark[]"  class="form-control""></td></div>';

                        html +='</tr>'; 
                    } 
                    else if (po_details_result[index] =='HEPL Business Services Charges (Rs)') {
                        html +='<tr>';
                            html +='<td class="sno_cl">'+sno+'</td>';
                            html +='<td>'+po_details_result[index]+'*';
                            html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                            html +='<input type="hidden" name="hepl_bs_charge_percent" id="hepl_bs_charge_percent" value="'+Number(data.po_default_values[0].hepl_bs_charge_percent)+'" class="form-control">';
                            html +='</td>';
                            if(po_description_result[index] ==null){
                                html +='<td><input type="text" name="po_description[]" value="" class="form-control"></td>';
                            }
                            else{
                                html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" required class="form-control"></td>';
                            }
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="hepl_bsc" value="'+po_amount_result[index]+'" class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount_month[]" id="hepl_bsc_month" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text"  placeholder ="Enter Your Remark" name="remark[]"  class="form-control""></td></div>';

                            html +='</tr>';
                    }
                    else if (po_details_result[index] =='Total Headcount Cost (Rs)') {
                        html +='<tr>';
                            html +='<td class="sno_cl">'+sno+'</td>';
                            html +='<td>'+po_details_result[index]+'*';
                            html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                            html +='</td>';
                            if(po_description_result[index] ==null){
                                html +='<td><input type="text" name="po_description[]" value="" class="form-control"></td>';
                            }
                            else{
                                html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" required class="form-control"></td>';
                            }
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="total_headcount" class="form-control po_amnt_cell" value="'+po_amount_result[index]+'" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount_month[]"  id="total_headcount_month" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text"  style="display:block" name="remark[]" class="form-control"></td></div>';

                            html +='</tr>';
                    }
                    else if (po_details_result[index] =='One Time Recruitment Fee') {
                        html +='<tr>';
                            html +='<td class="sno_cl"></td>';
                            html +='<td>'+po_details_result[index]+'*';
                            html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                            html +='</td>';
                            if(po_description_result[index] ==null){
                                html +='<td><input type="text" name="po_description[]" value="" class="form-control"></td>';
                            }
                            else{
                                html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" required class="form-control"></td>';
                            }
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="total_headcount" class="form-control po_amnt_cell" value="'+po_amount_result[index]+'" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text" name="remark[]" class="form-control"></td>';

                            html +='</tr>';
                    }
                    else if(po_details_result[index] =='Current/Last drawn CTC- Annual (Rs)'){
                        html +='<tr>';
                            html +='<td class="sno_cl">'+sno+'</td>';
                            html +='<td>'+po_details_result[index]+'';
                            html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                            html +='</td>';
                            html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" class="form-control"></td>';
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount[]" value="'+po_amount_result[index]+'" readonly class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount_month[]" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text"  style="display:block" name="remark[]" class="form-control"></td></div>';
                            html +='</tr>';
                    }
                    else{
                        if(sno ==17){
                            html +='<tr id="add_comp_below">';

                        }else{
                            html +='<tr>';

                        }
                        html +='<td class="sno_cl">'+sno+'</td>';
                        html +='<td>'+po_details_result[index]+'*';
                        html +='<input type="hidden" name="po_details[]" value="'+po_details_result[index]+'" class="form-control">';
                        html +='</td>';
                        if(po_description_result[index] ==null){
                            html +='<td><input type="text" name="po_description[]" value="" class="form-control"></td>';
                        }
                        else{
                            html +='<td><input type="text" name="po_description[]" value="'+po_description_result[index]+'" required class="form-control"></td>';
                        }

                        if(sno>=9){
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount[]" value="'+po_amount_result[index]+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount_month[]" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text" style="display:block" name="remark[]" placeholder ="Enter Your Remark" class="form-control""></td></div>';

                        }else{
                            html +='<td><input type="hidden" name="po_amount[]" value="'+po_amount_result[index]+'" class="form-control" onkeypress="num_validate(event)"></td></div>';
                            html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="'+Math.round((po_amount_result[index])/12)+'" class="form-control p_month" onkeypress="num_validate(event)"></td></div>';

                            html +='<td><input type="text"  style="display:block" placeholder ="Enter Your Remark" name="remark[]"  class="form-control""></td></div>';

                        }
                        html +='</tr>';
                    }

                    sno++;

                }
                
                $('#add_po_tbody').html(html);
                $('#type_of_engage').val(po_description_result[7]);
            }else{

                var calculate_pf_wages = Number(data.ctc_details[0].basic_pm) + Number(data.ctc_details[0].medi_al_pm) + Number(data.ctc_details[0].conv_pm) + Number(data.ctc_details[0].spl_al_pm);
                
                var pf_admin_charge = Math.round(calculate_pf_wages * Number(data.po_default_values[0].pf_admin_charge_percent));
                
                html +='<tr>';
                    html +='<td class="sno_cl">1</td>';
                    html +='<td>Client Name*';
                    html +='<input type="hidden" name="po_details[]" value="Client Name" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" required class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">2</td>';
                    html +='<td>Position Title*';
                    html +='<input type="hidden" name="po_details[]" value="Position Title" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" required value="'+data.rfh_form_details[0].position_title+'" class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">3</td>';
                    html +='<td>Client Division*';
                    html +='<input type="hidden" name="po_details[]" value="Client Division" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" required class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">4</td>';
                    html +='<td>Sub Position';
                    html +='<input type="hidden" name="po_details[]" value="Sub Position" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" value="'+data.rfh_form_details[0].sub_position_title+'" class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">5</td>';
                    html +='<td>RFH Number*';
                    html +='<input type="hidden" name="po_details[]" value="RFH Number" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" value="'+data.rfh_form_details[0].rfh_no+'" readonly required class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">6</td>';
                    html +='<td>Name of Select*';
                    html +='<input type="hidden" name="po_details[]" value="Name of Select" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" value="'+data.candidate_details[0].candidate_name+'" required class="form-control"></td>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">7</td>';
                    html +='<td>Hiring Manager*';
                    html +='<input type="hidden" name="po_details[]" value="Hiring Manager" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" value="'+data.rfh_form_details[0].interviewer+'" required class="form-control"></td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">8</td>';
                    html +='<td>Type of Engagement*';
                    html +='<input type="hidden" name="po_details[]" value="Type of Engagement" class="form-control">';
                    html +='</td>';
                    html +='<td>';
                        html +='<select name="po_description[]" class="form-control" required >';
                            html +='<option value="">Choose</option>';
                            html +='<option value="Regular" selected>Regular</option>';
                            html +='<option value="Contractual">Contractual</option>';
                            html +='<option value="Part Time">Part Time</option>';
                            html +='<option value="Trainee">Trainee</option>';
                            html +='<option value="Intern">Intern</option>';
                            html +='<option value="FTE">FTE</option>';
                        html +='</select>';
                    html +='</td>';
                    html +='<td><input type="hidden" name="po_amount[]" value="no_val" class="form-control" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">9</td>';
                    html +='<td>Current/Last drawn CTC- Annual (Rs)';
                    html +='<input type="hidden" name="po_details[]" value="Current/Last drawn CTC- Annual (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount[]" value="'+data.candidate_details[0].last_drawn_ctc+'" readonly class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><div class="input-group"><input type="hidden" name="po_amount_month[]" value="" class="form-control" onkeypress="num_validate(event)"></td></div>';

                    html +='<td><input type="hidden" name="remark[]" class="form-control"></td>';

                    html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">10</td>';
                    html +='<td>Offer CTC – Annual (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Offer CTC – Annual (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.candidate_details[0].closed_salary_pa+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.candidate_details[0].closed_salary_pa);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">11</td>';
                    html +='<td>Medical Insurance Premium (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Medical Insurance Premium (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].medical_insurance+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].medical_insurance);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">12</td>';
                    html +='<td>Personal Accident Coverage Premium (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Personal Accident Coverage Premium (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].accident_coverage+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].accident_coverage);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">13</td>';
                    html +='<td>Term Insurance Coverage Premium (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Term Insurance Coverage Premium (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].term_insurance+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].term_insurance);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">14</td>';
                    html +='<td>Staff Welfare Administration Costs (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Staff Welfare Administration Costs (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].staff_welfare+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].staff_welfare);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">15</td>';
                    html +='<td>HR Software Modules  Access Charges (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="HR Software Modules  Access Charges (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].hr_software_modules+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].hr_software_modules);
                html +='</tr>';
                html +='<tr>';
                    html +='<td class="sno_cl">16</td>';
                    html +='<td>Internet charges (For Work from Home)(Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Internet charges (For Work from Home)(Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" value="'+data.po_default_values[0].internet_charges_wfh+'" class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    headcount_cost += Number(data.po_default_values[0].internet_charges_wfh);
                html +='</tr>';
                html +='<tr id="add_comp_below">';
                    html +='<td class="sno_cl">17</td>';
                    html +='<td>Provident Fund Admin Charges (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Provident Fund Admin Charges (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="number" required name="po_amount[]" value="'+pf_admin_charge+'" class="form-control headcount_cost" pattern="[0-9.]+"></td></div>';
                    headcount_cost += Number(pf_admin_charge);
                html +='</tr>';

                html +='<tr>';
                    html +='<td class="sno_cl">18</td>';
                    html +='<td>Total Headcount Cost (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Total Headcount Cost (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="total_headcount" class="form-control po_amnt_cell" value="'+headcount_cost+'" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><input type="text" style="display:block" name="remark[]" placeholder ="Enter Your Remark" class="form-control""></td></div>';

                    html +='</tr>';

                var hepl_bsc = Math.round(headcount_cost * Number(data.po_default_values[0].hepl_bs_charge_percent));

                html +='<tr>';
                    html +='<td class="sno_cl">19</td>';
                    html +='<td>HEPL Business Services Charges (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="HEPL Business Services Charges (Rs)" class="form-control">';
                    html +='<input type="hidden" name="hepl_bs_charge_percent" id="hepl_bs_charge_percent" value="'+Number(data.po_default_values[0].hepl_bs_charge_percent)+'" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="hepl_bsc" value="'+hepl_bsc+'" class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><input type="text" style="display:block" name="remark[]" placeholder ="Enter Your Remark" class="form-control""></td></div>';

                    html +='</tr>';

                var purchase_order = (headcount_cost + hepl_bsc);
                html +='<tr>';
                    html +='<td class="sno_cl">20</td>';
                    html +='<td>Total Purchase Order Value (Rs)*';
                    html +='<input type="hidden" name="po_details[]" value="Total Purchase Order Value (Rs)" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" required name="po_amount[]" id="purchase_order" value="'+purchase_order+'" class="form-control po_amnt_cell" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><input type="text" style="display:block" name="remark[]" placeholder ="Enter Your Remark" class="form-control""></td></div>';

                    html +='</tr>';
                                
                $('#add_po_tbody').html(html);  
            }
        }
    })
}
// $(".headcount_cost").on("change input keyup ", function(){
    jQuery(document ).on( "keyup input change", ".headcount_cost", function(){
        var oat_btn = '<button id="submitBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Revert to OAT</button>';
        oat_btn +='<input type="hidden" id="ch_v" value="1"/>';   
        $('#submit_btn').html(oat_btn);
    calc_total();
});
jQuery(document ).on( "keydown change", ".headcount_cost", function(){
    $(this).closest('tr').find('input').css({"display": "block"});  

   // var remark_btn ='<input type="text" class="form-control" placeholder = "Enter your Remark" name="remark[]" id="remark_bh"/>';
  //  var po = $(this).val();
    // remark_btn +='<input type="text" class="form-control" value ="'+po+'" name="po_remark[]" id="po_bh"/>';
   //$(this).(remark_btn);
});

function calc_total(){
    var headcount_sum = 0;
    jQuery(".headcount_cost").each(function(){
    // console.log($(this).val());

    headcount_sum += parseFloat(Number($(this).val()));
    });
    $('#total_headcount').val(headcount_sum);

    var hepl_bs_charge_percent = $('#hepl_bs_charge_percent').val();
    var hepl_bsc = Math.round(headcount_sum * Number(hepl_bs_charge_percent));
    $('#hepl_bsc').val(hepl_bsc);

    var purchase_order = (headcount_sum + hepl_bsc);
    $('#purchase_order').val(purchase_order);

}
function change_submit_button(){
   var fin_btn = '<button id="submitBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Submit</button>';
        fin_btn +='<input type="hidden" id="ch_v" value="0"/>';
   $('#submit_btn').html(fin_btn);
}
function add_component_extra(){
    
    var html ='';
        html +='<tr id="add_comp_below">';
                    html +='<td class="sno_cl"></td>';
                    html +='<td><input name="po_details[]" type="text" class="form-control" required placeholder="Detail">';
                    html +='<input type="hidden" value="" class="form-control">';
                    html +='</td>';
                    html +='<td><input type="text" name="po_description[]" class="form-control"></td>';
                    html +='<td><div class="input-group"><span class="input-group-text">₹</span><input type="text" name="po_amount[]" required class="form-control headcount_cost" onkeypress="num_validate(event)"></td></div>';
                    html +='<td><input type="text" style="display:block"  placeholder ="Enter Your Remark" name="remark[]"  class="form-control""></td></div>';

                    html +='</tr>';

        $('#add_comp_below').after(html);
        // $('#po_components > tbody td:nth-child(17)').append(html);

        updatesno();
    $("html, body").animate({ scrollTop: $(document).height() }, "slow");
    var oat_btn = '<button id="submitBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Revert to OAT</button>';
    oat_btn +='<input type="hidden" id="ch_v" value="1"/>';
    $('#submit_btn').html(oat_btn);

    
}
// jQuery(document ).on( "keyup input change", ".headcount_cost", function(){
//     var oat_btn = '<button id="submitBtn" type="submit" style="float:right" class="btn btn-sm btn-smn btn-primary">Submit to OAT</button>';
//     oat_btn +='<input type="hidden" id="ch_v" value="1"/>';
//     var remark_btn ='<input type="text" class="form-control" name="remark_bh" id="remark_bh"/>';
//     $(this).after(remark_btn);
//     $('#submit_btn').html(oat_btn);
// });
jQuery(document ).on( "input change", ".headcount_cost", function(){
    val = $(this).val();
    $(this).closest('tr').find('.p_month').val(Math.round(val/12));  
    var total_headcount = $('#total_headcount').val();
    $('#total_headcount_month').val(Math.round(total_headcount/12));
    var hepl_bsc = $('#hepl_bsc').val();
    $('#hepl_bsc_month').val(Math.round(hepl_bsc/12));
    var purchase_order = $('#purchase_order').val();
    $('#purchase_order_month').val(Math.round(purchase_order/12));

//calc_total();
});
function updatesno(){
   
    $.each($("#po_components tr:not(:first)"), function (i, el) {
        var sn = i + 1;
        var sno = "<p>"+sn+"</p>";
        $(this).find("td:first").html(sno);   
    })

}

function num_validate(evt) {
    var theEvent = evt || window.event;
  
    // Handle paste
    if (theEvent.type === 'paste') {
        key = event.clipboardData.getData('text/plain');
    } else {
    // Handle key press
        var key = theEvent.keyCode || theEvent.which;
        key = String.fromCharCode(key);
    }
    var regex = /[0-9]|\./;
    if( !regex.test(key) ) {
      theEvent.returnValue = false;
      if(theEvent.preventDefault) theEvent.preventDefault();
    }
}

$('#addpoForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

    $('#submitBtn').prop("disabled",true);
    $('#submitBtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
   var get_status = $('#ch_v').val();
   if(get_status == 0){
       var url = submit_po_process_link;
   }
   else{
       var url = submit_OAT_process_link;
   }
       $.ajax({  
            url:url, 
            method:"POST",  
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#submitBtn').prop("disabled",false);
                $('#submitBtn').html('Submit');

                if(data.response =='success'){

                    Toastify({
                        text: "Added Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            var host = window.location.origin; 

                         //   var host_url = host+"/"+data.redirect_url;
                          //  console.log(data.cdID,data.rfh_no);
                            window.open(data.redirect_url, '_blank').focus();
                            call_to_mail(data.cdID,data.rfh_no,get_status);
                          //  window.open(host_url, '_blank');

                            //window.location = "ol_leader_verify";
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
function call_to_mail(cdid,rfh_no,get_status){
    if(get_status == 0){
        var url = submit_finance;
    }
    else{
        var url = submit_oat;
    }
    $.ajax({  
        url:url, 
        method:"POST",  
        data: {'cdID':cdid,'rfh_no':rfh_no},
       
        dataType:"json",

        success:function(data) {

            if(data.response =='success'){

                Toastify({
                    text: "Mailed Successfully",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        var host = window.location.origin; 

                        var host_url = host+"/"+data.redirect_url;
                       // console.log(host+"/"+data.redirect_url);
                       // window.open(host_url, '_blank').focus();
                     //   call_to_mail();
                      //  window.open(host_url, '_blank');

                        window.location = "ol_leader_verify";
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

}