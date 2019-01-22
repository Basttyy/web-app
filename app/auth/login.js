$(document).ready(function(){
    // show login form
    $(document).on('click', '#login', function(){
        showLoginPage();
    });
    
    // trigger when login form is submitted
    $(document).on('submit', '#login_form', function(){    
        // get form data
        var login_form=$(this);
        var form_data=JSON.stringify(login_form.serializeObject());

        // submit form data to api
        $.ajax({
            url: "api/auth/login.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result){
        
                // store jwt to cookie
                setCookie("jwt", result.jwt, 1);
        
                // show home page & tell the user it was a successful login
                showHomePage();
                $('#response').html("<div class='alert alert-success'>Successful login.</div>");
        
            },
            error: function(xhr, resp, text){
                // on error, tell the user login has failed & empty the input boxes
                $('#response').html("<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>");
                login_form.find('input').val('');
            }
        });
        return false;
    });
});