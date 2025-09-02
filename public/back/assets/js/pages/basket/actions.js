$(document).ready(function () {
    $('#requirements').select2({
        rtl: true,
        width: '100%',
        placeholder: {
            id: null, // the value of the option
            text: 'محصولات را انتخاب کنید'
        },
    });
    $(document).on('click','.remove-gift-products-item',function (e) {
        $(e.target).closest('.col-12.d-flex.align-items-center').remove();
    });
    $('#gifts').select2({
        rtl: true,
        width: '100%',
        placeholder: {
            id: null, // the value of the option
            text: 'انتخاب کنید'
        },
    }).on('select2:selecting', function (e) {
        const giftProductsList = $('.gift-products-list');
        const id = e.params.args.data.id;
        if (giftProductsList.find('> div[data-product="' + id + '"]').length) {
            alert("محصول در لیست هدایا وجود دارد")
        } else {
            const i = giftProductsList.find('> div').length + 1;
            const html = '<div class="col-12 d-flex align-items-center" data-product="' + id + '">' +
                '<button type="button" class="remove-gift-products-item border-0 bg-transparent">' +
                '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
                '</button>' +
                '<input type="hidden" name="gifts['+i+'][product_id]" value="'+id+'">' +
                '<div class="flex-grow-1 row m-0">' +
                '<div class="col-md-6">' +
                '<p class="m-0 font-size-xsmall">' + e.params.args.data.text + '</p>' +
                '</div>' +
                '<div class="col-md-6">' +
                '<div class="form-group">' +
                '<div class="controls">' +
                '<label>تعداد</label>' +
                '<input type="text" name="gifts[' + i + '][quantity]" class="form-control valid" value="">' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '</div>';
            giftProductsList.append(html);
        }
    });
});
