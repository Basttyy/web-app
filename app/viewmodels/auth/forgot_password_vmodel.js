$(document).ready(function(){
    // trigger when login form is submitted
    $(document).on('submit', '#forgot_pass_form', function(){
        // get form data
        var forgot_form=$(this);
        
        requestReset(api_url + "api/auth/forgot_password.php", forgot_form)
            .then( //login success?
                function(response){
                    // store jwt to cookie
                    //setCookie("jwt", response.jwt, 1);
                    //loadHTML('./app/views/nav-template.html', 'hide-nav-view');
                    alert(response.message);
                    //router.navigate(forgot_password_page); //go to homepage
                    //show success response
                    //$('#response').html(okResponse);
                }
            )
            .catch(
                function(error, resp, text){
                    alert(error.responseJSON.message + ' try again or contact admin');
                    forgot_form.find('input').val('');
                }
            );
        return false;
    });
});