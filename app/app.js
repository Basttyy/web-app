$(document).ready(function(){
    // show home page
    showHomePage();
    $(document).on('click', '#home', function(){
        showHomePage();
        clearResponse();
    });
    // app html
    app_html="";
    // navbar 
    app_html += '<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">';
        app_html += '<a class="navbar-brand" href="#">Navbar</a>';
        app_html += '<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">';
            app_html += '<span class="navbar-toggler-icon"></span>';
        app_html += '</button>';
        app_html += '<div class="collapse navbar-collapse" id="navbarNavAltMarkup">';
            app_html += '<div class="navbar-nav">';
                app_html += '<a class="nav-item nav-link" href="#" id="home">Home</a>';
                app_html += '<a class="nav-item nav-link" href="#" id="update_account">Account</a>';
                app_html += '<a class="nav-item nav-link" href="#" id="logout">Logout</a>';
                app_html += '<a class="nav-item nav-link" href="#" id="login">Login</a>';
                app_html += '<a class="nav-item nav-link" href="#" id="sign_up">Sign Up</a>';
            app_html += '</div>';
        app_html += '</div>';
    app_html += '</nav>';

    //container
    app_html += '<main role="main" class="container starter-template">';

        app_html += '<div class="row">';
            app_html += '<div class="col">';        
                //where prompt  messages will appear
                app_html += '<div id="response"></div>';        
                //where main content will appear
                app_html += '<div id="content"></div>'
            app_html += '</div>';
        app_html += '</div>'        
    app_html += '</main>'
 
    /*app_html+="<div class='container'>";
 
        app_html+="<div class='page-header'>";
            app_html+="<h1 id='page-title'>Read Products</h1>";
        app_html+="</div>";
 
        // this is where the contents will be shown.
        app_html+="<div id='page-content'></div>";
 
    app_html+="</div>";*/
 
    // inject to 'app' in index.html
    $("#app").html(app_html);
 
});
 
// change page title
function changePageTitle(page_title){
 
    // change page title
    $('#page-title').text(page_title);
 
    // change title tag
    document.title=page_title;
}

// show home page
function showHomePage(){
        
    // validate jwt to verify access
    var jwt = getCookie('jwt');
    $.post("api/auth/validate_token.php", JSON.stringify({ jwt:jwt })).done(function(result) {

        // if valid, show homepage
        var html = `
            <div class="card">
                <div class="card-header">Welcome to Home!</div>
                <div class="card-body">
                    <h5 class="card-title">You are logged in.</h5>
                    <p class="card-text">You won't be able to access the home and account pages if you are not logged in.</p>
                </div>
            </div>
            `;
        
        $('#content').html(html);
        showLoggedInMenu();
    })
    // show login page on error
    .fail(function(result){
        showLoginPage();
        $('#response').html("<div class='alert alert-danger'>Please login to access the home page.</div>");
    });
}

// if the user is logged in
function showLoggedInMenu(){
    // hide login and sign up from navbar & show logout button
    $("#login, #sign_up").hide();
    $("#logout").show();
}

// if the user is logged out
function showLoggedOutMenu(){
    // show login and sign up from navbar & hide logout button
    $("#login, #sign_up").show();
    $("#logout").hide();
}

//show login page
function showLoginPage(){
    
    // remove jwt
    setCookie("jwt", "", 1);

    // login page html
    var html = `
        <h2>Login</h2>
        <form id='login_form'>
            <div class='form-group'>
                <label for='email'>Email address</label>
                <input type='email' class='form-control' id='email' name='email' placeholder='Enter email'>
            </div>

            <div class='form-group'>
                <label for='password'>Password</label>
                <input type='password' class='form-control' id='password' name='password' placeholder='Password'>
            </div>

            <button type='submit' class='btn btn-primary'>Login</button>
        </form>
        `;

        $('#content').html(html);
        clearResponse();
        showLoggedOutMenu();
}

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