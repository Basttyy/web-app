let showVerifyAccount = (url, params) => {
    verifyUrl = api_url + "api/auth/verify_account.php"
    // remove jwt
    setCookie("jwt", "", 1)
    loadHTML(url, 'hide-nav-view')
    setTimeout(function(){
        $('#verify_success, #verify_error').hide()
        verifyAccount(verifyUrl, params)
        .then(
            function(response){
                alert(response.message)
                $('#verify_error, #verify_info').hide(1200)
                $('#verify_success').show(700)
                setTimeout(async function(){
                    formValues = await orderStove()
                    
                    // router.navigate()
                }, 2000)
            }
        )
        .catch(
            function(xhr, resp, text){
                alert(xhr.responseJSON.message)
                $('#verify_success, #verify_info').hide(1200)
                $('#verify_error').show(700)
            }
        )
    }, 10)
    // $id('nav-activate').hidden = true;
    // $id('side-activate').hidden = true;
}

//show order stove form
async function orderStove(){
    const {value: formValues} = await Swal.fire({
        title: 'Multiple inputs',
        html:
            '<input type="email" placeholder="Enter your email" id="email" class="swal2-input">' +
            '<select class="swal2-input">' +
                '<option value="">Select Purchase Type</option>'+
                '<option value="purch_install">Full Purchase</option>'+
                '<option value="purch_install">Installment Purchase</option>'+
                '<option value="purch_renew">Renewment</option>'+
            '</select>'+
            '<input id="quantity" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
            return [
                document.getElementById('email').value,
                document.getElementById('quantity').value
            ]
        }
    })
    return JSON.stringify(formValues)
}