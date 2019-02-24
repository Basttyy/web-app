// getElementById wrapper
function $id(id) {
  return document.getElementById(id);
}

// asyncrhonously fetch the html template partial from the file directory,
// then set its contents to the html of the parent element
function loadHTML(url, id) {
  req = new XMLHttpRequest();
  req.open('GET', url);
  req.send();
  req.onload = () => {
    $id(id).innerHTML = req.responseText;
  };
}

// use #! to hash
router = new Navigo(null, true, '#');
router.on({
  // 'view' is the id of the div element inside which we render the HTML
  'login': () => {
    prepareLogin('./app/views/auth/login.html');
  },
  'signup': () => {
    showSignupForm('./app/views/auth/signup.html');
  },
  'verify-account/:access_code': (params) =>{
    showVerifyAccount('./app/views/auth/verify-account.html', params);
  },
  'reset-password/:access_code': (params) =>{
    showResetPassword('./app/views/auth/reset-password.html', params);
  },
  'forgot-password': () =>{
    showRequestPassForm('./app/views/auth/forgot-password.html');
  },
  'view-profile': () =>{
    
  },
  'update-profile': () => {
  showUpdateProfilePage('./app/views/auth/update-profile.html');
  },
  'user-details': () =>{

  },
  'lock-screen': () =>{

  },
  'payment': () =>{

  }
});

// set the default route
router.on(() => {
  showDefaultPage('./app/views/default.html');
});

// set the 404 route
router.notFound((query) => { $id('hide-nav-view').innerHTML = '<h3>Couldn\'t find the page you\'re looking for...</h3>'; });

router.resolve();
