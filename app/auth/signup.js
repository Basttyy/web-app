$(document).ready(function(){
    // show sign up / registration form
    $(document).on('click', '#sign_up', function(){
    
        var html = `
            <h2>Sign Up</h2>
            <form id='sign_up_form'>
                <div class="form-group">
                    <label for="firstname">Firstname</label>
                    <input type="text" class="form-control" name="firstname" id="firstname" required />
                </div>
 
                <div class="form-group">
                <div class="form-group">
                    <label for="lastname">Lastname</label>
                    <input type="text" class="form-control" name="lastname" id="lastname" required />
                </div>
 
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" name="email" id="email" required />
                </div>
 
                    <label for="password">Password</label>
                    <input type="password" class="form-control" name="password" id="password" required />
                </div> 
                <button type='submit' class='btn btn-primary'>Sign Up</button>
            </form>
            `;
 
        clearResponse();
    });
 
    // trigger when registration form is submitted
    $(document).on('submit', '#sign_up_form', function(){
    
        // get form data
        var sign_up_form=$(this);
        var form_data=JSON.stringify(sign_up_form.serializeObject());

        console.log(form_data);
        // submit form data to api
        $.ajax({
            url: "api/auth/create_user.php",
            type : "POST",
            contentType : 'application/json',
            data : form_data,
            success : function(result) {
                // if response is a success, tell the user it was a successful sign up & empty the input boxes
                $('#response').html("<div class='alert alert-success'>Successful sign up. Please go to your email and confirm your account.</div>");
                sign_up_form.find('input').val('');
            },
            error: function(xhr, resp, text){
                // on error, tell the user sign up failed
                $('#response').html("<div class='alert alert-danger'>Unable to sign up. Please contact admin.</div>");
            }
        });

        return false;
    });
});