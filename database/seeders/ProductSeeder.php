<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        $image_id = 1; // 連番でID
        // shop1~4にsecondary_category1~8を設定
        foreach(range(1, 4) as $shop_id) { foreach(range(1, 8) as $secondary_category_id) {
                $data[] = [
                    'shop_id' => $shop_id,
                    'secondary_category_id' => $secondary_category_id,
                    'image1' => $image_id++,
                    'created_at' => '2000/01/01 00:00:00',
                ];
            }
        }

        DB::table('products')->insert($data);
    }
}
