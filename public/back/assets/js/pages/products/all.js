CKEDITOR.config.height = 400;
CKEDITOR.replace('description');

$(document).on("click", ".remove-gift-per-purchase-product", function (e) {
    const destroy = $(this).data('destroy');
    const parent = $(this).closest('.d-flex');
    doDeleteProductGifts(destroy,parent);
});
$(document).on("click", ".remove-gift-per-purchase", function (e) {
    const destroy = $(this).data('destroy');
    const parent = $(this).closest('.gift-per-purchase-product');
    doDeleteProductGifts(destroy,parent);
});

document.getElementById('add-gift-per-purchase').onclick = function () {
    const giftPerPurchaseItems = $('.gift-per-purchase-items');
    const i = giftPerPurchaseItems.find('> div').length + 1;
    const html = '<div class="gift-per-purchase-product">' +
        '<div class="col-12 d-flex align-items-center">' +
        '<button type="button" class="remove-gift-per-purchase border-0 bg-transparent">' +
        '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
        '</button>' +
        '<div class="flex-grow-1 row m-0">' +
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<div class="controls">' +
        '<label>حداقل تعداد خرید</label>' +
        '<input type="text" class="form-control valid" name="gift_products[' + i + '][count]">' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<div class="controls">' +
        '<label>محصولات</label>' +
        '<select class="form-control gift-products"></select>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-12 gift-per-purchase-products" data-key="gift_products[' + i + ']"></div>' +
        '<hr/>' +
        '</div>';
    giftPerPurchaseItems.append(html);
    initProductsSelect2($('.gift-products:last'));
}

$(".gift-products").each(function () {
    initProductsSelect2($(this));
});

function initProductsSelect2(select) {
    select.select2({
        rtl: true,
        width: '100%',
        minimumInputLength: 1,
        ajax: {
            url: window.Laravel.routes.products.publish,
            dataType: 'json',
            type: "GET",
            delay: 400,
            data: function (term) {
                return {
                    title: term.term
                };
            },
            processResults: function (data) {
                return {
                    results: data.products.map(function (item) {
                        return {
                            id: item.id,
                            text: item.title
                        }
                    })
                };
            }

        }
    }).on("change", function () {
        const data = $(this).select2('data')[0];
        const parent = $(this).closest('.gift-per-purchase-product');
        const giftPerPurchaseProducts = parent.find('.gift-per-purchase-products');
        const i = giftPerPurchaseProducts.find('> div').length + 1;
        const html = '<div class="d-flex">' +
            '<button type="button" class="remove-gift-per-purchase-product border-0 bg-transparent">' +
            '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
            '</button>' +
            '<div class="row m-0 flex-grow-1" data-product="' + data.id + '">' +
            '<div class="col-md-6 d-flex justify-content-start align-items-center">' + data.text + '</div>' +
            '<input type="hidden" name="' + giftPerPurchaseProducts.data('key') + '[products][' + i + '][product_id]" value="' + data.id + '">' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>تعداد</label>' +
            '<input type="text" name="' + giftPerPurchaseProducts.data('key') + '[products][' + i + '][quantity]" class="form-control valid">' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>';
        giftPerPurchaseProducts.append(html);
    });
}

$(document).on('click', '.remove-discount-per-purchase', function (e) {
    $(e.target).closest('.col-12.d-flex.align-items-center').remove();
});
document.getElementById('add-discount-per-purchase').onclick = function (e) {
    const discountPerPurchaseItems = $('.discount-per-purchase-items');
    const i = discountPerPurchaseItems.find('> div').length + 1;
    const html = '<div class="col-12 d-flex align-items-center">' +
        '<button type="button" class="remove-discount-per-purchase border-0 bg-transparent">' +
        '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
        '</button>' +
        '<div class="flex-grow-1 row m-0">' +
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<div class="controls">' +
        '<label>حداقل تعداد خرید</label>' +
        '<input type="text" name="discount_per_purchase[' + i + '][quantity]" class="form-control valid"/>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<div class="col-md-6">' +
        '<div class="form-group">' +
        '<div class="controls">' +
        '<label>میزان تخفیف</label>' +
        '<input type="text" name="discount_per_purchase[' + i + '][discount_amount]" class="form-control valid"/>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '</div>' +
        '<fieldset class="checkbox">' +
        '<div class="vs-checkbox-con vs-checkbox-primary">' +
        '<input type="checkbox" name="discount_per_purchase[' + i + '][is_percent]" value="1"/>' +
        '<span class="vs-checkbox">' +
        '<span class="vs-checkbox--check">' +
        '<i class="vs-icon feather icon-check"></i>' +
        '</span>' +
        '</span>' +
        '<span>درصد</span>' +
        '</div>' +
        '</fieldset>' +
        '</div>';
    discountPerPurchaseItems.append(html);
}

