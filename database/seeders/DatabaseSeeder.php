<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Shop;
use App\Models\Stock;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            AdminSeeder::class,
            OwnerSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
        ]);
        Shop::factory(10)->create();
        Stock::factory(100)->create(); // ->Product::factory()->Image::factory()
    }
}
