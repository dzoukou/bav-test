<?php

namespace Database\Seeders;

use App\Models\Product;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();
        if (Product::first()) return;
        $data = collect();
        for ($i = 0; $i < 100; $i++) {
            $data->add([
                'name' => $faker->name,
                'description' => $faker->text,
                'price' => $faker->randomNumber(),
                'in_stock' => $faker->boolean,
                'published' => $faker->boolean,
                'is_service' => $faker->boolean,
                'discount' => $faker->boolean,
                'created_at' => $faker->dateTime,
                'updated_at' => $faker->dateTime
            ]);
        }
        Product::insert($data->toArray());
    }
}