$('.tags').tagsInput({
    defaultText: 'افزودن',
    width: '100%',
    autocomplete_url: $('.tags').data('action')
});

$('.labels').tagsInput({
    defaultText: 'افزودن',
    width: '100%',
    height: '110px',
    autocomplete_url: $('.labels').data('action')
});

$('.product-category').select2ToTree({
    rtl: true,
    width: '100%'
});

$('.product-categories').select2ToTree({
    rtl: true,
    width: '100%'
});

// validate form with jquery validation plugin
jQuery('#product-create-form, #product-edit-form').validate({
    rules: {
        title: {
            required: true
        },
        weight: {
            required: true,
            digits: true
        }
    }
});

//------------ specification group js codes

var groupsCount = groupCount;

$('#add-product-specification-group').click(function () {
    var template = $('#specification-group').clone();

    var group = $('#specifications-area').append(template.html());

    var count = ++groupCount;
    groupsCount++;

    var input = group.find('input[name="specification_group"]');

    input.attr('name', 'specification_group[' + count + '][name]');
    input.data('group_name', count);

    groupSortable();

    setTimeout(() => {
        group.find('.specification-group').removeClass('.animated fadeIn');
    }, 700);
});

function groupSortable() {
    $('#specifications-area').sortable({
        opacity: 0.75,
        start: function (e, ui) {
            ui.placeholder.css({
                height: ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom')
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}

groupSortable();

$(document).on('click', '.remove-group', function () {
    var group = $(this).closest('.specification-group');

    group.addClass('animated fadeOut');

    setTimeout(() => {
        group.remove();
    }, 500);

    groupsCount--;
});

//------------ specifications js codes

$(document).on('click', '.add-specifaction', function () {
    var template = $('#specification-single').clone();

    var specification = $(this)
        .closest('.specification-group')
        .find('.all-specifications')
        .append(template.html());

    var count = ++specificationCount;
    var group_name = $(specification)
        .closest('.specification-group')
        .find('.group-input')
        .data('group_name');

    specification
        .find('input[name="special_specification"]')
        .attr(
            'name',
            'specification_group[' +
            group_name +
            '][specifications][' +
            count +
            '][special]'
        );
    specification
        .find('input[name="specification_name"]')
        .attr(
            'name',
            'specification_group[' +
            group_name +
            '][specifications][' +
            count +
            '][name]'
        );
    specification
        .find('textarea[name="specification_value"]')
        .attr(
            'name',
            'specification_group[' +
            group_name +
            '][specifications][' +
            count +
            '][value]'
        );

    specificationSortable();

    setTimeout(() => {
        specification
            .find('.single-specificition')
            .removeClass('.animated fadeIn');
    }, 700);
});

$(document).on('click', '.remove-specification', function () {
    var specification = $(this).closest('.single-specificition');

    specification.addClass('animated fadeOut');

    setTimeout(() => {
        specification.remove();
    }, 500);
});

function specificationSortable() {
    $('.all-specifications').sortable({
        opacity: 0.75,
        start: function (e, ui) {
            ui.placeholder.css({
                height: ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom')
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}

specificationSortable();

//------------ files js codes

function addProductFile() {
    var template = $('#files-template').clone();

    var file = $('#product-files-area')
        .append(template.html())
        .find('.single-file:last');
    var count = ++filesCount;

    file.find('input[name="title"]').attr(
        'name',
        'download_files[' + count + '][title]'
    );
    file.find('select[name="status"]').attr(
        'name',
        'download_files[' + count + '][status]'
    );
    file.find('input[name="file"]').attr(
        'name',
        'download_files[' + count + '][file]'
    );
    file.find('input[name="file"]').attr(
        'id',
        'download_files[' + count + '][id]'
    );
    file.find('label[for="file"]').attr(
        'for',
        'download_files[' + count + '][id]'
    );
    file.find('input[name="price"]').attr(
        'name',
        'download_files[' + count + '][price]'
    );
    file.find('input[name="discount"]').attr(
        'name',
        'download_files[' + count + '][discount]'
    );

    filesSortable();

    setTimeout(() => {
        file.removeClass('animated fadeIn');
    }, 700);
}

$(document).on('click', '#add-product-file', function () {
    addProductFile();
});

$(document).on('click', '.remove-file', function () {
    var file = $(this).closest('.single-file');

    file.addClass('animated fadeOut');

    setTimeout(() => {
        file.remove();
    }, 500);
});

if (filesCount == 0) {
    addProductFile();
}

function filesSortable() {
    $('#product-files-area').sortable({
        opacity: 0.75,
        start: function (e, ui) {
            ui.placeholder.css({
                height: ui.item.outerHeight(),
                'margin-bottom': ui.item.css('margin-bottom')
            });
        },
        helper: function (e, tr) {
            var $originals = tr.children();
            var $helper = tr.clone();
            $helper.children().each(function (index) {
                $(this).width($originals.eq(index).width());
            });
            return $helper;
        }
    });
}

filesSortable();

$('#product-type').on('change', function () {
    if ($(this).val() == 'physical') {
        $('.physical-item').show();
        $('.download-item').hide();
    } else {
        $('.physical-item').hide();
        $('.download-item').show();
    }
});

$('#product-type').trigger('change');

//------------ spectype js codes

$('#specifications_type').autocomplete({
    source: availableTypes
});

$('#specifications_type').change(function () {
    var value = $(this).val();

    if (availableTypes.includes(value) && !specifications_type_first_change) {
        addSpecTypeData();
    } else if (availableTypes.includes(value) && groupsCount != 0) {
        $('#specifications-modal').modal('show');
    } else if (availableTypes.includes(value) && groupsCount == 0) {
        addSpecTypeData();
    }

    specifications_type_first_change = true;

    $('#spec-div').show();
});

$('#add-spec-type-data').click(addSpecTypeData);

$('#specifications_type').on('keyup keypress', function (e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) {
        e.preventDefault();
        return false;
    }
});

function addSpecTypeData() {
    $.ajax({
        url: BASE_URL + '/spectypes/spec-type-data',
        type: 'GET',
        data: {
            name: $('#specifications_type').val()
        },
        success: function (data) {
            groupCount = data.groupCount;
            specificationCount = data.specificationCount;
            groupsCount = data.groupCount;

            $('#specifications-area').html(data.view);
            specificationSortable();
            groupSortable();
        },
        beforeSend: function (xhr) {
            block('#specifications-card');
        },
        complete: function () {
            unblock('#specifications-card');
        }
    });
}

//------------ prices js codes

$('#add-product-prices').click(function () {
    addProductPrice();
});

$(document).on('click', '.remove-product-price', function () {
    var price = $(this).closest('.single-price');

    price.addClass('animated fadeOut');

    setTimeout(() => {
        price.remove();
    }, 500);
});

if (priceCount == 0) {
    addProductPrice();
}

function addProductPrice() {
    var template = $('#prices-template').clone();

    var price = $('#product-prices-div').append(template.html());

    var count = ++priceCount;
    let unit = price
        .closest('.product-prices-tab')
        .find('select[name="currency_id"] option:selected')
        .data('title');

    price
        .find('select[name="attribute"]')
        .attr('name', 'prices[' + count + '][attributes][]');

    price
        .find('input[name="price"]')
        .attr('name', 'prices[' + count + '][price]')
        .data('unit', unit);
    price
        .find('input[name="discount"]')
        .attr('name', 'prices[' + count + '][discount]');
    price
        .find('input[name="cart_max"]')
        .attr('name', 'prices[' + count + '][cart_max]');
    price
        .find('input[name="cart_min"]')
        .attr('name', 'prices[' + count + '][cart_min]');
    price
        .find('input[name="stock"]')
        .attr('name', 'prices[' + count + '][stock]');
    price
        .find('input[name="discount_expire"]')
        .attr('name', 'prices[' + count + '][discount_expire]');

    setTimeout(() => {
        price.find('.single-price').removeClass('.animated fadeIn');
    }, 700);
}

$('select[name="currency_id"]').on('change', function () {
    var unit = $(this).find(':selected').data('title');

    $('.single-price .amount-input').data('unit', unit).trigger('keyup');
});

$(document).on(
    'keyup',
    '.single-price .price, .single-price .discount',
    function () {
        let unit = $(this)
            .closest('.product-prices-tab')
            .find('select[name="currency_id"] option:selected')
            .data('amount');

        let roundingAmount = $(this)
            .closest('.product-prices-tab')
            .find('select[name="rounding_amount"] option:selected')
            .data('value');

        let roundingType = $(this)
            .closest('.product-prices-tab')
            .find('select[name="rounding_type"] option:selected')
            .data('value');

        let discount = $(this).closest('.single-price').find('.discount').val();

        let price = $(this).closest('.single-price').find('.price').val();

        price = price ? parseFloat(price) : 0;
        unit = parseFloat(unit);
        discount = discount ? parseFloat(discount) : 0;
        roundingAmount =
            roundingAmount != 'no' ? parseFloat(roundingAmount) : 0;

        let finalPrice = (price - price * (discount / 100)) * unit;

        finalPrice = toRoundInt(finalPrice, roundingType, roundingAmount);

        finalPrice = +finalPrice.toFixed(2);

        let finalPriceText = number_format(finalPrice) + ' تومان';

        $(this)
            .closest('.single-price')
            .find('.final-price')
            .val(finalPriceText);
    }
);

$(document).on('change', '.prices-option-div select', function () {
    $('.single-price .price').trigger('keyup');
});

$('.prices-option-div select').trigger('change');

//------------ generate slug

$('#generate-product-slug').click(function (e) {
    e.preventDefault();

    var title = $('input[name="meta_title"]').val();

    $.ajax({
        url: BASE_URL + '/product/slug',
        type: 'POST',
        data: {
            title: title
        },
        success: function (data) {
            $('#slug').val(data.slug);
        },
        beforeSend: function (xhr) {
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
            $('#slug-spinner').show();
        },
        complete: function () {
            $('#slug-spinner').hide();
        }
    });
});

//------------ dropzone sortable

$('.dropzone-area').sortable({
    items: '.dz-preview',
    opacity: 0.75,
    start: function (e, ui) {
        ui.placeholder.css({
            height: ui.item.outerHeight(),
            'margin-bottom': ui.item.css('margin-bottom')
        });
    },
    helper: function (e, tr) {
        var $originals = tr.children();
        var $helper = tr.clone();
        $helper.children().each(function (index) {
            $(this).width($originals.eq(index).width());
        });
        return $helper;
    }
});

//------------ spectype js codes

$('#brand').autocomplete({
    source: BASE_URL + '/brands/ajax/get',
    delay: 1000
});

//------------ publish time picker js codes

// $('#publish_date_picker').on('keydown', function (e) {
//     e.preventDefault();
//     $(this).val('');
//     $('#publish_date').val('');
// });

function doDeleteProductGifts(destroy,parent) {
    if (destroy) {
        $.ajax({
            url: destroy,
            method: 'DELETE',
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (response) {
                parent.remove();
                toastr.success(response.msg, "موفق");
            },
            error: function (error) {
                toastr.error(JSON.parse(error.responseText).msg, 'خطا');
            }
        });
    } else {
        parent.remove();
    }
}
