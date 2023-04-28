$( document ).ready(function() {
    $.ajaxSetup({
        headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    get_band_details();
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
                    html += '<option value="'+data[index].band_title+'">'+data[index].band_title+'</option>';
                }
                
                $('#band').html(html);

            }
        }
    });
}
$('#requestForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

    $('#btnSubmit').prop("disabled",true);
    $('#btnSubmit').html('<span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span> Processing');
    
       $.ajax({  
            url:reqcruitment_request_process_link, 
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
                        text: "Inserted Successfully",
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