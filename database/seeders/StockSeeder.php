<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('t_stocks')->insert([
            [
                'product_id' => 1,
                'type' => 1,
                'quantity' => 5,
                'created_at' => '2000/01/01 00:00:00',
            ],
            [
                'product_id' => 1,
                'type' => 1,
                'quantity' => -2,
                'created_at' => '2000/01/01 00:00:00',
            ],
        ]);
    }
}
