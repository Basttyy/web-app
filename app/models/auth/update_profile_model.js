let updateProfile = (url, form_data) => {
    return new Promise((resolve, reject) =>{

        // validate jwt to verify access
        var jwt = getCookie('jwt');

        // serialize the form data
        var form_data_obj = form_data.serializeObject()
        
        // add jwt on the object
       form_data_obj.jwt = jwt;
        
        // convert object to json string
        var json_form_data=JSON.stringify(form_data_obj);

        // submit form data to api
        $.ajax({
            url: url,
            type : "POST",
            contentType : 'application/json',
            data : json_form_data
        }).done((response) =>{
            //this means the api call suceeded, so resolve the response
            alert('profile updated');
            resolve(response);
        }).fail((xhr, resp, text) =>{
            //this means the api call failed, so reject on the error
            alert('profile udate failed');
            reject(xhr);
        });
    });
}

let populateUpdateForm = (url) =>{
    return new Promise((resolve, reject) =>{
        var jwt = getCookie('jwt');

        $.post(url, JSON.stringify({ jwt: jwt }))
        .done((response) =>{
            //if authentication successful
            resolve(response);
            // if valid, show homepage
        })
        .fail((xhr, resp, text) =>{
            //reject if authentication failed
            reject(xhr);
        });
    });
}