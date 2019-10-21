$(document).ready(function(){
    // trigger when registration form is submitted
    $(document).on('submit', '#sign_up_form', function(){

        // get form data
        var sign_up_form=$(this);
        var url = api_url + "api/auth/create_user.php";
        var jwt = getCookie('jwt');
        // alert(sign_up_form);
        sign_up_form_obj = sign_up_form.serializeObject();
        sign_up_form_obj.jwt = jwt;

        signup(url, sign_up_form_obj)
            .then(
                function(response){
                    // if response is a success, tell the user it was a successful sign up & empty the input boxes
                    alert(response.message);
                    sign_up_form.find('input').val('');
                    $('textarea').val('');
                    sign_up_form.find('select').val(1);
                }
            )
            .catch(
                function(xhr, resp, text){
                    // on error, tell the user sign up failed
                    alert(xhr.responseJSON.message + ". Please contact admin.");
                }
            )
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
});

function showSignupForm(templateUrl){
    //link to authenticate
    tokenUrl = api_url + "api/auth/validate_token.php";

    authenticate(tokenUrl)
        .then(
            function(response){
                // if valid, show homepage
                //alert(response.message);
                loadHTML(templateUrl, 'content');
                $.getJSON("app/assets/data/countries.json", function(data){
                    setTimeout(function(){
                        // Everything will have rendered here
                        $.each(data.countries, function(i, val){
                            $('#country').append($('<option></option>').val(val.toLowerCase()).html(val));
                        });
                    }, 5);
                });
            }
        )
        .catch(
            function(xhr){
                alert(xhr.responseJSON.message);
                router.navigate(login_page);
                //$('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
                //showLoggedOutMenu();
            }
        );
}