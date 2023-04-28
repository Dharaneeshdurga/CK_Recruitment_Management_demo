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
    get_ctc_edit_oat(cdID);
});

function get_ctc_edit_oat(cdID){

    $.ajax({
        type: "POST",
        url: get_ctc_edit_oat_link,
        data: { "cdID":cdID,},
        success: function (data) {
            
            var html ='';
            if(data.length !=0){
                html +='<tr>';
                    html +='<td>Basic</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="basic_pm" id="basic_pm" value="'+data[0].basic_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="basic_pa" id="basic_pa" value="'+data[0].basic_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>HRA</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="hra_pm" id="hra_pm" value="'+data[0].hra_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="hra_pa" id="hra_pa" value="'+data[0].hra_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Medical Allowance </td>';
                    html +='<td><input type="text" class="form-control cl_right" name="medi_al_pm" id="medi_al_pm" value="'+data[0].medi_al_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="medi_al_pa" id="medi_al_pa" value="'+data[0].medi_al_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Conveyance</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="conv_pm" id="conv_pm" value="'+data[0].conv_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="conv_pa" id="conv_pa" value="'+data[0].conv_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Special Allowance</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="spl_al_pm" id="spl_al_pm" value="'+data[0].spl_al_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="spl_al_pa" id="spl_al_pa" value="'+data[0].spl_al_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Monthly Components [A]</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="comp_a_pm" id="comp_a_pm" value="'+data[0].comp_a_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="comp_a_pa" id="comp_a_pa" value="'+data[0].comp_a_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td colspan="3">Employer Contrinbution (DIRECT)</td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Employee Contribution PF </td>';
                    html +='<td><input type="text" class="form-control cl_right" name="ec_pf_pm" id="ec_pf_pm" value="'+data[0].ec_pf_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="ec_pf_pa" id="ec_pf_pa" value="'+data[0].ec_pf_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Employer Contribution ESI</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="ec_esi_pm" id="ec_esi_pm" value="'+data[0].ec_esi_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="ec_esi_pa" id="ec_esi_pa" value="'+data[0].ec_esi_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>SUB TOTAL [B]</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="sub_totalb_pm" id="sub_totalb_pm" value="'+data[0].sub_totalb_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="sub_totalb_pa" id="sub_totalb_pa" value="'+data[0].sub_totalb_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td colspan="3">Annual Benefits (INDIRECT))</td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Gratuity</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="gratuity_pm" id="gratuity_pm" value="'+data[0].gratuity_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="gratuity_pa" id="gratuity_pa" value="'+data[0].gratuity_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Statutory Bonus</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="st_bonus_pm" id="st_bonus_pm" value="'+data[0].st_bonus_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="st_bonus_pa" id="st_bonus_pa" value="'+data[0].st_bonus_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>SUB TOTAL [C]</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="sub_totalc_pm" id="sub_totalc_pm" value="'+data[0].sub_totalc_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="sub_totalc_pa" id="sub_totalc_pa" value="'+data[0].sub_totalc_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>[A] + [B] + [C]</td>';
                    html +='<td><input type="text" class="form-control cl_right" name="abc_pm" id="abc_pm" value="'+data[0].abc_pm+'"></td>';
                    html +='<td><input type="text" class="form-control cl_right" name="abc_pa" id="abc_pa" value="'+data[0].abc_pa+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>NET PAY [In Rs PM]</td>';
                    html +='<td colspan="2"><input type="text" class="form-control cl_right" name="net_pay" id="net_pay" value="'+data[0].net_pay+'"></td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Group Mediclaim for Self and Family(if ESI not Covered</td>';
                    html +='<td colspan="2" class=" cl_right">2,00,000</td>';
                html +='</tr>';
                html +='<tr>';
                    html +='<td>Personal Accident Policy</td>';
                    html +='<td colspan="2" class="cl_right">5,00,000</td>';

                html +='</tr>';
                html +='<tr>';
                    html +='<td>Term Insurance</td>';
                    html +='<td colspan="2" class="cl_right">5,00,000</td>';
                html +='</tr>';
                // html +='<tr>';
                //     html +='<td>PARTICULARS</td>';
                //     html +='<td colspan="2"></td>';
                // html +='</tr>';
                
                
                $('#edit_ctc_oat_tbody').html(html);
            }
        }
    })
}

$('#editctcForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

    $('#updateBtn').prop("disabled",true);
    $('#updateBtn').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
    
       $.ajax({  
            url:update_ctc_edit_link, 
            method:"POST",  
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                $('#updateBtn').prop("disabled",false);
                $('#updateBtn').html('Update');
    

                if(data.response =='success'){

                    Toastify({
                        text: "Updated Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            // window.location = data.url;
                            location.reload();
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