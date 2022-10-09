<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('primary_categories')->insert([
            [
                'name' => '家電',
                'sort_order' => 1,
            ],
            [
                'name' => 'ファッション',
                'sort_order' => 2,
            ],
            [
                'name' => 'アウトドア',
                'sort_order' => 3,
            ],

        ]);

        DB::table('secondary_categories')->insert([
            [
                'name' => '冷蔵庫',
                'sort_order' => 1,
                'primary_category_id' => 1,
            ],
            [
                'name' => '洗濯機',
                'sort_order' => 2,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'エアコン',
                'sort_order' => 3,
                'primary_category_id' => 1,
            ],
            [
                'name' => 'アウター',
                'sort_order' => 4,
                'primary_category_id' => 2,
            ],
            [
                'name' => 'パンツ',
                'sort_order' => 5,
                'primary_category_id' => 2,
            ],
            [
                'name' => 'シューズ',
                'sort_order' => 6,
                'primary_category_id' => 2,
            ],
            [
                'name' => 'スポーツ',
                'sort_order' => 7,
                'primary_category_id' => 3,
            ],
            [
                'name' => 'キャンプ',
                'sort_order' => 8,
                'primary_category_id' => 3,
            ],
        ]);
    }
}
