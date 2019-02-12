let showVerifyAccount = (url, params) => {
    // remove jwt
    setCookie("jwt", "", 1);
    loadHTML(url, 'hide-nav-view');
    // setTimeout(function(){
    //     $('#access_code').val(params.access_code);
    // }, 15);
    // $id('nav-activate').hidden = true;
    // $id('side-activate').hidden = true;
};