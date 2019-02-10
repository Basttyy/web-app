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
    $(document.body).on('change', '#country', function(e){
        $('#state').empty();
        $('#state').append($('<option default="true"></option>').html("State"));
        if($('#country option:selected').val() != '1'){
            var jsonurl = "app/assets/data/states/" + $('#country option:selected').val() + ".json";
            $.getJSON(jsonurl, function(data){
                $.each(data.states, function(i, val){
                    $('#state').append($('<option></option>').val(val.toLowerCase()).html(val));
                });
            });
        }
    });
    $(document.body).on('change', '#state', function(){        
        $('#postal_code').empty();
        $('#postal_code').append($('<option default="true"></option>').html("Postal Code"));
        if($('#state option:selected').val() != '1'){
            var jsonurl = "app/assets/data/states/codes/" + $('#state option:selected').val() + ".json";
            $.getJSON(jsonurl, function(data){
                $.each(data.codes, function(i, val){
                    $('#postal_code').append($('<option></option>').val(val.toLowerCase()).html(val));
                });
            });
        }
    });
    $('#country').on('focus', function(){
        $('#country').val(obj.country);
    })
    $('#state').on('focus', function(){
        $('#state').val(obj.state);
    })
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
    var url = "api/auth/validate_token.php";
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
                    $.getJSON("app/assets/data/countries.json", function(data){
                        $.each(data.countries, function(i, val){
                            $('#country').append($('<option></option>').val(val.toLowerCase()).html(val));
                        });
                    });
                    $('#firstname').val(obj.firstname);
                    $('#lastname').val(obj.lastname);
                    $('#email').val(obj.email);
                    $('#address').val(obj.streetAddress);
                    $('#contact_number').val(obj.phone);
                }, 15);
                setTimeout(() => {
                    $('#state option:selected').val(obj.state);
                    $('#postal_code option:selected').val(obj.postalCode);
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