$(document).ready(function(){
    //trigger when signup form is submitted
    $(document).on('submit', '#update_account_form', function(){
        //get the form data
        var signup_form = $(this);

        updateProfile('api/auth/update_user.php', signup_form)
            .then(
                function(response){
                    //$('#response').html("<div class='alert alert-success'>"+ response + ".</div>");
                    // store new jwt to coookie
                    alert('success');
                    alert(response.message);
                    setCookie("jwt", response.jwt, 1);
                    //signup_form.find('input').val('');
                }
            )
            .catch(
                function(xhr, resp, text){
                    alert('fail');
                    if(xhr.responseJSON.message=="Unable to update user."){
                        $('#response').html("<div class='alert alert-danger'>Unable to update account.</div>");
                    }
                
                    else if(xhr.responseJSON.message=="Access denied."){
                        alert('Expired Session, please login again');
                        router.navigate(login_page);
                    }
                }
            );
        return false;
    })
})

function showUpdateProfilePage(updateUrl){
    var obj = {
        firstname : "",
        lastname : "",
        email : ""
    };
    //link to authenticate
    var url = "api/auth/validate_token.php";
    populateUpdateForm(url)
        .then(
            function(response){
                obj.firstname = response.data.firstname;
                obj.lastname = response.data.lastname;
                obj.email = response.data.email;
                loadHTML(updateUrl, 'content');
                setTimeout(function(){
                    // Everything will have rendered here   
                    $('#firstname').val(obj.firstname);
                    $('#lastname').val(obj.lastname);
                    $('#email').val(obj.email);
                }, 5);
            }
        )
        .catch(
            function(xhr, resp, text){
                alert(xhr.responseJSON.message)
                router.navigate(login_page);
            }
        );
}