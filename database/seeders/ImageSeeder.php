<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use function Ramsey\Uuid\v1;

class ImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [];
        // owner1~3 に imageを1~24ずつ作成 ※ filename を空白とすることで NO IMAGE に固定
        foreach(range(1, 3) as $owner_id) { foreach(range(1, 24) as $index) {
                $data[] = [
                    'owner_id' => $owner_id,
                    'filename' => 'sample.jpg',
                    'title' => 'sample' . $owner_id. '-' . $index,
                    'created_at' => '2000/01/01 00:00:00',
                ];
            }
        }

        DB::table('images')->insert($data);
    }
}
