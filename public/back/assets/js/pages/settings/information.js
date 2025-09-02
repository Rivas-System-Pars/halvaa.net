$(document).ready(function () {

    $(document).on('click','.remove-cash-discount',function (e) {
        $(e.target).closest('.col-12.d-flex.align-items-center').remove();
    });
    $(document).on('click','.remove-per-purchase',function (e) {
        $(e.target).closest('.col-12.d-flex.align-items-center').remove();
    });
    $(document).on('click','.remove-discount-factor-row',function (e) {
        $(e.target).closest('.col-12.d-flex.align-items-center').remove();
    });
    document.getElementById('add-discount-factor-row').onclick = function (e) {
        const discountFactorRoItems = $('.discount-factor-row-items');
        const i = discountFactorRoItems.find('> div').length + 1;
        const html = '<div class="col-12 d-flex align-items-center">' +
            '<button type="button" class="remove-discount-factor-row border-0 bg-transparent">' +
            '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
            '</button>' +
            '<div class="flex-grow-1 row m-0">' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>حداقل تعداد سطر فاکتور</label>' +
            '<input type="text" name="discount_factor_rows['+i+'][count]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>میزان تخفیف</label>' +
            '<input type="text" name="discount_factor_rows['+i+'][discount_amount]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<fieldset class="checkbox">' +
            '<div class="vs-checkbox-con vs-checkbox-primary">' +
            '<input type="checkbox" name="discount_factor_rows['+i+'][is_percent]" value="1"/>' +
            '<span class="vs-checkbox">' +
            '<span class="vs-checkbox--check">' +
            '<i class="vs-icon feather icon-check"></i>' +
            '</span>' +
            '</span>' +
            '<span>درصد</span>' +
            '</div>' +
            '</fieldset>' +
            '</div>';
        discountFactorRoItems.append(html);
    }
    document.getElementById('add-per-purchase').onclick = function (e) {
        const perPurchaseItems = $('.per-purchase-items');
        const i = perPurchaseItems.find('> div').length + 1;
        const html = '<div class="col-12 d-flex align-items-center">' +
            '<button type="button" class="remove-cash-discount border-0 bg-transparent">' +
            '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
            '</button>' +
            '<div class="flex-grow-1 row m-0">' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>حداقل مقدار پرداختی</label>' +
            '<input type="text" name="per_purchases['+i+'][min_amount]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>میزان تخفیف</label>' +
            '<input type="text" name="per_purchases['+i+'][discount_amount]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<fieldset class="checkbox">' +
            '<div class="vs-checkbox-con vs-checkbox-primary">' +
            '<input type="checkbox" name="per_purchases['+i+'][is_percent]" value="1"/>' +
            '<span class="vs-checkbox">' +
            '<span class="vs-checkbox--check">' +
            '<i class="vs-icon feather icon-check"></i>' +
            '</span>' +
            '</span>' +
            '<span>درصد</span>' +
            '</div>' +
            '</fieldset>' +
            '</div>';
        perPurchaseItems.append(html);
    }
    document.getElementById('add-cash-discount').onclick = function (e) {
        const cashDiscountItems = $('.cash-discount-items');
        const i = cashDiscountItems.find('> div').length + 1;
        const html = '<div class="col-12 d-flex align-items-center">' +
            '<button type="button" class="remove-cash-discount border-0 bg-transparent">' +
            '<i class="vs-icon feather text-danger icon-x-circle"></i>' +
            '</button>' +
            '<div class="flex-grow-1 row m-0">' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>حداقل مقدار پرداختی</label>' +
            '<input type="text" name="cash_discounts['+i+'][min_amount]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="col-md-6">' +
            '<div class="form-group">' +
            '<div class="controls">' +
            '<label>میزان تخفیف</label>' +
            '<input type="text" name="cash_discounts['+i+'][discount_amount]" class="form-control valid"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<fieldset class="checkbox">' +
            '<div class="vs-checkbox-con vs-checkbox-primary">' +
            '<input type="checkbox" name="cash_discounts['+i+'][is_percent]" value="1"/>' +
            '<span class="vs-checkbox">' +
            '<span class="vs-checkbox--check">' +
            '<i class="vs-icon feather icon-check"></i>' +
            '</span>' +
            '</span>' +
            '<span>درصد</span>' +
            '</div>' +
            '</fieldset>' +
            '</div>';
        cashDiscountItems.append(html);
    }

    /*=========+===================
      Information Tab Js Codes
    ===============================*/

    $('#tags').tagsInput({
        'defaultText': 'افزودن',
        'width': '100%',
    });

    $('#province, #city').select2({
        rtl: true,
        width: '100%',
    });

    // validate form with jquery validation plugin
    jQuery('#information-form').validate({
        rules: {
            'info_site_title': {
                required: true,
            },

        },
        messages: {
            'info_site_title': {
                required: 'لطفا عنوان وبسایت را وارد کنید',
            },

        }
    });

    $('#province').change(function () {
        var id = $(this).find(":selected").val();
        $('#city').empty();

        $.ajax({
            type: 'get',
            url: '/province/get-cities',
            data: {id: id},
            success: function (data) {
                $(data).each(function () {
                    $('#city').append('<option value="' + $(this)[0].id + '">' + $(this)[0].name + '</option>')
                });
            },
            beforeSend: function () {
                block('#city-div');
            },
            complete: function () {
                unblock('#city-div');
            },

        });

    });

    $('#information-form').submit(function (e) {
        e.preventDefault();

        if ($(this).valid()) {
            var formData = new FormData(this);

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                success: function (data) {
                    Swal.fire({
                        type: 'success',
                        title: 'تغییرات با موفقیت ذخیره شد',
                        confirmButtonClass: 'btn btn-primary',
                        confirmButtonText: 'باشه',
                        buttonsStyling: false,
                    });

                    if (data.admin_route_prefix_changed) {
                        if (data.admin_route_prefix) {
                            window.location.href = FRONT_URL + '/admin/' + data.admin_route_prefix + '/settings/information'
                        } else {
                            window.location.href = FRONT_URL + '/admin/settings/information'
                        }
                    }
                },
                beforeSend: function (xhr) {
                    block('#main-card');
                    xhr.setRequestHeader("X-CSRF-TOKEN", $('meta[name="csrf-token"]').attr('content'));
                },
                complete: function () {
                    unblock('#main-card');
                },

                cache: false,
                contentType: false,
                processData: false
            });
        }

    });

});


