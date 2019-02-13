$(document).ready(function(){

})

function showDefaultPage(url){
    authenticateDefault(url)
        .then(
            function(response){
                // if valid, show homepage
                loadHTML('./app/views/auth/default.html', 'content');
                showLoggedInMenu();
            }
        ).catch(
            function(xhr, resp, text){
                alert(xhr.responseJSON.message);
                router.navigate(login_page);
                //$('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
            }
        )
}