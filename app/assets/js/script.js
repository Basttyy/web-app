//global variable declarations
var url = window.location;

//functions to run when the documents finish loading
$(document).ready(function(){
    $('select').material_select();
    $('.parallax').parallax();
    $(".button-collapse").sideNav();
    $('.datepicker').pickadate({
        selectMonths: true, // Creates a dropdown to control month
        selectYears: 30, // Creates a dropdown of 15 years to control year,
        today: 'Today',
        clear: 'Clear',
        close: 'Ok',
        closeOnSelect: true // Close upon selecting a date,
    });
    
    $('.timepicker').pickatime({
        default: 'now', // Set default time: 'now', '1:30AM', '16:30'
        fromnow: 0,       // set default time to * milliseconds from now (using with default = 'now')
        twelvehour: false, // Use AM/PM or 24-hour format
        donetext: 'OK', // text for done-button
        cleartext: 'Clear', // text for clear-button
        canceltext: 'Cancel', // Text for cancel-button
        autoclose: false, // automatic close timepicker
        ampmclickable: true, // make AM PM clickable
        aftershow: function(){} //Function for after opening timepicker
    });
    $('ul.navi a').filter(function(){
        return this.href == url;
    }).parent().addClass('active');
    if($(window).width() > 900){
        $('#nav-div').addClass('navbar-fixed');
    }
});

//functions to run when the screen size changes
$(window).resize(function(){
    if($(window).width() > 900){
        $('#nav-div').addClass('navbar-fixed');
    }else{
        $('#nav-div').removeClass('navbar-fixed');
    }
})