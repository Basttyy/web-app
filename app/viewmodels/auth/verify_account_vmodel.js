let showVerifyAccount = (url, params) => {
    verifyUrl = api_url + "api/auth/verify_account.php";
    // remove jwt
    setCookie("jwt", "", 1);
    loadHTML(url, 'hide-nav-view');
    setTimeout(function(){
        $('#verify_success, #verify_error').hide();
        verifyAccount(verifyUrl, params)
        .then(
            function(response){
                alert(response.message);
                $('#verify_error, #verify_info').hide(1200);
                $('#verify_success').show(700);
            }
        )
        .catch(
            function(xhr, resp, text){
                alert(xhr.responseJSON.message);
                $('#verify_success, #verify_info').hide(1200);
                $('#verify_error').show(700);
            }
        );
    }, 10);
    // $id('nav-activate').hidden = true;
    // $id('side-activate').hidden = true;
};