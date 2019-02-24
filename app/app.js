//global variables
var api_url = 'http://web-app.test:8080/';
var home_page = '';
var login_page = 'login';
var update_profile_page = 'update-profile';
var signup_page = 'signup';
var profile_page = 'profile';
var forgot_password_page = 'forgot-password';
var reset_password_page = 'reset-password';

$(document).ready(function(){

});

// change page title
function changePageTitle(page_title){
 
    // change page title
    $('#panel-title').text(page_title);
 
    // change title tag
    document.title=page_title;
}

// // // show home page
// // function showHomePage(){
        
//     // validate jwt to verify access
//     var jwt = getCookie('jwt');
//     $.post("api/auth/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

//         // if valid, show homepage
//         var app_html = `
//             <div class="card">
//                 <div class="card-header">Welcome to Home!</div>
//                 <div class="card-body">
//                     <h5 class="card-title">You are logged in.</h5>
//                     <p class="card-text">You won't be able to access the home and account pages if you are not logged in.</p>
//                 </div>
//             </div>
//             `;            
//         route('#', app_html);
//         //$('#content').html(app_html);
//         showLoggedInMenu();
//     })
//     // show login page on error
//     .fail(function(result){
//         showLoginPage();
//         $('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
//     });
// // }

// if the user is logged in
function showLoggedInMenu(){
    // hide login and sign up from navbar & show logout button
    $("#login").hide();
    $("#logout, #update_profile, #signup").show();
}

// if the user is logged out
function showLoggedOutMenu(){
    // show login and sign up from navbar & hide logout button
    $("#login, #sign_up").show();
    $("#logout").hide();
}

// //show login page
// function showLoginPage(){
    
//     // remove jwt
//     setCookie("jwt", "", 1);

//     // login page html
//     var app_html = `
//         <h2>Login</h2>
//         <form id='login_form'>
//             <div class='form-group'>
//                 <label for='email'>Email address</label>
//                 <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>
//             </div>

//             <div class='form-group'>
//                 <label for='password'>Password</label>
//                 <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
//             </div>

//             <button type='submit' class='btn btn-primary'>Login</button>
//         </form>
//     `;        
//     clearResponse();
//     route('login', app_html);
//     //$('#content').html(app_html);
//     showLoggedOutMenu();
// }

// function to set cookie
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

// get or read cookie
function getCookie(cname){
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' '){
            c = c.substring(1);
        }

        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}
 
// function to make form values to json format
$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};

// remove any prompt messages
function clearResponse(){
    $('#response').html('');
}

// $('document').on('load', function(){
//     router.updatePageLinks();
// })