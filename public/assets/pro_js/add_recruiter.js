$( document ).ready(function() {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
});

$("#designation").on('change', function() {
        
    var designation = $('#designation').val();
    if(designation =='Recruiter'){
        $("#team_div").css({"display":"block"});
        $('#team').attr('required',true);
    }else{
        $("#team_div").css({"display":"none"});
        $('#team').removeAttr('required');
    }
});


$('#addRecruiterForm').submit(function(e) {
    $('#btnSubmit').prop("disabled",true);
    $('#btnSubmit').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
    
    e.preventDefault();
    var formData = new FormData(this);
    $.ajax({  
        url:add_recruiter_process_link, 
        method:"POST",  
        data: formData,
        cache:false,
        contentType: false,
        processData: false,
        dataType:"json",

        success:function(data) {
            $('#btnSubmit').prop("disabled",false);
                $('#btnSubmit').html('Submit');
    
                
            if(data.response =='success'){

                Toastify({
                    text: "Added Sussssfully..!",
                    duration: 3000,
                    close:true,
                    backgroundColor: "#4fbe87",
                }).showToast();

                setTimeout(
                    function() {
                        window.location.href = "view_recruiter";

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