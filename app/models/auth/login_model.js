//make login https request to api
let login = (url, form_data) =>{
    return new Promise((resolve, reject) => {
        //serialize the form data
        form_data = JSON.stringify(form_data.serializeObject());
    
        // submit form data to api
        $.ajax({
            url: url,
            type : "POST",
            contentType : 'application/json',
            data : form_data
        }).done((response) =>{
            //this means the api call suceeded, so resolve the response
            resolve(response);
        }).fail((error, resp, text) =>{
            //this means the api call failed, so reject on the error
            reject(error);
        });
    });
}

let prepareLogin = () => {
    // remove jwt
    setCookie("jwt", "", 1);
    // $id('nav-activate').hidden = true;
    // $id('side-activate').hidden = true;
};