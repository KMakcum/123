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

    $('.cost_item .cost_btn').on('click', function () {
        let $this = $(this).closest('.cost_item');
        $('.new__cost_item').slideUp(0);
        $this.find('.new__cost_item').slideDown();
    })

    $('.add__cost_item').on('click', '.form__add_new_cost button', function (e) {
        e.preventDefault();
        let costCategoryName = $(this).closest('.add__cost_item').find('.cost_category_name').text()
        let costCategorySlug = $(this).closest('.add__cost_item').data('category')
        let formData = new FormData();
        formData.append('action', 'set_new_cost');
        formData.append('cost_category_slug', costCategorySlug);
        formData.append('cost_category_name', costCategoryName);
        formData.append('form', $(this).closest('form').serialize())

        ajaxSendCost(formData)
    })

    $('.add__cost_category_item').on('click', '.form__add_new_category_cost button', function (e) {
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
            },
        });
    }

});
