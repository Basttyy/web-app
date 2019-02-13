let authenticateDefault = (url) =>{
    return new Promise((resolve, reject) =>{
        var jwt = getCookie('jwt');

        $.post("api/auth/validate_token.php", JSON.stringify({ jwt: jwt }))
            .done(function (response){
                resolve(response);
            })
            // show login page on error
            .fail(function (xhr, resp, text) {
                reject(xhr);
            });
    })
}