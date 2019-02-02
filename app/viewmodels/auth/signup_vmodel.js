$(document).ready(function(){
    // trigger when registration form is submitted
    $(document).on('submit', '#sign_up_form', function(){

        // get form data
        var sign_up_form=$(this);
        var url = "api/auth/create_user.php";

        signup(url, sign_up_form)
            .then(
                function(response){
                    // if response is a success, tell the user it was a successful sign up & empty the input boxes
                    //$('#response').html("<div class='alert alert-success'>Successful sign up. Please go to your email and confirm your account.</div>");
                    sign_up_form.find('input').val('');
                }
            )
            .catch(
                function(text){
                    // on error, tell the user sign up failed
                    $('#response').html("<div class='alert alert-danger'>"+JSON.parse(text)+". Please contact admin.</div>");
                }
            )
        return false;
    });
});

function showSignupForm(templateUrl){
    //link to authenticate
    tokenUrl = "api/auth/validate_token.php";

    authenticate(tokenUrl)
        .then(
            function(response){
                // if valid, show homepage
                alert(response.message);
                loadHTML(templateUrl, 'content');
                //showLoggedInMenu();
            }
        )
        .catch(
            function(xhr){
                alert(xhr.responseJSON.message);
                router.navigate(login_page);
                //$('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
                //showLoggedOutMenu();
            }
        );
}