$(document).ready(function(){
    // show update account form
    $(document).on('click', '#update_account', function(){
        showUpdateAccountForm();
    });
    
    // trigger when 'update account' form is submitted
    $(document).on('submit', '#update_account_form', function(){
        
        // handle for update_account_form
        var update_account_form=$(this);

        // validate jwt to verify access
        var jwt = getCookie('jwt');

        // get form data
        var update_account_form_obj = update_account_form.serializeObject()
        
        // add jwt on the object
        update_account_form_obj.jwt = jwt;
        
        // convert object to json string
        var form_data=JSON.stringify(update_account_form_obj);
        
        // submit form data to api
        $.ajax({
            url: "api/auth/update_user.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
        
                // tell the user account was updated
                $('#response').html("<div class='alert alert-success'>Account was updated.</div>");
        
                // store new jwt to coookie
                setCookie("jwt", result.jwt, 1);
            },
        
            // show error message to user
            error: function(xhr, resp, text){
                if(xhr.responseJSON.message=="Unable to update user."){
                    $('#response').html("<div class='alert alert-danger'>Unable to update account.</div>");
                }
            
                else if(xhr.responseJSON.message=="Access denied."){
                    showLoginPage();
                    $('#response').html("<div class='alert alert-success'>Access denied. Please login</div>");
                }
            }
        });

        return false;
    });    
});

function showUpdateAccountForm(){
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post("api/auth/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

        // if response is valid, put user details in the form
        var html = `
                <h2>Update Account</h2>
                <form id='update_account_form'>
                    <div class="form-group">
                        <label for="firstname">Firstname</label>
                        <input type="text" class="form-control" name="firstname" id="firstname" required value="` + result.data.firstname + `" />
                    </div>
        
                    <div class="form-group">
                        <label for="lastname">Lastname</label>
                        <input type="text" class="form-control" name="lastname" id="lastname" required value="` + result.data.lastname + `" />
                    </div>
        
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control" name="email" id="email" required value="` + result.data.email + `" />
                    </div>
        
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" name="password" id="password" />
                    </div>
        
                    <button type='submit' class='btn btn-primary'>
                        Save Changes
                    </button>
                </form>
            `;
        
        clearResponse();
        $('#content').html(html);
    })
    // on error/fail, tell the user he needs to login to show the account page
    .fail(function(result){
        showLoginPage();
        $('#response').html("<div class='alert alert-danger'>Please login to access the account page.</div>");
    });
}