$(document).ready(function () {

    //---------------------- google map js codes
    if (typeof google !== 'undefined') {
        var myLatlng = new google.maps.LatLng(info_latitude, info_Longitude);
    }
    var map;
    var gmarkers = [];

    function initialize() {
        var mapOptions = {
            zoom: 16,
            center: myLatlng,
            scrollwheel: true,
            mapTypeId: google.maps.MapTypeId.ROADMAP
        }

        map = new google.maps.Map(document.getElementById('googleMap'), mapOptions);

        google.maps.event.addListener(map, 'click', function (event) {
            addMapMarkers(event.latLng.lat(), event.latLng.lng());
        });

        addMapMarkers(info_latitude, info_Longitude);
    }


    function removeMarkers() {
        for (i = 0; i < gmarkers.length; i++) {
            gmarkers[i].setMap(null);
        }
    }

    if (typeof google !== 'undefined')
        google.maps.event.addDomListener(window, 'load', initialize);


    //---------------------- map.ir js codes
    var mapIr = new Mapp({
        element: '#mapIr',
        presets: {
            latlng: {
                lat: info_latitude,
                lng: info_Longitude,
            },
            zoom: 16
        },
        apiKey: mapIrApiKey
    });

    mapIr.addLayers();

    mapIr.map.on('click', function (e) {
        addMapMarkers(e.latlng.lat, e.latlng.lng)
    });

    function addMapMarkers(latitude, Longitude) {

        //------ google map
        removeMarkers();
        var googlemarker = new google.maps.Marker({
            position: new google.maps.LatLng(latitude, Longitude),
            map: map,
        });

        gmarkers.push(googlemarker);

        //-------- map.ir
        var mapirmarker = mapIr.addMarker({
            name: 'advanced-marker',
            latlng: {
                lat: latitude,
                lng: Longitude,
            },
            icon: mapIr.icons.red,
            popup: false,
            pan: false,
            draggable: false,
            history: false,
        });

        //------ change inputs
        $('#Longitude').val(Longitude);
        $('#latitude').val(latitude);
    }

    // add markers to both maps
    addMapMarkers(info_latitude, info_Longitude);

    $('#Longitude, #latitude').on('change', function () {
        if (!$('#Longitude').val() || !$('#latitude').val()) {
            return;
        }

        addMapMarkers($('#latitude').val(), $('#Longitude').val());
    });

    $('.info_map_type').on('change', function () {
        $('.map').hide();

        var checked = $('input[name=info_map_type]:checked').val();

        if (checked == 'google') {
            $('#googleMap').show();
        } else if (checked == 'mapir') {
            $('#mapIr').show();
        }
    });

    $('.info_map_type').trigger('change');
});
