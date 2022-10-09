<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ShopSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shops')->insert([
            [
                'owner_id' => 1,
                'name' => 'ShopName1',
                'information' => 'ShopInfo',
                'filename' => '',
                'is_selling' => true,
                'created_at' => '2000/01/01 00:00:00',
            ],
            [
                'owner_id' => 1,
                'name' => 'ShopName2',
                'information' => 'ShopInfo',
                'filename' => '',
                'is_selling' => true,
                'created_at' => '2000/01/01 00:00:00',
            ],
            [
                'owner_id' => 1,
                'name' => 'ShopName3',
                'information' => 'ShopInfo',
                'filename' => '',
                'is_selling' => true,
                'created_at' => '2000/01/01 00:00:00',
            ],
            [
                'owner_id' => 2,
                'name' => 'ShopName4',
                'information' => 'ShopInfo',
                'filename' => '',
                'is_selling' => true,
                'created_at' => '2000/01/01 00:00:00',
            ],
        ]);
    }
}
