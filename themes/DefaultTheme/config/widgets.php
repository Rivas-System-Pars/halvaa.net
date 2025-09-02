<?php

return [
    'event' => [
        'title' =>  'رویداد های روز',
        'image' =>  'widgets/performance.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],

        ],
        'rules'     =>  [
            'title' =>  'required',
        ]

    ],
    'performance'  =>  [
        'title' =>  'مزیت های شرکت',
        'image' =>  'widgets/performance.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],

        ],
        'rules'     =>  [
            'title' =>  'required',
            'image' =>  'required|image',
        ]
    ],
    'products-timer-card' => [
        'title' => 'محصولات با تخفیفات زمان دار',
        'image' => 'widgets/special.jpg',
        'options' => [
            [
                'title'      => 'نوع محصولات',
                'key'        => 'products_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'all',
                        'title' => 'همه'
                    ],
                    [
                        'value' => 'discount',
                        'title' => 'تخفیف خورده'
                    ],
                    [
                        'value' => 'special',
                        'title' => 'پیشنهاد ویژه'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'وضعیت موجودی',
                'key'        => 'inventory_status',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'all',
                        'title' => 'همه'
                    ],
                    [
                        'value' => 'available',
                        'title' => 'موجود'
                    ],
                    [
                        'value' => 'unavailable',
                        'title' => 'نا موجود'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'نوع مرتب سازی',
                'key'        => 'sort_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'latest',
                        'title' => 'جدیدترین'
                    ],
                    [
                        'value' => 'sell',
                        'title' => 'پرفروش ترین'
                    ],
                    [
                        'value' => 'view',
                        'title' => 'پربازدید ترین'
                    ],
                    [
                        'value' => 'cheapest',
                        'title' => 'ارزانترین'
                    ],
                    [
                        'value' => 'expensivest',
                        'title' => 'گرانترین'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'نمایش محصولات موجود در اول',
                'key'        => 'order_by_stock',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'yes',
                        'title' => 'بله'
                    ],
                    [
                        'value' => 'no',
                        'title' => 'خیر'
                    ]
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'لینک',
                'key'        => 'link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عنوان لینک',
                'key'        => 'link_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'رنگ پس زمینه کادر',
                'key'        => 'block_color',
                'input-type' => 'input',
                'default'    => '#ef394e',
                'type'       => 'color',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'تصویر',
                'key'        => 'image',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 850 * 500'
            ],
            [
                'title'      => 'تعداد',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'class'      => 'col-md-4 col-6',
                'default'    => '10',
                'attributes' => 'required'
            ],
            [
                'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                'key'        => 'categories',
                'input-type' => 'product_categories',
                'class'      => 'col-md-9',
            ],
            [
                'title'      => 'شامل محصولات زیر دسته ها',
                'key'        => 'sub_category_products',
                'input-type' => 'select',
                'class'      => 'col-md-3',
                'options'    => [
                    [
                        'value' => 'yes',
                        'title' => 'بله'
                    ],
                    [
                        'value' => 'no',
                        'title' => 'خیر'
                    ]
                ],
            ],
            [
                'title' =>  'تاریخ اتمام تخفیفات',
                'key'   =>  'end_date',
                'input-type'    =>  'input',
                'type'       => 'text',
                'class'      =>  'col-md-3 ',
                'attributes'  =>  'data-jdp',
            ],
            [
                'title' =>  'زمان اتمام تخفیفات',
                'key'   =>  'end_time',
                'input-type'    =>  'input',
                'type'       => 'time',
                'class'      =>  'col-md-3 ',

            ],
        ],
        'rules' => [
            'products_type'    => 'required|in:all,discount,special',
            'inventory_status' => 'required|in:all,available,unavailable',
            'sort_type'        => 'required|in:latest,sell,view,cheapest,expensivest',
            'order_by_stock'   => 'required|in:yes,no',
            'link'             => 'nullable|string',
            'link_title'       => 'nullable|string',
            'block_color'      => 'nullable|string',
            'number'           => 'required',
            'image'            => 'nullable|image',
        ]
    ],
    'banner-get-demo'  =>  [
        'title' =>  'بنر دریافت دمو',
        'image' => 'widgets/banner_get_demo.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'لینک',
                'key'        => 'link1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'تصویر',
                'key'        => 'image',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 850 * 500'
            ],
        ],
        'rules'     =>  [
            'title1' =>  'required|string',
            'link1' =>  'required|string',
            'image' =>  'required',
        ]

    ],
    'product-info'  =>  [
        'title' =>  'معرفی محصولات',
        'image' => 'widgets/product_info.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
        ],
        'rules'     =>  [
            'title' =>  'required|string',
        ]
    ],

    'counseling'    =>  [
        'title' =>  'مشاوره و اجرای سامانه های تحت وب',
        'image' =>   'widgets/counseling.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن سمت راست',
                'key'        => 'right_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'لینک سمت راست',
                'key'        => 'right_link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن وسط',
                'key'        => 'center_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'لینک متن وسط',
                'key'        => 'center_link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن سمت چپ',
                'key'        => 'left_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'لینک متن سمت چپ',
                'key'        => 'left_link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
        ],
        'rules'     =>  [
            'title'         =>  'required|string',
            'left_link'     =>  'required|string',
            'left_title'    =>  'required|string',
            'center_link'   =>  'required|string',
            'center_title'  =>  'required|string',
            'right_link'    =>  'required|string',
            'right_title'   =>  'required|string',
        ]
    ],

    'services-slider'   =>  [
        'title' =>  'اسلایدر مشخصات محصولات',
        'image' =>  'widgets/services-slider.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'انتخاب دسته بندی ها',
                'key'        => 'categories',
                'input-type' => 'product_categories',
                'class'      => 'col-md-12',
            ],
        ],
        'rules'     =>  [
            'title'             =>  'required|string',
            'categories'        =>  'required|array',
            'categories'        =>  'required',
            'categories.*'      =>  'exists:categories,id',
        ]
    ],
    'coworker-sliders'   =>  [
        'title' =>  'مشتریان ما',
        'image' =>  'widgets/coworker-sliders.png',

        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'تعداد قابل نمایش',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'default'    => '10',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'required'
            ]
        ],
        'rules'     =>  [
            'title' =>  'required|string',
            'number' => 'required',

        ]
    ],
    'about-company'   =>  [
        'title' =>  'درباره ما',
        'image' =>  'widgets/about-company.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن',
                'key'        => 'title2',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس اصلی',
                'key'        => 'image',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => 'فیلم',
                'key'        => 'video',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'يو کمتر از 5 مگابایت باشد'
            ],
        ],
        'rules'     =>  [
            'title1' =>  'required|string',
            'title2' =>  'required|string',
            'image' =>  'required|image',
            'video' =>  'required',
        ]

    ],
    'why-us'   =>  [
        'title' =>  'چرا ما؟',
        'image' =>  'widgets/why-us.png',
        'options'   =>  [
            [
                'title'      => ' موضوع',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => ' عنوان اصلی اول',
                'key'        => 'title1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن اول',
                'key'        => 'text1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس اول',
                'key'        => 'image1',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => ' عنوان اصلی دوم',
                'key'        => 'title2',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن دوم',
                'key'        => 'text2',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس دوم',
                'key'        => 'image2',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => ' عنوان اصلی سوم',
                'key'        => 'title3',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن سوم',
                'key'        => 'text3',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس سوم',
                'key'        => 'image3',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => ' عنوان اصلی چهارم',
                'key'        => 'title4',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن چهارم',
                'key'        => 'text4',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس چهارم',
                'key'        => 'image4',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => ' عنوان اصلی پنجم',
                'key'        => 'title5',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن پنجم',
                'key'        => 'text5',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس پنجم',
                'key'        => 'image5',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
            [
                'title'      => ' عنوان اصلی ششم',
                'key'        => 'title6',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'متن ششم',
                'key'        => 'text6',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عکس ششم',
                'key'        => 'image6',
                'input-type' => 'file',
                'type'       => 'file',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'accept="image/*"',
                'help'       => 'بهترین اندازه 320 * 456'
            ],
        ],
        'rules'     =>  [
            'title' =>  'required|string',
            'title1' =>  'required|string',
            'title2' =>  'required|string',
            'title3' =>  'nullable|string',
            'title4' =>  'nullable|string',
            'title5' =>  'nullable|string',
            'title6' =>  'nullable|string',
            'text1' =>  'required|string',
            'text2' =>  'required|string',
            'text3' =>  'nullable|string',
            'text4' =>  'nullable|string',
            'text5' =>  'nullable|string',
            'text6' =>  'nullable|string',
            'image1' =>  'required|image',
            'image2' =>  'required|image',
            'image3' =>  'nullable|image',
            'image4' =>  'nullable|image',
            'image5' =>  'nullable|image',
            'image6' =>  'nullable|image',
        ]
    ],
    'question-sort'   =>  [
        'title' =>  'سوالات متداول',
        'image' =>  'widgets/question-sort.png',
        'options'   =>  [
            [
                'title'      => 'عنوان اصلی',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'پرسش اول',
                'key'        => 'title1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'جواب اول',
                'key'        => 'text1',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'پرسش دوم',
                'key'        => 'title2',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'جواب دوم',
                'key'        => 'text2',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'پرسش سوم',
                'key'        => 'title3',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'جواب سوم',
                'key'        => 'text3',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'پرسش چهارم',
                'key'        => 'title4',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'جواب چهارم',
                'key'        => 'text4',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
        ],
        'rules'     =>  [
            'title' =>  'required|string',
            'title1' =>  'required|string',
            'title2' =>  'required|string',
            'title3' =>  'nullable|string',
            'title4' =>  'nullable|string',
            'text1' =>  'required|string',
            'text2' =>  'required|string',
            'text3' =>  'nullable|string',
            'text4' =>  'nullable|string',
        ]
    ],

    'main-slider' => [
        'title' => 'اسلایدر اصلی و بنر کناری',
        'image' => 'widgets/slider.jpg',
        'options' => [
            [
                'title'      => 'تعداد اسلایدر',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'default'    => '5',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'required'
            ],
            [
                'title'      => 'جایگاه بنر',
                'key'        => 'banner_position',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'left',
                        'title' => 'سمت چپ'
                    ],
                    [
                        'value' => 'right',
                        'title' => 'سمت راست'
                    ]
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'ترتیب نمایش',
                'key'        => 'ordering',
                'input-type' => 'select',
                'class'      => 'col-md-4',
                'options'    => [
                    [
                        'value' => 'asc',
                        'title' => 'صعودی'
                    ],
                    [
                        'value' => 'desc',
                        'title' => 'نزولی'
                    ]
                ],
            ],
        ],
        'rules' => [
            'number' => 'required',
            'banner_position' => 'required|in:right,left'
        ]
    ],

    'main-slider-fullpage' => [
        'title' => 'اسلایدر تمام صفحه',
        'image' => 'widgets/slider.jpg',
        'options' => [
            [
                'title'      => 'تعداد اسلایدر',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'default'    => '5',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'required'
            ],
            [
                'title'      => 'جایگاه بنر',
                'key'        => 'banner_position',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'left',
                        'title' => 'سمت چپ'
                    ],
                    [
                        'value' => 'right',
                        'title' => 'سمت راست'
                    ]
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'ترتیب نمایش',
                'key'        => 'ordering',
                'input-type' => 'select',
                'class'      => 'col-md-4',
                'options'    => [
                    [
                        'value' => 'asc',
                        'title' => 'صعودی'
                    ],
                    [
                        'value' => 'desc',
                        'title' => 'نزولی'
                    ]
                ],
            ],
        ],
        'rules' => [
            'number' => 'required',
            'banner_position' => 'required|in:right,left'
        ]
    ],

    'products-default-block' => [
        'title' => 'آرامگاه ها با پس زمینه ساده',
        'image' => 'widgets/products-default.jpg',
        'options' => [
            [
                'title'      => 'عنوان',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            // [
            //     'title'      => 'نوع محصولات',
            //     'key'        => 'products_type',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'all',
            //             'title' => 'همه'
            //         ],
            //         [
            //             'value' => 'discount',
            //             'title' => 'تخفیف خورده'
            //         ],
            //         [
            //             'value' => 'special',
            //             'title' => 'پیشنهاد ویژه'
            //         ],
            //     ],
            //     'attributes' => 'required'
            // ],
            // [
            //     'title'      => 'وضعیت موجودی',
            //     'key'        => 'inventory_status',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'all',
            //             'title' => 'همه'
            //         ],
            //         [
            //             'value' => 'available',
            //             'title' => 'موجود'
            //         ],
            //         [
            //             'value' => 'unavailable',
            //             'title' => 'نا موجود'
            //         ],
            //     ],
            //     'attributes' => 'required'
            // ],
            [
                'title'      => 'نوع مرتب سازی',
                'key'        => 'sort_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'desc',
                        'title' => 'جدیدترین'
                    ],
                    // [
                    //     'value' => 'sell',
                    //     'title' => 'پرفروش ترین'
                    // ],
                    //[
                      //  'value' => 'profile_views',
                        //'title' => 'پربازدید ترین'
                    //],
                    // [
                    //     'value' => 'cheapest',
                    //     'title' => 'ارزانترین'
                    // ],
                    // [
                    //     'value' => 'expensivest',
                    //     'title' => 'گرانترین'
                    // ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'لینک',
                'key'        => 'link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عنوان لینک',
                'key'        => 'link_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            // [
            //     'title'      => 'نمایش محصولات موجود در اول',
            //     'key'        => 'order_by_stock',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'yes',
            //             'title' => 'بله'
            //         ],
            //         [
            //             'value' => 'no',
            //             'title' => 'خیر'
            //         ]
            //     ],
            //     'attributes' => 'required'
            // ],
            [
                'title'      => 'تعداد',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'class'      => 'col-md-4 col-6',
                'default'    => '10',
                'attributes' => 'required'
            ],
            // [
            //     'title'      => 'انتخاب دسته بندی ها (اختیاری)',
            //     'key'        => 'categories',
            //     'input-type' => 'product_categories',
            //     'class'      => 'col-md-9',
            // ],
            // [
            //     'title'      => 'شامل محصولات زیر دسته ها',
            //     'key'        => 'sub_category_products',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-3',
            //     'options'    => [
            //         [
            //             'value' => 'yes',
            //             'title' => 'بله'
            //         ],
            //         [
            //             'value' => 'no',
            //             'title' => 'خیر'
            //         ]
            //     ],
            // ],
        ],
        'rules' => [
            // 'products_type'    => 'required|in:all,discount,special',
            // 'inventory_status' => 'required|in:all,available,unavailable',
            'sort_type'        => 'required|in:desc,profile_views',
            // 'order_by_stock'   => 'required|in:yes,no',
            'link'             => 'nullable|string',
            'link_title'       => 'nullable|string',
            'number'           => 'required',
            // 'categories'       => 'nullable|array',
            // 'categories.*'     => 'exists:categories,id',
        ]
    ],

    'products-colorful-block' => [
        'title' => 'کادر آرامگاه با پس زمینه',
        'image' => 'widgets/special.jpg',
        'options' => [
            // [
            //     'title'      => 'نوع محصولات',
            //     'key'        => 'products_type',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'all',
            //             'title' => 'همه'
            //         ],
            //         [
            //             'value' => 'discount',
            //             'title' => 'تخفیف خورده'
            //         ],
            //         [
            //             'value' => 'special',
            //             'title' => 'پیشنهاد ویژه'
            //         ],
            //     ],
            //     'attributes' => 'required'
            // ],
            // [
            //     'title'      => 'وضعیت موجودی',
            //     'key'        => 'inventory_status',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'all',
            //             'title' => 'همه'
            //         ],
            //         [
            //             'value' => 'available',
            //             'title' => 'موجود'
            //         ],
            //         [
            //             'value' => 'unavailable',
            //             'title' => 'نا موجود'
            //         ],
            //     ],
            //     'attributes' => 'required'
            // ],
            [
                'title'      => 'نوع مرتب سازی',
                'key'        => 'sort_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'desc',
                        'title' => 'جدیدترین'
                    ],
                    // [
                    //     'value' => 'sell',
                    //     'title' => 'پرفروش ترین'
                    // ],
                    [
                        'value' => 'profile_views',
                        'title' => 'پربازدید ترین'
                    ],
                    // [
                    //     'value' => 'cheapest',
                    //     'title' => 'ارزانترین'
                    // ],
                    // [
                    //     'value' => 'expensivest',
                    //     'title' => 'گرانترین'
                    // ],
                ],
                'attributes' => 'required'
            ],
            // [
            //     'title'      => 'نمایش محصولات موجود در اول',
            //     'key'        => 'order_by_stock',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-4 col-6',
            //     'options'    => [
            //         [
            //             'value' => 'yes',
            //             'title' => 'بله'
            //         ],
            //         [
            //             'value' => 'no',
            //             'title' => 'خیر'
            //         ]
            //     ],
            //     'attributes' => 'required'
            // ],
            [
                'title'      => 'لینک',
                'key'        => 'link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عنوان لینک',
                'key'        => 'link_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'رنگ پس زمینه کادر',
                'key'        => 'block_color',
                'input-type' => 'input',
                'default'    => '#ef394e',
                'type'       => 'color',
                'class'      => 'col-md-4 col-6',
            ],
            // [
            //     'title'      => 'تصویر',
            //     'key'        => 'image',
            //     'input-type' => 'file',
            //     'type'       => 'file',
            //     'class'      => 'col-md-4 col-6',
            //     'attributes' => 'accept="image/*"',
            //     'help'       => 'بهترین اندازه 850 * 500'
            // ],
            [
                'title'      => 'تعداد',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'class'      => 'col-md-4 col-6',
                'default'    => '10',
                'attributes' => 'required'
            ],
            // [
            //     'title'      => 'انتخاب دسته بندی ها (اختیاری)',
            //     'key'        => 'categories',
            //     'input-type' => 'product_categories',
            //     'class'      => 'col-md-9',
            // ],
            // [
            //     'title'      => 'شامل محصولات زیر دسته ها',
            //     'key'        => 'sub_category_products',
            //     'input-type' => 'select',
            //     'class'      => 'col-md-3',
            //     'options'    => [
            //         [
            //             'value' => 'yes',
            //             'title' => 'بله'
            //         ],
            //         [
            //             'value' => 'no',
            //             'title' => 'خیر'
            //         ]
            //     ],
            // ],
        ],
        'rules' => [
            // 'products_type'    => 'required|in:all,discount,special',
            // 'inventory_status' => 'required|in:all,available,unavailable',
            'sort_type'        => 'required|in:desc,profile_views',
            // 'order_by_stock'   => 'required|in:yes,no',
            'link'             => 'nullable|string',
            'link_title'       => 'nullable|string',
            'block_color'      => 'nullable|string',
            'number'           => 'required',
            // 'image'            => 'nullable|image',
        ]
    ],

    'middle-banners' => [
        'title' => 'بنر دوتایی',
        'image' => 'widgets/banner.jpg',
        'options' => [
            [
                'title'      => 'تعداد قابل نمایش',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'default'    => '2',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'required'
            ],
            [
                'title'      => 'ترتیب نمایش',
                'key'        => 'ordering',
                'input-type' => 'select',
                'class'      => 'col-md-4',
                'options'    => [
                    [
                        'value' => 'asc',
                        'title' => 'صعودی'
                    ],
                    [
                        'value' => 'desc',
                        'title' => 'نزولی'
                    ]
                ],
            ],

        ],
        'rules' => [
            'number' => 'required',
        ]
    ],


    'sevices-sliders' => [
        'title' => 'اسلایدر خدمات',
        'image' => 'widgets/support.jpg',
        'options' => [
            [
                'title'      => 'تعداد قابل نمایش',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'default'    => '4',
                'class'      => 'col-md-4 col-6',
                'attributes' => 'required'
            ]

        ],
        'rules' => [
            'number' => 'required',
        ]
    ],

    'categories' => [
        'title' => 'دسته بندی محصولات',
        'image' => 'widgets/categories.png',
        'options' => [
            [
                'title'      => 'انتخاب دسته بندی ها',
                'key'        => 'categories',
                'input-type' => 'product_categories',
                'class'      => 'col-md-12',
            ],

        ],
        'rules' => [
            'categories'      => 'required|array',
            'categories'      => 'required',
            'categories.*'    => 'exists:categories,id',
        ]
    ],

    'posts' => [
        'title' => 'نوشته های وبلاگ',
        'image' => 'widgets/posts.png',
        'options' => [
            [
                'title'      => 'عنوان',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'نوع مرتب سازی',
                'key'        => 'sort_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'latest',
                        'title' => 'جدیدترین'
                    ],
                    [
                        'value' => 'view',
                        'title' => 'پربازدید ترین'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'لینک',
                'key'        => 'link',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'عنوان لینک',
                'key'        => 'link_title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'تعداد',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'class'      => 'col-md-4 col-6',
                'default'    => '10',
                'attributes' => 'required'
            ],
            [
                'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                'key'        => 'categories',
                'input-type' => 'post_categories',
                'class'      => 'col-md-9',
            ],
        ],
        'rules' => [
            'sort_type'        => 'required|in:latest,view',
            'link'             => 'nullable|string',
            'link_title'       => 'nullable|string',
            'number'           => 'required',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,id',
        ]
    ],
    'products-slider-block' => [
        'title' => 'اسلایدر جدید محصولات',
        'image' => 'widgets/products-default.jpg',
        'options' => [
            [
                'title'      => 'عنوان',
                'key'        => 'title',
                'input-type' => 'input',
                'type'       => 'text',
                'class'      => 'col-md-4 col-6',
            ],
            [
                'title'      => 'نوع محصولات',
                'key'        => 'products_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'all',
                        'title' => 'همه'
                    ],
                    [
                        'value' => 'discount',
                        'title' => 'تخفیف خورده'
                    ],
                    [
                        'value' => 'special',
                        'title' => 'پیشنهاد ویژه'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'وضعیت موجودی',
                'key'        => 'inventory_status',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'all',
                        'title' => 'همه'
                    ],
                    [
                        'value' => 'available',
                        'title' => 'موجود'
                    ],
                    [
                        'value' => 'unavailable',
                        'title' => 'نا موجود'
                    ],
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'نوع مرتب سازی',
                'key'        => 'sort_type',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'latest',
                        'title' => 'جدیدترین'
                    ],
                    [
                        'value' => 'sell',
                        'title' => 'پرفروش ترین'
                    ],
                    [
                        'value' => 'view',
                        'title' => 'پربازدید ترین'
                    ],
                    [
                        'value' => 'cheapest',
                        'title' => 'ارزانترین'
                    ],
                    [
                        'value' => 'expensivest',
                        'title' => 'گرانترین'
                    ],
                ],
                'attributes' => 'required'
            ],

            [
                'title'      => 'نمایش محصولات موجود در اول',
                'key'        => 'order_by_stock',
                'input-type' => 'select',
                'class'      => 'col-md-4 col-6',
                'options'    => [
                    [
                        'value' => 'yes',
                        'title' => 'بله'
                    ],
                    [
                        'value' => 'no',
                        'title' => 'خیر'
                    ]
                ],
                'attributes' => 'required'
            ],
            [
                'title'      => 'تعداد',
                'key'        => 'number',
                'input-type' => 'input',
                'type'       => 'number',
                'class'      => 'col-md-4 col-6',
                'default'    => '10',
                'attributes' => 'required'
            ],
            [
                'title'      => 'انتخاب دسته بندی ها (اختیاری)',
                'key'        => 'categories',
                'input-type' => 'product_categories',
                'class'      => 'col-md-9',
            ],
            [
                'title'      => 'شامل محصولات زیر دسته ها',
                'key'        => 'sub_category_products',
                'input-type' => 'select',
                'class'      => 'col-md-3',
                'options'    => [
                    [
                        'value' => 'yes',
                        'title' => 'بله'
                    ],
                    [
                        'value' => 'no',
                        'title' => 'خیر'
                    ]
                ],
            ],
        ],
        'rules' => [
            'products_type'    => 'required|in:all,discount,special',
            'inventory_status' => 'required|in:all,available,unavailable',
            'sort_type'        => 'required|in:latest,sell,view,cheapest,expensivest',
            'order_by_stock'   => 'required|in:yes,no',
            'number'           => 'required',
            'categories'       => 'nullable|array',
            'categories.*'     => 'exists:categories,id',
        ]
    ],
];
