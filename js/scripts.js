jQuery(document).ready(function(){
    $( ".js-datepicker" ).datepicker();


    $('.expenses_list .expenses_btn__trigger').on('click', function () {
        $(this).next('.new__expenses_list').slideToggle();
    });
    $('.new__expenses_category .new__expenses_category--name').on('click', function () {
        let newCategoryTrigger = $(this).closest('.new__expenses_category');
        if (!newCategoryTrigger.hasClass('open')) {
            $('.add__expenses_category').slideUp();
            $('.new__expenses_category').removeClass('open');
            newCategoryTrigger.toggleClass('open');
            newCategoryTrigger.children('.add__expenses_category').slideToggle();
        }
    });
    $('.right_cost_popup .hide_show__popup').on('click', function () {
        $(this).closest('.right_cost_popup').toggleClass('show')
    })
});





