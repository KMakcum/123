jQuery(document).ready(function(){
    $(document).on('click', '.datepicker--cell-day', function () {
        let $this = $(this);
        let day = $this.data('date');
        let month = $this.data('month') + 1;//datapicker показывает на один месяц назад
        let year = $this.data('year');

        $this.append('111111');
        console.log('day ',day);
        console.log('month ',month);
        console.log('year ',year);
        $.ajax({
            url: costAjaxSettings.ajax_url,
            method: 'POST',
            data: {
                action : 'get_cost_categories_popup',
                nonce : costAjaxSettings.nonce,
            },
            // dataType: 'json',
            success: function (response) {
                if (response.success) {
                    // $('.datepicker--content').append(response.data.popup)
                    console.log(response);
                    // let data = jQuery.parseJSON(response.data.category);
                    // popupDayCost(data)
                    // console.log(data);

                } else {
                    console.log('else');
                }

            },
        });
    })

    $('input.expenses_date__input').on('change', function () {
        let date = $(this).val();
        console. log(date)
        change_period(date);
    })

    $('.add_expenses_btn').on('click', function (e) {
        e.preventDefault();
        let costCategoryName = $(this).closest('.new__expenses_category').find('.new__expenses_category--name').text()
        let costCategorySlug = $(this).closest('.new__expenses_category').data('category')
        let formData = new FormData();
        formData.append('action', 'set_new_cost');
        formData.append('cost_category_slug', costCategorySlug);
        formData.append('cost_category_name', costCategoryName);
        formData.append('form', $(this).closest('form').serialize())

        ajaxSendCost(formData)
    })

    $('.add_expenses_category_btn').on('click', function (e) {
        e.preventDefault();
        let formData = new FormData();
        formData.append('action', 'set_new_category_cost');
        formData.append('form', $(this).closest('form').serialize())

        ajaxSendCost(formData)
    })

    function ajaxSendCost(formData) {
        $.ajax({
            url: costAjaxSettings.ajax_url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (response) {
                console.log(response);
                window.location.reload()
            },
        });
    }

    function change_period(date) {

    }

});
