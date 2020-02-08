<?php

use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        \DB::table('users')->insert([
            'name' => 'osama said',
            'email' => 'osama.saieed@gmail.com',
            'email_verified_at' => now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);

        \DB::table('admins')->insert([
            'name' => 'osama said',
            'email' => 'osama.saieed@gmail.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);


        for($i = 1 ; $i <= 5; $i++) {
            \DB::table('products')->insert([
                'title' => $faker->name,
                'description' => $faker->sentence(10),
                'price' => $faker->randomNumber(3),
                'discount_amount' => $faker->randomNumber(2),
                'discount_type' => $faker->randomElement(['percentage', 'amount']),
            ]);
        }

        \DB::table('bundles')->insert([
            'bundle_id' => 2,
            'products' => '1',
        ]);


        \DB::table('bundles')->insert([
            'bundle_id' => 3,
            'products' => '1,2',
        ]);

        \DB::table('bundles')->insert([
            'bundle_id' => 4,
            'products' => '1,2,3',
        ]);

        for($i = 1 ; $i <= 5; $i++) {
            \DB::table('photos')->insert([
                'photoable_type' => 'App\Product',
                'photoable_id' => $i,
                'url'          => '[imageurl] i am using storage i cant load external images here',
                'type'         => 'cover'
            ]);
        }

        for($i = 1 ; $i <= 5; $i++) {
            \DB::table('photos')->insert([
                'photoable_type' => 'App\Product',
                'photoable_id' => $i,
                'url'          => '[imageurl] i am using storage i cant load external images here',
                'type'         => 'photo'
            ]);

            \DB::table('photos')->insert([
                'photoable_type' => 'App\Product',
                'photoable_id' => $i,
                'url'          => '[imageurl] i am using storage i cant load external images here',
                'type'         => 'photo'
            ]);
        }





    }
}
