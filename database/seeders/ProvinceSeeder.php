<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
               $provinces = [
            ['id' => 1, 'name' => 'تهران'],
            ['id' => 2, 'name' => 'گيلان'],
            ['id' => 3, 'name' => 'آذربايجان شرقي'],
            ['id' => 4, 'name' => 'خوزستان'],
            ['id' => 5, 'name' => 'فارس'],
            ['id' => 6, 'name' => 'اصفهان'],
            ['id' => 7, 'name' => 'خراسان رضوي'],
            ['id' => 8, 'name' => 'قزوين'],
            ['id' => 9, 'name' => 'سمنان'],
            ['id' => 10, 'name' => 'قم'],
            ['id' => 11, 'name' => 'مركزي'],
            ['id' => 12, 'name' => 'زنجان'],
            ['id' => 13, 'name' => 'مازندران'],
            ['id' => 14, 'name' => 'گلستان'],
            ['id' => 15, 'name' => 'اردبيل'],
            ['id' => 16, 'name' => 'آذربايجان غربي'],
            ['id' => 17, 'name' => 'همدان'],
            ['id' => 18, 'name' => 'كردستان'],
            ['id' => 19, 'name' => 'كرمانشاه'],
            ['id' => 20, 'name' => 'لرستان'],
            ['id' => 21, 'name' => 'بوشهر'],
            ['id' => 22, 'name' => 'كرمان'],
            ['id' => 23, 'name' => 'هرمزگان'],
            ['id' => 24, 'name' => 'چهارمحال و بختياري'],
            ['id' => 25, 'name' => 'يزد'],
            ['id' => 26, 'name' => 'سيستان و بلوچستان'],
            ['id' => 27, 'name' => 'ايلام'],
            ['id' => 28, 'name' => 'كهگيلويه و بويراحمد'],
            ['id' => 29, 'name' => 'خراسان شمالي'],
            ['id' => 30, 'name' => 'خراسان جنوبي'],
            ['id' => 31, 'name' => 'البرز'],
        ];

        $ordering = 1;

               foreach ($provinces as $index => $province) {
            Province::updateOrCreate(
                ['id' => $province['id']],
                [
                    'name' => $province['name'],
                    'name_en' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'ordering' => $index + 1,
                    'is_active' => true,
                    'deleted_at' => null,
                ]
            );
        }
    }
}
