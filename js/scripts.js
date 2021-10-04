jQuery(document).ready(function(){
    $( ".js-datepicker" ).datepicker();

    $('.right_cost_popup .hide_show__popup').on('click', function () {
        $(this).closest('.right_cost_popup').toggleClass('show')
    })
});





