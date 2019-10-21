$(document).ready(function(){
    //trigger when signup form is submitted
    $(document).on('submit', '#update_account_form', function(){
        //get the form data
        var signup_form = $(this);
        var jwt = getCookie('jwt');
        var signup_form_obj = signup_form.serializeObject();
        signup_form_obj.jwt = jwt;

        updateProfile(api_url + 'api/users/update_user.php', signup_form_obj)
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
                        alert("Unable to update account.");
                    }
                
                    else if(xhr.responseJSON.message=="Access denied."){
                        alert('Expired Session, please login again');
                        router.navigate(login_page);
                    }
                }
            );
        return false;
    });
    $(document.body).on('change', '#update_country', function(e){
        $('#update_state').empty();
        $('#update_state').append($('<option default="true"></option>').html("State"));
        if($('#update_country option:selected').val() != '1'){
            var jsonurl = "app/assets/data/states/" + $('#update_country option:selected').val() + ".json";
            $.getJSON(jsonurl, function(data){
                $.each(data.states, function(i, val){
                    $('#update_state').append($('<option></option>').val(val.toLowerCase()).html(val));
                });
            });
        }
    });
    $(document.body).on('change', '#update_state', function(){        
        $('#update_postal_code').empty();
        $('#update_postal_code').append($('<option default="true"></option>').html("Postal Code"));
        if($('#update_state option:selected').val() != '1'){
            var jsonurl = "app/assets/data/states/codes/" + $('#update_state option:selected').val() + ".json";
            $.getJSON(jsonurl, function(data){
                $.each(data.codes, function(i, val){
                    $('#update_postal_code').append($('<option></option>').val(val.toLowerCase()).html(val));
                });
            });
        }
    });
    $(document.body).on('mouseenter', '#update_country', function(){
        $('#update_country').val(obj.country).change();
    });
    $(document.body).on('mouseenter', '#update_state', function(){
        $('#update_state').val(obj.state).change();
    });
    $(document.body).on('mouseenter', '#update_postal_code', function(){
        $('#update_postal_code').val(obj.postalCode).change();
    });
})

var obj = {
    firstname : "",
    lastname : "",
    email : "",
    country : "",
    state : "",
    postalCode : "",
    streetAddress : "",
    phone : ""
};

function showUpdateProfilePage(updateUrl){
    //link to authenticate
    var url = api_url + "api/auth/validate_token.php";
    populateUpdateForm(url)
        .then(
            function(response){
                obj.firstname = response.data.firstname;
                obj.lastname = response.data.lastname;
                obj.email = response.data.email;
                obj.country = response.data.country;
                obj.state = response.data.state;
                obj.postalCode = response.data.postal_code;
                obj.streetAddress = response.data.address;
                obj.phone = response.data.contact_number;
                loadHTML(updateUrl, 'content');
                setTimeout(function(){
                    // Everything will have rendered here  
                    $('#firstname').val(obj.firstname);
                    $('#lastname').val(obj.lastname);
                    $('#email').val(obj.email);
                    $('#address').val(obj.streetAddress);
                    $('#contact_number').val(obj.phone);
                    $.getJSON("app/assets/data/countries.json", function(data){
                        $.each(data.countries, function(i, val){
                            $('#update_country').append($('<option></option>').val(val.toLowerCase()).html(val));
                        });
                    });
                }, 15);
            }
        )
        .catch(
            function(xhr, resp, text){
                alert(xhr.responseJSON.message)
                router.navigate(login_page);
            }
        );
}