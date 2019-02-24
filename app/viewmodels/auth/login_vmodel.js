$(document).ready(function(){
    // trigger when login form is submitted
    $(document).on('submit', '#login_form', function(){
        //var homePage = '';
        //var okResponse = "<div class='alert alert-success'>Successful login.</div>";
        //var errResponse = "<div class='alert alert-danger'>Login failed. Email or password is incorrect.</div>";
        // get form data
        var login_form=$(this);
        
        login(api_url + "api/auth/login.php", login_form)
            .then( //login success?
                function(response){
                    // store jwt to cookie
                    setCookie("jwt", response.jwt, 1);
                    loadHTML('./app/views/nav-template.html', 'hide-nav-view');
                    router.navigate(home_page); //go to homepage
                    //show success response
                    //$('#response').html(okResponse);
                }
            )
            .catch(
                function(error, resp, text){
                    alert(error.responseJSON.message + 'Login failed');
                    login_form.find('input').val('');
                }
            );
        return false;
    });
});