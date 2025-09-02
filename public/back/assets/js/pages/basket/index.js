'use strict';
// Class definition

var datatable;

var basket_datatable = (function () {
    // Private functions

    var options = {
        // datasource definition
        data: {
            type: 'remote',
            source: {
                read: {
                    url: $('#baskets_datatable').data('action'),
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr(
                            'content'
                        )
                    },
                    map: function (raw) {
                        // sample data mapping
                        var dataSet = raw;
                        if (typeof raw.data !== 'undefined') {
                            dataSet = raw.data;
                        }
                        return dataSet;
                    },
                    params: {
                        query: null
                    }
                }
            },
            pageSize: 10,
            serverPaging: true,
            serverFiltering: true,
            serverSorting: true
        },

        layout: {
            scroll: true
        },

        rows: {
            autoHide: false
        },

        // columns definition
        columns: [
            {
                field: 'id',
                title: '#',
                sortable: false,
                width: 20,
                selector: {
                    class: ''
                },
                textAlign: 'center'
            },
            {
                field: 'productid',
                sortable: false,
                width: 50,
                title: 'ID',
                template: function (row) {
                    return row.id;
                }
            },
            {
                field: 'title',
                title: 'عنوان سبد محصول',
                width: 200,
                template: function (row) {
                    return row.title;
                }
            },
            {
                field: 'actions',
                title: 'عملیات',
                textAlign: 'center',
                sortable: false,
                width: 150,
                overflow: 'visible',
                autoHide: false,
                template: function (row) {
                    return (
                        '<a href ="' +
                        window.location.href+
                        "/"+
                        row.id +
                        '/edit"class="btn btn-warning waves-effect waves-light">ویرایش</a>\
                    <button data-toggle="modal" data-target="#delete-modal" data-action="' +
                        row.id +
                        '" class="btn btn-danger waves-effect waves-light btn-delete">حذف</button>'
                    );
                }
            }
        ]
    };

    var initDatatable = function () {
        // enable extension
        options.extensions = {
            // boolean or object (extension options)
            checkbox: true
        };

        datatable = $('#baskets_datatable').KTDatatable(options);

        $('#filter-products-form .datatable-filter').on('change', function () {
            formDataToUrl('filter-products-form');
            datatable.setDataSourceQuery(
                $('#filter-products-form').serializeJSON()
            );
            datatable.reload();
        });

        datatable.on('datatable-on-click-checkbox', function (e) {
            var ids = datatable.checkbox().getSelectedId();
            var count = ids.length;

            $('#datatable-selected-rows').html(count);

            if (count > 0) {
                $('.datatable-actions').collapse('show');
            } else {
                $('.datatable-actions').collapse('hide');
            }
        });

        datatable.on('datatable-on-reloaded', function (e) {
            $('.datatable-actions').collapse('hide');
        });
    };

    return {
        // public functions
        init: function () {
            initDatatable();
        }
    };
})();

jQuery(document).ready(function () {
    basket_datatable.init();
});

$(document).on('click', '.btn-delete', function () {
    $('#basket-delete-form').attr('action', $(this).data('action'));
});

$('#basket-delete-form').on('submit', function (e) {
    e.preventDefault();

    $('#delete-modal').modal('hide');

    $.ajax({
        url: window.location.href+'/'+$(this).attr('action'),
        type: 'DELETE',
        success: function (data) {
            toastr.success('محصول با موفقیت حذف شد.');
            datatable.reload();
        },
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        },
        complete: function () {
            unblock('#main-card');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});

$('#basket-multiple-delete-form').on('submit', function (e) {
    e.preventDefault();

    $('#multiple-delete-modal').modal('hide');

    var formData = new FormData(this);
    var ids = datatable.checkbox().getSelectedId();

    ids.forEach(function (id) {
        formData.append('ids[]', id);
    });

    $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: formData,
        success: function (data) {
            toastr.success('محصولات انتخاب شده با موفقیت حذف شدند.');
            datatable.reload();
        },
        beforeSend: function (xhr) {
            block('#main-card');
            xhr.setRequestHeader(
                'X-CSRF-TOKEN',
                $('meta[name="csrf-token"]').attr('content')
            );
        },
        complete: function () {
            unblock('#main-card');
        },
        cache: false,
        contentType: false,
        processData: false
    });
});
