
function employeeid_valid(){
				
    var textInput = document.getElementById("employee_id").value;
    textInput = textInput.replace(/[&\/\-_=|\][;\#,+()$~%.'":*?<>{}@^!`]/g, "");
    document.getElementById("employee_id").value = textInput;
    if(textInput ==''){
        $("#employee_id").addClass("is-invalid");
        $("#btnLogin").attr("disabled", true);

    }
    else{
        $("#employee_id").addClass("is-valid");
        $("#employee_id").removeClass("is-invalid");
        $('#btnLogin').removeAttr("disabled");

    }
}

function password_valid(){
				
    var textInput = document.getElementById("login_password").value;
    if(textInput ==''){
        $("#login_password").removeClass("is-valid");
        $("#login_password").addClass("is-invalid");

        $("#btnLogin").attr("disabled", true);


    }
    else{
        $("#login_password").removeClass("is-invalid");

        $("#login_password").addClass("is-valid");
        $("#btnLogin").attr("disabled", false);
        

    }
}

$('#loginForm').submit(function(e) {
    var formData = new FormData(this);
    e.preventDefault();

       $.ajax({  
            url:login_check_process_link, 
            method:"POST",  
            data: formData,
            cache:false,
            contentType: false,
            processData: false,
            dataType:"json",

            success:function(data) {
                if(data.logstatus =='success'){

                    Toastify({
                        text: "Login Successfully",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#4fbe87",
                    }).showToast();

                    setTimeout(
                        function() {
                            window.location = data.url;

                        }, 2000);

                }
                else{
                    Toastify({
                        text: "Emp ID or Password are wrong",
                        duration: 3000,
                        close:true,
                        backgroundColor: "#f3616d",
                    }).showToast();

                    setTimeout(
                        function() {
                            window.location = data.url;
                        }, 2000);

                    

                }
                
            }  
        }); 
    
    
});