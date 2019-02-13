let verifyAccount = (url, params) =>{
    return new Promise((resolve, reject) =>{
        verifyData = JSON.stringify(params);
        //alert(resetData);
        $.ajax({
            url: url,
            type: "POST",
            contentType: "application/json",
            data: verifyData
        })
        .done((response) =>{
            alert('done');
            //this means the api call succeeded
            resolve(response);
        })
        .fail((xhr, resp, text) =>{
            alert('fail');
            reject(xhr);
        });
    });
}