$(document).ready(function(){
    // logout the user
    $(document).on('click', '#logout', function(){
        logout();
    });
});

function logout(){
    showLoginPage();
    //$('#response').html("<div class='alert alert-info'>You are logged out.</div>");
}