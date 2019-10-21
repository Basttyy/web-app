let authenticate = (tokenUrl) => {
    return new Promise((resolve, reject) =>{
        var jwt = getCookie('jwt');

        $.post(tokenUrl, JSON.stringify({ jwt: jwt }))
            .done((response) =>{
                resolve(response);
            })
            // show login page on error
            .fail((xhr, resp, text) =>{
                reject(xhr);
            });
    })
}

let signup = (url, sign_up_form_obj) =>{
    return new Promise((resolve, reject) =>{
        var form_data=JSON.stringify(sign_up_form_obj);
        alert(form_data);
        // submit form data to api
        $.ajax({
            url: url,
            type : "POST",
            contentType : 'application/json',
            data : form_data
        }).done((response) =>{
            resolve(response);
        }).fail((xhr, resp, text) =>{
            reject(xhr);
        });
    });
}