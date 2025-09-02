<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
//        $this->_generateSuperAdmin();
//        $this->call(ProvinceSeeder::class);
//        $this->call(CitySeeder::class);
//        $this->call(PermissionSeeder::class);
//        $this->call(RoleSeeder::class);
//        $this->call(MenuSeeder::class);
//        $this->call(CategorySeeder::class);
//        $this->call(SliderSeeder::class);
//        $this->call(BannerSeeder::class);
//        $this->call(ProductSeeder::class);
//        $this->call(AttributeSeeder::class);
//        $this->call(StaticFilterSeeder::class);
    }

    /**
     * @return void
     */
    private function _generateSuperAdmin(): void
    {
        /** @var User $user */
        $user = User::query()->firstOrCreate([
            'username' => "admin",
        ],[
            'first_name' => "admin",
            'last_name' => "admini",
            'level' => "admin",
            'username' => "admin",
            'password' => bcrypt("admin"),
        ]);
        $user->roles()->sync(Role::query()->oldest()->first()->id);
    }
}
