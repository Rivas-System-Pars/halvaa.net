<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Seeder;

class RelativesTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = now(); // اگر timestamps را حذف کردی، این ستون‌ها را هم از insert بردار

        $items = [
            // خانوادهٔ درجه یک
            ['title' => 'پدر',        'slug' => 'father'],
            ['title' => 'مادر',       'slug' => 'mother'],
            ['title' => 'همسر',       'slug' => 'spouse'],
            ['title' => 'پسر',        'slug' => 'son'],
            ['title' => 'دختر',       'slug' => 'daughter'],
            ['title' => 'برادر',      'slug' => 'brother'],
            ['title' => 'خواهر',      'slug' => 'sister'],

            // پدربزرگ/مادربزرگ و نوه
            ['title' => 'پدربزرگ',    'slug' => 'grandfather'],
            ['title' => 'مادربزرگ',   'slug' => 'grandmother'],
            ['title' => 'نوه',        'slug' => 'grandchild'],

            // عمو/عمه/دایی/خاله
            ['title' => 'عمو',        'slug' => 'paternal-uncle'],
            ['title' => 'عمه',        'slug' => 'paternal-aunt'],
            ['title' => 'دایی',       'slug' => 'maternal-uncle'],
            ['title' => 'خاله',       'slug' => 'maternal-aunt'],

            // پسر/دخترِ عمو/عمه/دایی/خاله
            ['title' => 'پسرعمو',     'slug' => 'male-cousin-paternal-uncle'],
            ['title' => 'دخترعمو',    'slug' => 'female-cousin-paternal-uncle'],
            ['title' => 'پسرعمه',     'slug' => 'male-cousin-paternal-aunt'],
            ['title' => 'دخترعمه',    'slug' => 'female-cousin-paternal-aunt'],
            ['title' => 'پسردایی',    'slug' => 'male-cousin-maternal-uncle'],
            ['title' => 'دختردایی',   'slug' => 'female-cousin-maternal-uncle'],
            ['title' => 'پسرخاله',    'slug' => 'male-cousin-maternal-aunt'],
            ['title' => 'دخترخاله',   'slug' => 'female-cousin-maternal-aunt'],

            // نسبت‌های سببی
            ['title' => 'پدرزن',      'slug' => 'father-in-law-wife'],
            ['title' => 'مادرزن',     'slug' => 'mother-in-law-wife'],
            ['title' => 'پدرشوهر',    'slug' => 'father-in-law-husband'],
            ['title' => 'مادرشوهر',   'slug' => 'mother-in-law-husband'],
            ['title' => 'برادرزن',    'slug' => 'brother-in-law-wife'],
            ['title' => 'خواهرزن',    'slug' => 'sister-in-law-wife'],
            ['title' => 'برادرشوهر',  'slug' => 'brother-in-law-husband'],
            ['title' => 'خواهرشوهر',  'slug' => 'sister-in-law-husband'],
            ['title' => 'داماد',      'slug' => 'son-in-law'],
            ['title' => 'عروس',       'slug' => 'daughter-in-law'],
            ['title' => 'باجناق',     'slug' => 'bajnagh'],
            ['title' => 'جاری',       'slug' => 'jari'],

            // ناپدری/نامادری و غیره
            ['title' => 'ناپدری',     'slug' => 'stepfather'],
            ['title' => 'نامادری',    'slug' => 'stepmother'],

            // متفرقهٔ پرکاربرد
            ['title' => 'معرف',       'slug' => 'referrer'],
            ['title' => 'همکار',      'slug' => 'colleague'],
            ['title' => 'دوست',       'slug' => 'friend'],
        ];

        // اگر timestamps داری:
        $items = array_map(function ($row) use ($now) {
            return $row + ['ordering' => 0, 'is_active' => true, 'created_at' => $now, 'updated_at' => $now];
        }, $items);

        DB::table('relatives_types')->insert($items);
        }
}
