$(document).ready(() =>{
    $(document).on('submit','#reset_pass_form', function(){
        if($('#password1').val() == $('#password2').val()){
            var reset_pass_form = $(this);
            var url = api_url + "api/auth/reset_password.php";

            resetPassword(url, reset_pass_form)
            .then(
                function(response){
                    alert(response.message);
                    router.navigate(login_page);
                }
            )
            .catch(
                function(xhr, resp, text){
                    alert(xhr.responseJSON.message + ". please try again or contact admin");
                }
            );
        }else{
            alert("password one and two don't match");
            //router.Navigate("http://web-app.test:8080/#reset-password/" + $('#access_code').val());
        }  
        return false;
    });
})

let showResetPassword = (url, params) => {
    // remove jwt
    setCookie("jwt", "", 1);
    loadHTML(url, 'hide-nav-view');
    setTimeout(function(){
        $('#access_code').val(params.access_code);
    }, 15);
    // $id('nav-activate').hidden = true;
    // $id('side-activate').hidden = true;
};